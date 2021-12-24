<?php

namespace Tests\Unit;


use App\Models\EducationTax;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EducationUnitTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_gets_the_correct_nis_entry_for_a_given_date()
    {
       //Set up dates
        $educationTaxJanToMarch = EducationTax::factory()->create([
            'effective_date' => '2021-01-01'
        ]);
        $educationTaxAprilToMay = EducationTax::factory()->create([
            'effective_date' => '2021-04-01'
        ]);
        $educationTaxJuneToDec = EducationTax::factory()->create([
            'effective_date' => '2021-06-01'
        ]);

        $educationTaxForApril = EducationTax::findEntryForDate('2021-04-25');
        $this->assertEquals($educationTaxForApril->effective_date, $educationTaxAprilToMay->effective_date);
    }

    /** @test */
    public function it_defaults_to_current_date_when_no_date_is_specified()
    {
        //Set up dates
        $educationTaxJanToMarch = EducationTax::factory()->create([
            'effective_date' => '2021-01-01'
        ]);
        $educationTaxAprilToMay = EducationTax::factory()->create([
            'effective_date' => '2021-04-01'
        ]);
        $educationTaxJuneToDec = EducationTax::factory()->create([
            'effective_date' => '2021-06-01'
        ]);

        $this->travel(-1)->months();
        $educationTaxForLastMonth = EducationTax::findEntryForDate();
        $this->assertEquals($educationTaxJuneToDec->effective_date, $educationTaxForLastMonth->effective_date);

    }

    /** @test */
    public function it_returns_null_if_no_suitable_date_is_found()
    {

        //Set up dates
        $educationTaxJanToMarch = EducationTax::factory()->create([
            'effective_date' => '2021-01-01'
        ]);
        $educationTaxAprilToMay = EducationTax::factory()->create([
            'effective_date' => '2021-04-01'
        ]);
        $educationTaxJuneToDec = EducationTax::factory()->create([
            'effective_date' => '2021-06-01'
        ]);

        $educationTaxTenYearsAgo = EducationTax::findEntryForDate('2010-12-31');
        $this->assertNull($educationTaxTenYearsAgo);
    }
}
