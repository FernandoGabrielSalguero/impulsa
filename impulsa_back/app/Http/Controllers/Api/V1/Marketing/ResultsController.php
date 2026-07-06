<?php

namespace App\Http\Controllers\Api\V1\Marketing;

use App\Http\Controllers\Controller;
use App\Services\Marketing\MarketingResultsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResultsController extends Controller
{
    public function __construct(
        private readonly MarketingResultsService $resultsService,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $subscriptionId = $request->filled('subscription_id')
            ? (int) $request->integer('subscription_id')
            : null;

        return response()->json([
            'data' => [
                'results' => $this->resultsService->results($subscriptionId),
                'reports' => $this->resultsService->reports(),
            ],
        ]);
    }
}
