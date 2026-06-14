<?php

require_once __DIR__ . '/../../../auth/auth_helpers.php';

$usuario = authRequiereRol('impulsa_administrador');
$usuarioCorreo = (string) ($usuario['correo'] ?? '');
$usuarioInicial = obtenerInicialAvatar($usuarioCorreo);

require __DIR__ . '/../../partials/marketing/constructor de planes/constructorPlanesMarketingController.php';
require __DIR__ . '/../../partials/marketing/visualizador de planes/visualizadorPlanesMarketingController.php';
require __DIR__ . '/../../partials/marketing/monitor de planes/monitorPlanesMarketingController.php';
require __DIR__ . '/../../partials/marketing/visualizador de resultados/visualizadorResultadosMarketingController.php';
require __DIR__ . '/../../partials/bottom_sheet_perfil/perfilController.php';

$usuarioAvatarUrl = $perfilAvatarUrl;
$usuarioMarcaNombre = trim((string) ($perfilDatos['apodo'] ?? ''));
if ($usuarioMarcaNombre === '') {
    $usuarioMarcaNombre = trim((string) ($perfilDatos['nombre'] ?? ''));
}
if ($usuarioMarcaNombre === '') {
    $usuarioMarcaNombre = 'Usuario';
}

$marketingRolLabel = 'Administrador';
$marketingBackHref = '/impulsa_emprende/controller/admin/dashboard.php';
$marketingNavItems = [
    ['href' => '/impulsa_emprende/controller/admin/dashboard.php', 'icon' => 'dashboard', 'label' => 'Dashboard'],
    ['href' => '/impulsa_emprende/controller/admin/adminListUserController.php', 'icon' => 'groups', 'label' => 'Usuarios'],
    ['href' => '/impulsa_emprende/controller/admin/adminSolicitudesPaginaWebSolicitudesController.php', 'icon' => 'language', 'label' => 'Solicitudes web'],
    ['href' => '/impulsa_emprende/controller/admin/adminProyectosController.php', 'icon' => 'work', 'label' => 'Proyectos'],
    ['href' => '/impulsa_emprende/controller/admin/adminMarketingController.php', 'icon' => 'campaign', 'label' => 'Marketing', 'active' => true],
    ['href' => '/impulsa_emprende/controller/admin/adminAPIconfigurationController.php', 'icon' => 'key', 'label' => 'Integraciones API'],
    ['href' => '/impulsa_emprende/controller/admin/adminCorreosEnviadosController.php', 'icon' => 'mail', 'label' => 'Correos enviados'],
    ['href' => '/impulsa_emprende/controller/admin/adminChatbotController.php', 'icon' => 'forum', 'label' => 'Chatbots'],
];

require __DIR__ . '/../../view/admin/adminMarketingView.php';
