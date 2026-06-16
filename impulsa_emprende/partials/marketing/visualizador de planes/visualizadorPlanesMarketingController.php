<?php

require_once __DIR__ . '/../marketingShared.php';
require_once __DIR__ . '/visualizadorPlanesMarketingModel.php';
require_once __DIR__ . '/../../../mail/Mail.php';

$visualizadorPlanesMarketingModel = new VisualizadorPlanesMarketingModel($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accionMarketing = (string) ($_POST['marketing_action'] ?? '');
    if ($accionMarketing === 'subscription_request' && marketingUsuarioPuedeVerCliente($usuario['rol'] ?? null)) {
        try {
            $subscriptionId = $visualizadorPlanesMarketingModel->solicitarPlan(
                (int) ($_POST['plan_id'] ?? 0),
                (int) ($_POST['pricing_option_id'] ?? 0),
                $usuario,
                $_POST['notes'] ?? null
            );
            $marketingUsers = $visualizadorPlanesMarketingModel->obtenerUsuariosMarketing();
            $detalle = $visualizadorPlanesMarketingModel->obtenerDetalleSolicitud($subscriptionId);
            $resultadoCorreo = ['ok' => false, 'error' => 'No se encontraron usuarios marketing.'];
            if ($marketingUsers && $detalle) {
                $features = array_map(static function (array $feature): string {
                    $nombre = htmlspecialchars((string) ($feature['feature_name'] ?? ''), ENT_QUOTES, 'UTF-8');
                    $cantidad = trim((string) ($feature['quantity'] ?? '') . ' ' . (string) ($feature['unit'] ?? ''));
                    $descripcion = htmlspecialchars((string) ($feature['feature_description'] ?? ''), ENT_QUOTES, 'UTF-8');
                    return '<tr><td>' . $nombre . '</td><td>' . htmlspecialchars($cantidad !== '' ? $cantidad : '-', ENT_QUOTES, 'UTF-8') . '</td><td>' . ($descripcion !== '' ? $descripcion : 'Incluido en el plan') . '</td></tr>';
                }, $detalle['features'] ?? []);
                $itemsHtml = '<table class="items"><thead><tr><th>Item</th><th>Cantidad</th><th>Detalle</th></tr></thead><tbody>' . implode('', $features) . '</tbody></table>';
                $solicitanteNombre = trim((string) ($detalle['client_nombre'] ?? $detalle['entrepreneur_nombre'] ?? '') . ' ' . (string) ($detalle['client_apellido'] ?? $detalle['entrepreneur_apellido'] ?? ''));
                if ($solicitanteNombre === '') {
                    $solicitanteNombre = (string) ($detalle['client_apodo'] ?? $detalle['entrepreneur_apodo'] ?? 'Usuario');
                }
                $solicitanteCorreo = (string) ($detalle['client_email'] ?? $detalle['entrepreneur_email'] ?? $usuario['correo'] ?? '');
                $budgetMin = (float) ($detalle['recommended_ad_budget_min'] ?? 0);
                $budgetMax = (float) ($detalle['recommended_ad_budget_max'] ?? 0);
                $inversionSugerida = $budgetMin > 0 || $budgetMax > 0
                    ? '$' . number_format($budgetMin ?: $budgetMax, 0, ',', '.') . ($budgetMin > 0 && $budgetMax > 0 ? ' a $' . number_format($budgetMax, 0, ',', '.') : '')
                    : 'Sin especificar';
                $payloadCorreoMarketing = [
                    'solicitante' => $solicitanteNombre,
                    'solicitante_correo' => $solicitanteCorreo,
                    'solicitante_rol' => (string) ($usuario['rol'] ?? ''),
                    'plan' => (string) ($detalle['plan_name'] ?? ''),
                    'descripcion' => (string) ($detalle['full_description'] ?? $detalle['short_description'] ?? ''),
                    'objetivo' => (string) ($detalle['objective'] ?? 'Sin especificar'),
                    'reportes' => (string) ($detalle['report_frequency'] ?? 'Sin especificar'),
                    'soporte' => (string) ($detalle['support_level'] ?? 'Sin especificar'),
                    'cobro' => (string) ($detalle['billing_period'] ?? 'Sin especificar'),
                    'inversion_sugerida' => $inversionSugerida,
                    'duracion' => (int) ($detalle['duration_months'] ?? 0) . ' meses',
                    'precio_mensual' => '$' . number_format((float) ($detalle['monthly_price'] ?? 0), 0, ',', '.'),
                    'precio_total' => '$' . number_format((float) ($detalle['total_contract_value'] ?? 0), 0, ',', '.'),
                    'estado' => 'Solicitado',
                    'fecha' => date('d/m/Y H:i'),
                    'notas' => (string) ($detalle['notes'] ?? 'Sin notas.'),
                    'items_html' => $itemsHtml,
                    'link' => 'https://impulsagroup.com/ingreso.html',
                    'meta' => ['subscription_id' => $subscriptionId, 'plan_id' => (int) ($detalle['plan_id'] ?? 0)],
                ];
                $enviosCorrectos = 0;
                $erroresEnvio = [];
                foreach ($marketingUsers as $marketingUser) {
                    $resultadoEnvio = \SVE\Mail\Mailer::enviarSolicitudMarketing($payloadCorreoMarketing + [
                        'correo' => (string) ($marketingUser['correo'] ?? ''),
                        'user_auth_id' => (int) ($marketingUser['id'] ?? 0),
                    ]);
                    if (($resultadoEnvio['ok'] ?? false) === true) {
                        $enviosCorrectos++;
                        continue;
                    }
                    $erroresEnvio[] = (string) ($marketingUser['correo'] ?? 'sin correo') . ': ' . (string) ($resultadoEnvio['error'] ?? 'Error no informado.');
                }
                $resultadoCorreo = $enviosCorrectos > 0
                    ? ['ok' => true, 'enviados' => $enviosCorrectos, 'fallidos' => count($erroresEnvio), 'errores' => $erroresEnvio]
                    : ['ok' => false, 'error' => implode(' | ', $erroresEnvio) ?: 'No se pudo enviar el correo a ningun usuario marketing.'];
            }
            if (($resultadoCorreo['ok'] ?? false) === true) {
                $mensajeCorreo = 'Solicitud enviada. El equipo de marketing la revisara.';
                if ((int) ($resultadoCorreo['fallidos'] ?? 0) > 0) {
                    $mensajeCorreo .= ' Algunos correos de marketing no pudieron enviarse.';
                }
                $_SESSION['marketing_estado'] = ['estado' => 'ok', 'mensaje' => $mensajeCorreo];
            } else {
                $_SESSION['marketing_estado'] = ['estado' => 'ok', 'mensaje' => 'Solicitud guardada. No pudimos enviar el correo a marketing: ' . (string) ($resultadoCorreo['error'] ?? 'Error no informado.')];
            }
        } catch (Throwable $e) {
            $_SESSION['marketing_estado'] = ['estado' => 'error', 'mensaje' => $e->getMessage()];
        }
        header('Location: ' . marketingRedireccionRol((string) ($usuario['rol'] ?? '')));
        exit;
    }
}

$marketingPlanesPublicados = $visualizadorPlanesMarketingModel->obtenerPlanesPublicados();
