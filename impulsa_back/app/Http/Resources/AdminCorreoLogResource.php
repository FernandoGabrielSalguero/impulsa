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
            'id' => $this->id,
            'user_auth_id' => $this->user_auth_id,
            'correo' => $this->correo,
            'asunto' => $this->asunto,
            'template' => $this->template,
            'template_label' => MailTemplateLabels::labelFor($this->template),
            'estado' => $this->estado,
            'error' => $this->error,
            'created_at' => $this->formatDateTime($this->created_at),
            'usuario_relacionado' => $this->resolveRelatedUserName(),
        ];
    }

    private function resolveRelatedUserName(): string
    {
        $info = $this->userAuth?->info;

        if ($info !== null) {
            $nombre = trim((string) ($info->nombre ?? '') . ' ' . (string) ($info->apellido ?? ''));

            if ($nombre !== '') {
                return $nombre;
            }

            $apodo = trim((string) ($info->apodo ?? ''));

            if ($apodo !== '') {
                return $apodo;
            }
        }

        $usuarioCorreo = trim((string) ($this->userAuth?->correo ?? ''));

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
