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

    public function obtenerFasesPorProyecto(): array
    {
        return $this->agruparPorProyecto(
            'SELECT id, project_id, title, description, duration_days, phase_order, status, due_date, completed_at
             FROM project_phases
             ORDER BY project_id ASC, phase_order ASC, id ASC'
        );
    }

    public function obtenerObjetivosPorProyecto(): array
    {
        return $this->agruparPorProyecto(
            'SELECT pd.id, pd.project_id, pd.phase_id, pd.title, pd.description, pd.deliverable_type,
                    pd.status, pd.due_date, pd.delivered_at, pd.client_visible,
                    pp.title AS phase_title
             FROM project_deliverables pd
             LEFT JOIN project_phases pp ON pp.id = pd.phase_id
             ORDER BY pd.project_id ASC, pd.due_date IS NULL ASC, pd.due_date ASC, pd.id ASC'
        );
    }

    public function obtenerContratosPorProyecto(): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, project_id, contract_name, contract_html, contract_text, version_number,
                    is_signed, signed_at, signer_full_name, created_at, updated_at
             FROM project_contracts
             ORDER BY updated_at DESC'
        );
        $stmt->execute();
        $contratos = [];

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $contrato) {
            $contratos[(int) $contrato['project_id']] = $contrato;
        }

        return $contratos;
    }

    public function existeProyecto(int $projectId): bool
    {
        $stmt = $this->pdo->prepare('SELECT id FROM projects WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $projectId]);

        return (bool) $stmt->fetchColumn();
    }

    public function fasePerteneceAProyecto(int $phaseId, int $projectId): bool
    {
        if ($phaseId <= 0) {
            return true;
        }

        $stmt = $this->pdo->prepare('SELECT id FROM project_phases WHERE id = :id AND project_id = :project_id LIMIT 1');
        $stmt->execute(['id' => $phaseId, 'project_id' => $projectId]);

        return (bool) $stmt->fetchColumn();
    }

    public function existeFaseConTitulo(int $projectId, string $title): bool
    {
        $stmt = $this->pdo->prepare(
            'SELECT id FROM project_phases WHERE project_id = :project_id AND LOWER(title) = LOWER(:title) LIMIT 1'
        );
        $stmt->execute(['project_id' => $projectId, 'title' => $title]);

        return (bool) $stmt->fetchColumn();
    }

    public function existeObjetivoConTitulo(int $projectId, string $title): bool
    {
        $stmt = $this->pdo->prepare(
            'SELECT id FROM project_deliverables WHERE project_id = :project_id AND LOWER(title) = LOWER(:title) LIMIT 1'
        );
        $stmt->execute(['project_id' => $projectId, 'title' => $title]);

        return (bool) $stmt->fetchColumn();
    }

    public function crearFase(array $datos): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO project_phases
                (project_id, title, description, duration_days, phase_order, status, due_date, created_at, updated_at)
             VALUES
                (:project_id, :title, :description, :duration_days, :phase_order, :status, :due_date, NOW(), NOW())"
        );
        $stmt->execute([
            'project_id' => (int) $datos['project_id'],
            'title' => $datos['title'],
            'description' => $datos['description'] !== '' ? $datos['description'] : null,
            'duration_days' => $datos['duration_days'] !== '' ? (int) $datos['duration_days'] : null,
            'phase_order' => max(1, (int) $datos['phase_order']),
            'status' => $datos['status'],
            'due_date' => $datos['due_date'] !== '' ? $datos['due_date'] : null,
        ]);
    }

    public function crearObjetivo(array $datos): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO project_deliverables
                (project_id, phase_id, title, description, deliverable_type, status, due_date, client_visible, created_at, updated_at)
             VALUES
                (:project_id, :phase_id, :title, :description, :deliverable_type, :status, :due_date, :client_visible, NOW(), NOW())"
        );
        $stmt->execute([
            'project_id' => (int) $datos['project_id'],
            'phase_id' => (int) $datos['phase_id'] > 0 ? (int) $datos['phase_id'] : null,
            'title' => $datos['title'],
            'description' => $datos['description'] !== '' ? $datos['description'] : null,
            'deliverable_type' => $datos['deliverable_type'],
            'status' => $datos['status'],
            'due_date' => $datos['due_date'] !== '' ? $datos['due_date'] : null,
            'client_visible' => (int) $datos['client_visible'],
        ]);
    }

    public function guardarContrato(array $datos): void
    {
        $stmt = $this->pdo->prepare('SELECT id FROM project_contracts WHERE project_id = :project_id LIMIT 1');
        $stmt->execute(['project_id' => (int) $datos['project_id']]);
        $contratoId = (int) $stmt->fetchColumn();

        if ($contratoId > 0) {
            $stmt = $this->pdo->prepare(
                'UPDATE project_contracts
                 SET contract_name = :contract_name,
                     contract_html = :contract_html,
                     contract_text = :contract_text,
                     version_number = version_number + 1,
                     updated_by_user_id = :updated_by_user_id,
                     updated_at = NOW()
                 WHERE id = :id'
            );
            $stmt->execute([
                'contract_name' => $datos['contract_name'],
                'contract_html' => $datos['contract_html'],
                'contract_text' => $datos['contract_text'],
                'updated_by_user_id' => (int) $datos['admin_user_id'],
                'id' => $contratoId,
            ]);
            return;
        }

        $stmt = $this->pdo->prepare(
            'INSERT INTO project_contracts
                (project_id, contract_name, contract_html, contract_text, version_number, is_signed,
                 created_by_user_id, updated_by_user_id, created_at, updated_at)
             VALUES
                (:project_id, :contract_name, :contract_html, :contract_text, 1, 0,
                 :created_by_user_id, :updated_by_user_id, NOW(), NOW())'
        );
        $stmt->execute([
            'project_id' => (int) $datos['project_id'],
            'contract_name' => $datos['contract_name'],
            'contract_html' => $datos['contract_html'],
            'contract_text' => $datos['contract_text'],
            'created_by_user_id' => (int) $datos['admin_user_id'],
            'updated_by_user_id' => (int) $datos['admin_user_id'],
        ]);
    }

    private function agruparPorProyecto(string $sql): array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $agrupado = [];

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $fila) {
            $agrupado[(int) $fila['project_id']][] = $fila;
        }

        return $agrupado;
    }
}
