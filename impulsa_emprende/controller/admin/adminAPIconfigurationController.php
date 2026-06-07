<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../auth/auth_helpers.php';
require_once __DIR__ . '/../../model/admin/adminAPIconfigurationModel.php';

$usuario = authRequiereRol('impulsa_administrador');
$usuarioCorreo = (string) ($usuario['correo'] ?? '');
$usuarioInicial = obtenerInicialAvatar($usuarioCorreo);
$adminAPIconfigurationModel = new AdminAPIconfigurationModel($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['api_integration_action'] ?? '') !== '') {
    try {
        procesarAccionIntegracion($adminAPIconfigurationModel);
    } catch (Throwable $exception) {
        $_SESSION['admin_api_integrations_flash'] = [
            'estado' => 'error',
            'mensaje' => $exception->getMessage() !== '' ? $exception->getMessage() : 'No se pudo completar la accion.',
        ];
    }

    header('Location: /impulsa_emprende/controller/admin/adminAPIconfigurationController.php');
    exit;
}

$integraciones = $adminAPIconfigurationModel->obtenerIntegraciones();
$opcionesProyectoSitio = $adminAPIconfigurationModel->obtenerOpcionesProyectoSitio();
$flashIntegraciones = $_SESSION['admin_api_integrations_flash'] ?? null;
unset($_SESSION['admin_api_integrations_flash']);

$appBaseUrl = obtenerBaseUrlAplicacion();

require __DIR__ . '/../../partials/bottom_sheet_perfil/perfilController.php';

$usuarioAvatarUrl = $perfilAvatarUrl;
$usuarioMarcaNombre = trim((string) ($perfilDatos['apodo'] ?? ''));
if ($usuarioMarcaNombre === '') {
    $usuarioMarcaNombre = trim((string) ($perfilDatos['nombre'] ?? ''));
}
if ($usuarioMarcaNombre === '') {
    $usuarioMarcaNombre = 'Usuario';
}

require __DIR__ . '/../../view/admin/adminAPIconfigurationView.php';

function procesarAccionIntegracion(AdminAPIconfigurationModel $model): void
{
    $accion = trim((string) ($_POST['api_integration_action'] ?? ''));

    if ($accion === 'create') {
        $projectName = validarNombreProyecto((string) ($_POST['project_name'] ?? ''));
        $allowedDomain = normalizarDominioPermitido((string) ($_POST['allowed_domain'] ?? ''));
        $publicKey = generarClaveUnica($model, 'pk_');
        $secretPlain = generarClavePlana('sk_');
        $secretHash = password_hash($secretPlain, PASSWORD_DEFAULT);
        $integrationId = $model->crearIntegracion($projectName, $allowedDomain, $publicKey, $secretHash);

        $_SESSION['admin_api_integrations_flash'] = [
            'estado' => 'ok',
            'mensaje' => 'Integracion creada correctamente.',
            'integration_id' => $integrationId,
            'public_key' => $publicKey,
            'secret_key' => $secretPlain,
        ];
        return;
    }

    $integrationId = validarIdIntegracion((string) ($_POST['integration_id'] ?? ''));
    $integration = $model->obtenerIntegracionPorId($integrationId);

    if (!$integration) {
        throw new RuntimeException('La integracion seleccionada no existe.');
    }

    if ($accion === 'update') {
        $model->actualizarIntegracion(
            $integrationId,
            validarNombreProyecto((string) ($_POST['project_name'] ?? '')),
            normalizarDominioPermitido((string) ($_POST['allowed_domain'] ?? ''))
        );
        $_SESSION['admin_api_integrations_flash'] = [
            'estado' => 'ok',
            'mensaje' => 'Integracion actualizada correctamente.',
            'integration_id' => $integrationId,
        ];
        return;
    }

    if ($accion === 'toggle_status') {
        $nuevoEstado = ($integration['status'] ?? '') === 'active' ? 'inactive' : 'active';
        $model->actualizarEstado($integrationId, $nuevoEstado);
        $_SESSION['admin_api_integrations_flash'] = [
            'estado' => 'ok',
            'mensaje' => $nuevoEstado === 'active' ? 'Integracion activada.' : 'Integracion desactivada.',
            'integration_id' => $integrationId,
        ];
        return;
    }

    if ($accion === 'regenerate_public_key') {
        $publicKey = generarClaveUnica($model, 'pk_', $integrationId);
        $model->actualizarPublicKey($integrationId, $publicKey);
        $_SESSION['admin_api_integrations_flash'] = [
            'estado' => 'ok',
            'mensaje' => 'Clave publica regenerada correctamente.',
            'integration_id' => $integrationId,
            'public_key' => $publicKey,
        ];
        return;
    }

    if ($accion === 'regenerate_secret_key') {
        $secretPlain = generarClavePlana('sk_');
        $model->actualizarSecretKeyHash($integrationId, password_hash($secretPlain, PASSWORD_DEFAULT));
        $_SESSION['admin_api_integrations_flash'] = [
            'estado' => 'ok',
            'mensaje' => 'Clave secreta regenerada correctamente.',
            'integration_id' => $integrationId,
            'secret_key' => $secretPlain,
        ];
        return;
    }

    throw new RuntimeException('Accion de integracion no valida.');
}

function validarNombreProyecto(string $nombre): string
{
    $nombre = trim($nombre);

    if ($nombre === '') {
        throw new RuntimeException('El nombre del proyecto es obligatorio.');
    }

    if ((function_exists('mb_strlen') ? mb_strlen($nombre, 'UTF-8') : strlen($nombre)) > 180) {
        throw new RuntimeException('El nombre del proyecto no puede superar 180 caracteres.');
    }

    return $nombre;
}

function normalizarDominioPermitido(string $dominio): string
{
    $dominio = trim($dominio);

    if ($dominio === '') {
        throw new RuntimeException('El dominio autorizado es obligatorio.');
    }

    if (!preg_match('#^https?://#i', $dominio)) {
        $dominio = 'https://' . $dominio;
    }

    $partes = parse_url($dominio);

    if ($partes === false || empty($partes['scheme']) || empty($partes['host'])) {
        throw new RuntimeException('El dominio autorizado no tiene un formato valido.');
    }

    $normalizado = strtolower($partes['scheme']) . '://' . strtolower($partes['host']);

    if (!empty($partes['port'])) {
        $normalizado .= ':' . (int) $partes['port'];
    }

    return $normalizado;
}

function validarIdIntegracion(string $valor): int
{
    $id = filter_var($valor, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

    if ($id === false) {
        throw new RuntimeException('La integracion seleccionada no es valida.');
    }

    return (int) $id;
}

function generarClaveUnica(AdminAPIconfigurationModel $model, string $prefijo, ?int $excludeId = null): string
{
    do {
        $clave = generarClavePlana($prefijo);
    } while ($model->existePublicKey($clave, $excludeId));

    return $clave;
}

function generarClavePlana(string $prefijo): string
{
    return $prefijo . bin2hex(random_bytes(16));
}

function obtenerBaseUrlAplicacion(): string
{
    $esHttps = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    $scheme = $esHttps ? 'https' : 'http';
    $host = trim((string) ($_SERVER['HTTP_HOST'] ?? ''));

    if ($host === '') {
        return '';
    }

    return $scheme . '://' . $host;
}
