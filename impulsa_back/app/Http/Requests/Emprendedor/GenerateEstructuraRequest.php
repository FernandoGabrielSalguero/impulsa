<?php

namespace App\Http\Requests\Emprendedor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GenerateEstructuraRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'type' => ['required', 'string', Rule::in(['mision', 'vision', 'buyer_persona'])],
            'fields' => ['required', 'array'],
            'prefer_ai' => ['nullable', 'boolean'],
        ];
    }
}
