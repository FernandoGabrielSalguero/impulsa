<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Emprendedor\CompareFinanceScenariosRequest;
use App\Http\Requests\Emprendedor\FinanceBreakEvenPreviewRequest;
use App\Http\Requests\Emprendedor\FinancePricingPreviewRequest;
use App\Http\Requests\Emprendedor\FinanceProjectionPreviewRequest;
use App\Http\Requests\Emprendedor\FinanceScenarioPreviewRequest;
use App\Models\UserAuth;
use App\Services\Admin\AdminFinanzasService;
use App\Services\Emprendedor\EmprendedorFinanzasService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminFinanzasController extends Controller
{
    public function __construct(
        private readonly AdminFinanzasService $adminFinanzasService,
        private readonly EmprendedorFinanzasService $finanzasService,
    ) {}

    public function users(Request $request): JsonResponse
    {
        $perPage = max(1, min((int) $request->integer('per_page', 15), 100));
        $users = $this->adminFinanzasService->listUsers($request->query('q'), $perPage);

        return response()->json([
            'data' => collect($users->items())
                ->map(fn (UserAuth $user): array => $this->adminFinanzasService->serializeUser($user))
                ->values()
                ->all(),
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'total' => $users->total(),
                'per_page' => $users->perPage(),
            ],
        ]);
    }

    public function user(int $userId): JsonResponse
    {
        $user = $this->adminFinanzasService->resolveUser($userId);

        return response()->json([
            'data' => $this->adminFinanzasService->serializeUser($user),
        ]);
    }

    public function summary(Request $request, int $userId): JsonResponse
    {
        $user = $this->adminFinanzasService->resolveUser($userId);

        return response()->json([
            'data' => $this->finanzasService->summary($user, [
                'from' => $request->query('from'),
                'to' => $request->query('to'),
            ]),
        ]);
    }

    public function settings(int $userId): JsonResponse
    {
        $user = $this->adminFinanzasService->resolveUser($userId);

        return response()->json([
            'data' => $this->finanzasService->getSettings($user),
        ]);
    }

    public function categories(Request $request, int $userId): JsonResponse
    {
        $user = $this->adminFinanzasService->resolveUser($userId);

        return response()->json([
            'data' => $this->finanzasService->listCategories($user, $request->query('type')),
        ]);
    }

    public function movements(Request $request, int $userId): JsonResponse
    {
        $user = $this->adminFinanzasService->resolveUser($userId);

        return response()->json([
            'data' => $this->finanzasService->listMovements($user, [
                'from' => $request->query('from'),
                'to' => $request->query('to'),
                'type' => $request->query('type'),
                'category_id' => $request->query('category_id'),
            ]),
        ]);
    }

    public function fixedCosts(int $userId): JsonResponse
    {
        $user = $this->adminFinanzasService->resolveUser($userId);

        return response()->json([
            'data' => $this->finanzasService->listFixedCosts($user),
            'monthly_total' => $this->finanzasService->monthlyFixedCostsTotal($user),
        ]);
    }

    public function pricingItems(int $userId): JsonResponse
    {
        $user = $this->adminFinanzasService->resolveUser($userId);

        return response()->json([
            'data' => $this->finanzasService->listPricingItems($user),
        ]);
    }

    public function pricingPreview(FinancePricingPreviewRequest $request, int $userId): JsonResponse
    {
        $this->adminFinanzasService->resolveUser($userId);

        return response()->json([
            'data' => $this->finanzasService->pricingPreview($request->validated()),
        ]);
    }

    public function breakEvenPreview(FinanceBreakEvenPreviewRequest $request, int $userId): JsonResponse
    {
        $user = $this->adminFinanzasService->resolveUser($userId);

        return response()->json([
            'data' => $this->finanzasService->breakEvenPreview($user, $request->validated()),
        ]);
    }

    public function productReferences(int $userId): JsonResponse
    {
        $user = $this->adminFinanzasService->resolveUser($userId);

        return response()->json([
            'data' => $this->finanzasService->productReferences($user),
        ]);
    }

    public function projectionBaseline(int $userId): JsonResponse
    {
        $user = $this->adminFinanzasService->resolveUser($userId);

        return response()->json([
            'data' => $this->finanzasService->projectionBaseline($user),
        ]);
    }

    public function projectionPreview(FinanceProjectionPreviewRequest $request, int $userId): JsonResponse
    {
        $user = $this->adminFinanzasService->resolveUser($userId);

        return response()->json([
            'data' => $this->finanzasService->projectionPreview($user, $request->validated()),
        ]);
    }

    public function projections(int $userId): JsonResponse
    {
        $user = $this->adminFinanzasService->resolveUser($userId);

        return response()->json([
            'data' => $this->finanzasService->listProjections($user),
        ]);
    }

    public function scenarioPreview(FinanceScenarioPreviewRequest $request, int $userId): JsonResponse
    {
        $user = $this->adminFinanzasService->resolveUser($userId);

        return response()->json([
            'data' => $this->finanzasService->scenarioPreview($user, $request->validated()),
        ]);
    }

    public function scenarios(int $userId): JsonResponse
    {
        $user = $this->adminFinanzasService->resolveUser($userId);

        return response()->json([
            'data' => $this->finanzasService->listScenarios($user),
        ]);
    }

    public function compareScenarios(CompareFinanceScenariosRequest $request, int $userId): JsonResponse
    {
        $user = $this->adminFinanzasService->resolveUser($userId);

        return response()->json([
            'data' => $this->finanzasService->compareScenarios($user, $request->validated('scenario_ids')),
        ]);
    }
}
