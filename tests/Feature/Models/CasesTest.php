<?php

namespace Tests\Unit\Models;

use App\Models\Cases;
use App\Models\Category;
use App\Models\Item;
use App\Models\Type;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CasesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_cases_can_have_categories(): void
    {
        $category = Category::factory()->create();
        $case = Cases::factory()->create();

        $case->categories()->attach($category->id, ['user_id' => User::factory()->create()]);

        $this->assertCount(1, $case->fresh()->categories);
    }

    /** @test */
    public function a_cases_can_have_items(): void
    {
        $item = Item::factory()->create();
        $case = Cases::factory()->create();

        $case->items()->attach($item->id, ['user_id' => User::factory()->create()]);

        $this->assertCount(1, $case->fresh()->items);
    }

    /** @test */
    public function a_cases_can_have_type(): void
    {
        $type = Type::factory()->create();
        $case = Cases::factory()->create([
            'type_id' => $type->id,
        ]);

        $this->assertInstanceOf(Type::class, $case->type);
        $this->assertEquals($type->name, $case->type->name);
    }
}