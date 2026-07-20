<?php

namespace App\Services\Cliente;

use App\Models\MarketingPlanSubscription;
use App\Models\UserAuth;
use App\Support\EmprendedorIntegrationAccess;
use Illuminate\Support\Facades\DB;

class ClienteDashboardService
{
    public function __construct(
        private readonly EmprendedorIntegrationAccess $integrationAccess,
    ) {}

    public function stats(UserAuth $user): array
    {
        $integration = $this->integrationAccess->integrationForUser($user);
        $integrationId = $integration !== null ? (int) $integration->id : null;

        $marketingSubscriptions = MarketingPlanSubscription::query()
            ->where('client_user_id', $user->id);

        return [
            'resumen' => $this->resumen($user),
            'active_marketing_subscriptions' => (clone $marketingSubscriptions)->where('status', 'active')->count(),
            'pending_marketing_requests' => (clone $marketingSubscriptions)->whereIn('status', ['requested', 'pending_payment'])->count(),
            'has_api_integration' => $integrationId !== null,
            'api_integration' => $integration !== null ? [
                'id' => (int) $integration->id,
                'project_name' => (string) $integration->project_name,
                'allowed_domain' => (string) $integration->allowed_domain,
                'status' => (string) $integration->status,
            ] : null,
            'products_count' => $integrationId !== null
                ? (int) DB::table('api_products')->where('api_integration_id', $integrationId)->count()
                : 0,
            'chatbot_status' => $this->chatbotStatus($integrationId),
            'proyectos' => $this->proyectos($user),
            'fases' => $this->fases($user),
            'objetivos' => $this->objetivos($user),
            'actualizaciones' => $this->actualizaciones($user),
            'contratos' => $this->contratos($user),
        ];
    }

    /** @return array<string, int> */
    private function resumen(UserAuth $user): array
    {
        return [
            'proyectos_total' => (int) DB::table('projects')
                ->where('client_user_id', $user->id)
                ->where('client_visible', 1)
                ->count(),
            'proyectos_activos' => (int) DB::table('projects')
                ->where('client_user_id', $user->id)
                ->where('client_visible', 1)
                ->whereIn('status', ['planned', 'in_progress', 'in_review'])
                ->count(),
            'entregables_pendientes' => (int) DB::table('project_deliverables as pd')
                ->join('projects as p', 'p.id', '=', 'pd.project_id')
                ->where('p.client_user_id', $user->id)
                ->where('p.client_visible', 1)
                ->where('pd.client_visible', 1)
                ->whereIn('pd.status', ['pending', 'in_progress', 'ready_for_review'])
                ->count(),
            'contratos_pendientes' => (int) DB::table('project_contracts as pc')
                ->join('projects as p', 'p.id', '=', 'pc.project_id')
                ->where('p.client_user_id', $user->id)
                ->where('p.client_visible', 1)
                ->where('pc.is_signed', 0)
                ->count(),
        ];
    }

    private function chatbotStatus(?int $integrationId): ?string
    {
        if ($integrationId === null) {
            return null;
        }

        $chatbot = DB::table('chatbots')
            ->where('api_integration_id', $integrationId)
            ->first(['status']);

        return $chatbot !== null ? (string) $chatbot->status : null;
    }

    /** @return list<array<string, mixed>> */
    private function proyectos(UserAuth $user): array
    {
        return DB::table('projects as p')
            ->join('user_auth as manager', 'manager.id', '=', 'p.manager_user_id')
            ->leftJoin('user_info as manager_info', 'manager_info.user_auth_id', '=', 'manager.id')
            ->where('p.client_user_id', $user->id)
            ->where('p.client_visible', 1)
            ->orderByDesc('p.updated_at')
            ->get([
                'p.id',
                'p.project_name',
                'p.project_type',
                'p.client_name',
                'p.summary',
                'p.scope_summary',
                'p.status',
                'p.priority',
                'p.start_date',
                'p.target_delivery_date',
                'p.progress_percent',
                'p.created_at',
                'p.updated_at',
                'manager.correo as manager_correo',
                'manager_info.nombre as manager_nombre',
                'manager_info.apellido as manager_apellido',
                DB::raw('(SELECT COUNT(*) FROM project_phases pp WHERE pp.project_id = p.id) as fases_total'),
                DB::raw('(SELECT COUNT(*) FROM project_deliverables pd WHERE pd.project_id = p.id AND pd.client_visible = 1) as entregables_total'),
                DB::raw('(SELECT COUNT(*) FROM project_updates pu WHERE pu.project_id = p.id AND pu.visible_to_client = 1) as actualizaciones_total'),
            ])
            ->map(static fn ($row): array => [
                'id' => (int) $row->id,
                'project_name' => (string) $row->project_name,
                'project_type' => (string) $row->project_type,
                'client_name' => $row->client_name !== null ? (string) $row->client_name : null,
                'summary' => $row->summary !== null ? (string) $row->summary : null,
                'scope_summary' => $row->scope_summary !== null ? (string) $row->scope_summary : null,
                'status' => (string) $row->status,
                'priority' => (string) $row->priority,
                'start_date' => $row->start_date,
                'target_delivery_date' => $row->target_delivery_date,
                'progress_percent' => (int) $row->progress_percent,
                'created_at' => $row->created_at,
                'updated_at' => $row->updated_at,
                'manager_correo' => (string) $row->manager_correo,
                'manager_nombre' => $row->manager_nombre !== null ? (string) $row->manager_nombre : null,
                'manager_apellido' => $row->manager_apellido !== null ? (string) $row->manager_apellido : null,
                'fases_total' => (int) $row->fases_total,
                'entregables_total' => (int) $row->entregables_total,
                'actualizaciones_total' => (int) $row->actualizaciones_total,
            ])
            ->all();
    }

