<?php

declare(strict_types=1);

class FormContactMetricsModel
{
    public function __construct(private PDO $pdo)
    {
    }

    /**
     * @param array<int, int> $integrationIds
     */
    private function normalizarIntegrationIds(array $integrationIds): array
    {
        return array_values(array_filter(
            array_map(static fn (mixed $id): int => (int) $id, $integrationIds),
            static fn (int $id): bool => $id > 0
        ));
    }

    /**
     * @param array<int, int> $integrationIds
     * @return array<int, array<string, mixed>>
     */
    public function obtenerContactos(array $integrationIds): array
    {
        $integrationIds = $this->normalizarIntegrationIds($integrationIds);
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

    /**
     * @param array<int, int> $integrationIds
     */
    public function actualizarEstadoContacto(int $contactId, array $integrationIds, string $state): bool
    {
        $integrationIds = $this->normalizarIntegrationIds($integrationIds);
        $state = trim($state);

        if ($contactId <= 0 || $integrationIds === [] || $state === '') {
            return false;
        }

        $placeholders = implode(',', array_fill(0, count($integrationIds), '?'));
        $sql = "UPDATE forms_clients_contact
                SET state = ?
                WHERE id = ?
                  AND api_integration_id IN ($placeholders)";

        $params = array_merge([$state, $contactId], $integrationIds);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->rowCount() > 0;
    }
}
