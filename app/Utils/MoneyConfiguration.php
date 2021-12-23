<?php


namespace App\Utils;


use JetBrains\PhpStorm\Pure;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Parser\DecimalMoneyParser;

class MoneyConfiguration
{
    private ISOCurrencies $supportedCurrencies;

    #[Pure] public function __construct()
    {
        $this->supportedCurrencies = new ISOCurrencies();
    }


    /**
     * @return Currency
     */
    public function defaultCurrency(): Currency
    {
        return new Currency(config('app.default_currency_code'));
    }

    /**
     * @return DecimalMoneyParser
     */
    #[Pure] public function defaultParser(): DecimalMoneyParser
    {
        return new DecimalMoneyParser($this->supportedCurrencies);
    }

    /**
     * @return DecimalMoneyFormatter
     */
    #[Pure] public function defaultFormatter(): DecimalMoneyFormatter
    {
        return new DecimalMoneyFormatter($this->supportedCurrencies);
    }
}
