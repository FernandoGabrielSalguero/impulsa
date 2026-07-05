<?php

namespace App\Services\Emprendedor;

use App\Models\UserAuth;
use App\Models\WebsiteSubscription;
use App\Support\EmprendedorIntegrationAccess;
use Illuminate\Validation\ValidationException;

class EmprendedorWebsiteSubscriptionService
{
    public function __construct(
        private readonly EmprendedorIntegrationAccess $integrationAccess,
    ) {}

    public function show(UserAuth $user): WebsiteSubscription
    {
        $integration = $this->integrationAccess->requireIntegration($user);

        $subscription = WebsiteSubscription::query()
            ->with(['periods', 'mercadopagoPlan', 'apiIntegration'])
            ->where('api_integration_id', $integration->id)
            ->first();

        if ($subscription === null) {
            throw ValidationException::withMessages([
                'subscription' => ['No tenés una suscripción web configurada.'],
            ]);
        }

        return $subscription;
    }
}
