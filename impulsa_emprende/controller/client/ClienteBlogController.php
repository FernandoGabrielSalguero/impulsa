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

$apiBlogContext = [
    'user' => $usuario,
    'role_label' => 'Cliente',
    'page_title' => 'Blog para tus integraciones',
    'page_description' => 'Crea, edita y publica notas para cada integracion API visible en tu cuenta.',
    'back_href' => '/impulsa_emprende/controller/client/ClienteDashboardController.php',
    'back_label' => 'Volver al dashboard',
    'flash_key' => 'cliente_blog_flash',
    'post_action' => 'client_blog',
    'module_label' => 'Blog',
];

require __DIR__ . '/../../view/client/ClienteBlogView.php';
