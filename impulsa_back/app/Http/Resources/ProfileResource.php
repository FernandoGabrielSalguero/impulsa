<?php

namespace App\Http\Resources;

use App\Services\Profile\UserAvatarStorageService;
use App\Support\RoleLabels;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $avatarStorage = app(UserAvatarStorageService::class);
        $avatarPath = trim((string) ($this->info?->avatar_path ?? ''));

        return [
            'id' => $this->id,
            'correo' => $this->correo,
            'rol' => $this->rol,
            'rol_label' => RoleLabels::labelFor($this->rol),
            'email_verified_at' => $this->email_verified_at?->toISOString(),
            'nombre' => $this->info?->nombre,
            'apellido' => $this->info?->apellido,
            'apodo' => $this->info?->apodo,
            'fecha_nacimiento' => $this->info?->fecha_nacimiento?->format('Y-m-d'),
            'has_avatar' => $avatarPath !== '' && $avatarStorage->isManagedPath($avatarPath),
            'correo_contacto' => $this->contacto?->correo ?? $this->correo,
            'whatsapp' => $this->contacto?->whatsapp,
            'permison_correo' => (bool) ($this->contacto?->permison_correo ?? true),
            'permison_whatsapp' => (bool) ($this->contacto?->permison_whatsapp ?? true),
        ];
    }
}
