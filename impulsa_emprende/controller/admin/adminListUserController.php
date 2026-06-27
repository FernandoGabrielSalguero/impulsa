<?php

require_once __DIR__ . '/../../../auth/auth_helpers.php';
require_once __DIR__ . '/../../model/admin/adminListUserModel.php';
require_once __DIR__ . '/../../mail/Mail.php';
require_once __DIR__ . '/../../partials/components/admin/cimientos/emprendedor_cimientosController.php';
require_once __DIR__ . '/../../partials/components/admin/GestorDeMenu/admin_gestorMenuController.php';

$usuario = authRequiereRol('impulsa_administrador');
$usuarioCorreo = (string) ($usuario['correo'] ?? '');
$usuarioInicial = obtenerInicialAvatar($usuarioCorreo);

$adminListUserModel = new AdminListUserModel($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'crear_usuario_manual') {
    try {
        $passwordPlano = rtrim(strtr(base64_encode(random_bytes(18)), '+/', '-_'), '=') . 'A1!';
        $resultadoUsuario = $adminListUserModel->crearUsuarioManual($_POST, $passwordPlano);
        $estado = (string) ($resultadoUsuario['estado'] ?? 'usuario_error_crear');
        if (($resultadoUsuario['ok'] ?? false) === true) {
            $resultadoCorreo = \SVE\Mail\Mailer::enviarNuevoUsuarioCliente([
                'correo' => (string) ($resultadoUsuario['usuario']['correo'] ?? ''),
                'nombre' => (string) ($resultadoUsuario['usuario']['nombre'] ?? ''),
                'password' => $passwordPlano,
                'link' => 'https://impulsagroup.com/ingreso.html',
                'user_auth_id' => (int) ($resultadoUsuario['usuario']['id'] ?? 0),
            ]);
            $estado = ($resultadoCorreo['ok'] ?? false) ? 'usuario_creado' : 'usuario_creado_correo_fallido';
        }
    } catch (Throwable) {
        $estado = 'usuario_error_crear';
    }

    header('Location: /impulsa_emprende/controller/admin/adminListUserController.php?estado=' . urlencode($estado));
    exit;
}

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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'actualizar_usuario') {
    $usuarioId = filter_input(INPUT_POST, 'usuario_id', FILTER_VALIDATE_INT);
    if (!$usuarioId || $usuarioId <= 0) {
        header('Location: /impulsa_emprende/controller/admin/adminListUserController.php?estado=usuario_id_invalido');
        exit;
    }

    try {
        $resultado = $adminListUserModel->actualizarUsuario($usuarioId, $_POST);
        $estado = (string) ($resultado['estado'] ?? 'usuario_error_actualizar');
    } catch (Throwable) {
        $estado = 'usuario_error_actualizar';
    }

    header('Location: /impulsa_emprende/controller/admin/adminListUserController.php?estado=' . urlencode($estado));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'guardar_menu_usuario') {
    $resultado = adminGestorMenuProcesarGuardado($pdo, $_POST);
    $estado = (string) ($resultado['estado'] ?? 'menu_usuario_error_guardar');

    header('Location: /impulsa_emprende/controller/admin/adminListUserController.php?estado=' . urlencode($estado));
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

if (($_GET['ajax'] ?? '') === 'cimientos_usuario') {
    emprendedorCimientosResponderAjax($pdo);
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
