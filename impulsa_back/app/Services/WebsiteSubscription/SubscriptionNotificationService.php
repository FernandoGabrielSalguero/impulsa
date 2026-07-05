<?php

namespace App\Services\WebsiteSubscription;

use App\Mail\SubscriptionMonthlyNoticeMail;
use App\Mail\SubscriptionPaymentReminderMail;
use App\Models\ApiIntegration;
use App\Models\UserAuth;
use App\Models\WebsiteSubscription;
use App\Models\WebsiteSubscriptionPeriod;
use App\Services\Mail\ImpulsaMailService;
use App\Support\BusinessDayHelper;
use Carbon\Carbon;

class SubscriptionNotificationService
{
    public function __construct(
        private readonly ImpulsaMailService $mailService,
        private readonly WebsiteSubscriptionPaymentUrlService $paymentUrlService,
    ) {}

    public function maybeSendFirstBusinessDayNotice(
        WebsiteSubscription $subscription,
        WebsiteSubscriptionPeriod $period,
        ApiIntegration $integration,
    ): void {
        $now = Carbon::now();

        if (! BusinessDayHelper::isFirstBusinessDayOfMonth($now)) {
            return;
        }

        if ($period->first_notice_sent_at !== null) {
            return;
        }

        if (in_array($period->status, ['paid', 'grace', 'waived'], true)) {
            $period->first_notice_sent_at = $now;
            $period->save();

            return;
        }

        $owner = $this->resolveVerifiedOwner($integration);

        if ($owner === null) {
            return;
        }

        $sent = $this->mailService->send(new SubscriptionMonthlyNoticeMail(
            user: $owner,
            subscription: $subscription,
            period: $period,
            integration: $integration,
            paymentUrl: $this->paymentUrlService->forSubscription($subscription),
        ));

        if ($sent) {
            $period->first_notice_sent_at = $now;
            $period->save();
        }
    }

    public function sendDueReminders(): int
    {
        $now = Carbon::now();
        $sentCount = 0;

        if ($now->day < 6 || $now->day >= 15 || $now->day % 2 !== 0) {
            return 0;
        }

        $periods = WebsiteSubscriptionPeriod::query()
            ->where('year', $now->year)
            ->where('month', $now->month)
            ->whereIn('status', ['pending', 'overdue'])
            ->get();

        foreach ($periods as $period) {
            if ($this->shouldSkipReminder($period, $now)) {
                continue;
            }

            $subscription = WebsiteSubscription::query()->find($period->website_subscription_id);

            if ($subscription === null || $subscription->status !== 'active') {
                continue;
            }

            $integration = ApiIntegration::query()->find($subscription->api_integration_id);

            if ($integration === null) {
                continue;
            }

            $owner = $this->resolveVerifiedOwner($integration);

            if ($owner === null) {
                continue;
            }

            $sent = $this->mailService->send(new SubscriptionPaymentReminderMail(
                user: $owner,
                subscription: $subscription,
                period: $period,
                integration: $integration,
                paymentUrl: $this->paymentUrlService->forSubscription($subscription),
            ));

            if ($sent) {
                $period->last_reminder_sent_at = $now;
                $period->save();
                $sentCount++;
            }
        }

        return $sentCount;
    }

    private function shouldSkipReminder(WebsiteSubscriptionPeriod $period, Carbon $now): bool
    {
        if ($period->last_reminder_sent_at === null) {
            return false;
        }

        return $period->last_reminder_sent_at->isSameDay($now);
    }

    private function resolveVerifiedOwner(ApiIntegration $integration): ?UserAuth
    {
        if ($integration->user_auth_id === null) {
            return null;
        }

        $owner = UserAuth::query()->find($integration->user_auth_id);

        if ($owner === null || $owner->email_verified_at === null) {
            return null;
        }

        return $owner;
    }
}
