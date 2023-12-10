<?php

namespace Tests\Unit\Models;

use App\Models\Cases;
use App\Models\Category;
use App\Models\Item;
use App\Models\Type;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_item_can_have_cases(): void
    {
        $item = Item::factory()->create();
        $case = Cases::factory()->create();

        $item->cases()->attach($case->id, ['user_id' => User::factory()->create()]);

        $this->assertCount(1, $item->fresh()->cases);
    }

    /** @test */
    public function a_item_can_have_type(): void
    {
        $type = Type::factory()->create();
        $item = Item::factory()->create([
            'type_id' => $type->id,
        ]);

        $this->assertInstanceOf(Type::class, $item->type);
        $this->assertEquals($type->name, $item->type->name);
    }
}
