<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CaseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (DB::table('cases_category')->count() === 0) {
            DB::table('cases_category')->insert([
                'cases_id' => 1,
                'category_id' => 1,
                'user_id' => 1
            ]);
            DB::table('cases_category')->insert([
                'cases_id' => 2,
                'category_id' => 1,
                'user_id' => 1
            ]);
            DB::table('cases_category')->insert([
                'cases_id' => 3,
                'category_id' => 1,
                'user_id' => 1
            ]);
            DB::table('cases_category')->insert([
                'cases_id' => 4,
                'category_id' => 2,
                'user_id' => 1
            ]);
            DB::table('cases_category')->insert([
                'cases_id' => 5,
                'category_id' => 2,
                'user_id' => 1
            ]);
        }
    }
}
