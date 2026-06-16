<?php

declare(strict_types=1);

require_once __DIR__ . '/../../api_content/api_content_shared_model.php';

final class ChatbotBuilderModel
{
    private const CHATBOT_STATUSES = ['active', 'inactive'];
    private const NODE_STATUSES = ['active', 'inactive'];
    private const ACTION_TYPES = ['go_to_node', 'whatsapp', 'restart', 'close'];
    private ApiIntegrationAccessModel $integrationAccessModel;

    public function __construct(private PDO $pdo)
    {
        $this->integrationAccessModel = new ApiIntegrationAccessModel($pdo);
    }

    public function obtenerContextoUsuario(int $userId): array
    {
        return [
            'whatsapp' => $this->obtenerWhatsappUsuario($userId),
            'integraciones' => $this->obtenerIntegracionesAccesibles($userId),
        ];
    }

    public function obtenerWhatsappUsuario(int $userId): ?string
    {
        $stmt = $this->pdo->prepare(
            'SELECT whatsapp
             FROM user_contacto
             WHERE user_auth_id = :user_id
             LIMIT 1'
        );
        $stmt->execute([':user_id' => $userId]);
        $whatsapp = $stmt->fetchColumn();

        if (!is_string($whatsapp)) {
            return null;
        }

        $whatsapp = trim($whatsapp);

        return $whatsapp !== '' ? $whatsapp : null;
    }

    public function obtenerIntegracionesAccesibles(int $userId): array
    {
        $integracionesBase = $this->integrationAccessModel->obtenerIntegracionesAccesibles($userId);

        if ($integracionesBase === []) {
            return [];
        }

        $ids = array_values(array_filter(array_map(
            static fn (array $item): int => (int) ($item['id'] ?? 0),
            $integracionesBase
        )));

        $stmt = $this->pdo->prepare(
            'SELECT id, api_integration_id, name, status, disabled_by_admin
             FROM chatbots
             WHERE api_integration_id IN (' . implode(',', array_fill(0, count($ids), '?')) . ')'
        );
        $stmt->execute($ids);
        $chatbots = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $chatbotsPorIntegracion = [];

        foreach ($chatbots as $chatbot) {
            $chatbotsPorIntegracion[(int) ($chatbot['api_integration_id'] ?? 0)] = $chatbot;
        }

        foreach ($integracionesBase as &$integracion) {
            $chatbot = $chatbotsPorIntegracion[(int) ($integracion['id'] ?? 0)] ?? null;
            $integracion['chatbot_id'] = $chatbot['id'] ?? null;
            $integracion['chatbot_name'] = $chatbot['name'] ?? null;
            $integracion['chatbot_status'] = $chatbot['status'] ?? null;
            $integracion['disabled_by_admin'] = $chatbot['disabled_by_admin'] ?? 0;
        }
        unset($integracion);

        return $integracionesBase;
    }

    public function obtenerIntegracionAccesible(int $userId, int $integrationId): ?array
    {
        foreach ($this->obtenerIntegracionesAccesibles($userId) as $integracion) {
            if ((int) ($integracion['id'] ?? 0) === $integrationId) {
                return $integracion;
            }
        }

        return null;
    }

