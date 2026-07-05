<?php

namespace App\Http\Requests\Emprendedor;

use Illuminate\Foundation\Http\FormRequest;

class SaveVisionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'conversion_futura' => ['required', 'string'],
            'lugar_mercado' => ['required', 'string'],
            'impacto_generado' => ['required', 'string'],
            'vision_estructura' => ['nullable', 'string'],
        ];
    }
}
