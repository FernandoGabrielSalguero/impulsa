<?php

declare(strict_types=1);

require_once __DIR__ . '/form_contact_model.php';

$formContactComponentContext = $clienteMetricasData ?? [];
$formContactIntegraciones = $formContactComponentContext['integraciones'] ?? [];
$formContactIntegrationIds = array_values(array_map(
    static fn (array $integracion): int => (int) ($integracion['id'] ?? 0),
    array_filter($formContactIntegraciones, static fn (array $integracion): bool => (int) ($integracion['id'] ?? 0) > 0)
));

$formContactMetricsModel = new FormContactMetricsModel($pdo);
$formContactRows = $formContactMetricsModel->obtenerContactos($formContactIntegrationIds);

require __DIR__ . '/form_contact_view.php';
