<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NHTSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        if (DB::table('n_h_t_s')->count() > 0) {
            return;
        }
        DB::table('n_h_t_s')->truncate();
        DB::table('n_h_t_s')->insert([
            // 2021
            ['effective_date' => '2021-01-01', 'rate_percentage' => '2.0'],
        ]);
    }
}
