<?php

declare(strict_types=1);

require_once __DIR__ . '/../_shared/api_integration_helpers.php';

const VISITS_TABLE = 'visit_user_page';

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
    $integracion = apiValidarIntegracion($pdo, $datos['public_key']);
    registrarVisita($pdo, $integracion['id'], $datos['page']);
    apiRegistrarUltimoUsoIntegracion($pdo, $integracion['id']);

    apiResponderJson(201, true, 'Visita registrada correctamente', [
        'integration_id' => $integracion['id'],
    ]);
} catch (InvalidArgumentException $exception) {
    apiResponderJson(422, false, $exception->getMessage());
} catch (RuntimeException $exception) {
    $codigo = $exception->getCode();
    $codigoHttp = ($codigo >= 400 && $codigo <= 599) ? $codigo : 500;

    if ($codigoHttp >= 500) {
        error_log('Error interno en API/visit_user_page/index.php: ' . $exception->getMessage());
        apiResponderJson(500, false, 'Error interno del servidor');
    }

    apiResponderJson($codigoHttp, false, $exception->getMessage());
} catch (Throwable $exception) {
    error_log('Error no controlado en API/visit_user_page/index.php: ' . $exception->getMessage());
    apiResponderJson(500, false, 'Error interno del servidor');
}

/**
 * Valida solo el nombre de pagina. Cualquier campo extra se ignora.
 *
 * @param array<string, mixed> $payload
 * @return array{public_key: string, page: string}
 */
function validarPayload(array $payload): array
{
    if (!array_key_exists('public_key', $payload) || !is_string($payload['public_key'])) {
        throw new InvalidArgumentException('El campo public_key es obligatorio.');
    }

    $publicKey = trim($payload['public_key']);

    if ($publicKey === '') {
        throw new InvalidArgumentException('El campo public_key es obligatorio.');
    }

    if (!array_key_exists('page', $payload) || $payload['page'] === null) {
        throw new InvalidArgumentException('El campo page es obligatorio.');
    }

    if (!is_string($payload['page'])) {
        throw new InvalidArgumentException('El campo page debe ser texto.');
    }

    $page = trim($payload['page']);

    if ($page === '') {
        throw new InvalidArgumentException('El campo page es obligatorio.');
    }

    if (apiLongitudTexto($page) > 150) {
        throw new InvalidArgumentException('El campo page no puede superar 150 caracteres.');
    }

    return [
        'public_key' => $publicKey,
        'page' => $page,
    ];
}

/**
 * Inserta una visita nueva. No hay lectura, edicion, borrado ni listado.
 */
function registrarVisita(PDO $pdo, int $integracionId, string $page): void
{
    $sql = 'INSERT INTO ' . VISITS_TABLE . ' (page, api_integration_id, visited_at)
            VALUES (:page, :api_integration_id, CURRENT_TIMESTAMP)';

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':page' => $page,
            ':api_integration_id' => $integracionId,
        ]);
    } catch (PDOException $exception) {
        error_log('Error insertando visita en ' . VISITS_TABLE . ': ' . $exception->getMessage());
        throw new RuntimeException('Error interno del servidor', 500);
    }
}
