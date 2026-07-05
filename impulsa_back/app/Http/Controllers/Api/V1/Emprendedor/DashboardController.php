<?php

namespace App\Http\Controllers\Api\V1\Emprendedor;

use App\Http\Controllers\Controller;
use App\Services\Emprendedor\EmprendedorDashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        private readonly EmprendedorDashboardService $dashboardService,
    ) {}

    public function stats(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $this->dashboardService->stats($request->user()),
        ]);
    }
}
