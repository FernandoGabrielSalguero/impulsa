<?php

namespace App\Http\Requests\Emprendedor;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFinancePricingItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:160'],
            'variable_cost' => ['sometimes', 'required', 'numeric', 'min:0', 'max:9999999999.99'],
            'extra_costs' => ['nullable', 'numeric', 'min:0', 'max:9999999999.99'],
            'mode' => ['sometimes', 'required', 'in:markup,margen'],
            'target_percent' => ['sometimes', 'required', 'numeric', 'min:0', 'max:99.99'],
            'notes' => ['nullable', 'string', 'max:500'],
            'product_id' => ['nullable', 'integer', 'min:1'],
            'competitors' => ['nullable', 'array', 'max:6'],
            'competitors.*.name' => ['nullable', 'string', 'max:120'],
            'competitors.*.price' => ['nullable', 'numeric', 'min:0', 'max:9999999999.99'],
            'competitors.*.description' => ['nullable', 'string', 'max:255'],
        ];
    }
}
