<?php

require_once __DIR__ . '/../../../auth/auth_helpers.php';
require_once __DIR__ . '/../../model/emprendedor/EmprendedorDashboardModel.php';
require_once __DIR__ . '/../../partials/components/admin/GestorDeMenu/admin_gestorMenuController.php';

$usuario = authRequiereRol('impulsa_emprendedor');
adminGestorMenuAsegurarAccesoSeccion($pdo, $usuario, 'dashboard');
$usuarioCorreo = (string) ($usuario['correo'] ?? '');
$usuarioInicial = obtenerInicialAvatar($usuarioCorreo);

$dashboardModel = new EmprendedorDashboardModel($pdo);
$dashboardData = $dashboardModel->obtenerDashboard((int) $usuario['id']);

require __DIR__ . '/../../partials/bottom_sheet_perfil/perfilController.php';

$usuarioAvatarUrl = $perfilAvatarUrl;
$usuarioMarcaNombre = trim((string) ($perfilDatos['apodo'] ?? ''));
if ($usuarioMarcaNombre === '') {
    $usuarioMarcaNombre = trim((string) ($perfilDatos['nombre'] ?? ''));
}
if ($usuarioMarcaNombre === '') {
    $usuarioMarcaNombre = 'Cliente';
}

require __DIR__ . '/../../view/emprendedor/EmprendedorDashboardView.php';
