<?php

require_once __DIR__ . '/../../../auth/auth_helpers.php';

$usuario = authRequiereRol('impulsa_marketing');
$usuarioCorreo = (string) ($usuario['correo'] ?? '');
$usuarioInicial = obtenerInicialAvatar($usuarioCorreo);

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

$marketingPageTitle = 'Resultados';
$marketingActivePage = 'resultados';
$marketingContentView = __DIR__ . '/../../partials/marketing/visualizador de resultados/visualizadorResultadosMarketingView.php';

require __DIR__ . '/../../view/marketing/marketingResultadosView.php';
