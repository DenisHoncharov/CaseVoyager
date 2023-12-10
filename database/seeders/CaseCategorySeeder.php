<?php

namespace Database\Seeders;

use App\Models\CaseCategory;
use Illuminate\Database\Seeder;

class CaseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (CaseCategory::count() === 0 && !app()->environment('production')) {
            CaseCategory::insert([
                'cases_id' => 1,
                'category_id' => 1,
                'user_id' => 1
            ]);
            CaseCategory::insert([
                'cases_id' => 2,
                'category_id' => 1,
                'user_id' => 1
            ]);
            CaseCategory::insert([
                'cases_id' => 3,
                'category_id' => 1,
                'user_id' => 1
            ]);
            CaseCategory::insert([
                'cases_id' => 4,
                'category_id' => 2,
                'user_id' => 1
            ]);
            CaseCategory::insert([
                'cases_id' => 5,
                'category_id' => 2,
                'user_id' => 1
            ]);
        }
    }
}
