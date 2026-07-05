<?php

namespace App\Http\Controllers\Api\V1\Emprendedor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Emprendedor\StoreMarketingSubscriptionRequest;
use App\Http\Resources\EmprendedorMarketingPlanResource;
use App\Http\Resources\EmprendedorMarketingSubscriptionResource;
use App\Models\MarketingPlanSubscription;
use App\Services\Emprendedor\EmprendedorMarketingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MarketingController extends Controller
{
    public function __construct(
        private readonly EmprendedorMarketingService $marketingService,
    ) {}

    public function plans(): JsonResponse
    {
        return response()->json([
            'data' => EmprendedorMarketingPlanResource::collection($this->marketingService->listPlans()),
        ]);
    }

    public function showPlan(int $planId): JsonResponse
    {
        return response()->json([
            'data' => new EmprendedorMarketingPlanResource($this->marketingService->findPlan($planId)),
        ]);
    }

    public function subscriptions(Request $request): JsonResponse
    {
        return response()->json([
            'data' => EmprendedorMarketingSubscriptionResource::collection(
                $this->marketingService->listSubscriptions($request->user()),
            ),
        ]);
    }

    public function storeSubscription(StoreMarketingSubscriptionRequest $request): JsonResponse
    {
        $subscription = $this->marketingService->requestSubscription(
            $request->user(),
            $request->validated(),
        );

        return response()->json([
            'message' => 'Solicitud enviada correctamente. Te contactaremos para continuar el proceso.',
            'subscription' => new EmprendedorMarketingSubscriptionResource(
                $subscription->load(['plan', 'pricingOption.mercadopagoPlan']),
            ),
        ], 201);
    }

    public function paymentUrl(Request $request, MarketingPlanSubscription $marketingPlanSubscription): JsonResponse
    {
        $url = $this->marketingService->paymentUrl($marketingPlanSubscription, $request->user());

        return response()->json([
            'payment_url' => $url,
        ]);
    }
}
