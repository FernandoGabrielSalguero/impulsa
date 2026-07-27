<?php

namespace App\Http\Controllers\Api\V1\Emprendedor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Emprendedor\StoreUserGoalObjectiveRequest;
use App\Http\Requests\Emprendedor\StoreUserGoalRequest;
use App\Http\Requests\Emprendedor\UpdateUserGoalObjectiveRequest;
use App\Http\Requests\Emprendedor\UpdateUserGoalObjectiveStatusRequest;
use App\Http\Requests\Emprendedor\UpdateUserGoalRequest;
use App\Services\Goals\UserGoalsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GoalsController extends Controller
{
    public function __construct(
        private readonly UserGoalsService $goalsService,
    ) {}

    public function options(): JsonResponse
    {
        return response()->json($this->goalsService->options());
    }

    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $this->goalsService->listGoals($request->user(), [
                'status' => $request->query('status'),
                'q' => $request->query('q'),
                'overdue' => $request->query('overdue'),
            ]),
        ]);
    }

    public function store(StoreUserGoalRequest $request): JsonResponse
    {
        return response()->json([
            'message' => 'Meta creada correctamente.',
            'data' => $this->goalsService->createGoal($request->user(), $request->validated()),
        ], 201);
    }

    public function show(Request $request, int $goalId): JsonResponse
    {
        return response()->json([
            'data' => $this->goalsService->getGoalDetail($request->user(), $goalId),
        ]);
    }

    public function update(UpdateUserGoalRequest $request, int $goalId): JsonResponse
    {
        return response()->json([
            'message' => 'Meta actualizada correctamente.',
            'data' => $this->goalsService->updateGoal($request->user(), $goalId, $request->validated()),
        ]);
    }

    public function destroy(Request $request, int $goalId): JsonResponse
    {
        $this->goalsService->deleteGoal($request->user(), $goalId);

        return response()->json([
            'message' => 'Meta eliminada correctamente.',
        ]);
    }

    public function storeObjective(StoreUserGoalObjectiveRequest $request, int $goalId): JsonResponse
    {
        return response()->json([
            'message' => 'Objetivo creado correctamente.',
            'data' => $this->goalsService->createObjective($request->user(), $goalId, $request->validated()),
        ], 201);
    }

    public function updateObjective(UpdateUserGoalObjectiveRequest $request, int $goalId, int $objectiveId): JsonResponse
    {
        return response()->json([
            'message' => 'Objetivo actualizado correctamente.',
            'data' => $this->goalsService->updateObjective(
                $request->user(),
                $goalId,
                $objectiveId,
                $request->validated(),
            ),
        ]);
    }

    public function updateObjectiveStatus(
        UpdateUserGoalObjectiveStatusRequest $request,
        int $goalId,
        int $objectiveId,
    ): JsonResponse {
        return response()->json([
            'message' => 'Estado del objetivo actualizado.',
            'data' => $this->goalsService->updateObjectiveStatus(
                $request->user(),
                $goalId,
                $objectiveId,
                (string) $request->validated('status'),
            ),
        ]);
    }

    public function destroyObjective(Request $request, int $goalId, int $objectiveId): JsonResponse
    {
        $this->goalsService->deleteObjective($request->user(), $goalId, $objectiveId);

        return response()->json([
            'message' => 'Objetivo eliminado correctamente.',
        ]);
    }
}
