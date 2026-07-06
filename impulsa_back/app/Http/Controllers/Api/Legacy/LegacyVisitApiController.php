<?php

namespace App\Http\Controllers\Api\Legacy;

use App\Http\Controllers\Controller;
use App\Models\ApiIntegration;
use App\Services\PublicApi\PublicMetricsTrackingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LegacyVisitApiController extends Controller
{
    public function __construct(
        private readonly PublicMetricsTrackingService $trackingService,
    ) {}

    public function handle(Request $request): JsonResponse
    {
        /** @var ApiIntegration $integration */
        $integration = $request->attributes->get('api_integration');

        $page = trim((string) $request->input('page', ''));

        if ($page === '') {
            $page = trim((string) ($request->input('pathname', '') ?: '/'));
        }

        try {
            $this->trackingService->recordPageVisit($integration, $page);

            return response()->json([
                'success' => true,
                'ok' => true,
                'message' => 'Visita registrada.',
            ], 201);
        } catch (ValidationException $exception) {
            return response()->json([
                'success' => false,
                'message' => collect($exception->errors())->flatten()->first() ?? 'Solicitud inválida.',
            ], 422);
        }
    }
}
