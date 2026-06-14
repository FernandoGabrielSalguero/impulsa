<?php

class VisualizadorResultadosMarketingModel
{
    public function __construct(private PDO $pdo)
    {
    }

    public function obtenerResultados(array $usuario, array $filtros = []): array
    {
        $rol = (string) ($usuario['rol'] ?? '');
        $usuarioId = (int) ($usuario['id'] ?? 0);
        $where = [];
        $params = [];

        if ($rol === 'impulsa_emprendedor') {
            $where[] = 'mps.entrepreneur_user_id = :usuario_id';
            $params['usuario_id'] = $usuarioId;
        } elseif ($rol === 'impulsa_cliente') {
            $where[] = 'mps.client_user_id = :usuario_id';
            $params['usuario_id'] = $usuarioId;
            $where[] = '(mr.visible_to_client = 1 OR mr.id IS NULL)';
        }

        if (!empty($filtros['subscription_id'])) {
            $where[] = 'mps.id = :subscription_id';
            $params['subscription_id'] = (int) $filtros['subscription_id'];
        }

        $sqlWhere = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        $stmt = $this->pdo->prepare(
            "SELECT mps.id AS subscription_id, mps.status AS subscription_status, mp.name AS plan_name,
                    mc.id AS campaign_id, mc.campaign_name, mc.status AS campaign_status,
                    MAX(mcm.report_end_date) AS last_metric_date,
                    SUM(COALESCE(mcm.amount_spent_ars, 0)) AS spent_total,
                    SUM(COALESCE(mcm.impressions, 0)) AS impressions_total,
                    SUM(COALESCE(mcm.reach, 0)) AS reach_total,
                    SUM(COALESCE(mcm.results, 0)) AS results_total,
                    SUM(COALESCE(mcom.closed_clients, 0)) AS closed_clients_total,
                    SUM(COALESCE(mcom.closed_revenue, 0)) AS closed_revenue_total,
                    COUNT(DISTINCT mr.id) AS reports_total
             FROM marketing_plan_subscriptions mps
             INNER JOIN marketing_plans mp ON mp.id = mps.plan_id
             LEFT JOIN marketing_campaigns mc ON mc.subscription_id = mps.id
             LEFT JOIN marketing_campaign_metrics mcm ON mcm.campaign_id = mc.id
             LEFT JOIN marketing_commercial_metrics mcom ON mcom.campaign_id = mc.id
             LEFT JOIN marketing_reports mr ON mr.subscription_id = mps.id
             $sqlWhere
             GROUP BY mps.id, mc.id
             ORDER BY COALESCE(MAX(mcm.report_end_date), mps.updated_at) DESC"
        );
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function obtenerReportes(array $usuario): array
    {
        $rol = (string) ($usuario['rol'] ?? '');
        $usuarioId = (int) ($usuario['id'] ?? 0);
        $where = [];
        $params = [];
        if ($rol === 'impulsa_emprendedor') {
            $where[] = 'mps.entrepreneur_user_id = :usuario_id';
            $params['usuario_id'] = $usuarioId;
        } elseif ($rol === 'impulsa_cliente') {
            $where[] = 'mps.client_user_id = :usuario_id';
            $where[] = 'mr.visible_to_client = 1';
            $params['usuario_id'] = $usuarioId;
        }
        $sqlWhere = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        $stmt = $this->pdo->prepare(
            "SELECT mr.*, mp.name AS plan_name
             FROM marketing_reports mr
             INNER JOIN marketing_plan_subscriptions mps ON mps.id = mr.subscription_id
             INNER JOIN marketing_plans mp ON mp.id = mps.plan_id
             $sqlWhere
             ORDER BY mr.period_end DESC, mr.created_at DESC"
        );
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
}
