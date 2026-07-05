<?php

namespace App\Services\Emprendedor;

use App\Models\MarketingPlanSubscription;
use App\Models\Project;
use App\Models\UserAuth;
use App\Support\EmprendedorIntegrationAccess;
use Illuminate\Support\Facades\DB;

class EmprendedorDashboardService
{
    public function __construct(
        private readonly EmprendedorIntegrationAccess $integrationAccess,
    ) {}

    public function stats(UserAuth $user): array
    {
        $integration = $this->integrationAccess->integrationForUser($user);
        $integrationId = $integration !== null ? (int) $integration->id : null;

        $activeProjects = Project::query()
            ->where('client_user_id', $user->id)
            ->whereIn('status', ['planned', 'in_progress', 'in_review'])
            ->count();

        $marketingSubscriptions = MarketingPlanSubscription::query()
            ->where('entrepreneur_user_id', $user->id);

        $activeMarketing = (clone $marketingSubscriptions)->where('status', 'active')->count();
        $pendingMarketing = (clone $marketingSubscriptions)->whereIn('status', ['requested', 'pending_payment'])->count();

        $productsCount = 0;
        $chatbotStatus = null;
        $websiteSubscriptionStatus = null;

        if ($integrationId !== null) {
            $productsCount = (int) DB::table('api_products')
                ->where('api_integration_id', $integrationId)
                ->count();

            $chatbot = DB::table('chatbots')
                ->where('api_integration_id', $integrationId)
                ->first();

            if ($chatbot !== null) {
                $chatbotStatus = (string) $chatbot->status;
            }

            $websiteSubscription = DB::table('website_subscriptions')
                ->where('api_integration_id', $integrationId)
                ->first();

            if ($websiteSubscription !== null) {
                $websiteSubscriptionStatus = (string) $websiteSubscription->status;
            }
        }

        return [
            'active_projects' => $activeProjects,
            'active_marketing_subscriptions' => $activeMarketing,
            'pending_marketing_requests' => $pendingMarketing,
            'has_api_integration' => $integrationId !== null,
            'api_integration' => $integration !== null ? [
                'id' => (int) $integration->id,
                'project_name' => (string) $integration->project_name,
                'allowed_domain' => (string) $integration->allowed_domain,
                'status' => (string) $integration->status,
            ] : null,
            'products_count' => $productsCount,
            'chatbot_status' => $chatbotStatus,
            'website_subscription_status' => $websiteSubscriptionStatus,
            'definicion' => $this->definicionSummary($user),
            'proyectos' => $this->proyectos($user),
            'contratos' => $this->contratos($user),
            'pagina_web_solicitud' => $this->paginaWebSolicitud($user),
        ];
    }

    /** @return array<string, array<string, mixed>> */
    private function definicionSummary(UserAuth $user): array
    {
        return [
            'mision' => $this->definicionModulo('emprendedor_mision', 'mision_estructura', $user->id),
            'vision' => $this->definicionModulo('emprendedor_vision', 'vision_estructura', $user->id),
            'buyer_persona' => $this->definicionModulo('emprendedor_buyer_persona', 'buyer_persona_estructura', $user->id),
        ];
    }

    /** @return array{resultado: string, completado: bool} */
    private function definicionModulo(string $table, string $estructuraColumn, int $userId): array
    {
        $row = DB::table($table)
            ->where('user_auth_id', $userId)
            ->first([$estructuraColumn, 'completado']);

        return [
            'resultado' => trim((string) ($row?->$estructuraColumn ?? '')),
            'completado' => (int) ($row?->completado ?? 0) === 1,
        ];
    }

    /** @return list<array<string, mixed>> */
    private function proyectos(UserAuth $user): array
    {
        return DB::table('projects as p')
            ->where('p.client_user_id', $user->id)
            ->where('p.client_visible', 1)
            ->orderByDesc('p.updated_at')
            ->limit(8)
            ->get([
                'p.id',
                'p.project_name',
                'p.project_type',
                'p.summary',
                'p.scope_summary',
                'p.status',
                'p.progress_percent',
                'p.target_delivery_date',
                DB::raw('(SELECT COUNT(*) FROM project_phases pp WHERE pp.project_id = p.id) as fases_total'),
                DB::raw('(SELECT COUNT(*) FROM project_deliverables pd WHERE pd.project_id = p.id AND pd.client_visible = 1) as entregables_total'),
            ])
            ->map(static fn ($row): array => [
                'id' => (int) $row->id,
                'project_name' => (string) $row->project_name,
                'project_type' => (string) $row->project_type,
                'summary' => $row->summary !== null ? (string) $row->summary : null,
                'scope_summary' => $row->scope_summary !== null ? (string) $row->scope_summary : null,
                'status' => (string) $row->status,
                'progress_percent' => (int) $row->progress_percent,
                'target_delivery_date' => $row->target_delivery_date,
                'fases_total' => (int) $row->fases_total,
                'entregables_total' => (int) $row->entregables_total,
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
            ->limit(10)
            ->get([
                'pc.id',
                'pc.contract_name',
                'pc.version_number',
                'pc.is_signed',
                'pc.signed_at',
                'p.project_name',
            ])
            ->map(static fn ($row): array => [
                'id' => (int) $row->id,
                'contract_name' => (string) $row->contract_name,
                'project_name' => (string) $row->project_name,
                'version_number' => (int) $row->version_number,
                'is_signed' => (int) $row->is_signed === 1,
                'signed_at' => $row->signed_at,
            ])
            ->all();
    }

    /** @return array<string, mixed>|null */
    private function paginaWebSolicitud(UserAuth $user): ?array
    {
        $row = DB::table('landing_page_request')
            ->where('user_auth_id', $user->id)
            ->first(['completado', 'nombre_emprendimiento', 'updated_at']);

        if ($row === null) {
            return null;
        }

        return [
            'completado' => (int) $row->completado === 1,
            'nombre_emprendimiento' => (string) $row->nombre_emprendimiento,
            'updated_at' => $row->updated_at,
        ];
    }
}
