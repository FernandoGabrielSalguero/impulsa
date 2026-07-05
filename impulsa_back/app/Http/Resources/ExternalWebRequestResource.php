<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExternalWebRequestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tipo' => 'externa',
            'nombre' => $this->nombre,
            'nombre_proyecto' => $this->nombre_proyecto,
            'correo' => $this->correo,
            'whatsapp' => $this->whatsapp,
            'form_source' => $this->form_source,
            'cliente_user_id' => $this->cliente_user_id ? (int) $this->cliente_user_id : null,
            'cliente_rol' => $this->cliente_rol,
            'cliente_email_verified_at' => $this->cliente_email_verified_at,
            'proyecto_id' => $this->proyecto_id ? (int) $this->proyecto_id : null,
            'proyecto_estado' => $this->proyecto_estado,
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
