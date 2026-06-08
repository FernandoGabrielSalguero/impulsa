<?php

declare(strict_types=1);

require_once __DIR__ . '/chatbot_builder_model.php';

const CHATBOT_AVATAR_UPLOAD_DIR = __DIR__ . '/../../../assets/images/avatar_bot';
const CHATBOT_AVATAR_PUBLIC_PATH = '/impulsa_emprende/assets/images/avatar_bot';
const CHATBOT_AVATAR_MAX_BYTES = 2097152;
const CHATBOT_AVATAR_ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];
const CHATBOT_AVATAR_ALLOWED_MIME_TYPES = ['image/jpeg', 'image/png', 'image/webp'];

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

            $avatarPath = resolverAvatarChatbot($_FILES['avatar_file'] ?? null, (string) ($_POST['current_avatar_path'] ?? ''));

            $chatbotBuilderModel->guardarChatbot($chatbotBuilderUserId, (int) $integrationId, [
                'name' => $_POST['name'] ?? '',
                'avatar_url' => $avatarPath,
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

function resolverAvatarChatbot(?array $file, string $currentAvatarPath): ?string
{
    $currentAvatarPath = trim($currentAvatarPath);

    if ($file === null || !isset($file['error'])) {
        return $currentAvatarPath !== '' ? $currentAvatarPath : null;
    }

    if ((int) $file['error'] === UPLOAD_ERR_NO_FILE) {
        return $currentAvatarPath !== '' ? $currentAvatarPath : null;
    }

    if ((int) $file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('No se pudo subir el avatar del chatbot.');
    }

    $tmpName = (string) ($file['tmp_name'] ?? '');
    $originalName = (string) ($file['name'] ?? '');
    $fileSize = (int) ($file['size'] ?? 0);

    if ($tmpName === '' || !is_uploaded_file($tmpName)) {
        throw new RuntimeException('El archivo de avatar no es valido.');
    }

    if ($fileSize <= 0 || $fileSize > CHATBOT_AVATAR_MAX_BYTES) {
        throw new RuntimeException('El avatar debe pesar como maximo 2MB.');
    }

    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    if (!in_array($extension, CHATBOT_AVATAR_ALLOWED_EXTENSIONS, true)) {
        throw new RuntimeException('El avatar solo admite archivos JPG, JPEG, PNG o WEBP.');
    }

    $finfo = function_exists('finfo_open') ? finfo_open(FILEINFO_MIME_TYPE) : false;
    $mimeType = $finfo ? (string) finfo_file($finfo, $tmpName) : '';
    if ($finfo) {
        finfo_close($finfo);
    }

    if ($mimeType === '' || !in_array($mimeType, CHATBOT_AVATAR_ALLOWED_MIME_TYPES, true)) {
        throw new RuntimeException('El archivo subido no tiene un formato de imagen permitido.');
    }

    if (!is_dir(CHATBOT_AVATAR_UPLOAD_DIR) && !mkdir(CHATBOT_AVATAR_UPLOAD_DIR, 0775, true) && !is_dir(CHATBOT_AVATAR_UPLOAD_DIR)) {
        throw new RuntimeException('No se pudo preparar la carpeta de avatares del chatbot.');
    }

    $fileName = 'bot_' . date('Ymd_His') . '_' . bin2hex(random_bytes(6)) . '.' . $extension;
    $destination = CHATBOT_AVATAR_UPLOAD_DIR . DIRECTORY_SEPARATOR . $fileName;

    if (!move_uploaded_file($tmpName, $destination)) {
        throw new RuntimeException('No se pudo guardar la imagen del avatar.');
    }

    return CHATBOT_AVATAR_PUBLIC_PATH . '/' . $fileName;
}
