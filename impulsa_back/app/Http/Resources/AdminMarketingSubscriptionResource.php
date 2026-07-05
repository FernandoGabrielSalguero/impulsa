<?php

namespace App\Http\Resources;

use App\Support\MarketingLabels;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminMarketingSubscriptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => (int) $this->value('id'),
            'plan_id' => (int) $this->value('plan_id'),
            'plan_name' => (string) $this->value('plan_name'),
            'plan_slug' => (string) $this->value('plan_slug'),
            'plan_status' => (string) $this->value('plan_status'),
            'pricing_option_id' => (int) $this->value('pricing_option_id'),
            'pricing_duration_months' => (int) $this->value('pricing_duration_months'),
            'pricing_currency' => (string) ($this->value('pricing_currency') ?? 'ARS'),
            'user_name' => $this->resolveUserName(),
            'user_email' => $this->resolveUserEmail(),
            'user_type' => $this->value('client_user_id') ? 'cliente' : 'emprendedor',
            'status' => (string) $this->value('status'),
            'status_label' => MarketingLabels::subscriptionStatusLabel((string) $this->value('status')),
            'payment_status' => (string) $this->value('payment_status'),
            'payment_status_label' => MarketingLabels::paymentStatusLabel((string) $this->value('payment_status')),
            'payment_provider' => $this->value('payment_provider'),
            'payment_reference' => $this->value('payment_reference'),
            'payment_required' => (bool) $this->value('payment_required', false),
            'payment_url' => $this->value('payment_reference'),
            'duration_months' => (int) $this->value('duration_months'),
            'monthly_price' => (float) $this->value('monthly_price'),
            'total_contract_value' => (float) $this->value('total_contract_value'),
            'monthly_ad_budget' => $this->value('monthly_ad_budget') !== null
                ? (float) $this->value('monthly_ad_budget')
                : null,
            'notes' => $this->value('notes'),
            'mercadopago_plan' => $this->value('mercadopago_plan_id') ? [
                'id' => (int) $this->value('mercadopago_plan_id'),
                'name' => (string) $this->value('mercadopago_plan_name'),
                'amount' => $this->value('mercadopago_plan_amount') !== null
                    ? (float) $this->value('mercadopago_plan_amount')
                    : null,
                'payment_url' => $this->value('mercadopago_plan_payment_url'),
            ] : null,
            'start_date' => $this->formatDate($this->value('start_date'), dateOnly: true),
            'end_date' => $this->formatDate($this->value('end_date'), dateOnly: true),
            'activated_at' => $this->formatDate($this->value('activated_at')),
            'created_at' => $this->formatDate($this->value('created_at')),
            'updated_at' => $this->formatDate($this->value('updated_at')),
        ];
    }

    private function resolveUserName(): string
    {
        if ($this->value('client_user_id')) {
            $nombre = trim((string) $this->value('client_nombre') . ' ' . (string) $this->value('client_apellido'));

            return $nombre !== '' ? $nombre : $this->resolveUserEmail();
        }

        $nombre = trim((string) $this->value('entrepreneur_nombre') . ' ' . (string) $this->value('entrepreneur_apellido'));

        return $nombre !== '' ? $nombre : $this->resolveUserEmail();
    }

    private function resolveUserEmail(): string
    {
        if ($this->value('client_user_id')) {
            $contacto = trim((string) $this->value('client_contacto_correo'));

            return $contacto !== '' ? $contacto : trim((string) $this->value('client_auth_correo'));
        }

        $contacto = trim((string) $this->value('entrepreneur_contacto_correo'));

        return $contacto !== '' ? $contacto : trim((string) $this->value('entrepreneur_auth_correo'));
    }

    private function value(string $key, mixed $default = null): mixed
    {
        $resource = $this->resource;

        if (is_object($resource) && property_exists($resource, $key)) {
            return $resource->{$key} ?? $default;
        }

        if (is_array($resource) && array_key_exists($key, $resource)) {
            return $resource[$key];
        }

        return $default;
    }

    private function formatDate(mixed $value, bool $dateOnly = false): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return $dateOnly ? $value->format('Y-m-d') : $value->format(DATE_ATOM);
        }

        try {
            $date = Carbon::parse((string) $value);

            return $dateOnly ? $date->format('Y-m-d') : $date->toISOString();
        } catch (\Throwable) {
            return null;
        }
    }
}
