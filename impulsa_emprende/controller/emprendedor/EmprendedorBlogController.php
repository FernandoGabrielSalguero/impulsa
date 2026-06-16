<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../auth/auth_helpers.php';

$usuario = authRequiereRol('impulsa_emprendedor');
$usuarioCorreo = (string) ($usuario['correo'] ?? '');
$usuarioInicial = obtenerInicialAvatar($usuarioCorreo);

require __DIR__ . '/../../partials/bottom_sheet_perfil/perfilController.php';

$usuarioAvatarUrl = $perfilAvatarUrl;
$usuarioMarcaNombre = trim((string) ($perfilDatos['apodo'] ?? ''));
if ($usuarioMarcaNombre === '') {
    $usuarioMarcaNombre = trim((string) ($perfilDatos['nombre'] ?? ''));
}
if ($usuarioMarcaNombre === '') {
    $usuarioMarcaNombre = 'Emprendedor';
}

$apiBlogContext = [
    'user' => $usuario,
    'role_label' => 'Emprendedor',
    'page_title' => 'Blog para tu web',
    'page_description' => 'Publica contenido enriquecido, adjuntos y portadas para las integraciones vinculadas a tu emprendimiento.',
    'back_href' => '/impulsa_emprende/controller/emprendedor/EmprendedorDashboardController.php',
    'back_label' => 'Volver al dashboard',
    'flash_key' => 'emprendedor_blog_flash',
    'post_action' => 'entrepreneur_blog',
    'module_label' => 'Blog',
];

require __DIR__ . '/../../view/emprendedor/EmprendedorBlogView.php';
