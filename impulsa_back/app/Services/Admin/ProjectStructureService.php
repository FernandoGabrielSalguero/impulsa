<?php

namespace App\Services\Admin;

use App\Models\Project;
use App\Services\Notifications\NotificationService;
use App\Support\ProjectLabels;
use DateTimeImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use RuntimeException;
use Throwable;

class ProjectStructureService
{
    public function __construct(
        private readonly ProjectClientNotificationService $clientNotificationService,
        private readonly NotificationService $notificationService,
    ) {}

    /** @return list<array<string, mixed>> */
    public function getPhases(int $projectId): array
    {
        return DB::table('project_phases as pp')
            ->leftJoin('user_auth as ua', 'ua.id', '=', 'pp.assigned_user_id')
            ->leftJoin('user_info as ui', 'ui.user_auth_id', '=', 'ua.id')
            ->where('pp.project_id', $projectId)
            ->orderBy('pp.phase_order')
            ->orderBy('pp.id')
            ->get([
                'pp.*',
                'ua.correo as assigned_user_correo',
                'ui.nombre as assigned_user_nombre',
                'ui.apellido as assigned_user_apellido',
            ])
            ->map(fn ($row): array => $this->withAssigneeLabel((array) $row))
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
            ->leftJoin('user_auth as ua', 'ua.id', '=', 'pd.assigned_user_id')
            ->leftJoin('user_info as ui', 'ui.user_auth_id', '=', 'ua.id')
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
                'pd.assigned_user_id',
                'pp.title as phase_title',
                'ua.correo as assigned_user_correo',
                'ui.nombre as assigned_user_nombre',
                'ui.apellido as assigned_user_apellido',
            ])
            ->map(fn ($row): array => $this->withAssigneeLabel((array) $row))
            ->all();
    }

    public function managerExists(int $managerUserId): bool
    {
        return DB::table('user_auth')
            ->where('id', $managerUserId)
            ->whereIn('rol', ['impulsa_administrador', 'impulsa_colaborador', 'impulsa_marketing'])
            ->exists();
    }

    public function clientExists(int $clientUserId): bool
    {
        return DB::table('user_auth')
            ->where('id', $clientUserId)
            ->whereIn('rol', ['impulsa_cliente', 'impulsa_emprendedor'])
            ->exists();
    }

    public function seedDefaultStructure(int $projectId, int $managerUserId, string $updateMessage): void
    {
        $phases = [
            ['Relevamiento y alcance', 'Revisión de objetivos, contenido, referencias y criterios de éxito.', 1],
            ['Diseño y contenidos', 'Definición visual, estructura de secciones y textos principales.', 2],
            ['Desarrollo y publicación', 'Construcción, pruebas, ajustes finales y puesta online.', 3],
        ];

        foreach ($phases as [$title, $description, $order]) {
            DB::table('project_phases')->insert([
                'project_id' => $projectId,
                'title' => $title,
                'description' => $description,
                'duration_days' => null,
                'phase_order' => $order,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $deliverables = [
            ['Documento de alcance', 'Resumen inicial de objetivos, secciones y materiales necesarios.', 'document'],
            ['Propuesta visual', 'Base visual y criterio de marca para la página web.', 'design'],
            ['Página web publicada', 'Entrega de la página construida y publicada.', 'deployment'],
        ];

        foreach ($deliverables as [$title, $description, $type]) {
            DB::table('project_deliverables')->insert([
                'project_id' => $projectId,
                'phase_id' => null,
                'title' => $title,
                'description' => $description,
                'deliverable_type' => $type,
                'status' => 'pending',
                'client_visible' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('project_updates')->insert([
            'project_id' => $projectId,
            'phase_id' => null,
            'created_by' => $managerUserId,
            'title' => 'Proyecto creado',
            'message' => $updateMessage,
            'progress_delta' => null,
            'visible_to_client' => true,
            'created_at' => now(),
        ]);
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
            'assigned_user_id' => $this->resolveAssignedUserId((int) $project->id, $data['assigned_user_id'] ?? null),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $progress = $this->recalculateProject((int) $project->id);

        $phase = $this->getPhase((int) $project->id, $phaseId);

        $this->clientNotificationService->notify(
            project: $project,
            updateTitle: 'Nueva fase agregada',
            updateMessage: 'Incorporamos una nueva etapa al plan de trabajo de tu proyecto.',
            changeLines: [
                'Fase: ' . $phase['title'],
                'Estado: ' . ProjectLabels::phaseStatusLabel($phase['status'] ?? null),
            ],
            createdByUserId: $this->actorUserId(),
            phaseId: $phaseId,
            progress: $progress,
        );

        $this->notificationService->notifyProjectPhaseCreated(
            projectId: (int) $project->id,
            phaseId: $phaseId,
            phaseTitle: (string) $phase['title'],
            actorUserId: $this->actorUserId(),
            notifyClient: (bool) ($project->client_visible ?? false),
        );

        return $phase;
    }

    /** @param array<string, mixed> $data */
    public function updatePhase(Project $project, int $phaseId, array $data): array
    {
        $this->assertPhaseBelongsToProject($phaseId, (int) $project->id);

        $before = $this->getPhase((int) $project->id, $phaseId);
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
                'assigned_user_id' => $this->resolveAssignedUserId((int) $project->id, $data['assigned_user_id'] ?? null),
                'updated_at' => now(),
            ]);

        $progress = $this->recalculateProject((int) $project->id);

        $after = $this->getPhase((int) $project->id, $phaseId);
        $changeLines = $this->describePhaseChanges($before, $after);

        if ($changeLines !== []) {
            $this->clientNotificationService->notify(
                project: $project,
                updateTitle: 'Fase actualizada',
                updateMessage: 'Actualizamos una etapa de tu proyecto.',
                changeLines: $changeLines,
                createdByUserId: $this->actorUserId(),
                phaseId: $phaseId,
                progress: $progress,
            );
        }

        return $after;
    }

    public function deletePhase(Project $project, int $phaseId): void
    {
        $phaseTitle = 'Sin nombre';

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

            $phaseTitle = (string) $phase->title;

            $deliverableIds = DB::table('project_deliverables')
                ->where('phase_id', $phaseId)
                ->pluck('id')
                ->map(static fn ($id): int => (int) $id)
                ->all();

            if ($deliverableIds !== []) {
                DB::table('project_deliverable_tasks')->whereIn('deliverable_id', $deliverableIds)->delete();
                DB::table('project_deliverable_comments')->whereIn('deliverable_id', $deliverableIds)->delete();
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

        $progress = $this->recalculateProject((int) $project->id);

        $this->clientNotificationService->notify(
            project: $project,
            updateTitle: 'Fase eliminada',
            updateMessage: 'Reorganizamos el plan de trabajo de tu proyecto.',
            changeLines: [
                'Se eliminó la fase: ' . $phaseTitle,
            ],
            createdByUserId: $this->actorUserId(),
            phaseId: null,
            progress: $progress,
        );
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
            'assigned_user_id' => $this->resolveAssignedUserId((int) $project->id, $data['assigned_user_id'] ?? null),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $progress = $this->recalculateProject((int) $project->id);

        $deliverable = $this->getDeliverable((int) $project->id, $deliverableId);

        $this->clientNotificationService->notify(
            project: $project,
            updateTitle: 'Nuevo objetivo agregado',
            updateMessage: 'Sumamos un nuevo objetivo al avance de tu proyecto.',
            changeLines: [
                'Objetivo: ' . $deliverable['title'],
                'Estado: ' . ProjectLabels::deliverableStatusLabel($deliverable['status'] ?? null),
            ],
            createdByUserId: $this->actorUserId(),
            phaseId: $phaseId,
            progress: $progress,
        );

        $notifyClient = (bool) ($project->client_visible ?? false)
            && (bool) ($deliverable['client_visible'] ?? false);

        $this->notificationService->notifyProjectDeliverableCreated(
            projectId: (int) $project->id,
            deliverableId: $deliverableId,
            deliverableTitle: (string) $deliverable['title'],
            actorUserId: $this->actorUserId(),
            notifyClient: $notifyClient,
        );

        return $deliverable;
    }

    /** @param array<string, mixed> $data */
    public function updateDeliverable(Project $project, int $deliverableId, array $data): array
    {
        $this->assertDeliverableBelongsToProject($deliverableId, (int) $project->id);

        $before = $this->getDeliverable((int) $project->id, $deliverableId);
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
                'assigned_user_id' => $this->resolveAssignedUserId((int) $project->id, $data['assigned_user_id'] ?? null),
                'updated_at' => now(),
            ]);

        $progress = $this->recalculateProject((int) $project->id);

        $after = $this->getDeliverable((int) $project->id, $deliverableId);
        $changeLines = $this->describeDeliverableChanges($before, $after);

        if ($changeLines !== []) {
            $this->clientNotificationService->notify(
                project: $project,
                updateTitle: 'Objetivo actualizado',
                updateMessage: 'Actualizamos un objetivo de tu proyecto.',
                changeLines: $changeLines,
                createdByUserId: $this->actorUserId(),
                phaseId: $phaseId,
                progress: $progress,
            );
        }

        return $after;
    }

    public function deleteDeliverable(Project $project, int $deliverableId): void
    {
        $deliverableTitle = 'Sin nombre';
        $phaseId = null;

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

            $deliverableTitle = (string) $deliverable->title;
            $phaseId = $deliverable->phase_id !== null ? (int) $deliverable->phase_id : null;

            DB::table('project_deliverable_tasks')->where('deliverable_id', $deliverableId)->delete();
            DB::table('project_deliverable_comments')->where('deliverable_id', $deliverableId)->delete();
            DB::table('project_deliverables')->where('id', $deliverableId)->delete();

            DB::commit();
        } catch (Throwable $exception) {
            DB::rollBack();

            if ($exception instanceof ValidationException) {
                throw $exception;
            }

            throw new RuntimeException('No se pudo eliminar el objetivo.', 0, $exception);
        }

        $progress = $this->recalculateProject((int) $project->id);

        $this->clientNotificationService->notify(
            project: $project,
            updateTitle: 'Objetivo eliminado',
            updateMessage: 'Reorganizamos los objetivos de tu proyecto.',
            changeLines: [
                'Se eliminó el objetivo: ' . $deliverableTitle,
            ],
            createdByUserId: $this->actorUserId(),
            phaseId: $phaseId,
            progress: $progress,
        );
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
        $row = DB::table('project_phases as pp')
            ->leftJoin('user_auth as ua', 'ua.id', '=', 'pp.assigned_user_id')
            ->leftJoin('user_info as ui', 'ui.user_auth_id', '=', 'ua.id')
            ->where('pp.project_id', $projectId)
            ->where('pp.id', $phaseId)
            ->first([
                'pp.*',
                'ua.correo as assigned_user_correo',
                'ui.nombre as assigned_user_nombre',
                'ui.apellido as assigned_user_apellido',
            ]);

        return $row !== null ? $this->withAssigneeLabel((array) $row) : [];
    }

    /** @return array<string, mixed> */
    private function getDeliverable(int $projectId, int $deliverableId): array
    {
        $row = DB::table('project_deliverables as pd')
            ->leftJoin('project_phases as pp', function ($join): void {
                $join->on('pp.id', '=', 'pd.phase_id')
                    ->on('pp.project_id', '=', 'pd.project_id');
            })
            ->leftJoin('user_auth as ua', 'ua.id', '=', 'pd.assigned_user_id')
            ->leftJoin('user_info as ui', 'ui.user_auth_id', '=', 'ua.id')
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
                'pd.assigned_user_id',
                'pp.title as phase_title',
                'ua.correo as assigned_user_correo',
                'ui.nombre as assigned_user_nombre',
                'ui.apellido as assigned_user_apellido',
            ]);

        return $row !== null ? $this->withAssigneeLabel((array) $row) : [];
    }

    private function resolveAssignedUserId(int $projectId, mixed $assignedUserId): ?int
    {
        if ($assignedUserId === null || $assignedUserId === '' || (int) $assignedUserId <= 0) {
            return null;
        }

        $userId = (int) $assignedUserId;
        $exists = DB::table('project_collaborators')
            ->where('project_id', $projectId)
            ->where('user_auth_id', $userId)
            ->exists();

        if (! $exists) {
            throw ValidationException::withMessages([
                'assigned_user_id' => ['El responsable debe ser un colaborador del proyecto.'],
            ]);
        }

        return $userId;
    }

    /**
     * @param  array<string, mixed>  $row
     * @return array<string, mixed>
     */
    private function withAssigneeLabel(array $row): array
    {
        $assignedUserId = isset($row['assigned_user_id']) && $row['assigned_user_id'] !== null
            ? (int) $row['assigned_user_id']
            : null;

        $name = trim((string) (($row['assigned_user_nombre'] ?? '') . ' ' . ($row['assigned_user_apellido'] ?? '')));
        $correo = trim((string) ($row['assigned_user_correo'] ?? ''));

        $row['assigned_user_id'] = $assignedUserId;
        $row['assigned_user_label'] = $assignedUserId === null
            ? null
            : ($name !== '' ? $name : ($correo !== '' ? $correo : null));

        unset(
            $row['assigned_user_correo'],
            $row['assigned_user_nombre'],
            $row['assigned_user_apellido'],
        );

        return $row;
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

    private function actorUserId(): ?int
    {
        $userId = auth()->id();

        return $userId ? (int) $userId : null;
    }

    /**
     * @param  array<string, mixed>  $before
     * @param  array<string, mixed>  $after
     * @return list<string>
     */
    private function describePhaseChanges(array $before, array $after): array
    {
        $lines = [];
        $phaseLabel = (string) ($after['title'] ?? $before['title'] ?? 'Fase');

        if (($before['title'] ?? '') !== ($after['title'] ?? '')) {
            $lines[] = 'Fase renombrada: ' . ($before['title'] ?? '—') . ' → ' . ($after['title'] ?? '—');
        }

        if (($before['status'] ?? '') !== ($after['status'] ?? '')) {
            $lines[] = $phaseLabel . ': ' . ProjectLabels::phaseStatusLabel($before['status'] ?? null)
                . ' → ' . ProjectLabels::phaseStatusLabel($after['status'] ?? null);
        }

        if ((int) ($before['phase_order'] ?? 0) !== (int) ($after['phase_order'] ?? 0)) {
            $lines[] = $phaseLabel . ': orden ' . (int) ($before['phase_order'] ?? 0) . ' → ' . (int) ($after['phase_order'] ?? 0);
        }

        if (($before['duration_days'] ?? null) !== ($after['duration_days'] ?? null)) {
            $lines[] = $phaseLabel . ': duración actualizada.';
        }

        if (($before['description'] ?? null) !== ($after['description'] ?? null)) {
            $lines[] = $phaseLabel . ': descripción actualizada.';
        }

        return $lines;
    }

    /**
     * @param  array<string, mixed>  $before
     * @param  array<string, mixed>  $after
     * @return list<string>
     */
    private function describeDeliverableChanges(array $before, array $after): array
    {
        $lines = [];
        $label = (string) ($after['title'] ?? $before['title'] ?? 'Objetivo');

        if (($before['title'] ?? '') !== ($after['title'] ?? '')) {
            $lines[] = 'Objetivo renombrado: ' . ($before['title'] ?? '—') . ' → ' . ($after['title'] ?? '—');
        }

        if (($before['status'] ?? '') !== ($after['status'] ?? '')) {
            $lines[] = $label . ': ' . ProjectLabels::deliverableStatusLabel($before['status'] ?? null)
                . ' → ' . ProjectLabels::deliverableStatusLabel($after['status'] ?? null);
        }

        if (($before['deliverable_type'] ?? '') !== ($after['deliverable_type'] ?? '')) {
            $lines[] = $label . ': tipo ' . ProjectLabels::deliverableTypeLabel($before['deliverable_type'] ?? null)
                . ' → ' . ProjectLabels::deliverableTypeLabel($after['deliverable_type'] ?? null);
        }

        if (($before['phase_id'] ?? null) !== ($after['phase_id'] ?? null)) {
            $lines[] = $label . ': reasignado de fase.';
        }

        if (($before['due_date'] ?? null) !== ($after['due_date'] ?? null)) {
            $lines[] = $label . ': fecha objetivo actualizada.';
        }

        if (($before['description'] ?? null) !== ($after['description'] ?? null)) {
            $lines[] = $label . ': descripción actualizada.';
        }

        return $lines;
    }
}
