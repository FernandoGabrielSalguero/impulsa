<?php

namespace App\Http\Resources;

use App\Support\ProjectLabels;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminProjectListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $contractStatus = 'none';

        if ($this->contrato_id) {
            $contractStatus = (bool) $this->is_signed ? 'signed' : 'pending';
        }

        return [
            'id' => $this->id,
            'project_name' => $this->project_name,
            'project_type' => $this->project_type,
            'project_type_label' => ProjectLabels::projectTypeLabel($this->project_type),
            'client_name' => $this->client_name,
            'client_email' => $this->client_email,
            'client_whatsapp' => $this->client_whatsapp,
            'status' => $this->status,
            'status_label' => ProjectLabels::statusLabel($this->status),
            'priority' => $this->priority,
            'priority_label' => ProjectLabels::priorityLabel($this->priority),
            'progress_percent' => (int) $this->progress_percent,
            'fases_total' => (int) ($this->fases_total ?? 0),
            'objetivos_total' => (int) ($this->objetivos_total ?? 0),
            'contrato_id' => $this->contrato_id ? (int) $this->contrato_id : null,
            'contract_name' => $this->contract_name,
            'contract_status' => $contractStatus,
            'contract_status_label' => match ($contractStatus) {
                'signed' => 'Firmado',
                'pending' => 'Pendiente',
                default => 'Sin contrato',
            },
            'manager_correo' => $this->manager_correo,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}
