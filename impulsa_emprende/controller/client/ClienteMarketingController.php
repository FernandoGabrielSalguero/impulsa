<?php

require_once __DIR__ . '/../../../auth/auth_helpers.php';

$usuario = authRequiereRol('impulsa_cliente');
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
    $usuarioMarcaNombre = 'Cliente';
}

$marketingRolLabel = 'Cliente';
$marketingNavItems = [
    ['href' => '/impulsa_emprende/controller/client/ClienteDashboardController.php', 'icon' => 'dashboard', 'label' => 'Dashboard'],
    ['href' => '/impulsa_emprende/controller/client/ClienteMetricasController.php', 'icon' => 'monitoring', 'label' => 'Metricas'],
    ['href' => '/impulsa_emprende/controller/client/ClienteMarketingController.php', 'icon' => 'campaign', 'label' => 'Marketing', 'active' => true],
    ['href' => '/impulsa_emprende/controller/client/ClienteChatbotController.php', 'icon' => 'forum', 'label' => 'Chatbot'],
    ['href' => '/impulsa_emprende/controller/client/ClienteBlogController.php', 'icon' => 'article', 'label' => 'Blog'],
    ['href' => '/impulsa_emprende/controller/client/ClienteProductController.php', 'icon' => 'inventory_2', 'label' => 'Productos'],
];

require __DIR__ . '/../../view/client/ClienteMarketingView.php';
