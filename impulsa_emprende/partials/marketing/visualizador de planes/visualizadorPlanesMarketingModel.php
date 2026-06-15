<?php

class VisualizadorPlanesMarketingModel
{
    public function __construct(private PDO $pdo)
    {
    }

    public function obtenerPlanesPublicados(): array
    {
        $stmt = $this->pdo->query(
            "SELECT * FROM marketing_plans
             WHERE status = 'published' AND is_visible_to_clients = 1
             ORDER BY name ASC"
        );
        $planes = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        foreach ($planes as &$plan) {
            $plan['features'] = $this->obtenerFeatures((int) $plan['id']);
            $plan['pricing_options'] = $this->obtenerPrecios((int) $plan['id']);
        }
        unset($plan);

        return $planes;
    }

    public function solicitarPlan(int $planId, int $pricingId, array $usuario, ?string $notas = null): int
    {
        $stmt = $this->pdo->prepare(
            "SELECT mp.id AS plan_id, mpo.id AS pricing_id, mpo.duration_months, mpo.monthly_price, mpo.total_price
             FROM marketing_plans mp
             INNER JOIN marketing_plan_pricing_options mpo ON mpo.plan_id = mp.id
             WHERE mp.id = :plan_id
               AND mpo.id = :pricing_id
               AND mp.status = 'published'
               AND mp.is_visible_to_clients = 1"
        );
        $stmt->execute(['plan_id' => $planId, 'pricing_id' => $pricingId]);
        $precio = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$precio) {
            throw new InvalidArgumentException('El plan seleccionado no esta disponible.');
        }

        $rol = (string) ($usuario['rol'] ?? '');
        $usuarioId = (int) ($usuario['id'] ?? 0);
        $stmt = $this->pdo->prepare(
            "INSERT INTO marketing_plan_subscriptions
             (plan_id, pricing_option_id, client_user_id, entrepreneur_user_id, status, duration_months,
              monthly_price, total_contract_value, notes)
             VALUES
             (:plan_id, :pricing_option_id, :client_user_id, :entrepreneur_user_id, 'requested',
              :duration_months, :monthly_price, :total_contract_value, :notes)"
        );
        $stmt->execute([
            'plan_id' => $planId,
            'pricing_option_id' => $pricingId,
            'client_user_id' => $rol === 'impulsa_cliente' ? $usuarioId : null,
            'entrepreneur_user_id' => $rol === 'impulsa_emprendedor' ? $usuarioId : null,
            'duration_months' => (int) $precio['duration_months'],
            'monthly_price' => (float) $precio['monthly_price'],
            'total_contract_value' => (float) $precio['total_price'],
            'notes' => trim((string) $notas) ?: null,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function obtenerUsuarioMarketing(): ?array
    {
        $stmt = $this->pdo->prepare("SELECT id, correo, rol FROM user_auth WHERE rol = 'impulsa_marketing' LIMIT 1");
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        return $usuario ?: null;
    }

    public function obtenerDetalleSolicitud(int $subscriptionId): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT mps.*, mp.name AS plan_name, mp.short_description, mp.full_description, mp.objective,
                    mp.report_frequency, mp.support_level, mp.billing_period,
                    mp.recommended_ad_budget_min, mp.recommended_ad_budget_max, mp.setup_fee AS plan_setup_fee,
                    mpo.currency, uc.correo AS client_email, ue.correo AS entrepreneur_email,
                    uic.nombre AS client_nombre, uic.apellido AS client_apellido, uic.apodo AS client_apodo,
                    uie.nombre AS entrepreneur_nombre, uie.apellido AS entrepreneur_apellido, uie.apodo AS entrepreneur_apodo
             FROM marketing_plan_subscriptions mps
             INNER JOIN marketing_plans mp ON mp.id = mps.plan_id
             INNER JOIN marketing_plan_pricing_options mpo ON mpo.id = mps.pricing_option_id
             LEFT JOIN user_auth uc ON uc.id = mps.client_user_id
             LEFT JOIN user_auth ue ON ue.id = mps.entrepreneur_user_id
             LEFT JOIN user_info uic ON uic.user_auth_id = uc.id
             LEFT JOIN user_info uie ON uie.user_auth_id = ue.id
             WHERE mps.id = :id"
        );
        $stmt->execute(['id' => $subscriptionId]);
        $detalle = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$detalle) {
            return null;
        }

        $detalle['features'] = $this->obtenerFeatures((int) $detalle['plan_id']);
        return $detalle;
    }

    private function obtenerFeatures(int $planId): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM marketing_plan_features WHERE plan_id = :id ORDER BY feature_order ASC, id ASC');
        $stmt->execute(['id' => $planId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    private function obtenerPrecios(int $planId): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM marketing_plan_pricing_options WHERE plan_id = :id ORDER BY is_default DESC, display_order ASC, duration_months ASC');
        $stmt->execute(['id' => $planId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
}
