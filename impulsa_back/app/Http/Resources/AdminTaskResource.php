<?php

namespace App\Http\Resources;

use App\Support\TaskLabels;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminTaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre_tarea' => $this->nombre_tarea,
            'descripcion' => $this->descripcion,
            'fecha_entrega' => $this->fecha_entrega?->format('Y-m-d'),
            'prioridad_defcon' => (int) $this->prioridad_defcon,
            'prioridad_label' => TaskLabels::defconLabel((int) $this->prioridad_defcon),
            'reporta_a' => $this->reporta_a,
            'estado' => $this->estado,
            'estado_label' => TaskLabels::statusLabel($this->estado),
            'responsable_user_id' => (int) $this->responsable_user_id,
            'responsable_correo' => $this->responsable_correo ?? $this->responsable?->correo,
            'responsable_nombre' => $this->formatPersonName(
                $this->responsable_nombre ?? null,
                $this->responsable_apellido ?? null,
                $this->responsable_apodo ?? null,
            ),
            'created_by_user_id' => (int) $this->created_by_user_id,
            'creador_correo' => $this->creador_correo ?? $this->creador?->correo,
            'creador_nombre' => $this->formatPersonName(
                $this->creador_nombre ?? null,
                $this->creador_apellido ?? null,
                $this->creador_apodo ?? null,
            ),
            'completed_at' => $this->completed_at?->toISOString(),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }

    private function formatPersonName(?string $nombre, ?string $apellido, ?string $apodo): ?string
    {
        $fullName = trim((string) $nombre . ' ' . (string) $apellido);

        if ($fullName !== '') {
            return $fullName;
        }

        $apodo = trim((string) $apodo);

        return $apodo !== '' ? $apodo : null;
    }
}
