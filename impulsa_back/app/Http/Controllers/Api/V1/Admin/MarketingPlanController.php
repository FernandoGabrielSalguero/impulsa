<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMarketingPlanRequest;
use App\Http\Requests\Admin\UpdateMarketingPlanStatusRequest;
use App\Http\Requests\Admin\UpdateMarketingSubscriptionStatusRequest;
use App\Http\Resources\AdminMarketingPlanCollection;
use App\Http\Resources\AdminMarketingPlanResource;
use App\Http\Resources\AdminMarketingSubscriptionCollection;
use App\Http\Resources\AdminMarketingSubscriptionResource;
use App\Models\MarketingPlan;
use App\Models\MarketingPlanSubscription;
use App\Services\Admin\MarketingDashboardAdminService;
use App\Services\Admin\MarketingPlanAdminService;
use App\Services\Admin\MarketingSubscriptionAdminService;
use App\Services\Admin\MercadoPagoSubscriptionPlanAdminService;
use App\Support\MarketingLabels;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MarketingPlanController extends Controller
{
    public function __construct(
        private readonly MarketingPlanAdminService $marketingPlanAdminService,
        private readonly MercadoPagoSubscriptionPlanAdminService $mpPlanAdminService,
    ) {}

    public function options(): JsonResponse
    {
        return response()->json([
            'statuses' => collect(MarketingLabels::planStatuses())->map(static fn (string $value): array => [
                'value' => $value,
                'label' => MarketingLabels::planStatusLabel($value),
            ])->values(),
            'mercadopago_plans' => $this->mpPlanAdminService->listActiveOptions()->map(static fn ($plan): array => [
                'id' => (int) $plan->id,
                'name' => (string) $plan->name,
                'amount' => (float) $plan->amount,
                'payment_url' => (string) $plan->payment_url,
            ])->values(),
        ]);
    }

    public function index(Request $request): AdminMarketingPlanCollection
    {
        $perPage = max(1, min((int) $request->integer('per_page', 20), 100));
        $result = $this->marketingPlanAdminService->list(
            $request->query('q'),
            $request->query('status'),
            $perPage,
        );

        return new AdminMarketingPlanCollection($result['data']);
    }

    public function store(StoreMarketingPlanRequest $request): JsonResponse
    {
        $plan = $this->marketingPlanAdminService->create($request->validated(), (int) $request->user()->id);

        return response()->json([
            'message' => 'Plan de marketing creado correctamente.',
            'plan' => new AdminMarketingPlanResource($plan),
        ], 201);
    }

    public function show(MarketingPlan $marketingPlan): AdminMarketingPlanResource
    {
        return new AdminMarketingPlanResource($this->marketingPlanAdminService->find((int) $marketingPlan->id));
    }

    public function preview(MarketingPlan $marketingPlan): AdminMarketingPlanResource
    {
        return new AdminMarketingPlanResource($this->marketingPlanAdminService->preview((int) $marketingPlan->id));
    }

    public function update(StoreMarketingPlanRequest $request, MarketingPlan $marketingPlan): JsonResponse
    {
        $plan = $this->marketingPlanAdminService->update($marketingPlan, $request->validated());

        return response()->json([
            'message' => 'Plan de marketing actualizado correctamente.',
            'plan' => new AdminMarketingPlanResource($plan),
        ]);
    }

    public function updateStatus(UpdateMarketingPlanStatusRequest $request, MarketingPlan $marketingPlan): JsonResponse
    {
        $plan = $this->marketingPlanAdminService->updateStatus(
            $marketingPlan,
            (string) $request->validated('status'),
        );

        return response()->json([
            'message' => 'Estado del plan actualizado correctamente.',
            'plan' => new AdminMarketingPlanResource($plan),
        ]);
    }
}
