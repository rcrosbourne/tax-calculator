<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NISSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (DB::table('n_i_s')->count() > 0) {
            return;
        }
        DB::table('n_i_s')->truncate();
        DB::table('n_i_s')->insert([
            // 2021
            ['effective_date' => '2021-01-01', 'rate_percentage' => '3.0', 'annual_income_threshold' => '1500000.00'],
            ['effective_date' => '2021-04-01', 'rate_percentage' => '3.0', 'annual_income_threshold' => '3000000.00'],
            // 2022
            ['effective_date' => '2022-04-01', 'rate_percentage' => '3.0', 'annual_income_threshold' => '5000000.00'],
        ]);
    }
}
