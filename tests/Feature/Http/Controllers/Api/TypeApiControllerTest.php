<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Type;
use Auth0\Laravel\Entities\CredentialEntity;
use Auth0\Laravel\Traits\Impersonate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TypeApiControllerTest extends TestCase
{
    use RefreshDatabase, Impersonate;

    /** @test */
    public function user_can_get_all(): void
    {
        $count = 2;
        Type::factory($count)->create();

        $response = $this->impersonateToken(CredentialEntity::create(), 'auth0-api')
            ->getJson(route('api.types.all'));
        $response->assertOk();

        $response->assertJsonCount($count, 'data');
    }

    /** @test */
    public function user_can_show(): void
    {
        $type = Type::factory()->create();

        $response = $this->impersonateToken(CredentialEntity::create(), 'auth0-api')
            ->getJson(route('api.types.show', ['type' => $type->id]));
        $response->assertOk();

        $response->assertJsonFragment(['id' => $type->id]);
    }

    /** @test */
    public function user_can_create(): void
    {
        $type = Type::factory()->make();

        $response = $this->impersonateToken(CredentialEntity::create(), 'auth0-api')
            ->postJson(route('api.types.create'), $type->toArray());
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

        $response = $this->impersonateToken(CredentialEntity::create(), 'auth0-api')
            ->putJson(route('api.types.update', ['type' => $type->id]), $typeRaw);
        $response->assertOk();

        $this->assertDatabaseMissing('types', ['name' => $type->name]);
        $this->assertDatabaseHas('types', ['name' => $newName]);
    }

    /** @test */
    public function user_can_delete(): void
    {
        $type = Type::factory()->create();

        $this->assertDatabaseHas('types', ['id' => $type->id]);

        $response = $this->impersonateToken(CredentialEntity::create(), 'auth0-api')
            ->deleteJson(route('api.types.delete', ['type' => $type->id]));

        $response->assertOk();

        $this->assertDatabaseMissing('types', ['id' => $type->id]);
    }
}
