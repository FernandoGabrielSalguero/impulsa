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
