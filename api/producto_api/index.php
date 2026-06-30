<?php

declare(strict_types=1);

require_once __DIR__ . '/../_shared/api_integration_helpers.php';
require_once __DIR__ . '/../../impulsa_emprende/partials/api_product/api_productoModel.php';

try {
    apiCargarEnv();
    apiConfigurarCorsPersonalizado(['GET', 'POST', 'OPTIONS'], ['Content-Type', 'X-API-SECRET']);

    $metodo = $_SERVER['REQUEST_METHOD'] ?? '';
    if ($metodo === 'OPTIONS') {
        http_response_code(204);
        exit;
    }

    if ($metodo === 'GET' && isset($_GET['media_item_id'], $_GET['media_type'], $_GET['public_key'])) {
        $publicKey = productoObtenerTexto($_GET, 'public_key', true, 80);

        $pdo = apiCrearConexionPdo();
        $integracion = apiValidarIntegracion($pdo, $publicKey);
        $model = new ApiProductoModel($pdo);

        $itemId = filter_var($_GET['media_item_id'] ?? null, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
        ]);

        if ($itemId === false || $itemId === null) {
            http_response_code(400);
            exit('Solicitud invalida.');
        }

        $mediaType = trim((string) ($_GET['media_type'] ?? ''));
        $column = match ($mediaType) {
            'main' => 'main_image_path',
            'thumbnail' => 'thumbnail_path',
            'attachment' => 'attachment_path',
            default => null,
        };

        if ($column === null) {
            http_response_code(400);
            exit('Tipo de archivo invalido.');
        }

        $file = $model->obtenerArchivoPublico((int) $integracion['id'], (int) $itemId, $column);

        if ($file === null || !is_file((string) ($file['absolute_path'] ?? ''))) {
            http_response_code(404);
            exit('Archivo no encontrado.');
        }

        $absolutePath = (string) $file['absolute_path'];
        $mimeType = (string) ($file['mime_type'] ?? 'application/octet-stream');
        $downloadName = basename((string) ($file['download_name'] ?? $absolutePath));
        $disposition = $mediaType === 'attachment' ? 'attachment' : 'inline';

        header('X-Content-Type-Options: nosniff');
        header('Content-Type: ' . $mimeType);
        header('Content-Length: ' . (string) filesize($absolutePath));
        header('Content-Disposition: ' . $disposition . '; filename="' . addslashes($downloadName) . '"');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Expires: 0');

        readfile($absolutePath);
        exit;
    }

    if ($metodo !== 'POST') {
        apiResponderJson(405, false, 'Metodo no permitido. Usa POST.');
    }

    [$payload, $files] = productoResolverEntrada();
    $action = trim((string) ($payload['action'] ?? ''));
    $publicKey = productoObtenerTexto($payload, 'public_key', true, 80);

    $pdo = apiCrearConexionPdo();
    $integracion = apiValidarIntegracion($pdo, $publicKey);
    $model = new ApiProductoModel($pdo);

    $resultado = match ($action) {
        'list' => [
            'items' => $model->obtenerListadoPublico($integracion['id']),
        ],
        'detail' => [
            'item' => productoResolverDetalle($model, $integracion['id'], $payload),
        ],
        'create' => [
            'id' => $model->guardarItemApi(
                $integracion['id'],
                null,
                productoObtenerUsuarioApiOpcional($payload),
                $payload,
                $files
            ),
        ],
        'update' => [
            'id' => $model->guardarItemApi(
                $integracion['id'],
                productoObtenerId($payload, 'id'),
                productoObtenerUsuarioApiOpcional($payload),
                $payload,
                $files
            ),
        ],
        'toggle_status' => productoToggleStatus($model, $integracion['id'], $payload),
        'delete' => productoDelete($model, $integracion['id'], $payload),
        default => throw new RuntimeException('La accion solicitada no es valida.', 422),
    };

    apiRegistrarUltimoUsoIntegracion($pdo, $integracion['id']);
    apiResponderJson(200, true, 'Solicitud procesada correctamente.', $resultado);
} catch (InvalidArgumentException $exception) {
    apiResponderJson(422, false, $exception->getMessage());
} catch (RuntimeException $exception) {
    $codigo = $exception->getCode();
    $codigoHttp = ($codigo >= 400 && $codigo <= 599) ? $codigo : 500;
    if ($codigoHttp >= 500) {
        error_log('Error interno en API/producto_api/index.php: ' . $exception->getMessage());
        apiResponderJson(500, false, 'Error interno del servidor');
    }

    apiResponderJson($codigoHttp, false, $exception->getMessage());
} catch (Throwable $exception) {
    error_log('Error no controlado en API/producto_api/index.php: ' . $exception->getMessage());
    apiResponderJson(500, false, 'Error interno del servidor');
}

