<?php

require_once __DIR__ . '/emprendedor_cimientosModel.php';

if (!function_exists('emprendedorCimientosObtenerData')) {
    function emprendedorCimientosObtenerData(PDO $pdo, int $userId): array
    {
        $model = new EmprendedorCimientosModel($pdo);
        return $model->obtenerDrawerData($userId);
    }
}

if (!function_exists('emprendedorCimientosResponderAjax')) {
    function emprendedorCimientosResponderAjax(PDO $pdo): void
    {
        header('Content-Type: application/json; charset=UTF-8');

        $userId = filter_input(INPUT_GET, 'user_id', FILTER_VALIDATE_INT);
        if (!$userId || $userId <= 0) {
            http_response_code(400);
            echo json_encode([
                'ok' => false,
                'mensaje' => 'Usuario invalido.',
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            exit;
        }

        $data = emprendedorCimientosObtenerData($pdo, (int) $userId);
        if ($data === [] || ($data['usuario'] ?? []) === []) {
            http_response_code(404);
            echo json_encode([
                'ok' => false,
                'mensaje' => 'No encontramos al emprendedor seleccionado.',
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            exit;
        }

        echo json_encode([
            'ok' => true,
            'data' => $data,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }
}
