<?php

namespace App\Support;

class FinanceCalculator
{
    /**
     * @return array{
     *   total_cost: float,
     *   variable_cost: float,
     *   extra_costs: float,
     *   mode: string,
     *   target_percent: float,
     *   suggested_price: float,
     *   unit_contribution: float,
     *   contribution_margin_percent: float|null
     * }
     */
    public function pricingPreview(
        float $variableCost,
        float $extraCosts,
        string $mode,
        float $targetPercent,
    ): array {
        $totalCost = round($variableCost + $extraCosts, 2);
        $percent = max(0.0, $targetPercent);
        $mode = $mode === 'markup' ? 'markup' : 'margen';

        if ($mode === 'markup') {
            $suggested = $totalCost * (1 + ($percent / 100));
        } else {
            if ($percent >= 100) {
                $suggested = 0.0;
            } else {
                $suggested = $totalCost / (1 - ($percent / 100));
            }
        }

        $suggested = round(max(0.0, $suggested), 2);
        $contribution = round($suggested - $variableCost, 2);
        $marginPercent = $suggested > 0
            ? round(($contribution / $suggested) * 100, 2)
            : null;

        return [
            'total_cost' => $totalCost,
            'variable_cost' => round($variableCost, 2),
            'extra_costs' => round($extraCosts, 2),
            'mode' => $mode,
            'target_percent' => round($percent, 2),
            'suggested_price' => $suggested,
            'unit_contribution' => $contribution,
            'contribution_margin_percent' => $marginPercent,
        ];
    }

    /**
     * @param list<array{name?: string, price?: float|int|string|null, description?: string|null}> $competitors
     * @return list<array{name: string, price: float, description: string|null}>
     */
    public function normalizeCompetitors(array $competitors): array
    {
        $rows = [];

        foreach (array_slice($competitors, 0, 6) as $row) {
            if (! is_array($row)) {
                continue;
            }

            $name = trim((string) ($row['name'] ?? ''));
            $price = round(max(0.0, (float) ($row['price'] ?? 0)), 2);
            $description = trim((string) ($row['description'] ?? ''));

            if ($name === '' && $price <= 0 && $description === '') {
                continue;
            }

            $rows[] = [
                'name' => $name,
                'price' => $price,
                'description' => $description !== '' ? $description : null,
            ];
        }

        return $rows;
    }

    /**
     * @param list<array{name: string, price: float, description: string|null}> $competitors
     * @return array{
     *   min: float,
     *   max: float,
     *   avg: float,
     *   count: int,
     *   position: 'below'|'within'|'above'|null,
     *   message: string|null
     * }|null
     */
    public function competitorBand(array $competitors, float $suggestedPrice): ?array
    {
        $prices = array_values(array_filter(
            array_map(static fn (array $row): float => (float) $row['price'], $competitors),
            static fn (float $price): bool => $price > 0,
        ));

        if ($prices === []) {
            return null;
        }

        $min = round(min($prices), 2);
        $max = round(max($prices), 2);
        $avg = round(array_sum($prices) / count($prices), 2);
        $position = null;
        $message = null;

        if ($suggestedPrice > 0) {
            if ($suggestedPrice < $min) {
                $position = 'below';
                $message = 'Estás por debajo de la competencia; podrías estar dejando margen.';
            } elseif ($suggestedPrice > $max) {
                $position = 'above';
                $message = 'Estás por encima; revisá si tu propuesta de valor lo justifica.';
            } else {
                $position = 'within';
                $message = 'Estás dentro de la banda de la competencia.';
            }
        }

        return [
            'min' => $min,
            'max' => $max,
            'avg' => $avg,
            'count' => count($prices),
            'position' => $position,
            'message' => $message,
        ];
    }

