<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\MarketingDashboardAdminService;
use Illuminate\Http\JsonResponse;

class MarketingDashboardController extends Controller
{
    public function __construct(
        private readonly MarketingDashboardAdminService $marketingDashboardAdminService,
    ) {}

    public function summary(): JsonResponse
    {
        return response()->json([
            'data' => $this->marketingDashboardAdminService->summary(),
        ]);
    }
}
