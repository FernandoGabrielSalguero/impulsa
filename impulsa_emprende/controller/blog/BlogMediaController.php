<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../auth/auth_helpers.php';
require_once __DIR__ . '/../../partials/api_blog/api_blogModel.php';

$usuario = authRequiereLogin();
$rol = (string) ($usuario['rol'] ?? '');

if (!in_array($rol, ['impulsa_cliente', 'impulsa_emprendedor'], true)) {
    http_response_code(403);
    exit('Sin permisos para acceder al archivo solicitado.');
}

$itemId = filter_input(INPUT_GET, 'item_id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$type = trim((string) ($_GET['type'] ?? 'cover'));

$column = match ($type) {
    'cover' => 'cover_image_path',
    'attachment' => 'attachment_path',
    default => null,
};

if ($itemId === false || $itemId === null || $column === null) {
    http_response_code(400);
    exit('Solicitud invalida.');
}

$model = new ApiBlogModel($pdo);
$file = $model->obtenerArchivoEditable((int) ($usuario['id'] ?? 0), (int) $itemId, $column);

if ($file === null || !is_file((string) ($file['absolute_path'] ?? ''))) {
    http_response_code(404);
    exit('Archivo no encontrado.');
}

$absolutePath = (string) $file['absolute_path'];
$mimeType = (string) ($file['mime_type'] ?? 'application/octet-stream');
$downloadName = basename((string) ($file['download_name'] ?? $absolutePath));
$disposition = $type === 'attachment' ? 'attachment' : 'inline';

header('X-Content-Type-Options: nosniff');
header('Content-Type: ' . $mimeType);
header('Content-Length: ' . (string) filesize($absolutePath));
header('Content-Disposition: ' . $disposition . '; filename="' . addslashes($downloadName) . '"');
header('Cache-Control: private, max-age=300');

readfile($absolutePath);
exit;
