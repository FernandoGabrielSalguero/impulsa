<?php

namespace App\Http\Controllers\Api\V1\Emprendedor;

use App\Http\Controllers\Controller;
use App\Services\Emprendedor\EmprendedorMetricsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MetricsController extends Controller
{
    public function __construct(
        private readonly EmprendedorMetricsService $metricsService,
    ) {}

    public function summary(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $this->metricsService->summary($request->user()),
        ]);
    }

    public function dashboard(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $this->metricsService->dashboard($request->user()),
        ]);
    }
}
