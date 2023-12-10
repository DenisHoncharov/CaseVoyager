<?php

namespace Database\Seeders;

use App\Models\Cases;
use Illuminate\Database\Seeder;

class CasesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Cases::count() === 0 && !app()->environment('production')) {
            Cases::factory()->create(['id' => 1, 'name' => 'Case 1']);
            Cases::factory()->create(['id' => 2, 'name' => 'Case 2']);
            Cases::factory()->create(['id' => 3, 'name' => 'Case 3']);
            Cases::factory()->create(['id' => 4, 'name' => 'Case 4']);
            Cases::factory()->create(['id' => 5, 'name' => 'Case 5']);
        }
    }
}
