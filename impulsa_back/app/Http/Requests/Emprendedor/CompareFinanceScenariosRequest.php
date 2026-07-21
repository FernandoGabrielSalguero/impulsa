<?php

namespace App\Http\Requests\Emprendedor;

use Illuminate\Foundation\Http\FormRequest;

class CompareFinanceScenariosRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'scenario_ids' => ['required', 'array', 'min:2', 'max:4'],
            'scenario_ids.*' => ['integer', 'min:1'],
        ];
    }
}
