<?php

namespace App\Services\WebsiteSubscription;

use App\Enums\WebsiteSubscriptionPeriodStatus;
use App\Models\ApiIntegration;
use App\Models\WebsiteSubscription;
use App\Models\WebsiteSubscriptionPeriod;
use Carbon\Carbon;

class WebsiteSubscriptionAccessService
{
    public const BLOCK_DAY = 15;

    public function __construct(
        private readonly WebsiteSubscriptionPaymentUrlService $paymentUrlService,
    ) {}

    public function currentPeriod(WebsiteSubscription $subscription, ?Carbon $now = null): ?WebsiteSubscriptionPeriod
    {
        $now ??= Carbon::now();

        return $subscription->periods()
            ->where('year', $now->year)
            ->where('month', $now->month)
            ->first();
    }

    public function evaluateAccess(
        WebsiteSubscription $subscription,
        ApiIntegration $integration,
        ?WebsiteSubscriptionPeriod $period = null,
        ?Carbon $now = null,
    ): array {
        $now ??= Carbon::now();
        $period ??= $this->currentPeriod($subscription, $now);

        if ($integration->status !== 'active') {
            return $this->buildResult(false, $period, $now, 'Integración inactiva.', $subscription);
        }

        if ($subscription->status === 'cancelled') {
            return $this->buildResult(false, $period, $now, 'Suscripción cancelada.', $subscription);
        }

        if ($subscription->status === 'paused') {
            return $this->buildResult(false, $period, $now, 'Suscripción pausada.', $subscription);
        }

        if ($period === null) {
            return $this->buildResult(true, null, $now, 'Sin período configurado para este mes.', $subscription);
        }

        $allowed = $this->isPeriodAccessAllowed($period, $now);

        return $this->buildResult(
            $allowed,
            $period,
            $now,
            $allowed
                ? 'Acceso permitido.'
                : 'Estamos experimentando inconvenientes técnicos. Contacte al administrador.',
            $subscription,
        );
    }

    public function isPeriodAccessAllowed(WebsiteSubscriptionPeriod $period, ?Carbon $now = null): bool
    {
        $now ??= Carbon::now();
        $status = WebsiteSubscriptionPeriodStatus::from($period->status);

        if (in_array($status, [
            WebsiteSubscriptionPeriodStatus::Paid,
            WebsiteSubscriptionPeriodStatus::Grace,
            WebsiteSubscriptionPeriodStatus::Waived,
        ], true)) {
            return true;
        }

        if ($now->day < self::BLOCK_DAY) {
            return true;
        }

        return false;
    }

    public function syncOverdueStatus(WebsiteSubscriptionPeriod $period, ?Carbon $now = null): WebsiteSubscriptionPeriod
    {
        $now ??= Carbon::now();

        if ($period->year !== $now->year || $period->month !== $now->month) {
            return $period;
        }

        $status = WebsiteSubscriptionPeriodStatus::from($period->status);

        if (in_array($status, [
            WebsiteSubscriptionPeriodStatus::Paid,
            WebsiteSubscriptionPeriodStatus::Grace,
            WebsiteSubscriptionPeriodStatus::Waived,
        ], true)) {
            return $period;
        }

        if ($now->day >= self::BLOCK_DAY && $status === WebsiteSubscriptionPeriodStatus::Pending) {
            $period->status = WebsiteSubscriptionPeriodStatus::Overdue->value;
            $period->save();
        }

        return $period;
    }

    /** @return array<string, mixed> */
    private function buildResult(
        bool $accessAllowed,
        ?WebsiteSubscriptionPeriod $period,
        Carbon $now,
        string $message,
        WebsiteSubscription $subscription,
    ): array {
        return [
            'access_allowed' => $accessAllowed,
            'period' => $period ? $period->periodKey() : sprintf('%04d-%02d', $now->year, $now->month),
            'status' => $period?->status ?? 'pending',
            'amount' => $period ? (float) $period->amount : 0.0,
            'currency' => 'ARS',
            'payment_url' => $this->paymentUrlService->forSubscription($subscription),
            'payment_plan' => $this->paymentUrlService->planSummaryForSubscription($subscription),
            'message' => $message,
            'block_day' => self::BLOCK_DAY,
            'day_of_month' => $now->day,
        ];
    }
}
