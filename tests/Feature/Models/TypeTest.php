<?php

namespace Tests\Feature\Models;

use App\Models\Cases;
use App\Models\Category;
use App\Models\Item;
use App\Models\Type;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TypeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_type_has_many_cases(): void
    {
        $type = Type::factory()->create();
        Cases::factory(2)->create(['type_id' => $type->id]);

        $this->assertCount(2, $type->cases);
        $this->assertInstanceOf(Cases::class, $type->cases->first());
    }

    /** @test */
    public function a_type_has_many_categories(): void
    {
        $type = Type::factory()->create();
        Category::factory(2)->create(['type_id' => $type->id]);

        $this->assertCount(2, $type->categories);
        $this->assertInstanceOf(Category::class, $type->categories->first());
    }

    /** @test */
    public function a_type_has_many_items(): void
    {
        $type = Type::factory()->create();
        Item::factory(2)->create(['type_id' => $type->id]);

        $this->assertCount(2, $type->items);
        $this->assertInstanceOf(Item::class, $type->items->first());
    }
}
