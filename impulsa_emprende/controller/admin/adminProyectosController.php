<?php

require_once __DIR__ . '/../../../auth/auth_helpers.php';
require_once __DIR__ . '/../../model/admin/adminProyectosModel.php';

$usuario = authRequiereRol('impulsa_administrador');
$usuarioCorreo = (string) ($usuario['correo'] ?? '');
$usuarioInicial = obtenerInicialAvatar($usuarioCorreo);

require __DIR__ . '/../../partials/components/admin/proyect manager/adminProyectManagerController.php';
require __DIR__ . '/../../partials/components/admin/contratos/adminContratoController.php';

$adminProyectosModel = new AdminProyectosModel($pdo);
$proyectos = $adminProyectosModel->obtenerProyectos();
$mensajeEstadoProyectos = $_SESSION['admin_proyectos_estado'] ?? null;
unset($_SESSION['admin_proyectos_estado']);

require __DIR__ . '/../../partials/bottom_sheet_perfil/perfilController.php';

$usuarioAvatarUrl = $perfilAvatarUrl;
$usuarioMarcaNombre = trim((string) ($perfilDatos['apodo'] ?? ''));
if ($usuarioMarcaNombre === '') {
    $usuarioMarcaNombre = trim((string) ($perfilDatos['nombre'] ?? ''));
}
if ($usuarioMarcaNombre === '') {
    $usuarioMarcaNombre = 'Usuario';
}

require __DIR__ . '/../../view/admin/adminProyectosView.php';
