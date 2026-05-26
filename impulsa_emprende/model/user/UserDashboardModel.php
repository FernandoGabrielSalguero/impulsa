<?php

class UserDashboardModel
{
    public function __construct(private PDO $pdo)
    {
    }

    public function obtenerDashboard(int $userId): array
    {
        return [
            'usuario' => $this->obtenerUsuario($userId),
            'resumen' => $this->obtenerResumen($userId),
            'proyectos' => $this->obtenerProyectos($userId),
            'actualizaciones' => $this->obtenerActualizaciones($userId),
            'suscripcionesMarketing' => $this->obtenerSuscripcionesMarketing($userId),
            'reportesMarketing' => $this->obtenerReportesMarketing($userId),
            'contratos' => $this->obtenerContratos($userId),
            'definicion' => $this->obtenerDefinicion($userId),
            'paginaWeb' => $this->obtenerSolicitudPaginaWeb($userId),
        ];
    }

    private function obtenerUsuario(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT ua.id, ua.correo AS auth_correo, ua.rol, ua.created_at,
                    ui.nombre, ui.apellido, ui.apodo, ui.avatar_path,
                    uc.correo AS contacto_correo, uc.whatsapp
             FROM user_auth ua
             LEFT JOIN user_info ui ON ui.user_auth_id = ua.id
             LEFT JOIN user_contacto uc ON uc.user_auth_id = ua.id
             WHERE ua.id = :user_id
             LIMIT 1'
        );
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    private function obtenerResumen(int $userId): array
    {
        return [
            'proyectos_total' => $this->contar(
                'SELECT COUNT(*) FROM projects WHERE client_user_id = :user_id AND client_visible = 1',
                $userId
            ),
            'proyectos_activos' => $this->contar(
                "SELECT COUNT(*) FROM projects
                 WHERE client_user_id = :user_id
                   AND client_visible = 1
                   AND status IN ('planned','in_progress','in_review')",
                $userId
            ),
            'entregables_pendientes' => $this->contar(
                "SELECT COUNT(*)
                 FROM project_deliverables pd
                 INNER JOIN projects p ON p.id = pd.project_id
                 WHERE p.client_user_id = :user_id
                   AND p.client_visible = 1
                   AND pd.client_visible = 1
                   AND pd.status IN ('pending','in_progress','ready_for_review')",
                $userId
            ),
            'reportes_visibles' => $this->contar(
                'SELECT COUNT(*)
                 FROM marketing_reports mr
                 INNER JOIN marketing_plan_subscriptions mps ON mps.id = mr.subscription_id
                 WHERE mps.client_user_id = :user_id
                   AND mr.visible_to_client = 1',
                $userId
            ),
            'suscripciones_marketing' => $this->contar(
                "SELECT COUNT(*)
                 FROM marketing_plan_subscriptions
                 WHERE client_user_id = :user_id
                   AND status IN ('requested','meeting_scheduled','approved_manually','pending_payment','active','paused')",
                $userId
            ),
        ];
    }

