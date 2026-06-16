<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../auth/auth_helpers.php';

$usuario = authRequiereRol('impulsa_cliente');
$usuarioCorreo = (string) ($usuario['correo'] ?? '');
$usuarioInicial = obtenerInicialAvatar($usuarioCorreo);

require __DIR__ . '/../../partials/bottom_sheet_perfil/perfilController.php';

$usuarioAvatarUrl = $perfilAvatarUrl;
$usuarioMarcaNombre = trim((string) ($perfilDatos['apodo'] ?? ''));
if ($usuarioMarcaNombre === '') {
    $usuarioMarcaNombre = trim((string) ($perfilDatos['nombre'] ?? ''));
}
if ($usuarioMarcaNombre === '') {
    $usuarioMarcaNombre = 'Cliente';
}

$apiProductoContext = [
    'user' => $usuario,
    'role_label' => 'Cliente',
    'page_title' => 'Productos para tus integraciones',
    'page_description' => 'Gestiona catalogos, imagenes, stock y documentos por integracion API.',
    'back_href' => '/impulsa_emprende/controller/client/ClienteDashboardController.php',
    'back_label' => 'Volver al dashboard',
    'flash_key' => 'cliente_producto_flash',
    'post_action' => 'client_product',
    'module_label' => 'Productos',
];

require __DIR__ . '/../../view/client/ClienteProductView.php';
