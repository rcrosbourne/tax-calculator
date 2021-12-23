<?php

namespace Tests\Unit;


use App\Models\NIS;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NISUnitTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_gets_the_correct_nis_entry_for_a_given_date()
    {
       //Set up dates
        $nisJanToMarch = NIS::factory()->create([
            'effective_date' => '2021-01-01'
        ]);
        $nisAprilToMay = NIS::factory()->create([
            'effective_date' => '2021-04-01'
        ]);
        $nisJuneToDec = NIS::factory()->create([
            'effective_date' => '2021-06-01'
        ]);

        $nisForApril = NIS::findEntryForDate('2021-04-25');
        $this->assertEquals($nisForApril->effective_date, $nisAprilToMay->effective_date);
    }

    /** @test */
    public function it_defaults_to_current_date_when_no_date_is_specified()
    {
        //Set up dates
        $nisJanToMarch = NIS::factory()->create([
            'effective_date' => '2021-01-01'
        ]);
        $nisAprilToMay = NIS::factory()->create([
            'effective_date' => '2021-04-01'
        ]);
        $nisJuneToDec = NIS::factory()->create([
            'effective_date' => '2021-06-01'
        ]);

        $this->travel(-1)->months();
        $nisForLastMonth = NIS::findEntryForDate();
        $this->assertEquals($nisJuneToDec->effective_date, $nisForLastMonth->effective_date);

    }

    /** @test */
    public function it_returns_null_if_no_suitable_date_is_found()
    {

        //Set up dates
        $nisJanToMarch = NIS::factory()->create([
            'effective_date' => '2021-01-01'
        ]);
        $nisAprilToMay = NIS::factory()->create([
            'effective_date' => '2021-04-01'
        ]);
        $nisJuneToDec = NIS::factory()->create([
            'effective_date' => '2021-06-01'
        ]);

        $nisTenYearsAgo = NIS::findEntryForDate('2010-12-31');
        $this->assertNull($nisTenYearsAgo);
    }
}
