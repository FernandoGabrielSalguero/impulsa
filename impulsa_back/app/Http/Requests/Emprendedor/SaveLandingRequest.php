<?php

namespace App\Http\Requests\Emprendedor;

use Illuminate\Foundation\Http\FormRequest;

class SaveLandingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'nombre_emprendimiento' => ['required', 'string', 'max:255'],
            'fecha_inicio' => ['required', 'date'],
            'descripcion' => ['required', 'string'],
            'dominio_registrado' => ['nullable', 'boolean'],
            'hosting_propio' => ['nullable', 'boolean'],
            'cantidad_colaboradores' => ['nullable', 'integer', 'min:1'],
            'nombre_fundador' => ['required', 'string', 'max:255'],
            'vende_productos' => ['nullable', 'boolean'],
            'vende_servicios' => ['nullable', 'boolean'],
            'ya_factura' => ['nullable', 'boolean'],
            'espacio_fisico' => ['nullable', 'boolean'],
            'rubro_categoria_id' => ['nullable', 'integer', 'min:1'],
            'rubro_subcategoria_id' => ['nullable', 'integer', 'min:1'],
            'pais' => ['nullable', 'string', 'max:100'],
            'provincia' => ['nullable', 'string', 'max:100'],
            'localidad' => ['nullable', 'string', 'max:100'],
            'calle' => ['nullable', 'string', 'max:255'],
            'numero' => ['nullable', 'string', 'max:20'],
            'telefono_contacto' => ['required', 'string', 'max:30'],
        ];
    }
}
