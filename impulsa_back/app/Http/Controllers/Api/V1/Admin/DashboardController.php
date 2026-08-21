<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\AdminDashboardService;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function __construct(
        private readonly AdminDashboardService $dashboardService,
    ) {}

    public function stats(): JsonResponse
    {
        return response()->json($this->dashboardService->stats());
    }
}
