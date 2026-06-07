<?php

require_once __DIR__ . '/../../../auth/auth_helpers.php';
require_once __DIR__ . '/../../model/client/ClienteDashboardModel.php';

$usuario = authRequiereRol('impulsa_cliente');
$usuarioCorreo = (string) ($usuario['correo'] ?? '');
$usuarioInicial = obtenerInicialAvatar($usuarioCorreo);

$dashboardModel = new ClienteDashboardModel($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion_cliente'] ?? '') === 'firmar_contrato') {
    $contractId = (int) ($_POST['contract_id'] ?? 0);
    $estado = 'error';
    $mensaje = 'No pudimos registrar la firma del contrato.';

    try {
        if ($contractId <= 0) {
            throw new RuntimeException('El contrato seleccionado no es valido.');
        }

        $firmante = $dashboardModel->obtenerNombreFirmante((int) $usuario['id']);
        $ipFirma = trim((string) ($_SERVER['REMOTE_ADDR'] ?? ''));
        $firmado = $dashboardModel->firmarContratoCliente(
            $contractId,
            (int) $usuario['id'],
            $firmante,
            $ipFirma !== '' ? $ipFirma : null
        );

        if (!$firmado) {
            throw new RuntimeException('El contrato ya fue firmado o no pertenece a tu cuenta.');
        }

        $estado = 'ok';
        $mensaje = 'Contrato firmado correctamente.';
    } catch (Throwable $e) {
        $mensaje = $e->getMessage();
    }

    $_SESSION['cliente_dashboard_contrato_estado'] = ['estado' => $estado, 'mensaje' => $mensaje];
    header('Location: /impulsa_emprende/controller/client/ClienteDashboardController.php');
    exit;
}

$dashboardData = $dashboardModel->obtenerDashboard((int) $usuario['id']);
$mensajeContratoCliente = $_SESSION['cliente_dashboard_contrato_estado'] ?? null;
unset($_SESSION['cliente_dashboard_contrato_estado']);

require __DIR__ . '/../../partials/bottom_sheet_perfil/perfilController.php';

$usuarioAvatarUrl = $perfilAvatarUrl;
$usuarioMarcaNombre = trim((string) ($perfilDatos['apodo'] ?? ''));
if ($usuarioMarcaNombre === '') {
    $usuarioMarcaNombre = trim((string) ($perfilDatos['nombre'] ?? ''));
}
if ($usuarioMarcaNombre === '') {
    $usuarioMarcaNombre = 'Cliente';
}

require __DIR__ . '/../../view/client/ClienteDashboardView.php';
