<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMercadoPagoSubscriptionPlanRequest;
use App\Http\Resources\AdminMercadoPagoSubscriptionPlanCollection;
use App\Http\Resources\AdminMercadoPagoSubscriptionPlanResource;
use App\Models\MercadoPagoSubscriptionPlan;
use App\Services\Admin\MercadoPagoSubscriptionPlanAdminService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MercadoPagoSubscriptionPlanController extends Controller
{
    public function __construct(
        private readonly MercadoPagoSubscriptionPlanAdminService $planAdminService,
    ) {}

    public function index(Request $request): AdminMercadoPagoSubscriptionPlanCollection
    {
        $perPage = max(1, min((int) $request->integer('per_page', 20), 100));
        $result = $this->planAdminService->list(
            $request->query('q'),
            $request->query('status'),
            $perPage,
        );

        return new AdminMercadoPagoSubscriptionPlanCollection($result['data']);
    }

    public function options(): JsonResponse
    {
        return response()->json([
            'data' => AdminMercadoPagoSubscriptionPlanResource::collection(
                $this->planAdminService->listActiveOptions(),
            ),
        ]);
    }

    public function store(StoreMercadoPagoSubscriptionPlanRequest $request): JsonResponse
    {
        $plan = $this->planAdminService->create($request->validated());

        return response()->json([
            'message' => 'Plan de Mercado Pago creado correctamente.',
            'plan' => new AdminMercadoPagoSubscriptionPlanResource($plan),
        ], 201);
    }

    public function show(MercadoPagoSubscriptionPlan $mercadopagoSubscriptionPlan): AdminMercadoPagoSubscriptionPlanResource
    {
        return new AdminMercadoPagoSubscriptionPlanResource(
            $this->planAdminService->find((int) $mercadopagoSubscriptionPlan->id),
        );
    }

    public function update(
        StoreMercadoPagoSubscriptionPlanRequest $request,
        MercadoPagoSubscriptionPlan $mercadopagoSubscriptionPlan,
    ): JsonResponse {
        $plan = $this->planAdminService->update($mercadopagoSubscriptionPlan, $request->validated());

        return response()->json([
            'message' => 'Plan actualizado correctamente.',
            'plan' => new AdminMercadoPagoSubscriptionPlanResource($plan),
        ]);
    }

    public function toggleStatus(MercadoPagoSubscriptionPlan $mercadopagoSubscriptionPlan): JsonResponse
    {
        $plan = $this->planAdminService->toggleStatus($mercadopagoSubscriptionPlan);

        return response()->json([
            'message' => $plan->status === 'active' ? 'Plan activado.' : 'Plan desactivado.',
            'plan' => new AdminMercadoPagoSubscriptionPlanResource($plan),
        ]);
    }

    public function destroy(MercadoPagoSubscriptionPlan $mercadopagoSubscriptionPlan): JsonResponse
    {
        $this->planAdminService->delete($mercadopagoSubscriptionPlan);

        return response()->json([
            'message' => 'Plan eliminado correctamente.',
        ]);
    }
}
