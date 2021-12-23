<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class NISFactory extends Factory
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
            'rate_percentage' => $this->faker->regexify('[1-9]{1}\\.[0-9]{1,2}'),
            'annual_income_threshold' => $this->faker->numerify('#######')
        ];
    }
}