function productoResolverEntrada(): array
{
    $contentType = strtolower((string) ($_SERVER['CONTENT_TYPE'] ?? $_SERVER['HTTP_CONTENT_TYPE'] ?? ''));
    if (str_contains($contentType, 'application/json')) {
        return [apiObtenerPayloadJson(), []];
    }

    $payload = $_POST;
    if (!is_array($payload) || $payload === []) {
        throw new RuntimeException('Debes enviar JSON o FormData con una accion valida.', 400);
    }

    return [$payload, $_FILES];
}

function productoResolverDetalle(ApiProductoModel $model, int $integrationId, array $payload): array
{
    $item = $model->obtenerDetallePublico(
        $integrationId,
        productoObtenerIdOpcional($payload, 'id'),
        productoObtenerTexto($payload, 'slug', false, 220)
    );

    if ($item === null) {
        throw new RuntimeException('No se encontro el producto solicitado.', 404);
    }

    return $item;
}

function productoToggleStatus(ApiProductoModel $model, int $integrationId, array $payload): array
{
    $itemId = productoObtenerId($payload, 'id');
    $status = productoObtenerTexto($payload, 'status', true, 16);
    $model->actualizarEstadoApi($integrationId, $itemId, $status);

    return ['id' => $itemId, 'status' => $status];
}

function productoDelete(ApiProductoModel $model, int $integrationId, array $payload): array
{
    $itemId = productoObtenerId($payload, 'id');
    $model->eliminarLogicamenteApi($integrationId, $itemId);

    return ['id' => $itemId, 'status' => 'inactive'];
}

function productoObtenerUsuarioApiOpcional(array $payload): ?int
{
    if (!isset($payload['created_by_user_id']) || trim((string) $payload['created_by_user_id']) === '') {
        return null;
    }

    return productoObtenerId($payload, 'created_by_user_id');
}

function productoObtenerId(array $payload, string $field): int
{
    $value = filter_var($payload[$field] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($value === false) {
        throw new RuntimeException('El campo ' . $field . ' debe ser un entero positivo.', 422);
    }

    return (int) $value;
}

function productoObtenerIdOpcional(array $payload, string $field): ?int
{
    if (!isset($payload[$field]) || trim((string) $payload[$field]) === '') {
        return null;
    }

    return productoObtenerId($payload, $field);
}

function productoObtenerTexto(array $payload, string $field, bool $required, ?int $maxLength): ?string
{
    if (!array_key_exists($field, $payload) || $payload[$field] === null) {
        if ($required) {
            throw new RuntimeException('El campo ' . $field . ' es obligatorio.', 422);
        }

        return null;
    }

    if (!is_scalar($payload[$field])) {
        throw new RuntimeException('El campo ' . $field . ' debe ser texto.', 422);
    }

    $value = trim((string) $payload[$field]);
    if ($value === '') {
        if ($required) {
            throw new RuntimeException('El campo ' . $field . ' es obligatorio.', 422);
        }

        return null;
    }

    if ($maxLength !== null && apiLongitudTexto($value) > $maxLength) {
        throw new RuntimeException('El campo ' . $field . ' supera la longitud maxima.', 422);
    }

    return $value;
}
