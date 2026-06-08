<?php

declare(strict_types=1);

require_once __DIR__ . '/chatbot_builder_model.php';

$chatbotBuilderContext = $chatbotBuilderContext ?? [];
$chatbotBuilderUser = $chatbotBuilderContext['user'] ?? null;
$chatbotBuilderRoleLabel = (string) ($chatbotBuilderContext['role_label'] ?? 'Usuario');
$chatbotBuilderPageTitle = (string) ($chatbotBuilderContext['page_title'] ?? 'Chatbot');
$chatbotBuilderPageDescription = (string) ($chatbotBuilderContext['page_description'] ?? '');
$chatbotBuilderBackHref = (string) ($chatbotBuilderContext['back_href'] ?? '#');
$chatbotBuilderBackLabel = (string) ($chatbotBuilderContext['back_label'] ?? 'Volver');
$chatbotBuilderNavItems = is_array($chatbotBuilderContext['nav_items'] ?? null) ? $chatbotBuilderContext['nav_items'] : [];
$chatbotBuilderFlashKey = (string) ($chatbotBuilderContext['flash_key'] ?? 'chatbot_builder_flash');
$chatbotBuilderPostAction = (string) ($chatbotBuilderContext['post_action'] ?? '');
$chatbotBuilderModel = new ChatbotBuilderModel($pdo);

if (!is_array($chatbotBuilderUser) || (int) ($chatbotBuilderUser['id'] ?? 0) <= 0) {
    throw new RuntimeException('No se pudo identificar al usuario autenticado.');
}

$chatbotBuilderUserId = (int) $chatbotBuilderUser['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && (($chatbotBuilderPostAction !== '' && ($_POST['chatbot_builder_action'] ?? '') === $chatbotBuilderPostAction) || $chatbotBuilderPostAction === '')) {
    try {
        $action = trim((string) ($_POST['chatbot_builder_submit'] ?? 'save'));
        $integrationId = filter_var($_POST['api_integration_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

        if ($integrationId === false) {
            throw new RuntimeException('Debes seleccionar una integracion valida.');
        }

        if ($action === 'toggle') {
            $status = trim((string) ($_POST['target_status'] ?? 'inactive'));
            $chatbotBuilderModel->actualizarEstado($chatbotBuilderUserId, (int) $integrationId, $status);
            $_SESSION[$chatbotBuilderFlashKey] = [
                'estado' => 'ok',
                'mensaje' => $status === 'active' ? 'Chatbot activado correctamente.' : 'Chatbot desactivado correctamente.',
            ];
        } else {
            $nodesJson = trim((string) ($_POST['nodes_json'] ?? ''));
            $nodes = json_decode($nodesJson, true);

            if (!is_array($nodes)) {
                throw new RuntimeException('No se pudo interpretar la estructura de nodos enviada desde el constructor.');
            }

            $chatbotBuilderModel->guardarChatbot($chatbotBuilderUserId, (int) $integrationId, [
                'name' => $_POST['name'] ?? '',
                'avatar_url' => $_POST['avatar_url'] ?? '',
                'whatsapp' => $_POST['whatsapp'] ?? '',
                'initial_message' => $_POST['initial_message'] ?? '',
                'status' => $_POST['status'] ?? 'inactive',
                'nodes' => $nodes,
            ]);
            $_SESSION[$chatbotBuilderFlashKey] = [
                'estado' => 'ok',
                'mensaje' => 'Chatbot guardado correctamente. Los cambios impactan de inmediato en produccion.',
            ];
        }
    } catch (Throwable $exception) {
        $_SESSION[$chatbotBuilderFlashKey] = [
            'estado' => 'error',
            'mensaje' => $exception->getMessage() !== '' ? $exception->getMessage() : 'No se pudo guardar el chatbot.',
        ];
    }

    header('Location: ' . (string) ($_SERVER['REQUEST_URI'] ?? ''));
    exit;
}

$chatbotBuilderFlash = $_SESSION[$chatbotBuilderFlashKey] ?? null;
unset($_SESSION[$chatbotBuilderFlashKey]);

$chatbotBuilderUserContext = $chatbotBuilderModel->obtenerContextoUsuario($chatbotBuilderUserId);
$chatbotBuilderIntegraciones = $chatbotBuilderUserContext['integraciones'] ?? [];
$chatbotBuilderWhatsappSugerido = $chatbotBuilderUserContext['whatsapp'] ?? '';
$chatbotBuilderSelectedIntegrationId = filter_var($_GET['integration_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

if ($chatbotBuilderSelectedIntegrationId === false && $chatbotBuilderIntegraciones !== []) {
    $chatbotBuilderSelectedIntegrationId = (int) ($chatbotBuilderIntegraciones[0]['id'] ?? 0);
}

$chatbotBuilderSelectedIntegration = null;
$chatbotBuilderChatbotActual = null;
foreach ($chatbotBuilderIntegraciones as $chatbotBuilderIntegrationItem) {
    if ((int) ($chatbotBuilderIntegrationItem['id'] ?? 0) === (int) $chatbotBuilderSelectedIntegrationId) {
        $chatbotBuilderSelectedIntegration = $chatbotBuilderIntegrationItem;
        $chatbotBuilderChatbotActual = $chatbotBuilderModel->obtenerChatbotPorIntegracion((int) $chatbotBuilderSelectedIntegrationId);
        break;
    }
}

require __DIR__ . '/chatbot_builder_view.php';
