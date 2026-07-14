<?php

namespace App\Services\Admin;

use App\Models\Project;
use App\Models\UserAuth;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Support\ProjectLabels;
use Illuminate\Validation\ValidationException;

class ProjectAdminService
{
    private const CLIENT_ROLES = ['impulsa_cliente', 'impulsa_emprendedor'];

    public function __construct(
        private readonly ProjectStructureService $structureService,
        private readonly UserAdminService $userAdminService,
        private readonly ProjectClientNotificationService $clientNotificationService,
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
    public function updateProject(Project $project, array $data, ?int $actorUserId = null): array
    {
        if (! $this->structureService->managerExists((int) $data['manager_user_id'])) {
            throw ValidationException::withMessages([
                'manager_user_id' => ['El responsable seleccionado no es válido.'],
            ]);
        }

        $before = [
            'project_name' => $project->project_name,
            'status' => $project->status,
            'priority' => $project->priority,
            'start_date' => $project->start_date?->format('Y-m-d'),
            'summary' => $project->summary,
            'scope_summary' => $project->scope_summary,
            'client_visible' => (bool) $project->client_visible,
            'progress_percent' => (int) $project->progress_percent,
        ];

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
        $detail = $this->getDetail($project->fresh());
        $changeLines = $this->describeProjectChanges($before, $detail['project']);

        if ($changeLines !== []) {
            $this->clientNotificationService->notify(
                project: $project->fresh(),
                updateTitle: 'Actualización del proyecto',
                updateMessage: 'Actualizamos la información general de tu proyecto.',
                changeLines: $changeLines,
                createdByUserId: $actorUserId,
                progress: [
                    'progress_percent' => (int) ($detail['project']['progress_percent'] ?? 0),
                    'progress_detail' => (string) ($detail['project']['progress_detail'] ?? ''),
                ],
            );
        }

        return $detail;
    }

    public function flushClientNotification(Project $project, ?int $actorUserId = null): ?bool
    {
        return $this->clientNotificationService->flush($project, $actorUserId);
    }

    public function discardClientNotification(Project $project): void
    {
        $this->clientNotificationService->discard($project);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array{detail: array<string, mixed>, client_created: bool, email_sent: bool|null, message: string}
     */
    public function create(array $data): array
    {
        $managerUserId = (int) $data['manager_user_id'];

        if (! $this->structureService->managerExists($managerUserId)) {
            throw ValidationException::withMessages([
                'manager_user_id' => ['El responsable seleccionado no es válido.'],
            ]);
        }

        $clientCreated = false;
        $welcomePassword = null;
        $welcomeUser = null;

        /** @var Project $project */
        $project = DB::transaction(function () use ($data, $managerUserId, &$clientCreated, &$welcomePassword, &$welcomeUser): Project {
            if (isset($data['create_client']) && is_array($data['create_client'])) {
                $clientData = $data['create_client'];

                ['user' => $welcomeUser, 'password' => $welcomePassword] = $this->userAdminService->persistUserAccount([
                    'correo' => $clientData['correo'],
                    'rol' => 'impulsa_cliente',
                    'nombre' => $clientData['nombre'] ?? null,
                    'apellido' => $clientData['apellido'] ?? null,
                    'apodo' => $clientData['apodo'] ?? null,
                    'whatsapp' => $clientData['whatsapp'] ?? null,
                ]);

                $clientUserId = (int) $welcomeUser->id;
                $clientName = $this->resolveClientName($welcomeUser, $clientData);
                $clientEmail = strtolower(trim((string) $clientData['correo']));
                $clientWhatsapp = filled($clientData['whatsapp'] ?? null) ? trim((string) $clientData['whatsapp']) : null;
                $clientCreated = true;
            } else {
                $clientUserId = (int) $data['client_user_id'];

                if (! $this->structureService->clientExists($clientUserId)) {
                    throw ValidationException::withMessages([
                        'client_user_id' => ['El cliente seleccionado no es válido.'],
                    ]);
                }

                $clientUser = UserAuth::query()->with('info')->findOrFail($clientUserId);
                $clientName = $this->resolveClientName($clientUser);
                $clientEmail = strtolower(trim((string) $clientUser->correo));
                $clientWhatsapp = null;
            }

            $project = Project::query()->create([
                'source_type' => 'admin_manual',
                'source_id' => null,
                'project_name' => trim((string) $data['project_name']),
                'project_type' => 'website',
                'client_user_id' => $clientUserId,
                'manager_user_id' => $managerUserId,
                'client_name' => $clientName,
                'client_email' => $clientEmail,
                'client_whatsapp' => $clientWhatsapp,
                'summary' => filled($data['summary'] ?? null) ? trim((string) $data['summary']) : null,
                'scope_summary' => filled($data['scope_summary'] ?? null) ? trim((string) $data['scope_summary']) : null,
                'status' => 'planned',
                'priority' => 'medium',
                'progress_percent' => 0,
                'client_visible' => (bool) ($data['client_visible'] ?? true),
            ]);

            $this->structureService->seedDefaultStructure(
                (int) $project->id,
                $managerUserId,
                'El proyecto fue creado manualmente desde el panel de administración.',
            );

            return $project;
        });

        $emailSent = null;

        if ($clientCreated && $welcomeUser !== null && $welcomePassword !== null) {
            $emailSent = $this->userAdminService->sendClienteWelcomeEmail($welcomeUser, $welcomePassword);
        }

        $message = 'Proyecto creado correctamente.';

        if ($clientCreated) {
            $message = $emailSent
                ? 'Proyecto creado y credenciales enviadas por correo.'
                : 'Proyecto creado, pero falló el envío del correo con credenciales.';
        }

        return [
            'detail' => $this->getDetail($project),
            'client_created' => $clientCreated,
            'email_sent' => $emailSent,
            'message' => $message,
        ];
    }

    /** @return Collection<int, array<string, mixed>> */
    public function listClients(?string $q, int $limit = 20): Collection
    {
        $search = trim((string) $q);

        if (mb_strlen($search) < 2) {
            return collect();
        }

        $like = '%' . $search . '%';

        return DB::table('user_auth as ua')
            ->leftJoin('user_info as ui', 'ui.user_auth_id', '=', 'ua.id')
            ->whereIn('ua.rol', self::CLIENT_ROLES)
            ->where(function ($builder) use ($like): void {
                $builder
                    ->where('ua.correo', 'like', $like)
                    ->orWhere('ui.nombre', 'like', $like)
                    ->orWhere('ui.apellido', 'like', $like)
                    ->orWhere('ui.apodo', 'like', $like)
                    ->orWhereRaw("CONCAT_WS(' ', ui.nombre, ui.apellido) LIKE ?", [$like]);
            })
            ->orderBy('ui.nombre')
            ->orderBy('ua.correo')
            ->limit(max(1, min($limit, 50)))
            ->get([
                'ua.id',
                'ua.correo',
                'ua.rol',
                'ui.nombre',
                'ui.apellido',
                'ui.apodo',
            ])
            ->map(static function ($user): array {
                $name = trim((string) (($user->nombre ?? '') . ' ' . ($user->apellido ?? '')));

                if ($name === '') {
                    $name = trim((string) ($user->apodo ?? ''));
                }

                return [
                    'id' => (int) $user->id,
                    'correo' => $user->correo,
                    'rol' => $user->rol,
                    'nombre' => $name !== '' ? $name : null,
                    'label' => $name !== '' ? $name . ' (' . $user->correo . ')' : $user->correo,
                ];
            });
    }

    /** @param  array<string, mixed>  $clientData */
    private function resolveClientName(UserAuth $user, array $clientData = []): string
    {
        $name = trim(implode(' ', array_filter([
            trim((string) ($clientData['nombre'] ?? '')),
            trim((string) ($clientData['apellido'] ?? '')),
        ])));

        if ($name !== '') {
            return $name;
        }

        $name = trim((string) (($user->info?->nombre ?? '') . ' ' . ($user->info?->apellido ?? '')));

        if ($name !== '') {
            return $name;
        }

        $apodo = trim((string) ($user->info?->apodo ?? ''));

        if ($apodo !== '') {
            return $apodo;
        }

        return 'Cliente';
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

        $isNew = $existing === null;

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

        $this->clientNotificationService->notify(
            project: $project,
            updateTitle: $isNew ? 'Contrato disponible' : 'Contrato actualizado',
            updateMessage: $isNew
                ? 'Ya podés revisar el contrato de tu proyecto desde el panel.'
                : 'Actualizamos el contrato de tu proyecto.',
            changeLines: [
                'Contrato: ' . $contractName,
            ],
            createdByUserId: $adminUserId,
            progress: $this->structureService->recalculateProject((int) $project->id),
        );

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

    /**
     * @param  array<string, mixed>  $before
     * @param  array<string, mixed>  $after
     * @return list<string>
     */
    private function describeProjectChanges(array $before, array $after): array
    {
        $lines = [];

        if (($before['project_name'] ?? '') !== ($after['project_name'] ?? '')) {
            $lines[] = 'Nombre: ' . ($before['project_name'] ?? '—') . ' → ' . ($after['project_name'] ?? '—');
        }

        if (($before['status'] ?? '') !== ($after['status'] ?? '')) {
            $lines[] = 'Estado: ' . ProjectLabels::statusLabel($before['status'] ?? null)
                . ' → ' . ProjectLabels::statusLabel($after['status'] ?? null);
        }

        if (($before['priority'] ?? '') !== ($after['priority'] ?? '')) {
            $lines[] = 'Prioridad: ' . ProjectLabels::priorityLabel($before['priority'] ?? null)
                . ' → ' . ProjectLabels::priorityLabel($after['priority'] ?? null);
        }

        if (($before['start_date'] ?? null) !== ($after['start_date'] ?? null)) {
            $lines[] = 'Fecha de inicio: ' . ($before['start_date'] ?? 'Sin fecha') . ' → ' . ($after['start_date'] ?? 'Sin fecha');
        }

        if (($before['summary'] ?? null) !== ($after['summary'] ?? null)) {
            $lines[] = 'Se actualizó el resumen del proyecto.';
        }

        if (($before['scope_summary'] ?? null) !== ($after['scope_summary'] ?? null)) {
            $lines[] = 'Se actualizó el alcance del proyecto.';
        }

        if ((bool) ($before['client_visible'] ?? false) !== (bool) ($after['client_visible'] ?? false)) {
            $lines[] = (bool) ($after['client_visible'] ?? false)
                ? 'El proyecto quedó visible para vos en el panel.'
                : 'El proyecto dejó de estar visible en el panel.';
        }

        if ((int) ($before['progress_percent'] ?? 0) !== (int) ($after['progress_percent'] ?? 0)) {
            $lines[] = 'Avance: ' . (int) ($before['progress_percent'] ?? 0) . '% → ' . (int) ($after['progress_percent'] ?? 0) . '%';
        }

        return $lines;
    }
}
