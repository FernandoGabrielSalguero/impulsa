<?php

declare(strict_types=1);

require_once __DIR__ . '/api_blogModel.php';

$apiBlogContext = $apiBlogContext ?? [];
$apiBlogRoleLabel = (string) ($apiBlogContext['role_label'] ?? 'Cliente');
$apiBlogPageTitle = (string) ($apiBlogContext['page_title'] ?? 'API Blog');

$apiBlogModel = new ApiBlogModel($pdo);
$apiBlogStatusMessage = $apiBlogModel->verificarConexion()
    ? 'El modelo y el controlador estan conectados correctamente.'
    : 'No se pudo validar la conexion entre el modelo y el controlador.';

require __DIR__ . '/api_blogView.php';
