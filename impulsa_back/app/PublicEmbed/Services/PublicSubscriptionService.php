<?php

namespace App\PublicEmbed\Services;

use App\Models\ApiIntegration;
use App\Models\WebsiteSubscription;
use App\PublicEmbed\Support\OwnerContactResolver;
use App\Services\WebsiteSubscription\SubscriptionNotificationService;
use App\Services\WebsiteSubscription\WebsiteSubscriptionAccessService;
use App\Services\WebsiteSubscription\WebsiteSubscriptionPeriodService;

class PublicSubscriptionService
{
    public function __construct(
        private readonly WebsiteSubscriptionAccessService $accessService,
        private readonly WebsiteSubscriptionPeriodService $periodService,
        private readonly SubscriptionNotificationService $notificationService,
        private readonly OwnerContactResolver $ownerContactResolver,
    ) {}

    /** @return array<string, mixed> */
    public function status(ApiIntegration $integration): array
    {
        $subscription = WebsiteSubscription::query()
            ->with('mercadopagoPlan')
            ->where('api_integration_id', $integration->id)
            ->first();

        $ownerContact = $this->ownerContactResolver->resolve($integration);

        if ($subscription === null) {
            return array_merge([
                'access_allowed' => true,
                'period' => now()->format('Y-m'),
                'status' => 'not_configured',
                'amount' => 0,
                'currency' => 'ARS',
                'payment_url' => config('mercadopago.subscription_plan_url'),
                'payment_plan' => null,
                'message' => 'Suscripción no configurada.',
                'block_day' => WebsiteSubscriptionAccessService::BLOCK_DAY,
                'day_of_month' => now()->day,
            ], [
                'owner_contact' => $ownerContact,
            ]);
        }

        $this->periodService->ensureRollingPeriods($subscription);
        $period = $this->accessService->currentPeriod($subscription);

        if ($period !== null) {
            $period = $this->accessService->syncOverdueStatus($period);
            $this->notificationService->maybeSendFirstBusinessDayNotice($subscription, $period, $integration);
        }

        $result = $this->accessService->evaluateAccess($subscription, $integration, $period);
        $result['owner_contact'] = $ownerContact;

        return $result;
    }
}
