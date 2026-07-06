<?php

namespace App\Services\Marketing;

use App\Models\UserAuth;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MarketingImportService
{
    /** @return list<array<string, mixed>> */
    public function campaigns(): array
    {
        return DB::table('marketing_campaigns as mc')
            ->join('marketing_plan_subscriptions as mps', 'mps.id', '=', 'mc.subscription_id')
            ->join('marketing_plans as mp', 'mp.id', '=', 'mps.plan_id')
            ->orderBy('mc.campaign_name')
            ->get([
                'mc.*',
                'mp.name as plan_name',
                'mps.client_user_id',
                'mps.entrepreneur_user_id',
            ])
            ->map(static fn ($row): array => (array) $row)
            ->all();
    }

    /**
     * @param  array<string, int>  $manualAssignments  campaign_name => campaign_id
     * @return array{batch_id: int, imported: int, unresolved: int, total: int}
     */
    public function importMetaCsv(UserAuth $user, UploadedFile $file, array $manualAssignments = []): array
    {
        $handle = fopen($file->getRealPath(), 'r');

        if ($handle === false) {
            throw ValidationException::withMessages([
                'file' => ['No se pudo abrir el CSV.'],
            ]);
        }

        $headers = fgetcsv($handle);

        if ($headers === false) {
            fclose($handle);
            throw ValidationException::withMessages([
                'file' => ['El CSV no contiene encabezados.'],
            ]);
        }

        $headers = array_map(static fn ($header): string => trim((string) $header), $headers);
        $campaigns = $this->indexedCampaigns();
        $rows = [];
        $line = 1;

        while (($values = fgetcsv($handle)) !== false) {
            $line++;
            $row = [];

            foreach ($headers as $index => $header) {
                $row[$header] = $values[$index] ?? null;
            }

            $campaignName = $this->csvValue($row, ['Campaign name', 'Nombre de la campana', 'Nombre de la campaña', 'campaign_name']);
            $manualCampaignId = (int) ($manualAssignments[$campaignName] ?? 0);
            $matchedCampaign = $campaigns[$campaignName] ?? null;

            if ($manualCampaignId > 0) {
                $matchedCampaign = $this->campaignById($manualCampaignId);
            }

            $rows[] = [
                'line' => $line,
                'raw' => $row,
                'campaign_name' => $campaignName,
                'campaign' => $matchedCampaign,
            ];
        }

        fclose($handle);

        return DB::transaction(function () use ($user, $file, $rows): array {
            $batchId = (int) DB::table('marketing_import_batches')->insertGetId([
                'platform' => 'meta_ads',
                'uploaded_by_user_id' => $user->id,
                'original_filename' => $file->getClientOriginalName(),
                'file_type' => 'csv',
                'total_rows' => count($rows),
                'status' => 'previewed',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $imported = 0;
            $unresolved = 0;

            foreach ($rows as $row) {
                $campaign = $row['campaign'];
                $status = $campaign ? 'matched' : 'unresolved';

                if (! $campaign) {
                    $unresolved++;
                }

                DB::table('marketing_import_rows')->insert([
                    'import_batch_id' => $batchId,
                    'csv_row_number' => $row['line'],
                    'external_campaign_name' => $row['campaign_name'],
                    'internal_client_user_id' => $campaign['client_user_id'] ?? null,
                    'internal_entrepreneur_user_id' => $campaign['entrepreneur_user_id'] ?? null,
                    'internal_subscription_id' => $campaign['subscription_id'] ?? null,
                    'internal_campaign_id' => $campaign['id'] ?? null,
                    'status' => $status,
                    'reason' => $campaign ? 'exact_or_manual_match' : 'Sin match',
                    'raw_data_json' => json_encode($row['raw'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                if ($campaign) {
                    $this->insertCampaignMetric($batchId, (int) $campaign['id'], $row['raw'], (string) $row['campaign_name']);
                    $imported++;
                }
            }

            DB::table('marketing_import_batches')
                ->where('id', $batchId)
                ->update([
                    'imported_rows' => $imported,
                    'unresolved_rows' => $unresolved,
                    'status' => $unresolved > 0 ? 'partial' : 'imported',
                    'updated_at' => now(),
                ]);

            return [
                'batch_id' => $batchId,
                'imported' => $imported,
                'unresolved' => $unresolved,
                'total' => count($rows),
            ];
        });
    }

    /** @return array<string, array<string, mixed>> */
    private function indexedCampaigns(): array
    {
        $indexed = [];

        foreach ($this->campaigns() as $campaign) {
            $indexed[(string) $campaign['campaign_name']] = $campaign;

            if (! empty($campaign['recommended_meta_campaign_name'])) {
                $indexed[(string) $campaign['recommended_meta_campaign_name']] = $campaign;
            }
        }

        return $indexed;
    }

    /** @return array<string, mixed>|null */
    private function campaignById(int $id): ?array
    {
        $row = DB::table('marketing_campaigns')->where('id', $id)->first();

        return $row !== null ? (array) $row : null;
    }

    /** @param array<string, mixed> $row */
    private function insertCampaignMetric(int $batchId, int $campaignId, array $row, string $campaignName): void
    {
        $today = now()->toDateString();

        DB::table('marketing_campaign_metrics')->insert([
            'campaign_id' => $campaignId,
            'import_batch_id' => $batchId,
            'report_start_date' => $this->csvDate($row, ['Reporting starts', 'Inicio del informe']) ?? $today,
            'report_end_date' => $this->csvDate($row, ['Reporting ends', 'Fin del informe']) ?? $today,
            'campaign_name' => $campaignName,
            'campaign_delivery' => $this->csvValue($row, ['Delivery', 'Entrega']),
            'results' => $this->csvNumber($row, ['Results', 'Resultados']),
            'result_indicator' => $this->csvValue($row, ['Result indicator', 'Indicador de resultados']),
            'cost_per_result' => $this->csvNumber($row, ['Cost per result', 'Costo por resultado']),
            'adset_budget' => $this->csvNumber($row, ['Ad set budget', 'Presupuesto del conjunto de anuncios']),
            'adset_budget_type' => $this->csvValue($row, ['Ad set budget type', 'Tipo de presupuesto']),
            'amount_spent_ars' => $this->csvNumber($row, ['Amount spent (ARS)', 'Importe gastado (ARS)', 'Amount spent']),
            'impressions' => $this->csvInteger($row, ['Impressions', 'Impresiones']),
            'reach' => $this->csvInteger($row, ['Reach', 'Alcance']),
            'campaign_end_date' => $this->csvDate($row, ['Campaign end date', 'Fecha de finalizacion']),
            'attribution_setting' => $this->csvValue($row, ['Attribution setting', 'Configuracion de atribucion']),
            'total_message_contacts' => $this->csvInteger($row, ['Messaging conversations started', 'Contactos por mensaje']),
            'new_message_contacts' => $this->csvInteger($row, ['New messaging contacts', 'Contactos nuevos']),
            'purchases' => $this->csvInteger($row, ['Purchases', 'Compras']),
            'cost_per_purchase_ars' => $this->csvNumber($row, ['Cost per purchase', 'Costo por compra']),
            'messaging_conversations_started' => $this->csvInteger($row, ['Messaging conversations started', 'Conversaciones iniciadas']),
            'cost_per_messaging_conversation_started_ars' => $this->csvNumber($row, ['Cost per messaging conversation started', 'Costo por conversacion iniciada']),
            'source' => 'meta_csv',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /** @param array<string, mixed> $row */
    private function csvValue(array $row, array $keys): ?string
    {
        foreach ($keys as $key) {
            if (isset($row[$key]) && trim((string) $row[$key]) !== '') {
                return trim((string) $row[$key]);
            }
        }

        return null;
    }

    /** @param array<string, mixed> $row */
    private function csvNumber(array $row, array $keys): ?float
    {
        $value = $this->csvValue($row, $keys);

        if ($value === null) {
            return null;
        }

        $value = preg_replace('/[^0-9,.\-]/', '', $value) ?: '';

        if (str_contains($value, ',') && str_contains($value, '.')) {
            $value = str_replace('.', '', $value);
        }

        return (float) str_replace(',', '.', $value);
    }

    /** @param array<string, mixed> $row */
    private function csvInteger(array $row, array $keys): ?int
    {
        $value = $this->csvNumber($row, $keys);

        return $value === null ? null : (int) $value;
    }

    /** @param array<string, mixed> $row */
    private function csvDate(array $row, array $keys): ?string
    {
        $value = $this->csvValue($row, $keys);

        if ($value === null) {
            return null;
        }

        $timestamp = strtotime($value);

        return $timestamp ? date('Y-m-d', $timestamp) : null;
    }
}
