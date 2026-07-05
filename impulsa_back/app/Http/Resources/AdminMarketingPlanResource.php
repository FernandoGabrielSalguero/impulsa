<?php

namespace App\Http\Resources;

use App\Models\MarketingPlan;
use App\Support\MarketingLabels;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminMarketingPlanResource extends JsonResource
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
            'is_visible_to_clients' => (bool) $plan->is_visible_to_clients,
            'status' => (string) $plan->status,
            'status_label' => MarketingLabels::planStatusLabel((string) $plan->status),
            'features_count' => (int) ($plan->features_count ?? $plan->features->count()),
            'pricing_options_count' => (int) ($plan->pricing_options_count ?? $plan->pricingOptions->count()),
            'subscriptions_count' => (int) ($plan->subscriptions_count ?? 0),
            'active_subscriptions_count' => (int) ($plan->active_subscriptions_count ?? 0),
            'has_mp_links' => $plan->relationLoaded('pricingOptions')
                ? $plan->pricingOptions->contains(static fn ($option): bool => $option->mercadopago_subscription_plan_id !== null)
                : null,
            'features' => AdminMarketingPlanFeatureResource::collection(
                $plan->relationLoaded('features') ? $plan->features : [],
            ),
            'pricing_options' => AdminMarketingPlanPricingOptionResource::collection(
                $plan->relationLoaded('pricingOptions') ? $plan->pricingOptions : [],
            ),
            'created_at' => $plan->created_at?->toISOString(),
            'updated_at' => $plan->updated_at?->toISOString(),
        ];
    }
}
