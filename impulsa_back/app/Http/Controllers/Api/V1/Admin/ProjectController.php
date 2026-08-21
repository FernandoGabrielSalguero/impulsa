<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDeliverableCommentRequest;
use App\Http\Requests\Admin\StoreProjectDeliverableRequest;
use App\Http\Requests\Admin\StoreProjectPhaseRequest;
use App\Http\Requests\Admin\StoreProjectRequest;
use App\Http\Requests\Admin\UpdateProjectContractRequest;
use App\Http\Requests\Admin\UpdateProjectRequest;
use App\Http\Requests\Projects\StoreProjectAttachmentRequest;
use App\Http\Resources\AdminProjectCollection;
use App\Http\Resources\AdminProjectDetailResource;
use App\Models\Project;
use App\Services\Admin\ProjectAdminService;
use App\Services\Admin\ProjectStructureService;
use App\Services\Colaborador\DeliverableCommentService;
use App\Services\Projects\ProjectAttachmentService;
use App\Support\ProjectLabels;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProjectController extends Controller
{
    public function __construct(
        private readonly ProjectAdminService $projectAdminService,
        private readonly ProjectStructureService $structureService,
        private readonly DeliverableCommentService $commentService,
        private readonly ProjectAttachmentService $attachmentService,
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

    public function show(Request $request, Project $project): AdminProjectDetailResource
    {
        return new AdminProjectDetailResource(
            $this->projectAdminService->getDetail($project, (int) $request->user()->id),
        );
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
        $detail = $this->projectAdminService->getDetail($project, (int) $request->user()->id);

        return response()->json([
            'message' => 'Fase creada correctamente.',
            'phase' => $phase,
            'data' => (new AdminProjectDetailResource($detail))->resolve(),
        ], 201);
    }

    public function updatePhase(StoreProjectPhaseRequest $request, Project $project, int $phase): JsonResponse
    {
        $updatedPhase = $this->structureService->updatePhase($project, $phase, $request->validated());
        $detail = $this->projectAdminService->getDetail($project, (int) $request->user()->id);

        return response()->json([
            'message' => 'Fase actualizada correctamente.',
            'phase' => $updatedPhase,
            'data' => (new AdminProjectDetailResource($detail))->resolve(),
        ]);
    }

    public function destroyPhase(Request $request, Project $project, int $phase): JsonResponse
    {
        $this->structureService->deletePhase($project, $phase);
        $detail = $this->projectAdminService->getDetail($project, (int) $request->user()->id);

        return response()->json([
            'message' => 'Fase eliminada correctamente.',
            'data' => (new AdminProjectDetailResource($detail))->resolve(),
        ]);
    }

    public function storeDeliverable(StoreProjectDeliverableRequest $request, Project $project): JsonResponse
    {
        $deliverable = $this->structureService->createDeliverable($project, $request->validated());
        $detail = $this->projectAdminService->getDetail($project, (int) $request->user()->id);

        return response()->json([
            'message' => 'Objetivo creado correctamente.',
            'deliverable' => $deliverable,
            'data' => (new AdminProjectDetailResource($detail))->resolve(),
        ], 201);
    }

    public function updateDeliverable(StoreProjectDeliverableRequest $request, Project $project, int $deliverable): JsonResponse
    {
        $updatedDeliverable = $this->structureService->updateDeliverable($project, $deliverable, $request->validated());
        $detail = $this->projectAdminService->getDetail($project, (int) $request->user()->id);

        return response()->json([
            'message' => 'Objetivo actualizado correctamente.',
            'deliverable' => $updatedDeliverable,
            'data' => (new AdminProjectDetailResource($detail))->resolve(),
        ]);
    }

    public function destroyDeliverable(Request $request, Project $project, int $deliverable): JsonResponse
    {
        $this->structureService->deleteDeliverable($project, $deliverable);
        $detail = $this->projectAdminService->getDetail($project, (int) $request->user()->id);

        return response()->json([
            'message' => 'Objetivo eliminado correctamente.',
            'data' => (new AdminProjectDetailResource($detail))->resolve(),
        ]);
    }

    public function projectComments(Request $request, Project $project): JsonResponse
    {
        return response()->json([
            'data' => $this->commentService->listGroupedByPhaseForAdmin(
                (int) $project->id,
                (int) $request->user()->id,
            ),
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

    public function storePhaseAttachment(
        StoreProjectAttachmentRequest $request,
        Project $project,
        int $phase,
    ): JsonResponse {
        $file = $request->file('file');

        if ($file === null) {
            return response()->json(['message' => 'Debés seleccionar un archivo.'], 422);
        }

        $attachment = $this->attachmentService->storeForPhase(
            (int) $request->user()->id,
            (int) $project->id,
            $phase,
            $file,
        );

        return response()->json([
            'message' => 'Archivo adjunto correctamente.',
            'data' => $attachment,
        ], 201);
    }

    public function storeDeliverableAttachment(
        StoreProjectAttachmentRequest $request,
        Project $project,
        int $deliverable,
    ): JsonResponse {
        $file = $request->file('file');

        if ($file === null) {
            return response()->json(['message' => 'Debés seleccionar un archivo.'], 422);
        }

        $attachment = $this->attachmentService->storeForDeliverable(
            (int) $request->user()->id,
            (int) $project->id,
            $deliverable,
            $file,
        );

        return response()->json([
            'message' => 'Archivo adjunto correctamente.',
            'data' => $attachment,
        ], 201);
    }

    public function markPhaseAttachmentsRead(Request $request, Project $project, int $phase): JsonResponse
    {
        $unread = $this->attachmentService->markPhaseAttachmentsRead(
            (int) $request->user()->id,
            (int) $project->id,
            $phase,
        );

        return response()->json([
            'message' => 'Adjuntos marcados como vistos.',
            'unread_attachments_count' => $unread,
        ]);
    }

    public function markDeliverableAttachmentsRead(
        Request $request,
        Project $project,
        int $deliverable,
    ): JsonResponse {
        $unread = $this->attachmentService->markDeliverableAttachmentsRead(
            (int) $request->user()->id,
            (int) $project->id,
            $deliverable,
        );

        return response()->json([
            'message' => 'Adjuntos marcados como vistos.',
            'unread_attachments_count' => $unread,
        ]);
    }

    public function destroyAttachment(Project $project, int $attachment): JsonResponse
    {
        $this->attachmentService->delete((int) $project->id, $attachment);

        return response()->json([
            'message' => 'Adjunto eliminado correctamente.',
        ]);
    }

    public function showAttachment(Project $project, int $attachment): BinaryFileResponse
    {
        return $this->attachmentService->fileResponse((int) $project->id, $attachment);
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

    public function markDeliverableCommentsRead(
        Request $request,
        Project $project,
        int $deliverable,
    ): JsonResponse {
        $unreadCount = $this->commentService->markReadForAdmin(
            (int) $request->user()->id,
            (int) $project->id,
            $deliverable,
        );

        return response()->json([
            'message' => 'Comentarios marcados como leídos.',
            'unread_comments_count' => $unreadCount,
        ]);
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
        $notifyClient = $request->boolean('notify_client', true);
        $notifyCollaborators = $request->boolean('notify_collaborators', false);

        $result = $this->projectAdminService->flushClientNotification(
            $project,
            (int) $request->user()->id,
            $notifyClient,
            $notifyCollaborators,
        );

        $clientSent = $result['client_email_sent'];
        $collaboratorsSent = $result['collaborators_email_sent'];
        $collaboratorsNotified = (int) $result['collaborators_notified'];

        return response()->json([
            'message' => $this->flushNotificationMessage(
                $notifyClient,
                $notifyCollaborators,
                $clientSent,
                $collaboratorsSent,
                $collaboratorsNotified,
            ),
            'email_sent' => $clientSent,
            'client_email_sent' => $clientSent,
            'collaborators_email_sent' => $collaboratorsSent,
            'collaborators_notified' => $collaboratorsNotified,
        ]);
    }

    public function discardClientNotification(Project $project): JsonResponse
    {
        $this->projectAdminService->discardClientNotification($project);

        return response()->json([
            'message' => 'Cambios pendientes de notificación descartados.',
        ]);
    }

    private function flushNotificationMessage(
        bool $notifyClient,
        bool $notifyCollaborators,
        ?bool $clientSent,
        ?bool $collaboratorsSent,
        int $collaboratorsNotified,
    ): string {
        if ($clientSent === null && $collaboratorsSent === null && $collaboratorsNotified === 0) {
            return 'No había cambios pendientes para notificar.';
        }

        $parts = [];

        if ($notifyClient) {
            $parts[] = match ($clientSent) {
                true => 'Notificación enviada al cliente.',
                false => 'No pudimos enviar la notificación al cliente.',
                default => null,
            };
        }

        if ($notifyCollaborators) {
            if ($collaboratorsNotified > 0) {
                $parts[] = $collaboratorsNotified === 1
                    ? 'Notificación enviada a 1 colaborador.'
                    : 'Notificación enviada a '.$collaboratorsNotified.' colaboradores.';
            } elseif ($collaboratorsSent === false) {
                $parts[] = 'No pudimos enviar la notificación a los colaboradores.';
            } else {
                $parts[] = 'No había colaboradores para notificar.';
            }
        }

        $parts = array_values(array_filter($parts));

        return $parts !== [] ? implode(' ', $parts) : 'No había cambios pendientes para notificar.';
    }
}
