<?php

namespace App\Http\Controllers\Api\V1\Emprendedor;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmprendedorWebsiteSubscriptionResource;
use App\Services\Emprendedor\EmprendedorWebsiteSubscriptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebsiteSubscriptionController extends Controller
{
    public function __construct(
        private readonly EmprendedorWebsiteSubscriptionService $websiteSubscriptionService,
    ) {}

    public function show(Request $request): JsonResponse
    {
        return response()->json([
            'data' => new EmprendedorWebsiteSubscriptionResource(
                $this->websiteSubscriptionService->show($request->user()),
            ),
        ]);
    }
}
