<?php

namespace Tests\Unit;

use App\Models\IncomeTax;
use App\Models\NHT;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IncomeTaxUnitTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_gets_the_correct_income_tax_entry_for_a_given_date()
    {
        //Set up dates
        $incomeTaxJanToMarch = IncomeTax::factory()->create([
            'effective_date' => '2021-01-01'
        ]);
        $incomeTaxAprilToMay = IncomeTax::factory()->create([
            'effective_date' => '2021-04-01'
        ]);
        $incomeTaxJuneToDec = IncomeTax::factory()->create([
            'effective_date' => '2021-06-01'
        ]);

        $incomeTaxForApril = IncomeTax::findEntryForDate('2021-04-25');
        $this->assertEquals($incomeTaxForApril->effective_date, $incomeTaxAprilToMay->effective_date);
    }

    /** @test */
    public function it_defaults_to_current_date_when_no_date_is_specified()
    {
        //Set up dates
        $incomeTaxJanToMarch = IncomeTax::factory()->create([
            'effective_date' => '2021-01-01'
        ]);
        $incomeTaxAprilToMay = IncomeTax::factory()->create([
            'effective_date' => '2021-04-01'
        ]);
        $incomeTaxJuneToDec = IncomeTax::factory()->create([
            'effective_date' => '2021-06-01'
        ]);

        $this->travel(-1)->months();
        $incomeTaxForLastMonth = IncomeTax::findEntryForDate();
        $this->assertEquals($incomeTaxJuneToDec->effective_date, $incomeTaxForLastMonth->effective_date);

    }

    /** @test */
    public function it_returns_null_if_no_suitable_date_is_found()
    {

        //Set up dates
        $incomeTaxJanToMarch = IncomeTax::factory()->create([
            'effective_date' => '2021-01-01'
        ]);
        $incomeTaxAprilToMay = IncomeTax::factory()->create([
            'effective_date' => '2021-04-01'
        ]);
        $incomeTaxJuneToDec = IncomeTax::factory()->create([
            'effective_date' => '2021-06-01'
        ]);

        $incomeTaxTenYearsAgo = IncomeTax::findEntryForDate('2010-12-31');
        $this->assertNull($incomeTaxTenYearsAgo);
    }
}