    /**
     * @return array{
     *   fixed_costs_monthly: float,
     *   price: float,
     *   variable_cost: float,
     *   unit_contribution: float,
     *   break_even_units: float|null,
     *   break_even_revenue: float|null,
     *   current_sales_revenue: float,
     *   margin_of_safety_percent: float|null,
     *   is_viable: bool
     * }
     */
    public function breakEvenPreview(
        float $fixedCostsMonthly,
        float $price,
        float $variableCost,
        float $currentSalesRevenue = 0.0,
    ): array {
        $fixed = round(max(0.0, $fixedCostsMonthly), 2);
        $price = round(max(0.0, $price), 2);
        $variableCost = round(max(0.0, $variableCost), 2);
        $currentSales = round(max(0.0, $currentSalesRevenue), 2);
        $contribution = round($price - $variableCost, 2);
        $viable = $contribution > 0;

        $breakEvenUnits = null;
        $breakEvenRevenue = null;
        $marginOfSafety = null;

        if ($viable) {
            $breakEvenUnits = round($fixed / $contribution, 2);
            $breakEvenRevenue = round($breakEvenUnits * $price, 2);

            if ($currentSales > 0) {
                $marginOfSafety = round((($currentSales - $breakEvenRevenue) / $currentSales) * 100, 2);
            }
        }

        return [
            'fixed_costs_monthly' => $fixed,
            'price' => $price,
            'variable_cost' => $variableCost,
            'unit_contribution' => $contribution,
            'break_even_units' => $breakEvenUnits,
            'break_even_revenue' => $breakEvenRevenue,
            'current_sales_revenue' => $currentSales,
            'margin_of_safety_percent' => $marginOfSafety,
            'is_viable' => $viable,
        ];
    }

    /**
     * @param list<float>|null $seasonality 12 multipliers (1.0 = normal), or null
     * @return array{
     *   months: int,
     *   assumptions: array<string, mixed>,
     *   series: array{
     *     labels: list<string>,
     *     ingresos: list<float>,
     *     egresos: list<float>,
     *     inversiones: list<float>,
     *     neto: list<float>,
     *     saldo: list<float>
     *   },
     *   totals: array{ingresos: float, egresos: float, inversiones: float, neto: float, saldo_final: float}
     * }
     */
    public function projectCashflow(
        int $months,
        float $baselineMonthlyIncome,
        float $baselineMonthlyExpense,
        float $baselineMonthlyInvestment,
        float $fixedCostsMonthly,
        float $growthIncomePercent,
        float $growthExpensePercent,
        float $openingBalance = 0.0,
        bool $includeFixedCosts = true,
        ?array $seasonality = null,
        ?string $startMonth = null,
    ): array {
        $months = max(1, min(24, $months));
        $incomeBase = max(0.0, $baselineMonthlyIncome);
        $expenseBase = max(0.0, $baselineMonthlyExpense);
        $investmentBase = max(0.0, $baselineMonthlyInvestment);
        $fixed = $includeFixedCosts ? max(0.0, $fixedCostsMonthly) : 0.0;
        $growthIncome = $growthIncomePercent / 100;
        $growthExpense = $growthExpensePercent / 100;
        $cursor = $startMonth
            ? \Illuminate\Support\Carbon::parse($startMonth)->startOfMonth()
            : now()->startOfMonth()->addMonth();

        $labels = [];
        $ingresos = [];
        $egresos = [];
        $inversiones = [];
        $neto = [];
        $saldo = [];
        $running = $openingBalance;

        for ($i = 0; $i < $months; $i++) {
            $factor = $this->seasonalityFactor($seasonality, (int) $cursor->month);
            $income = round($incomeBase * ((1 + $growthIncome) ** $i) * $factor, 2);
            $expense = round(($expenseBase + $fixed) * ((1 + $growthExpense) ** $i) * $factor, 2);
            $investment = round($investmentBase * ((1 + $growthExpense) ** $i), 2);
            $net = round($income - $expense - $investment, 2);
            $running = round($running + $net, 2);

            $labels[] = $cursor->format('m/Y');
            $ingresos[] = $income;
            $egresos[] = $expense;
            $inversiones[] = $investment;
            $neto[] = $net;
            $saldo[] = $running;

            $cursor->addMonth();
        }

        $assumptions = [
            'baseline_monthly_income' => round($incomeBase, 2),
            'baseline_monthly_expense' => round($expenseBase, 2),
            'baseline_monthly_investment' => round($investmentBase, 2),
            'fixed_costs_monthly' => round($fixed, 2),
            'growth_income_percent' => round($growthIncomePercent, 2),
            'growth_expense_percent' => round($growthExpensePercent, 2),
            'opening_balance' => round($openingBalance, 2),
            'include_fixed_costs' => $includeFixedCosts,
            'seasonality' => $seasonality,
        ];

        return [
            'months' => $months,
            'assumptions' => $assumptions,
            'series' => [
                'labels' => $labels,
                'ingresos' => $ingresos,
                'egresos' => $egresos,
                'inversiones' => $inversiones,
                'neto' => $neto,
                'saldo' => $saldo,
            ],
            'totals' => [
                'ingresos' => round(array_sum($ingresos), 2),
                'egresos' => round(array_sum($egresos), 2),
                'inversiones' => round(array_sum($inversiones), 2),
                'neto' => round(array_sum($neto), 2),
                'saldo_final' => $saldo === [] ? round($openingBalance, 2) : $saldo[array_key_last($saldo)],
            ],
        ];
    }

