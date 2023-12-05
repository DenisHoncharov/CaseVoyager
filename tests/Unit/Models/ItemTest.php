<?php

namespace Tests\Unit\Models;

use App\Models\Cases;
use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function items_can_have_cases(): void
    {
        $item = Item::factory()->create();
        $case = Cases::factory()->create();

        $item->cases()->attach($case->id, ['user_id' => User::factory()->create()]);

        $this->assertCount(1, $item->fresh()->cases);
    }
}