    public function obtenerChatbotPorIntegracion(int $integrationId): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, api_integration_id, name, avatar_url, whatsapp, initial_message, status, disabled_by_admin, created_at, updated_at
             FROM chatbots
             WHERE api_integration_id = :api_integration_id
             LIMIT 1'
        );
        $stmt->execute([':api_integration_id' => $integrationId]);
        $chatbot = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!is_array($chatbot)) {
            return null;
        }

        $chatbot['nodes'] = $this->obtenerNodosChatbot((int) $chatbot['id']);

        return $chatbot;
    }

    public function guardarChatbot(int $userId, int $integrationId, array $payload): int
    {
        $integracion = $this->obtenerIntegracionAccesible($userId, $integrationId);

        if ($integracion === null) {
            throw new RuntimeException('La integracion seleccionada no pertenece a tu cuenta.');
        }

        $chatbot = $this->normalizarPayloadChatbot($payload, (string) ($integracion['project_name'] ?? ''));
        $existente = $this->obtenerChatbotPlanoPorIntegracion($integrationId);
        $chatbotId = (int) ($existente['id'] ?? 0);
        $disabledByAdmin = (int) ($existente['disabled_by_admin'] ?? 0) === 1;

        if ($disabledByAdmin && $chatbot['status'] === 'active') {
            throw new RuntimeException('Este chatbot esta desactivado por administracion y no puede activarse desde esta pantalla.');
        }

        $this->pdo->beginTransaction();

        try {
            if ($chatbotId > 0) {
                $stmt = $this->pdo->prepare(
                    'UPDATE chatbots
                     SET name = :name,
                         avatar_url = :avatar_url,
                         whatsapp = :whatsapp,
                         initial_message = :initial_message,
                         status = :status,
                         updated_at = CURRENT_TIMESTAMP
                     WHERE id = :id'
                );
                $stmt->execute([
                    ':id' => $chatbotId,
                    ':name' => $chatbot['name'],
                    ':avatar_url' => $chatbot['avatar_url'],
                    ':whatsapp' => $chatbot['whatsapp'],
                    ':initial_message' => $chatbot['initial_message'],
                    ':status' => $chatbot['status'],
                ]);

                $this->pdo->prepare('DELETE FROM chatbot_node_options WHERE node_id IN (SELECT id FROM chatbot_nodes WHERE chatbot_id = :chatbot_id)')
                    ->execute([':chatbot_id' => $chatbotId]);
                $this->pdo->prepare('DELETE FROM chatbot_nodes WHERE chatbot_id = :chatbot_id')
                    ->execute([':chatbot_id' => $chatbotId]);
            } else {
                $stmt = $this->pdo->prepare(
                    'INSERT INTO chatbots
                     (api_integration_id, name, avatar_url, whatsapp, initial_message, status, disabled_by_admin, created_at, updated_at)
                     VALUES
                     (:api_integration_id, :name, :avatar_url, :whatsapp, :initial_message, :status, 0, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)'
                );
                $stmt->execute([
                    ':api_integration_id' => $integrationId,
                    ':name' => $chatbot['name'],
                    ':avatar_url' => $chatbot['avatar_url'],
                    ':whatsapp' => $chatbot['whatsapp'],
                    ':initial_message' => $chatbot['initial_message'],
                    ':status' => $chatbot['status'],
                ]);
                $chatbotId = (int) $this->pdo->lastInsertId();
            }

            $nodeIdPorClave = [];
            $stmtNodo = $this->pdo->prepare(
                'INSERT INTO chatbot_nodes
                 (chatbot_id, title, body, sort_order, is_start, status, created_at, updated_at)
                 VALUES
                 (:chatbot_id, :title, :body, :sort_order, :is_start, :status, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)'
            );

            foreach ($chatbot['nodes'] as $nodeIndex => $node) {
                $stmtNodo->execute([
                    ':chatbot_id' => $chatbotId,
                    ':title' => $node['title'],
                    ':body' => $node['body'],
                    ':sort_order' => $node['sort_order'],
                    ':is_start' => $node['is_start'] ? 1 : 0,
                    ':status' => $node['status'],
                ]);
                $nodeIdPorClave[$node['client_key']] = (int) $this->pdo->lastInsertId();
                $chatbot['nodes'][$nodeIndex]['db_id'] = $nodeIdPorClave[$node['client_key']];
            }

            $stmtOpcion = $this->pdo->prepare(
                'INSERT INTO chatbot_node_options
                 (node_id, label, action_type, target_node_id, sort_order, created_at, updated_at)
                 VALUES
                 (:node_id, :label, :action_type, :target_node_id, :sort_order, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)'
            );

            foreach ($chatbot['nodes'] as $node) {
                foreach ($node['options'] as $option) {
                    $targetNodeId = null;

                    if ($option['action_type'] === 'go_to_node') {
                        $targetNodeId = $nodeIdPorClave[$option['target_node_key']] ?? null;

                        if ($targetNodeId === null) {
                            throw new RuntimeException('Una opcion apunta a un nodo destino inexistente.');
                        }
                    }

                    $stmtOpcion->execute([
                        ':node_id' => $node['db_id'],
                        ':label' => $option['label'],
                        ':action_type' => $option['action_type'],
                        ':target_node_id' => $targetNodeId,
                        ':sort_order' => $option['sort_order'],
                    ]);
                }
            }

            $this->pdo->commit();

            return $chatbotId;
        } catch (Throwable $exception) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $exception;
        }
    }

    public function actualizarEstado(int $userId, int $integrationId, string $status): void
    {
        $integracion = $this->obtenerIntegracionAccesible($userId, $integrationId);

        if ($integracion === null) {
            throw new RuntimeException('La integracion seleccionada no pertenece a tu cuenta.');
        }

        if (!in_array($status, self::CHATBOT_STATUSES, true)) {
            throw new RuntimeException('El estado del chatbot no es valido.');
        }

        $chatbot = $this->obtenerChatbotPlanoPorIntegracion($integrationId);

        if ($chatbot === null) {
            throw new RuntimeException('Todavia no existe un chatbot para esta integracion.');
        }

        if ((int) ($chatbot['disabled_by_admin'] ?? 0) === 1 && $status === 'active') {
            throw new RuntimeException('Este chatbot esta desactivado por administracion y no puede activarse desde esta pantalla.');
        }

        $stmt = $this->pdo->prepare(
            'UPDATE chatbots
             SET status = :status,
                 updated_at = CURRENT_TIMESTAMP
             WHERE id = :id'
        );
        $stmt->execute([
            ':id' => (int) $chatbot['id'],
            ':status' => $status,
        ]);
    }

    private function obtenerChatbotPlanoPorIntegracion(int $integrationId): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, api_integration_id, name, avatar_url, whatsapp, initial_message, status, disabled_by_admin
             FROM chatbots
             WHERE api_integration_id = :api_integration_id
             LIMIT 1'
        );
        $stmt->execute([':api_integration_id' => $integrationId]);
        $chatbot = $stmt->fetch(PDO::FETCH_ASSOC);

        return is_array($chatbot) ? $chatbot : null;
    }

    private function obtenerNodosChatbot(int $chatbotId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, chatbot_id, title, body, sort_order, is_start, status
             FROM chatbot_nodes
             WHERE chatbot_id = :chatbot_id
             ORDER BY sort_order ASC, id ASC'
        );
        $stmt->execute([':chatbot_id' => $chatbotId]);
        $nodes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($nodes === []) {
            return [];
        }

        $stmtOpciones = $this->pdo->prepare(
            'SELECT id, node_id, label, action_type, target_node_id, sort_order
             FROM chatbot_node_options
             WHERE node_id IN (' . implode(',', array_fill(0, count($nodes), '?')) . ')
             ORDER BY sort_order ASC, id ASC'
        );
        $stmtOpciones->execute(array_map(static fn (array $node): int => (int) $node['id'], $nodes));
        $options = $stmtOpciones->fetchAll(PDO::FETCH_ASSOC);

        $optionsByNode = [];
        foreach ($options as $option) {
            $optionsByNode[(int) ($option['node_id'] ?? 0)][] = $option;
        }

        foreach ($nodes as &$node) {
            $nodeId = (int) ($node['id'] ?? 0);
            $node['options'] = $optionsByNode[$nodeId] ?? [];
        }
        unset($node);

        return $nodes;
    }

    private function normalizarPayloadChatbot(array $payload, string $projectName): array
    {
        $name = $this->normalizarTexto($payload['name'] ?? '', true, 180);
        $avatarUrl = $this->normalizarTexto($payload['avatar_url'] ?? '', false, 255);
        $whatsapp = $this->normalizarTexto($payload['whatsapp'] ?? '', true, 80);
        $initialMessage = $this->normalizarTexto($payload['initial_message'] ?? '', true, 1000);
        $status = trim((string) ($payload['status'] ?? 'inactive'));
        $nodes = $payload['nodes'] ?? null;

        if (!in_array($status, self::CHATBOT_STATUSES, true)) {
            throw new RuntimeException('El estado del chatbot no es valido.');
        }

        if (!is_array($nodes) || $nodes === []) {
            throw new RuntimeException('Debes crear al menos un nodo para el chatbot.');
        }

        $normalizedNodes = [];
        $nodeKeys = [];
        $hasStartNode = false;
        $startNodesCount = 0;
        $activeNodesCount = 0;

        foreach (array_values($nodes) as $nodeIndex => $node) {
            if (!is_array($node)) {
                throw new RuntimeException('La estructura de nodos no es valida.');
            }

            $clientKey = $this->normalizarTexto($node['client_key'] ?? ('node-' . ($nodeIndex + 1)), true, 80);
            $title = $this->normalizarTexto($node['title'] ?? '', true, 180);
            $body = $this->normalizarTexto($node['body'] ?? '', true, 2000);
            $sortOrder = $this->normalizarEntero($node['sort_order'] ?? ($nodeIndex + 1), 1);
            $statusNode = trim((string) ($node['status'] ?? 'active'));
            $isStart = (bool) ($node['is_start'] ?? false);
            $options = $node['options'] ?? null;

            if (!in_array($statusNode, self::NODE_STATUSES, true)) {
                throw new RuntimeException('Uno de los nodos tiene un estado invalido.');
            }

            if (isset($nodeKeys[$clientKey])) {
                throw new RuntimeException('Cada nodo debe tener una clave unica dentro del constructor.');
            }

            if (!is_array($options) || $options === []) {
                throw new RuntimeException('Cada nodo debe tener al menos una opcion.');
            }

            $nodeKeys[$clientKey] = true;
            $hasStartNode = $hasStartNode || $isStart;
            if ($isStart) {
                $startNodesCount++;
            }
            if ($statusNode === 'active') {
                $activeNodesCount++;
            }

            $normalizedOptions = [];
            foreach (array_values($options) as $optionIndex => $option) {
                if (!is_array($option)) {
                    throw new RuntimeException('La estructura de opciones no es valida.');
                }

                $actionType = trim((string) ($option['action_type'] ?? ''));

                if (!in_array($actionType, self::ACTION_TYPES, true)) {
                    throw new RuntimeException('Una opcion tiene un tipo de accion invalido.');
                }

                $targetNodeKey = $this->normalizarTexto($option['target_node_key'] ?? '', false, 80);
                if ($actionType === 'go_to_node' && $targetNodeKey === null) {
                    throw new RuntimeException('Las opciones que navegan a otro nodo deben indicar un destino.');
                }

                if ($actionType !== 'go_to_node') {
                    $targetNodeKey = null;
                }

                $normalizedOptions[] = [
                    'label' => $this->normalizarTexto($option['label'] ?? '', true, 180),
                    'action_type' => $actionType,
                    'target_node_key' => $targetNodeKey,
                    'sort_order' => $this->normalizarEntero($option['sort_order'] ?? ($optionIndex + 1), 1),
                ];
            }

            $normalizedNodes[] = [
                'client_key' => $clientKey,
                'title' => $title,
                'body' => $body,
                'sort_order' => $sortOrder,
                'is_start' => $isStart,
                'status' => $statusNode,
                'options' => $normalizedOptions,
            ];
        }

        if (!$hasStartNode) {
            $normalizedNodes[0]['is_start'] = true;
        }

        if ($startNodesCount > 1) {
            throw new RuntimeException('Solo puede existir una pregunta inicial.');
        }

        if ($activeNodesCount === 0) {
            throw new RuntimeException('Debe existir al menos una pregunta activa.');
        }

        foreach ($normalizedNodes as $node) {
            if ($node['status'] === 'active' && $node['options'] === []) {
                throw new RuntimeException('Cada pregunta activa debe tener al menos una opcion.');
            }

            foreach ($node['options'] as $option) {
                if ($option['action_type'] === 'go_to_node' && !isset($nodeKeys[$option['target_node_key']])) {
                    throw new RuntimeException('Una opcion hace referencia a un nodo inexistente.');
                }
            }
        }

        return [
            'name' => $name !== '' ? $name : 'Chatbot ' . $projectName,
            'avatar_url' => $avatarUrl,
            'whatsapp' => $whatsapp,
            'initial_message' => $initialMessage,
            'status' => $status,
            'nodes' => $normalizedNodes,
        ];
    }

    private function normalizarTexto(mixed $value, bool $required, int $maxLength): ?string
    {
        if (!is_string($value) && !is_numeric($value)) {
            if ($required) {
                throw new RuntimeException('Falta un campo obligatorio del chatbot.');
            }

            return null;
        }

        $text = trim((string) $value);

        if ($text === '') {
            if ($required) {
                throw new RuntimeException('Falta un campo obligatorio del chatbot.');
            }

            return null;
        }

        $length = function_exists('mb_strlen') ? mb_strlen($text, 'UTF-8') : strlen($text);
        if ($length > $maxLength) {
            throw new RuntimeException('Uno de los campos del chatbot supera la longitud permitida.');
        }

        return $text;
    }

    private function normalizarEntero(mixed $value, int $minimum): int
    {
        $integer = filter_var($value, FILTER_VALIDATE_INT);

        if ($integer === false || $integer < $minimum) {
            throw new RuntimeException('Uno de los campos numericos del chatbot no es valido.');
        }

        return (int) $integer;
    }
}
