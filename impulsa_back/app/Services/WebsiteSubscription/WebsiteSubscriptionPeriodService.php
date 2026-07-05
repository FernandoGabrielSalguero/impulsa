<?php

namespace App\Services\WebsiteSubscription;

use App\Enums\WebsiteSubscriptionPeriodStatus;
use App\Models\WebsiteSubscription;
use App\Models\WebsiteSubscriptionPeriod;
use Carbon\Carbon;

class WebsiteSubscriptionPeriodService
{
    public function __construct(
        private readonly WebsiteSubscriptionAccessService $accessService,
    ) {}

    public function ensureRollingPeriods(WebsiteSubscription $subscription, ?Carbon $from = null): void
    {
        $from ??= Carbon::now()->startOfMonth();
        $end = $from->copy()->addMonths(11);

        $cursor = $from->copy();

        while ($cursor->lte($end)) {
            $this->findOrCreatePeriod($subscription, $cursor->year, $cursor->month);
            $cursor->addMonth();
        }
    }

    public function applyGraceMonths(WebsiteSubscription $subscription, int $graceMonths, ?Carbon $from = null): void
    {
        if ($graceMonths <= 0) {
            return;
        }

        $from ??= Carbon::now()->startOfMonth();
        $cursor = $from->copy();

        for ($i = 0; $i < $graceMonths; $i++) {
            $period = $this->findOrCreatePeriod($subscription, $cursor->year, $cursor->month);

            if ($period->status === WebsiteSubscriptionPeriodStatus::Pending->value) {
                $period->status = WebsiteSubscriptionPeriodStatus::Grace->value;
                $period->save();
            }

            $cursor->addMonth();
        }
    }

    public function findOrCreatePeriod(
        WebsiteSubscription $subscription,
        int $year,
        int $month,
    ): WebsiteSubscriptionPeriod {
        $period = WebsiteSubscriptionPeriod::query()->firstOrCreate(
            [
                'website_subscription_id' => $subscription->id,
                'year' => $year,
                'month' => $month,
            ],
            [
                'amount' => $subscription->default_amount,
                'status' => WebsiteSubscriptionPeriodStatus::Pending->value,
            ],
        );

        $this->accessService->syncOverdueStatus($period);

        return $period;
    }

    public function markPeriodPaid(
        WebsiteSubscriptionPeriod $period,
        ?string $mercadopagoPaymentId = null,
        ?Carbon $paidAt = null,
    ): WebsiteSubscriptionPeriod {
        $period->status = WebsiteSubscriptionPeriodStatus::Paid->value;
        $period->paid_at = $paidAt ?? Carbon::now();
        $period->mercadopago_payment_id = $mercadopagoPaymentId ?? $period->mercadopago_payment_id;
        $period->save();

        return $period;
    }
}
