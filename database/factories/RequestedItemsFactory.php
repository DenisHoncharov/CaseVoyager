<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserInventory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RequestedItems>
 */
class RequestedItemsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create(),
            'inventory_ids' => json_encode([UserInventory::factory()->create()->id]),
            'status' => false,
        ];
    }
}
