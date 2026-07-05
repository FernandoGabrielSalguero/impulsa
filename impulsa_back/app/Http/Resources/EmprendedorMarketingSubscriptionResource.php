<?php

namespace App\Http\Resources;

use App\Models\MarketingPlanSubscription;
use App\Support\MarketingLabels;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmprendedorMarketingSubscriptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var MarketingPlanSubscription $subscription */
        $subscription = $this->resource;
        $mpPlan = $subscription->relationLoaded('pricingOption')
            && $subscription->pricingOption?->relationLoaded('mercadopagoPlan')
            ? $subscription->pricingOption->mercadopagoPlan
            : null;

        return [
            'id' => (int) $subscription->id,
            'plan_id' => (int) $subscription->plan_id,
            'plan_name' => $subscription->relationLoaded('plan') ? (string) $subscription->plan->name : null,
            'pricing_option_id' => (int) $subscription->pricing_option_id,
            'status' => (string) $subscription->status,
            'status_label' => MarketingLabels::subscriptionStatusLabel((string) $subscription->status),
            'payment_status' => (string) $subscription->payment_status,
            'payment_required' => (bool) $subscription->payment_required,
            'duration_months' => (int) $subscription->duration_months,
            'monthly_price' => (float) $subscription->monthly_price,
            'total_contract_value' => (float) $subscription->total_contract_value,
            'monthly_ad_budget' => $subscription->monthly_ad_budget !== null
                ? (float) $subscription->monthly_ad_budget
                : null,
            'notes' => $subscription->notes,
            'payment_url' => $subscription->status === 'pending_payment'
                ? trim((string) ($subscription->payment_reference ?: $mpPlan?->payment_url))
                : null,
            'can_pay' => $subscription->status === 'pending_payment'
                && trim((string) ($subscription->payment_reference ?: $mpPlan?->payment_url)) !== '',
            'created_at' => $subscription->created_at?->toISOString(),
            'updated_at' => $subscription->updated_at?->toISOString(),
        ];
    }
}
