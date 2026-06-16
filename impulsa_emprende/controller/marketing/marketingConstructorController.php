<?php

require_once __DIR__ . '/../../../auth/auth_helpers.php';

$usuario = authRequiereRol('impulsa_marketing');
$usuarioCorreo = (string) ($usuario['correo'] ?? '');
$usuarioInicial = obtenerInicialAvatar($usuarioCorreo);
$marketingRedirectUrl = '/impulsa_emprende/controller/marketing/marketingConstructorController.php';

require __DIR__ . '/../../partials/marketing/constructor de planes/constructorPlanesMarketingController.php';
require __DIR__ . '/../../partials/bottom_sheet_perfil/perfilController.php';

$usuarioAvatarUrl = $perfilAvatarUrl;
$usuarioMarcaNombre = trim((string) ($perfilDatos['apodo'] ?? ''));
if ($usuarioMarcaNombre === '') {
    $usuarioMarcaNombre = trim((string) ($perfilDatos['nombre'] ?? ''));
}
if ($usuarioMarcaNombre === '') {
    $usuarioMarcaNombre = 'Marketing';
}

$marketingPageTitle = 'Constructor de planes';
$marketingActivePage = 'constructor';
$marketingContentView = __DIR__ . '/../../partials/marketing/constructor de planes/constructorPlanesMarketingView.php';

require __DIR__ . '/../../view/marketing/marketingConstructorView.php';
