<?php


namespace App\Casts;


use Facades\App\Utils\MoneyConfiguration;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;
use Money\Currency;
use Money\Money;

class MoneyType implements CastsAttributes
{
    private Currency $currency;

    /**
     * MoneyType constructor.
     * @param  string  $currencyCode
     */
    public function __construct(
        string $currencyCode
    ) {
        $this->currency = new Currency($currencyCode) ?? MoneyConfiguration::defaultCurrency();
    }

    public function get($model, string $key, $value, array $attributes)
    {
        if(! is_numeric($value) || !is_string($value)) {
            throw new InvalidArgumentException('The given value cannot be reliably cast to Money Type');
        }
        return MoneyConfiguration::defaultParser()->parse($value, $this->currency);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        if(! $value instanceof Money) {
            throw new InvalidArgumentException("Value should be money type");
        }
        //Convert money to string
        return MoneyConfiguration::defaultFormatter()->format($value);
    }
}
