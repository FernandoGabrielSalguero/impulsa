<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class ExternalWebRequestDetailResource extends ExternalWebRequestResource
{
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            'q1_nombre_comercial' => $this->q1_nombre_comercial,
            'q2_actividad' => $this->q2_actividad,
            'q3_objetivo' => $this->q3_objetivo,
            'q4_publico' => $this->q4_publico,
            'q5_accion_principal' => $this->q5_accion_principal,
            'q6_propuestas_destacar' => $this->q6_propuestas_destacar,
            'q7_diferencial' => $this->q7_diferencial,
            'q8_secciones' => $this->q8_secciones,
            'q9_textos' => $this->q9_textos,
            'q10_contacto' => $this->q10_contacto,
            'q11_material_marca' => $this->q11_material_marca,
            'q12_estilo_visual' => $this->q12_estilo_visual,
            'q13_referencias' => $this->q13_referencias,
            'q14_recursos_visuales' => $this->q14_recursos_visuales,
            'q15_imagenes_apoyo' => $this->q15_imagenes_apoyo,
            'q16_dominio_hosting' => $this->q16_dominio_hosting,
            'q17_correos_corporativos' => $this->q17_correos_corporativos,
            'q18_requerimientos_adicionales' => $this->q18_requerimientos_adicionales,
            'ip_address' => $this->ip_address,
            'user_agent' => $this->user_agent,
        ]);
    }
}
