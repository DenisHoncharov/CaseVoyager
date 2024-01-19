<?php

namespace Tests\Feature\Models;

use App\Models\Cases;
use App\Models\Category;
use App\Models\Type;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_category_can_have_cases(): void
    {
        $category = Category::factory()->create();
        $case = Cases::factory()->create();

        $category->cases()->attach($case->id, ['user_id' => User::factory()->create()]);

        $this->assertCount(1, $category->fresh()->cases);
    }

    /** @test */
    public function a_category_can_have_type(): void
    {
        $type = Type::factory()->create();
        $category = Category::factory()->create([
            'type_id' => $type->id,
        ]);

        $this->assertInstanceOf(Type::class, $category->type);
        $this->assertEquals($type->name, $category->type->name);
    }
}
