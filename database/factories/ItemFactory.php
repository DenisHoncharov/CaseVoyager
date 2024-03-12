<?php

namespace Database\Factories;

use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
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
            'quality' => fake()->randomFloat(2, 0,100),
            'rarity' => fake()->text(10),
            'image' => fake()->imageUrl(),
            'source_preview_link' => fake()->url(),
            'source_marketplace_link' => fake()->url(),
        ];
    }
}
