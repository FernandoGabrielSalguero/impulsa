<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminAiUsageLogDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $metadata = null;

        if ($this->metadata_json !== null && trim((string) $this->metadata_json) !== '') {
            $decoded = json_decode((string) $this->metadata_json, true);
            $metadata = is_array($decoded) ? $decoded : ['raw' => (string) $this->metadata_json];
        }

        return [
            'id' => (int) $this->id,
            'user_auth_id' => $this->user_auth_id !== null ? (int) $this->user_auth_id : null,
            'usuario_relacionado' => $this->resolveRelatedUserName(),
            'usuario_correo' => $this->usuario_correo !== null ? (string) $this->usuario_correo : null,
            'provider' => (string) $this->provider,
            'feature' => (string) $this->feature,
            'model' => $this->model !== null ? (string) $this->model : null,
            'status' => (string) $this->status,
            'prompt_tokens' => $this->prompt_tokens !== null ? (int) $this->prompt_tokens : null,
            'completion_tokens' => $this->completion_tokens !== null ? (int) $this->completion_tokens : null,
            'total_tokens' => $this->total_tokens !== null ? (int) $this->total_tokens : null,
            'latency_ms' => $this->latency_ms !== null ? (int) $this->latency_ms : null,
            'error_message' => $this->error_message !== null ? (string) $this->error_message : null,
            'metadata' => $metadata,
            'ip_address' => $this->ip_address !== null ? (string) $this->ip_address : null,
            'created_at' => $this->created_at,
        ];
    }

    private function resolveRelatedUserName(): string
    {
        $nombre = trim((string) ($this->usuario_nombre ?? '') . ' ' . (string) ($this->usuario_apellido ?? ''));

        if ($nombre !== '') {
            return $nombre;
        }

        $apodo = trim((string) ($this->usuario_apodo ?? ''));

        if ($apodo !== '') {
            return $apodo;
        }

        $usuarioCorreo = trim((string) ($this->usuario_correo ?? ''));

        return $usuarioCorreo !== '' ? $usuarioCorreo : '-';
    }
}
