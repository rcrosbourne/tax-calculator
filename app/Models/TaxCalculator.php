<?php


namespace App\Models;

use Exception;
use InvalidArgumentException;
use Money\Currency;
use Money\Money;
use MoneyConfiguration;

class TaxCalculator
{
    private Money $monthlyGrossAsMoney;
    private Money $nisAnnualIncomeThresholdAsMoney;
    private string $nisPercent;

    /**
     * @throws Exception
     */
    public function __construct(
        public string $monthlyGross,
        public ?Currency $currency = null,
        public ?string $date = null,
    ) {
        $this->currency = $this->currency ?? MoneyConfiguration::defaultCurrency();
        $nisConfiguration = NIS::findEntryForDate($date);
        if ($nisConfiguration == null) {
            throw new InvalidArgumentException("Unable to retrieve NIS values");
        }
        // Convert the value 3% to 0.03
        $this->nisPercent = $nisConfiguration->rate_percentage / 100;
        $this->monthlyGrossAsMoney = MoneyConfiguration::defaultParser()->parse($this->monthlyGross, $this->currency);
        $this->nisAnnualIncomeThresholdAsMoney = $nisConfiguration->annual_income_threshold;
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
