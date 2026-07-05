<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAuthResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'correo' => $this->correo,
            'rol' => $this->rol,
            'nombre' => $this->whenLoaded('info', fn () => $this->info?->nombre),
            'redirect_to' => \App\Support\AuthDashboard::routeForRole($this->rol),
        ];
    }
}
