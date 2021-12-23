<?php


namespace App\Models;
use Money\Currency;
use Money\Money;
use MoneyConfiguration;

class TaxCalculator
{
    private Money $monthlyGrossAsMoney;
    private Money $nisAnnualIncomeThresholdAsMoney;

    public function __construct(
        public string $monthlyGross,
        public string $nisPercent,
        public string $nisAnnualIncomeThreshold,
        public ?Currency $currency = NULL,
    ) {
        $this->currency = $this->currency ?? MoneyConfiguration::defaultCurrency();
        // Convert the value 3% to 0.03
        $this->nisPercent = $this->nisPercent / 100;
        $this->monthlyGrossAsMoney = MoneyConfiguration::defaultParser()->parse($this->monthlyGross, $this->currency);
        $this->nisAnnualIncomeThresholdAsMoney = MoneyConfiguration::defaultParser()->parse($this->nisAnnualIncomeThreshold,
            $this->currency);
    }

    public static function formatAsString(Money $money): string
    {
        return MoneyConfiguration::defaultFormatter()->format($money);
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
