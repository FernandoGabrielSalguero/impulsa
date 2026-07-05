<?php

namespace App\Http\Controllers\Api\V1\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\StoreContentViewRequest;
use App\Http\Requests\Public\StorePageVisitRequest;
use App\Models\ApiIntegration;
use App\Services\PublicApi\PublicMetricsTrackingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicMetricsController extends Controller
{
    public function __construct(
        private readonly PublicMetricsTrackingService $trackingService,
    ) {}

    public function pageVisit(StorePageVisitRequest $request): JsonResponse
    {
        /** @var ApiIntegration $integration */
        $integration = $request->attributes->get('api_integration');

        $this->trackingService->recordPageVisit($integration, $request->validated('page'));

        return response()->json([
            'message' => 'Visita registrada.',
            'ok' => true,
        ], 201);
    }

    public function contentView(StoreContentViewRequest $request): JsonResponse
    {
        /** @var ApiIntegration $integration */
        $integration = $request->attributes->get('api_integration');

        $this->trackingService->recordContentView(
            $integration,
            $request->validated('content_type'),
            (int) $request->validated('content_id'),
            $request->validated('page_url'),
            $request->ip(),
        );

        return response()->json([
            'message' => 'Vista de contenido registrada.',
            'ok' => true,
        ], 201);
    }
}
