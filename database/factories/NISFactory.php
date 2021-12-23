<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Parser\DecimalMoneyParser;

class NISFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $currency = new Currency(config('app.default_currency_code'));
        $supported_currencies = new ISOCurrencies();
        $parser = new DecimalMoneyParser($supported_currencies);
        return [
            'effective_date' => $this->faker->date(),
            'rate_percentage' => $this->faker->regexify('[1-9]{1}\\.[0-9]{1,2}'),
            'annual_income_threshold' => $parser->parse($this->faker->numerify('#######.##'), $currency),
        ];
    }
}
