<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'auth0_id' => Str::random(10),
            'balance' => fake()->randomFloat('2','0', '100'),
            'email' => fake()->unique()->safeEmail(),
            'telegram_id' => Str::random(10),
            'email_verified_at' => now(),
            'steam_profile_url' => fake()->url(),
            'steam_trade_link' => fake()->url(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
