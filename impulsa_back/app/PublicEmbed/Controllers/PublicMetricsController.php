<?php

namespace App\PublicEmbed\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\StoreContentViewRequest;
use App\Http\Requests\Public\StorePageVisitRequest;
use App\Models\ApiIntegration;
use App\PublicEmbed\Services\PublicMetricsService;
use App\PublicEmbed\Support\PublicResponse;
use Illuminate\Http\JsonResponse;

class PublicMetricsController extends Controller
{
    public function __construct(
        private readonly PublicMetricsService $metricsService,
    ) {}

    public function pageVisit(StorePageVisitRequest $request): JsonResponse
    {
        /** @var ApiIntegration $integration */
        $integration = $request->attributes->get('api_integration');

        $this->metricsService->recordPageVisit($integration, $request->validated('page'));

        return PublicResponse::success(['ok' => true], [
            'feature' => 'visits',
            'message' => 'Visita registrada.',
        ], 201);
    }

    public function contentView(StoreContentViewRequest $request): JsonResponse
    {
        /** @var ApiIntegration $integration */
        $integration = $request->attributes->get('api_integration');

        $this->metricsService->recordContentView(
            $integration,
            $request->validated('content_type'),
            (int) $request->validated('content_id'),
            $request->validated('page_url'),
            $request->ip(),
        );

        return PublicResponse::success(['ok' => true], [
            'feature' => 'visits',
            'message' => 'Vista de contenido registrada.',
        ], 201);
    }
}
