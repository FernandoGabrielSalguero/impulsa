<?php

namespace Tests\Unit;

use App\Support\FinanceCalculator;
use PHPUnit\Framework\TestCase;

class FinanceCalculatorCompetitorBandTest extends TestCase
{
    public function test_competitor_band_reports_position_without_changing_price(): void
    {
        $calculator = new FinanceCalculator();
        $competitors = $calculator->normalizeCompetitors([
            ['name' => 'A', 'price' => 100, 'description' => ''],
            ['name' => 'B', 'price' => 200, 'description' => ''],
            ['name' => 'C', 'price' => 300, 'description' => ''],
        ]);

        $below = $calculator->competitorBand($competitors, 80);
        $within = $calculator->competitorBand($competitors, 200);
        $above = $calculator->competitorBand($competitors, 400);

        $this->assertSame('below', $below['position']);
        $this->assertSame('within', $within['position']);
        $this->assertSame('above', $above['position']);
        $this->assertSame(100.0, $within['min']);
        $this->assertSame(300.0, $within['max']);
    }
}
