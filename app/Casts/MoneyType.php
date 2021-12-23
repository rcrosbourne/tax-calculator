<?php


namespace App\Casts;


use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;
use JetBrains\PhpStorm\NoReturn;
use JetBrains\PhpStorm\Pure;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money;
use Money\Parser\DecimalMoneyParser;

class MoneyType implements CastsAttributes
{
    private Currency $currency;
    private ISOCurrencies $supported_currencies;

    /**
     * MoneyType constructor.
     * @param  string  $currencyCode
     */
    public function __construct(
        string $currencyCode
    ) {
        $this->currency = new Currency($currencyCode) ?? new Currency('JMD');
        $this->supported_currencies = new ISOCurrencies();
    }

    public function get($model, string $key, $value, array $attributes)
    {
        if(! is_numeric($value) || !is_string($value)) {
            throw new InvalidArgumentException('The given value cannot be reliably cast to Money Type');
        }
        $moneyParser = new DecimalMoneyParser($this->supported_currencies);
        return $moneyParser->parse($value, $this->currency);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        if(! $value instanceof Money) {
            throw new InvalidArgumentException("Value should be money type");
        }
        //Convert money to string
        $formatter = new DecimalMoneyFormatter($this->supported_currencies);
        return $formatter->format($value);
    }
}
