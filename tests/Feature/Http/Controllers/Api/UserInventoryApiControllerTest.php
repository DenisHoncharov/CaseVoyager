<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Cases;
use App\Models\Item;
use App\Models\User;
use Auth0\Laravel\Entities\CredentialEntity;
use Auth0\Laravel\Traits\Impersonate;
use Auth0\Laravel\Users\ImposterUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UserInventoryApiControllerTest extends TestCase
{
    use RefreshDatabase, Impersonate;

    /** @test */
    public function user_can_get_all_own_inventory_items(): void
    {
        $count = 2;
        $items = Item::factory($count)->create();
        $imposter = new ImposterUser(['sub' => 'auth0|example']);
        $user = User::factory()->create(['auth0_id' => $imposter->getAuthIdentifier()]);

        $otherItem = Item::factory()->create();
        $otherUser = User::factory()->create();

        $user->items()->attach($items);
        $otherUser->items()->attach($otherItem);

        $response = $this->impersonateToken(CredentialEntity::create($imposter), 'auth0-api')
            ->getJson(route('api.inventory.all'));
        $response->assertOk();

        $response->assertJsonCount($count, 'data');
    }

    /** @test */
    public function user_can_add_item_to_inventory(): void
    {
        $item = Item::factory()->create();
        $imposter = new ImposterUser(['sub' => 'auth0|example']);
        $user = User::factory()->create(['auth0_id' => $imposter->getAuthIdentifier()]);

        $openCaseResult = DB::table('open_case_results')->insertGetId([
            'opened_case_id' => Cases::factory()->create()->id,
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);

        $response = $this->impersonateToken(CredentialEntity::create($imposter), 'auth0-api')
            ->postJson(route('api.inventory.add'), [
                'items' => [
                    [
                        'openCaseResultId' => $openCaseResult,
                        'item_id' => $item->id
                    ]
                ]
            ]);
        $response->assertOk();

        $this->assertDatabaseHas('item_user', ['item_id' => $item->id, 'user_id' => $user->id]);
    }

    /** @test */
    public function user_can_add_multiple_items_to_inventory(): void
    {
        $items = Item::factory(2)->create();
        $imposter = new ImposterUser(['sub' => 'auth0|example']);
        $user = User::factory()->create(['auth0_id' => $imposter->getAuthIdentifier()]);

        $openCaseResultOne = DB::table('open_case_results')->insertGetId([
            'opened_case_id' => Cases::factory()->create()->id,
            'item_id' => $items[0]->id,
            'user_id' => $user->id,
        ]);

        $openCaseResultTwo = DB::table('open_case_results')->insertGetId([
            'opened_case_id' => Cases::factory()->create()->id,
            'item_id' => $items[1]->id,
            'user_id' => $user->id,
        ]);

        $response = $this->impersonateToken(CredentialEntity::create($imposter), 'auth0-api')
            ->postJson(route('api.inventory.add'), [
                'items' => [
                    [
                        'openCaseResultId' => $openCaseResultOne,
                        'item_id' => $items[0]->id
                    ],
                    [
                        'openCaseResultId' => $openCaseResultTwo,
                        'item_id' => $items[1]->id
                    ]
                ]
            ]);
        $response->assertOk();

        $this->assertDatabaseHas('item_user', ['item_id' => $items[0]->id, 'user_id' => $user->id]);
        $this->assertDatabaseCount('item_user', 2);
    }

    /** @test */
    public function user_can_not_add_item_from_case_opened_by_not_him()
    {
        $item = Item::factory()->create();
        $imposter = new ImposterUser(['sub' => 'auth0|example']);
        $user = User::factory()->create(['auth0_id' => $imposter->getAuthIdentifier()]);
        $otherUser = User::factory()->create();

        $openCaseResult = DB::table('open_case_results')->insertGetId([
            'opened_case_id' => Cases::factory()->create()->id,
            'item_id' => $item->id,
            'user_id' => $otherUser->id,
        ]);

        $response = $this->impersonateToken(CredentialEntity::create($imposter), 'auth0-api')
            ->postJson(route('api.inventory.add'), [
                'items' => [
                    [
                        'openCaseResultId' => $openCaseResult,
                        'item_id' => $item->id
                    ]
                ]
            ]);
        $response->assertStatus(422);

        $this->assertDatabaseMissing('item_user', ['item_id' => $item->id, 'user_id' => $user->id]);
    }

    /** @test */
    public function user_can_not_add_item_not_from_opened_case()
    {
        $item = Item::factory()->create();
        $otherItem = Item::factory()->create();
        $imposter = new ImposterUser(['sub' => 'auth0|example']);
        $user = User::factory()->create(['auth0_id' => $imposter->getAuthIdentifier()]);

        $openCaseResult = DB::table('open_case_results')->insertGetId([
            'opened_case_id' => Cases::factory()->create()->id,
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);

        $response = $this->impersonateToken(CredentialEntity::create($imposter), 'auth0-api')
            ->postJson(route('api.inventory.add'), [
                'items' => [
                    [
                        'openCaseResultId' => $openCaseResult,
                        'item_id' => $otherItem->id
                    ]
                ]
            ]);
        $response->assertStatus(422);

        $this->assertDatabaseMissing('item_user', ['item_id' => $otherItem->id, 'user_id' => $user->id]);
    }

    /** @test */
    public function user_can_delete_item_from_own_inventory(): void
    {
        $items = Item::factory(2)->create();
        $imposter = new ImposterUser(['sub' => 'auth0|example']);
        $user = User::factory()->create(['auth0_id' => $imposter->getAuthIdentifier()]);

        $inventoryItemId = DB::table('item_user')->insertGetId([
            'item_id' => $items[0]->id,
            'user_id' => $user->id,
        ]);

        DB::table('item_user')->insertGetId([
            'item_id' => $items[1]->id,
            'user_id' => $user->id,
        ]);

        $response = $this->impersonateToken(CredentialEntity::create($imposter), 'auth0-api')
            ->deleteJson(route('api.inventory.delete', ['items' => [$inventoryItemId]]));
        $response->assertOk();

        $this->assertDatabaseMissing('item_user', ['id' => $inventoryItemId]);
        $this->assertDatabaseCount('item_user', 1);
    }

    /** @test */
    public function user_can_delete_multiple_items_from_own_inventory(): void
    {
        $items = Item::factory(3)->create();
        $imposter = new ImposterUser(['sub' => 'auth0|example']);
        $user = User::factory()->create(['auth0_id' => $imposter->getAuthIdentifier()]);

        $inventoryItemIdOne = DB::table('item_user')->insertGetId([
            'item_id' => $items[0]->id,
            'user_id' => $user->id,
        ]);

        $inventoryItemIdTwo = DB::table('item_user')->insertGetId([
            'item_id' => $items[1]->id,
            'user_id' => $user->id,
        ]);

        DB::table('item_user')->insertGetId([
            'item_id' => $items[2]->id,
            'user_id' => $user->id,
        ]);

        $response = $this->impersonateToken(CredentialEntity::create($imposter), 'auth0-api')
            ->deleteJson(route('api.inventory.delete', ['items' => [$inventoryItemIdOne, $inventoryItemIdTwo]]));
        $response->assertOk();

        $this->assertDatabaseMissing('item_user', ['id' => $inventoryItemIdOne]);
        $this->assertDatabaseMissing('item_user', ['id' => $inventoryItemIdTwo]);
        $this->assertDatabaseCount('item_user', 1);
    }
}
