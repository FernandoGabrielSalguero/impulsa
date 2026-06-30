<?php

declare(strict_types=1);

require_once __DIR__ . '/../_shared/api_integration_helpers.php';
require_once __DIR__ . '/../../impulsa_emprende/mail/Mail.php';

try {
    apiCargarEnv();
    apiConfigurarCorsBase();

    $metodo = $_SERVER['REQUEST_METHOD'] ?? '';

    if ($metodo === 'OPTIONS') {
        http_response_code(204);
        exit;
    }

    if ($metodo !== 'POST') {
        apiResponderJson(405, false, 'Metodo no permitido. Usa POST.');
    }

    apiValidarContentTypeJson();

    $payload = apiObtenerPayloadJson();
    $datos = validarPayload($payload);

    $pdo = apiCrearConexionPdo();
    $integracion = apiValidarIntegracion($pdo, (string) $datos['public_key']);
    $consultaId = insertarConsulta($pdo, $integracion['id'], $datos);
    apiRegistrarUltimoUsoIntegracion($pdo, $integracion['id']);
    notificarPropietarioIntegracion($pdo, $integracion, $datos, $consultaId);

    apiResponderJson(201, true, 'Consulta enviada correctamente', [
        'integration_id' => $integracion['id'],
    ]);
} catch (InvalidArgumentException $exception) {
    apiResponderJson(422, false, $exception->getMessage());
} catch (RuntimeException $exception) {
    $codigo = $exception->getCode();
    $codigoHttp = ($codigo >= 400 && $codigo <= 599) ? $codigo : 500;

    if ($codigoHttp >= 500) {
        error_log('Error interno en API/contact_form_landing_page/index.php: ' . $exception->getMessage());
        apiResponderJson(500, false, 'Error interno del servidor');
    }

    apiResponderJson($codigoHttp, false, $exception->getMessage());
} catch (Throwable $exception) {
    error_log('Error no controlado en API/contact_form_landing_page/index.php: ' . $exception->getMessage());
    apiResponderJson(500, false, 'Error interno del servidor');
}

/**
 * Valida y normaliza solo los campos permitidos para insertar.
 *
 * @param array<string, mixed> $payload
 * @return array<string, string|null>
 */
function validarPayload(array $payload): array
{
    $publicKey = obtenerTexto($payload, 'public_key', true, 80);
    $page = obtenerTexto($payload, 'page', true, 150);
    $contactNombre = obtenerTexto($payload, 'contact_nombre', true, 150);
    $contactWhatsapp = obtenerTexto($payload, 'contact_whatsapp', false, 50);
    $contactEmail = obtenerTexto($payload, 'contact_email', false, 150);
    $contactDescription = obtenerTexto($payload, 'contact_description', false, null);
    $contactConsultation = obtenerTexto($payload, 'contact_consultation', false, 1000);

    if ($contactEmail !== null && !filter_var($contactEmail, FILTER_VALIDATE_EMAIL)) {
        throw new InvalidArgumentException('El campo contact_email debe ser un email valido.');
    }

    // Futuras mejoras: honeypot anti-spam y validaciones especificas por tipo de landing.
    return [
        'public_key' => $publicKey,
        'page' => $page,
        'contact_nombre' => $contactNombre,
        'contact_whatsapp' => $contactWhatsapp,
        'contact_email' => $contactEmail,
        'contact_description' => $contactDescription,
        'contact_consultation' => $contactConsultation,
    ];
}

/**
 * Obtiene un campo string, aplica trim y valida longitud maxima si corresponde.
 *
 * @param array<string, mixed> $payload
 */
function obtenerTexto(array $payload, string $campo, bool $requerido, ?int $maximo): ?string
{
    if (!array_key_exists($campo, $payload) || $payload[$campo] === null) {
        if ($requerido) {
            throw new InvalidArgumentException('El campo ' . $campo . ' es obligatorio.');
        }

        return null;
    }

    if (!is_string($payload[$campo])) {
        throw new InvalidArgumentException('El campo ' . $campo . ' debe ser texto.');
    }

    $valor = trim($payload[$campo]);

    if ($valor === '') {
        if ($requerido) {
            throw new InvalidArgumentException('El campo ' . $campo . ' es obligatorio.');
        }

        return null;
    }

    if ($maximo !== null && longitudTexto($valor) > $maximo) {
        throw new InvalidArgumentException('El campo ' . $campo . ' no puede superar ' . $maximo . ' caracteres.');
    }

    return $valor;
}

function longitudTexto(string $texto): int
{
    return apiLongitudTexto($texto);
}

/**
 * Inserta exclusivamente una consulta nueva. No hay lectura, edicion, borrado ni listado.
 *
 * @param array<string, string|null> $datos
 */
function insertarConsulta(PDO $pdo, int $integracionId, array $datos): int
{
    $sql = 'INSERT INTO forms_clients_contact
        (page, api_integration_id, contact_nombre, contact_whatsapp, contact_email, contact_description, contact_consultation, state)
        VALUES
        (:page, :api_integration_id, :contact_nombre, :contact_whatsapp, :contact_email, :contact_description, :contact_consultation, :state)';

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':page' => $datos['page'],
            ':api_integration_id' => $integracionId,
            ':contact_nombre' => $datos['contact_nombre'],
            ':contact_whatsapp' => $datos['contact_whatsapp'],
            ':contact_email' => $datos['contact_email'],
            ':contact_description' => $datos['contact_description'],
            ':contact_consultation' => $datos['contact_consultation'],
            ':state' => 'recibido',
        ]);
        return (int) $pdo->lastInsertId();
    } catch (PDOException $exception) {
        error_log('Error insertando consulta en forms_clients_contact: ' . $exception->getMessage());
        throw new RuntimeException('Error interno del servidor', 500);
    }
}

/**
 * @param array{id:int, project_name:string, allowed_domain:string, public_key:string, secret_key_hash:?string, status:string, user_auth_id:?int} $integracion
 * @param array<string, string|null> $datos
 */
function notificarPropietarioIntegracion(PDO $pdo, array $integracion, array $datos, int $consultaId): void
{
    $destinatario = apiObtenerDestinatarioIntegracion($pdo, (int) $integracion['id']);
    $GLOBALS['pdo'] = $pdo;

    $resultado = \SVE\Mail\Mailer::enviarNotificacionNuevoContactoApi([
        'integration_id' => (int) $integracion['id'],
        'project_name' => (string) ($integracion['project_name'] ?? ''),
        'allowed_domain' => (string) ($integracion['allowed_domain'] ?? ''),
        'owner_user_auth_id' => $destinatario['owner_user_auth_id'],
        'owner_email' => $destinatario['owner_email'],
        'owner_name' => $destinatario['owner_name'],
        'page' => (string) ($datos['page'] ?? ''),
        'contact_nombre' => (string) ($datos['contact_nombre'] ?? ''),
        'contact_email' => (string) ($datos['contact_email'] ?? ''),
        'contact_whatsapp' => (string) ($datos['contact_whatsapp'] ?? ''),
        'contact_description' => (string) ($datos['contact_description'] ?? ''),
        'contact_consultation' => (string) ($datos['contact_consultation'] ?? ''),
        'meta' => [
            'source' => 'api/contact_form_landing_page',
            'forms_clients_contact_id' => $consultaId,
            'integration_public_key' => (string) ($integracion['public_key'] ?? ''),
        ],
    ]);

    if (!($resultado['ok'] ?? false)) {
        error_log('No se pudo enviar la notificacion de contacto publico para la integracion #' . (int) $integracion['id'] . ': ' . (string) ($resultado['error'] ?? 'Error no informado.'));
    }
}
