<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDeliverableCommentRequest;
use App\Http\Requests\Admin\StoreProjectDeliverableRequest;
use App\Http\Requests\Admin\StoreProjectPhaseRequest;
use App\Http\Requests\Admin\StoreProjectRequest;
use App\Http\Requests\Admin\UpdateProjectContractRequest;
use App\Http\Requests\Admin\UpdateProjectRequest;
use App\Http\Resources\AdminProjectCollection;
use App\Http\Resources\AdminProjectDetailResource;
use App\Models\Project;
use App\Services\Admin\ProjectAdminService;
use App\Services\Admin\ProjectStructureService;
use App\Services\Colaborador\DeliverableCommentService;
use App\Support\ProjectLabels;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct(
        private readonly ProjectAdminService $projectAdminService,
        private readonly ProjectStructureService $structureService,
        private readonly DeliverableCommentService $commentService,
    ) {}

    public function index(Request $request): AdminProjectCollection
    {
        $perPage = max(1, min((int) $request->integer('per_page', 20), 100));
        $projects = $this->projectAdminService->list($request->query('q'), $perPage);

        return new AdminProjectCollection($projects);
    }

    public function managers(): JsonResponse
    {
        return response()->json([
            'data' => $this->projectAdminService->listManagers(),
        ]);
    }

    public function collaborators(): JsonResponse
    {
        return response()->json([
            'data' => $this->projectAdminService->listCollaboratorCandidates(),
        ]);
    }

    public function clients(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $this->projectAdminService->listClients(
                $request->query('q'),
                max(1, min((int) $request->integer('limit', 20), 50)),
            ),
        ]);
    }

    public function store(StoreProjectRequest $request): JsonResponse
    {
        $result = $this->projectAdminService->create($request->validated(), (int) $request->user()->id);

        return response()->json([
            'message' => $result['message'],
            'client_created' => $result['client_created'],
            'email_sent' => $result['email_sent'],
            'data' => (new AdminProjectDetailResource($result['detail']))->resolve(),
        ], 201);
    }

    public function options(): JsonResponse
    {
        return response()->json([
            'statuses' => collect(ProjectLabels::statuses())->map(static fn (string $value): array => [
                'value' => $value,
                'label' => ProjectLabels::statusLabel($value),
            ])->values(),
            'priorities' => collect(ProjectLabels::priorities())->map(static fn (string $value): array => [
                'value' => $value,
                'label' => ProjectLabels::priorityLabel($value),
            ])->values(),
            'phase_statuses' => collect(ProjectLabels::phaseStatuses())->map(static fn (string $value): array => [
                'value' => $value,
                'label' => ProjectLabels::phaseStatusLabel($value),
            ])->values(),
            'deliverable_types' => collect(ProjectLabels::deliverableTypes())->map(static fn (string $value): array => [
                'value' => $value,
                'label' => ProjectLabels::deliverableTypeLabel($value),
            ])->values(),
            'deliverable_statuses' => collect(ProjectLabels::deliverableStatuses())->map(static fn (string $value): array => [
                'value' => $value,
                'label' => ProjectLabels::deliverableStatusLabel($value),
            ])->values(),
            'defcon_levels' => collect(ProjectLabels::defconLevels())->map(static fn (int $value): array => [
                'value' => $value,
                'label' => ProjectLabels::defconLabel($value),
            ])->values(),
        ]);
    }

    public function show(Project $project): AdminProjectDetailResource
    {
        return new AdminProjectDetailResource($this->projectAdminService->getDetail($project));
    }

    public function update(UpdateProjectRequest $request, Project $project): JsonResponse
    {
        $detail = $this->projectAdminService->updateProject($project, $request->validated(), (int) $request->user()->id);

        return response()->json([
            'message' => 'Proyecto actualizado correctamente.',
            'data' => (new AdminProjectDetailResource($detail))->resolve(),
        ]);
    }

    public function storePhase(StoreProjectPhaseRequest $request, Project $project): JsonResponse
    {
        $phase = $this->structureService->createPhase($project, $request->validated());
        $detail = $this->projectAdminService->getDetail($project);

        return response()->json([
            'message' => 'Fase creada correctamente.',
            'phase' => $phase,
            'data' => (new AdminProjectDetailResource($detail))->resolve(),
        ], 201);
    }

    public function updatePhase(StoreProjectPhaseRequest $request, Project $project, int $phase): JsonResponse
    {
        $updatedPhase = $this->structureService->updatePhase($project, $phase, $request->validated());
        $detail = $this->projectAdminService->getDetail($project);

        return response()->json([
            'message' => 'Fase actualizada correctamente.',
            'phase' => $updatedPhase,
            'data' => (new AdminProjectDetailResource($detail))->resolve(),
        ]);
    }

    public function destroyPhase(Project $project, int $phase): JsonResponse
    {
        $this->structureService->deletePhase($project, $phase);
        $detail = $this->projectAdminService->getDetail($project);

        return response()->json([
            'message' => 'Fase eliminada correctamente.',
            'data' => (new AdminProjectDetailResource($detail))->resolve(),
        ]);
    }

    public function storeDeliverable(StoreProjectDeliverableRequest $request, Project $project): JsonResponse
    {
        $deliverable = $this->structureService->createDeliverable($project, $request->validated());
        $detail = $this->projectAdminService->getDetail($project);

        return response()->json([
            'message' => 'Objetivo creado correctamente.',
            'deliverable' => $deliverable,
            'data' => (new AdminProjectDetailResource($detail))->resolve(),
        ], 201);
    }

    public function updateDeliverable(StoreProjectDeliverableRequest $request, Project $project, int $deliverable): JsonResponse
    {
        $updatedDeliverable = $this->structureService->updateDeliverable($project, $deliverable, $request->validated());
        $detail = $this->projectAdminService->getDetail($project);

        return response()->json([
            'message' => 'Objetivo actualizado correctamente.',
            'deliverable' => $updatedDeliverable,
            'data' => (new AdminProjectDetailResource($detail))->resolve(),
        ]);
    }

    public function destroyDeliverable(Project $project, int $deliverable): JsonResponse
    {
        $this->structureService->deleteDeliverable($project, $deliverable);
        $detail = $this->projectAdminService->getDetail($project);

        return response()->json([
            'message' => 'Objetivo eliminado correctamente.',
            'data' => (new AdminProjectDetailResource($detail))->resolve(),
        ]);
    }

    public function deliverableComments(Request $request, Project $project, int $deliverable): JsonResponse
    {
        $comments = $this->commentService->listForAdmin(
            (int) $project->id,
            $deliverable,
            (int) $request->user()->id,
        );

        return response()->json([
            'data' => $comments,
        ]);
    }

    public function storeDeliverableComment(
        StoreDeliverableCommentRequest $request,
        Project $project,
        int $deliverable,
    ): JsonResponse {
        $comment = $this->commentService->createForAdmin(
            (int) $request->user()->id,
            (int) $project->id,
            $deliverable,
            $request->validated('message'),
        );

        return response()->json([
            'message' => 'Comentario publicado correctamente.',
            'data' => $comment,
        ], 201);
    }

    public function showContract(Project $project): JsonResponse
    {
        $contract = $this->projectAdminService->getContractRow((int) $project->id);

        return response()->json([
            'data' => $contract,
        ]);
    }

    public function updateContract(UpdateProjectContractRequest $request, Project $project): JsonResponse
    {
        $contract = $this->projectAdminService->saveContract(
            $project,
            $request->validated(),
            (int) $request->user()->id,
        );

        return response()->json([
            'message' => 'Contrato guardado correctamente.',
            'data' => $contract,
        ]);
    }

    public function flushClientNotification(Request $request, Project $project): JsonResponse
    {
        $emailSent = $this->projectAdminService->flushClientNotification($project, (int) $request->user()->id);

        return response()->json([
            'message' => $emailSent === true
                ? 'Notificación enviada al cliente.'
                : ($emailSent === false
                    ? 'No pudimos enviar la notificación al cliente.'
                    : 'No había cambios pendientes para notificar.'),
            'email_sent' => $emailSent,
        ]);
    }

    public function discardClientNotification(Project $project): JsonResponse
    {
        $this->projectAdminService->discardClientNotification($project);

        return response()->json([
            'message' => 'Cambios pendientes de notificación descartados.',
        ]);
    }
}
