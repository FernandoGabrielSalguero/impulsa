<?php

namespace App\PublicEmbed\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ApiIntegration;
use App\PublicEmbed\Services\PublicSubscriptionService;
use App\PublicEmbed\Support\PublicResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicSubscriptionController extends Controller
{
    public function __construct(
        private readonly PublicSubscriptionService $subscriptionService,
    ) {}

    public function show(Request $request): JsonResponse
    {
        /** @var ApiIntegration $integration */
        $integration = $request->attributes->get('api_integration');

        $status = $this->subscriptionService->status($integration);

        return PublicResponse::success($status, ['feature' => 'subscription']);
    }
}
