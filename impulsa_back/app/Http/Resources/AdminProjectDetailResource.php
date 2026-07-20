<?php

namespace App\Http\Resources;

use App\Support\ProjectLabels;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminProjectDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var array<string, mixed> $payload */
        $payload = $this->resource;
        $project = $payload['project'];
        $phases = $payload['phases'];
        $deliverables = $payload['deliverables'];
        $contract = $payload['contract'];
        $collaborators = $payload['collaborators'] ?? [];

        return [
            'project' => $this->formatProject($project),
            'phases' => array_map($this->formatPhase(...), $phases),
            'deliverables' => array_map($this->formatDeliverable(...), $deliverables),
            'contract' => $contract ? $this->formatContract($contract) : null,
            'collaborators' => array_map(static fn (array $collaborator): array => [
                'id' => (int) $collaborator['id'],
                'correo' => $collaborator['correo'],
                'nombre' => $collaborator['nombre'] ?? null,
                'label' => $collaborator['label'],
            ], $collaborators),
        ];
    }

    /** @param  array<string, mixed>  $project */
    private function formatProject(array $project): array
    {
        return [
            'id' => (int) $project['id'],
            'source_type' => $project['source_type'],
            'source_id' => $project['source_id'] ? (int) $project['source_id'] : null,
            'project_name' => $project['project_name'],
            'project_type' => $project['project_type'],
            'project_type_label' => ProjectLabels::projectTypeLabel($project['project_type'] ?? null),
            'client_user_id' => $project['client_user_id'] ? (int) $project['client_user_id'] : null,
            'manager_user_id' => (int) $project['manager_user_id'],
            'client_name' => $project['client_name'],
            'client_email' => $project['client_email'],
            'client_whatsapp' => $project['client_whatsapp'],
            'cliente_correo_login' => $project['cliente_correo_login'] ?? null,
            'manager_correo' => $project['manager_correo'] ?? null,
            'summary' => $project['summary'],
            'scope_summary' => $project['scope_summary'],
            'status' => $project['status'],
            'status_label' => ProjectLabels::statusLabel($project['status'] ?? null),
            'priority' => $project['priority'],
            'priority_label' => ProjectLabels::priorityLabel($project['priority'] ?? null),
            'start_date' => $project['start_date'],
            'target_delivery_date' => $project['target_delivery_date'] ?? null,
            'actual_delivery_date' => $project['actual_delivery_date'] ?? null,
            'progress_percent' => (int) ($project['progress_percent'] ?? 0),
            'progress_detail' => $project['progress_detail'] ?? null,
            'client_visible' => (bool) ($project['client_visible'] ?? false),
            'created_at' => $project['created_at'],
            'updated_at' => $project['updated_at'],
        ];
    }

    /** @param  array<string, mixed>  $phase */
    private function formatPhase(array $phase): array
    {
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
            'assigned_user_id' => isset($phase['assigned_user_id']) && $phase['assigned_user_id'] !== null
                ? (int) $phase['assigned_user_id']
                : null,
            'assigned_user_label' => $phase['assigned_user_label'] ?? null,
        ];
    }

    /** @param  array<string, mixed>  $deliverable */
    private function formatDeliverable(array $deliverable): array
    {
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
            'client_visible' => (bool) ($deliverable['client_visible'] ?? false),
            'assigned_user_id' => isset($deliverable['assigned_user_id']) && $deliverable['assigned_user_id'] !== null
                ? (int) $deliverable['assigned_user_id']
                : null,
            'assigned_user_label' => $deliverable['assigned_user_label'] ?? null,
        ];
    }

    /** @param  array<string, mixed>  $contract */
    private function formatContract(array $contract): array
    {
        return [
            'id' => (int) $contract['id'],
            'project_id' => (int) $contract['project_id'],
            'contract_name' => $contract['contract_name'],
            'contract_text' => $contract['contract_text'],
            'contract_html' => $contract['contract_html'],
            'version_number' => (int) $contract['version_number'],
            'is_signed' => (bool) $contract['is_signed'],
            'signed_at' => $contract['signed_at'] ?? null,
            'signer_full_name' => $contract['signer_full_name'] ?? null,
            'updated_at' => $contract['updated_at'] ?? null,
        ];
    }
}
