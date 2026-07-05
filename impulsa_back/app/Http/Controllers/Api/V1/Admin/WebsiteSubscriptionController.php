<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreWebsiteSubscriptionRequest;
use App\Http\Requests\Admin\UpdateWebsiteSubscriptionPeriodRequest;
use App\Http\Requests\Admin\UpdateWebsiteSubscriptionRequest;
use App\Http\Resources\AdminMercadoPagoSubscriptionPlanResource;
use App\Http\Resources\AdminWebsiteSubscriptionCollection;
use App\Http\Resources\AdminWebsiteSubscriptionPeriodResource;
use App\Http\Resources\AdminWebsiteSubscriptionResource;
use App\Models\WebsiteSubscription;
use App\Models\WebsiteSubscriptionPeriod;
use App\Services\Admin\MercadoPagoSubscriptionPlanAdminService;
use App\Services\Admin\WebsiteSubscriptionAdminService;
use App\Support\WebsiteSubscriptionLabels;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebsiteSubscriptionController extends Controller
{
    public function __construct(
        private readonly WebsiteSubscriptionAdminService $websiteSubscriptionAdminService,
        private readonly MercadoPagoSubscriptionPlanAdminService $planAdminService,
    ) {}

    public function index(Request $request): AdminWebsiteSubscriptionCollection
    {
        $perPage = max(1, min((int) $request->integer('per_page', 20), 100));
        $result = $this->websiteSubscriptionAdminService->list(
            $request->query('q'),
            $request->query('status'),
            $perPage,
        );

        return new AdminWebsiteSubscriptionCollection($result['data']);
    }

    public function options(): JsonResponse
    {
        return response()->json([
            'statuses' => collect(WebsiteSubscriptionLabels::subscriptionStatuses())->map(static fn (string $value): array => [
                'value' => $value,
                'label' => WebsiteSubscriptionLabels::subscriptionStatusLabel($value),
            ])->values(),
            'period_statuses' => collect(WebsiteSubscriptionLabels::periodStatuses())->map(static fn (string $value): array => [
                'value' => $value,
                'label' => WebsiteSubscriptionLabels::periodStatusLabel($value),
            ])->values(),
            'mercadopago' => $this->websiteSubscriptionAdminService->mercadoPagoConfig(),
            'mercadopago_plans' => AdminMercadoPagoSubscriptionPlanResource::collection(
                $this->planAdminService->listActiveOptions(),
            ),
        ]);
    }

    public function integrationOptions(): JsonResponse
    {
        return response()->json([
            'data' => $this->websiteSubscriptionAdminService->listAvailableIntegrations(),
        ]);
    }

    public function store(StoreWebsiteSubscriptionRequest $request): JsonResponse
    {
        $subscription = $this->websiteSubscriptionAdminService->create($request->validated());

        return response()->json([
            'message' => 'Suscripción creada correctamente.',
            'subscription' => new AdminWebsiteSubscriptionResource($subscription),
        ], 201);
    }

    public function show(WebsiteSubscription $websiteSubscription): JsonResponse
    {
        $subscription = $this->websiteSubscriptionAdminService->find((int) $websiteSubscription->id);
        $periods = $this->websiteSubscriptionAdminService->listPeriods($subscription);

        return response()->json([
            'data' => new AdminWebsiteSubscriptionResource($subscription),
            'periods' => AdminWebsiteSubscriptionPeriodResource::collection($periods),
        ]);
    }

    public function update(UpdateWebsiteSubscriptionRequest $request, WebsiteSubscription $websiteSubscription): JsonResponse
    {
        $subscription = $this->websiteSubscriptionAdminService->update(
            $websiteSubscription,
            $request->validated(),
        );

        return response()->json([
            'message' => 'Suscripción actualizada correctamente.',
            'subscription' => new AdminWebsiteSubscriptionResource($subscription),
        ]);
    }

    public function updatePeriod(
        UpdateWebsiteSubscriptionPeriodRequest $request,
        WebsiteSubscription $websiteSubscription,
        WebsiteSubscriptionPeriod $period,
    ): JsonResponse {
        if ((int) $period->website_subscription_id !== (int) $websiteSubscription->id) {
            abort(404);
        }

        $updated = $this->websiteSubscriptionAdminService->updatePeriod($period, $request->validated());

        return response()->json([
            'message' => 'Período actualizado correctamente.',
            'period' => new AdminWebsiteSubscriptionPeriodResource($updated),
        ]);
    }

    public function markPeriodPaid(
        WebsiteSubscription $websiteSubscription,
        WebsiteSubscriptionPeriod $period,
    ): JsonResponse {
        if ((int) $period->website_subscription_id !== (int) $websiteSubscription->id) {
            abort(404);
        }

        $updated = $this->websiteSubscriptionAdminService->markPeriodPaid($period);

        return response()->json([
            'message' => 'Período marcado como pagado.',
            'period' => new AdminWebsiteSubscriptionPeriodResource($updated),
        ]);
    }
}
