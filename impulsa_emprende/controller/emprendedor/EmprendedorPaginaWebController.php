<?php

require_once __DIR__ . '/../../../auth/auth_helpers.php';
require_once __DIR__ . '/../../model/emprendedor/EmprendedorPaginaWebModel.php';
require_once __DIR__ . '/../../model/client/ClienteDashboardModel.php';
require_once __DIR__ . '/../../partials/components/admin/GestorDeMenu/admin_gestorMenuController.php';

$usuario = authRequiereRol('impulsa_emprendedor');
adminGestorMenuAsegurarAccesoSeccion($pdo, $usuario, 'pagina_web');
$usuarioCorreo = (string) ($usuario['correo'] ?? '');
$usuarioInicial = obtenerInicialAvatar($usuarioCorreo);

$paginaWebModel = new EmprendedorPaginaWebModel($pdo);
$clienteDashboardModel = new ClienteDashboardModel($pdo);
$paginaWebUsuario = $paginaWebModel->obtenerUsuario((int) $usuario['id']);
$paginaWebEstadoDefinicion = $paginaWebModel->obtenerEstadoDefinicion((int) $usuario['id']);
$paginaWebDefinicionCompleta = $paginaWebModel->tieneDefinicionCompleta((int) $usuario['id']);
$paginaWebDominioAutorizado = $paginaWebModel->obtenerDominioAutorizado((int) $usuario['id']);
$paginaWebProyectoData = $clienteDashboardModel->obtenerDashboard((int) $usuario['id']);

require __DIR__ . '/../../partials/pagina_web/pagina_web_controller.php';
require __DIR__ . '/../../partials/bottom_sheet_perfil/perfilController.php';

$usuarioAvatarUrl = $perfilAvatarUrl;
$usuarioMarcaNombre = trim((string) ($perfilDatos['apodo'] ?? ''));
if ($usuarioMarcaNombre === '') {
    $usuarioMarcaNombre = trim((string) ($perfilDatos['nombre'] ?? ''));
}
if ($usuarioMarcaNombre === '') {
    $usuarioMarcaNombre = 'Cliente';
}

$paginaWebSnackbar = $_SESSION['pagina_web_snackbar'] ?? null;
unset($_SESSION['pagina_web_snackbar']);

require __DIR__ . '/../../view/emprendedor/EmprendedorPaginaWebView.php';
