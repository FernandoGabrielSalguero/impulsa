<?php

require_once __DIR__ . '/../marketingShared.php';
require_once __DIR__ . '/visualizadorResultadosMarketingModel.php';

$visualizadorResultadosMarketingModel = new VisualizadorResultadosMarketingModel($pdo);
$marketingResultados = $visualizadorResultadosMarketingModel->obtenerResultados($usuario, $_GET);
$marketingReportes = $visualizadorResultadosMarketingModel->obtenerReportes($usuario);
