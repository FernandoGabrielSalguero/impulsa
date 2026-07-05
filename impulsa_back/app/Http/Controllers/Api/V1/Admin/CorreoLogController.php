<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminCorreoLogCollection;
use App\Http\Resources\AdminCorreoLogDetailResource;
use App\Models\CorreoLog;
use App\Services\Admin\CorreoLogAdminService;
use App\Services\Mail\CorreoLogResendService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CorreoLogController extends Controller
{
    public function __construct(
        private readonly CorreoLogAdminService $correoLogAdminService,
        private readonly CorreoLogResendService $correoLogResendService,
    ) {}

    public function index(Request $request): AdminCorreoLogCollection
    {
        $perPage = max(1, min((int) $request->integer('per_page', 20), 100));

        $logs = $this->correoLogAdminService->list(
            $request->query('correo'),
            $request->query('asunto'),
            $perPage,
        );

        return new AdminCorreoLogCollection($logs);
    }

    public function show(int $correoLog): AdminCorreoLogDetailResource|JsonResponse
    {
        $log = $this->correoLogAdminService->find($correoLog);

        if ($log === null) {
            return response()->json([
                'message' => 'Correo no encontrado.',
            ], 404);
        }

        return new AdminCorreoLogDetailResource($log);
    }

    public function resend(int $correoLog): JsonResponse
    {
        $log = CorreoLog::query()->find($correoLog);

        if ($log === null) {
            return response()->json([
                'message' => 'Correo no encontrado.',
            ], 404);
        }

        $result = $this->correoLogResendService->resend($log);

        return response()->json([
            'message' => $result['message'],
            'ok' => $result['ok'],
            'log' => isset($result['log'])
                ? (new AdminCorreoLogDetailResource($result['log']))->resolve()
                : null,
        ], $result['ok'] ? 200 : 422);
    }
}
