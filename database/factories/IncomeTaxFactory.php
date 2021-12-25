<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class IncomeTaxFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'effective_date' => $this->faker->date(),
            'annual_thresholds' => [
                ['amount' => '1500096.00', 'rate_percent' => '25'],
                ['amount' => '6000000.00', 'rate_percent' => '30']
            ],
        ];
    }
}
