<?php

declare(strict_types=1);

class FormContactMetricsModel
{
    public function __construct(private PDO $pdo)
    {
    }

    /**
     * @param array<int, int> $integrationIds
     * @return array<int, array<string, mixed>>
     */
    public function obtenerContactos(array $integrationIds): array
    {
        if ($integrationIds === []) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($integrationIds), '?'));
        $sql = "SELECT fcc.id, fcc.page, fcc.contact_nombre, fcc.contact_whatsapp, fcc.contact_email,
                       fcc.contact_description, fcc.contact_consultation, fcc.state, fcc.created_at,
                       ai.project_name, ai.allowed_domain
                FROM forms_clients_contact fcc
                INNER JOIN api_integrations ai ON ai.id = fcc.api_integration_id
                WHERE fcc.api_integration_id IN ($placeholders)
                ORDER BY fcc.created_at DESC, fcc.id DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array_values($integrationIds));

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
