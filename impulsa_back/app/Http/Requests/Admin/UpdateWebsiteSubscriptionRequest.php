<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWebsiteSubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'default_amount' => ['required', 'numeric', 'min:0'],
            'grace_months_count' => ['nullable', 'integer', 'min:0', 'max:24'],
            'mercadopago_subscription_plan_id' => ['nullable', 'integer', 'min:1'],
            'status' => ['required', Rule::in(['active', 'paused', 'cancelled'])],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('mercadopago_subscription_plan_id') && $this->input('mercadopago_subscription_plan_id') === '') {
            $this->merge(['mercadopago_subscription_plan_id' => null]);
        }
    }
}
