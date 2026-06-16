<?php

require_once __DIR__ . '/../../../auth/auth_helpers.php';

$usuario = authRequiereRol('impulsa_marketing');
$usuarioCorreo = (string) ($usuario['correo'] ?? '');
$usuarioInicial = obtenerInicialAvatar($usuarioCorreo);
$marketingRedirectUrl = '/impulsa_emprende/controller/marketing/marketingMonitorController.php';

require __DIR__ . '/../../partials/marketing/monitor de planes/monitorPlanesMarketingController.php';
require __DIR__ . '/../../partials/bottom_sheet_perfil/perfilController.php';

$usuarioAvatarUrl = $perfilAvatarUrl;
$usuarioMarcaNombre = trim((string) ($perfilDatos['apodo'] ?? ''));
if ($usuarioMarcaNombre === '') {
    $usuarioMarcaNombre = trim((string) ($perfilDatos['nombre'] ?? ''));
}
if ($usuarioMarcaNombre === '') {
    $usuarioMarcaNombre = 'Marketing';
}

$marketingPageTitle = 'Monitor de planes';
$marketingActivePage = 'monitor';
$marketingContentView = __DIR__ . '/../../partials/marketing/monitor de planes/monitorPlanesMarketingView.php';

require __DIR__ . '/../../view/marketing/marketingMonitorView.php';
