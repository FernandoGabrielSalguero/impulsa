<?php

require_once __DIR__ . '/../../../auth/auth_helpers.php';
require_once __DIR__ . '/../../model/admin/adminListUserModel.php';

$usuario = authRequiereRol('impulsa_administrador');
$usuarioCorreo = (string) ($usuario['correo'] ?? '');
$usuarioInicial = obtenerInicialAvatar($usuarioCorreo);

$adminListUserModel = new AdminListUserModel($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'eliminar_usuario') {
    $usuarioId = filter_input(INPUT_POST, 'usuario_id', FILTER_VALIDATE_INT);
    if (!$usuarioId || $usuarioId <= 0) {
        header('Location: /impulsa_emprende/controller/admin/adminListUserController.php?estado=usuario_id_invalido');
        exit;
    }

    if ($usuarioId === (int) ($usuario['id'] ?? 0)) {
        header('Location: /impulsa_emprende/controller/admin/adminListUserController.php?estado=usuario_no_autodelete');
        exit;
    }

    $resultado = $adminListUserModel->eliminarUsuarioCompleto($usuarioId);
    $estado = $resultado['ok'] ? 'usuario_eliminado' : 'usuario_error_eliminar';

    header('Location: /impulsa_emprende/controller/admin/adminListUserController.php?estado=' . $estado);
    exit;
}

if (($_GET['ajax'] ?? '') === 'usuarios') {
    header('Content-Type: application/json; charset=UTF-8');

    $busqueda = trim((string) ($_GET['q'] ?? ''));
    echo json_encode([
        'ok' => true,
        'usuarios' => $adminListUserModel->buscarUsuarios($busqueda),
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

$estado = (string) ($_GET['estado'] ?? '');
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
