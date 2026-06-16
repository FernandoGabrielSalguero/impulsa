<?php

require_once __DIR__ . '/../../../auth/auth_helpers.php';
require_once __DIR__ . '/../../model/marketing/marketingUsuariosModel.php';

$usuario = authRequiereRol('impulsa_marketing');
$usuarioCorreo = (string) ($usuario['correo'] ?? '');
$usuarioInicial = obtenerInicialAvatar($usuarioCorreo);

$marketingUsuariosModel = new MarketingUsuariosModel($pdo);
$marketingUsuarios = $marketingUsuariosModel->obtenerUsuariosExternos();

require __DIR__ . '/../../partials/bottom_sheet_perfil/perfilController.php';

$usuarioAvatarUrl = $perfilAvatarUrl;
$usuarioMarcaNombre = trim((string) ($perfilDatos['apodo'] ?? ''));
if ($usuarioMarcaNombre === '') {
    $usuarioMarcaNombre = trim((string) ($perfilDatos['nombre'] ?? ''));
}
if ($usuarioMarcaNombre === '') {
    $usuarioMarcaNombre = 'Marketing';
}

$marketingPageTitle = 'Usuarios';
$marketingActivePage = 'usuarios';
$marketingContentView = __DIR__ . '/../../view/marketing/marketingUsuariosView.php';

require __DIR__ . '/../../partials/marketing/marketingRoleLayoutView.php';
