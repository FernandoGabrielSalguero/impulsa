<?php

namespace App\Http\Requests\Emprendedor;

use Illuminate\Foundation\Http\FormRequest;

class StoreFinanceScenarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:160'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_baseline' => ['nullable', 'boolean'],
            'months' => ['nullable', 'integer', 'min:1', 'max:24'],
            'baseline_monthly_income' => ['nullable', 'numeric', 'min:0', 'max:9999999999.99'],
            'baseline_monthly_expense' => ['nullable', 'numeric', 'min:0', 'max:9999999999.99'],
            'baseline_monthly_investment' => ['nullable', 'numeric', 'min:0', 'max:9999999999.99'],
            'fixed_costs_monthly' => ['nullable', 'numeric', 'min:0', 'max:9999999999.99'],
            'growth_income_percent' => ['nullable', 'numeric', 'min:-100', 'max:500'],
            'growth_expense_percent' => ['nullable', 'numeric', 'min:-100', 'max:500'],
            'opening_balance' => ['nullable', 'numeric', 'min:-9999999999.99', 'max:9999999999.99'],
            'include_fixed_costs' => ['nullable', 'boolean'],
            'seasonality' => ['nullable', 'array', 'size:12'],
            'seasonality.*' => ['numeric', 'min:0', 'max:5'],
            'price_change_percent' => ['nullable', 'numeric', 'min:-100', 'max:500'],
            'volume_change_percent' => ['nullable', 'numeric', 'min:-100', 'max:500'],
            'fixed_cost_change_percent' => ['nullable', 'numeric', 'min:-100', 'max:500'],
            'expense_change_percent' => ['nullable', 'numeric', 'min:-100', 'max:500'],
        ];
    }
}
