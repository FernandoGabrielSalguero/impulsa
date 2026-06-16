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

$apiProductoContext = [
    'user' => $usuario,
    'role_label' => 'Emprendedor',
    'page_title' => 'Productos para tu web',
    'page_description' => 'Administra fichas de producto, precios, stock y archivos para cada integracion disponible.',
    'back_href' => '/impulsa_emprende/controller/emprendedor/EmprendedorDashboardController.php',
    'back_label' => 'Volver al dashboard',
    'flash_key' => 'emprendedor_producto_flash',
    'post_action' => 'entrepreneur_product',
    'module_label' => 'Productos',
];

require __DIR__ . '/../../view/emprendedor/EmprendedorProductView.php';
