<?php

namespace App\Http\Requests\Emprendedor;

use Illuminate\Foundation\Http\FormRequest;

class SaveBuyerPersonaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'cliente_ideal' => ['nullable', 'string'],
            'edad_etapa_vida' => ['nullable', 'string'],
            'ocupacion_realidad_diaria' => ['nullable', 'string'],
            'problema_necesidad' => ['nullable', 'string'],
            'preocupacion_frustracion' => ['nullable', 'string'],
            'objetivo_mejora' => ['nullable', 'string'],
            'motivacion_busqueda' => ['nullable', 'string'],
            'freno_dudas' => ['nullable', 'string'],
            'criterio_eleccion' => ['nullable', 'string'],
            'busqueda_informacion' => ['nullable', 'string'],
            'decision_compra' => ['nullable', 'string'],
            'motivo_eleccion' => ['nullable', 'string'],
            'buyer_persona_estructura' => ['nullable', 'string'],
        ];
    }
}
