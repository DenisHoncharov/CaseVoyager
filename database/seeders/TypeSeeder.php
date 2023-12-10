<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Type::count() === 0) {
            Type::factory()->create([
                'name' => 'CS2',
            ]);
        }
    }
}
