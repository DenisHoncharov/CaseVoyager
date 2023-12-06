<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryApiControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_get_all(): void
    {
        $count = 2;
        Category::factory($count)->create();

        $response = $this->actingAs(User::factory()->create())
            ->get(route('api.categories.all'));
        $response->assertOk();

        $response->assertJsonCount($count, 'data');
    }

    /** @test */
    public function user_can_create(): void
    {
        $category = Category::factory()->make();

        $response = $this->actingAs(User::factory()->create())
            ->post(route('api.categories.create'), $category->toArray());
        $response->assertOk();

        $this->assertDatabaseHas('categories', ['name' => $category->name]);
        $this->assertDatabaseCount('categories', 1);
    }

    /** @test */
    public function user_can_update(): void
    {
        $newName = 'newName';
        $category = Category::factory()->create();

        $categoryRaw = $category->toArray();
        $categoryRaw['name'] = $newName;

        $response = $this->actingAs(User::factory()->create())
            ->put(route('api.categories.update', ['category' => $category->id]), $categoryRaw);
        $response->assertOk();

        $this->assertDatabaseMissing('categories', ['name' => $category->name]);
        $this->assertDatabaseHas('categories', ['name' => $newName]);
    }

    /** @test */
    public function user_can_delete(): void
    {
        $category = Category::factory()->create();

        $this->assertDatabaseHas('categories', ['id' => $category->id]);

        $response = $this->actingAs(User::factory()->create())
            ->delete(route('api.categories.delete', ['category' => $category->id]));

        $response->assertOk();

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
