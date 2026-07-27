<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminGoalMonitorCollection;
use App\Services\Admin\AdminGoalsMonitorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GoalsMonitorController extends Controller
{
    public function __construct(
        private readonly AdminGoalsMonitorService $goalsMonitorService,
    ) {}

    public function options(): JsonResponse
    {
        return response()->json($this->goalsMonitorService->options());
    }

    public function summary(): JsonResponse
    {
        return response()->json([
            'data' => $this->goalsMonitorService->summary(),
        ]);
    }

    public function index(Request $request): AdminGoalMonitorCollection
    {
        $perPage = max(1, min((int) $request->integer('per_page', 20), 100));

        $goals = $this->goalsMonitorService->listGoals([
            'q' => $request->query('q'),
            'status' => $request->query('status'),
            'role' => $request->query('role'),
            'overdue' => $request->query('overdue'),
            'user_id' => $request->query('user_id'),
        ], $perPage);

        return new AdminGoalMonitorCollection($goals);
    }

    public function show(int $goalId): JsonResponse
    {
        return response()->json([
            'data' => $this->goalsMonitorService->getGoalDetail($goalId),
        ]);
    }
}
