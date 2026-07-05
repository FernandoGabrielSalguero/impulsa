<?php

namespace App\Services\Admin;

use App\Models\Project;
use DateTimeImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use RuntimeException;
use Throwable;

class ProjectStructureService
{
    /** @return list<array<string, mixed>> */
    public function getPhases(int $projectId): array
    {
        return DB::table('project_phases')
            ->where('project_id', $projectId)
            ->orderBy('phase_order')
            ->orderBy('id')
            ->get()
            ->map(static fn ($row): array => (array) $row)
            ->all();
    }

    /** @return list<array<string, mixed>> */
    public function getDeliverables(int $projectId): array
    {
        return DB::table('project_deliverables as pd')
            ->leftJoin('project_phases as pp', function ($join): void {
                $join->on('pp.id', '=', 'pd.phase_id')
                    ->on('pp.project_id', '=', 'pd.project_id');
            })
            ->where('pd.project_id', $projectId)
            ->orderByRaw('pd.phase_id IS NULL ASC')
            ->orderBy('pd.phase_id')
            ->orderByRaw('pd.due_date IS NULL ASC')
            ->orderBy('pd.due_date')
            ->orderBy('pd.id')
            ->get([
                'pd.id',
                'pd.project_id',
                'pd.phase_id',
                'pd.title',
                'pd.description',
                'pd.deliverable_type',
                'pd.status',
                'pd.due_date',
                'pd.delivered_at',
                'pd.client_visible',
                'pp.title as phase_title',
            ])
            ->map(static fn ($row): array => (array) $row)
            ->all();
    }

    public function managerExists(int $managerUserId): bool
    {
        return DB::table('user_auth')
            ->where('id', $managerUserId)
            ->whereIn('rol', ['impulsa_administrador', 'impulsa_colaborador', 'impulsa_marketing'])
            ->exists();
    }