    private function obtenerProyectos(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT p.id, p.project_name, p.project_type, p.client_name, p.summary, p.scope_summary,
                    p.status, p.priority, p.start_date, p.target_delivery_date, p.progress_percent,
                    p.created_at, p.updated_at,
                    manager.correo AS manager_correo,
                    manager_info.nombre AS manager_nombre,
                    manager_info.apellido AS manager_apellido,
                    (SELECT COUNT(*) FROM project_phases pp WHERE pp.project_id = p.id) AS fases_total,
                    (SELECT COUNT(*) FROM project_deliverables pd WHERE pd.project_id = p.id AND pd.client_visible = 1) AS entregables_total,
                    (SELECT COUNT(*) FROM project_updates pu WHERE pu.project_id = p.id AND pu.visible_to_client = 1) AS actualizaciones_total
             FROM projects p
             INNER JOIN user_auth manager ON manager.id = p.manager_user_id
             LEFT JOIN user_info manager_info ON manager_info.user_auth_id = manager.id
             WHERE p.client_user_id = :user_id
               AND p.client_visible = 1
             ORDER BY p.updated_at DESC
             LIMIT 8'
        );
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function obtenerActualizaciones(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT pu.id, pu.title, pu.message, pu.progress_delta, pu.created_at,
                    p.project_name,
                    pp.title AS phase_title
             FROM project_updates pu
             INNER JOIN projects p ON p.id = pu.project_id
             LEFT JOIN project_phases pp ON pp.id = pu.phase_id
             WHERE p.client_user_id = :user_id
               AND p.client_visible = 1
               AND pu.visible_to_client = 1
             ORDER BY pu.created_at DESC
             LIMIT 8'
        );
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function obtenerSuscripcionesMarketing(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT mps.id, mps.status, mps.payment_status, mps.duration_months, mps.monthly_price,
                    mps.total_contract_value, mps.start_date, mps.end_date, mps.monthly_ad_budget,
                    mp.name AS plan_name, mp.short_description, mp.objective, mp.report_frequency,
                    (SELECT COUNT(*) FROM marketing_campaigns mc WHERE mc.subscription_id = mps.id) AS campanias_total,
                    (SELECT COUNT(*) FROM marketing_reports mr WHERE mr.subscription_id = mps.id AND mr.visible_to_client = 1) AS reportes_total
             FROM marketing_plan_subscriptions mps
             INNER JOIN marketing_plans mp ON mp.id = mps.plan_id
             WHERE mps.client_user_id = :user_id
             ORDER BY mps.updated_at DESC
             LIMIT 6'
        );
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function obtenerReportesMarketing(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT mr.id, mr.title, mr.summary, mr.period_start, mr.period_end, mr.created_at,
                    mp.name AS plan_name
             FROM marketing_reports mr
             INNER JOIN marketing_plan_subscriptions mps ON mps.id = mr.subscription_id
             INNER JOIN marketing_plans mp ON mp.id = mps.plan_id
             WHERE mps.client_user_id = :user_id
               AND mr.visible_to_client = 1
             ORDER BY mr.period_end DESC, mr.created_at DESC
             LIMIT 6'
        );
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function obtenerContratos(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT pc.id, pc.contract_name, pc.version_number, pc.is_signed, pc.signed_at,
                    p.project_name
             FROM project_contracts pc
             INNER JOIN projects p ON p.id = pc.project_id
             WHERE p.client_user_id = :user_id
               AND p.client_visible = 1
             ORDER BY pc.updated_at DESC
             LIMIT 6'
        );
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function obtenerDefinicion(int $userId): array
    {
        return [
            'mision' => $this->obtenerDefinicionMision($userId),
            'vision' => $this->obtenerDefinicionVision($userId),
            'buyer' => $this->obtenerDefinicionBuyer($userId),
        ];
    }

    private function obtenerDefinicionMision(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT mision_estructura AS resultado, completado
             FROM emprendedor_mision
             WHERE user_auth_id = :user_id
             LIMIT 1'
        );
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: ['resultado' => '', 'completado' => 0];
    }

    private function obtenerDefinicionVision(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT vision_estructura AS resultado, completado
             FROM emprendedor_vision
             WHERE user_auth_id = :user_id
             LIMIT 1'
        );
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: ['resultado' => '', 'completado' => 0];
    }

    private function obtenerDefinicionBuyer(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT buyer_persona_estructura AS resultado, completado
             FROM emprendedor_buyer_persona
             WHERE user_auth_id = :user_id
             LIMIT 1'
        );
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: ['resultado' => '', 'completado' => 0];
    }

    private function obtenerSolicitudPaginaWeb(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, nombre_emprendimiento, completado, created_at, updated_at
             FROM landing_page_request
             WHERE user_auth_id = :user_id
             LIMIT 1'
        );
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    private function contar(string $sql, int $userId): int
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId]);

        return (int) $stmt->fetchColumn();
    }
}
