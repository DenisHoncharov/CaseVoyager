<?php

namespace Tests\Unit\Models;

use App\Models\Cases;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function category_can_have_cases(): void
    {
        $category = Category::factory()->create();
        $case = Cases::factory()->create();

        $category->cases()->attach($case->id, ['user_id' => User::factory()->create()]);

        $this->assertCount(1, $category->fresh()->cases);
    }
}
