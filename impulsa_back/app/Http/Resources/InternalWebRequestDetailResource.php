<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class InternalWebRequestDetailResource extends InternalWebRequestResource
{
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            'dominio_registrado' => (bool) $this->dominio_registrado,
            'hosting_propio' => (bool) $this->hosting_propio,
            'cantidad_colaboradores' => (int) $this->cantidad_colaboradores,
            'nombre_fundador' => $this->nombre_fundador,
            'vende_productos' => (bool) $this->vende_productos,
            'vende_servicios' => (bool) $this->vende_servicios,
            'ya_factura' => (bool) $this->ya_factura,
            'espacio_fisico' => (bool) $this->espacio_fisico,
            'pais' => $this->pais,
            'provincia' => $this->provincia,
            'localidad' => $this->localidad,
            'calle' => $this->calle,
            'numero' => $this->numero,
            'user_auth_id' => (int) $this->user_auth_id,
            'updated_at' => $this->updated_at?->toISOString(),
        ]);
    }
}
