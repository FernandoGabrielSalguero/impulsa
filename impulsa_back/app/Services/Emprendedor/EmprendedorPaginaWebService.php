<?php

namespace App\Services\Emprendedor;

use App\Http\Resources\EmprendedorWebsiteSubscriptionResource;
use App\Models\UserAuth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EmprendedorPaginaWebService
{
    public function __construct(
        private readonly EmprendedorWebsiteSubscriptionService $websiteSubscriptionService,
    ) {}

    public function overview(UserAuth $user): array
    {
        $solicitudRow = DB::table('landing_page_request')
            ->where('user_auth_id', $user->id)
            ->first();

        $solicitud = $this->mapSolicitud($solicitudRow);
        $proyecto = $this->mapProyecto($user, $solicitudRow);
        $subscription = $this->mapSubscription($user);

        return [
            'solicitud' => $solicitud,
            'proyecto' => $proyecto,
            'subscription' => $subscription,
        ];
    }

    /** @return array<string, mixed> */
    private function mapSolicitud(?object $row): array
    {
        if ($row === null) {
            return [
                'exists' => false,
                'estado' => 'sin_solicitud',
                'completado' => false,
                'nombre_emprendimiento' => null,
                'updated_at' => null,
            ];
        }

        $completado = (int) $row->completado === 1;

        return [
            'exists' => true,
            'estado' => $completado ? 'enviada' : 'borrador',
            'completado' => $completado,
            'nombre_emprendimiento' => (string) $row->nombre_emprendimiento,
            'updated_at' => $row->updated_at,
        ];
    }

    /** @return array<string, mixed>|null */
    private function mapProyecto(UserAuth $user, ?object $solicitudRow): ?array
    {
        if ($solicitudRow === null) {
            return null;
        }

        $project = DB::table('projects')
            ->where('source_type', 'landing_page_request')
            ->where('source_id', $solicitudRow->id)
            ->where('client_user_id', $user->id)
            ->where('client_visible', 1)
            ->first();

        if ($project === null) {
            return null;
        }

        $projectId = (int) $project->id;

        $phases = DB::table('project_phases')
            ->where('project_id', $projectId)
            ->orderBy('phase_order')
            ->get([
                'id',
                'title',
                'description',
                'phase_order',
                'status',
                'due_date',
                'completed_at',
            ])
            ->map(static fn ($phase): array => [
                'id' => (int) $phase->id,
                'title' => (string) $phase->title,
                'description' => $phase->description !== null ? (string) $phase->description : null,
                'phase_order' => (int) $phase->phase_order,
                'status' => (string) $phase->status,
                'due_date' => $phase->due_date,
                'completed_at' => $phase->completed_at,
            ])
            ->all();

        $deliverables = DB::table('project_deliverables')
            ->where('project_id', $projectId)
            ->where('client_visible', 1)
            ->orderBy('id')
            ->get([
                'id',
                'phase_id',
                'title',
                'description',
                'deliverable_type',
                'status',
                'due_date',
                'delivered_at',
            ])
            ->map(static fn ($deliverable): array => [
                'id' => (int) $deliverable->id,
                'phase_id' => $deliverable->phase_id !== null ? (int) $deliverable->phase_id : null,
                'title' => (string) $deliverable->title,
                'description' => $deliverable->description !== null ? (string) $deliverable->description : null,
                'deliverable_type' => (string) $deliverable->deliverable_type,
                'status' => (string) $deliverable->status,
                'due_date' => $deliverable->due_date,
                'delivered_at' => $deliverable->delivered_at,
            ])
            ->all();

        return [
            'id' => $projectId,
            'project_name' => (string) $project->project_name,
            'project_type' => (string) $project->project_type,
            'summary' => $project->summary !== null ? (string) $project->summary : null,
            'status' => (string) $project->status,
            'progress_percent' => (int) $project->progress_percent,
            'target_delivery_date' => $project->target_delivery_date,
            'phases' => $phases,
            'deliverables' => $deliverables,
        ];
    }

    /** @return array<string, mixed>|null */
    private function mapSubscription(UserAuth $user): ?array
    {
        try {
            $subscription = $this->websiteSubscriptionService->show($user);

            return (new EmprendedorWebsiteSubscriptionResource($subscription))->resolve();
        } catch (ValidationException) {
            return null;
        }
    }
}
