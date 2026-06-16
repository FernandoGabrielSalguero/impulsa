<?php

declare(strict_types=1);

require_once __DIR__ . '/api_productoModel.php';

$apiProductoContext = $apiProductoContext ?? [];
$apiProductoUser = $apiProductoContext['user'] ?? null;
$apiProductoRoleLabel = (string) ($apiProductoContext['role_label'] ?? 'Usuario');
$apiProductoPageTitle = (string) ($apiProductoContext['page_title'] ?? 'API Productos');
$apiProductoPageDescription = (string) ($apiProductoContext['page_description'] ?? '');
$apiProductoBackHref = (string) ($apiProductoContext['back_href'] ?? '#');
$apiProductoBackLabel = (string) ($apiProductoContext['back_label'] ?? 'Volver');
$apiProductoFlashKey = (string) ($apiProductoContext['flash_key'] ?? 'api_producto_flash');
$apiProductoPostAction = (string) ($apiProductoContext['post_action'] ?? '');
$apiProductoModuleLabel = (string) ($apiProductoContext['module_label'] ?? 'Productos');
$apiProductoModel = new ApiProductoModel($pdo);

if (!is_array($apiProductoUser) || (int) ($apiProductoUser['id'] ?? 0) <= 0) {
    throw new RuntimeException('No se pudo identificar al usuario autenticado.');
}

$apiProductoUserId = (int) $apiProductoUser['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && (($apiProductoPostAction !== '' && ($_POST['api_producto_action_scope'] ?? '') === $apiProductoPostAction) || $apiProductoPostAction === '')) {
    try {
        $submitAction = trim((string) ($_POST['api_producto_submit'] ?? 'save'));
        $itemId = filter_var($_POST['item_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

        if ($submitAction === 'toggle_status') {
            $targetStatus = trim((string) ($_POST['target_status'] ?? 'inactive'));
            if ($itemId === false) {
                throw new RuntimeException('Debes seleccionar un registro valido.');
            }
            $apiProductoModel->actualizarEstado($apiProductoUserId, (int) $itemId, $targetStatus);
            $_SESSION[$apiProductoFlashKey] = [
                'estado' => 'ok',
                'mensaje' => $targetStatus === 'active' ? 'Producto activado correctamente.' : 'Producto actualizado correctamente.',
            ];
        } elseif ($submitAction === 'delete') {
            if ($itemId === false) {
                throw new RuntimeException('Debes seleccionar un registro valido.');
            }
            $apiProductoModel->eliminarLogicamente($apiProductoUserId, (int) $itemId);
            $_SESSION[$apiProductoFlashKey] = [
                'estado' => 'ok',
                'mensaje' => 'Producto desactivado correctamente.',
            ];
        } else {
            $integrationId = filter_var($_POST['api_integration_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
            if ($integrationId === false) {
                throw new RuntimeException('Debes seleccionar una integracion valida.');
            }

            $guardadoId = $apiProductoModel->guardarItem(
                $apiProductoUserId,
                $itemId !== false ? (int) $itemId : null,
                (int) $integrationId,
                $_POST,
                $_FILES
            );

            $_SESSION[$apiProductoFlashKey] = [
                'estado' => 'ok',
                'mensaje' => $itemId !== false ? 'Producto actualizado correctamente.' : 'Producto creado correctamente.',
                'edit_id' => $guardadoId,
                'integration_id' => (int) $integrationId,
            ];
        }
    } catch (Throwable $exception) {
        $_SESSION[$apiProductoFlashKey] = [
            'estado' => 'error',
            'mensaje' => $exception->getMessage() !== '' ? $exception->getMessage() : 'No se pudo guardar el producto.',
        ];
    }

    header('Location: ' . (string) ($_SERVER['REQUEST_URI'] ?? ''));
    exit;
}

$apiProductoFlash = $_SESSION[$apiProductoFlashKey] ?? null;
unset($_SESSION[$apiProductoFlashKey]);

$apiProductoIntegraciones = $apiProductoModel->obtenerIntegracionesAccesibles($apiProductoUserId);
$apiProductoSelectedIntegrationId = filter_var(
    $_GET['integration_id'] ?? ($apiProductoFlash['integration_id'] ?? null),
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 1]]
);

if ($apiProductoSelectedIntegrationId === false && $apiProductoIntegraciones !== []) {
    $apiProductoSelectedIntegrationId = (int) ($apiProductoIntegraciones[0]['id'] ?? 0);
}

$apiProductoSelectedIntegration = null;
foreach ($apiProductoIntegraciones as $apiProductoIntegrationItem) {
    if ((int) ($apiProductoIntegrationItem['id'] ?? 0) === (int) $apiProductoSelectedIntegrationId) {
        $apiProductoSelectedIntegration = $apiProductoIntegrationItem;
        break;
    }
}

$apiProductoItems = $apiProductoSelectedIntegrationId !== false && $apiProductoSelectedIntegrationId !== null
    ? $apiProductoModel->obtenerItemsPorUsuario($apiProductoUserId, (int) $apiProductoSelectedIntegrationId)
    : [];

$apiProductoEditId = filter_var(
    $_GET['edit_id'] ?? ($apiProductoFlash['edit_id'] ?? null),
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 1]]
);

$apiProductoEditingItem = $apiProductoEditId !== false ? $apiProductoModel->obtenerItemEditable($apiProductoUserId, (int) $apiProductoEditId) : null;

require __DIR__ . '/api_productoView.php';
