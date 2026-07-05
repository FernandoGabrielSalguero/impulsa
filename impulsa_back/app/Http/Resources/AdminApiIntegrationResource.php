<?php

namespace App\Http\Resources;

use App\Support\IntegrationLabels;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminApiIntegrationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $ownerName = $this->formatOwnerName();

        return [
            'id' => $this->id,
            'project_name' => $this->project_name,
            'allowed_domain' => $this->allowed_domain,
            'public_key' => $this->public_key,
            'status' => $this->status,
            'status_label' => IntegrationLabels::statusLabel($this->status),
            'user_auth_id' => $this->user_auth_id ? (int) $this->user_auth_id : null,
            'owner_name' => $ownerName,
            'owner_email' => $this->owner_contacto_correo ?? $this->owner_auth_correo,
            'owner_label' => $ownerName
                ? $ownerName . ' · ' . ($this->owner_contacto_correo ?? $this->owner_auth_correo)
                : ($this->owner_contacto_correo ?? $this->owner_auth_correo),
            'total_visits' => (int) ($this->total_visits ?? 0),
            'total_contacts' => (int) ($this->total_contacts ?? 0),
            'last_used_at' => $this->last_used_at?->toISOString(),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'has_secret' => filled($this->secret_key_hash),
        ];
    }

    private function formatOwnerName(): ?string
    {
        $fullName = trim((string) ($this->owner_nombre ?? '') . ' ' . (string) ($this->owner_apellido ?? ''));

        if ($fullName !== '') {
            return $fullName;
        }

        $nickname = trim((string) ($this->owner_apodo ?? ''));

        return $nickname !== '' ? $nickname : null;
    }
}
