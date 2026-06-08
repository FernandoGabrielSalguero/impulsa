<?php

declare(strict_types=1);

require_once __DIR__ . '/../_shared/api_integration_helpers.php';

try {
    apiCargarEnv();
    apiConfigurarCorsPersonalizado(['GET', 'OPTIONS'], ['Content-Type', 'X-API-SECRET']);

    $metodo = $_SERVER['REQUEST_METHOD'] ?? '';
    if ($metodo === 'OPTIONS') {
        http_response_code(204);
        exit;
    }

    if ($metodo !== 'GET') {
        apiResponderJson(405, false, 'Metodo no permitido. Usa GET.');
    }

    $publicKey = trim((string) ($_GET['public_key'] ?? $_GET['key'] ?? ''));
    if ($publicKey === '') {
        throw new InvalidArgumentException('El parametro public_key es obligatorio.');
    }

    $pdo = apiCrearConexionPdo();
    $integracion = apiValidarIntegracion($pdo, $publicKey);
    $chatbot = obtenerChatbotPublico($pdo, $integracion['id']);
    apiRegistrarUltimoUsoIntegracion($pdo, $integracion['id']);

    if ($chatbot === null) {
        apiResponderJson(200, true, 'Sin chatbot activo para esta integracion.', [
            'has_chatbot' => false,
        ]);
    }

    apiResponderJson(200, true, 'Chatbot cargado correctamente.', [
        'has_chatbot' => true,
        'chatbot' => $chatbot,
    ]);
} catch (InvalidArgumentException $exception) {
    apiResponderJson(422, false, $exception->getMessage());
} catch (RuntimeException $exception) {
    $codigo = $exception->getCode();
    $codigoHttp = ($codigo >= 400 && $codigo <= 599) ? $codigo : 500;

    if ($codigoHttp >= 500) {
        error_log('Error interno en API/chatbot_config/index.php: ' . $exception->getMessage());
        apiResponderJson(500, false, 'Error interno del servidor');
    }

    apiResponderJson($codigoHttp, false, $exception->getMessage());
} catch (Throwable $exception) {
    error_log('Error no controlado en API/chatbot_config/index.php: ' . $exception->getMessage());
    apiResponderJson(500, false, 'Error interno del servidor');
}

function obtenerChatbotPublico(PDO $pdo, int $integrationId): ?array
{
    $stmt = $pdo->prepare(
        'SELECT id, name, avatar_url, whatsapp, initial_message
         FROM chatbots
         WHERE api_integration_id = :api_integration_id
           AND status = \'active\'
           AND disabled_by_admin = 0
         LIMIT 1'
    );
    $stmt->execute([':api_integration_id' => $integrationId]);
    $chatbot = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!is_array($chatbot)) {
        return null;
    }

    $stmtNodes = $pdo->prepare(
        'SELECT id, title, body, sort_order, is_start
         FROM chatbot_nodes
         WHERE chatbot_id = :chatbot_id
           AND status = \'active\'
         ORDER BY sort_order ASC, id ASC'
    );
    $stmtNodes->execute([':chatbot_id' => (int) $chatbot['id']]);
    $nodes = $stmtNodes->fetchAll(PDO::FETCH_ASSOC);

    if ($nodes === []) {
        return null;
    }

    $nodeIds = array_map(static fn (array $node): int => (int) $node['id'], $nodes);
    $stmtOptions = $pdo->prepare(
        'SELECT id, node_id, label, action_type, target_node_id, sort_order
         FROM chatbot_node_options
         WHERE node_id IN (' . implode(',', array_fill(0, count($nodeIds), '?')) . ')
         ORDER BY sort_order ASC, id ASC'
    );
    $stmtOptions->execute($nodeIds);
    $options = $stmtOptions->fetchAll(PDO::FETCH_ASSOC);

    $optionsByNode = [];
    foreach ($options as $option) {
        $optionsByNode[(int) ($option['node_id'] ?? 0)][] = [
            'id' => (int) ($option['id'] ?? 0),
            'label' => (string) ($option['label'] ?? ''),
            'action_type' => (string) ($option['action_type'] ?? ''),
            'target_node_id' => isset($option['target_node_id']) ? (int) $option['target_node_id'] : null,
            'sort_order' => (int) ($option['sort_order'] ?? 0),
        ];
    }

    $startNodeId = null;
    $publicNodes = [];
    foreach ($nodes as $index => $node) {
        $nodeId = (int) ($node['id'] ?? 0);
        if ($startNodeId === null && ((int) ($node['is_start'] ?? 0) === 1 || $index === 0)) {
            $startNodeId = $nodeId;
        }

        $publicNodes[] = [
            'id' => $nodeId,
            'title' => (string) ($node['title'] ?? ''),
            'body' => (string) ($node['body'] ?? ''),
            'sort_order' => (int) ($node['sort_order'] ?? 0),
            'options' => $optionsByNode[$nodeId] ?? [],
        ];
    }

    return [
        'id' => (int) ($chatbot['id'] ?? 0),
        'name' => (string) ($chatbot['name'] ?? ''),
        'avatar_url' => normalizarAvatarUrlPublica((string) ($chatbot['avatar_url'] ?? '')),
        'initial_message' => (string) ($chatbot['initial_message'] ?? ''),
        'whatsapp' => (string) ($chatbot['whatsapp'] ?? ''),
        'start_node_id' => $startNodeId,
        'nodes' => $publicNodes,
    ];
}

function normalizarAvatarUrlPublica(string $avatarUrl): string
{
    $avatarUrl = trim($avatarUrl);

    if ($avatarUrl === '') {
        return '';
    }

    if (preg_match('/^https?:\/\//i', $avatarUrl) === 1) {
        return $avatarUrl;
    }

    if (str_starts_with($avatarUrl, '/impulsa_emprende/assets/images/avatar_bot/')) {
        return '/assets/images/avatar_bot/' . basename($avatarUrl);
    }

    if (str_starts_with($avatarUrl, '/assets/images/avatar_bot/')) {
        return $avatarUrl;
    }

    return '/assets/images/avatar_bot/' . ltrim(basename($avatarUrl), '/');
}
