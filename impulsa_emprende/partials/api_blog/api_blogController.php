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
$apiBlogSelectionKey = $apiBlogFlashKey . '_selected_integration';
$apiBlogPostAction = (string) ($apiBlogContext['post_action'] ?? '');
$apiBlogModuleLabel = (string) ($apiBlogContext['module_label'] ?? 'Blog');
$apiBlogModel = new ApiBlogModel($pdo);
$apiBlogBaseUrl = (string) strtok((string) ($_SERVER['REQUEST_URI'] ?? ''), '?');
$apiBlogBuildRedirect = static function (?int $integrationId = null, ?int $editId = null): string {
    $query = [];
    if ($integrationId !== null && $integrationId > 0) {
        $query['integration_id'] = $integrationId;
    }
    if ($editId !== null && $editId > 0) {
        $query['edit_id'] = $editId;
    }

    $queryString = http_build_query($query);

    return $queryString !== '' ? '?' . $queryString : (string) strtok((string) ($_SERVER['REQUEST_URI'] ?? ''), '?');
};

if (!is_array($apiBlogUser) || (int) ($apiBlogUser['id'] ?? 0) <= 0) {
    throw new RuntimeException('No se pudo identificar al usuario autenticado.');
}

$apiBlogUserId = (int) $apiBlogUser['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && (($apiBlogPostAction !== '' && ($_POST['api_blog_action_scope'] ?? '') === $apiBlogPostAction) || $apiBlogPostAction === '')) {
    $apiBlogRedirectTo = $apiBlogBaseUrl;

    try {
        $submitAction = trim((string) ($_POST['api_blog_submit'] ?? 'save'));
        $itemId = filter_var($_POST['item_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

        if ($submitAction === 'toggle_status') {
            $targetStatus = trim((string) ($_POST['target_status'] ?? 'inactive'));
            if ($itemId === false) {
                throw new RuntimeException('Debes seleccionar un registro valido.');
            }
            $apiBlogModel->actualizarEstado($apiBlogUserId, (int) $itemId, $targetStatus);
            $_SESSION[$apiBlogFlashKey] = [
                'estado' => 'ok',
                'mensaje' => $targetStatus === 'active' ? 'Publicacion activada correctamente.' : 'Publicacion actualizada correctamente.',
            ];
            $itemActualizado = $apiBlogModel->obtenerItemEditable($apiBlogUserId, (int) $itemId);
            $_SESSION[$apiBlogSelectionKey] = isset($itemActualizado['api_integration_id']) ? (int) $itemActualizado['api_integration_id'] : null;
        } elseif ($submitAction === 'delete') {
            if ($itemId === false) {
                throw new RuntimeException('Debes seleccionar un registro valido.');
            }
            $apiBlogModel->eliminarLogicamente($apiBlogUserId, (int) $itemId);
            $_SESSION[$apiBlogFlashKey] = [
                'estado' => 'ok',
                'mensaje' => 'Publicacion desactivada correctamente.',
            ];
            $itemActualizado = $apiBlogModel->obtenerItemEditable($apiBlogUserId, (int) $itemId);
            $_SESSION[$apiBlogSelectionKey] = isset($itemActualizado['api_integration_id']) ? (int) $itemActualizado['api_integration_id'] : null;
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
                'integration_id' => (int) $integrationId,
            ];
            $_SESSION[$apiBlogSelectionKey] = (int) $integrationId;
        }
    } catch (Throwable $exception) {
        $integrationIdError = filter_var($_POST['api_integration_id'] ?? ($_GET['integration_id'] ?? null), FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $_SESSION[$apiBlogFlashKey] = [
            'estado' => 'error',
            'mensaje' => $exception->getMessage() !== '' ? $exception->getMessage() : 'No se pudo guardar la publicacion.',
            'integration_id' => $integrationIdError !== false ? (int) $integrationIdError : null,
            'edit_id' => $itemId !== false ? (int) $itemId : null,
        ];
        $apiBlogRedirectTo = $apiBlogBuildRedirect(
            $integrationIdError !== false ? (int) $integrationIdError : null,
            $itemId !== false ? (int) $itemId : null
        );
    }

    header('Location: ' . $apiBlogRedirectTo);
    exit;
}

$apiBlogFlash = $_SESSION[$apiBlogFlashKey] ?? null;
unset($_SESSION[$apiBlogFlashKey]);

if (isset($_GET['integration_id'])) {
    $requestedIntegrationId = filter_var($_GET['integration_id'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($requestedIntegrationId !== false) {
        $_SESSION[$apiBlogSelectionKey] = (int) $requestedIntegrationId;
    }
}

$apiBlogIntegraciones = $apiBlogModel->obtenerIntegracionesAccesibles($apiBlogUserId);
$apiBlogSelectedIntegrationId = filter_var(
    $_GET['integration_id'] ?? ($_SESSION[$apiBlogSelectionKey] ?? ($apiBlogFlash['integration_id'] ?? null)),
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
