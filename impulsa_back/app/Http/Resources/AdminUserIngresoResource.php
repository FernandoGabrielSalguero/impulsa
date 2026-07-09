<?php

namespace App\Http\Resources;

use App\Support\RoleLabels;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminUserIngresoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $rol = (string) $this->rol;

        return [
            'id' => (int) $this->id,
            'user_auth_id' => (int) $this->user_auth_id,
            'nombre_usuario' => (string) $this->nombre_usuario,
            'usuario_correo' => $this->usuario_correo !== null ? (string) $this->usuario_correo : null,
            'rol' => $rol,
            'rol_label' => RoleLabels::labelFor($rol),
            'fecha_ingreso' => (string) $this->fecha_ingreso,
            'hora_ingreso' => (string) $this->hora_ingreso,
            'created_at' => $this->created_at,
        ];
    }
}
