<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(Item::count() === 0) {
            Item::factory()->create(['id' => 1, 'name' => 'Item 1']);
            Item::factory()->create(['id' => 2, 'name' => 'Item 2']);
            Item::factory()->create(['id' => 3, 'name' => 'Item 3']);
            Item::factory()->create(['id' => 4, 'name' => 'Item 4']);
            Item::factory()->create(['id' => 5, 'name' => 'Item 5']);
        }
    }
}
