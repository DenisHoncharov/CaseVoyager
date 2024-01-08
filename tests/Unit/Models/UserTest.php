<?php

namespace Tests\Unit\Models;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_have_items(): void
    {
        $item = Item::factory()->create();
        $item2 = Item::factory()->create();
        $user = User::factory()->create();

        $user->items()->attach($item->id);
        $user->items()->attach($item2->id);

        $this->assertCount(2, $user->fresh()->items);
    }

    /** @test */
    public function a_user_can_have_requested_items()
    {
        $user = User::factory()->create();
        $user->requestedItems()->create([
            'inventory_ids' => json_encode([1, 2, 3])
        ]);

        $this->assertCount(1, $user->fresh()->requestedItems);
    }
}
