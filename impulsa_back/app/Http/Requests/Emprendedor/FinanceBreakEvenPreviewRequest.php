<?php

namespace App\Http\Requests\Emprendedor;

use Illuminate\Foundation\Http\FormRequest;

class FinanceBreakEvenPreviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'price' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'variable_cost' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'fixed_costs_monthly' => ['nullable', 'numeric', 'min:0', 'max:9999999999.99'],
            'current_sales_revenue' => ['nullable', 'numeric', 'min:0', 'max:9999999999.99'],
        ];
    }
}
