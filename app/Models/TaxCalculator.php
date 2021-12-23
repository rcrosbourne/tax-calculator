<?php


namespace App\Models;


use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money;
use Money\Parser\DecimalMoneyParser;

class TaxCalculator
{
    private Money $monthlyGrossAsMoney;
    private Money $nisAnnualIncomeThresholdAsMoney;
    private DecimalMoneyParser $moneyParser;

    public function __construct(
        public string $monthlyGross,
        public string $nisPercent,
        public string $nisAnnualIncomeThreshold,
        public ?Currency $currency = NULL
    ) {
        $this->moneyParser = new DecimalMoneyParser(new ISOCurrencies());
        $this->currency = $this->currency ?? new Currency(config('app.default_currency_code'));

        // Convert the value 3% to 0.03
        $this->nisPercent = $this->nisPercent / 100;
        $this->monthlyGrossAsMoney = $this->moneyParser->parse($this->monthlyGross, $this->currency);
        $this->nisAnnualIncomeThresholdAsMoney = $this->moneyParser->parse($this->nisAnnualIncomeThreshold,
            $this->currency);
    }

    public static function formatAsString(Money $money): string
    {
        $formatter = new DecimalMoneyFormatter(new ISOCurrencies());
        return $formatter->format($money);

    }


    /**
     * @return Money
     */
    public function nisAmount(): Money
    {
        $nisMaxMonthlyAmountBasedOnIncomeThreshold = $this->nisMaxYearlyAmountBasedOnIncomeThreshold()->divide('12');
        $nisAmountUsingPercentageOfMonthlyGross = $this->monthlyGrossAsMoney->multiply($this->nisPercent);
        return min($nisMaxMonthlyAmountBasedOnIncomeThreshold, $nisAmountUsingPercentageOfMonthlyGross);
    }

    /**
     * @return Money
     */
    private function nisMaxYearlyAmountBasedOnIncomeThreshold(): Money
    {
        return $this->nisAnnualIncomeThresholdAsMoney->multiply($this->nisPercent);
    }
}
