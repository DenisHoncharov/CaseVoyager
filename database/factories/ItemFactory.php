<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
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
            'name' => ucwords(fake()->unique()->word),
            'type' => fake()->text(5),
            'price' => fake()->randomFloat(2, 0,100),
            'quality' => fake()->randomFloat(2, 0,100),
            'rarity' => fake()->text(10),
            'image' => fake()->imageUrl(),
        ];
    }
}
