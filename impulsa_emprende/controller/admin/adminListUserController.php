<?php

require_once __DIR__ . '/../../../auth/auth_helpers.php';
require_once __DIR__ . '/../../model/admin/adminListUserModel.php';

$usuario = authRequiereRol('impulsa_administrador');
$usuarioCorreo = (string) ($usuario['correo'] ?? '');
$usuarioInicial = obtenerInicialAvatar($usuarioCorreo);

$adminListUserModel = new AdminListUserModel($pdo);

if (($_GET['ajax'] ?? '') === 'usuarios') {
    header('Content-Type: application/json; charset=UTF-8');

    $busqueda = trim((string) ($_GET['q'] ?? ''));
    echo json_encode([
        'ok' => true,
        'usuarios' => $adminListUserModel->buscarUsuarios($busqueda),
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

$usuarios = $adminListUserModel->obtenerUsuarios();

require __DIR__ . '/../../partials/bottom_sheet_perfil/perfilController.php';

$usuarioAvatarUrl = $perfilAvatarUrl;
$usuarioMarcaNombre = trim((string) ($perfilDatos['apodo'] ?? ''));
if ($usuarioMarcaNombre === '') {
    $usuarioMarcaNombre = trim((string) ($perfilDatos['nombre'] ?? ''));
}
if ($usuarioMarcaNombre === '') {
    $usuarioMarcaNombre = 'Usuario';
}

require __DIR__ . '/../../view/admin/adminListUserView.php';
