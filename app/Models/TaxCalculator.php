<?php


namespace App\Models;

use App\Enums\PensionType;
use Illuminate\Support\Arr;
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
    private Money $additionalTaxableIncome;
    private Money $totalEarnings;
    private Money $nisAnnualIncomeThreshold;
    private string $nisPercent;
    private string $educationTaxPercent;
    private string $nhtPercent;
    public MoneyFormatter $formatter;
    public MoneyParser $parser;
    private array $incomeTaxMonthlyThresholds;
    private array $voluntaryDeductions;

    public function __construct(
        public string $monthlyGross,
        public ?string $otherTaxableIncome = null,
        public ?Pension $monthlyPension = null,
        public array $listOfVoluntaryDeductions = [],
        public ?Currency $currency = null,
        public ?string $date = null,
    ) {
        $this->setupDefaultMoneyFormatterParserAndCurrency();
        // Setup Taxes
        list(
            $nisConfiguration,
            $nhtConfiguration,
            $educationTaxConfiguration,
            $incomeTaxConfiguration) = $this->configureTaxesForDate($date);

        // Setup percentages
        // Convert the value 3% to 0.03
        $this->nisPercent = $nisConfiguration->rate_percentage / 100;
        $this->nhtPercent = $nhtConfiguration->rate_percentage / 100;
        $this->educationTaxPercent = $educationTaxConfiguration->rate_percentage / 100;
        $this->nisAnnualIncomeThreshold = $nisConfiguration->annual_income_threshold;
        $this->incomeTaxMonthlyThresholds = $this->extractMonthlyFromAnnualThresholds($incomeTaxConfiguration->annual_thresholds,
            $this->currency);

        $this->voluntaryDeductions = $this->extractDeductionsFromInputList($this->listOfVoluntaryDeductions,
            $this->currency);

        $this->grossMonthlyIncome = $this->parse($this->monthlyGross);

        $this->additionalTaxableIncome = $this->otherTaxableIncome == null ?
            $this->parse('0.00') : $this->parse($this->otherTaxableIncome);

        $this->totalEarnings = $this->grossMonthlyIncome->add($this->additionalTaxableIncome);

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
        $nisAmountUsingPercentageOfMonthlyGross = $this->totalEarnings->multiply($this->nisPercent);
        return Money::min($nisMaxMonthlyAmountBasedOnIncomeThreshold, $nisAmountUsingPercentageOfMonthlyGross);
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
        // Statuatory Income = Total Earning - NIS - Pension
        return $this->totalEarnings->subtract($this->pensionAmount())->subtract($this->nisAmount());
    }

    public function educationTaxAmount(): Money
    {
        return $this->statutoryIncome()->multiply($this->educationTaxPercent);
    }

    public function nhtAmount(): Money
    {
        return $this->totalEarnings->multiply($this->nhtPercent);
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


    public function subtotalDeductions(): Money
    {
        return $this->nisAmount() //NIS
        ->add($this->educationTaxAmount()) // Education Tax
        ->add($this->pensionAmount()) // Pension
        ->add($this->nhtAmount()); // NHT
    }

    public function totalStatutoryDeductions(): Money
    {
        return $this->nisAmount()
            ->add($this->educationTaxAmount())
            ->add($this->nhtAmount())
            ->add($this->incomeTaxAmount());
    }

    public function totalVoluntaryDeductions(): Money
    {
        // Accumulator
        return array_reduce(array_values($this->voluntaryDeductions),
            function (Money $accum, Money $current) {
                return $accum->add($current);
            }, $this->pensionAmount()); // Include pension
    }

    public function totalDeductions(): Money
    {
        return $this->totalStatutoryDeductions()->add($this->totalVoluntaryDeductions());
//        return $this->subtotalDeductions()->add($this->incomeTaxAmount());
    }

    public function netMonthlyIncome(): Money
    {
        return $this->totalEarnings->subtract($this->totalDeductions());
    }

    public function fullTaxBreakDown(): array
    {
        return [
            'grossMonthlyIncome' => TaxCalculator::formatAsString($this->grossMonthlyIncome),
            'additionalTaxableIncome' => TaxCalculator::formatAsString($this->additionalTaxableIncome),
            'totalEarnings' => TaxCalculator::formatAsString($this->totalEarnings),
            'nationalInsuranceScheme' => TaxCalculator::formatAsString($this->nisAmount()),
            'voluntaryDeductions' => array_merge(['pensionAmount' => TaxCalculator::formatAsString($this->pensionAmount())],
                $this->listOfVoluntaryDeductions),
            'statutoryIncome' => TaxCalculator::formatAsString($this->statutoryIncome()),
            'educationTax' => TaxCalculator::formatAsString($this->educationTaxAmount()),
            'nationalHousingTrust' => TaxCalculator::formatAsString($this->nhtAmount()),
            'incomeTax' => TaxCalculator::formatAsString($this->incomeTaxAmount()),
            'totalDeductions' => TaxCalculator::formatAsString($this->totalDeductions()),
            'netMonthlyIncome' => TaxCalculator::formatAsString($this->netMonthlyIncome()),
        ];
    }

    private function extractDeductionsFromInputList(array $listOfVoluntaryDeductions, ?Currency $currency): array
    {
        // This section just converts the array from [["expense" => "1111']] to ["expense" => '1111]
        // Basically flattens the array.
        //Creates the multidimensional array
        $arrayOfDeductions = array_map(function ($key, $value) use ($currency) {
            return [$key => (App::make(MoneyParser::class))->parse($value, $currency)];
        }, array_keys($listOfVoluntaryDeductions), $listOfVoluntaryDeductions);
        //Flattens it
        return array_merge(...array_values($arrayOfDeductions));
    }

    private function parse(string $moneyString): Money
    {
        return $this->parser->parse($moneyString, $this->currency);
    }

    /**
     * @return Money
     */
    private function nisMaxYearlyAmountBasedOnIncomeThreshold(): Money
    {
        return $this->nisAnnualIncomeThreshold->multiply($this->nisPercent);
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

    protected function setupDefaultMoneyFormatterParserAndCurrency(): void
    {
        $this->formatter = App::make(MoneyFormatter::class);
        $this->parser = App::make(MoneyParser::class);
        $this->currency = $this->currency ?? MoneyConfiguration::defaultCurrency();
    }

    /**
     * @param  string|null  $date
     * @return array
     */
    protected function configureTaxesForDate(?string $date): array
    {
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
        return array($nisConfiguration, $nhtConfiguration, $educationTaxConfiguration, $incomeTaxConfiguration);
    }
}
