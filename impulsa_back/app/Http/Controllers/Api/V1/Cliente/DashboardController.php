<?php

namespace App\Http\Controllers\Api\V1\Cliente;

use App\Http\Controllers\Controller;
use App\Services\Cliente\ClienteDashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        private readonly ClienteDashboardService $dashboardService,
    ) {}

    public function stats(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $this->dashboardService->stats($request->user()),
        ]);
    }
}
