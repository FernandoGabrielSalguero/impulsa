<?php

declare(strict_types=1);

require_once __DIR__ . '/../../../auth/auth_helpers.php';
require_once __DIR__ . '/../../model/admin/adminChatbotModel.php';

$usuario = authRequiereRol('impulsa_administrador');
$usuarioCorreo = (string) ($usuario['correo'] ?? '');
$usuarioInicial = obtenerInicialAvatar($usuarioCorreo);
$adminChatbotModel = new AdminChatbotModel($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['admin_chatbot_action'] ?? '') !== '') {
    try {
        $chatbotId = filter_var($_POST['chatbot_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if ($chatbotId === false) {
            throw new RuntimeException('El chatbot seleccionado no es valido.');
        }

        $chatbot = $adminChatbotModel->obtenerChatbotPorId((int) $chatbotId);
        if ($chatbot === null) {
            throw new RuntimeException('El chatbot seleccionado no existe.');
        }

        $action = trim((string) ($_POST['admin_chatbot_action'] ?? ''));
        if ($action === 'disable') {
            $adminChatbotModel->actualizarBloqueoAdmin((int) $chatbotId, true);
            $_SESSION['admin_chatbot_flash'] = ['estado' => 'ok', 'mensaje' => 'Chatbot desactivado por administracion.'];
        } elseif ($action === 'enable') {
            $adminChatbotModel->actualizarBloqueoAdmin((int) $chatbotId, false);
            $_SESSION['admin_chatbot_flash'] = ['estado' => 'ok', 'mensaje' => 'Chatbot rehabilitado por administracion.'];
        } else {
            throw new RuntimeException('La accion solicitada no es valida.');
        }
    } catch (Throwable $exception) {
        $_SESSION['admin_chatbot_flash'] = ['estado' => 'error', 'mensaje' => $exception->getMessage()];
    }

    header('Location: /impulsa_emprende/controller/admin/adminChatbotController.php');
    exit;
}

$chatbotResumen = $adminChatbotModel->obtenerResumen();
$chatbots = $adminChatbotModel->obtenerChatbots();
$flashChatbots = $_SESSION['admin_chatbot_flash'] ?? null;
unset($_SESSION['admin_chatbot_flash']);

require __DIR__ . '/../../partials/bottom_sheet_perfil/perfilController.php';

$usuarioAvatarUrl = $perfilAvatarUrl;
$usuarioMarcaNombre = trim((string) ($perfilDatos['apodo'] ?? ''));
if ($usuarioMarcaNombre === '') {
    $usuarioMarcaNombre = trim((string) ($perfilDatos['nombre'] ?? ''));
}
if ($usuarioMarcaNombre === '') {
    $usuarioMarcaNombre = 'Usuario';
}

require __DIR__ . '/../../view/admin/adminChatbotView.php';