    /** @return array<int, list<array<string, mixed>>> */
    private function fases(UserAuth $user): array
    {
        $rows = DB::table('project_phases as pp')
            ->join('projects as p', 'p.id', '=', 'pp.project_id')
            ->where('p.client_user_id', $user->id)
            ->where('p.client_visible', 1)
            ->orderBy('pp.project_id')
            ->orderBy('pp.phase_order')
            ->orderBy('pp.id')
            ->get([
                'pp.id',
                'pp.project_id',
                'pp.title',
                'pp.description',
                'pp.duration_days',
                'pp.phase_order',
                'pp.status',
                'pp.due_date',
                'pp.completed_at',
            ]);

        return $this->groupByProjectId($rows);
    }

    /** @return array<int, list<array<string, mixed>>> */
    private function objetivos(UserAuth $user): array
    {
        $rows = DB::table('project_deliverables as pd')
            ->join('projects as p', 'p.id', '=', 'pd.project_id')
            ->join('project_phases as pp', function ($join): void {
                $join->on('pp.id', '=', 'pd.phase_id')
                    ->on('pp.project_id', '=', 'pd.project_id');
            })
            ->where('p.client_user_id', $user->id)
            ->where('p.client_visible', 1)
            ->where('pd.client_visible', 1)
            ->orderBy('pd.project_id')
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
                'pd.defcon',
                'pd.due_date',
                'pd.delivered_at',
                'pp.title as phase_title',
            ]);

        return $this->groupByProjectId($rows);
    }

    /** @return list<array<string, mixed>> */
    private function actualizaciones(UserAuth $user): array
    {
        return DB::table('project_updates as pu')
            ->join('projects as p', 'p.id', '=', 'pu.project_id')
            ->leftJoin('project_phases as pp', 'pp.id', '=', 'pu.phase_id')
            ->where('p.client_user_id', $user->id)
            ->where('p.client_visible', 1)
            ->where('pu.visible_to_client', 1)
            ->orderByDesc('pu.created_at')
            ->limit(10)
            ->get([
                'pu.id',
                'pu.title',
                'pu.message',
                'pu.progress_delta',
                'pu.created_at',
                'p.project_name',
                'pp.title as phase_title',
            ])
            ->map(static fn ($row): array => [
                'id' => (int) $row->id,
                'title' => (string) $row->title,
                'message' => $row->message !== null ? (string) $row->message : null,
                'progress_delta' => $row->progress_delta !== null ? (int) $row->progress_delta : null,
                'created_at' => $row->created_at,
                'project_name' => (string) $row->project_name,
                'phase_title' => $row->phase_title !== null ? (string) $row->phase_title : null,
            ])
            ->all();
    }

    /** @return list<array<string, mixed>> */
    private function contratos(UserAuth $user): array
    {
        return DB::table('project_contracts as pc')
            ->join('projects as p', 'p.id', '=', 'pc.project_id')
            ->where('p.client_user_id', $user->id)
            ->where('p.client_visible', 1)
            ->orderByDesc('pc.updated_at')
            ->get([
                'pc.id',
                'pc.project_id',
                'pc.contract_name',
                'pc.version_number',
                'pc.is_signed',
                'pc.signed_at',
                'pc.signer_full_name',
                'p.project_name',
            ])
            ->map(static fn ($row): array => [
                'id' => (int) $row->id,
                'project_id' => (int) $row->project_id,
                'contract_name' => (string) $row->contract_name,
                'project_name' => (string) $row->project_name,
                'version_number' => (int) $row->version_number,
                'is_signed' => (int) $row->is_signed === 1,
                'signed_at' => $row->signed_at,
                'signer_full_name' => $row->signer_full_name !== null ? (string) $row->signer_full_name : null,
            ])
            ->all();
    }

    /** @param \Illuminate\Support\Collection<int, object> $rows */
    private function groupByProjectId($rows): array
    {
        $grouped = [];

        foreach ($rows as $row) {
            $projectId = (int) $row->project_id;
            $grouped[$projectId][] = (array) $row;
        }

        return $grouped;
    }
}
