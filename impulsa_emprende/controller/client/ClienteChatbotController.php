<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../auth/auth_helpers.php';
require_once __DIR__ . '/../../partials/components/admin/GestorDeMenu/admin_gestorMenuController.php';

$usuario = authRequiereRol('impulsa_cliente');
adminGestorMenuAsegurarAccesoSeccion($pdo, $usuario, 'chatbot');
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

$chatbotBuilderContext = [
    'user' => $usuario,
    'role_label' => 'Cliente',
    'page_title' => 'Chatbot de tu pagina',
    'page_description' => 'Configura preguntas frecuentes, respuestas y derivaciones por WhatsApp para cada integracion.',
    'back_href' => '/impulsa_emprende/controller/client/ClienteDashboardController.php',
    'back_label' => 'Volver al dashboard',
    'flash_key' => 'cliente_chatbot_flash',
    'post_action' => 'client_chatbot',
];

require __DIR__ . '/../../view/client/ClienteChatbotView.php';
