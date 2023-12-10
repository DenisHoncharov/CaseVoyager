<?php

namespace Database\Seeders;

use App\Models\CaseItem;
use Illuminate\Database\Seeder;

class CaseItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (CaseItem::count() === 0 && !app()->environment('production')) {
            //case id 1
            CaseItem::insert([
                'cases_id' => 1,
                'item_id' => 1,
                'user_id' => 1
            ]);
            CaseItem::insert([
                'cases_id' => 1,
                'item_id' => 2,
                'user_id' => 1
            ]);

            //case id 2
            CaseItem::insert([
                'cases_id' => 2,
                'item_id' => 3,
                'user_id' => 1
            ]);
            CaseItem::insert([
                'cases_id' => 2,
                'item_id' => 4,
                'user_id' => 1
            ]);
            CaseItem::insert([
                'cases_id' => 2,
                'item_id' => 5,
                'user_id' => 1
            ]);

            //case id 3
            CaseItem::insert([
                'cases_id' => 3,
                'item_id' => 1,
                'user_id' => 1
            ]);
            CaseItem::insert([
                'cases_id' => 3,
                'item_id' => 2,
                'user_id' => 1
            ]);
            CaseItem::insert([
                'cases_id' => 3,
                'item_id' => 4,
                'user_id' => 1
            ]);
            CaseItem::insert([
                'cases_id' => 3,
                'item_id' => 5,
                'user_id' => 1
            ]);

            //case id 4
            CaseItem::insert([
                'cases_id' => 4,
                'item_id' => 3,
                'user_id' => 1
            ]);
            CaseItem::insert([
                'cases_id' => 4,
                'item_id' => 2,
                'user_id' => 1
            ]);
            CaseItem::insert([
                'cases_id' => 4,
                'item_id' => 4,
                'user_id' => 1
            ]);
            CaseItem::insert([
                'cases_id' => 4,
                'item_id' => 5,
                'user_id' => 1
            ]);

            //case id 5
            CaseItem::insert([
                'cases_id' => 5,
                'item_id' => 2,
                'user_id' => 1
            ]);
            CaseItem::insert([
                'cases_id' => 5,
                'item_id' => 5,
                'user_id' => 1
            ]);
        }
    }
}
