<?php

namespace App\Http\Requests\Admin;

use App\Support\MarketingLabels;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMarketingPlanRequest extends FormRequest
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
            'slug' => ['nullable', 'string', 'max:180'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'full_description' => ['nullable', 'string'],
            'objective' => ['nullable', 'string', 'max:120'],
            'recommended_ad_budget_min' => ['nullable', 'numeric', 'min:0'],
            'recommended_ad_budget_max' => ['nullable', 'numeric', 'min:0'],
            'setup_fee' => ['nullable', 'numeric', 'min:0'],
            'billing_period' => ['nullable', 'string', 'max:40'],
            'report_frequency' => ['nullable', 'string', 'max:60'],
            'support_level' => ['nullable', 'string', 'max:80'],
            'is_visible_to_clients' => ['nullable', 'boolean'],
            'status' => ['required', Rule::in(MarketingLabels::planStatuses())],
            'features' => ['nullable', 'array'],
            'features.*.id' => ['nullable', 'integer', 'min:1'],
            'features.*.feature_name' => ['required_with:features', 'string', 'max:180'],
            'features.*.feature_description' => ['nullable', 'string', 'max:255'],
            'features.*.quantity' => ['nullable', 'numeric', 'min:0'],
            'features.*.unit' => ['nullable', 'string', 'max:60'],
            'features.*.feature_order' => ['nullable', 'integer', 'min:0'],
            'features.*.is_highlighted' => ['nullable', 'boolean'],
            'pricing_options' => ['nullable', 'array', 'min:1'],
            'pricing_options.*.id' => ['nullable', 'integer', 'min:1'],
            'pricing_options.*.duration_months' => ['required_with:pricing_options', 'integer', 'min:1'],
            'pricing_options.*.monthly_price' => ['required_with:pricing_options', 'numeric', 'min:0.01'],
            'pricing_options.*.setup_fee' => ['nullable', 'numeric', 'min:0'],
            'pricing_options.*.currency' => ['nullable', 'string', 'max:8'],
            'pricing_options.*.mercadopago_subscription_plan_id' => ['nullable', 'integer', 'exists:mercadopago_subscription_plans,id'],
            'pricing_options.*.is_featured' => ['nullable', 'boolean'],
            'pricing_options.*.is_default' => ['nullable', 'boolean'],
            'pricing_options.*.display_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
