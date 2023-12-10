<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Type;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TypeApiControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_get_all(): void
    {
        $count = 2;
        Type::factory($count)->create();

        $response = $this->actingAs(User::factory()->create())
            ->get(route('api.types.all'));
        $response->assertOk();

        $response->assertJsonCount($count, 'data');
    }

    /** @test */
    public function user_can_show(): void
    {
        $type = Type::factory()->create();

        $response = $this->actingAs(User::factory()->create())
            ->get(route('api.types.show', ['type' => $type->id]));
        $response->assertOk();

        $response->assertJsonFragment(['id' => $type->id]);
    }

    /** @test */
    public function user_can_create(): void
    {
        $type = Type::factory()->make();

        $response = $this->actingAs(User::factory()->create())
            ->post(route('api.types.create'), $type->toArray());
        $response->assertOk();

        $this->assertDatabaseHas('types', ['name' => $type->name]);
        $this->assertDatabaseCount('types', 1);
    }

    /** @test */
    public function user_can_update(): void
    {
        $newName = 'newName';
        $type = Type::factory()->create();

        $typeRaw = $type->toArray();
        $typeRaw['name'] = $newName;

        $response = $this->actingAs(User::factory()->create())
            ->put(route('api.types.update', ['type' => $type->id]), $typeRaw);
        $response->assertOk();

        $this->assertDatabaseMissing('types', ['name' => $type->name]);
        $this->assertDatabaseHas('types', ['name' => $newName]);
    }

    /** @test */
    public function user_can_delete(): void
    {
        $type = Type::factory()->create();

        $this->assertDatabaseHas('types', ['id' => $type->id]);

        $response = $this->actingAs(User::factory()->create())
            ->delete(route('api.types.delete', ['type' => $type->id]));

        $response->assertOk();

        $this->assertDatabaseMissing('types', ['id' => $type->id]);
    }
}
