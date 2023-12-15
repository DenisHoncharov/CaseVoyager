<?php

namespace Database\Factories;

use App\Models\Cases;
use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Cases>
 */
class CasesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => ucwords(fake()->unique()->text(30)),
            'type_id' => Type::factory()->create(),
            'price' => fake()->randomFloat(2, 0,100),
            'image' => fake()->imageUrl,
            'description' => fake()->text()
        ];
    }
}
