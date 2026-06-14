<?php

require_once __DIR__ . '/../../../auth/auth_helpers.php';

$usuario = authRequiereRol('impulsa_marketing');
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
    $usuarioMarcaNombre = 'Marketing';
}

$marketingRolLabel = 'Marketing';
$marketingBackHref = '/impulsa_emprende/controller/marketing/marketingDashboardController.php';
$marketingNavItems = [
    ['href' => '/impulsa_emprende/controller/marketing/marketingDashboardController.php', 'icon' => 'campaign', 'label' => 'Marketing', 'active' => true],
];

require __DIR__ . '/../../view/marketing/marketingDashboardView.php';
