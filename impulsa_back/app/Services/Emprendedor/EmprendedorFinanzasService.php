<?php

namespace App\Services\Emprendedor;

use App\Models\ApiProduct;
use App\Models\FinanceCategory;
use App\Models\FinanceFixedCost;
use App\Models\FinanceMovement;
use App\Models\FinancePricingItem;
use App\Models\FinanceProjection;
use App\Models\FinanceScenario;
use App\Models\FinanceSetting;
use App\Models\UserAuth;
use App\Support\EmprendedorIntegrationAccess;
use App\Support\FinanceCalculator;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EmprendedorFinanzasService
{
    public function __construct(
        private readonly FinanceCalculator $calculator,
        private readonly EmprendedorIntegrationAccess $integrationAccess,
    ) {}

    /** @return array<string, mixed> */
    public function getSettings(UserAuth $user): array
    {
        return $this->serializeSettings($this->ensureSettings($user));
    }

    /** @param array{currency?: string, opening_balance?: float|int|string} $payload */
    public function updateSettings(UserAuth $user, array $payload): array
    {
        $settings = $this->ensureSettings($user);
        $settings->fill([
            'currency' => $payload['currency'] ?? $settings->currency,
            'opening_balance' => $payload['opening_balance'] ?? $settings->opening_balance,
        ]);
        $settings->save();

        return $this->serializeSettings($settings);
    }

    /** @return list<array<string, mixed>> */
    public function listCategories(UserAuth $user, ?string $type = null): array
    {
        $query = FinanceCategory::query()
            ->where(function ($builder) use ($user): void {
                $builder->whereNull('user_auth_id')
                    ->orWhere('user_auth_id', $user->id);
            })
            ->where('is_active', true)
            ->orderBy('type')
            ->orderBy('name');

        if ($type !== null && $type !== '') {
            $query->where('type', $type);
        }

        return $query->get()->map(fn (FinanceCategory $category): array => $this->serializeCategory($category))->values()->all();
    }

    /** @param array{type: string, name: string} $payload */
    public function createCategory(UserAuth $user, array $payload): array
    {
        $category = FinanceCategory::query()->create([
            'user_auth_id' => $user->id,
            'type' => $payload['type'],
            'name' => trim($payload['name']),
            'is_active' => true,
        ]);

        return $this->serializeCategory($category);
    }

    /**
     * @param array{from?: string|null, to?: string|null, type?: string|null, category_id?: int|string|null} $filters
     * @return list<array<string, mixed>>
     */
    public function listMovements(UserAuth $user, array $filters = []): array
    {
        [$from, $to] = $this->resolvePeriod($filters['from'] ?? null, $filters['to'] ?? null);

        $query = FinanceMovement::query()
            ->with('category')
            ->where('user_auth_id', $user->id)
            ->whereBetween('occurred_on', [$from->toDateString(), $to->toDateString()])
            ->orderByDesc('occurred_on')
            ->orderByDesc('id');

        if (! empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (! empty($filters['category_id'])) {
            $query->where('category_id', (int) $filters['category_id']);
        }

        return $query->get()->map(fn (FinanceMovement $movement): array => $this->serializeMovement($movement))->values()->all();
    }

    /** @param array<string, mixed> $payload */
    public function createMovement(UserAuth $user, array $payload): array
    {
        $this->assertCategoryForUser($user, (int) $payload['category_id'], $payload['type']);
        $this->assertOptionalProduct($user, $payload['product_id'] ?? null);

        $quantity = max(1, min(200, (int) ($payload['quantity'] ?? 1)));
        $attributes = [
            'user_auth_id' => $user->id,
            'type' => $payload['type'],
            'category_id' => (int) $payload['category_id'],
            'amount' => (float) $payload['amount'],
            'occurred_on' => $payload['occurred_on'],
            'description' => $payload['description'] ?? null,
            'product_id' => $payload['product_id'] ?? null,
        ];

        $movement = \Illuminate\Support\Facades\DB::transaction(function () use ($attributes, $quantity) {
            $created = null;

            for ($i = 0; $i < $quantity; $i++) {
                $created = FinanceMovement::query()->create($attributes);
            }

            return $created;
        });

        return $this->serializeMovement($movement->load('category'));
    }

    /** @param array<string, mixed> $payload */
    public function updateMovement(UserAuth $user, int $movementId, array $payload): array
    {
        $movement = $this->findOwnedMovement($user, $movementId);
        $type = $payload['type'] ?? $movement->type;
        $categoryId = (int) ($payload['category_id'] ?? $movement->category_id);

        $this->assertCategoryForUser($user, $categoryId, $type);
        $this->assertOptionalProduct($user, $payload['product_id'] ?? $movement->product_id);

        $movement->fill([
            'type' => $type,
            'category_id' => $categoryId,
            'amount' => $payload['amount'] ?? $movement->amount,
            'occurred_on' => $payload['occurred_on'] ?? $movement->occurred_on?->toDateString(),
            'description' => array_key_exists('description', $payload) ? $payload['description'] : $movement->description,
            'product_id' => array_key_exists('product_id', $payload) ? $payload['product_id'] : $movement->product_id,
        ]);
        $movement->save();

        return $this->serializeMovement($movement->load('category'));
    }

    public function deleteMovement(UserAuth $user, int $movementId): void
    {
        $this->findOwnedMovement($user, $movementId)->delete();
    }

    /**
     * @param array{from?: string|null, to?: string|null} $filters
     * @return array<string, mixed>
     */
    public function summary(UserAuth $user, array $filters = []): array
    {
        $settings = $this->ensureSettings($user);
        [$from, $to] = $this->resolvePeriod($filters['from'] ?? null, $filters['to'] ?? null);

        $movements = FinanceMovement::query()
            ->where('user_auth_id', $user->id)
            ->whereBetween('occurred_on', [$from->toDateString(), $to->toDateString()])
            ->get(['type', 'amount', 'occurred_on']);

        $ingresos = (float) $movements->where('type', 'ingreso')->sum('amount');
        $egresos = (float) $movements->where('type', 'egreso')->sum('amount');
        $inversiones = (float) $movements->where('type', 'inversion')->sum('amount');
        $net = $ingresos - $egresos - $inversiones;
        $estimatedBalance = (float) $settings->opening_balance + $net;

        $labels = [];
        $ingresosSeries = [];
        $egresosSeries = [];
        $cursor = $from->copy();

        while ($cursor->lte($to)) {
            $key = $cursor->toDateString();
            $labels[] = $cursor->format('d/m');
            $dayMovements = $movements->filter(
                static fn (FinanceMovement $movement): bool => $movement->occurred_on?->toDateString() === $key,
            );
            $ingresosSeries[] = round((float) $dayMovements->where('type', 'ingreso')->sum('amount'), 2);
            $egresosSeries[] = round(
                (float) $dayMovements->whereIn('type', ['egreso', 'inversion'])->sum('amount'),
                2,
            );
            $cursor->addDay();
        }

        return [
            'currency' => $settings->currency,
            'opening_balance' => (float) $settings->opening_balance,
            'period' => [
                'from' => $from->toDateString(),
                'to' => $to->toDateString(),
            ],
            'totals' => [
                'ingresos' => round($ingresos, 2),
                'egresos' => round($egresos, 2),
                'inversiones' => round($inversiones, 2),
                'neto' => round($net, 2),
                'saldo_estimado' => round($estimatedBalance, 2),
            ],
            'alerts' => [
                'egresos_superan_ingresos' => ($egresos + $inversiones) > $ingresos && ($ingresos + $egresos + $inversiones) > 0,
            ],
            'series' => [
                'labels' => $labels,
                'ingresos' => $ingresosSeries,
                'egresos' => $egresosSeries,
            ],
        ];
    }

    /** @return list<array<string, mixed>> */
    public function listFixedCosts(UserAuth $user): array
    {
        return FinanceFixedCost::query()
            ->where('user_auth_id', $user->id)
            ->orderByDesc('is_active')
            ->orderBy('name')
            ->get()
            ->map(fn (FinanceFixedCost $cost): array => $this->serializeFixedCost($cost))
            ->values()
            ->all();
    }

    /** @param array<string, mixed> $payload */
    public function createFixedCost(UserAuth $user, array $payload): array
    {
        $cost = FinanceFixedCost::query()->create([
            'user_auth_id' => $user->id,
            'name' => trim($payload['name']),
            'amount' => (float) $payload['amount'],
            'frequency' => $payload['frequency'] ?? 'mensual',
            'is_active' => $payload['is_active'] ?? true,
        ]);

        return $this->serializeFixedCost($cost);
    }

    /** @param array<string, mixed> $payload */
    public function updateFixedCost(UserAuth $user, int $costId, array $payload): array
    {
        $cost = $this->findOwnedFixedCost($user, $costId);
        $cost->fill([
            'name' => array_key_exists('name', $payload) ? trim((string) $payload['name']) : $cost->name,
            'amount' => $payload['amount'] ?? $cost->amount,
            'frequency' => $payload['frequency'] ?? $cost->frequency,
            'is_active' => array_key_exists('is_active', $payload) ? (bool) $payload['is_active'] : $cost->is_active,
        ]);
        $cost->save();

        return $this->serializeFixedCost($cost);
    }

    public function deleteFixedCost(UserAuth $user, int $costId): void
    {
        $this->findOwnedFixedCost($user, $costId)->delete();
    }

    public function monthlyFixedCostsTotal(UserAuth $user): float
    {
        return round(
            (float) FinanceFixedCost::query()
                ->where('user_auth_id', $user->id)
                ->where('is_active', true)
                ->get()
                ->sum(static fn (FinanceFixedCost $cost): float => $cost->monthlyAmount()),
            2,
        );
    }

    /** @return list<array<string, mixed>> */
    public function listPricingItems(UserAuth $user): array
    {
        return FinancePricingItem::query()
            ->where('user_auth_id', $user->id)
            ->orderByDesc('updated_at')
            ->get()
            ->map(fn (FinancePricingItem $item): array => $this->serializePricingItem($item))
            ->values()
            ->all();
    }

    /** @param array<string, mixed> $payload */
    public function createPricingItem(UserAuth $user, array $payload): array
    {
        $this->assertOptionalProduct($user, $payload['product_id'] ?? null);
        $competitors = $this->validatedCompetitors($payload['competitors'] ?? []);
        $preview = $this->calculator->pricingPreview(
            (float) ($payload['variable_cost'] ?? 0),
            (float) ($payload['extra_costs'] ?? 0),
            (string) ($payload['mode'] ?? 'margen'),
            (float) ($payload['target_percent'] ?? 30),
        );

        $item = FinancePricingItem::query()->create([
            'user_auth_id' => $user->id,
            'name' => trim($payload['name']),
            'variable_cost' => $preview['variable_cost'],
            'extra_costs' => $preview['extra_costs'],
            'mode' => $preview['mode'],
            'target_percent' => $preview['target_percent'],
            'suggested_price' => $preview['suggested_price'],
            'notes' => $payload['notes'] ?? null,
            'product_id' => $payload['product_id'] ?? null,
            'competitors_json' => $competitors,
        ]);

        return $this->serializePricingItem($item);
    }

    /** @param array<string, mixed> $payload */
    public function updatePricingItem(UserAuth $user, int $itemId, array $payload): array
    {
        $item = $this->findOwnedPricingItem($user, $itemId);
        $this->assertOptionalProduct($user, $payload['product_id'] ?? $item->product_id);
        $competitors = array_key_exists('competitors', $payload)
            ? $this->validatedCompetitors($payload['competitors'] ?? [])
            : ($item->competitors_json ?? []);

        $preview = $this->calculator->pricingPreview(
            (float) ($payload['variable_cost'] ?? $item->variable_cost),
            (float) ($payload['extra_costs'] ?? $item->extra_costs),
            (string) ($payload['mode'] ?? $item->mode),
            (float) ($payload['target_percent'] ?? $item->target_percent),
        );

        $item->fill([
            'name' => array_key_exists('name', $payload) ? trim((string) $payload['name']) : $item->name,
            'variable_cost' => $preview['variable_cost'],
            'extra_costs' => $preview['extra_costs'],
            'mode' => $preview['mode'],
            'target_percent' => $preview['target_percent'],
            'suggested_price' => $preview['suggested_price'],
            'notes' => array_key_exists('notes', $payload) ? $payload['notes'] : $item->notes,
            'product_id' => array_key_exists('product_id', $payload) ? $payload['product_id'] : $item->product_id,
            'competitors_json' => $competitors,
        ]);
        $item->save();

        return $this->serializePricingItem($item);
    }

    public function deletePricingItem(UserAuth $user, int $itemId): void
    {
        $this->findOwnedPricingItem($user, $itemId)->delete();
    }

    /** @param array<string, mixed> $payload */
    public function pricingPreview(array $payload): array
    {
        $preview = $this->calculator->pricingPreview(
            (float) ($payload['variable_cost'] ?? 0),
            (float) ($payload['extra_costs'] ?? 0),
            (string) ($payload['mode'] ?? 'margen'),
            (float) ($payload['target_percent'] ?? 30),
        );

        $competitors = $this->calculator->normalizeCompetitors(
            is_array($payload['competitors'] ?? null) ? $payload['competitors'] : [],
        );
        $preview['competitor_band'] = $this->calculator->competitorBand(
            $competitors,
            (float) $preview['suggested_price'],
        );

        return $preview;
    }

    /** @param array<string, mixed> $payload */
    public function breakEvenPreview(UserAuth $user, array $payload): array
    {
        $fixed = array_key_exists('fixed_costs_monthly', $payload)
            ? (float) $payload['fixed_costs_monthly']
            : $this->monthlyFixedCostsTotal($user);

        return $this->calculator->breakEvenPreview(
            $fixed,
            (float) ($payload['price'] ?? 0),
            (float) ($payload['variable_cost'] ?? 0),
            (float) ($payload['current_sales_revenue'] ?? 0),
        );
    }

    /** @return list<array{id: int, title: string, price: float|null}> */
    public function productReferences(UserAuth $user): array
    {
        $integration = $this->integrationAccess->integrationForUser($user);

        if ($integration === null || $integration->status !== 'active') {
            return [];
        }

        return ApiProduct::query()
            ->where('api_integration_id', $integration->id)
            ->orderBy('title')
            ->limit(100)
            ->get(['id', 'title', 'price'])
            ->map(static fn (ApiProduct $product): array => [
                'id' => (int) $product->id,
                'title' => (string) $product->title,
                'price' => $product->price !== null ? (float) $product->price : null,
            ])
            ->values()
            ->all();
    }

    /** @return array<string, mixed> */
    public function projectionBaseline(UserAuth $user): array
    {
        $settings = $this->ensureSettings($user);
        $to = now()->startOfDay();
        $from = $to->copy()->subMonths(2)->startOfMonth();

        $movements = FinanceMovement::query()
            ->where('user_auth_id', $user->id)
            ->whereBetween('occurred_on', [$from->toDateString(), $to->toDateString()])
            ->get(['type', 'amount', 'occurred_on']);

        $monthKeys = [];
        $cursor = $from->copy()->startOfMonth();
        while ($cursor->lte($to)) {
            $monthKeys[$cursor->format('Y-m')] = true;
            $cursor->addMonth();
        }
        $monthCount = max(1, count($monthKeys));

        $ingresos = (float) $movements->where('type', 'ingreso')->sum('amount');
        $egresos = (float) $movements->where('type', 'egreso')->sum('amount');
        $inversiones = (float) $movements->where('type', 'inversion')->sum('amount');
        $fixed = $this->monthlyFixedCostsTotal($user);

        return [
            'lookback' => [
                'from' => $from->toDateString(),
                'to' => $to->toDateString(),
                'months' => $monthCount,
            ],
            'baseline_monthly_income' => round($ingresos / $monthCount, 2),
            'baseline_monthly_expense' => round($egresos / $monthCount, 2),
            'baseline_monthly_investment' => round($inversiones / $monthCount, 2),
            'fixed_costs_monthly' => $fixed,
            'opening_balance' => (float) $settings->opening_balance,
            'currency' => $settings->currency,
            'suggested_months' => 6,
            'suggested_growth_income_percent' => 5,
            'suggested_growth_expense_percent' => 2,
        ];
    }

    /** @param array<string, mixed> $payload */
    public function projectionPreview(UserAuth $user, array $payload): array
    {
        $baseline = $this->projectionBaseline($user);
        $seasonality = $this->normalizeSeasonality($payload['seasonality'] ?? null);

        return $this->calculator->projectCashflow(
            (int) ($payload['months'] ?? $baseline['suggested_months']),
            (float) ($payload['baseline_monthly_income'] ?? $baseline['baseline_monthly_income']),
            (float) ($payload['baseline_monthly_expense'] ?? $baseline['baseline_monthly_expense']),
            (float) ($payload['baseline_monthly_investment'] ?? $baseline['baseline_monthly_investment']),
            (float) ($payload['fixed_costs_monthly'] ?? $baseline['fixed_costs_monthly']),
            (float) ($payload['growth_income_percent'] ?? $baseline['suggested_growth_income_percent']),
            (float) ($payload['growth_expense_percent'] ?? $baseline['suggested_growth_expense_percent']),
            (float) ($payload['opening_balance'] ?? $baseline['opening_balance']),
            (bool) ($payload['include_fixed_costs'] ?? true),
            $seasonality,
        );
    }

    /** @return list<array<string, mixed>> */
    public function listProjections(UserAuth $user): array
    {
        return FinanceProjection::query()
            ->where('user_auth_id', $user->id)
            ->orderByDesc('updated_at')
            ->get()
            ->map(fn (FinanceProjection $projection): array => $this->serializeProjection($projection))
            ->values()
            ->all();
    }

    /** @param array<string, mixed> $payload */
    public function createProjection(UserAuth $user, array $payload): array
    {
        $preview = $this->projectionPreview($user, $payload);

        $projection = FinanceProjection::query()->create([
            'user_auth_id' => $user->id,
            'name' => trim((string) $payload['name']),
            'months' => $preview['months'],
            'assumptions_json' => $preview['assumptions'],
            'series_json' => [
                'series' => $preview['series'],
                'totals' => $preview['totals'],
            ],
            'notes' => $payload['notes'] ?? null,
        ]);

        return $this->serializeProjection($projection);
    }

    /** @param array<string, mixed> $payload */
    public function updateProjection(UserAuth $user, int $projectionId, array $payload): array
    {
        $projection = $this->findOwnedProjection($user, $projectionId);
        $merged = array_merge($projection->assumptions_json ?? [], $payload);
        $preview = $this->projectionPreview($user, $merged);

        $projection->fill([
            'name' => array_key_exists('name', $payload) ? trim((string) $payload['name']) : $projection->name,
            'months' => $preview['months'],
            'assumptions_json' => $preview['assumptions'],
            'series_json' => [
                'series' => $preview['series'],
                'totals' => $preview['totals'],
            ],
            'notes' => array_key_exists('notes', $payload) ? $payload['notes'] : $projection->notes,
        ]);
        $projection->save();

        return $this->serializeProjection($projection);
    }

    public function deleteProjection(UserAuth $user, int $projectionId): void
    {
        $this->findOwnedProjection($user, $projectionId)->delete();
    }

    /** @param array<string, mixed> $payload */
    public function scenarioPreview(UserAuth $user, array $payload): array
    {
        $baseline = $this->projectionBaseline($user);
        $input = [
            'months' => (int) ($payload['months'] ?? $baseline['suggested_months']),
            'baseline_monthly_income' => (float) ($payload['baseline_monthly_income'] ?? $baseline['baseline_monthly_income']),
            'baseline_monthly_expense' => (float) ($payload['baseline_monthly_expense'] ?? $baseline['baseline_monthly_expense']),
            'baseline_monthly_investment' => (float) ($payload['baseline_monthly_investment'] ?? $baseline['baseline_monthly_investment']),
            'fixed_costs_monthly' => (float) ($payload['fixed_costs_monthly'] ?? $baseline['fixed_costs_monthly']),
            'growth_income_percent' => (float) ($payload['growth_income_percent'] ?? $baseline['suggested_growth_income_percent']),
            'growth_expense_percent' => (float) ($payload['growth_expense_percent'] ?? $baseline['suggested_growth_expense_percent']),
            'opening_balance' => (float) ($payload['opening_balance'] ?? $baseline['opening_balance']),
            'include_fixed_costs' => (bool) ($payload['include_fixed_costs'] ?? true),
            'seasonality' => $this->normalizeSeasonality($payload['seasonality'] ?? null),
            'price_change_percent' => (float) ($payload['price_change_percent'] ?? 0),
            'volume_change_percent' => (float) ($payload['volume_change_percent'] ?? 0),
            'fixed_cost_change_percent' => (float) ($payload['fixed_cost_change_percent'] ?? 0),
            'expense_change_percent' => (float) ($payload['expense_change_percent'] ?? 0),
        ];

        return $this->calculator->scenarioPreview($input);
    }

    /** @return list<array<string, mixed>> */
    public function listScenarios(UserAuth $user): array
    {
        return FinanceScenario::query()
            ->where('user_auth_id', $user->id)
            ->orderByDesc('is_baseline')
            ->orderByDesc('updated_at')
            ->get()
            ->map(fn (FinanceScenario $scenario): array => $this->serializeScenario($scenario))
            ->values()
            ->all();
    }

    /** @param array<string, mixed> $payload */
    public function createScenario(UserAuth $user, array $payload): array
    {
        $preview = $this->scenarioPreview($user, $payload);
        $isBaseline = (bool) ($payload['is_baseline'] ?? false);

        if ($isBaseline) {
            FinanceScenario::query()
                ->where('user_auth_id', $user->id)
                ->where('is_baseline', true)
                ->update(['is_baseline' => false]);
        }

        $scenario = FinanceScenario::query()->create([
            'user_auth_id' => $user->id,
            'name' => trim((string) $payload['name']),
            'description' => $payload['description'] ?? null,
            'is_baseline' => $isBaseline,
            'months' => (int) ($preview['projection']['months'] ?? 6),
            'assumptions_json' => [
                ...($preview['projection']['assumptions'] ?? []),
                'deltas' => $preview['deltas'],
            ],
            'result_json' => $preview,
        ]);

        return $this->serializeScenario($scenario);
    }

    /** @param array<string, mixed> $payload */
    public function updateScenario(UserAuth $user, int $scenarioId, array $payload): array
    {
        $scenario = $this->findOwnedScenario($user, $scenarioId);
        $merged = array_merge(
            $scenario->assumptions_json ?? [],
            $scenario->assumptions_json['deltas'] ?? [],
            $payload,
        );
        $preview = $this->scenarioPreview($user, $merged);
        $isBaseline = array_key_exists('is_baseline', $payload)
            ? (bool) $payload['is_baseline']
            : (bool) $scenario->is_baseline;

        if ($isBaseline) {
            FinanceScenario::query()
                ->where('user_auth_id', $user->id)
                ->where('id', '!=', $scenario->id)
                ->where('is_baseline', true)
                ->update(['is_baseline' => false]);
        }

        $scenario->fill([
            'name' => array_key_exists('name', $payload) ? trim((string) $payload['name']) : $scenario->name,
            'description' => array_key_exists('description', $payload) ? $payload['description'] : $scenario->description,
            'is_baseline' => $isBaseline,
            'months' => (int) ($preview['projection']['months'] ?? $scenario->months),
            'assumptions_json' => [
                ...($preview['projection']['assumptions'] ?? []),
                'deltas' => $preview['deltas'],
            ],
            'result_json' => $preview,
        ]);
        $scenario->save();

        return $this->serializeScenario($scenario);
    }

    public function deleteScenario(UserAuth $user, int $scenarioId): void
    {
        $this->findOwnedScenario($user, $scenarioId)->delete();
    }

    /**
     * @param list<int> $scenarioIds
     * @return array<string, mixed>
     */
    public function compareScenarios(UserAuth $user, array $scenarioIds): array
    {
        $ids = array_values(array_unique(array_map('intval', $scenarioIds)));
        $scenarios = FinanceScenario::query()
            ->where('user_auth_id', $user->id)
            ->whereIn('id', $ids)
            ->get();

        if ($scenarios->count() < 2) {
            throw ValidationException::withMessages([
                'scenario_ids' => 'Necesitás al menos 2 escenarios guardados para comparar.',
            ]);
        }

        return [
            'scenarios' => $scenarios
                ->map(fn (FinanceScenario $scenario): array => $this->serializeScenario($scenario))
                ->values()
                ->all(),
        ];
    }

    private function ensureSettings(UserAuth $user): FinanceSetting
    {
        return FinanceSetting::query()->firstOrCreate(
            ['user_auth_id' => $user->id],
            ['currency' => 'ARS', 'opening_balance' => 0],
        );
    }

    private function findOwnedMovement(UserAuth $user, int $movementId): FinanceMovement
    {
        $movement = FinanceMovement::query()
            ->where('user_auth_id', $user->id)
            ->where('id', $movementId)
            ->first();

        if ($movement === null) {
            throw new NotFoundHttpException('No encontramos ese movimiento.');
        }

        return $movement;
    }

    private function findOwnedFixedCost(UserAuth $user, int $costId): FinanceFixedCost
    {
        $cost = FinanceFixedCost::query()
            ->where('user_auth_id', $user->id)
            ->where('id', $costId)
            ->first();

        if ($cost === null) {
            throw new NotFoundHttpException('No encontramos ese costo fijo.');
        }

        return $cost;
    }

    private function findOwnedPricingItem(UserAuth $user, int $itemId): FinancePricingItem
    {
        $item = FinancePricingItem::query()
            ->where('user_auth_id', $user->id)
            ->where('id', $itemId)
            ->first();

        if ($item === null) {
            throw new NotFoundHttpException('No encontramos ese ítem de precio.');
        }

        return $item;
    }

    private function findOwnedProjection(UserAuth $user, int $projectionId): FinanceProjection
    {
        $projection = FinanceProjection::query()
            ->where('user_auth_id', $user->id)
            ->where('id', $projectionId)
            ->first();

        if ($projection === null) {
            throw new NotFoundHttpException('No encontramos esa proyección.');
        }

        return $projection;
    }

    private function findOwnedScenario(UserAuth $user, int $scenarioId): FinanceScenario
    {
        $scenario = FinanceScenario::query()
            ->where('user_auth_id', $user->id)
            ->where('id', $scenarioId)
            ->first();

        if ($scenario === null) {
            throw new NotFoundHttpException('No encontramos ese escenario.');
        }

        return $scenario;
    }

    /** @return list<float>|null */
    private function normalizeSeasonality(mixed $seasonality): ?array
    {
        if (! is_array($seasonality) || count($seasonality) !== 12) {
            return null;
        }

        return array_map(static fn ($value): float => max(0.0, (float) $value), array_values($seasonality));
    }

    private function assertCategoryForUser(UserAuth $user, int $categoryId, string $type): void
    {
        $exists = FinanceCategory::query()
            ->where('id', $categoryId)
            ->where('type', $type)
            ->where('is_active', true)
            ->where(function ($builder) use ($user): void {
                $builder->whereNull('user_auth_id')
                    ->orWhere('user_auth_id', $user->id);
            })
            ->exists();

        if (! $exists) {
            throw ValidationException::withMessages([
                'category_id' => 'La categoría no es válida para ese tipo de movimiento.',
            ]);
        }
    }

    private function assertOptionalProduct(UserAuth $user, mixed $productId): void
    {
        if ($productId === null || $productId === '') {
            return;
        }

        $integration = $this->integrationAccess->integrationForUser($user);

        if ($integration === null) {
            throw ValidationException::withMessages([
                'product_id' => 'No tenés una integración activa para referenciar productos.',
            ]);
        }

        $exists = ApiProduct::query()
            ->where('id', (int) $productId)
            ->where('api_integration_id', $integration->id)
            ->exists();

        if (! $exists) {
            throw ValidationException::withMessages([
                'product_id' => 'El producto de referencia no existe o no te pertenece.',
            ]);
        }
    }

    /** @return array{0: Carbon, 1: Carbon} */
    private function resolvePeriod(?string $from, ?string $to): array
    {
        $end = $to ? Carbon::parse($to)->startOfDay() : now()->startOfDay();
        $start = $from ? Carbon::parse($from)->startOfDay() : $end->copy()->startOfMonth();

        if ($start->gt($end)) {
            [$start, $end] = [$end, $start];
        }

        return [$start, $end];
    }

    /** @return array<string, mixed> */
    private function serializeSettings(FinanceSetting $settings): array
    {
        return [
            'currency' => $settings->currency,
            'opening_balance' => (float) $settings->opening_balance,
        ];
    }

    /** @return array<string, mixed> */
    private function serializeCategory(FinanceCategory $category): array
    {
        return [
            'id' => (int) $category->id,
            'type' => $category->type,
            'name' => $category->name,
            'is_system' => $category->user_auth_id === null,
        ];
    }

    /** @return array<string, mixed> */
    private function serializeMovement(FinanceMovement $movement): array
    {
        return [
            'id' => (int) $movement->id,
            'type' => $movement->type,
            'category_id' => (int) $movement->category_id,
            'category_name' => $movement->category?->name,
            'amount' => (float) $movement->amount,
            'occurred_on' => $movement->occurred_on?->toDateString(),
            'description' => $movement->description,
            'product_id' => $movement->product_id !== null ? (int) $movement->product_id : null,
        ];
    }

    /** @return array<string, mixed> */
    private function serializeFixedCost(FinanceFixedCost $cost): array
    {
        return [
            'id' => (int) $cost->id,
            'name' => $cost->name,
            'amount' => (float) $cost->amount,
            'frequency' => $cost->frequency,
            'is_active' => (bool) $cost->is_active,
            'monthly_amount' => round($cost->monthlyAmount(), 2),
        ];
    }

    /** @return array<string, mixed> */
    private function serializePricingItem(FinancePricingItem $item): array
    {
        return [
            'id' => (int) $item->id,
            'name' => $item->name,
            'variable_cost' => (float) $item->variable_cost,
            'extra_costs' => (float) $item->extra_costs,
            'mode' => $item->mode,
            'target_percent' => (float) $item->target_percent,
            'suggested_price' => (float) $item->suggested_price,
            'notes' => $item->notes,
            'product_id' => $item->product_id !== null ? (int) $item->product_id : null,
            'competitors' => is_array($item->competitors_json) ? $item->competitors_json : [],
        ];
    }

    /**
     * @param mixed $competitors
     * @return list<array{name: string, price: float, description: string|null}>
     */
    private function validatedCompetitors(mixed $competitors): array
    {
        $rows = $this->calculator->normalizeCompetitors(is_array($competitors) ? $competitors : []);
        $complete = array_filter(
            $rows,
            static fn (array $row): bool => $row['name'] !== '' && $row['price'] > 0,
        );

        if (count($complete) < 3) {
            throw ValidationException::withMessages([
                'competitors' => ['Completá al menos 3 precios de la competencia (nombre y precio).'],
            ]);
        }

        return $rows;
    }

    /** @return array<string, mixed> */
    private function serializeProjection(FinanceProjection $projection): array
    {
        $seriesPayload = $projection->series_json ?? [];

        return [
            'id' => (int) $projection->id,
            'name' => $projection->name,
            'months' => (int) $projection->months,
            'assumptions' => $projection->assumptions_json ?? [],
            'series' => $seriesPayload['series'] ?? [],
            'totals' => $seriesPayload['totals'] ?? [],
            'notes' => $projection->notes,
            'updated_at' => $projection->updated_at?->toIso8601String(),
        ];
    }

    /** @return array<string, mixed> */
    private function serializeScenario(FinanceScenario $scenario): array
    {
        return [
            'id' => (int) $scenario->id,
            'name' => $scenario->name,
            'description' => $scenario->description,
            'is_baseline' => (bool) $scenario->is_baseline,
            'months' => (int) $scenario->months,
            'assumptions' => $scenario->assumptions_json ?? [],
            'result' => $scenario->result_json ?? [],
            'updated_at' => $scenario->updated_at?->toIso8601String(),
        ];
    }
}
