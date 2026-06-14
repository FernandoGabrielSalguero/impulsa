<?php

require_once __DIR__ . '/../marketingShared.php';
require_once __DIR__ . '/constructorPlanesMarketingModel.php';

$constructorPlanesMarketingModel = new ConstructorPlanesMarketingModel($pdo);
$marketingConstructorMensaje = $_SESSION['marketing_estado'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && marketingUsuarioPuedeGestionar($usuario['rol'] ?? null)) {
    $accionMarketing = (string) ($_POST['marketing_action'] ?? '');
    if (str_starts_with($accionMarketing, 'plan_') || str_starts_with($accionMarketing, 'feature_') || str_starts_with($accionMarketing, 'pricing_')) {
        try {
            if ($accionMarketing === 'plan_save') {
                $planIdGuardado = $constructorPlanesMarketingModel->guardarPlan($_POST, (int) $usuario['id']);
                $_SESSION['marketing_plan_activo'] = $planIdGuardado;
                $_SESSION['marketing_estado'] = ['estado' => 'ok', 'mensaje' => 'Plan guardado correctamente.'];
            } elseif ($accionMarketing === 'plan_delete') {
                $constructorPlanesMarketingModel->eliminarPlan((int) ($_POST['plan_id'] ?? 0));
                unset($_SESSION['marketing_plan_activo']);
                $_SESSION['marketing_estado'] = ['estado' => 'ok', 'mensaje' => 'Plan eliminado.'];
            } elseif ($accionMarketing === 'feature_save') {
                $constructorPlanesMarketingModel->guardarFeature($_POST);
                $_SESSION['marketing_plan_activo'] = (int) ($_POST['plan_id'] ?? 0);
                $_SESSION['marketing_estado'] = ['estado' => 'ok', 'mensaje' => 'Item del plan agregado.'];
            } elseif ($accionMarketing === 'feature_delete') {
                $constructorPlanesMarketingModel->eliminarFeature((int) ($_POST['feature_id'] ?? 0));
                $_SESSION['marketing_plan_activo'] = (int) ($_POST['plan_id'] ?? 0);
                $_SESSION['marketing_estado'] = ['estado' => 'ok', 'mensaje' => 'Item eliminado.'];
            } elseif ($accionMarketing === 'pricing_save') {
                $constructorPlanesMarketingModel->guardarPrecio($_POST);
                $_SESSION['marketing_plan_activo'] = (int) ($_POST['plan_id'] ?? 0);
                $_SESSION['marketing_estado'] = ['estado' => 'ok', 'mensaje' => 'Opcion de precio agregada.'];
            } elseif ($accionMarketing === 'pricing_delete') {
                $constructorPlanesMarketingModel->eliminarPrecio((int) ($_POST['pricing_id'] ?? 0));
                $_SESSION['marketing_plan_activo'] = (int) ($_POST['plan_id'] ?? 0);
                $_SESSION['marketing_estado'] = ['estado' => 'ok', 'mensaje' => 'Opcion de precio eliminada.'];
            }
        } catch (Throwable $e) {
            $_SESSION['marketing_estado'] = ['estado' => 'error', 'mensaje' => $e->getMessage()];
        }
        header('Location: ' . marketingRedireccionRol((string) ($usuario['rol'] ?? '')));
        exit;
    }
}

$marketingPlanesAdmin = marketingUsuarioPuedeGestionar($usuario['rol'] ?? null) ? $constructorPlanesMarketingModel->obtenerPlanes() : [];
$marketingPlanActivoId = (int) ($_SESSION['marketing_plan_activo'] ?? ($marketingPlanesAdmin[0]['id'] ?? 0));
$marketingPlanActivo = $marketingPlanActivoId > 0 ? $constructorPlanesMarketingModel->obtenerPlanCompleto($marketingPlanActivoId) : null;
