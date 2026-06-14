<?php

require_once __DIR__ . '/../marketingShared.php';
require_once __DIR__ . '/visualizadorPlanesMarketingModel.php';

$visualizadorPlanesMarketingModel = new VisualizadorPlanesMarketingModel($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accionMarketing = (string) ($_POST['marketing_action'] ?? '');
    if ($accionMarketing === 'subscription_request' && marketingUsuarioPuedeVerCliente($usuario['rol'] ?? null)) {
        try {
            $visualizadorPlanesMarketingModel->solicitarPlan(
                (int) ($_POST['plan_id'] ?? 0),
                (int) ($_POST['pricing_option_id'] ?? 0),
                $usuario,
                $_POST['notes'] ?? null
            );
            $_SESSION['marketing_estado'] = ['estado' => 'ok', 'mensaje' => 'Solicitud enviada. El equipo de marketing la revisara.'];
        } catch (Throwable $e) {
            $_SESSION['marketing_estado'] = ['estado' => 'error', 'mensaje' => $e->getMessage()];
        }
        header('Location: ' . marketingRedireccionRol((string) ($usuario['rol'] ?? '')));
        exit;
    }
}

$marketingPlanesPublicados = $visualizadorPlanesMarketingModel->obtenerPlanesPublicados();
