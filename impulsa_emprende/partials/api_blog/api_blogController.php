<?php

declare(strict_types=1);

require_once __DIR__ . '/api_blogModel.php';

$apiBlogContext = $apiBlogContext ?? [];
$apiBlogUser = $apiBlogContext['user'] ?? null;
$apiBlogRoleLabel = (string) ($apiBlogContext['role_label'] ?? 'Usuario');
$apiBlogPageTitle = (string) ($apiBlogContext['page_title'] ?? 'API Blog');
$apiBlogPageDescription = (string) ($apiBlogContext['page_description'] ?? '');
$apiBlogBackHref = (string) ($apiBlogContext['back_href'] ?? '#');
$apiBlogBackLabel = (string) ($apiBlogContext['back_label'] ?? 'Volver');
$apiBlogFlashKey = (string) ($apiBlogContext['flash_key'] ?? 'api_blog_flash');
$apiBlogPostAction = (string) ($apiBlogContext['post_action'] ?? '');
$apiBlogModuleLabel = (string) ($apiBlogContext['module_label'] ?? 'Blog');
$apiBlogModel = new ApiBlogModel($pdo);

if (!is_array($apiBlogUser) || (int) ($apiBlogUser['id'] ?? 0) <= 0) {
    throw new RuntimeException('No se pudo identificar al usuario autenticado.');
}

$apiBlogUserId = (int) $apiBlogUser['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && (($apiBlogPostAction !== '' && ($_POST['api_blog_action_scope'] ?? '') === $apiBlogPostAction) || $apiBlogPostAction === '')) {
    try {
        $submitAction = trim((string) ($_POST['api_blog_submit'] ?? 'save'));
        $itemId = filter_var($_POST['item_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

        if ($submitAction === 'delete') {
            if ($itemId === false) {
                throw new RuntimeException('Debes seleccionar una publicacion valida.');
            }

            $apiBlogModel->eliminarItem($apiBlogUserId, (int) $itemId);
            $_SESSION[$apiBlogFlashKey] = [
                'estado' => 'ok',
                'mensaje' => 'Publicacion eliminada correctamente.',
            ];
        } else {
            $integrationId = filter_var($_POST['api_integration_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
            if ($integrationId === false) {
                throw new RuntimeException('Debes seleccionar una integracion valida.');
            }

            $guardadoId = $apiBlogModel->guardarItem(
                $apiBlogUserId,
                $itemId !== false ? (int) $itemId : null,
                (int) $integrationId,
                $_POST,
                $_FILES
            );

            $_SESSION[$apiBlogFlashKey] = [
                'estado' => 'ok',
                'mensaje' => $itemId !== false ? 'Publicacion actualizada correctamente.' : 'Publicacion creada correctamente.',
                'edit_id' => $guardadoId,
                'integration_id' => (int) $integrationId,
            ];
        }
    } catch (Throwable $exception) {
        $_SESSION[$apiBlogFlashKey] = [
            'estado' => 'error',
            'mensaje' => $exception->getMessage() !== '' ? $exception->getMessage() : 'No se pudo guardar la publicacion.',
        ];
    }

    header('Location: ' . (string) ($_SERVER['REQUEST_URI'] ?? ''));
    exit;
}

$apiBlogFlash = $_SESSION[$apiBlogFlashKey] ?? null;
unset($_SESSION[$apiBlogFlashKey]);

$apiBlogIntegraciones = $apiBlogModel->obtenerIntegracionesAccesibles($apiBlogUserId);
$apiBlogSelectedIntegrationId = filter_var(
    $_GET['integration_id'] ?? ($apiBlogFlash['integration_id'] ?? null),
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 1]]
);

if ($apiBlogSelectedIntegrationId === false && $apiBlogIntegraciones !== []) {
    $apiBlogSelectedIntegrationId = (int) ($apiBlogIntegraciones[0]['id'] ?? 0);
}

$apiBlogSelectedIntegration = null;
foreach ($apiBlogIntegraciones as $apiBlogIntegrationItem) {
    if ((int) ($apiBlogIntegrationItem['id'] ?? 0) === (int) $apiBlogSelectedIntegrationId) {
        $apiBlogSelectedIntegration = $apiBlogIntegrationItem;
        break;
    }
}

$apiBlogItems = $apiBlogSelectedIntegrationId !== false && $apiBlogSelectedIntegrationId !== null
    ? $apiBlogModel->obtenerItemsPorUsuario($apiBlogUserId, (int) $apiBlogSelectedIntegrationId)
    : [];

$apiBlogEditId = filter_var(
    $_GET['edit_id'] ?? ($apiBlogFlash['edit_id'] ?? null),
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 1]]
);

$apiBlogEditingItem = $apiBlogEditId !== false ? $apiBlogModel->obtenerItemEditable($apiBlogUserId, (int) $apiBlogEditId) : null;

require __DIR__ . '/api_blogView.php';
