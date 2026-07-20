<?php

namespace App\Http\Controllers\Api\V1\Colaborador;

use App\Http\Controllers\Controller;
use App\Http\Requests\Colaborador\StoreDeliverableCommentRequest;
use App\Http\Requests\Colaborador\UpdateDeliverableStatusRequest;
use App\Http\Requests\Colaborador\UpdatePhaseStatusRequest;
use App\Http\Requests\Colaborador\UpdateProjectStatusRequest;
use App\Http\Resources\AdminProjectCollection;
use App\Http\Resources\ColaboradorProjectDetailResource;
use App\Services\Colaborador\ColaboradorProjectService;
use App\Services\Colaborador\DeliverableCommentService;
use App\Support\ProjectLabels;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct(
        private readonly ColaboradorProjectService $projectService,
        private readonly DeliverableCommentService $commentService,
    ) {}

    public function index(Request $request): AdminProjectCollection
    {
        $perPage = max(1, min((int) $request->integer('per_page', 20), 100));
        $projects = $this->projectService->listForUser(
            (int) $request->user()->id,
            $request->query('q'),
            $perPage,
        );

        return new AdminProjectCollection($projects);
    }

    public function options(): JsonResponse
    {
        return response()->json([
            'statuses' => collect(ProjectLabels::statuses())->map(static fn (string $value): array => [
                'value' => $value,
                'label' => ProjectLabels::statusLabel($value),
            ])->values(),
            'phase_statuses' => collect(ProjectLabels::phaseStatuses())->map(static fn (string $value): array => [
                'value' => $value,
                'label' => ProjectLabels::phaseStatusLabel($value),
            ])->values(),
            'deliverable_statuses' => collect(ProjectLabels::deliverableStatuses())
                ->filter(static fn (string $value): bool => $value !== 'delivered')
                ->map(static fn (string $value): array => [
                    'value' => $value,
                    'label' => ProjectLabels::deliverableStatusLabel($value),
                ])
                ->values(),
        ]);
    }

    public function show(Request $request, int $project): ColaboradorProjectDetailResource
    {
        return new ColaboradorProjectDetailResource(
            $this->projectService->getDetailForUser((int) $request->user()->id, $project),
        );
    }

    public function updateStatus(UpdateProjectStatusRequest $request, int $project): JsonResponse
    {
        $detail = $this->projectService->updateProjectStatus(
            (int) $request->user()->id,
            $project,
            $request->validated('status'),
        );

        return response()->json([
            'message' => 'Estado del proyecto actualizado correctamente.',
            'data' => (new ColaboradorProjectDetailResource($detail))->resolve(),
        ]);
    }

    public function updatePhaseStatus(UpdatePhaseStatusRequest $request, int $project, int $phase): JsonResponse
    {
        $detail = $this->projectService->updatePhaseStatus(
            (int) $request->user()->id,
            $project,
            $phase,
            $request->validated('status'),
        );

        return response()->json([
            'message' => 'Estado de la fase actualizado correctamente.',
            'data' => (new ColaboradorProjectDetailResource($detail))->resolve(),
        ]);
    }

    public function updateDeliverableStatus(
        UpdateDeliverableStatusRequest $request,
        int $project,
        int $deliverable,
    ): JsonResponse {
        $detail = $this->projectService->updateDeliverableStatus(
            (int) $request->user()->id,
            $project,
            $deliverable,
            $request->validated('status'),
        );

        return response()->json([
            'message' => 'Estado del objetivo actualizado correctamente.',
            'data' => (new ColaboradorProjectDetailResource($detail))->resolve(),
        ]);
    }

    public function comments(Request $request, int $project, int $deliverable): JsonResponse
    {
        $comments = $this->commentService->listForCollaborator(
            (int) $request->user()->id,
            $project,
            $deliverable,
        );

        return response()->json([
            'data' => $comments,
        ]);
    }

    public function storeComment(
        StoreDeliverableCommentRequest $request,
        int $project,
        int $deliverable,
    ): JsonResponse {
        $comment = $this->commentService->createForCollaborator(
            (int) $request->user()->id,
            $project,
            $deliverable,
            $request->validated('message'),
        );

        return response()->json([
            'message' => 'Comentario publicado correctamente.',
            'data' => $comment,
        ], 201);
    }
}
