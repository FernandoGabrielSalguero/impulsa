<?php

namespace App\Http\Resources;

use App\Support\RoleLabels;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmailMarketingContactResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'correo' => $this->correo,
            'nombre' => $this->info?->nombre,
            'apellido' => $this->info?->apellido,
            'apodo' => $this->info?->apodo,
            'rol' => $this->rol,
            'rol_label' => RoleLabels::labelFor($this->rol),
            'usuario_tipo' => $this->usuario_tipo,
            'email_verified_at' => $this->email_verified_at?->toISOString(),
            'permison_correo' => (bool) ($this->contacto?->permison_correo ?? true),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
