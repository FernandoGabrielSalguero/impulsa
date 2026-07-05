<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminApiProductIntegrationOptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => (int) $this->resource->id,
            'project_name' => (string) $this->resource->project_name,
            'allowed_domain' => (string) $this->resource->allowed_domain,
            'public_key' => (string) $this->resource->public_key,
            'status' => (string) $this->resource->status,
            'user_auth_id' => (int) $this->resource->user_auth_id,
            'owner_name' => $this->resolveOwnerName(),
            'owner_email' => $this->resolveOwnerEmail(),
            'total_productos' => (int) ($this->resource->total_productos ?? 0),
        ];
    }

    private function resolveOwnerName(): string
    {
        $nombre = trim((string) ($this->resource->owner_nombre ?? '') . ' ' . (string) ($this->resource->owner_apellido ?? ''));
        $apodo = trim((string) ($this->resource->owner_apodo ?? ''));
        $correo = $this->resolveOwnerEmail();

        if ($nombre !== '') {
            return $nombre;
        }

        if ($apodo !== '') {
            return $apodo;
        }

        return $correo !== '' ? $correo : 'Usuario sin nombre';
    }

    private function resolveOwnerEmail(): string
    {
        $contacto = trim((string) ($this->resource->owner_contacto_correo ?? ''));

        if ($contacto !== '') {
            return $contacto;
        }

        return trim((string) ($this->resource->owner_auth_correo ?? ''));
    }
}
