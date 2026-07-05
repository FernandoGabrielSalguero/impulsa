<?php

namespace App\Services\WebsiteSubscription;

use App\Models\MercadoPagoSubscriptionPlan;
use App\Models\WebsiteSubscription;

class WebsiteSubscriptionPaymentUrlService
{
    public function forSubscription(WebsiteSubscription $subscription): ?string
    {
        if ($subscription->mercadopago_subscription_plan_id !== null) {
            $plan = $subscription->relationLoaded('mercadopagoPlan')
                ? $subscription->mercadopagoPlan
                : MercadoPagoSubscriptionPlan::query()->find($subscription->mercadopago_subscription_plan_id);

            if ($plan !== null && $plan->status === 'active' && filled($plan->payment_url)) {
                return rtrim((string) $plan->payment_url, '/');
            }
        }

        $fallback = rtrim((string) config('mercadopago.subscription_plan_url'), '/');

        return $fallback !== '' ? $fallback : null;
    }

    public function planSummaryForSubscription(WebsiteSubscription $subscription): ?array
    {
        if ($subscription->mercadopago_subscription_plan_id === null) {
            return null;
        }

        $plan = $subscription->relationLoaded('mercadopagoPlan')
            ? $subscription->mercadopagoPlan
            : MercadoPagoSubscriptionPlan::query()->find($subscription->mercadopago_subscription_plan_id);

        if ($plan === null) {
            return null;
        }

        return [
            'id' => (int) $plan->id,
            'name' => $plan->name,
            'amount' => (float) $plan->amount,
            'payment_url' => rtrim((string) $plan->payment_url, '/'),
            'status' => $plan->status,
        ];
    }
}
