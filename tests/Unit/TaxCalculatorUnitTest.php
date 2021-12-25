<?php

namespace Tests\Unit;

use App\Enums\PensionType;
use App\Models\EducationTax;
use App\Models\IncomeTax;
use App\Models\NHT;
use App\Models\NIS;
use App\Models\Pension;
use App\Models\TaxCalculator;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Money\Currency;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money;
use Money\Parser\DecimalMoneyParser;
use MoneyConfiguration;
use Tests\TestCase;


class TaxCalculatorUnitTest extends TestCase
{
    use RefreshDatabase;

    private DecimalMoneyParser $parser;
    private DecimalMoneyFormatter $formatter;
    private Currency $currency;

    protected function setUp(): void
    {
        parent::setUp();
        $this->parser = MoneyConfiguration::defaultParser();
        $this->formatter = MoneyConfiguration::defaultFormatter();
        $this->currency = MoneyConfiguration::defaultCurrency();

        $this->setupNIS();
        $this->setupNHT();
        $this->setupEducationTax();
        $this->setupIncomeTax();

    }

    /** @test */
    public function it_can_calculate_nis()
    {
        $calculator = new TaxCalculator(
            monthlyGross: '100000.00',
        );
        $nisAmount = $calculator->nisAmount();
        $this->assertEquals('3000.00', TaxCalculator::formatAsString($nisAmount));
    }

    /** @test */
    public function it_calculates_nis_respecting_income_threshold()
    {
        $calculator = new TaxCalculator(
            monthlyGross: '264750.00',
        );

        $nisAmount = $calculator->nisAmount();
        $this->assertEquals('7500.00', TaxCalculator::formatAsString($nisAmount));
    }

    /** @test */
    public function it_calculates_nis_for_a_specific_period()
    {

        $calculator = new TaxCalculator(
            monthlyGross: '264750.00',
            date: '2021-02-01' //NIS max was 3750.00
        );

        $nisAmount = $calculator->nisAmount();
        $this->assertEquals('3750.00', TaxCalculator::formatAsString($nisAmount));
    }

    /** @test */
    public function it_calculates_fixed_value_pension()
    {
        $calculator = new TaxCalculator(
            monthlyGross: '264750.00',
            monthlyPension: new Pension(type: PensionType::FIXED, value: '10000.00')
        );

        $pensionAmount = $calculator->pensionAmount();
        $this->assertEquals('10000.00', TaxCalculator::formatAsString($pensionAmount));
    }

    /** @test */
    public function it_calculates_percent_of_gross_value_pension()
    {
        $calculator = new TaxCalculator(
            monthlyGross: '253755.00',
            monthlyPension: new Pension(type: PensionType::PERCENTAGE, value: '10.0')
        );

        $pensionAmount = $calculator->pensionAmount();
        $this->assertEquals('25375.50', TaxCalculator::formatAsString($pensionAmount));
    }

    /** @test */
    public function it_calculates_the_statutory_income_without_pension_included()
    {
        $calculator = new TaxCalculator(
            monthlyGross: '250000.00',
        );
        NIS::factory()->create([
            'effective_date' => Carbon::now(),
            'rate_percentage' => '3.0',
            'annual_income_threshold' => MoneyConfiguration::defaultParser()->parse('3000000',
                MoneyConfiguration::defaultCurrency())
        ]);
        // NIS 3% up to 7500
        $monthlyStatuatoryIncome = $calculator->statutoryIncome();
        $this->assertEquals('242500.00', TaxCalculator::formatAsString($monthlyStatuatoryIncome));
    }

    /** @test */
    public function it_calculates_the_statutory_income_with_pension_included()
    {
        $calculator = new TaxCalculator(
            monthlyGross: '250000.00',
            monthlyPension: new Pension(PensionType::PERCENTAGE, '10.0')
        );
        NIS::factory()->create([
            'effective_date' => Carbon::now(),
            'rate_percentage' => '3.0',
            'annual_income_threshold' => MoneyConfiguration::defaultParser()->parse('3000000',
                MoneyConfiguration::defaultCurrency())
        ]);
        // NIS 3% up to 7500 + Pension = 25000
        $monthlyStatutoryIncome = $calculator->statutoryIncome();
        $this->assertEquals('217500.00', TaxCalculator::formatAsString($monthlyStatutoryIncome));
    }

