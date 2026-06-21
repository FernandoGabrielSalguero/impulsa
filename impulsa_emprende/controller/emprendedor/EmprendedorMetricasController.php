<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../auth/auth_helpers.php';
require_once __DIR__ . '/../../model/emprendedor/EmprendedorMetricasModel.php';
require_once __DIR__ . '/../../partials/components/metrics/form_contact/form_contact_model.php';

$usuario = authRequiereRol('impulsa_emprendedor');
$usuarioCorreo = (string) ($usuario['correo'] ?? '');
$usuarioInicial = obtenerInicialAvatar($usuarioCorreo);

$emprendedorMetricasModel = new EmprendedorMetricasModel($pdo);
$clienteMetricasData = $emprendedorMetricasModel->obtenerContexto((int) $usuario['id']);
$clienteMetricasIntegraciones = $clienteMetricasData['integraciones'] ?? [];
$formContactIntegrationIds = array_values(array_map(
    static fn (array $integracion): int => (int) ($integracion['id'] ?? 0),
    array_filter($clienteMetricasIntegraciones, static fn (array $integracion): bool => (int) ($integracion['id'] ?? 0) > 0)
));

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_GET['ajax'] ?? '') === 'actualizar_contacto_estado') {
    header('Content-Type: application/json; charset=UTF-8');

    $contactId = filter_input(INPUT_POST, 'contact_id', FILTER_VALIDATE_INT);
    $state = trim((string) ($_POST['state'] ?? ''));

    if (!$contactId || $contactId <= 0 || $state === '') {
        http_response_code(422);
        echo json_encode([
            'ok' => false,
            'message' => 'No se pudo validar el contacto o el estado enviado.',
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    $formContactMetricsModel = new FormContactMetricsModel($pdo);
    $actualizado = $formContactMetricsModel->actualizarEstadoContacto($contactId, $formContactIntegrationIds, $state);

    if (!$actualizado) {
        http_response_code(404);
        echo json_encode([
            'ok' => false,
            'message' => 'No se encontro el contacto seleccionado para actualizar.',
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    echo json_encode([
        'ok' => true,
        'state' => $state,
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

require __DIR__ . '/../../partials/bottom_sheet_perfil/perfilController.php';

$usuarioAvatarUrl = $perfilAvatarUrl;
$usuarioMarcaNombre = trim((string) ($perfilDatos['apodo'] ?? ''));
if ($usuarioMarcaNombre === '') {
    $usuarioMarcaNombre = trim((string) ($perfilDatos['nombre'] ?? ''));
}
if ($usuarioMarcaNombre === '') {
    $usuarioMarcaNombre = 'Cliente';
}

require __DIR__ . '/../../view/emprendedor/EmprendedorMetricasView.php';
