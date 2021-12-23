<?php

namespace Tests\Unit;

use App\Models\TaxCalculator;
use Tests\TestCase;


class TaxCalculatorUnitTest extends TestCase
{
    /** @test */
    public function it_can_calculate_nis()
    {
        $calculator = new TaxCalculator(
            monthlyGross: '100000.00',
            nisPercent: '3',
            nisAnnualIncomeThreshold: '3000000.00'
        );

        $nisAmount = $calculator->nisAmount();

        $this->assertEquals('3000.00', TaxCalculator::formatAsString($nisAmount));
    }

    /** @test */
    public function it_calculates_nis_respecting_income_threshold()
    {
        $calculator = new TaxCalculator(
            monthlyGross: '264750.00',
            nisPercent: '3',
            nisAnnualIncomeThreshold: '3000000.00'
        );

        $nisAmount = $calculator->nisAmount();
        $this->assertEquals('7500.00', TaxCalculator::formatAsString($nisAmount));
    }
}
