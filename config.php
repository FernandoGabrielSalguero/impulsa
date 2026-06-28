<?php

date_default_timezone_set('America/Argentina/Buenos_Aires');

function loadEnv($path) {
    if (!file_exists($path)) return;

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) continue;
        list($name, $value) = explode('=', $line, 2);
        putenv(trim($name) . '=' . trim($value));
    }
}

loadEnv(__DIR__ . '/.env');

try {
    $dbHost = getenv('DB_HOST');
    $dbPort = getenv('DB_PORT');

    if (strpos($dbHost, ':') !== false) {
        list($hostOnly, $hostPort) = explode(':', $dbHost, 2);
        $dbHost = $hostOnly;
        if (!$dbPort) {
            $dbPort = $hostPort;
        }
    }

    if (!$dbPort) {
        $dbPort = '3306';
    }

    $pdo = new PDO(
        'mysql:host=' . $dbHost . ';port=' . $dbPort . ';dbname=' . getenv('DB_NAME'),
        getenv('DB_USER'),
        getenv('DB_PASS')
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Error de conexión: ' . $e->getMessage());
}

function obtenerValorSesion($clave)
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        return null;
    }
    return $_SESSION[$clave] ?? null;
}

function registrarAuditoria(PDO $pdo, array $data)
{
    $evento = isset($data['evento']) ? trim((string) $data['evento']) : '';
    if ($evento === '') {
        return false;
    }

    $usuarioId = $data['usuario_id'] ?? obtenerValorSesion('usuario_id');
    $usuarioLogin = $data['usuario_login'] ?? obtenerValorSesion('usuario');
    $rol = $data['rol'] ?? obtenerValorSesion('rol');
    $url = $data['url'] ?? ($_SERVER['REQUEST_URI'] ?? null);
    $metodo = $data['metodo'] ?? ($_SERVER['REQUEST_METHOD'] ?? null);
    $ip = $data['ip'] ?? ($_SERVER['REMOTE_ADDR'] ?? null);
    $userAgent = $data['user_agent'] ?? ($_SERVER['HTTP_USER_AGENT'] ?? null);
    $datos = $data['datos'] ?? null;

    if (is_array($datos) || is_object($datos)) {
        $datos = json_encode($datos, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    if ($userAgent !== null) {
        $userAgent = substr((string) $userAgent, 0, 255);
    }

    $sql = "INSERT INTO Auditoria_Eventos
            (Usuario_Id, Usuario_Login, Rol, Evento, Modulo, Url, Metodo, Entidad, Entidad_Id, Estado,
             Codigo_Http, Ip, User_Agent, Datos, Creado_En)
            VALUES
            (:usuario_id, :usuario_login, :rol, :evento, :modulo, :url, :metodo, :entidad, :entidad_id, :estado,
             :codigo_http, :ip, :user_agent, :datos, NOW())";

    try {
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            'usuario_id' => $usuarioId,
            'usuario_login' => $usuarioLogin,
            'rol' => $rol,
            'evento' => $evento,
            'modulo' => $data['modulo'] ?? null,
            'url' => $url,
            'metodo' => $metodo,
            'entidad' => $data['entidad'] ?? null,
            'entidad_id' => $data['entidad_id'] ?? null,
            'estado' => $data['estado'] ?? null,
            'codigo_http' => $data['codigo_http'] ?? null,
            'ip' => $ip,
            'user_agent' => $userAgent,
            'datos' => $datos,
        ]);
    } catch (Exception $e) {
        return false;
    }
}

function obtenerAvatarUrl(?string $avatarPath): ?string
{
    $avatarPath = trim((string) $avatarPath);
    if ($avatarPath === '') {
        return null;
    }

    return '/' . ltrim(str_replace('\\', '/', $avatarPath), '/');
}

function obtenerInicialAvatar(?string $label): string
{
    $label = trim((string) $label);
    if ($label === '') {
        return '?';
    }

    return mb_strtoupper(mb_substr($label, 0, 1));
}

function obtenerBaseAppUrl(): string
{
    $appUrl = rtrim((string) (getenv('APP_URL') ?: ''), '/');
    if ($appUrl !== '') {
        return $appUrl;
    }

    $scriptName = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));
    $scriptDir = str_replace('\\', '/', dirname($scriptName));

    if ($scriptDir === '.' || $scriptDir === '/') {
        return '';
    }

    foreach (['/auth', '/api', '/assets', '/impulsa_emprende'] as $marker) {
        $markerPos = strpos($scriptDir, $marker);
        if ($markerPos !== false) {
            $scriptDir = substr($scriptDir, 0, $markerPos);
            break;
        }
    }

    return rtrim($scriptDir, '/');
}

function obtenerFaviconHref(string $version = '20260607'): string
{
    $baseUrl = obtenerBaseAppUrl();
    $candidatos = [
        '/Favicon.ico',
        '/impulsa_emprende/favicon.ico',
        '/assets/impulsa_material/Favicon.ico',
    ];

    foreach ($candidatos as $rutaPublica) {
        $rutaLocal = __DIR__ . str_replace('/', DIRECTORY_SEPARATOR, $rutaPublica);
        if (is_file($rutaLocal)) {
            return ($baseUrl !== '' ? $baseUrl : '') . $rutaPublica . '?v=' . rawurlencode($version);
        }
    }

    return ($baseUrl !== '' ? $baseUrl : '') . '/Favicon.ico?v=' . rawurlencode($version);
}

function obtenerImpulsaMaterialCdnBaseUrl(): string
{
    $cdnBaseUrl = trim((string) (getenv('IMPULSA_MATERIAL_CDN_BASE_URL') ?: ''));
    if ($cdnBaseUrl === '') {
        $cdnBaseUrl = trim((string) (getenv('IMPULSA_CDN_BASE_URL') ?: ''));
    }
    if ($cdnBaseUrl === '') {
        $cdnBaseUrl = 'https://impulsagroup.com';
    }

    return rtrim($cdnBaseUrl, '/');
}

function obtenerImpulsaMaterialCssHref(): string
{
    return obtenerImpulsaMaterialCdnBaseUrl() . '/assets/impulsa_material/css/material.css';
}

function obtenerImpulsaMaterialJsSrc(): string
{
    return obtenerImpulsaMaterialCdnBaseUrl() . '/assets/impulsa_material/js/material.js';
}

function obtenerImpulsaMaterialValidacionesJsSrc(): string
{
    return obtenerImpulsaMaterialCdnBaseUrl() . '/assets/impulsa_material/js/material-validaciones.js';
}

function renderImpulsaMaterialFonts(): string
{
    return <<<'HTML'
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,400,0,0&display=swap" rel="stylesheet">
HTML;
}

function renderBotonPerfil(?string $avatarPath): string
{
    $avatarUrl = obtenerAvatarUrl($avatarPath);
    $buttonAttributes = 'class="btn-icon" id="btn-perfil" aria-label="Mi perfil" title="Mi perfil"';

    if ($avatarUrl) {
        $avatarUrlEscaped = htmlspecialchars($avatarUrl, ENT_QUOTES, 'UTF-8');
        return sprintf(
            '<button %s><img src="%s" alt="Avatar del usuario" style="width:24px;height:24px;object-fit:cover;border-radius:50%%;display:block;"></button>',
            $buttonAttributes,
            $avatarUrlEscaped
        );
    }

    return sprintf(
        '<button %s><span class="material-icons">account_circle</span></button>',
        $buttonAttributes
    );
}
