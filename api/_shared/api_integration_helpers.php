<?php

declare(strict_types=1);

const API_ENV_PATH = __DIR__ . '/../../.env';
const API_INTEGRATIONS_TABLE = 'api_integrations';

function apiCargarEnv(string $ruta = API_ENV_PATH): void
{
    if (!is_file($ruta) || !is_readable($ruta)) {
        throw new RuntimeException('Configuracion del servidor no disponible.', 500);
    }

    $lineas = file($ruta, FILE_IGNORE_NEW_LINES);

    if ($lineas === false) {
        throw new RuntimeException('No se pudo leer la configuracion del servidor.', 500);
    }

    foreach ($lineas as $linea) {
        $linea = trim($linea);

        if ($linea === '' || strpos($linea, '#') === 0 || strpos($linea, '=') === false) {
            continue;
        }

        [$clave, $valor] = explode('=', $linea, 2);
        $clave = trim($clave);
        $valor = trim(trim($valor), "\"'");

        if ($clave === '') {
            continue;
        }

        putenv($clave . '=' . $valor);
        $_ENV[$clave] = $valor;
        $_SERVER[$clave] = $valor;
    }
}

function apiConfigurarCorsBase(): void
{
    apiConfigurarCorsPersonalizado(['POST', 'OPTIONS'], ['Content-Type', 'X-API-SECRET']);
}

/**
 * @param array<int, string> $metodos
 * @param array<int, string> $headers
 */
function apiConfigurarCorsPersonalizado(array $metodos, array $headers, string $contentType = 'application/json; charset=utf-8'): void
{
    $origin = trim((string) ($_SERVER['HTTP_ORIGIN'] ?? ''));

    header('Content-Type: ' . $contentType);
    header('Access-Control-Allow-Methods: ' . implode(', ', $metodos));
    header('Access-Control-Allow-Headers: ' . implode(', ', $headers));
    header('Access-Control-Max-Age: 600');

    if ($origin !== '') {
        header('Access-Control-Allow-Origin: ' . $origin);
        header('Vary: Origin');
    }
}

function apiValidarContentTypeJson(): void
{
    $contentType = $_SERVER['CONTENT_TYPE'] ?? $_SERVER['HTTP_CONTENT_TYPE'] ?? '';

    if (stripos($contentType, 'application/json') === false) {
        throw new RuntimeException('Content-Type invalido. Usa application/json.', 400);
    }
}

/**
 * @return array<string, mixed>
 */
function apiObtenerPayloadJson(): array
{
    $body = file_get_contents('php://input');

    if ($body === false || trim($body) === '') {
        throw new RuntimeException('JSON invalido', 400);
    }

    $payload = json_decode($body, true);

    if (json_last_error() !== JSON_ERROR_NONE || !is_array($payload)) {
        throw new RuntimeException('JSON invalido', 400);
    }

    return $payload;
}

function apiLongitudTexto(string $texto): int
{
    if (function_exists('mb_strlen')) {
        return mb_strlen($texto, 'UTF-8');
    }

    return strlen($texto);
}

function apiObtenerEnv(string $clave, string $default = ''): string
{
    $valor = getenv($clave);

    if ($valor === false) {
        return $default;
    }

    return (string) $valor;
}

function apiCrearConexionPdo(): PDO
{
    $host = apiObtenerEnv('DB_HOST');
    $puerto = apiObtenerEnv('DB_PORT', '');
    $base = apiObtenerEnv('DB_NAME');
    $usuario = apiObtenerEnv('DB_USER');
    $password = apiObtenerEnv('DB_PASS', apiObtenerEnv('DB_PASSWORD', ''));

    if ($host === '' || $base === '' || $usuario === '') {
        throw new RuntimeException('Configuracion de base de datos incompleta.', 500);
    }

    if (strpos($host, ':') !== false) {
        [$hostSinPuerto, $puertoEnHost] = explode(':', $host, 2);
        $host = $hostSinPuerto;
        $puerto = $puerto !== '' ? $puerto : $puertoEnHost;
    }

    if ($puerto === '') {
        $puerto = '3306';
    }

    $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $host, $puerto, $base);

    try {
        return new PDO($dsn, $usuario, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    } catch (PDOException $exception) {
        error_log('Error conectando a MySQL en API compartida: ' . $exception->getMessage());
        throw new RuntimeException('Error interno del servidor', 500);
    }
}

