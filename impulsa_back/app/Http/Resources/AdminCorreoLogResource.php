<?php

namespace App\Http\Resources;

use App\Support\MailTemplateLabels;
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
            'created_at' => $this->created_at?->toISOString(),
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

        $usuarioCorreo = trim((string) ($this->usuario_correo ?? ''));

        return $usuarioCorreo !== '' ? $usuarioCorreo : '-';
    }
}
