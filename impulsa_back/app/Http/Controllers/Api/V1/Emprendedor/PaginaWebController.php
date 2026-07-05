<?php

namespace App\Http\Controllers\Api\V1\Emprendedor;

use App\Http\Controllers\Controller;
use App\Services\Emprendedor\EmprendedorPaginaWebService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaginaWebController extends Controller
{
    public function __construct(
        private readonly EmprendedorPaginaWebService $paginaWebService,
    ) {}

    public function overview(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $this->paginaWebService->overview($request->user()),
        ]);
    }
}