/**
 * @return array{id:int, project_name:string, allowed_domain:string, public_key:string, secret_key_hash:?string, status:string}
 */
function apiValidarIntegracion(PDO $pdo, string $publicKey): array
{
    $publicKey = trim($publicKey);

    if ($publicKey === '') {
        throw new RuntimeException('La public_key es obligatoria.', 401);
    }

    $stmt = $pdo->prepare(
        'SELECT id, project_name, allowed_domain, public_key, secret_key_hash, status
         FROM ' . API_INTEGRATIONS_TABLE . '
         WHERE public_key = :public_key
         LIMIT 1'
    );
    $stmt->execute([':public_key' => $publicKey]);
    $integracion = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!is_array($integracion)) {
        throw new RuntimeException('Integracion API invalida o inexistente.', 401);
    }

    if (($integracion['status'] ?? '') !== 'active') {
        throw new RuntimeException('La integracion API se encuentra inactiva.', 403);
    }

    apiValidarOrigenIntegracion((string) ($integracion['allowed_domain'] ?? ''));
    apiValidarSecretSiCorresponde((string) ($integracion['secret_key_hash'] ?? ''));

    return [
        'id' => (int) ($integracion['id'] ?? 0),
        'project_name' => (string) ($integracion['project_name'] ?? ''),
        'allowed_domain' => (string) ($integracion['allowed_domain'] ?? ''),
        'public_key' => (string) ($integracion['public_key'] ?? ''),
        'secret_key_hash' => $integracion['secret_key_hash'] !== null ? (string) $integracion['secret_key_hash'] : null,
        'status' => (string) ($integracion['status'] ?? ''),
    ];
}

function apiValidarOrigenIntegracion(string $dominioPermitido): void
{
    $origin = trim((string) ($_SERVER['HTTP_ORIGIN'] ?? ''));

    if ($origin === '') {
        $referer = trim((string) ($_SERVER['HTTP_REFERER'] ?? ''));
        if ($referer !== '') {
            $origin = $referer;
        }
    }

    if ($origin === '') {
        return;
    }

    if (apiNormalizarOrigin($origin) !== apiNormalizarOrigin($dominioPermitido)) {
        throw new RuntimeException('Origin no permitido para esta integracion.', 403);
    }
}

function apiValidarSecretSiCorresponde(string $secretKeyHash): void
{
    $secretRecibida = trim((string) ($_SERVER['HTTP_X_API_SECRET'] ?? ''));

    if ($secretRecibida === '' || $secretKeyHash === '') {
        return;
    }

    if (!password_verify($secretRecibida, $secretKeyHash)) {
        throw new RuntimeException('Secret API invalida.', 401);
    }
}

function apiNormalizarOrigin(string $origin): string
{
    $origin = trim($origin);

    if ($origin === '') {
        return '';
    }

    $partes = parse_url($origin);

    if ($partes === false || empty($partes['scheme']) || empty($partes['host'])) {
        return rtrim(strtolower($origin), '/');
    }

    $normalizado = strtolower($partes['scheme']) . '://' . strtolower($partes['host']);

    if (!empty($partes['port'])) {
        $normalizado .= ':' . (int) $partes['port'];
    }

    return $normalizado;
}

function apiRegistrarUltimoUsoIntegracion(PDO $pdo, int $integracionId): void
{
    $stmt = $pdo->prepare(
        'UPDATE ' . API_INTEGRATIONS_TABLE . '
         SET last_used_at = NOW()
         WHERE id = :id'
    );
    $stmt->execute([':id' => $integracionId]);
}

/**
 * @param array<string, mixed> $extra
 */
function apiResponderJson(int $codigoHttp, bool $success, string $mensaje, array $extra = []): void
{
    http_response_code($codigoHttp);
    echo json_encode(
        array_merge([
            'success' => $success,
            'message' => $mensaje,
        ], $extra),
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE
    );
    exit;
}
