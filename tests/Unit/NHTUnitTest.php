<?php

namespace Tests\Unit;

use App\Models\NHT;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NHTUnitTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_gets_the_correct_nht_entry_for_a_given_date()
    {
        //Set up dates
        $nhtJanToMarch = NHT::factory()->create([
            'effective_date' => '2021-01-01'
        ]);
        $nhtAprilToMay = NHT::factory()->create([
            'effective_date' => '2021-04-01'
        ]);
        $nhtJuneToDec = NHT::factory()->create([
            'effective_date' => '2021-06-01'
        ]);

        $nhtForApril = NHT::findEntryForDate('2021-04-25');
        $this->assertEquals($nhtForApril->effective_date, $nhtAprilToMay->effective_date);
    }

    /** @test */
    public function it_defaults_to_current_date_when_no_date_is_specified()
    {
        //Set up dates
        $nhtJanToMarch = NHT::factory()->create([
            'effective_date' => '2021-01-01'
        ]);
        $nhtAprilToMay = NHT::factory()->create([
            'effective_date' => '2021-04-01'
        ]);
        $nhtJuneToDec = NHT::factory()->create([
            'effective_date' => '2021-06-01'
        ]);

        $this->travel(-1)->months();
        $nhtForLastMonth = NHT::findEntryForDate();
        $this->assertEquals($nhtJuneToDec->effective_date, $nhtForLastMonth->effective_date);

    }

    /** @test */
    public function it_returns_null_if_no_suitable_date_is_found()
    {

        //Set up dates
        $nhtJanToMarch = NHT::factory()->create([
            'effective_date' => '2021-01-01'
        ]);
        $nhtAprilToMay = NHT::factory()->create([
            'effective_date' => '2021-04-01'
        ]);
        $nhtJuneToDec = NHT::factory()->create([
            'effective_date' => '2021-06-01'
        ]);

        $nhtTenYearsAgo = NHT::findEntryForDate('2010-12-31');
        $this->assertNull($nhtTenYearsAgo);
    }
}
