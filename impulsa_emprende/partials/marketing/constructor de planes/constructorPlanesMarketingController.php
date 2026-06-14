<?php

require_once __DIR__ . '/../marketingShared.php';
require_once __DIR__ . '/constructorPlanesMarketingModel.php';

$constructorPlanesMarketingModel = new ConstructorPlanesMarketingModel($pdo);
$marketingConstructorMensaje = $_SESSION['marketing_estado'] ?? null;
$marketingEsAjax = strtolower((string) ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '')) === 'xmlhttprequest'
    || (str_contains((string) ($_SERVER['HTTP_ACCEPT'] ?? ''), 'application/json'));
$marketingResponderJson = static function (array $payload, int $status = 200): void {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
};

if ($_SERVER['REQUEST_METHOD'] === 'POST' && marketingUsuarioPuedeGestionar($usuario['rol'] ?? null)) {
    $accionMarketing = (string) ($_POST['marketing_action'] ?? '');
    if (str_starts_with($accionMarketing, 'plan_') || str_starts_with($accionMarketing, 'feature_') || str_starts_with($accionMarketing, 'pricing_')) {
        try {
            if ($accionMarketing === 'plan_save' || $accionMarketing === 'plan_save_full') {
                $planIdGuardado = $accionMarketing === 'plan_save_full'
                    ? $constructorPlanesMarketingModel->guardarPlanCompleto($_POST, (int) $usuario['id'])
                    : $constructorPlanesMarketingModel->guardarPlan($_POST, (int) $usuario['id']);
                $_SESSION['marketing_plan_activo'] = $planIdGuardado;
                $_SESSION['marketing_estado'] = ['estado' => 'ok', 'mensaje' => 'Plan guardado correctamente.'];
                if ($marketingEsAjax) {
                    $marketingResponderJson([
                        'ok' => true,
                        'message' => 'Plan guardado correctamente.',
                        'plan' => $constructorPlanesMarketingModel->obtenerPlanCompleto($planIdGuardado),
                    ]);
                }
            } elseif ($accionMarketing === 'plan_delete') {
                $constructorPlanesMarketingModel->eliminarPlan((int) ($_POST['plan_id'] ?? 0));
                unset($_SESSION['marketing_plan_activo']);
                $_SESSION['marketing_estado'] = ['estado' => 'ok', 'mensaje' => 'Plan eliminado.'];
                if ($marketingEsAjax) {
                    $marketingResponderJson(['ok' => true, 'deleted' => true, 'message' => 'Plan eliminado.']);
                }
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
            if ($marketingEsAjax) {
                $marketingResponderJson(['ok' => false, 'message' => $e->getMessage()], 400);
            }
        }
        header('Location: ' . marketingRedireccionRol((string) ($usuario['rol'] ?? '')));
        exit;
    }
}

$marketingPlanesAdmin = marketingUsuarioPuedeGestionar($usuario['rol'] ?? null) ? $constructorPlanesMarketingModel->obtenerPlanes() : [];
$marketingPlanesCompletos = [];
foreach ($marketingPlanesAdmin as $marketingPlanResumen) {
    $marketingPlanCompleto = $constructorPlanesMarketingModel->obtenerPlanCompleto((int) ($marketingPlanResumen['id'] ?? 0));
    if ($marketingPlanCompleto) {
        $marketingPlanesCompletos[] = $marketingPlanCompleto;
    }
}
$marketingPlanActivoId = (int) ($_SESSION['marketing_plan_activo'] ?? 0);
$marketingPlanActivo = $marketingPlanActivoId > 0 ? $constructorPlanesMarketingModel->obtenerPlanCompleto($marketingPlanActivoId) : null;
unset($_SESSION['marketing_plan_activo']);
