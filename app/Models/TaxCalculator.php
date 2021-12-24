<?php


namespace App\Models;

use App\Enums\PensionType;
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
    private string $educationTaxPercent;

    /**
     * @throws Exception
     */
    public function __construct(
        public string $monthlyGross,
        public ?Currency $currency = null,
        public ?string $date = null,
        public ?Pension $monthlyPension = null
    ) {
        $this->currency = $this->currency ?? MoneyConfiguration::defaultCurrency();
        $nisConfiguration = NIS::findEntryForDate($date);
        $educationTaxConfiguration = EducationTax::findEntryForDate($date);
        if ($nisConfiguration == null) {
            throw new InvalidArgumentException("Unable to retrieve NIS values");
        }
        // Convert the value 3% to 0.03
        $this->nisPercent = $nisConfiguration->rate_percentage / 100;
        $this->educationTaxPercent = $educationTaxConfiguration->rate_percentage / 100;
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

    /**
     * @return Money
     */
    public function pensionAmount(): Money
    {
        return match (true) {
            $this->monthlyPension === null, !is_numeric($this->monthlyPension->value) => MoneyConfiguration::defaultParser()->parse('0.00',
                MoneyConfiguration::defaultCurrency()),
            $this->monthlyPension->type === PensionType::FIXED => MoneyConfiguration::defaultParser()->parse($this->monthlyPension->value,
                MoneyConfiguration::defaultCurrency()),
            $this->monthlyPension->type === PensionType::PERCENTAGE => $this->monthlyGrossAsMoney->multiply($this->monthlyPension->value)->divide('100'),
            default => throw new InvalidArgumentException("Unable to calculate pension amount")
        };
    }

    /**
     * @return Money
     */
    public function statutoryIncome(): Money
    {
        // Statuatory Income = Total Income - Pension Contributions - NIS
        return $this->monthlyGrossAsMoney->subtract($this->pensionAmount())->subtract($this->nisAmount());
    }

    public function educationTaxAmount(): Money
    {
        return $this->statutoryIncome()->multiply($this->educationTaxPercent);
    }
}
