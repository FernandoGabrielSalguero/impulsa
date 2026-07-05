<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAdminTaskRequest;
use App\Http\Resources\AdminTaskCollection;
use App\Http\Resources\AdminTaskResource;
use App\Models\AdminTarea;
use App\Services\Admin\AdminTaskService;
use App\Support\TaskLabels;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(
        private readonly AdminTaskService $adminTaskService,
    ) {}

    public function index(Request $request): AdminTaskCollection
    {
        $perPage = max(1, min((int) $request->integer('per_page', 20), 100));
        $result = $this->adminTaskService->list(
            $request->query('q'),
            $request->query('estado'),
            $perPage,
        );

        return new AdminTaskCollection($result['data'], $result['summary']);
    }

    public function options(): JsonResponse
    {
        return response()->json([
            'statuses' => collect(TaskLabels::statuses())->map(static fn (string $value): array => [
                'value' => $value,
                'label' => TaskLabels::statusLabel($value),
            ])->values(),
            'defcon_levels' => collect(TaskLabels::defconLevels())->map(static fn (int $value): array => [
                'value' => $value,
                'label' => TaskLabels::defconLabel($value),
            ])->values(),
        ]);
    }

    public function assignees(): JsonResponse
    {
        return response()->json([
            'data' => $this->adminTaskService->listAssignees(),
        ]);
    }

    public function store(StoreAdminTaskRequest $request): JsonResponse
    {
        $task = $this->adminTaskService->create($request->validated(), (int) $request->user()->id);

        return response()->json([
            'message' => 'Tarea creada correctamente.',
            'task' => new AdminTaskResource($task),
        ], 201);
    }

    public function show(AdminTarea $adminTarea): AdminTaskResource
    {
        return new AdminTaskResource($this->adminTaskService->find((int) $adminTarea->id));
    }

    public function update(StoreAdminTaskRequest $request, AdminTarea $adminTarea): JsonResponse
    {
        $updatedTask = $this->adminTaskService->update($adminTarea, $request->validated());

        return response()->json([
            'message' => 'Tarea actualizada correctamente.',
            'task' => new AdminTaskResource($updatedTask),
        ]);
    }

    public function destroy(AdminTarea $adminTarea): JsonResponse
    {
        $this->adminTaskService->delete($adminTarea);

        return response()->json([
            'message' => 'Tarea eliminada correctamente.',
        ]);
    }
}
