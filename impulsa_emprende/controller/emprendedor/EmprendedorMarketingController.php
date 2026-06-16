<?php

require_once __DIR__ . '/../../../auth/auth_helpers.php';

$usuario = authRequiereRol('impulsa_emprendedor');
$usuarioCorreo = (string) ($usuario['correo'] ?? '');
$usuarioInicial = obtenerInicialAvatar($usuarioCorreo);

require __DIR__ . '/../../partials/marketing/visualizador de planes/visualizadorPlanesMarketingController.php';
require __DIR__ . '/../../partials/marketing/visualizador de resultados/visualizadorResultadosMarketingController.php';
require __DIR__ . '/../../partials/bottom_sheet_perfil/perfilController.php';

$usuarioAvatarUrl = $perfilAvatarUrl;
$usuarioMarcaNombre = trim((string) ($perfilDatos['apodo'] ?? ''));
if ($usuarioMarcaNombre === '') {
    $usuarioMarcaNombre = trim((string) ($perfilDatos['nombre'] ?? ''));
}
if ($usuarioMarcaNombre === '') {
    $usuarioMarcaNombre = 'Emprendedor';
}

$marketingRolLabel = 'Emprendedor';
$marketingNavItems = [
    ['href' => '/impulsa_emprende/controller/emprendedor/EmprendedorDashboardController.php', 'icon' => 'dashboard', 'label' => 'Dashboard'],
    ['href' => '/impulsa_emprende/controller/emprendedor/emprendedorDefinicionController.php', 'icon' => 'flag', 'label' => 'Definicion'],
    ['href' => '/impulsa_emprende/controller/emprendedor/EmprendedorPaginaWebController.php', 'icon' => 'language', 'label' => 'Pagina web'],
    ['href' => '/impulsa_emprende/controller/emprendedor/EmprendedorMarketingController.php', 'icon' => 'campaign', 'label' => 'Marketing', 'active' => true],
    ['href' => '/impulsa_emprende/controller/emprendedor/EmprendedorChatbotController.php', 'icon' => 'forum', 'label' => 'Chatbot'],
    ['href' => '/impulsa_emprende/controller/emprendedor/EmprendedorBlogController.php', 'icon' => 'article', 'label' => 'Blog'],
    ['href' => '/impulsa_emprende/controller/emprendedor/EmprendedorProductController.php', 'icon' => 'inventory_2', 'label' => 'Productos'],
];

require __DIR__ . '/../../view/emprendedor/EmprendedorMarketingView.php';
