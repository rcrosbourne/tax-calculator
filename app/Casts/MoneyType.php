<?php


namespace App\Casts;


use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money;
use Money\Parser\DecimalMoneyParser;

class MoneyType implements CastsAttributes
{


    public function set($model, string $key, $value, array $attributes)
    {
        // Set the value coming from the database from Database and covert it to money
        // Parse string to money type
        $moneyParser = new DecimalMoneyParser(new ISOCurrencies());
        $currency = new Currency('JMD'); // we will take currency code from casts default to JMD for now
        return $moneyParser->parse($value, $currency);
    }

    public function get($model, string $key, $value, array $attributes)
    {
        // Get the value and convert it to a string before saving to database;

        if(!$value instanceof Money) {
            throw new InvalidArgumentException('The given value is not a Money type');
        }

        $formatter = new DecimalMoneyFormatter(new ISOCurrencies());
        return $formatter->format($value);
    }
}
