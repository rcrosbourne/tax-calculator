<?php

namespace App\Http\Controllers;

use App\Enums\PensionType;
use App\Models\Pension;
use App\Models\TaxCalculator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiTaxCalculatorController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'monthlyGross' => ['required', 'numeric'],
            'otherIncome' => ['numeric'],
            'pensionValue' => ['numeric'],
            'pensionType' => ['sometimes'],
            'otherDeductions' => ['numeric']
        ]);

        $calculator = new TaxCalculator(
            monthlyGross: $validated['monthlyGross'],
            otherTaxableIncome: $validated['otherIncome'],
            monthlyPension: new Pension(PensionType::from($validated['pensionType']), $validated['pensionValue']),
            listOfVoluntaryDeductions: ["deductions" => $validated['otherDeductions']]
        );


        $taxBreakdown = array_map(
            fn($entry) => number_format(floatval($entry), 2), // Add the thousand separator
            $calculator->fullTaxBreakDown());

        return response()->json(['breakdown' => $taxBreakdown]);
    }

}
