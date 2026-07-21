<?php

namespace Tests\Unit;

use App\Support\FinanceCalculator;
use PHPUnit\Framework\TestCase;

class FinanceCalculatorTest extends TestCase
{
    private FinanceCalculator $calculator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calculator = new FinanceCalculator();
    }

    public function test_pricing_with_markup(): void
    {
        $result = $this->calculator->pricingPreview(100, 20, 'markup', 50);

        $this->assertSame(120.0, $result['total_cost']);
        $this->assertSame(180.0, $result['suggested_price']);
        $this->assertSame(80.0, $result['unit_contribution']);
    }

    public function test_pricing_with_margin(): void
    {
        $result = $this->calculator->pricingPreview(60, 0, 'margen', 40);

        $this->assertSame(60.0, $result['total_cost']);
        $this->assertSame(100.0, $result['suggested_price']);
        $this->assertSame(40.0, $result['unit_contribution']);
        $this->assertSame(40.0, $result['contribution_margin_percent']);
    }

    public function test_break_even_viable(): void
    {
        $result = $this->calculator->breakEvenPreview(10000, 100, 40, 20000);

        $this->assertTrue($result['is_viable']);
        $this->assertSame(60.0, $result['unit_contribution']);
        $this->assertSame(166.67, $result['break_even_units']);
        $this->assertSame(16667.0, $result['break_even_revenue']);
        $this->assertSame(16.67, $result['margin_of_safety_percent']);
    }

    public function test_break_even_not_viable_when_price_below_variable_cost(): void
    {
        $result = $this->calculator->breakEvenPreview(5000, 30, 50);

        $this->assertFalse($result['is_viable']);
        $this->assertNull($result['break_even_units']);
        $this->assertNull($result['break_even_revenue']);
    }

    public function test_project_cashflow_grows_and_accumulates_balance(): void
    {
        $result = $this->calculator->projectCashflow(
            3,
            1000,
            400,
            100,
            200,
            10,
            0,
            500,
            true,
            null,
            '2026-01-01',
        );

        $this->assertSame(3, $result['months']);
        $this->assertSame(1000.0, $result['series']['ingresos'][0]);
        $this->assertSame(1100.0, $result['series']['ingresos'][1]);
        $this->assertSame(600.0, $result['series']['egresos'][0]); // 400 base + 200 fijos
        $this->assertSame(300.0, $result['series']['neto'][0]); // 1000 - 600 - 100
        $this->assertSame(800.0, $result['series']['saldo'][0]); // 500 opening + 300
        $this->assertGreaterThan($result['series']['saldo'][0], $result['series']['saldo'][2]);
    }

    public function test_scenario_preview_applies_price_and_volume_deltas(): void
    {
        $result = $this->calculator->scenarioPreview([
            'months' => 2,
            'baseline_monthly_income' => 1000,
            'baseline_monthly_expense' => 300,
            'baseline_monthly_investment' => 0,
            'fixed_costs_monthly' => 0,
            'growth_income_percent' => 0,
            'growth_expense_percent' => 0,
            'opening_balance' => 0,
            'include_fixed_costs' => true,
            'price_change_percent' => 10,
            'volume_change_percent' => 10,
            'fixed_cost_change_percent' => 0,
            'expense_change_percent' => 0,
        ]);

        $this->assertSame(1210.0, $result['adjusted_baselines']['baseline_monthly_income']);
        $this->assertGreaterThan($result['baseline_projection']['totals']['neto'], $result['projection']['totals']['neto']);
        $this->assertGreaterThan(0, $result['delta_vs_baseline']['neto']);
    }
}
