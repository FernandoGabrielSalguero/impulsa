<?php

namespace App\Http\Controllers\Api\V1\Emprendedor;

use App\Http\Controllers\Controller;
use App\Services\Emprendedor\EmprendedorMenuService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function __construct(
        private readonly EmprendedorMenuService $menuService,
    ) {}

    public function show(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $this->menuService->menuForUser($request->user()),
        ]);
    }
}
