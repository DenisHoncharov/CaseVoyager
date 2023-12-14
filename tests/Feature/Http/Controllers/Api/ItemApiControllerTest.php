<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Item;
use App\Models\User;
use Auth0\Laravel\Entities\CredentialEntity;
use Auth0\Laravel\Traits\Impersonate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemApiControllerTest extends TestCase
{
    use RefreshDatabase, Impersonate;

    /** @test */
    public function user_can_get_all(): void
    {
        $count = 2;
        Item::factory($count)->create();

        $response = $this->impersonateToken(CredentialEntity::create(), 'auth0-api')
            ->getJson(route('api.items.all'));
        $response->assertOk();

        $response->assertJsonCount($count, 'data');
    }

    /** @test */
    public function user_can_show(): void
    {
        $item = Item::factory()->create();

        $response = $this->impersonateToken(CredentialEntity::create(), 'auth0-api')
            ->getJson(route('api.items.show', ['item' => $item->id]));
        $response->assertOk();

        $response->assertJsonFragment(['id' => $item->id]);
    }

    /** @test */
    public function user_can_create(): void
    {
        $item = Item::factory()->make();

        $response = $this->impersonateToken(CredentialEntity::create(), 'auth0-api')
            ->postJson(route('api.items.create'), $item->toArray());
        $response->assertOk();

        $this->assertDatabaseHas('items', ['name' => $item->name]);
        $this->assertDatabaseCount('items', 1);
    }

    /** @test */
    public function user_can_update(): void
    {
        $newName = 'newName';
        $item = Item::factory()->create();

        $itemRaw = $item->toArray();
        $itemRaw['name'] = $newName;

        $response = $this->impersonateToken(CredentialEntity::create(), 'auth0-api')
            ->putJson(route('api.items.update', ['item' => $item->id]), $itemRaw);
        $response->assertOk();

        $this->assertDatabaseMissing('items', ['name' => $item->name]);
        $this->assertDatabaseHas('items', ['name' => $newName]);
    }

    /** @test */
    public function user_can_delete(): void
    {
        $item = Item::factory()->create();

        $this->assertDatabaseHas('items', ['id' => $item->id]);

        $response = $this->impersonateToken(CredentialEntity::create(), 'auth0-api')
            ->deleteJson(route('api.items.delete', ['item' => $item->id]));

        $response->assertOk();

        $this->assertDatabaseMissing('items', ['id' => $item->id]);
    }
}
