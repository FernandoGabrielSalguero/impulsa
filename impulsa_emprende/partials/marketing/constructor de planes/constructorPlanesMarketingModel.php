<?php

class ConstructorPlanesMarketingModel
{
    public function __construct(private PDO $pdo)
    {
    }

    public function obtenerPlanes(): array
    {
        $stmt = $this->pdo->query(
            "SELECT mp.*,
                    COUNT(DISTINCT mf.id) AS features_total,
                    COUNT(DISTINCT mpo.id) AS precios_total
             FROM marketing_plans mp
             LEFT JOIN marketing_plan_features mf ON mf.plan_id = mp.id
             LEFT JOIN marketing_plan_pricing_options mpo ON mpo.plan_id = mp.id
             GROUP BY mp.id
             ORDER BY FIELD(mp.status, 'published', 'draft', 'paused', 'archived'), mp.updated_at DESC"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function obtenerPlanCompleto(int $planId): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM marketing_plans WHERE id = :id');
        $stmt->execute(['id' => $planId]);
        $plan = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$plan) {
            return null;
        }

        $plan['features'] = $this->obtenerFeatures($planId);
        $plan['pricing_options'] = $this->obtenerPrecios($planId);
        return $plan;
    }

    public function guardarPlanCompleto(array $data, int $usuarioId): int
    {
        $this->pdo->beginTransaction();
        try {
            $planId = $this->guardarPlan($data, $usuarioId);
            $this->sincronizarFeatures($planId, is_array($data['features'] ?? null) ? $data['features'] : []);
            $this->sincronizarPrecios($planId, is_array($data['pricing'] ?? null) ? $data['pricing'] : []);
            $this->pdo->commit();
            return $planId;
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function guardarPlan(array $data, int $usuarioId): int
    {
        $planId = (int) ($data['plan_id'] ?? 0);
        $nombre = trim((string) ($data['name'] ?? ''));
        if ($nombre === '') {
            throw new InvalidArgumentException('El nombre del plan es obligatorio.');
        }

        $slug = trim((string) ($data['slug'] ?? ''));
        if ($slug === '') {
            $slug = marketingSlug($nombre);
        }
        $slug = $this->slugUnico($slug, $planId);

        $payload = [
            'name' => $nombre,
            'slug' => $slug,
            'short_description' => $this->nullSiVacio($data['short_description'] ?? null),
            'full_description' => $this->nullSiVacio($data['full_description'] ?? null),
            'objective' => $this->nullSiVacio($data['objective'] ?? null),
            'recommended_ad_budget_min' => $this->dineroONull($data['recommended_ad_budget_min'] ?? null),
            'recommended_ad_budget_max' => $this->dineroONull($data['recommended_ad_budget_max'] ?? null),
            'setup_fee' => $this->dinero($data['setup_fee'] ?? 0),
            'billing_period' => trim((string) ($data['billing_period'] ?? 'Mensual')) ?: 'Mensual',
            'report_frequency' => $this->nullSiVacio($data['report_frequency'] ?? null),
            'support_level' => $this->nullSiVacio($data['support_level'] ?? null),
            'is_visible_to_clients' => !empty($data['is_visible_to_clients']) ? 1 : 0,
            'status' => $this->estadoPlan((string) ($data['status'] ?? 'draft')),
            'created_by_user_id' => $usuarioId,
        ];

        if ($planId > 0) {
            unset($payload['created_by_user_id']);
            $payload['id'] = $planId;
            $stmt = $this->pdo->prepare(
                "UPDATE marketing_plans
                 SET name = :name, slug = :slug, short_description = :short_description,
                     full_description = :full_description, objective = :objective,
                     recommended_ad_budget_min = :recommended_ad_budget_min,
                     recommended_ad_budget_max = :recommended_ad_budget_max,
                     setup_fee = :setup_fee, billing_period = :billing_period,
                     report_frequency = :report_frequency, support_level = :support_level,
                     is_visible_to_clients = :is_visible_to_clients, status = :status
                 WHERE id = :id"
            );
            $stmt->execute($payload);
            return $planId;
        }

        $stmt = $this->pdo->prepare(
            "INSERT INTO marketing_plans
             (name, slug, short_description, full_description, objective, recommended_ad_budget_min,
              recommended_ad_budget_max, setup_fee, billing_period, report_frequency, support_level,
              is_visible_to_clients, status, created_by_user_id)
             VALUES
             (:name, :slug, :short_description, :full_description, :objective, :recommended_ad_budget_min,
              :recommended_ad_budget_max, :setup_fee, :billing_period, :report_frequency, :support_level,
              :is_visible_to_clients, :status, :created_by_user_id)"
        );
        $stmt->execute($payload);

        return (int) $this->pdo->lastInsertId();
    }

    public function guardarFeature(array $data): void
    {
        $planId = (int) ($data['plan_id'] ?? 0);
        $nombre = trim((string) ($data['feature_name'] ?? ''));
        if ($planId <= 0 || $nombre === '') {
            throw new InvalidArgumentException('Selecciona un plan y completa el nombre del item.');
        }

        $stmt = $this->pdo->prepare(
            "INSERT INTO marketing_plan_features
             (plan_id, feature_name, feature_description, quantity, unit, feature_order, is_highlighted)
             VALUES (:plan_id, :feature_name, :feature_description, :quantity, :unit, :feature_order, :is_highlighted)"
        );
        $stmt->execute([
            'plan_id' => $planId,
            'feature_name' => $nombre,
            'feature_description' => $this->nullSiVacio($data['feature_description'] ?? null),
            'quantity' => $this->enteroONull($data['quantity'] ?? null),
            'unit' => $this->nullSiVacio($data['unit'] ?? null),
            'feature_order' => (int) ($data['feature_order'] ?? 0),
            'is_highlighted' => !empty($data['is_highlighted']) ? 1 : 0,
        ]);
    }

    public function guardarPrecio(array $data): void
    {
        $planId = (int) ($data['plan_id'] ?? 0);
        $duracion = (int) ($data['duration_months'] ?? 0);
        $mensual = $this->dinero($data['monthly_price'] ?? 0);
        if ($planId <= 0 || $duracion <= 0 || $mensual <= 0) {
            throw new InvalidArgumentException('Selecciona un plan y completa duracion y precio mensual.');
        }

        $total = $mensual * $duracion;
        $stmt = $this->pdo->prepare(
            "INSERT INTO marketing_plan_pricing_options
             (plan_id, duration_months, monthly_price, total_price, setup_fee, currency, is_featured, is_default, display_order)
             VALUES (:plan_id, :duration_months, :monthly_price, :total_price, :setup_fee, :currency, :is_featured, :is_default, :display_order)"
        );
        $stmt->execute([
            'plan_id' => $planId,
            'duration_months' => $duracion,
            'monthly_price' => $mensual,
            'total_price' => $total,
            'setup_fee' => $this->dinero($data['setup_fee'] ?? 0),
            'currency' => trim((string) ($data['currency'] ?? 'ARS')) ?: 'ARS',
            'is_featured' => !empty($data['is_featured']) ? 1 : 0,
            'is_default' => !empty($data['is_default']) ? 1 : 0,
            'display_order' => (int) ($data['display_order'] ?? 0),
        ]);
    }

    public function eliminarFeature(int $featureId): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM marketing_plan_features WHERE id = :id');
        $stmt->execute(['id' => $featureId]);
    }

    public function eliminarPrecio(int $precioId): void
    {
        if ($this->precioUsadoEnSuscripciones($precioId)) {
            throw new RuntimeException('No se puede eliminar esta opcion de precio porque ya esta usada por una suscripcion.');
        }

        $stmt = $this->pdo->prepare('DELETE FROM marketing_plan_pricing_options WHERE id = :id');
        $stmt->execute(['id' => $precioId]);
    }

    public function eliminarPlan(int $planId): void
    {
        if ($this->planUsadoEnSuscripciones($planId)) {
            throw new RuntimeException('No se puede eliminar este plan porque ya tiene suscripciones. Pausalo o archivalo para ocultarlo sin romper el historial.');
        }

        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare('DELETE FROM marketing_plan_features WHERE plan_id = :id');
            $stmt->execute(['id' => $planId]);
            $stmt = $this->pdo->prepare('DELETE FROM marketing_plan_pricing_options WHERE plan_id = :id');
            $stmt->execute(['id' => $planId]);
            $stmt = $this->pdo->prepare('DELETE FROM marketing_plans WHERE id = :id');
            $stmt->execute(['id' => $planId]);
            $this->pdo->commit();
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function obtenerFeatures(int $planId): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM marketing_plan_features WHERE plan_id = :id ORDER BY feature_order ASC, id ASC');
        $stmt->execute(['id' => $planId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function obtenerPrecios(int $planId): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM marketing_plan_pricing_options WHERE plan_id = :id ORDER BY display_order ASC, duration_months ASC');
        $stmt->execute(['id' => $planId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function slugUnico(string $slug, int $planId): string
    {
        $base = marketingSlug($slug);
        $candidate = $base;
        $i = 2;
        while (true) {
            $stmt = $this->pdo->prepare('SELECT id FROM marketing_plans WHERE slug = :slug AND id <> :id LIMIT 1');
            $stmt->execute(['slug' => $candidate, 'id' => $planId]);
            if (!$stmt->fetchColumn()) {
                return $candidate;
            }
            $candidate = $base . '-' . $i++;
        }
    }

    private function nullSiVacio(mixed $valor): ?string
    {
        $valor = trim((string) ($valor ?? ''));
        return $valor === '' ? null : $valor;
    }

    private function decimal(mixed $valor): float
    {
        return (float) str_replace(',', '.', (string) ($valor ?? 0));
    }

    private function decimalONull(mixed $valor): ?float
    {
        $valor = trim((string) ($valor ?? ''));
        return $valor === '' ? null : $this->decimal($valor);
    }

    private function enteroONull(mixed $valor): ?int
    {
        $valor = trim((string) ($valor ?? ''));
        return $valor === '' ? null : (int) floor($this->decimal($valor));
    }

    private function dinero(mixed $valor): int
    {
        return (int) floor($this->decimal($valor));
    }

    private function dineroONull(mixed $valor): ?int
    {
        $valor = trim((string) ($valor ?? ''));
        return $valor === '' ? null : $this->dinero($valor);
    }

    private function estadoPlan(string $estado): string
    {
        return in_array($estado, ['draft', 'published', 'paused', 'archived'], true) ? $estado : 'draft';
    }

    private function sincronizarFeatures(int $planId, array $features): void
    {
        $idsEnviados = [];
        foreach ($features as $index => $feature) {
            $nombre = trim((string) ($feature['feature_name'] ?? ''));
            if ($nombre === '') {
                continue;
            }

            $featureId = (int) ($feature['id'] ?? 0);
            $payload = [
                'plan_id' => $planId,
                'feature_name' => $nombre,
                'feature_description' => $this->nullSiVacio($feature['feature_description'] ?? null),
                'quantity' => $this->enteroONull($feature['quantity'] ?? null),
                'unit' => $this->nullSiVacio($feature['unit'] ?? null),
                'feature_order' => (int) ($feature['feature_order'] ?? $index),
                'is_highlighted' => !empty($feature['is_highlighted']) ? 1 : 0,
            ];

            if ($featureId > 0) {
                $payload['id'] = $featureId;
                $stmt = $this->pdo->prepare(
                    "UPDATE marketing_plan_features
                     SET feature_name = :feature_name, feature_description = :feature_description,
                         quantity = :quantity, unit = :unit, feature_order = :feature_order,
                         is_highlighted = :is_highlighted
                     WHERE id = :id AND plan_id = :plan_id"
                );
                $stmt->execute($payload);
                $idsEnviados[] = $featureId;
                continue;
            }

            $stmt = $this->pdo->prepare(
                "INSERT INTO marketing_plan_features
                 (plan_id, feature_name, feature_description, quantity, unit, feature_order, is_highlighted)
                 VALUES (:plan_id, :feature_name, :feature_description, :quantity, :unit, :feature_order, :is_highlighted)"
            );
            $stmt->execute($payload);
            $idsEnviados[] = (int) $this->pdo->lastInsertId();
        }

        $existentes = $this->idsFeatures($planId);
        foreach (array_diff($existentes, $idsEnviados) as $featureId) {
            $this->eliminarFeature((int) $featureId);
        }
    }

    private function sincronizarPrecios(int $planId, array $precios): void
    {
        $idsEnviados = [];
        foreach ($precios as $index => $precio) {
            $duracion = (int) ($precio['duration_months'] ?? 0);
            $mensual = $this->dinero($precio['monthly_price'] ?? 0);
            if ($duracion <= 0 || $mensual <= 0) {
                continue;
            }

            $precioId = (int) ($precio['id'] ?? 0);
            $total = $mensual * $duracion;
            $payload = [
                'plan_id' => $planId,
                'duration_months' => $duracion,
                'monthly_price' => $mensual,
                'total_price' => $total,
                'setup_fee' => $this->dinero($precio['setup_fee'] ?? 0),
                'currency' => trim((string) ($precio['currency'] ?? 'ARS')) ?: 'ARS',
                'is_featured' => !empty($precio['is_featured']) ? 1 : 0,
                'is_default' => !empty($precio['is_default']) ? 1 : 0,
                'display_order' => (int) ($precio['display_order'] ?? $index),
            ];

            if ($precioId > 0) {
                $payload['id'] = $precioId;
                $stmt = $this->pdo->prepare(
                    "UPDATE marketing_plan_pricing_options
                     SET duration_months = :duration_months, monthly_price = :monthly_price,
                         total_price = :total_price, setup_fee = :setup_fee, currency = :currency,
                         is_featured = :is_featured, is_default = :is_default, display_order = :display_order
                     WHERE id = :id AND plan_id = :plan_id"
                );
                $stmt->execute($payload);
                $idsEnviados[] = $precioId;
                continue;
            }

            $stmt = $this->pdo->prepare(
                "INSERT INTO marketing_plan_pricing_options
                 (plan_id, duration_months, monthly_price, total_price, setup_fee, currency, is_featured, is_default, display_order)
                 VALUES (:plan_id, :duration_months, :monthly_price, :total_price, :setup_fee, :currency, :is_featured, :is_default, :display_order)"
            );
            $stmt->execute($payload);
            $idsEnviados[] = (int) $this->pdo->lastInsertId();
        }

        $existentes = $this->idsPrecios($planId);
        foreach (array_diff($existentes, $idsEnviados) as $precioId) {
            $this->eliminarPrecio((int) $precioId);
        }
    }

    private function idsFeatures(int $planId): array
    {
        $stmt = $this->pdo->prepare('SELECT id FROM marketing_plan_features WHERE plan_id = :id');
        $stmt->execute(['id' => $planId]);
        return array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN) ?: []);
    }

    private function idsPrecios(int $planId): array
    {
        $stmt = $this->pdo->prepare('SELECT id FROM marketing_plan_pricing_options WHERE plan_id = :id');
        $stmt->execute(['id' => $planId]);
        return array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN) ?: []);
    }

    private function planUsadoEnSuscripciones(int $planId): bool
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM marketing_plan_subscriptions WHERE plan_id = :id');
        $stmt->execute(['id' => $planId]);
        return (int) $stmt->fetchColumn() > 0;
    }

    private function precioUsadoEnSuscripciones(int $precioId): bool
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM marketing_plan_subscriptions WHERE pricing_option_id = :id');
        $stmt->execute(['id' => $precioId]);
        return (int) $stmt->fetchColumn() > 0;
    }
}
