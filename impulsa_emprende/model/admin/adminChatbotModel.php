<?php

declare(strict_types=1);

final class AdminChatbotModel
{
    public function __construct(private PDO $pdo)
    {
    }

    public function obtenerResumen(): array
    {
        $sql = "
            SELECT
                COUNT(*) AS total_chatbots,
                SUM(CASE WHEN c.status = 'active' AND c.disabled_by_admin = 0 THEN 1 ELSE 0 END) AS total_activos,
                SUM(CASE WHEN c.disabled_by_admin = 1 THEN 1 ELSE 0 END) AS total_bloqueados,
                COUNT(ce.id) AS total_eventos
            FROM chatbots c
            LEFT JOIN chatbot_events ce ON ce.chatbot_id = c.id
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    public function obtenerChatbots(): array
    {
        $sql = "
            SELECT
                c.id,
                c.name,
                c.status,
                c.disabled_by_admin,
                c.created_at,
                c.updated_at,
                ai.project_name,
                ai.allowed_domain,
                ai.status AS integration_status,
                SUM(CASE WHEN ce.event_type = 'widget_loaded' THEN 1 ELSE 0 END) AS total_widget_loaded,
                SUM(CASE WHEN ce.event_type = 'bubble_opened' THEN 1 ELSE 0 END) AS total_bubble_opened,
                SUM(CASE WHEN ce.event_type = 'question_viewed' THEN 1 ELSE 0 END) AS total_question_viewed,
                SUM(CASE WHEN ce.event_type = 'option_clicked' THEN 1 ELSE 0 END) AS total_option_clicked,
                SUM(CASE WHEN ce.event_type = 'whatsapp_clicked' THEN 1 ELSE 0 END) AS total_whatsapp_clicked,
                MAX(ce.created_at) AS last_activity
            FROM chatbots c
            INNER JOIN api_integrations ai ON ai.id = c.api_integration_id
            LEFT JOIN chatbot_events ce ON ce.chatbot_id = c.id
            GROUP BY c.id, c.name, c.status, c.disabled_by_admin, c.created_at, c.updated_at, ai.project_name, ai.allowed_domain, ai.status
            ORDER BY c.updated_at DESC, c.id DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizarBloqueoAdmin(int $chatbotId, bool $disabled): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE chatbots
             SET disabled_by_admin = :disabled_by_admin,
                 status = CASE WHEN :disabled_by_admin = 1 THEN \'inactive\' ELSE status END,
                 updated_at = CURRENT_TIMESTAMP
             WHERE id = :id'
        );
        $stmt->execute([
            ':id' => $chatbotId,
            ':disabled_by_admin' => $disabled ? 1 : 0,
        ]);
    }

    public function obtenerChatbotPorId(int $chatbotId): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, name, disabled_by_admin
             FROM chatbots
             WHERE id = :id
             LIMIT 1'
        );
        $stmt->execute([':id' => $chatbotId]);
        $chatbot = $stmt->fetch(PDO::FETCH_ASSOC);

        return is_array($chatbot) ? $chatbot : null;
    }
}
