<?php

namespace App\Http\Requests\Emprendedor;

use Illuminate\Foundation\Http\FormRequest;

class SaveMisionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'a_quien_ayudo' => ['required', 'string', 'max:255'],
            'que_problema_resuelvo' => ['required', 'string'],
            'como_lo_resuelvo' => ['required', 'string'],
            'mision_estructura' => ['nullable', 'string'],
        ];
    }
}
