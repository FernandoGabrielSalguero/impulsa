<?php

namespace App\Http\Resources;

use App\Models\MarketingPlanPricingOption;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminMarketingPlanPricingOptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var MarketingPlanPricingOption $option */
        $option = $this->resource;
        $mpPlan = $option->relationLoaded('mercadopagoPlan') ? $option->mercadopagoPlan : null;

        return [
            'id' => (int) $option->id,
            'duration_months' => (int) $option->duration_months,
            'monthly_price' => (float) $option->monthly_price,
            'total_price' => (float) $option->total_price,
            'setup_fee' => (float) $option->setup_fee,
            'currency' => (string) $option->currency,
            'is_featured' => (bool) $option->is_featured,
            'is_default' => (bool) $option->is_default,
            'display_order' => (int) $option->display_order,
            'mercadopago_subscription_plan_id' => $option->mercadopago_subscription_plan_id
                ? (int) $option->mercadopago_subscription_plan_id
                : null,
            'mercadopago_plan' => $mpPlan !== null ? [
                'id' => (int) $mpPlan->id,
                'name' => (string) $mpPlan->name,
                'amount' => (float) $mpPlan->amount,
                'payment_url' => (string) $mpPlan->payment_url,
                'status' => (string) $mpPlan->status,
            ] : null,
        ];
    }
}
