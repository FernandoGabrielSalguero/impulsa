<?php

class ClienteDashboardModel
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
            'fases' => $this->obtenerFases($userId),
            'objetivos' => $this->obtenerObjetivos($userId),
            'actualizaciones' => $this->obtenerActualizaciones($userId),
            'contratos' => $this->obtenerContratos($userId),
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
            'proyectos_total' => $this->contar('SELECT COUNT(*) FROM projects WHERE client_user_id = :user_id AND client_visible = 1', $userId),
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
            'contratos_pendientes' => $this->contar(
                "SELECT COUNT(*)
                 FROM project_contracts pc
                 INNER JOIN projects p ON p.id = pc.project_id
                 WHERE p.client_user_id = :user_id
                   AND p.client_visible = 1
                   AND pc.is_signed = 0",
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
             ORDER BY p.updated_at DESC'
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
             LIMIT 10'
        );
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function obtenerFases(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT pp.id, pp.project_id, pp.title, pp.description, pp.duration_days,
                    pp.phase_order, pp.status, pp.due_date, pp.completed_at
             FROM project_phases pp
             INNER JOIN projects p ON p.id = pp.project_id
             WHERE p.client_user_id = :user_id
               AND p.client_visible = 1
             ORDER BY pp.project_id ASC, pp.phase_order ASC, pp.id ASC'
        );
        $stmt->execute(['user_id' => $userId]);

        return $this->agruparPorProyecto($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    private function obtenerObjetivos(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT pd.id, pd.project_id, pd.phase_id, pd.title, pd.description,
                    pd.deliverable_type, pd.status, pd.due_date, pd.delivered_at,
                    pp.title AS phase_title
             FROM project_deliverables pd
             INNER JOIN projects p ON p.id = pd.project_id
             LEFT JOIN project_phases pp ON pp.id = pd.phase_id
             WHERE p.client_user_id = :user_id
               AND p.client_visible = 1
               AND pd.client_visible = 1
             ORDER BY pd.project_id ASC, pd.due_date IS NULL ASC, pd.due_date ASC, pd.id ASC'
        );
        $stmt->execute(['user_id' => $userId]);

        return $this->agruparPorProyecto($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    private function obtenerContratos(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT pc.id, pc.project_id, pc.contract_name, pc.version_number, pc.is_signed, pc.signed_at,
                    p.project_name
             FROM project_contracts pc
             INNER JOIN projects p ON p.id = pc.project_id
             WHERE p.client_user_id = :user_id
               AND p.client_visible = 1
             ORDER BY pc.updated_at DESC'
        );
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function contar(string $sql, int $userId): int
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId]);

        return (int) $stmt->fetchColumn();
    }

    private function agruparPorProyecto(array $filas): array
    {
        $agrupado = [];
        foreach ($filas as $fila) {
            $agrupado[(int) $fila['project_id']][] = $fila;
        }

        return $agrupado;
    }
}
