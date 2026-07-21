<?php

namespace App\Http\Requests\Emprendedor;

use Illuminate\Foundation\Http\FormRequest;

class FinanceScenarioPreviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $payload = [];

        foreach ([
            'months',
            'baseline_monthly_income',
            'baseline_monthly_expense',
            'baseline_monthly_investment',
            'fixed_costs_monthly',
            'growth_income_percent',
            'growth_expense_percent',
            'opening_balance',
            'price_change_percent',
            'volume_change_percent',
            'fixed_cost_change_percent',
            'expense_change_percent',
        ] as $field) {
            if (! $this->exists($field)) {
                continue;
            }

            $value = $this->input($field);

            if ($value === '' || $value === null) {
                $payload[$field] = null;
                continue;
            }

            if (is_numeric($value)) {
                $payload[$field] = $field === 'months' ? (int) $value : (float) $value;
            }
        }

        if (array_key_exists('months', $payload) && ($payload['months'] === null || (int) $payload['months'] < 1)) {
            $payload['months'] = 6;
        }

        if ($payload !== []) {
            $this->merge($payload);
        }
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
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
