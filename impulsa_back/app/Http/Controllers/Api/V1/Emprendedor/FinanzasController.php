<?php

namespace App\Http\Controllers\Api\V1\Emprendedor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Emprendedor\CompareFinanceScenariosRequest;
use App\Http\Requests\Emprendedor\FinanceBreakEvenPreviewRequest;
use App\Http\Requests\Emprendedor\FinancePricingPreviewRequest;
use App\Http\Requests\Emprendedor\FinanceProjectionPreviewRequest;
use App\Http\Requests\Emprendedor\FinanceScenarioPreviewRequest;
use App\Http\Requests\Emprendedor\StoreFinanceCategoryRequest;
use App\Http\Requests\Emprendedor\StoreFinanceFixedCostRequest;
use App\Http\Requests\Emprendedor\StoreFinanceMovementRequest;
use App\Http\Requests\Emprendedor\StoreFinancePricingItemRequest;
use App\Http\Requests\Emprendedor\StoreFinanceProjectionRequest;
use App\Http\Requests\Emprendedor\StoreFinanceScenarioRequest;
use App\Http\Requests\Emprendedor\UpdateFinanceFixedCostRequest;
use App\Http\Requests\Emprendedor\UpdateFinanceMovementRequest;
use App\Http\Requests\Emprendedor\UpdateFinancePricingItemRequest;
use App\Http\Requests\Emprendedor\UpdateFinanceProjectionRequest;
use App\Http\Requests\Emprendedor\UpdateFinanceScenarioRequest;
use App\Http\Requests\Emprendedor\UpdateFinanceSettingsRequest;
use App\Services\Emprendedor\EmprendedorFinanzasService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FinanzasController extends Controller
{
    public function __construct(
        private readonly EmprendedorFinanzasService $finanzasService,
    ) {}

    public function summary(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $this->finanzasService->summary($request->user(), [
                'from' => $request->query('from'),
                'to' => $request->query('to'),
            ]),
        ]);
    }

    public function settings(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $this->finanzasService->getSettings($request->user()),
        ]);
    }

    public function updateSettings(UpdateFinanceSettingsRequest $request): JsonResponse
    {
        return response()->json([
            'message' => 'Configuración actualizada.',
            'data' => $this->finanzasService->updateSettings($request->user(), $request->validated()),
        ]);
    }

    public function categories(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $this->finanzasService->listCategories(
                $request->user(),
                $request->query('type'),
            ),
        ]);
    }

    public function storeCategory(StoreFinanceCategoryRequest $request): JsonResponse
    {
        return response()->json([
            'message' => 'Categoría creada.',
            'data' => $this->finanzasService->createCategory($request->user(), $request->validated()),
        ], 201);
    }

    public function movements(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $this->finanzasService->listMovements($request->user(), [
                'from' => $request->query('from'),
                'to' => $request->query('to'),
                'type' => $request->query('type'),
                'category_id' => $request->query('category_id'),
            ]),
        ]);
    }

    public function storeMovement(StoreFinanceMovementRequest $request): JsonResponse
    {
        return response()->json([
            'message' => 'Movimiento registrado.',
            'data' => $this->finanzasService->createMovement($request->user(), $request->validated()),
        ], 201);
    }

    public function updateMovement(UpdateFinanceMovementRequest $request, int $movementId): JsonResponse
    {
        return response()->json([
            'message' => 'Movimiento actualizado.',
            'data' => $this->finanzasService->updateMovement($request->user(), $movementId, $request->validated()),
        ]);
    }

    public function destroyMovement(Request $request, int $movementId): JsonResponse
    {
        $this->finanzasService->deleteMovement($request->user(), $movementId);

        return response()->json([
            'message' => 'Movimiento eliminado.',
        ]);
    }

    public function fixedCosts(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $this->finanzasService->listFixedCosts($request->user()),
            'monthly_total' => $this->finanzasService->monthlyFixedCostsTotal($request->user()),
        ]);
    }

    public function storeFixedCost(StoreFinanceFixedCostRequest $request): JsonResponse
    {
        return response()->json([
            'message' => 'Costo fijo guardado.',
            'data' => $this->finanzasService->createFixedCost($request->user(), $request->validated()),
        ], 201);
    }

    public function updateFixedCost(UpdateFinanceFixedCostRequest $request, int $costId): JsonResponse
    {
        return response()->json([
            'message' => 'Costo fijo actualizado.',
            'data' => $this->finanzasService->updateFixedCost($request->user(), $costId, $request->validated()),
        ]);
    }

    public function destroyFixedCost(Request $request, int $costId): JsonResponse
    {
        $this->finanzasService->deleteFixedCost($request->user(), $costId);

        return response()->json([
            'message' => 'Costo fijo eliminado.',
        ]);
    }

    public function pricingItems(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $this->finanzasService->listPricingItems($request->user()),
        ]);
    }

    public function storePricingItem(StoreFinancePricingItemRequest $request): JsonResponse
    {
        return response()->json([
            'message' => 'Precio guardado.',
            'data' => $this->finanzasService->createPricingItem($request->user(), $request->validated()),
        ], 201);
    }

    public function updatePricingItem(UpdateFinancePricingItemRequest $request, int $itemId): JsonResponse
    {
        return response()->json([
            'message' => 'Precio actualizado.',
            'data' => $this->finanzasService->updatePricingItem($request->user(), $itemId, $request->validated()),
        ]);
    }

    public function destroyPricingItem(Request $request, int $itemId): JsonResponse
    {
        $this->finanzasService->deletePricingItem($request->user(), $itemId);

        return response()->json([
            'message' => 'Precio eliminado.',
        ]);
    }

    public function pricingPreview(FinancePricingPreviewRequest $request): JsonResponse
    {
        return response()->json([
            'data' => $this->finanzasService->pricingPreview($request->validated()),
        ]);
    }

    public function breakEvenPreview(FinanceBreakEvenPreviewRequest $request): JsonResponse
    {
        return response()->json([
            'data' => $this->finanzasService->breakEvenPreview($request->user(), $request->validated()),
        ]);
    }

    public function projectionBaseline(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $this->finanzasService->projectionBaseline($request->user()),
        ]);
    }

    public function projectionPreview(FinanceProjectionPreviewRequest $request): JsonResponse
    {
        return response()->json([
            'data' => $this->finanzasService->projectionPreview($request->user(), $request->validated()),
        ]);
    }

    public function projections(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $this->finanzasService->listProjections($request->user()),
        ]);
    }

    public function storeProjection(StoreFinanceProjectionRequest $request): JsonResponse
    {
        return response()->json([
            'message' => 'Proyección guardada.',
            'data' => $this->finanzasService->createProjection($request->user(), $request->validated()),
        ], 201);
    }

    public function updateProjection(UpdateFinanceProjectionRequest $request, int $projectionId): JsonResponse
    {
        return response()->json([
            'message' => 'Proyección actualizada.',
            'data' => $this->finanzasService->updateProjection($request->user(), $projectionId, $request->validated()),
        ]);
    }

    public function destroyProjection(Request $request, int $projectionId): JsonResponse
    {
        $this->finanzasService->deleteProjection($request->user(), $projectionId);

        return response()->json([
            'message' => 'Proyección eliminada.',
        ]);
    }

    public function scenarioPreview(FinanceScenarioPreviewRequest $request): JsonResponse
    {
        return response()->json([
            'data' => $this->finanzasService->scenarioPreview($request->user(), $request->validated()),
        ]);
    }

    public function scenarios(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $this->finanzasService->listScenarios($request->user()),
        ]);
    }

    public function storeScenario(StoreFinanceScenarioRequest $request): JsonResponse
    {
        return response()->json([
            'message' => 'Escenario guardado.',
            'data' => $this->finanzasService->createScenario($request->user(), $request->validated()),
        ], 201);
    }

    public function updateScenario(UpdateFinanceScenarioRequest $request, int $scenarioId): JsonResponse
    {
        return response()->json([
            'message' => 'Escenario actualizado.',
            'data' => $this->finanzasService->updateScenario($request->user(), $scenarioId, $request->validated()),
        ]);
    }

    public function destroyScenario(Request $request, int $scenarioId): JsonResponse
    {
        $this->finanzasService->deleteScenario($request->user(), $scenarioId);

        return response()->json([
            'message' => 'Escenario eliminado.',
        ]);
    }

    public function compareScenarios(CompareFinanceScenariosRequest $request): JsonResponse
    {
        return response()->json([
            'data' => $this->finanzasService->compareScenarios(
                $request->user(),
                $request->validated('scenario_ids'),
            ),
        ]);
    }
}
