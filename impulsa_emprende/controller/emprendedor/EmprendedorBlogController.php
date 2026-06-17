<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../auth/auth_helpers.php';
require_once __DIR__ . '/../../partials/api_blog/api_blogModel.php';

$usuario = authRequiereRol('impulsa_emprendedor');

$blogMediaItemId = filter_input(INPUT_GET, 'media_item_id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$blogMediaType = trim((string) ($_GET['media_type'] ?? ''));
if ($blogMediaItemId !== false && $blogMediaItemId !== null && $blogMediaType !== '') {
    $blogMediaColumn = match ($blogMediaType) {
        'cover' => 'cover_image_path',
        'attachment' => 'attachment_path',
        default => null,
    };

    if ($blogMediaColumn === null) {
        http_response_code(400);
        exit('Solicitud invalida.');
    }

    $blogMediaModel = new ApiBlogModel($pdo);
    $blogMediaFile = $blogMediaModel->obtenerArchivoEditable((int) ($usuario['id'] ?? 0), (int) $blogMediaItemId, $blogMediaColumn);

    if ($blogMediaFile === null || !is_file((string) ($blogMediaFile['absolute_path'] ?? ''))) {
        http_response_code(404);
        exit('Archivo no encontrado.');
    }

    $blogMediaAbsolutePath = (string) $blogMediaFile['absolute_path'];
    $blogMediaMimeType = (string) ($blogMediaFile['mime_type'] ?? 'application/octet-stream');
    $blogMediaDownloadName = basename((string) ($blogMediaFile['download_name'] ?? $blogMediaAbsolutePath));
    $blogMediaDisposition = $blogMediaType === 'attachment' ? 'attachment' : 'inline';

    header('X-Content-Type-Options: nosniff');
    header('Content-Type: ' . $blogMediaMimeType);
    header('Content-Length: ' . (string) filesize($blogMediaAbsolutePath));
    header('Content-Disposition: ' . $blogMediaDisposition . '; filename="' . addslashes($blogMediaDownloadName) . '"');
    header('Cache-Control: private, max-age=300');

    readfile($blogMediaAbsolutePath);
    exit;
}

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