    /** @test */
    public function it_calculates_education_tax()
    {
        // Calculate education tax on statutory income

        NIS::factory()->create([
            'effective_date' => Carbon::now(),
            'rate_percentage' => '3.0',
            'annual_income_threshold' => MoneyConfiguration::defaultParser()->parse('3000000',
                MoneyConfiguration::defaultCurrency())
        ]);
        EducationTax::factory()->create([
            'effective_date' => Carbon::now(),
            'rate_percentage' => '2.25',
        ]);

        $calculator = new TaxCalculator(
            monthlyGross: '250000.00',
            monthlyPension: new Pension(PensionType::PERCENTAGE, '10.0')
        );
        // NIS 3% up to 7500 + Pension = 25000
        // Education Tax = '2.25%'
        $monthlyEducationTaxAmount = $calculator->educationTaxAmount();
        $this->assertEquals('4893.75', TaxCalculator::formatAsString($monthlyEducationTaxAmount));
    }
    /** @test */
    public function it_calculates_nht()
    {
        // Calculate education tax on statutory income

        NIS::factory()->create([
            'effective_date' => Carbon::now(),
            'rate_percentage' => '3.0',
            'annual_income_threshold' => MoneyConfiguration::defaultParser()->parse('3000000',
                MoneyConfiguration::defaultCurrency())
        ]);
        EducationTax::factory()->create([
            'effective_date' => Carbon::now(),
            'rate_percentage' => '2.25',
        ]);
        NHT::factory()->create([
            'effective_date' => Carbon::now(),
            'rate_percentage' => '2.0',
        ]);

        $calculator = new TaxCalculator(
            monthlyGross: '238841.00',
        );
        // NHT 2.0%
        $monthlyNhtAmount = $calculator->nhtAmount();
        $this->assertEquals('4776.82', TaxCalculator::formatAsString($monthlyNhtAmount));
    }

    /** @test */
    public function it_calculates_income_tax_for_income_below_threshold()
    {
        // Calculate education tax on statutory income
        $calculator = new TaxCalculator(
            monthlyGross: '100000.00',
        );
        // NHT 2.0%
        $monthlyIncomeTaxAmount = $calculator->incomeTaxAmount();
        $this->assertEquals('0.00', TaxCalculator::formatAsString($monthlyIncomeTaxAmount));
    }

    /** @test */
    public function it_calculates_income_tax_for_income_above_25_percent_but_below_30_percent_threshold()
    {
        // Calculate education tax on statutory income
        $calculator = new TaxCalculator(
            monthlyGross: '150000.00',
        );
        // Income tax 25% monthly threshold = 1,500,096.00 / 12 = 125,008.00
        $monthlyIncomeTaxAmount = $calculator->incomeTaxAmount();
        $this->assertEquals('5123.00', TaxCalculator::formatAsString($monthlyIncomeTaxAmount));
    }

    /** @test */
    public function it_calculates_income_tax_for_income_above_30_percent_threshold()
    {
        // Calculate education tax on statutory income
        $calculator = new TaxCalculator(
            monthlyGross: '600000.00',
        );
        // Stat = 592500 [(500000-125008) * 0.25 + (592500-500000) * 0.03] = [93748.00 + 27750.00 = 121,498.00]
        // Income tax 25% monthly threshold = 1,500,096.00 / 12 = 125,008.00
        // Income tax 30% monthly threshold = 6,000,000.00 / 12 = 500,000.00
        $monthlyIncomeTaxAmount = $calculator->incomeTaxAmount();
        $this->assertEquals('121498.00', TaxCalculator::formatAsString($monthlyIncomeTaxAmount));
    }

    private function parse(string $moneyString): Money
    {
        return $this->parser->parse($moneyString, $this->currency);
    }

    protected function setupNIS(): void
    {
        NIS::factory()->create(
            [
                'effective_date' => '2021-01-01', 'rate_percentage' => '3.0',
                'annual_income_threshold' => $this->parse('1500000.00')
            ],
        );
        NIS::factory()->create(
            [
                'effective_date' => '2021-04-01', 'rate_percentage' => '3.0',
                'annual_income_threshold' => $this->parse('3000000.00')
            ],
        );
        NIS::factory()->create(
            [
                'effective_date' => '2022-04-01', 'rate_percentage' => '3.0',
                'annual_income_threshold' => $this->parse('5000000.00')
            ],
        );
    }

    private function setupEducationTax()
    {
        EducationTax::factory()->create(
            [
                'effective_date' => '2021-01-01', 'rate_percentage' => '2.25',
            ],
        );
    }
    private function setupNHT()
    {
        NHT::factory()->create(
            [
                'effective_date' => '2021-01-01', 'rate_percentage' => '2.0',
            ],
        );
    }
    private function setupIncomeTax()
    {
        IncomeTax::factory()->create(
            [
                'effective_date' => '2021-01-01',
                'annual_thresholds' => [
                    ['amount' => '1500096.00', 'rate_percent' => '25'],
                    ['amount' => '6000000.00', 'rate_percent' => '30']
                ],
            ],
        );
    }
}
