<?php

declare(strict_types=1);

require_once __DIR__ . '/visit_page_model.php';

$visitPageComponentContext = $clienteMetricasData ?? [];
$visitPageIntegraciones = $visitPageComponentContext['integraciones'] ?? [];
$visitPageIntegrationIds = array_values(array_map(
    static fn (array $integracion): int => (int) ($integracion['id'] ?? 0),
    array_filter($visitPageIntegraciones, static fn (array $integracion): bool => (int) ($integracion['id'] ?? 0) > 0)
));

$visitPageMetricsModel = new VisitPageMetricsModel($pdo);
$visitPageResumen = $visitPageMetricsModel->obtenerResumenMensual($visitPageIntegrationIds);
$visitPageTotalIntegraciones = count($visitPageIntegraciones);

require __DIR__ . '/visit_page_view.php';
