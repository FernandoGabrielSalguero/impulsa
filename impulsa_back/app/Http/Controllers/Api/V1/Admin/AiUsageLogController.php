<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminAiUsageLogCollection;
use App\Http\Resources\AdminAiUsageLogDetailResource;
use App\Services\Admin\AiUsageLogAdminService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AiUsageLogController extends Controller
{
    public function __construct(
        private readonly AiUsageLogAdminService $aiUsageLogAdminService,
    ) {}

    public function options(): JsonResponse
    {
        return response()->json([
            'features' => $this->aiUsageLogAdminService->featureOptions(),
            'statuses' => [
                ['value' => 'success', 'label' => 'Exitoso'],
                ['value' => 'failed', 'label' => 'Fallido'],
            ],
        ]);
    }

    public function index(Request $request): AdminAiUsageLogCollection
    {
        $perPage = max(1, min((int) $request->integer('per_page', 20), 100));

        $logs = $this->aiUsageLogAdminService->list(
            $request->query('usuario'),
            $request->query('feature'),
            $request->query('status'),
            $request->query('date_from'),
            $request->query('date_to'),
            $perPage,
        );

        return new AdminAiUsageLogCollection($logs);
    }

    public function show(int $aiUsageLog): AdminAiUsageLogDetailResource|JsonResponse
    {
        $log = $this->aiUsageLogAdminService->find($aiUsageLog);

        if ($log === null) {
            return response()->json([
                'message' => 'Registro de IA no encontrado.',
            ], 404);
        }

        return new AdminAiUsageLogDetailResource($log);
    }
}
