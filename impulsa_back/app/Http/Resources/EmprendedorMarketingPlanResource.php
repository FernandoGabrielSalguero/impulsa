<?php

namespace App\Http\Resources;

use App\Models\MarketingPlan;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmprendedorMarketingPlanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var MarketingPlan $plan */
        $plan = $this->resource;

        return [
            'id' => (int) $plan->id,
            'name' => (string) $plan->name,
            'slug' => (string) $plan->slug,
            'short_description' => $plan->short_description,
            'full_description' => $plan->full_description,
            'objective' => $plan->objective,
            'recommended_ad_budget_min' => $plan->recommended_ad_budget_min !== null
                ? (float) $plan->recommended_ad_budget_min
                : null,
            'recommended_ad_budget_max' => $plan->recommended_ad_budget_max !== null
                ? (float) $plan->recommended_ad_budget_max
                : null,
            'setup_fee' => (float) $plan->setup_fee,
            'billing_period' => (string) $plan->billing_period,
            'report_frequency' => $plan->report_frequency,
            'support_level' => $plan->support_level,
            'features' => AdminMarketingPlanFeatureResource::collection(
                $plan->relationLoaded('features') ? $plan->features : [],
            ),
            'pricing_options' => AdminMarketingPlanPricingOptionResource::collection(
                $plan->relationLoaded('pricingOptions') ? $plan->pricingOptions : [],
            ),
        ];
    }
}
