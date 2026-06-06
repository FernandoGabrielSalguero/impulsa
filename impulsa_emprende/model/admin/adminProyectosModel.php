<?php

class AdminProyectosModel
{
    public function __construct(private PDO $pdo)
    {
    }

    public function obtenerProyectos(): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT p.id, p.project_name, p.project_type, p.client_user_id, p.manager_user_id,
                    p.client_name, p.client_email, p.client_whatsapp, p.summary, p.scope_summary,
                    p.status, p.priority, p.start_date, p.target_delivery_date, p.progress_percent,
                    p.client_visible, p.created_at, p.updated_at,
                    client.correo AS cliente_correo_login,
                    manager.correo AS manager_correo,
                    (SELECT COUNT(*) FROM project_phases pp WHERE pp.project_id = p.id) AS fases_total,
                    (SELECT COUNT(*) FROM project_deliverables pd WHERE pd.project_id = p.id) AS objetivos_total,
                    pc.id AS contrato_id,
                    pc.contract_name,
                    pc.is_signed
             FROM projects p
             LEFT JOIN user_auth client ON client.id = p.client_user_id
             INNER JOIN user_auth manager ON manager.id = p.manager_user_id
             LEFT JOIN project_contracts pc ON pc.project_id = p.id
             ORDER BY p.updated_at DESC, p.id DESC'
        );
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
