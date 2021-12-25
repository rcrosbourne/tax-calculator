<?php


namespace App\Models;

use App\Enums\PensionType;
use Illuminate\Support\Facades\App;
use InvalidArgumentException;
use JetBrains\PhpStorm\ArrayShape;
use Money\Currency;
use Money\Money;
use Money\MoneyFormatter;
use Money\MoneyParser;
use MoneyConfiguration;

class TaxCalculator
{
    private Money $grossMonthlyIncome;
    private Money $nisAnnualIncomeThreshold;
    private string $nisPercent;
    private string $educationTaxPercent;
    private string $nhtPercent;
    public MoneyFormatter $formatter;
    public MoneyParser $parser;
    private array $incomeTaxMonthlyThresholds;

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
        $incomeTaxConfiguration = IncomeTax::findEntryForDate($date);

        if ($nisConfiguration == null) {
            throw new InvalidArgumentException("Unable to retrieve NIS values");
        }
        if ($nhtConfiguration == null) {
            throw new InvalidArgumentException("Unable to retrieve NHT values");
        }
        if ($educationTaxConfiguration == null) {
            throw new InvalidArgumentException("Unable to retrieve Education Tax values");
        }
        if ($incomeTaxConfiguration == null) {
            throw new InvalidArgumentException("Unable to retrieve Income Tax values");
        }
        // Setup percentages
        // Convert the value 3% to 0.03
        $this->currency = $this->currency ?? MoneyConfiguration::defaultCurrency();

        $this->nisPercent = $nisConfiguration->rate_percentage / 100;
        $this->nhtPercent = $nhtConfiguration->rate_percentage / 100;
        $this->educationTaxPercent = $educationTaxConfiguration->rate_percentage / 100;

        $this->incomeTaxMonthlyThresholds = $this->extractMonthlyFromAnnualThresholds($incomeTaxConfiguration->annual_thresholds,
            $this->currency);

        $this->grossMonthlyIncome = $this->parse($this->monthlyGross);
        $this->nisAnnualIncomeThreshold = $nisConfiguration->annual_income_threshold;
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
        $nisAmountUsingPercentageOfMonthlyGross = $this->grossMonthlyIncome->multiply($this->nisPercent);
        return min($nisMaxMonthlyAmountBasedOnIncomeThreshold, $nisAmountUsingPercentageOfMonthlyGross);
    }

    /**
     * @return Money
     */
    private function nisMaxYearlyAmountBasedOnIncomeThreshold(): Money
    {
        return $this->nisAnnualIncomeThreshold->multiply($this->nisPercent);
    }

    /**
     * @return Money
     */
    public function pensionAmount(): Money
    {
        return match (true) {
            $this->monthlyPension === null, !is_numeric($this->monthlyPension->value) => $this->parse('0.00'),
            $this->monthlyPension->type === PensionType::FIXED => $this->parse($this->monthlyPension->value),
            $this->monthlyPension->type === PensionType::PERCENTAGE => $this->grossMonthlyIncome->multiply($this->monthlyPension->value)->divide('100'),
            default => throw new InvalidArgumentException("Unable to calculate pension amount")
        };
    }

    /**
     * @return Money
     */
    public function statutoryIncome(): Money
    {
        // Statuatory Income = Total Income - Pension Contributions - NIS
        return $this->grossMonthlyIncome->subtract($this->pensionAmount())->subtract($this->nisAmount());
    }

    public function educationTaxAmount(): Money
    {
        return $this->statutoryIncome()->multiply($this->educationTaxPercent);
    }

    public function nhtAmount(): Money
    {
        return $this->grossMonthlyIncome->multiply($this->nhtPercent);
    }

    public function incomeTaxAmount(): Money
    {
        // Get Statutory Income
        $monthlyStatutoryIncome = $this->statutoryIncome();
        //Is Statutory > first monthly threshold
        $firstMonthlyThreshold = $this->incomeTaxMonthlyThresholds[0]['amount'];
        $firstMonthlyThresholdRate = $this->incomeTaxMonthlyThresholds[0]['rate_percent'];
        $secondMonthlyThreshold = $this->incomeTaxMonthlyThresholds[1]['amount'];
        $secondMonthlyThresholdRate = $this->incomeTaxMonthlyThresholds[1]['rate_percent'];

        if ($monthlyStatutoryIncome->greaterThan($secondMonthlyThreshold)) {
            $secondThresholdAmount = ($monthlyStatutoryIncome->subtract($secondMonthlyThreshold))->multiply($secondMonthlyThresholdRate);
            $firstThresholdAmount = ($secondMonthlyThreshold->subtract($firstMonthlyThreshold))->multiply($firstMonthlyThresholdRate);
            return $secondThresholdAmount->add($firstThresholdAmount);
        }
        if ($monthlyStatutoryIncome->greaterThan($firstMonthlyThreshold)) {
            return ($monthlyStatutoryIncome->subtract($firstMonthlyThreshold))->multiply($firstMonthlyThresholdRate);
        }
        return $this->parse('0.00');
    }

    private function parse(string $moneyString): Money
    {
        return $this->parser->parse($moneyString, $this->currency);
    }

    private function extractMonthlyFromAnnualThresholds(array $annual_thresholds, Currency $currency): array
    {
        //return an array with money in monthly
        return array_map(function ($entry) use ($currency) {
            return [
                "amount" => (App::make(MoneyParser::class))->parse($entry['amount'], $currency)->divide('12'),
                "rate_percent" => strval($entry["rate_percent"] / 100)
            ];
        }, $annual_thresholds);
    }

    public function subtotalDeductions(): Money
    {
        return $this->nisAmount() //NIS
        ->add($this->educationTaxAmount()) // Education Tax
        ->add($this->pensionAmount()) // Pension
        ->add($this->nhtAmount()); // NHT
    }

    public function totalDeductions(): Money
    {
        return $this->subtotalDeductions()->add($this->incomeTaxAmount());
    }

    public function netMonthlyIncome(): Money
    {
        return $this->grossMonthlyIncome->subtract($this->totalDeductions());
    }

    #[ArrayShape([
        'grossMonthlyIncome' => "string", 'nationalInsuranceScheme' => "string", 'pensionAmount' => "string",
        'statutoryIncome' => "string", 'educationTax' => "string", 'nationalHousingTrust' => "string",
        'incomeTax' => "string", 'totalDeductions' => "string", 'netMonthlyIncome' => "string"
    ])] public function fullTaxBreakDown(): array
    {
        return [
            'grossMonthlyIncome' => TaxCalculator::formatAsString($this->grossMonthlyIncome),
            'nationalInsuranceScheme' => TaxCalculator::formatAsString($this->nisAmount()),
            'pensionAmount' => TaxCalculator::formatAsString($this->pensionAmount()),
            'statutoryIncome' => TaxCalculator::formatAsString($this->statutoryIncome()),
            'educationTax' => TaxCalculator::formatAsString($this->educationTaxAmount()),
            'nationalHousingTrust' => TaxCalculator::formatAsString($this->nhtAmount()),
            'incomeTax' => TaxCalculator::formatAsString($this->incomeTaxAmount()),
            'totalDeductions' => TaxCalculator::formatAsString($this->totalDeductions()),
            'netMonthlyIncome' => TaxCalculator::formatAsString($this->netMonthlyIncome()),
        ];
    }
}
