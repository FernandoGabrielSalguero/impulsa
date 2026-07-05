<?php

namespace App\Http\Requests\Admin;

use App\Support\MarketingLabels;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMarketingSubscriptionStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(MarketingLabels::subscriptionStatuses())],
        ];
    }
}