    /**
     * What-if: part of a baseline projection and apply % deltas.
     *
     * @param array{
     *   months?: int,
     *   baseline_monthly_income?: float|int,
     *   baseline_monthly_expense?: float|int,
     *   baseline_monthly_investment?: float|int,
     *   fixed_costs_monthly?: float|int,
     *   growth_income_percent?: float|int,
     *   growth_expense_percent?: float|int,
     *   opening_balance?: float|int,
     *   include_fixed_costs?: bool,
     *   seasonality?: list<float>|null,
     *   price_change_percent?: float|int,
     *   volume_change_percent?: float|int,
     *   fixed_cost_change_percent?: float|int,
     *   expense_change_percent?: float|int
     * } $input
     * @return array<string, mixed>
     */
    public function scenarioPreview(array $input): array
    {
        $months = (int) ($input['months'] ?? 6);
        $income = (float) ($input['baseline_monthly_income'] ?? 0);
        $expense = (float) ($input['baseline_monthly_expense'] ?? 0);
        $investment = (float) ($input['baseline_monthly_investment'] ?? 0);
        $fixed = (float) ($input['fixed_costs_monthly'] ?? 0);
        $growthIncome = (float) ($input['growth_income_percent'] ?? 0);
        $growthExpense = (float) ($input['growth_expense_percent'] ?? 0);
        $opening = (float) ($input['opening_balance'] ?? 0);
        $includeFixed = (bool) ($input['include_fixed_costs'] ?? true);
        $seasonality = $input['seasonality'] ?? null;

        $priceChange = (float) ($input['price_change_percent'] ?? 0);
        $volumeChange = (float) ($input['volume_change_percent'] ?? 0);
        $fixedChange = (float) ($input['fixed_cost_change_percent'] ?? 0);
        $expenseChange = (float) ($input['expense_change_percent'] ?? 0);

        $adjustedIncome = $income * (1 + ($priceChange / 100)) * (1 + ($volumeChange / 100));
        $adjustedExpense = $expense * (1 + ($expenseChange / 100));
        $adjustedFixed = $fixed * (1 + ($fixedChange / 100));

        $projection = $this->projectCashflow(
            $months,
            $adjustedIncome,
            $adjustedExpense,
            $investment,
            $adjustedFixed,
            $growthIncome,
            $growthExpense,
            $opening,
            $includeFixed,
            is_array($seasonality) ? $seasonality : null,
        );

        $baseline = $this->projectCashflow(
            $months,
            $income,
            $expense,
            $investment,
            $fixed,
            $growthIncome,
            $growthExpense,
            $opening,
            $includeFixed,
            is_array($seasonality) ? $seasonality : null,
        );

        return [
            'deltas' => [
                'price_change_percent' => round($priceChange, 2),
                'volume_change_percent' => round($volumeChange, 2),
                'fixed_cost_change_percent' => round($fixedChange, 2),
                'expense_change_percent' => round($expenseChange, 2),
            ],
            'adjusted_baselines' => [
                'baseline_monthly_income' => round($adjustedIncome, 2),
                'baseline_monthly_expense' => round($adjustedExpense, 2),
                'fixed_costs_monthly' => round($adjustedFixed, 2),
            ],
            'projection' => $projection,
            'baseline_projection' => $baseline,
            'delta_vs_baseline' => [
                'neto' => round($projection['totals']['neto'] - $baseline['totals']['neto'], 2),
                'saldo_final' => round($projection['totals']['saldo_final'] - $baseline['totals']['saldo_final'], 2),
                'ingresos' => round($projection['totals']['ingresos'] - $baseline['totals']['ingresos'], 2),
                'egresos' => round($projection['totals']['egresos'] - $baseline['totals']['egresos'], 2),
            ],
        ];
    }

    /** @param list<float>|null $seasonality */
    private function seasonalityFactor(?array $seasonality, int $month): float
    {
        if ($seasonality === null || $seasonality === []) {
            return 1.0;
        }

        $index = max(1, min(12, $month)) - 1;

        if (! isset($seasonality[$index])) {
            return 1.0;
        }

        $factor = (float) $seasonality[$index];

        return $factor > 0 ? $factor : 1.0;
    }
}
