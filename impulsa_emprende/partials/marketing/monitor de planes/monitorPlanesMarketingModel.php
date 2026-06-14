<?php

class MonitorPlanesMarketingModel
{
    public function __construct(private PDO $pdo)
    {
    }

    public function obtenerSuscripciones(): array
    {
        $stmt = $this->pdo->query(
            "SELECT mps.*, mp.name AS plan_name, mpo.currency,
                    uc.correo AS client_email, ue.correo AS entrepreneur_email, um.correo AS assigned_email,
                    (SELECT COUNT(*) FROM marketing_campaigns mc WHERE mc.subscription_id = mps.id) AS campaigns_total
             FROM marketing_plan_subscriptions mps
             INNER JOIN marketing_plans mp ON mp.id = mps.plan_id
             INNER JOIN marketing_plan_pricing_options mpo ON mpo.id = mps.pricing_option_id
             LEFT JOIN user_auth uc ON uc.id = mps.client_user_id
             LEFT JOIN user_auth ue ON ue.id = mps.entrepreneur_user_id
             LEFT JOIN user_auth um ON um.id = mps.assigned_marketing_user_id
             ORDER BY mps.updated_at DESC"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function obtenerCampanias(): array
    {
        $stmt = $this->pdo->query(
            "SELECT mc.*, mp.name AS plan_name
             FROM marketing_campaigns mc
             INNER JOIN marketing_plan_subscriptions mps ON mps.id = mc.subscription_id
             INNER JOIN marketing_plans mp ON mp.id = mps.plan_id
             ORDER BY mc.campaign_name ASC"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function cambiarEstadoSuscripcion(int $subscriptionId, string $estado, ?int $responsableId = null): void
    {
        $estados = ['requested', 'meeting_scheduled', 'active', 'paused', 'completed', 'cancelled'];
        if (!in_array($estado, $estados, true)) {
            throw new InvalidArgumentException('Estado invalido.');
        }

        $stmt = $this->pdo->prepare(
            "UPDATE marketing_plan_subscriptions
             SET status = :status,
                 assigned_marketing_user_id = COALESCE(:responsable_id, assigned_marketing_user_id),
                 activated_at = CASE WHEN :status_active = 'active' AND activated_at IS NULL THEN NOW() ELSE activated_at END
             WHERE id = :id"
        );
        $stmt->execute([
            'status' => $estado,
            'status_active' => $estado,
            'responsable_id' => $responsableId,
            'id' => $subscriptionId,
        ]);
    }

    public function importarCsvMeta(array $archivo, array $asignaciones, int $usuarioId): array
    {
        if (($archivo['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            throw new InvalidArgumentException('No se pudo leer el CSV.');
        }

        $tmpName = (string) ($archivo['tmp_name'] ?? '');
        $handle = fopen($tmpName, 'r');
        if (!$handle) {
            throw new RuntimeException('No se pudo abrir el CSV.');
        }

        $headers = fgetcsv($handle);
        if (!$headers) {
            fclose($handle);
            throw new InvalidArgumentException('El CSV no contiene encabezados.');
        }
        $headers = array_map(static fn ($h) => trim((string) $h), $headers);

        $campaigns = $this->obtenerCampaniasIndexadas();
        $rows = [];
        $line = 1;
        while (($values = fgetcsv($handle)) !== false) {
            $line++;
            $row = [];
            foreach ($headers as $i => $header) {
                $row[$header] = $values[$i] ?? null;
            }
            $campaignName = $this->valorCsv($row, ['Campaign name', 'Nombre de la campana', 'Nombre de la campaña', 'campaign_name']);
            $manualCampaignId = (int) ($asignaciones[$campaignName] ?? 0);
            $matchedCampaign = $campaigns[$campaignName] ?? null;
            if ($manualCampaignId > 0) {
                $matchedCampaign = $this->obtenerCampaniaPorId($manualCampaignId);
            }
            $rows[] = [
                'line' => $line,
                'raw' => $row,
                'campaign_name' => $campaignName,
                'campaign' => $matchedCampaign,
            ];
        }
        fclose($handle);

        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO marketing_import_batches
                 (platform, uploaded_by_user_id, original_filename, file_type, total_rows, status)
                 VALUES ('meta_ads', :user_id, :filename, 'csv', :total_rows, 'previewed')"
            );
            $stmt->execute([
                'user_id' => $usuarioId,
                'filename' => basename((string) ($archivo['name'] ?? 'meta.csv')),
                'total_rows' => count($rows),
            ]);
            $batchId = (int) $this->pdo->lastInsertId();

            $imported = 0;
            $unresolved = 0;
            foreach ($rows as $row) {
                $campaign = $row['campaign'];
                $status = $campaign ? 'matched' : 'unresolved';
                if (!$campaign) {
                    $unresolved++;
                }
                $stmt = $this->pdo->prepare(
                    "INSERT INTO marketing_import_rows
                     (import_batch_id, csv_row_number, external_campaign_name, internal_client_user_id,
                      internal_entrepreneur_user_id, internal_subscription_id, internal_campaign_id,
                      status, reason, raw_data_json)
                     VALUES
                     (:batch_id, :row_number, :external_campaign_name, :client_id, :entrepreneur_id,
                      :subscription_id, :campaign_id, :status, :reason, :raw)"
                );
                $stmt->execute([
                    'batch_id' => $batchId,
                    'row_number' => $row['line'],
                    'external_campaign_name' => $row['campaign_name'],
                    'client_id' => $campaign['client_user_id'] ?? null,
                    'entrepreneur_id' => $campaign['entrepreneur_user_id'] ?? null,
                    'subscription_id' => $campaign['subscription_id'] ?? null,
                    'campaign_id' => $campaign['id'] ?? null,
                    'status' => $status,
                    'reason' => $campaign ? 'exact_or_manual_match' : 'Sin match',
                    'raw' => json_encode($row['raw'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                ]);

                if ($campaign) {
                    $this->insertarMetricaCampania($batchId, (int) $campaign['id'], $row['raw'], $row['campaign_name']);
                    $imported++;
                }
            }

            $stmt = $this->pdo->prepare(
                "UPDATE marketing_import_batches
                 SET imported_rows = :imported, unresolved_rows = :unresolved,
                     status = CASE WHEN :unresolved_case > 0 THEN 'partial' ELSE 'imported' END
                 WHERE id = :id"
            );
            $stmt->execute([
                'imported' => $imported,
                'unresolved' => $unresolved,
                'unresolved_case' => $unresolved,
                'id' => $batchId,
            ]);
            $this->pdo->commit();
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }

        return ['batch_id' => $batchId, 'imported' => $imported, 'unresolved' => $unresolved, 'total' => count($rows)];
    }

    private function obtenerCampaniasIndexadas(): array
    {
        $campanias = [];
        foreach ($this->obtenerCampanias() as $campania) {
            $campanias[(string) $campania['campaign_name']] = $campania;
            $campanias[(string) $campania['recommended_meta_campaign_name']] = $campania;
        }
        return $campanias;
    }

    private function obtenerCampaniaPorId(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM marketing_campaigns WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    private function insertarMetricaCampania(int $batchId, int $campaignId, array $row, string $campaignName): void
    {
        $today = date('Y-m-d');
        $stmt = $this->pdo->prepare(
            "INSERT INTO marketing_campaign_metrics
             (campaign_id, import_batch_id, report_start_date, report_end_date, campaign_name, campaign_delivery,
              results, result_indicator, cost_per_result, adset_budget, adset_budget_type, amount_spent_ars,
              impressions, reach, campaign_end_date, attribution_setting, total_message_contacts,
              new_message_contacts, purchases, cost_per_purchase_ars, messaging_conversations_started,
              cost_per_messaging_conversation_started_ars, source)
             VALUES
             (:campaign_id, :batch_id, :start_date, :end_date, :campaign_name, :delivery,
              :results, :result_indicator, :cost_per_result, :budget, :budget_type, :spent,
              :impressions, :reach, :campaign_end_date, :attribution, :total_contacts,
              :new_contacts, :purchases, :cost_purchase, :conversations, :cost_conversation, 'meta_csv')"
        );
        $stmt->execute([
            'campaign_id' => $campaignId,
            'batch_id' => $batchId,
            'start_date' => $this->fechaCsv($row, ['Reporting starts', 'Inicio del informe']) ?? $today,
            'end_date' => $this->fechaCsv($row, ['Reporting ends', 'Fin del informe']) ?? $today,
            'campaign_name' => $campaignName,
            'delivery' => $this->valorCsv($row, ['Delivery', 'Entrega']),
            'results' => $this->numeroCsv($row, ['Results', 'Resultados']),
            'result_indicator' => $this->valorCsv($row, ['Result indicator', 'Indicador de resultados']),
            'cost_per_result' => $this->numeroCsv($row, ['Cost per result', 'Costo por resultado']),
            'budget' => $this->numeroCsv($row, ['Ad set budget', 'Presupuesto del conjunto de anuncios']),
            'budget_type' => $this->valorCsv($row, ['Ad set budget type', 'Tipo de presupuesto']),
            'spent' => $this->numeroCsv($row, ['Amount spent (ARS)', 'Importe gastado (ARS)', 'Amount spent']),
            'impressions' => $this->enteroCsv($row, ['Impressions', 'Impresiones']),
            'reach' => $this->enteroCsv($row, ['Reach', 'Alcance']),
            'campaign_end_date' => $this->fechaCsv($row, ['Campaign end date', 'Fecha de finalizacion']),
            'attribution' => $this->valorCsv($row, ['Attribution setting', 'Configuracion de atribucion']),
            'total_contacts' => $this->enteroCsv($row, ['Messaging conversations started', 'Contactos por mensaje']),
            'new_contacts' => $this->enteroCsv($row, ['New messaging contacts', 'Contactos nuevos']),
            'purchases' => $this->enteroCsv($row, ['Purchases', 'Compras']),
            'cost_purchase' => $this->numeroCsv($row, ['Cost per purchase', 'Costo por compra']),
            'conversations' => $this->enteroCsv($row, ['Messaging conversations started', 'Conversaciones iniciadas']),
            'cost_conversation' => $this->numeroCsv($row, ['Cost per messaging conversation started', 'Costo por conversacion iniciada']),
        ]);
    }

    private function valorCsv(array $row, array $keys): ?string
    {
        foreach ($keys as $key) {
            if (isset($row[$key]) && trim((string) $row[$key]) !== '') {
                return trim((string) $row[$key]);
            }
        }
        return null;
    }

    private function numeroCsv(array $row, array $keys): ?float
    {
        $value = $this->valorCsv($row, $keys);
        if ($value === null) {
            return null;
        }
        $value = preg_replace('/[^0-9,.\-]/', '', $value) ?: '';
        if (str_contains($value, ',') && str_contains($value, '.')) {
            $value = str_replace('.', '', $value);
        }
        return (float) str_replace(',', '.', $value);
    }

    private function enteroCsv(array $row, array $keys): ?int
    {
        $value = $this->numeroCsv($row, $keys);
        return $value === null ? null : (int) $value;
    }

    private function fechaCsv(array $row, array $keys): ?string
    {
        $value = $this->valorCsv($row, $keys);
        if (!$value) {
            return null;
        }
        $timestamp = strtotime($value);
        return $timestamp ? date('Y-m-d', $timestamp) : null;
    }
}
