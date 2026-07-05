<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateMarketingSubscriptionStatusRequest;
use App\Http\Resources\AdminMarketingSubscriptionCollection;
use App\Http\Resources\AdminMarketingSubscriptionResource;
use App\Models\MarketingPlanSubscription;
use App\Services\Admin\MarketingSubscriptionAdminService;
use App\Support\MarketingLabels;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MarketingSubscriptionController extends Controller
{
    public function __construct(
        private readonly MarketingSubscriptionAdminService $marketingSubscriptionAdminService,
    ) {}

    public function options(): JsonResponse
    {
        return response()->json([
            'statuses' => collect(MarketingLabels::subscriptionStatuses())->map(static fn (string $value): array => [
                'value' => $value,
                'label' => MarketingLabels::subscriptionStatusLabel($value),
            ])->values(),
        ]);
    }

    public function index(Request $request): AdminMarketingSubscriptionCollection
    {
        $perPage = max(1, min((int) $request->integer('per_page', 20), 100));
        $planId = $request->filled('plan_id') ? (int) $request->integer('plan_id') : null;

        $result = $this->marketingSubscriptionAdminService->list(
            $request->query('q'),
            $request->query('status'),
            $planId,
            $perPage,
        );

        return new AdminMarketingSubscriptionCollection($result['data']);
    }

    public function show(MarketingPlanSubscription $marketingPlanSubscription): AdminMarketingSubscriptionResource
    {
        return new AdminMarketingSubscriptionResource(
            $this->marketingSubscriptionAdminService->find((int) $marketingPlanSubscription->id),
        );
    }

    public function updateStatus(
        UpdateMarketingSubscriptionStatusRequest $request,
        MarketingPlanSubscription $marketingPlanSubscription,
    ): JsonResponse {
        $updated = $this->marketingSubscriptionAdminService->updateStatus(
            $marketingPlanSubscription,
            (string) $request->validated('status'),
            (int) $request->user()->id,
        );

        return response()->json([
            'message' => 'Estado de la suscripción actualizado correctamente.',
            'subscription' => new AdminMarketingSubscriptionResource(
                $this->marketingSubscriptionAdminService->find((int) $updated->id),
            ),
        ]);
    }

    public function markPaid(MarketingPlanSubscription $marketingPlanSubscription): JsonResponse
    {
        $updated = $this->marketingSubscriptionAdminService->markPaid($marketingPlanSubscription);

        return response()->json([
            'message' => 'Suscripción marcada como pagada y activada.',
            'subscription' => new AdminMarketingSubscriptionResource(
                $this->marketingSubscriptionAdminService->find((int) $updated->id),
            ),
        ]);
    }
}
