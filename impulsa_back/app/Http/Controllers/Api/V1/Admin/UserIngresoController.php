<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminUserIngresoCollection;
use App\Services\Admin\UserIngresoAdminService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserIngresoController extends Controller
{
    public function __construct(
        private readonly UserIngresoAdminService $userIngresoAdminService,
    ) {}

    public function options(): JsonResponse
    {
        return response()->json([
            'roles' => $this->userIngresoAdminService->roleOptions(),
        ]);
    }

    public function index(Request $request): AdminUserIngresoCollection
    {
        $perPage = max(1, min((int) $request->integer('per_page', 20), 100));

        $ingresos = $this->userIngresoAdminService->list(
            $request->query('nombre_usuario'),
            $request->query('rol'),
            $request->query('fecha'),
            $request->query('usuario_tipo'),
            $perPage,
        );

        return new AdminUserIngresoCollection($ingresos);
    }
}
