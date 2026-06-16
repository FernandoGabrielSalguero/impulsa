<?php

require_once __DIR__ . '/../marketingShared.php';
require_once __DIR__ . '/monitorPlanesMarketingModel.php';

$monitorPlanesMarketingModel = new MonitorPlanesMarketingModel($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && marketingUsuarioPuedeGestionar($usuario['rol'] ?? null)) {
    $accionMarketing = (string) ($_POST['marketing_action'] ?? '');
    if ($accionMarketing === 'subscription_status_save' || $accionMarketing === 'meta_csv_import') {
        try {
            if ($accionMarketing === 'subscription_status_save') {
                $monitorPlanesMarketingModel->cambiarEstadoSuscripcion(
                    (int) ($_POST['subscription_id'] ?? 0),
                    (string) ($_POST['status'] ?? 'requested'),
                    (int) ($usuario['id'] ?? 0)
                );
                $_SESSION['marketing_estado'] = ['estado' => 'ok', 'mensaje' => 'Estado de suscripcion actualizado.'];
            } elseif ($accionMarketing === 'meta_csv_import') {
                $asignaciones = [];
                foreach (($_POST['manual_campaign'] ?? []) as $external => $campaignId) {
                    $asignaciones[(string) $external] = (int) $campaignId;
                }
                $resultado = $monitorPlanesMarketingModel->importarCsvMeta($_FILES['meta_csv'] ?? [], $asignaciones, (int) ($usuario['id'] ?? 0));
                $_SESSION['marketing_estado'] = [
                    'estado' => $resultado['unresolved'] > 0 ? 'error' : 'ok',
                    'mensaje' => 'CSV procesado: ' . $resultado['imported'] . ' importadas, ' . $resultado['unresolved'] . ' sin match.',
                ];
            }
        } catch (Throwable $e) {
            $_SESSION['marketing_estado'] = ['estado' => 'error', 'mensaje' => $e->getMessage()];
        }
        header('Location: ' . ($marketingRedirectUrl ?? marketingRedireccionRol((string) ($usuario['rol'] ?? ''))));
        exit;
    }
}

$marketingSuscripciones = marketingUsuarioPuedeGestionar($usuario['rol'] ?? null) ? $monitorPlanesMarketingModel->obtenerSuscripciones() : [];
$marketingCampanias = marketingUsuarioPuedeGestionar($usuario['rol'] ?? null) ? $monitorPlanesMarketingModel->obtenerCampanias() : [];
