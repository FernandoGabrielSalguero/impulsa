<?php

namespace App\Http\Controllers\Api\V1\Marketing;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateMarketingSubscriptionStatusRequest;
use App\Services\Marketing\MarketingImportService;
use App\Services\Admin\MarketingSubscriptionAdminService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MonitorController extends Controller
{
    public function __construct(
        private readonly MarketingImportService $importService,
        private readonly MarketingSubscriptionAdminService $subscriptionAdminService,
    ) {}

    public function campaigns(): JsonResponse
    {
        return response()->json([
            'data' => $this->importService->campaigns(),
        ]);
    }

    public function importCsv(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:10240'],
        ]);

        $result = $this->importService->importMetaCsv(
            $request->user(),
            $validated['file'],
        );

        return response()->json([
            'message' => 'Importación procesada correctamente.',
            'data' => $result,
        ]);
    }

    public function updateSubscriptionStatus(
        UpdateMarketingSubscriptionStatusRequest $request,
        int $marketingPlanSubscription,
    ): JsonResponse {
        $updated = $this->subscriptionAdminService->updateStatus(
            \App\Models\MarketingPlanSubscription::query()->findOrFail($marketingPlanSubscription),
            (string) $request->validated('status'),
            (int) $request->user()->id,
        );

        return response()->json([
            'message' => 'Estado de la suscripción actualizado correctamente.',
            'subscription_id' => (int) $updated->id,
        ]);
    }
}
