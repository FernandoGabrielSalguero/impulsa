<?php

namespace App\Http\Resources;

use App\Models\WebsiteSubscription;
use App\Support\WebsiteSubscriptionLabels;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminWebsiteSubscriptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => (int) $this->value('id'),
            'api_integration_id' => (int) $this->value('api_integration_id'),
            'project_name' => $this->value('project_name') ?? $this->relatedModel()?->apiIntegration?->project_name,
            'allowed_domain' => $this->value('allowed_domain') ?? $this->relatedModel()?->apiIntegration?->allowed_domain,
            'public_key' => $this->value('public_key') ?? $this->relatedModel()?->apiIntegration?->public_key,
            'integration_status' => $this->value('integration_status') ?? $this->relatedModel()?->apiIntegration?->status,
            'status' => (string) $this->value('status'),
            'status_label' => WebsiteSubscriptionLabels::subscriptionStatusLabel((string) $this->value('status')),
            'default_amount' => (float) $this->value('default_amount', 0),
            'grace_months_count' => (int) $this->value('grace_months_count', 0),
            'mercadopago_preapproval_id' => $this->value('mercadopago_preapproval_id'),
            'mercadopago_subscription_plan_id' => $this->value('mercadopago_subscription_plan_id')
                ? (int) $this->value('mercadopago_subscription_plan_id')
                : null,
            'mercadopago_plan' => $this->formatPlan(),
            'notes' => $this->value('notes'),
            'owner_name' => $this->value('owner_name'),
            'owner_email' => $this->value('owner_email'),
            'current_period_status' => $this->value('current_period_status'),
            'current_period_status_label' => $this->value('current_period_status')
                ? WebsiteSubscriptionLabels::periodStatusLabel((string) $this->value('current_period_status'))
                : null,
            'current_period_amount' => $this->value('current_period_amount') !== null
                ? (float) $this->value('current_period_amount')
                : null,
            'access_allowed' => (bool) $this->value('access_allowed', false),
            'payment_url' => $this->resolvePaymentUrl(),
            'created_at' => $this->formatDate($this->value('created_at')),
            'updated_at' => $this->formatDate($this->value('updated_at')),
        ];
    }

    /** @return array<string, mixed>|null */
    private function formatPlan(): ?array
    {
        $model = $this->relatedModel();
        $plan = $model?->mercadopagoPlan;

        if ($plan !== null) {
            return [
                'id' => (int) $plan->id,
                'name' => $plan->name,
                'amount' => (float) $plan->amount,
                'payment_url' => $plan->payment_url,
                'status' => $plan->status,
            ];
        }

        if ($this->value('mercadopago_plan_name')) {
            return [
                'id' => $this->value('mercadopago_subscription_plan_id')
                    ? (int) $this->value('mercadopago_subscription_plan_id')
                    : null,
                'name' => $this->value('mercadopago_plan_name'),
                'amount' => $this->value('mercadopago_plan_amount') !== null
                    ? (float) $this->value('mercadopago_plan_amount')
                    : null,
                'payment_url' => $this->value('mercadopago_plan_payment_url'),
                'status' => null,
            ];
        }

        return null;
    }

    private function resolvePaymentUrl(): ?string
    {
        $explicit = $this->value('payment_url');

        if (is_string($explicit) && $explicit !== '') {
            return $explicit;
        }

        $planUrl = $this->value('mercadopago_plan_payment_url');

        if (is_string($planUrl) && $planUrl !== '') {
            return $planUrl;
        }

        $model = $this->relatedModel();

        if ($model !== null) {
            $fromService = app(\App\Services\WebsiteSubscription\WebsiteSubscriptionPaymentUrlService::class)
                ->forSubscription($model);

            if ($fromService !== null) {
                return $fromService;
            }
        }

        $fallback = config('mercadopago.subscription_plan_url');

        return is_string($fallback) && $fallback !== '' ? $fallback : null;
    }

    private function relatedModel(): ?WebsiteSubscription
    {
        return $this->resource instanceof WebsiteSubscription ? $this->resource : null;
    }

    private function value(string $key, mixed $default = null): mixed
    {
        $resource = $this->resource;

        if ($resource instanceof WebsiteSubscription) {
            if ($resource->offsetExists($key)) {
                return $resource->getAttribute($key) ?? $default;
            }

            return $default;
        }

        if (is_object($resource) && property_exists($resource, $key)) {
            return $resource->{$key} ?? $default;
        }

        if (is_array($resource) && array_key_exists($key, $resource)) {
            return $resource[$key];
        }

        return $default;
    }

    private function formatDate(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format(DATE_ATOM);
        }

        try {
            return Carbon::parse((string) $value)->toISOString();
        } catch (\Throwable) {
            return null;
        }
    }
}
