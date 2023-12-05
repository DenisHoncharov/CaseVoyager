<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CaseItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (DB::table('cases_item')->count() === 0) {
            //case id 1
            DB::table('cases_item')->insert([
                'cases_id' => 1,
                'item_id' => 1,
                'user_id' => 1
            ]);
            DB::table('cases_item')->insert([
                'cases_id' => 1,
                'item_id' => 2,
                'user_id' => 1
            ]);

            //case id 2
            DB::table('cases_item')->insert([
                'cases_id' => 2,
                'item_id' => 3,
                'user_id' => 1
            ]);
            DB::table('cases_item')->insert([
                'cases_id' => 2,
                'item_id' => 4,
                'user_id' => 1
            ]);
            DB::table('cases_item')->insert([
                'cases_id' => 2,
                'item_id' => 5,
                'user_id' => 1
            ]);

            //case id 3
            DB::table('cases_item')->insert([
                'cases_id' => 3,
                'item_id' => 1,
                'user_id' => 1
            ]);
            DB::table('cases_item')->insert([
                'cases_id' => 3,
                'item_id' => 2,
                'user_id' => 1
            ]);
            DB::table('cases_item')->insert([
                'cases_id' => 3,
                'item_id' => 4,
                'user_id' => 1
            ]);
            DB::table('cases_item')->insert([
                'cases_id' => 3,
                'item_id' => 5,
                'user_id' => 1
            ]);

            //case id 4
            DB::table('cases_item')->insert([
                'cases_id' => 4,
                'item_id' => 3,
                'user_id' => 1
            ]);
            DB::table('cases_item')->insert([
                'cases_id' => 4,
                'item_id' => 2,
                'user_id' => 1
            ]);
            DB::table('cases_item')->insert([
                'cases_id' => 4,
                'item_id' => 4,
                'user_id' => 1
            ]);
            DB::table('cases_item')->insert([
                'cases_id' => 4,
                'item_id' => 5,
                'user_id' => 1
            ]);

            //case id 5
            DB::table('cases_item')->insert([
                'cases_id' => 5,
                'item_id' => 2,
                'user_id' => 1
            ]);
            DB::table('cases_item')->insert([
                'cases_id' => 5,
                'item_id' => 5,
                'user_id' => 1
            ]);
        }
    }
}
