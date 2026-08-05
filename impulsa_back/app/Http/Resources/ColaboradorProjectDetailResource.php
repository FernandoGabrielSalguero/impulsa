<?php

namespace App\Http\Resources;

use App\Support\ProjectLabels;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ColaboradorProjectDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var array<string, mixed> $payload */
        $payload = $this->resource;
        $project = $payload['project'];
        $phases = $payload['phases'];
        $deliverables = $payload['deliverables'];
        $collaborators = $payload['collaborators'] ?? [];
        $viewerId = (int) $request->user()->id;

        return [
            'project' => $this->formatProject($project),
            'phases' => array_map(
                fn (array $phase): array => $this->formatPhase($phase, $viewerId),
                $phases,
            ),
            'deliverables' => array_map(
                fn (array $deliverable): array => $this->formatDeliverable($deliverable, $viewerId),
                $deliverables,
            ),
            'collaborators' => array_map(
                static fn (array $collaborator): array => [
                    'id' => (int) $collaborator['id'],
                    'nombre' => $collaborator['nombre'] ?? null,
                    'correo' => $collaborator['correo'],
                    'correo_contacto' => $collaborator['correo_contacto'] ?? $collaborator['correo'],
                    'whatsapp' => $collaborator['whatsapp'] ?? null,
                    'has_avatar' => (bool) ($collaborator['has_avatar'] ?? false),
                ],
                $collaborators,
            ),
        ];
    }

    /** @param  array<string, mixed>  $project */
    private function formatProject(array $project): array
    {
        return [
            'id' => (int) $project['id'],
            'project_name' => $project['project_name'],
            'project_type' => $project['project_type'],
            'project_type_label' => ProjectLabels::projectTypeLabel($project['project_type'] ?? null),
            'client_name' => $project['client_name'],
            'client_email' => $project['client_email'],
            'client_whatsapp' => $project['client_whatsapp'],
            'manager_correo' => $project['manager_correo'] ?? null,
            'summary' => $project['summary'],
            'scope_summary' => $project['scope_summary'],
            'status' => $project['status'],
            'status_label' => ProjectLabels::statusLabel($project['status'] ?? null),
            'priority' => $project['priority'],
            'priority_label' => ProjectLabels::priorityLabel($project['priority'] ?? null),
            'start_date' => $project['start_date'],
            'target_delivery_date' => $project['target_delivery_date'] ?? null,
            'progress_percent' => (int) ($project['progress_percent'] ?? 0),
            'progress_detail' => $project['progress_detail'] ?? null,
            'updated_at' => $project['updated_at'],
            'created_at' => $project['created_at'],
        ];
    }

    /** @param  array<string, mixed>  $phase */
    private function formatPhase(array $phase, int $viewerId): array
    {
        $assignedUserId = isset($phase['assigned_user_id']) && $phase['assigned_user_id'] !== null
            ? (int) $phase['assigned_user_id']
            : null;

        return [
            'id' => (int) $phase['id'],
            'project_id' => (int) $phase['project_id'],
            'title' => $phase['title'],
            'description' => $phase['description'],
            'duration_days' => $phase['duration_days'] !== null ? (int) $phase['duration_days'] : null,
            'phase_order' => (int) $phase['phase_order'],
            'status' => $phase['status'],
            'status_label' => ProjectLabels::phaseStatusLabel($phase['status'] ?? null),
            'due_date' => $phase['due_date'],
            'completed_at' => $phase['completed_at'] ?? null,
            'assigned_user_id' => $assignedUserId,
            'assigned_user_label' => $phase['assigned_user_label'] ?? null,
            'assigned_to_me' => $assignedUserId !== null && $assignedUserId === $viewerId,
            'attachments' => array_map(
                static fn (array $attachment): array => [
                    'id' => (int) $attachment['id'],
                    'original_name' => $attachment['original_name'],
                    'mime_type' => $attachment['mime_type'],
                    'size_bytes' => (int) ($attachment['size_bytes'] ?? 0),
                    'created_at' => $attachment['created_at'] ?? null,
                ],
                $phase['attachments'] ?? [],
            ),
        ];
    }

    /** @param  array<string, mixed>  $deliverable */
    private function formatDeliverable(array $deliverable, int $viewerId): array
    {
        $assignedUserId = isset($deliverable['assigned_user_id']) && $deliverable['assigned_user_id'] !== null
            ? (int) $deliverable['assigned_user_id']
            : null;

        return [
            'id' => (int) $deliverable['id'],
            'project_id' => (int) $deliverable['project_id'],
            'phase_id' => $deliverable['phase_id'] !== null ? (int) $deliverable['phase_id'] : null,
            'phase_title' => $deliverable['phase_title'] ?? null,
            'title' => $deliverable['title'],
            'description' => $deliverable['description'],
            'deliverable_type' => $deliverable['deliverable_type'],
            'deliverable_type_label' => ProjectLabels::deliverableTypeLabel($deliverable['deliverable_type'] ?? null),
            'status' => $deliverable['status'],
            'status_label' => ProjectLabels::deliverableStatusLabel($deliverable['status'] ?? null),
            'defcon' => (int) ($deliverable['defcon'] ?? 5),
            'defcon_label' => ProjectLabels::defconLabel((int) ($deliverable['defcon'] ?? 5)),
            'due_date' => $deliverable['due_date'],
            'delivered_at' => $deliverable['delivered_at'] ?? null,
            'assigned_user_id' => $assignedUserId,
            'assigned_user_label' => $deliverable['assigned_user_label'] ?? null,
            'assigned_to_me' => $assignedUserId !== null && $assignedUserId === $viewerId,
            'comments_count' => (int) ($deliverable['comments_count'] ?? 0),
            'unread_comments_count' => (int) ($deliverable['unread_comments_count'] ?? 0),
            'attachments' => array_map(
                static fn (array $attachment): array => [
                    'id' => (int) $attachment['id'],
                    'original_name' => $attachment['original_name'],
                    'mime_type' => $attachment['mime_type'],
                    'size_bytes' => (int) ($attachment['size_bytes'] ?? 0),
                    'created_at' => $attachment['created_at'] ?? null,
                ],
                $deliverable['attachments'] ?? [],
            ),
        ];
    }
}
