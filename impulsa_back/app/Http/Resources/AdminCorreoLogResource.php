<?php

namespace App\Http\Resources;

use App\Support\MailTemplateLabels;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminCorreoLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => (int) $this->id,
            'user_auth_id' => $this->user_auth_id !== null ? (int) $this->user_auth_id : null,
            'correo' => (string) $this->correo,
            'asunto' => (string) $this->asunto,
            'template' => $this->template !== null ? (string) $this->template : null,
            'template_label' => MailTemplateLabels::labelFor($this->template),
            'estado' => (string) $this->estado,
            'error' => $this->error !== null ? (string) $this->error : null,
            'created_at' => $this->formatDateTime($this->created_at),
            'usuario_relacionado' => $this->resolveRelatedUserName(),
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

        if ($this->resource && method_exists($this->resource, 'relationLoaded') && $this->resource->relationLoaded('userAuth')) {
            $info = $this->resource->userAuth?->info;

            if ($info !== null) {
                $nombreRelacion = trim((string) ($info->nombre ?? '') . ' ' . (string) ($info->apellido ?? ''));

                if ($nombreRelacion !== '') {
                    return $nombreRelacion;
                }

                $apodoRelacion = trim((string) ($info->apodo ?? ''));

                if ($apodoRelacion !== '') {
                    return $apodoRelacion;
                }
            }

            $correoRelacion = trim((string) ($this->resource->userAuth?->correo ?? ''));

            if ($correoRelacion !== '') {
                return $correoRelacion;
            }
        }

        $usuarioCorreo = trim((string) ($this->usuario_correo ?? ''));

        return $usuarioCorreo !== '' ? $usuarioCorreo : '-';
    }

    private function formatDateTime(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof Carbon) {
            return $value->toIso8601String();
        }

        try {
            return Carbon::parse((string) $value)->toIso8601String();
        } catch (\Throwable) {
            return (string) $value;
        }
    }
}
