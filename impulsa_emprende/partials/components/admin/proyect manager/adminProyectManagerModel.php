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
             INNER JOIN project_phases pp ON pp.id = pd.phase_id
                                        AND pp.project_id = pd.project_id
             ORDER BY pd.project_id ASC, pd.phase_id ASC, pd.due_date IS NULL ASC, pd.due_date ASC, pd.id ASC'
        );
    }

    public function obtenerResponsables(): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT ua.id, ua.correo, ui.nombre, ui.apellido
             FROM user_auth ua
             LEFT JOIN user_info ui ON ui.user_auth_id = ua.id
             WHERE ua.rol IN ('impulsa_administrador', 'impulsa_colaborador', 'impulsa_marketing')
             ORDER BY ui.nombre IS NULL ASC, ui.nombre ASC, ua.correo ASC"
        );
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerProyecto(int $projectId): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT p.id, p.source_type, p.source_id, p.project_name, p.project_type, p.client_user_id, p.manager_user_id,
                    p.client_name, p.client_email, p.client_whatsapp, p.summary, p.scope_summary,
                    p.status, p.priority, p.start_date, p.target_delivery_date, p.progress_percent,
                    p.client_visible, p.created_at, p.updated_at,
                    client.correo AS cliente_correo_login,
                    manager.correo AS manager_correo
             FROM projects p
             LEFT JOIN user_auth client ON client.id = p.client_user_id
             INNER JOIN user_auth manager ON manager.id = p.manager_user_id
             WHERE p.id = :id
             LIMIT 1'
        );
        $stmt->execute(['id' => $projectId]);
        $proyecto = $stmt->fetch(PDO::FETCH_ASSOC);

        return $proyecto ?: null;
    }

    public function obtenerFasesProyecto(int $projectId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, project_id, title, description, duration_days, phase_order, status, due_date, completed_at
             FROM project_phases
             WHERE project_id = :project_id
             ORDER BY phase_order ASC, id ASC'
        );
        $stmt->execute(['project_id' => $projectId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerObjetivosProyecto(int $projectId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT pd.id, pd.project_id, pd.phase_id, pd.title, pd.description, pd.deliverable_type,
                    pd.status, pd.due_date, pd.delivered_at, pd.client_visible,
                    pp.title AS phase_title
             FROM project_deliverables pd
             INNER JOIN project_phases pp ON pp.id = pd.phase_id
                                        AND pp.project_id = pd.project_id
             WHERE pd.project_id = :project_id
             ORDER BY pd.phase_id ASC, pd.due_date IS NULL ASC, pd.due_date ASC, pd.id ASC'
        );
        $stmt->execute(['project_id' => $projectId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    public function responsableExiste(int $responsableId): bool
    {
        $stmt = $this->pdo->prepare(
            "SELECT id
             FROM user_auth
             WHERE id = :id
               AND rol IN ('impulsa_administrador', 'impulsa_colaborador', 'impulsa_marketing')
             LIMIT 1"
        );
        $stmt->execute(['id' => $responsableId]);

        return (bool) $stmt->fetchColumn();
    }

    public function actualizarProyecto(int $projectId, array $datos): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE projects
             SET project_name = :project_name,
                 manager_user_id = :manager_user_id,
                 summary = :summary,
                 scope_summary = :scope_summary,
                 status = :status,
                 priority = :priority,
                 start_date = :start_date,
                 client_visible = :client_visible,
                 updated_at = NOW()
             WHERE id = :id'
        );
        $stmt->execute([
            'project_name' => $datos['project_name'],
            'manager_user_id' => (int) $datos['manager_user_id'],
            'summary' => $datos['summary'] !== '' ? $datos['summary'] : null,
            'scope_summary' => $datos['scope_summary'] !== '' ? $datos['scope_summary'] : null,
            'status' => $datos['status'],
            'priority' => $datos['priority'],
            'start_date' => $datos['start_date'] !== '' ? $datos['start_date'] : null,
            'client_visible' => (int) $datos['client_visible'],
            'id' => $projectId,
        ]);
    }

    public function recalcularProyecto(int $projectId): array
    {
        $stmt = $this->pdo->prepare('SELECT start_date FROM projects WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $projectId]);
        $proyecto = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

        $stmt = $this->pdo->prepare(
            'SELECT id, duration_days, phase_order, status
             FROM project_phases
             WHERE project_id = :project_id
             ORDER BY phase_order ASC, id ASC'
        );
        $stmt->execute(['project_id' => $projectId]);
        $fases = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $this->pdo->prepare(
            'SELECT pd.id, pd.phase_id, pd.status, pd.due_date
             FROM project_deliverables pd
             INNER JOIN project_phases pp ON pp.id = pd.phase_id
                                        AND pp.project_id = pd.project_id
             WHERE pd.project_id = :project_id
             ORDER BY pd.due_date IS NULL ASC, pd.due_date ASC, pd.id ASC'
        );
        $stmt->execute(['project_id' => $projectId]);
        $objetivos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $objetivosPorFase = [];
        foreach ($objetivos as $objetivo) {
            $objetivosPorFase[(int) ($objetivo['phase_id'] ?? 0)][] = $objetivo;
        }

        $fechaFinal = null;
        $fechaCursor = $this->crearFecha($proyecto['start_date'] ?? null);
        foreach ($fases as $fase) {
            $phaseId = (int) $fase['id'];
            $fechaFase = null;

            if ($fechaCursor instanceof DateTimeImmutable) {
                $dias = max(0, (int) ($fase['duration_days'] ?? 0));
                $fechaFase = $fechaCursor->modify('+' . $dias . ' days');
            }

            foreach ($objetivosPorFase[$phaseId] ?? [] as $objetivo) {
                $fechaObjetivo = $this->crearFecha($objetivo['due_date'] ?? null);
                if ($fechaObjetivo instanceof DateTimeImmutable && (!$fechaFase || $fechaObjetivo > $fechaFase)) {
                    $fechaFase = $fechaObjetivo;
                }
            }

            $stmt = $this->pdo->prepare('UPDATE project_phases SET due_date = :due_date, updated_at = NOW() WHERE id = :id AND project_id = :project_id');
            $stmt->execute([
                'due_date' => $fechaFase ? $fechaFase->format('Y-m-d') : null,
                'id' => $phaseId,
                'project_id' => $projectId,
            ]);

            if ($fechaFase instanceof DateTimeImmutable) {
                $fechaCursor = $fechaFase;
                if (!$fechaFinal || $fechaFase > $fechaFinal) {
                    $fechaFinal = $fechaFase;
                }
            }
        }

        foreach ($objetivos as $objetivo) {
            $fechaObjetivo = $this->crearFecha($objetivo['due_date'] ?? null);
            if ($fechaObjetivo instanceof DateTimeImmutable && (!$fechaFinal || $fechaObjetivo > $fechaFinal)) {
                $fechaFinal = $fechaObjetivo;
            }
        }

        $progreso = $this->calcularProgreso($fases, $objetivos);
        $stmt = $this->pdo->prepare(
            'UPDATE projects
             SET target_delivery_date = :target_delivery_date,
                 progress_percent = :progress_percent,
                 updated_at = NOW()
             WHERE id = :id'
        );
        $stmt->execute([
            'target_delivery_date' => $fechaFinal ? $fechaFinal->format('Y-m-d') : null,
            'progress_percent' => $progreso['percent'],
            'id' => $projectId,
        ]);

        return [
            'target_delivery_date' => $fechaFinal ? $fechaFinal->format('Y-m-d') : null,
            'progress_percent' => $progreso['percent'],
            'progress_detail' => $progreso['detail'],
        ];
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

    public function eliminarFase(int $projectId, int $phaseId): void
    {
        $this->pdo->beginTransaction();

        try {
            $stmt = $this->pdo->prepare(
                'SELECT id
                 FROM project_phases
                 WHERE id = :id AND project_id = :project_id
                 LIMIT 1
                 FOR UPDATE'
            );
            $stmt->execute([
                'id' => $phaseId,
                'project_id' => $projectId,
            ]);

            if (!$stmt->fetchColumn()) {
                throw new RuntimeException('La fase seleccionada no pertenece a este proyecto.');
            }

            $deliverableIds = $this->obtenerIdsPorCampo('project_deliverables', 'phase_id', [$phaseId]);

            $this->eliminarPorIds('project_deliverable_tasks', 'deliverable_id', $deliverableIds);
            $this->eliminarPorIds('project_updates', 'phase_id', [$phaseId]);
            $this->eliminarPorIds('project_deliverables', 'id', $deliverableIds);
            $this->eliminarPorIds('project_phases', 'id', [$phaseId]);

            $this->pdo->commit();
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $e;
        }
    }

    public function eliminarObjetivo(int $projectId, int $objectiveId): void
    {
        $this->pdo->beginTransaction();

        try {
            $stmt = $this->pdo->prepare(
                'SELECT id
                 FROM project_deliverables
                 WHERE id = :id AND project_id = :project_id
                 LIMIT 1
                 FOR UPDATE'
            );
            $stmt->execute([
                'id' => $objectiveId,
                'project_id' => $projectId,
            ]);

            if (!$stmt->fetchColumn()) {
                throw new RuntimeException('El objetivo seleccionado no pertenece a este proyecto.');
            }

            $this->eliminarPorIds('project_deliverable_tasks', 'deliverable_id', [$objectiveId]);
            $this->eliminarPorIds('project_deliverables', 'id', [$objectiveId]);

            $this->pdo->commit();
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $e;
        }
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
            'due_date' => null,
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

    private function obtenerIdsPorCampo(string $tabla, string $campo, array $ids): array
    {
        if ($ids === []) {
            return [];
        }

        $sql = sprintf(
            'SELECT id FROM %s WHERE %s IN (%s)',
            $tabla,
            $campo,
            $this->placeholders($ids)
        );
        $stmt = $this->pdo->prepare($sql);
        foreach ($ids as $indice => $id) {
            $stmt->bindValue(':ids' . $indice, (int) $id, PDO::PARAM_INT);
        }
        $stmt->execute();

        return array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
    }

    private function eliminarPorIds(string $tabla, string $campo, array $ids): void
    {
        if ($ids === []) {
            return;
        }

        $sql = sprintf(
            'DELETE FROM %s WHERE %s IN (%s)',
            $tabla,
            $campo,
            $this->placeholders($ids)
        );
        $stmt = $this->pdo->prepare($sql);
        foreach ($ids as $indice => $id) {
            $stmt->bindValue(':ids' . $indice, (int) $id, PDO::PARAM_INT);
        }
        $stmt->execute();
    }

    private function crearFecha(mixed $valor): ?DateTimeImmutable
    {
        $valor = trim((string) ($valor ?? ''));
        if ($valor === '') {
            return null;
        }

        $fecha = DateTimeImmutable::createFromFormat('Y-m-d', $valor);

        return $fecha instanceof DateTimeImmutable ? $fecha : null;
    }

    private function calcularProgreso(array $fases, array $objetivos): array
    {
        if ($objetivos) {
            $total = count($objetivos);
            $finalizados = count(array_filter($objetivos, static fn (array $objetivo): bool => ($objetivo['status'] ?? '') === 'delivered'));

            return [
                'percent' => (int) round(($finalizados / $total) * 100),
                'detail' => $finalizados . ' de ' . $total . ' objetivos finalizados',
            ];
        }

        if ($fases) {
            $total = count($fases);
            $finalizadas = count(array_filter($fases, static fn (array $fase): bool => ($fase['status'] ?? '') === 'done'));

            return [
                'percent' => (int) round(($finalizadas / $total) * 100),
                'detail' => $finalizadas . ' de ' . $total . ' fases finalizadas',
            ];
        }

        return ['percent' => 0, 'detail' => 'Sin fases ni objetivos'];
    }

    private function placeholders(array $ids): string
    {
        if ($ids === []) {
            return 'NULL';
        }

        return implode(', ', array_map(static fn (int $indice): string => ':ids' . $indice, array_keys($ids)));
    }
}
