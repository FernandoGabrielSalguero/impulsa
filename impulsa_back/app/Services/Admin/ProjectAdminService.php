<?php

namespace App\Services\Admin;

use App\Models\Project;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProjectAdminService
{
    public function __construct(
        private readonly ProjectStructureService $structureService,
    ) {}

    public function list(?string $q, int $perPage = 20): LengthAwarePaginator
    {
        $query = Project::query()
            ->from('projects as p')
            ->leftJoin('user_auth as client', 'client.id', '=', 'p.client_user_id')
            ->join('user_auth as manager', 'manager.id', '=', 'p.manager_user_id')
            ->leftJoin('project_contracts as pc', 'pc.project_id', '=', 'p.id')
            ->select([
                'p.*',
                'client.correo as cliente_correo_login',
                'manager.correo as manager_correo',
                'pc.id as contrato_id',
                'pc.contract_name',
                'pc.is_signed',
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
    public function getDetail(Project $project): array
    {
        $row = $this->findProjectRow((int) $project->id);

        if ($row === null) {
            throw ValidationException::withMessages([
                'project' => ['El proyecto no existe.'],
            ]);
        }

        $phases = $this->structureService->getPhases((int) $project->id);
        $deliverables = $this->structureService->getDeliverables((int) $project->id);
        $contract = $this->getContractRow((int) $project->id);
        $recalculated = $this->structureService->recalculateProject((int) $project->id);

        $row['target_delivery_date'] = $recalculated['target_delivery_date'];
        $row['progress_percent'] = $recalculated['progress_percent'];
        $row['progress_detail'] = $recalculated['progress_detail'];

        return [
            'project' => $row,
            'phases' => $phases,
            'deliverables' => $deliverables,
            'contract' => $contract,
        ];
    }

    /** @param array<string, mixed> $data */
    public function updateProject(Project $project, array $data): array
    {
        if (! $this->structureService->managerExists((int) $data['manager_user_id'])) {
            throw ValidationException::withMessages([
                'manager_user_id' => ['El responsable seleccionado no es válido.'],
            ]);
        }

        $project->update([
            'project_name' => trim((string) $data['project_name']),
            'manager_user_id' => (int) $data['manager_user_id'],
            'summary' => filled($data['summary'] ?? null) ? trim((string) $data['summary']) : null,
            'scope_summary' => filled($data['scope_summary'] ?? null) ? trim((string) $data['scope_summary']) : null,
            'status' => $data['status'],
            'priority' => $data['priority'],
            'start_date' => filled($data['start_date'] ?? null) ? $data['start_date'] : null,
            'client_visible' => (bool) ($data['client_visible'] ?? false),
        ]);

        $this->structureService->recalculateProject((int) $project->id);

        return $this->getDetail($project->fresh());
    }

    /** @return Collection<int, array<string, mixed>> */
    public function listManagers(): Collection
    {
        return DB::table('user_auth as ua')
            ->leftJoin('user_info as ui', 'ui.user_auth_id', '=', 'ua.id')
            ->whereIn('ua.rol', ['impulsa_administrador', 'impulsa_colaborador', 'impulsa_marketing'])
            ->orderByRaw('ui.nombre IS NULL ASC')
            ->orderBy('ui.nombre')
            ->orderBy('ua.correo')
            ->get([
                'ua.id',
                'ua.correo',
                'ui.nombre',
                'ui.apellido',
            ])
            ->map(static function ($user): array {
                $name = trim((string) (($user->nombre ?? '') . ' ' . ($user->apellido ?? '')));

                return [
                    'id' => (int) $user->id,
                    'correo' => $user->correo,
                    'nombre' => $name !== '' ? $name : null,
                    'label' => $name !== '' ? $name . ' (' . $user->correo . ')' : $user->correo,
                ];
            });
    }

    /** @return array<string, mixed>|null */
    public function getContractRow(int $projectId): ?array
    {
        $row = DB::table('project_contracts')
            ->where('project_id', $projectId)
            ->first();

        if ($row === null) {
            return null;
        }

        return (array) $row;
    }

    /** @param array<string, mixed> $data */
    public function saveContract(Project $project, array $data, int $adminUserId): array
    {
        $contractName = trim((string) $data['contract_name']);
        $contractText = trim((string) ($data['contract_text'] ?? ''));
        $contractHtml = nl2br(htmlspecialchars($contractText, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));

        $existing = DB::table('project_contracts')
            ->where('project_id', $project->id)
            ->first();

        if ($existing !== null) {
            DB::table('project_contracts')
                ->where('id', $existing->id)
                ->update([
                    'contract_name' => $contractName,
                    'contract_html' => $contractHtml,
                    'contract_text' => $contractText !== '' ? $contractText : null,
                    'version_number' => (int) $existing->version_number + 1,
                    'updated_by_user_id' => $adminUserId,
                    'updated_at' => now(),
                ]);
        } else {
            DB::table('project_contracts')->insert([
                'project_id' => $project->id,
                'contract_name' => $contractName,
                'contract_html' => $contractHtml,
                'contract_text' => $contractText !== '' ? $contractText : null,
                'version_number' => 1,
                'is_signed' => false,
                'created_by_user_id' => $adminUserId,
                'updated_by_user_id' => $adminUserId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $this->getContractRow((int) $project->id) ?? [];
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