    /** @param array<string, mixed> $data */
    public function createPhase(Project $project, array $data): array
    {
        $title = trim((string) $data['title']);

        if ($this->phaseTitleExists((int) $project->id, $title)) {
            throw ValidationException::withMessages([
                'title' => ['Ya existe una fase con ese título en el proyecto.'],
            ]);
        }

        $phaseId = (int) DB::table('project_phases')->insertGetId([
            'project_id' => $project->id,
            'title' => $title,
            'description' => filled($data['description'] ?? null) ? trim((string) $data['description']) : null,
            'duration_days' => filled($data['duration_days'] ?? null) ? (int) $data['duration_days'] : null,
            'phase_order' => max(1, (int) ($data['phase_order'] ?? 1)),
            'status' => $data['status'],
            'due_date' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->recalculateProject((int) $project->id);

        return $this->getPhase((int) $project->id, $phaseId);
    }

    /** @param array<string, mixed> $data */
    public function updatePhase(Project $project, int $phaseId, array $data): array
    {
        $this->assertPhaseBelongsToProject($phaseId, (int) $project->id);

        $title = trim((string) $data['title']);

        if ($this->phaseTitleExists((int) $project->id, $title, $phaseId)) {
            throw ValidationException::withMessages([
                'title' => ['Ya existe una fase con ese título en el proyecto.'],
            ]);
        }

        DB::table('project_phases')
            ->where('id', $phaseId)
            ->where('project_id', $project->id)
            ->update([
                'title' => $title,
                'description' => filled($data['description'] ?? null) ? trim((string) $data['description']) : null,
                'duration_days' => filled($data['duration_days'] ?? null) ? (int) $data['duration_days'] : null,
                'phase_order' => max(1, (int) ($data['phase_order'] ?? 1)),
                'status' => $data['status'],
                'updated_at' => now(),
            ]);

        $this->recalculateProject((int) $project->id);

        return $this->getPhase((int) $project->id, $phaseId);
    }

    public function deletePhase(Project $project, int $phaseId): void
    {
        DB::beginTransaction();

        try {
            $phase = DB::table('project_phases')
                ->where('id', $phaseId)
                ->where('project_id', $project->id)
                ->lockForUpdate()
                ->first();

            if ($phase === null) {
                throw ValidationException::withMessages([
                    'phase' => ['La fase seleccionada no pertenece a este proyecto.'],
                ]);
            }

            $deliverableIds = DB::table('project_deliverables')
                ->where('phase_id', $phaseId)
                ->pluck('id')
                ->map(static fn ($id): int => (int) $id)
                ->all();

            if ($deliverableIds !== []) {
                DB::table('project_deliverable_tasks')->whereIn('deliverable_id', $deliverableIds)->delete();
                DB::table('project_deliverables')->whereIn('id', $deliverableIds)->delete();
            }

            DB::table('project_updates')->where('phase_id', $phaseId)->delete();
            DB::table('project_phases')->where('id', $phaseId)->delete();

            DB::commit();
        } catch (Throwable $exception) {
            DB::rollBack();

            if ($exception instanceof ValidationException) {
                throw $exception;
            }

            throw new RuntimeException('No se pudo eliminar la fase.', 0, $exception);
        }

        $this->recalculateProject((int) $project->id);
    }

    /** @param array<string, mixed> $data */
    public function createDeliverable(Project $project, array $data): array
    {
        $phaseId = (int) $data['phase_id'];
        $this->assertPhaseBelongsToProject($phaseId, (int) $project->id);

        $title = trim((string) $data['title']);

        if ($this->deliverableTitleExists($phaseId, $title)) {
            throw ValidationException::withMessages([
                'title' => ['Ya existe un objetivo con ese título en la fase.'],
            ]);
        }

        $deliverableId = (int) DB::table('project_deliverables')->insertGetId([
            'project_id' => $project->id,
            'phase_id' => $phaseId,
            'title' => $title,
            'description' => filled($data['description'] ?? null) ? trim((string) $data['description']) : null,
            'deliverable_type' => $data['deliverable_type'],
            'status' => $data['status'],
            'due_date' => filled($data['due_date'] ?? null) ? $data['due_date'] : null,
            'client_visible' => (bool) ($data['client_visible'] ?? true),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->recalculateProject((int) $project->id);

        return $this->getDeliverable((int) $project->id, $deliverableId);
    }

    /** @param array<string, mixed> $data */
    public function updateDeliverable(Project $project, int $deliverableId, array $data): array
    {
        $this->assertDeliverableBelongsToProject($deliverableId, (int) $project->id);

        $phaseId = (int) $data['phase_id'];
        $this->assertPhaseBelongsToProject($phaseId, (int) $project->id);

        $title = trim((string) $data['title']);

        if ($this->deliverableTitleExists($phaseId, $title, $deliverableId)) {
            throw ValidationException::withMessages([
                'title' => ['Ya existe un objetivo con ese título en la fase.'],
            ]);
        }

        DB::table('project_deliverables')
            ->where('id', $deliverableId)
            ->where('project_id', $project->id)
            ->update([
                'phase_id' => $phaseId,
                'title' => $title,
                'description' => filled($data['description'] ?? null) ? trim((string) $data['description']) : null,
                'deliverable_type' => $data['deliverable_type'],
                'status' => $data['status'],
                'due_date' => filled($data['due_date'] ?? null) ? $data['due_date'] : null,
                'client_visible' => (bool) ($data['client_visible'] ?? true),
                'updated_at' => now(),
            ]);

        $this->recalculateProject((int) $project->id);

        return $this->getDeliverable((int) $project->id, $deliverableId);
    }

    public function deleteDeliverable(Project $project, int $deliverableId): void
    {
        DB::beginTransaction();

        try {
            $deliverable = DB::table('project_deliverables')
                ->where('id', $deliverableId)
                ->where('project_id', $project->id)
                ->lockForUpdate()
                ->first();

            if ($deliverable === null) {
                throw ValidationException::withMessages([
                    'deliverable' => ['El objetivo seleccionado no pertenece a este proyecto.'],
                ]);
            }

            DB::table('project_deliverable_tasks')->where('deliverable_id', $deliverableId)->delete();
            DB::table('project_deliverables')->where('id', $deliverableId)->delete();

            DB::commit();
        } catch (Throwable $exception) {
            DB::rollBack();

            if ($exception instanceof ValidationException) {
                throw $exception;
            }

            throw new RuntimeException('No se pudo eliminar el objetivo.', 0, $exception);
        }

        $this->recalculateProject((int) $project->id);
    }

    /** @return array{target_delivery_date: ?string, progress_percent: int, progress_detail: string} */
    public function recalculateProject(int $projectId): array
    {
        $project = DB::table('projects')->where('id', $projectId)->first(['start_date']);
        $phases = DB::table('project_phases')
            ->where('project_id', $projectId)
            ->orderBy('phase_order')
            ->orderBy('id')
            ->get(['id', 'duration_days', 'phase_order', 'status']);

        $deliverables = DB::table('project_deliverables as pd')
            ->join('project_phases as pp', function ($join): void {
                $join->on('pp.id', '=', 'pd.phase_id')
                    ->on('pp.project_id', '=', 'pd.project_id');
            })
            ->where('pd.project_id', $projectId)
            ->orderByRaw('pd.due_date IS NULL ASC')
            ->orderBy('pd.due_date')
            ->orderBy('pd.id')
            ->get(['pd.id', 'pd.phase_id', 'pd.status', 'pd.due_date']);

        $deliverablesByPhase = [];

        foreach ($deliverables as $deliverable) {
            $deliverablesByPhase[(int) $deliverable->phase_id][] = (array) $deliverable;
        }

        $finalDate = null;
        $cursor = $this->createDate($project->start_date ?? null);

        foreach ($phases as $phase) {
            $phaseId = (int) $phase->id;
            $phaseDate = null;

            if ($cursor instanceof DateTimeImmutable) {
                $days = max(0, (int) ($phase->duration_days ?? 0));
                $phaseDate = $cursor->modify('+' . $days . ' days');
            }

            foreach ($deliverablesByPhase[$phaseId] ?? [] as $deliverable) {
                $objectiveDate = $this->createDate($deliverable['due_date'] ?? null);

                if ($objectiveDate instanceof DateTimeImmutable && (! $phaseDate || $objectiveDate > $phaseDate)) {
                    $phaseDate = $objectiveDate;
                }
            }

            DB::table('project_phases')
                ->where('id', $phaseId)
                ->where('project_id', $projectId)
                ->update([
                    'due_date' => $phaseDate?->format('Y-m-d'),
                    'updated_at' => now(),
                ]);

            if ($phaseDate instanceof DateTimeImmutable) {
                $cursor = $phaseDate;

                if (! $finalDate || $phaseDate > $finalDate) {
                    $finalDate = $phaseDate;
                }
            }
        }

        foreach ($deliverables as $deliverable) {
            $objectiveDate = $this->createDate($deliverable->due_date ?? null);

            if ($objectiveDate instanceof DateTimeImmutable && (! $finalDate || $objectiveDate > $finalDate)) {
                $finalDate = $objectiveDate;
            }
        }

        $progress = $this->calculateProgress($phases->map(static fn ($row): array => (array) $row)->all(), $deliverables->map(static fn ($row): array => (array) $row)->all());

        DB::table('projects')
            ->where('id', $projectId)
            ->update([
                'target_delivery_date' => $finalDate?->format('Y-m-d'),
                'progress_percent' => $progress['percent'],
                'updated_at' => now(),
            ]);

        return [
            'target_delivery_date' => $finalDate?->format('Y-m-d'),
            'progress_percent' => $progress['percent'],
            'progress_detail' => $progress['detail'],
        ];
    }

    private function phaseTitleExists(int $projectId, string $title, int $exceptId = 0): bool
    {
        $query = DB::table('project_phases')
            ->where('project_id', $projectId)
            ->whereRaw('LOWER(title) = ?', [mb_strtolower($title)]);

        if ($exceptId > 0) {
            $query->where('id', '!=', $exceptId);
        }

        return $query->exists();
    }

    private function deliverableTitleExists(int $phaseId, string $title, int $exceptId = 0): bool
    {
        $query = DB::table('project_deliverables')
            ->where('phase_id', $phaseId)
            ->whereRaw('LOWER(title) = ?', [mb_strtolower($title)]);

        if ($exceptId > 0) {
            $query->where('id', '!=', $exceptId);
        }

        return $query->exists();
    }

    private function assertPhaseBelongsToProject(int $phaseId, int $projectId): void
    {
        if ($phaseId <= 0) {
            throw ValidationException::withMessages([
                'phase_id' => ['Tenés que seleccionar una fase válida.'],
            ]);
        }

        $exists = DB::table('project_phases')
            ->where('id', $phaseId)
            ->where('project_id', $projectId)
            ->exists();

        if (! $exists) {
            throw ValidationException::withMessages([
                'phase_id' => ['La fase seleccionada no pertenece a este proyecto.'],
            ]);
        }
    }

    private function assertDeliverableBelongsToProject(int $deliverableId, int $projectId): void
    {
        $exists = DB::table('project_deliverables')
            ->where('id', $deliverableId)
            ->where('project_id', $projectId)
            ->exists();

        if (! $exists) {
            throw ValidationException::withMessages([
                'deliverable' => ['El objetivo seleccionado no pertenece a este proyecto.'],
            ]);
        }
    }

    /** @return array<string, mixed> */
    private function getPhase(int $projectId, int $phaseId): array
    {
        $row = DB::table('project_phases')
            ->where('project_id', $projectId)
            ->where('id', $phaseId)
            ->first();

        return $row !== null ? (array) $row : [];
    }

    /** @return array<string, mixed> */
    private function getDeliverable(int $projectId, int $deliverableId): array
    {
        $row = DB::table('project_deliverables as pd')
            ->leftJoin('project_phases as pp', function ($join): void {
                $join->on('pp.id', '=', 'pd.phase_id')
                    ->on('pp.project_id', '=', 'pd.project_id');
            })
            ->where('pd.project_id', $projectId)
            ->where('pd.id', $deliverableId)
            ->first([
                'pd.id',
                'pd.project_id',
                'pd.phase_id',
                'pd.title',
                'pd.description',
                'pd.deliverable_type',
                'pd.status',
                'pd.due_date',
                'pd.delivered_at',
                'pd.client_visible',
                'pp.title as phase_title',
            ]);

        return $row !== null ? (array) $row : [];
    }

    private function createDate(mixed $value): ?DateTimeImmutable
    {
        $value = trim((string) ($value ?? ''));

        if ($value === '') {
            return null;
        }

        $date = DateTimeImmutable::createFromFormat('Y-m-d', $value);

        return $date instanceof DateTimeImmutable ? $date : null;
    }

    /**
     * @param  list<array<string, mixed>>  $phases
     * @param  list<array<string, mixed>>  $deliverables
     * @return array{percent: int, detail: string}
     */
    private function calculateProgress(array $phases, array $deliverables): array
    {
        if ($deliverables !== []) {
            $total = count($deliverables);
            $finished = count(array_filter(
                $deliverables,
                static fn (array $item): bool => ($item['status'] ?? '') === 'delivered',
            ));

            return [
                'percent' => (int) round(($finished / $total) * 100),
                'detail' => $finished . ' de ' . $total . ' objetivos finalizados',
            ];
        }

        if ($phases !== []) {
            $total = count($phases);
            $finished = count(array_filter(
                $phases,
                static fn (array $item): bool => ($item['status'] ?? '') === 'done',
            ));

            return [
                'percent' => (int) round(($finished / $total) * 100),
                'detail' => $finished . ' de ' . $total . ' fases finalizadas',
            ];
        }

        return ['percent' => 0, 'detail' => 'Sin fases ni objetivos'];
    }
}
