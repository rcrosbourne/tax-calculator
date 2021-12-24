<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EducationTaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        if (DB::table('education_taxes')->count() > 0) {
            return;
        }
        DB::table('education_taxes')->truncate();
        DB::table('education_taxes')->insert([
            // 2021
            ['effective_date' => '2021-01-01', 'rate_percentage' => '2.25'],
        ]);
    }
}
