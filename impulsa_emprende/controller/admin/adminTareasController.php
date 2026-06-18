<?php

require_once __DIR__ . '/../../../auth/auth_helpers.php';
require_once __DIR__ . '/../../model/admin/adminsTareasModel.php';

$usuario = authRequiereRol('impulsa_administrador');
$usuarioCorreo = (string) ($usuario['correo'] ?? '');
$usuarioInicial = obtenerInicialAvatar($usuarioCorreo);

$adminsTareasModel = new AdminsTareasModel($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = trim((string) ($_POST['accion'] ?? ''));
    $estado = 'tarea_accion_invalida';

    if ($accion === 'crear_tarea') {
        $resultado = $adminsTareasModel->crearTarea($_POST, (int) ($usuario['id'] ?? 0));
        $estado = (string) ($resultado['estado'] ?? 'tarea_error_crear');
    } elseif ($accion === 'actualizar_tarea') {
        $tareaId = filter_input(INPUT_POST, 'tarea_id', FILTER_VALIDATE_INT);
        $resultado = $adminsTareasModel->actualizarTarea((int) $tareaId, $_POST);
        $estado = (string) ($resultado['estado'] ?? 'tarea_error_actualizar');
    } elseif ($accion === 'eliminar_tarea') {
        $tareaId = filter_input(INPUT_POST, 'tarea_id', FILTER_VALIDATE_INT);
        $resultado = $adminsTareasModel->eliminarTarea((int) $tareaId);
        $estado = (string) ($resultado['estado'] ?? 'tarea_error_eliminar');
    }

    $_SESSION['admin_tareas_flash'] = [
        'estado' => $estado,
    ];

    header('Location: /impulsa_emprende/controller/admin/adminTareasController.php');
    exit;
}

$flashTareas = $_SESSION['admin_tareas_flash'] ?? null;
unset($_SESSION['admin_tareas_flash']);

$tareas = $adminsTareasModel->obtenerTareas();
$usuariosTareas = $adminsTareasModel->obtenerOpcionesUsuarios();

require __DIR__ . '/../../partials/bottom_sheet_perfil/perfilController.php';

$usuarioAvatarUrl = $perfilAvatarUrl;
$usuarioMarcaNombre = trim((string) ($perfilDatos['apodo'] ?? ''));
if ($usuarioMarcaNombre === '') {
    $usuarioMarcaNombre = trim((string) ($perfilDatos['nombre'] ?? ''));
}
if ($usuarioMarcaNombre === '') {
    $usuarioMarcaNombre = 'Usuario';
}

require __DIR__ . '/../../view/admin/adminTareasView.php';
