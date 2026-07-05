<?php

namespace App\Http\Resources;

use App\Models\WebsiteSubscription;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmprendedorWebsiteSubscriptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var WebsiteSubscription $subscription */
        $subscription = $this->resource;

        return [
            'id' => (int) $subscription->id,
            'status' => (string) $subscription->status,
            'default_amount' => (float) $subscription->default_amount,
            'grace_months_count' => (int) $subscription->grace_months_count,
            'notes' => $subscription->notes,
            'mercadopago_plan' => $subscription->relationLoaded('mercadopagoPlan') && $subscription->mercadopagoPlan !== null
                ? [
                    'id' => (int) $subscription->mercadopagoPlan->id,
                    'name' => (string) $subscription->mercadopagoPlan->name,
                    'amount' => (float) $subscription->mercadopagoPlan->amount,
                ]
                : null,
            'integration' => $subscription->relationLoaded('apiIntegration') && $subscription->apiIntegration !== null
                ? [
                    'id' => (int) $subscription->apiIntegration->id,
                    'project_name' => (string) $subscription->apiIntegration->project_name,
                    'allowed_domain' => (string) $subscription->apiIntegration->allowed_domain,
                ]
                : null,
            'periods' => $subscription->relationLoaded('periods')
                ? $subscription->periods->map(static fn ($period): array => [
                    'id' => (int) $period->id,
                    'year' => (int) $period->year,
                    'month' => (int) $period->month,
                    'amount' => (float) $period->amount,
                    'status' => (string) $period->status,
                    'paid_at' => $period->paid_at?->toISOString(),
                ])->values()->all()
                : [],
        ];
    }
}
