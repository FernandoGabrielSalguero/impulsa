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
            'apellido' => $this->whenLoaded('info', fn () => $this->info?->apellido),
            'apodo' => $this->whenLoaded('info', fn () => $this->info?->apodo),
            'has_avatar' => $this->whenLoaded('info', function () {
                $avatarPath = trim((string) ($this->info?->avatar_path ?? ''));

                return $avatarPath !== '' && str_starts_with($avatarPath, 'user-avatars/');
            }),
            'redirect_to' => \App\Support\AuthDashboard::routeForRole($this->rol),
        ];
    }
}
