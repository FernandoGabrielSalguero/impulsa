<?php

declare(strict_types=1);

require_once __DIR__ . '/../_shared/api_integration_helpers.php';

const CHATBOT_ALLOWED_EVENTS = [
    'widget_loaded',
    'bubble_opened',
    'question_viewed',
    'option_clicked',
    'whatsapp_clicked',
    'chat_closed',
];

try {
    apiCargarEnv();
    apiConfigurarCorsBase();

    $metodo = $_SERVER['REQUEST_METHOD'] ?? '';
    if ($metodo === 'OPTIONS') {
        http_response_code(204);
        exit;
    }

    if ($metodo !== 'POST') {
        apiResponderJson(405, false, 'Metodo no permitido. Usa POST.');
    }

    apiValidarContentTypeJson();
    $payload = apiObtenerPayloadJson();
    $datos = validarPayloadEvento($payload);

    $pdo = apiCrearConexionPdo();
    $integracion = apiValidarIntegracion($pdo, $datos['public_key']);
    $chatbot = obtenerChatbotEvento($pdo, $integracion['id'], $datos['chatbot_id']);
    registrarEventoChatbot($pdo, $integracion['id'], $chatbot['id'], $datos);
    apiRegistrarUltimoUsoIntegracion($pdo, $integracion['id']);

    apiResponderJson(201, true, 'Evento registrado correctamente.');
} catch (InvalidArgumentException $exception) {
    apiResponderJson(422, false, $exception->getMessage());
} catch (RuntimeException $exception) {
    $codigo = $exception->getCode();
    $codigoHttp = ($codigo >= 400 && $codigo <= 599) ? $codigo : 500;

    if ($codigoHttp >= 500) {
        error_log('Error interno en API/chatbot_event/index.php: ' . $exception->getMessage());
        apiResponderJson(500, false, 'Error interno del servidor');
    }

    apiResponderJson($codigoHttp, false, $exception->getMessage());
} catch (Throwable $exception) {
    error_log('Error no controlado en API/chatbot_event/index.php: ' . $exception->getMessage());
    apiResponderJson(500, false, 'Error interno del servidor');
}

function validarPayloadEvento(array $payload): array
{
    $publicKey = obtenerTextoEvento($payload, 'public_key', true, 80);
    $eventType = obtenerTextoEvento($payload, 'event_type', true, 40);

    if (!in_array($eventType, CHATBOT_ALLOWED_EVENTS, true)) {
        throw new InvalidArgumentException('El event_type no es valido.');
    }

    $chatbotId = filter_var($payload['chatbot_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($chatbotId === false) {
        throw new InvalidArgumentException('El chatbot_id es obligatorio y debe ser entero positivo.');
    }

    $nodeId = $payload['node_id'] ?? null;
    if ($nodeId !== null && $nodeId !== '') {
        $nodeId = filter_var($nodeId, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if ($nodeId === false) {
            throw new InvalidArgumentException('El node_id debe ser entero positivo.');
        }
    } else {
        $nodeId = null;
    }

    $optionId = $payload['option_id'] ?? null;
    if ($optionId !== null && $optionId !== '') {
        $optionId = filter_var($optionId, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if ($optionId === false) {
            throw new InvalidArgumentException('El option_id debe ser entero positivo.');
        }
    } else {
        $optionId = null;
    }

    $pageUrl = obtenerTextoEvento($payload, 'page_url', false, 500);
    $metadata = $payload['metadata'] ?? null;

    if ($metadata !== null && !is_array($metadata) && !is_string($metadata)) {
        throw new InvalidArgumentException('La metadata enviada no es valida.');
    }

    $metadataJson = null;
    if (is_array($metadata)) {
      $metadataJson = json_encode($metadata, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    } elseif (is_string($metadata)) {
      $metadataJson = trim($metadata);
    }

    if ($metadataJson !== null && apiLongitudTexto($metadataJson) > 2000) {
        throw new InvalidArgumentException('La metadata no puede superar 2000 caracteres.');
    }

    return [
        'public_key' => $publicKey,
        'chatbot_id' => (int) $chatbotId,
        'event_type' => $eventType,
        'node_id' => $nodeId !== null ? (int) $nodeId : null,
        'option_id' => $optionId !== null ? (int) $optionId : null,
        'page_url' => $pageUrl,
        'metadata_json' => $metadataJson,
    ];
}

function obtenerTextoEvento(array $payload, string $field, bool $required, int $maxLength): ?string
{
    if (!array_key_exists($field, $payload) || $payload[$field] === null) {
        if ($required) {
            throw new InvalidArgumentException('El campo ' . $field . ' es obligatorio.');
        }

        return null;
    }

    if (!is_string($payload[$field]) && !is_numeric($payload[$field])) {
        throw new InvalidArgumentException('El campo ' . $field . ' debe ser texto.');
    }

    $value = trim((string) $payload[$field]);
    if ($value === '') {
        if ($required) {
            throw new InvalidArgumentException('El campo ' . $field . ' es obligatorio.');
        }

        return null;
    }

    if (apiLongitudTexto($value) > $maxLength) {
        throw new InvalidArgumentException('El campo ' . $field . ' supera la longitud maxima permitida.');
    }

    return $value;
}

function obtenerChatbotEvento(PDO $pdo, int $integrationId, int $chatbotId): array
{
    $stmt = $pdo->prepare(
        'SELECT id
         FROM chatbots
         WHERE id = :id
           AND api_integration_id = :api_integration_id
         LIMIT 1'
    );
    $stmt->execute([
        ':id' => $chatbotId,
        ':api_integration_id' => $integrationId,
    ]);
    $chatbot = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!is_array($chatbot)) {
        throw new RuntimeException('El chatbot no pertenece a la integracion indicada.', 403);
    }

    return $chatbot;
}

function registrarEventoChatbot(PDO $pdo, int $integrationId, int $chatbotId, array $datos): void
{
    $ip = trim((string) ($_SERVER['REMOTE_ADDR'] ?? ''));
    $userAgent = trim((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''));
    $ipHash = $ip !== '' ? hash('sha256', $ip) : null;

    $stmt = $pdo->prepare(
        'INSERT INTO chatbot_events
         (chatbot_id, api_integration_id, event_type, node_id, option_id, page_url, metadata_json, ip_hash, user_agent, created_at)
         VALUES
         (:chatbot_id, :api_integration_id, :event_type, :node_id, :option_id, :page_url, :metadata_json, :ip_hash, :user_agent, CURRENT_TIMESTAMP)'
    );
    $stmt->execute([
        ':chatbot_id' => $chatbotId,
        ':api_integration_id' => $integrationId,
        ':event_type' => $datos['event_type'],
        ':node_id' => $datos['node_id'],
        ':option_id' => $datos['option_id'],
        ':page_url' => $datos['page_url'],
        ':metadata_json' => $datos['metadata_json'],
        ':ip_hash' => $ipHash,
        ':user_agent' => $userAgent !== '' ? substr($userAgent, 0, 255) : null,
    ]);
}
