<?php


namespace App\Models;

use App\Enums\PensionType;
use Exception;
use Illuminate\Support\Facades\App;
use InvalidArgumentException;
use Money\Currency;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money;
use Money\MoneyFormatter;
use Money\MoneyParser;
use MoneyConfiguration;

class TaxCalculator
{
    private Money $monthlyGrossAsMoney;
    private Money $nisAnnualIncomeThresholdAsMoney;
    private string $nisPercent;
    private string $educationTaxPercent;
    private string $nhtPercent;
    public MoneyFormatter $formatter;
    public MoneyParser $parser;

    public function __construct(
        public string $monthlyGross,
        public ?Currency $currency = null,
        public ?string $date = null,
        public ?Pension $monthlyPension = null,
    ) {
        $this->formatter = App::make(MoneyFormatter::class);
        $this->parser = App::make(MoneyParser::class);
        // Setup Taxes
        $nisConfiguration = NIS::findEntryForDate($date);
        $nhtConfiguration = NHT::findEntryForDate($date);
        $educationTaxConfiguration = EducationTax::findEntryForDate($date);

        if ($nisConfiguration == null) {
            throw new InvalidArgumentException("Unable to retrieve NIS values");
        }
        if ($nhtConfiguration == null) {
            throw new InvalidArgumentException("Unable to retrieve NHT values");
        }
        if ($educationTaxConfiguration == null) {
            throw new InvalidArgumentException("Unable to retrieve Education Tax values");
        }
        // Setup percentages
        // Convert the value 3% to 0.03
        $this->nisPercent = $nisConfiguration->rate_percentage / 100;
        $this->nhtPercent = $nhtConfiguration->rate_percentage / 100;
        $this->educationTaxPercent = $educationTaxConfiguration->rate_percentage / 100;

        $this->currency = $this->currency ?? MoneyConfiguration::defaultCurrency();
        $this->monthlyGrossAsMoney = $this->parse($this->monthlyGross);
        $this->nisAnnualIncomeThresholdAsMoney = $nisConfiguration->annual_income_threshold;
    }

    public static function formatAsString(Money $money): string
    {
        return (App::make(MoneyFormatter::class))->format($money);
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
            $this->monthlyPension === null, !is_numeric($this->monthlyPension->value) => $this->parse('0.00'),
            $this->monthlyPension->type === PensionType::FIXED => $this->parse($this->monthlyPension->value),
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

    public function nhtAmount(): Money
    {
        return $this->monthlyGrossAsMoney->multiply($this->nhtPercent);
    }

    public function incomeTaxAmount(): Money
    {
        return $this->parse('0.00');
    }

    private function parse(string $moneyString): Money
    {
        return $this->parser->parse($moneyString, $this->currency);
    }
}
