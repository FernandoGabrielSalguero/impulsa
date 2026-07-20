<?php

namespace App\Services\Colaborador;

use App\Models\Project;
use App\Services\Admin\ProjectStructureService;
use App\Support\ProjectLabels;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ColaboradorProjectService
{
    public function __construct(
        private readonly ProjectStructureService $structureService,
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

        return [
            'project' => $row,
            'phases' => $this->structureService->getPhases($projectId),
            'deliverables' => $this->structureService->getDeliverables($projectId),
        ];
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

        DB::table('projects')
            ->where('id', $projectId)
            ->update([
                'status' => $status,
                'updated_at' => now(),
            ]);

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

        $updated = DB::table('project_phases')
            ->where('id', $phaseId)
            ->where('project_id', $projectId)
            ->update([
                'status' => $status,
                'completed_at' => $status === 'done' ? now() : null,
                'updated_at' => now(),
            ]);

        if ($updated === 0) {
            throw ValidationException::withMessages([
                'phase' => ['La fase seleccionada no pertenece a este proyecto.'],
            ]);
        }

        $this->structureService->recalculateProject($projectId);

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

        $updated = DB::table('project_deliverables')
            ->where('id', $deliverableId)
            ->where('project_id', $projectId)
            ->update([
                'status' => $status,
                'delivered_at' => $status === 'delivered' ? now() : null,
                'updated_at' => now(),
            ]);

        if ($updated === 0) {
            throw ValidationException::withMessages([
                'deliverable' => ['El objetivo seleccionado no pertenece a este proyecto.'],
            ]);
        }

        $this->structureService->recalculateProject($projectId);

        return $this->getDetailForUser($userAuthId, $projectId);
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
