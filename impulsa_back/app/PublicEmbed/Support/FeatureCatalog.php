<?php

namespace App\PublicEmbed\Support;

use App\Models\ApiIntegration;
use App\Models\Chatbot;
use App\Models\WebsiteSubscription;
use App\Services\WebsiteSubscription\WebsiteSubscriptionAccessService;
use App\Services\WebsiteSubscription\WebsiteSubscriptionPeriodService;
use Illuminate\Support\Facades\DB;

final class FeatureCatalog
{
    public function __construct(
        private readonly OwnerContactResolver $ownerContactResolver,
        private readonly WebsiteSubscriptionAccessService $accessService,
        private readonly WebsiteSubscriptionPeriodService $periodService,
    ) {}

    /** @return array<string, mixed> */
    public function bootstrap(ApiIntegration $integration): array
    {
        return [
            'integration' => [
                'id' => (int) $integration->id,
                'project_name' => (string) $integration->project_name,
                'status' => (string) $integration->status,
            ],
            'owner_contact' => $this->ownerContactResolver->resolve($integration),
            'features' => [
                'visits' => $this->visitsFeature(),
                'blog' => $this->blogFeature($integration),
                'products' => $this->productsFeature($integration),
                'chatbot' => $this->chatbotFeature($integration),
                'contact' => $this->contactFeature($integration),
                'subscription' => $this->subscriptionFeature($integration),
            ],
        ];
    }

    /** @return array<string, mixed> */
    private function visitsFeature(): array
    {
        return [
            'enabled' => true,
            'status' => 'ready',
        ];
    }

    /** @return array<string, mixed> */
    private function blogFeature(ApiIntegration $integration): array
    {
        $count = (int) DB::table('api_blog_posts')
            ->where('api_integration_id', $integration->id)
            ->where('status', 'active')
            ->count();

        return [
            'enabled' => true,
            'status' => $count > 0 ? 'ready' : 'inactive',
            'count' => $count,
        ];
    }

    /** @return array<string, mixed> */
    private function productsFeature(ApiIntegration $integration): array
    {
        $count = (int) DB::table('api_products')
            ->where('api_integration_id', $integration->id)
            ->where('status', 'active')
            ->count();

        return [
            'enabled' => true,
            'status' => $count > 0 ? 'ready' : 'inactive',
            'count' => $count,
        ];
    }

    /** @return array<string, mixed> */
    private function chatbotFeature(ApiIntegration $integration): array
    {
        $chatbot = Chatbot::query()
            ->withCount(['nodes'])
            ->where('api_integration_id', $integration->id)
            ->first();

        if ($chatbot === null) {
            return [
                'enabled' => false,
                'status' => 'inactive',
                'nodes_count' => 0,
            ];
        }

        $available = $chatbot->status === 'active'
            && ! $chatbot->disabled_by_admin
            && $integration->status === 'active';

        return [
            'enabled' => true,
            'status' => $available ? 'ready' : 'inactive',
            'nodes_count' => (int) $chatbot->nodes_count,
        ];
    }

    /** @return array<string, mixed> */
    private function contactFeature(ApiIntegration $integration): array
    {
        return [
            'enabled' => $integration->status === 'active',
            'status' => $integration->status === 'active' ? 'ready' : 'inactive',
        ];
    }

    /** @return array<string, mixed> */
    private function subscriptionFeature(ApiIntegration $integration): array
    {
        $subscription = WebsiteSubscription::query()
            ->with('mercadopagoPlan')
            ->where('api_integration_id', $integration->id)
            ->first();

        if ($subscription === null) {
            return [
                'enabled' => true,
                'status' => 'ready',
                'access_allowed' => true,
                'period' => now()->format('Y-m'),
                'subscription_status' => 'not_configured',
            ];
        }

        $this->periodService->ensureRollingPeriods($subscription);
        $period = $this->accessService->currentPeriod($subscription);

        if ($period !== null) {
            $period = $this->accessService->syncOverdueStatus($period);
        }

        $result = $this->accessService->evaluateAccess($subscription, $integration, $period);

        return [
            'enabled' => true,
            'status' => ($result['access_allowed'] ?? true) ? 'ready' : 'blocked',
            'access_allowed' => (bool) ($result['access_allowed'] ?? true),
            'period' => $result['period'] ?? now()->format('Y-m'),
            'subscription_status' => $result['status'] ?? 'pending',
            'payment_url' => $result['payment_url'] ?? null,
            'message' => $result['message'] ?? null,
        ];
    }
}
