<?php

class AdminProyectManagerModel
{
    public function __construct(private PDO $pdo)
    {
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
             ORDER BY pd.project_id ASC, pd.phase_id ASC, pd.due_date IS NULL ASC, pd.due_date ASC, pd.id ASC'
        );
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
            return false;
        }

        $stmt = $this->pdo->prepare('SELECT id FROM project_phases WHERE id = :id AND project_id = :project_id LIMIT 1');
        $stmt->execute(['id' => $phaseId, 'project_id' => $projectId]);

        return (bool) $stmt->fetchColumn();
    }

    public function objetivoPerteneceAProyecto(int $objectiveId, int $projectId): bool
    {
        $stmt = $this->pdo->prepare('SELECT id FROM project_deliverables WHERE id = :id AND project_id = :project_id LIMIT 1');
        $stmt->execute(['id' => $objectiveId, 'project_id' => $projectId]);

        return (bool) $stmt->fetchColumn();
    }

    public function existeFaseConTitulo(int $projectId, string $title, int $exceptId = 0): bool
    {
        $sql = 'SELECT id
                FROM project_phases
                WHERE project_id = :project_id
                  AND LOWER(title) = LOWER(:title)';
        $params = ['project_id' => $projectId, 'title' => $title];

        if ($exceptId > 0) {
            $sql .= ' AND id <> :except_id';
            $params['except_id'] = $exceptId;
        }

        $sql .= ' LIMIT 1';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return (bool) $stmt->fetchColumn();
    }

    public function existeObjetivoConTituloEnFase(int $phaseId, string $title, int $exceptId = 0): bool
    {
        $sql = 'SELECT id
                FROM project_deliverables
                WHERE phase_id = :phase_id
                  AND LOWER(title) = LOWER(:title)';
        $params = ['phase_id' => $phaseId, 'title' => $title];

        if ($exceptId > 0) {
            $sql .= ' AND id <> :except_id';
            $params['except_id'] = $exceptId;
        }

        $sql .= ' LIMIT 1';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

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
        $stmt->execute($this->parametrosFase($datos));
    }

    public function actualizarFase(int $phaseId, array $datos): void
    {
        $params = $this->parametrosFase($datos);
        $params['id'] = $phaseId;

        $stmt = $this->pdo->prepare(
            'UPDATE project_phases
             SET title = :title,
                 description = :description,
                 duration_days = :duration_days,
                 phase_order = :phase_order,
                 status = :status,
                 due_date = :due_date,
                 updated_at = NOW()
             WHERE id = :id AND project_id = :project_id'
        );
        $stmt->execute($params);
    }

    public function crearObjetivo(array $datos): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO project_deliverables
                (project_id, phase_id, title, description, deliverable_type, status, due_date, client_visible, created_at, updated_at)
             VALUES
                (:project_id, :phase_id, :title, :description, :deliverable_type, :status, :due_date, :client_visible, NOW(), NOW())"
        );
        $stmt->execute($this->parametrosObjetivo($datos));
    }

    public function actualizarObjetivo(int $objectiveId, array $datos): void
    {
        $params = $this->parametrosObjetivo($datos);
        $params['id'] = $objectiveId;

        $stmt = $this->pdo->prepare(
            'UPDATE project_deliverables
             SET phase_id = :phase_id,
                 title = :title,
                 description = :description,
                 deliverable_type = :deliverable_type,
                 status = :status,
                 due_date = :due_date,
                 client_visible = :client_visible,
                 updated_at = NOW()
             WHERE id = :id AND project_id = :project_id'
        );
        $stmt->execute($params);
    }

    private function parametrosFase(array $datos): array
    {
        return [
            'project_id' => (int) $datos['project_id'],
            'title' => $datos['title'],
            'description' => $datos['description'] !== '' ? $datos['description'] : null,
            'duration_days' => $datos['duration_days'] !== '' ? (int) $datos['duration_days'] : null,
            'phase_order' => max(1, (int) $datos['phase_order']),
            'status' => $datos['status'],
            'due_date' => $datos['due_date'] !== '' ? $datos['due_date'] : null,
        ];
    }

    private function parametrosObjetivo(array $datos): array
    {
        return [
            'project_id' => (int) $datos['project_id'],
            'phase_id' => (int) $datos['phase_id'],
            'title' => $datos['title'],
            'description' => $datos['description'] !== '' ? $datos['description'] : null,
            'deliverable_type' => $datos['deliverable_type'],
            'status' => $datos['status'],
            'due_date' => $datos['due_date'] !== '' ? $datos['due_date'] : null,
            'client_visible' => (int) $datos['client_visible'],
        ];
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
