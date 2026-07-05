<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InternalWebRequestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tipo' => 'interna',
            'nombre_emprendimiento' => $this->nombre_emprendimiento,
            'fecha_inicio' => $this->fecha_inicio?->format('Y-m-d'),
            'descripcion' => $this->descripcion,
            'telefono_contacto' => $this->telefono_contacto,
            'completado' => (bool) $this->completado,
            'estado_label' => $this->completado ? 'Completada' : 'Pendiente',
            'rubro_categoria' => $this->rubro_categoria,
            'rubro_subcategoria' => $this->rubro_subcategoria,
            'usuario_correo' => $this->usuario_correo,
            'solicitante_nombre' => $this->resolveRequesterName(),
            'cliente_user_id' => $this->cliente_user_id ? (int) $this->cliente_user_id : null,
            'proyecto_id' => $this->proyecto_id ? (int) $this->proyecto_id : null,
            'proyecto_estado' => $this->proyecto_estado,
            'created_at' => $this->created_at?->toISOString(),
        ];
    }

    private function resolveRequesterName(): string
    {
        $fullName = trim((string) ($this->usuario_nombre ?? '') . ' ' . (string) ($this->usuario_apellido ?? ''));

        if ($fullName !== '') {
            return $fullName;
        }

        $apodo = trim((string) ($this->usuario_apodo ?? ''));

        return $apodo !== '' ? $apodo : 'Sin nombre';
    }
}
