<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IncomeTaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (DB::table('income_taxes')->count() > 0) {
            return;
        }
        DB::table('income_taxes')->truncate();
        DB::table('income_taxes')->insert([
            // 2021
            ['effective_date' => '2021-01-01', 'annual_thresholds' => json_encode([
                ['amount' => '1500096.00', 'rate_percent' => '25'],
                ['amount' => '6000000.00', 'rate_percent' => '30']
            ])],
        ]);
    }
}
