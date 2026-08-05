<?php

namespace App\Services\Colaborador;

use App\Models\Project;
use App\Services\Admin\ProjectClientNotificationService;
use App\Services\Admin\ProjectStructureService;
use App\Services\Colaborador\DeliverableCommentService;
use App\Services\Notifications\NotificationService;
use App\Services\Profile\UserAvatarStorageService;
use App\Services\Projects\ProjectAttachmentService;
use App\Support\ProjectLabels;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ColaboradorProjectService
{
    public function __construct(
        private readonly ProjectStructureService $structureService,
        private readonly NotificationService $notificationService,
        private readonly ProjectClientNotificationService $clientNotificationService,
        private readonly UserAvatarStorageService $avatarStorage,
        private readonly DeliverableCommentService $commentService,
        private readonly ProjectAttachmentService $attachmentService,
    ) {}

    public function listForUser(int $userAuthId, ?string $q, int $perPage = 20): LengthAwarePaginator
    {
        $query = Project::query()
            ->from('projects as p')
            ->join('project_collaborators as pc', function ($join) use ($userAuthId): void {
                $join->on('pc.project_id', '=', 'p.id')
                    ->where('pc.user_auth_id', '=', $userAuthId);
            })
            ->leftJoin('user_auth as client', 'client.id', '=', 'p.client_user_id')
            ->join('user_auth as manager', 'manager.id', '=', 'p.manager_user_id')
            ->select([
                'p.*',
                'client.correo as cliente_correo_login',
                'manager.correo as manager_correo',
            ])
            ->selectSub(
                DB::table('project_phases')->selectRaw('COUNT(*)')->whereColumn('project_id', 'p.id'),
                'fases_total'
            )
            ->selectSub(
                DB::table('project_deliverables')->selectRaw('COUNT(*)')->whereColumn('project_id', 'p.id'),
                'objetivos_total'
            )
            ->orderByRaw("CASE WHEN p.status = 'cancelled' THEN 1 ELSE 0 END ASC")
            ->orderByDesc('p.updated_at')
            ->orderByDesc('p.id');

        $search = trim((string) $q);

        if ($search !== '') {
            $like = '%' . $search . '%';
            $query->where(function ($builder) use ($like): void {
                $builder
                    ->where('p.project_name', 'like', $like)
                    ->orWhere('p.client_name', 'like', $like)
                    ->orWhere('p.client_email', 'like', $like)
                    ->orWhere('manager.correo', 'like', $like)
                    ->orWhereRaw('CAST(p.id AS CHAR) LIKE ?', [$like]);
            });
        }

        return $query->paginate($perPage)->withQueryString();
    }

    /** @return array<string, mixed> */
    public function getDetailForUser(int $userAuthId, int $projectId): array
    {
        $this->assertAssigned($userAuthId, $projectId);

        $row = $this->findProjectRow($projectId);

        if ($row === null) {
            throw new NotFoundHttpException('El proyecto no existe.');
        }

        $recalculated = $this->structureService->recalculateProject($projectId);
        $row['target_delivery_date'] = $recalculated['target_delivery_date'];
        $row['progress_percent'] = $recalculated['progress_percent'];
        $row['progress_detail'] = $recalculated['progress_detail'];

        $phases = $this->structureService->getPhases($projectId);
        $deliverables = $this->commentService->attachUnreadCounts(
            $this->structureService->getDeliverables($projectId),
            $userAuthId,
        );
        [$phases, $deliverables] = $this->attachmentService->attachToDetail(
            $projectId,
            $phases,
            $deliverables,
            $userAuthId,
        );

        return [
            'project' => $row,
            'phases' => $phases,
            'deliverables' => $deliverables,
            'collaborators' => $this->listProjectCollaborators($projectId),
        ];
    }

    /** @return list<array<string, mixed>> */
    private function listProjectCollaborators(int $projectId): array
    {
        $hasAvatarPath = Schema::hasColumn('user_info', 'avatar_path');
        $hasContacto = Schema::hasTable('user_contacto');

        $query = DB::table('project_collaborators as pc')
            ->join('user_auth as ua', 'ua.id', '=', 'pc.user_auth_id')
            ->leftJoin('user_info as ui', 'ui.user_auth_id', '=', 'ua.id')
            ->where('pc.project_id', $projectId)
            ->orderByRaw('ui.nombre IS NULL ASC')
            ->orderBy('ui.nombre')
            ->orderBy('ua.correo');

        if ($hasContacto) {
            $query->leftJoin('user_contacto as uc', 'uc.user_auth_id', '=', 'ua.id');
        }

        $columns = [
            'ua.id',
            'ua.correo',
            'ui.nombre',
            'ui.apellido',
        ];

        if ($hasAvatarPath) {
            $columns[] = 'ui.avatar_path';
        }

        if ($hasContacto) {
            $columns[] = 'uc.whatsapp';
            $columns[] = 'uc.correo as correo_contacto';
        }

        return $query
            ->get($columns)
            ->map(function ($user) use ($hasAvatarPath, $hasContacto): array {
                $name = trim((string) (($user->nombre ?? '') . ' ' . ($user->apellido ?? '')));
                $avatarPath = $hasAvatarPath ? trim((string) ($user->avatar_path ?? '')) : '';
                $correoContacto = $hasContacto
                    ? (($user->correo_contacto ?? null) ?: $user->correo)
                    : $user->correo;
                $whatsapp = $hasContacto ? ($user->whatsapp ?: null) : null;

                return [
                    'id' => (int) $user->id,
                    'correo' => $user->correo,
                    'correo_contacto' => $correoContacto,
                    'nombre' => $name !== '' ? $name : null,
                    'whatsapp' => $whatsapp,
                    'has_avatar' => $avatarPath !== '' && $this->avatarStorage->isManagedPath($avatarPath),
                ];
            })
            ->all();
    }

    /** @return array<string, mixed> */
    public function updateProjectStatus(int $userAuthId, int $projectId, string $status): array
    {
        $this->assertAssigned($userAuthId, $projectId);

        if (! in_array($status, ProjectLabels::statuses(), true)) {
            throw ValidationException::withMessages([
                'status' => ['El estado del proyecto no es válido.'],
            ]);
        }

        $before = (string) (DB::table('projects')->where('id', $projectId)->value('status') ?? '');

        DB::table('projects')
            ->where('id', $projectId)
            ->update([
                'status' => $status,
                'updated_at' => now(),
            ]);

        if ($before !== $status) {
            $statusLabel = ProjectLabels::statusLabel($status);
            $this->notificationService->notifyProjectStatusChanged(
                projectId: $projectId,
                entityLabel: 'el proyecto',
                statusLabel: $statusLabel,
                actorUserId: $userAuthId,
                payload: ['entity' => 'project'],
            );

            $this->notifyClientStatusChange(
                $projectId,
                $userAuthId,
                'Estado del proyecto actualizado',
                'Actualizamos el estado general de tu proyecto.',
                ['Estado: ' . ProjectLabels::statusLabel($before) . ' → ' . $statusLabel],
            );
        }

        return $this->getDetailForUser($userAuthId, $projectId);
    }

    /** @return array<string, mixed> */
    public function updatePhaseStatus(int $userAuthId, int $projectId, int $phaseId, string $status): array
    {
        $this->assertAssigned($userAuthId, $projectId);

        if (! in_array($status, ProjectLabels::phaseStatuses(), true)) {
            throw ValidationException::withMessages([
                'status' => ['El estado de la fase no es válido.'],
            ]);
        }

        $phase = DB::table('project_phases')
            ->where('id', $phaseId)
            ->where('project_id', $projectId)
            ->first(['id', 'title', 'status']);

        if ($phase === null) {
            throw ValidationException::withMessages([
                'phase' => ['La fase seleccionada no pertenece a este proyecto.'],
            ]);
        }

        $before = (string) $phase->status;

        DB::table('project_phases')
            ->where('id', $phaseId)
            ->where('project_id', $projectId)
            ->update([
                'status' => $status,
                'completed_at' => $status === 'done' ? now() : null,
                'updated_at' => now(),
            ]);

        $progress = $this->structureService->recalculateProject($projectId);

        if ($before !== $status) {
            $statusLabel = ProjectLabels::phaseStatusLabel($status);
            $phaseTitle = (string) $phase->title;

            $this->notificationService->notifyProjectStatusChanged(
                projectId: $projectId,
                entityLabel: 'la fase "'.$phaseTitle.'"',
                statusLabel: $statusLabel,
                actorUserId: $userAuthId,
                payload: [
                    'entity' => 'phase',
                    'phase_id' => $phaseId,
                ],
            );

            $this->notifyClientStatusChange(
                $projectId,
                $userAuthId,
                'Fase actualizada',
                'Actualizamos el estado de una etapa de tu proyecto.',
                [
                    'Fase: '.$phaseTitle,
                    'Estado: '.ProjectLabels::phaseStatusLabel($before).' → '.$statusLabel,
                ],
                $phaseId,
                $progress,
            );
        }

        return $this->getDetailForUser($userAuthId, $projectId);
    }

    /** @return array<string, mixed> */
    public function updateDeliverableStatus(int $userAuthId, int $projectId, int $deliverableId, string $status): array
    {
        $this->assertAssigned($userAuthId, $projectId);

        if (! in_array($status, ProjectLabels::deliverableStatuses(), true)) {
            throw ValidationException::withMessages([
                'status' => ['El estado del objetivo no es válido.'],
            ]);
        }

        $deliverable = DB::table('project_deliverables')
            ->where('id', $deliverableId)
            ->where('project_id', $projectId)
            ->first(['id', 'title', 'status', 'phase_id']);

        if ($deliverable === null) {
            throw ValidationException::withMessages([
                'deliverable' => ['El objetivo seleccionado no pertenece a este proyecto.'],
            ]);
        }

        $before = (string) $deliverable->status;

        DB::table('project_deliverables')
            ->where('id', $deliverableId)
            ->where('project_id', $projectId)
            ->update([
                'status' => $status,
                'delivered_at' => $status === 'delivered' ? now() : null,
                'updated_at' => now(),
            ]);

        $progress = $this->structureService->recalculateProject($projectId);

        if ($before !== $status) {
            $statusLabel = ProjectLabels::deliverableStatusLabel($status);
            $deliverableTitle = (string) $deliverable->title;

            $this->notificationService->notifyProjectStatusChanged(
                projectId: $projectId,
                entityLabel: 'el objetivo "'.$deliverableTitle.'"',
                statusLabel: $statusLabel,
                actorUserId: $userAuthId,
                payload: [
                    'entity' => 'deliverable',
                    'deliverable_id' => $deliverableId,
                ],
            );

            $this->notifyClientStatusChange(
                $projectId,
                $userAuthId,
                'Objetivo actualizado',
                'Actualizamos el estado de un objetivo de tu proyecto.',
                [
                    'Objetivo: '.$deliverableTitle,
                    'Estado: '.ProjectLabels::deliverableStatusLabel($before).' → '.$statusLabel,
                ],
                $deliverable->phase_id !== null ? (int) $deliverable->phase_id : null,
                $progress,
            );
        }

        return $this->getDetailForUser($userAuthId, $projectId);
    }

    /**
     * @param  list<string>  $changeLines
     * @param  array{target_delivery_date?: ?string, progress_percent?: int, progress_detail?: string}|null  $progress
     */
    private function notifyClientStatusChange(
        int $projectId,
        int $actorUserId,
        string $updateTitle,
        string $updateMessage,
        array $changeLines,
        ?int $phaseId = null,
        ?array $progress = null,
    ): void {
        $project = Project::query()->find($projectId);

        if ($project === null) {
            return;
        }

        $this->clientNotificationService->notify(
            project: $project,
            updateTitle: $updateTitle,
            updateMessage: $updateMessage,
            changeLines: $changeLines,
            createdByUserId: $actorUserId,
            phaseId: $phaseId,
            progress: $progress,
        );
    }

    private function assertAssigned(int $userAuthId, int $projectId): void
    {
        $assigned = DB::table('project_collaborators')
            ->where('project_id', $projectId)
            ->where('user_auth_id', $userAuthId)
            ->exists();

        if (! $assigned) {
            throw new NotFoundHttpException('El proyecto no está asignado o no existe.');
        }
    }

    /** @return array<string, mixed>|null */
    private function findProjectRow(int $projectId): ?array
    {
        $row = DB::table('projects as p')
            ->leftJoin('user_auth as client', 'client.id', '=', 'p.client_user_id')
            ->join('user_auth as manager', 'manager.id', '=', 'p.manager_user_id')
            ->where('p.id', $projectId)
            ->first([
                'p.id',
                'p.source_type',
                'p.source_id',
                'p.project_name',
                'p.project_type',
                'p.client_user_id',
                'p.manager_user_id',
                'p.client_name',
                'p.client_email',
                'p.client_whatsapp',
                'p.summary',
                'p.scope_summary',
                'p.status',
                'p.priority',
                'p.start_date',
                'p.target_delivery_date',
                'p.actual_delivery_date',
                'p.progress_percent',
                'p.client_visible',
                'p.created_at',
                'p.updated_at',
                'client.correo as cliente_correo_login',
                'manager.correo as manager_correo',
            ]);

        return $row !== null ? (array) $row : null;
    }
}
