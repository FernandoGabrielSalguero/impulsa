<?php

namespace App\Http\Requests\Emprendedor;

use Illuminate\Foundation\Http\FormRequest;

class FinancePricingPreviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'variable_cost' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'extra_costs' => ['nullable', 'numeric', 'min:0', 'max:9999999999.99'],
            'mode' => ['required', 'in:markup,margen'],
            'target_percent' => ['required', 'numeric', 'min:0', 'max:99.99'],
        ];
    }
}
