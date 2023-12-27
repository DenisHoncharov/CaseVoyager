<?php

namespace Database\Factories;

use App\Models\Cases;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OpenCaseResult>
 */
class OpenCaseResultFactory extends Factory
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
            'opened_case_id' => Cases::factory()->create(),
            'item_id' => Item::factory()->create(),
            'is_received' => false,
        ];
    }
}
