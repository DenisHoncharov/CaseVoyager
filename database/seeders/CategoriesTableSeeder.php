<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        if (Category::count() === 0) {
            Category::factory()->create(['id' => 1, 'name' => 'Popular']);
            Category::factory()->create(['id' => 2, 'name' => 'Most Relevant']);
        }
    }
}
