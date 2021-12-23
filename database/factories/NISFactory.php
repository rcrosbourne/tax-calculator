<?php

namespace Database\Factories;

use App\Utils\MoneyConfiguration;
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
            'annual_income_threshold' => MoneyConfiguration::DefaultParser()
                ->parse($this->faker->numerify('#######.##'),
                    MoneyConfiguration::defaultCurrency()),
        ];
    }
}
