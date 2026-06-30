<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../auth/auth_helpers.php';
require_once __DIR__ . '/../../model/admin/adminProductosManagerModel.php';

$usuario = authRequiereRol('impulsa_administrador');
$usuarioCorreo = (string) ($usuario['correo'] ?? '');
$usuarioInicial = obtenerInicialAvatar($usuarioCorreo);
$adminProductosManagerModel = new AdminProductosManagerModel($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['admin_product_action'] ?? '') !== '') {
    try {
        procesarAccionProductoAdmin($adminProductosManagerModel);
    } catch (Throwable $exception) {
        $_SESSION['admin_productos_flash'] = [
            'estado' => 'error',
            'mensaje' => $exception->getMessage() !== '' ? $exception->getMessage() : 'No se pudo completar la accion sobre el producto.',
        ];
    }

    $redirectPath = (string) parse_url((string) ($_SERVER['REQUEST_URI'] ?? ''), PHP_URL_PATH);
    $redirectUrl = $redirectPath !== '' ? $redirectPath : '/impulsa_emprende/controller/admin/adminProductosManagerController.php';
    $integrationIdRedirect = filter_var($_POST['integration_id_redirect'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($integrationIdRedirect !== false) {
        $redirectUrl .= '?integration_id=' . (int) $integrationIdRedirect;
    }

    header('Location: ' . $redirectUrl);
    exit;
}

$integraciones = $adminProductosManagerModel->obtenerIntegracionesAsignables();
$flashProductos = $_SESSION['admin_productos_flash'] ?? null;
unset($_SESSION['admin_productos_flash']);

$selectedIntegrationId = filter_var(
    $_GET['integration_id'] ?? ($flashProductos['integration_id'] ?? null),
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 1]]
);

if ($selectedIntegrationId === false && $integraciones !== []) {
    $selectedIntegrationId = (int) ($integraciones[0]['id'] ?? 0);
}

$selectedIntegration = null;
foreach ($integraciones as $integrationItem) {
    if ((int) ($integrationItem['id'] ?? 0) === (int) $selectedIntegrationId) {
        $selectedIntegration = $integrationItem;
        break;
    }
}

$productos = $selectedIntegration !== null
    ? $adminProductosManagerModel->obtenerProductosPorIntegracion((int) $selectedIntegration['id'])
    : [];

$editId = filter_var(
    $_GET['edit_id'] ?? ($flashProductos['edit_id'] ?? null),
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 1]]
);
$editingProduct = $editId !== false ? $adminProductosManagerModel->obtenerProductoPorId((int) $editId) : null;

if ($editingProduct !== null && $selectedIntegration !== null && (int) ($editingProduct['api_integration_id'] ?? 0) !== (int) ($selectedIntegration['id'] ?? 0)) {
    $editingProduct = null;
}

$productosResumen = $adminProductosManagerModel->obtenerResumen($selectedIntegration !== null ? (int) $selectedIntegration['id'] : null);

require __DIR__ . '/../../partials/bottom_sheet_perfil/perfilController.php';

$usuarioAvatarUrl = $perfilAvatarUrl;
$usuarioMarcaNombre = trim((string) ($perfilDatos['apodo'] ?? ''));
if ($usuarioMarcaNombre === '') {
    $usuarioMarcaNombre = trim((string) ($perfilDatos['nombre'] ?? ''));
}
if ($usuarioMarcaNombre === '') {
    $usuarioMarcaNombre = 'Usuario';
}

require __DIR__ . '/../../view/admin/adminProductosManagerView.php';

function procesarAccionProductoAdmin(AdminProductosManagerModel $model): void
{
    $action = trim((string) ($_POST['admin_product_action'] ?? ''));
    $productId = filter_var($_POST['item_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    $integrationId = filter_var($_POST['api_integration_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

    if ($action === 'save') {
        if ($integrationId === false) {
            throw new RuntimeException('Debes seleccionar una integracion valida para guardar el producto.');
        }

        $savedId = $model->guardarProducto(
            $productId !== false ? (int) $productId : null,
            (int) $integrationId,
            $_POST,
            $_FILES
        );

        $_SESSION['admin_productos_flash'] = [
            'estado' => 'ok',
            'mensaje' => $productId !== false ? 'Producto actualizado correctamente.' : 'Producto creado correctamente.',
            'integration_id' => (int) $integrationId,
        ];

        if ($productId !== false) {
            $_SESSION['admin_productos_flash']['edit_id'] = $savedId;
        }

        return;
    }

    if ($productId === false) {
        throw new RuntimeException('Debes seleccionar un producto valido.');
    }

    if ($action === 'toggle_status') {
        $targetStatus = trim((string) ($_POST['target_status'] ?? 'inactive'));
        $model->actualizarEstadoProducto((int) $productId, $targetStatus);
        $_SESSION['admin_productos_flash'] = [
            'estado' => 'ok',
            'mensaje' => $targetStatus === 'active' ? 'Producto activado correctamente.' : 'Producto actualizado correctamente.',
            'integration_id' => $integrationId !== false ? (int) $integrationId : null,
        ];
        return;
    }

    if ($action === 'deactivate') {
        $model->actualizarEstadoProducto((int) $productId, 'inactive');
        $_SESSION['admin_productos_flash'] = [
            'estado' => 'ok',
            'mensaje' => 'Producto desactivado correctamente.',
            'integration_id' => $integrationId !== false ? (int) $integrationId : null,
        ];
        return;
    }

    throw new RuntimeException('La accion solicitada no es valida.');
}
