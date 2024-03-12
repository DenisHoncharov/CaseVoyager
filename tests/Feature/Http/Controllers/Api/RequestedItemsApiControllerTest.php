<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Cases;
use App\Models\Item;
use App\Models\OpenCaseResult;
use App\Models\RequestedItems;
use App\Models\User;
use Auth0\Laravel\Entities\CredentialEntity;
use Auth0\Laravel\Traits\Impersonate;
use Auth0\Laravel\Users\ImposterUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class RequestedItemsApiControllerTest extends TestCase
{
    use RefreshDatabase, Impersonate;

    /** @test */
    public function user_can_show_all_own_items_request()
    {
        $imposter = new ImposterUser(['sub' => 'auth0|example']);
        $user = User::factory()->create(['auth0_id' => $imposter->getAuthIdentifier()]);

        $item = Item::factory()->create();

        $user->items()->attach($item->id);

        $requestedItem = RequestedItems::factory()->create([
            'user_id' => $user->id,
            'inventory_ids' => json_encode([$user->items()->first()->id]),
        ]);

        RequestedItems::factory()->create();

        $response = $this->impersonateToken(CredentialEntity::create($imposter), 'auth0-api')
            ->getJson(route('api.request-items.all'));
        $response->assertOk();

        $response->assertJsonCount(1, 'data');
        $this->assertEquals($requestedItem->id, $response->json('data.0.id'));

        $this->assertDatabaseCount('requested_items', 2);
    }

    /** @test */
    public function user_with_viewAllRequest_permission_can_show_all_items_request()
    {
        $imposter = new ImposterUser(['sub' => 'auth0|example']);
        $user = User::factory()->create(['auth0_id' => $imposter->getAuthIdentifier()]);
        $permission = Permission::create(['name' => 'requestedItem viewAllRequests']);
        $user->givePermissionTo($permission);

        RequestedItems::factory(2)->create();

        $response = $this->impersonateToken(CredentialEntity::create($imposter), 'auth0-api')
            ->getJson(route('api.request-items.all') . '?isAdmin=1');
        $response->assertOk();

        $response->assertJsonCount(2, 'data');
    }

    /** @test */
    public function user_without_viewAllRequest_permission_can_show_only_own_items_request()
    {
        $imposter = new ImposterUser(['sub' => 'auth0|example']);
        $user = User::factory()->create(['auth0_id' => $imposter->getAuthIdentifier()]);

        $userRequest = RequestedItems::factory()->create([
            'user_id' => $user->id,
        ]);
        RequestedItems::factory(2)->create();

        $response = $this->impersonateToken(CredentialEntity::create($imposter), 'auth0-api')
            ->getJson(route('api.request-items.all') . '?isAdmin=1');
        $response->assertOk();

        $response->assertJsonCount(1, 'data');
        $this->assertEquals($userRequest->id, $response->json('data.0.id'));
    }

    /** @test */
    public function user_can_create_request_to_order_items()
    {
        $imposter = new ImposterUser(['sub' => 'auth0|example']);
        $user = User::factory()->create(['auth0_id' => $imposter->getAuthIdentifier()]);

        $item = Item::factory()->create();

        $user->items()->attach($item->id);

        $itemUserInventoryId = $user->items()->first()->id;

        $response = $this->impersonateToken(CredentialEntity::create($imposter), 'auth0-api')
            ->postJson(route('api.request-items.create'), [
                'inventory_ids' => [
                    $itemUserInventoryId,
                ],
            ]);
        $response->assertOk();

        $this->assertDatabaseHas('item_user', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'is_requested' => true,
        ]);
    }

    /** @test */
    public function user_can_not_request_items_if_items_not_in_user_inventory()
    {
        $imposter = new ImposterUser(['sub' => 'auth0|example']);
        $user = User::factory()->create(['auth0_id' => $imposter->getAuthIdentifier()]);

        $item = Item::factory()->create();

        $case = Cases::factory()->create();

        OpenCaseResult::factory()->create([
            'user_id' => $user->id,
            'opened_case_id' => $case->id,
            'item_id' => $item->id,
        ]);

        $response = $this->impersonateToken(CredentialEntity::create($imposter), 'auth0-api')
            ->postJson(route('api.request-items.create'), [
                'inventory_ids' => [
                    0,
                ],
            ]);
        $response->assertStatus(422);

        $this->assertDatabaseMissing('requested_items', [
            'user_id' => $user->id
        ]);
    }

    /** @test */
    public function user_without_updateStatus_permission_can_not_update_request_items_status()
    {
        $requestedItem = RequestedItems::factory()->create([
            'status' => 'on_approval',
        ]);

        $imposter = new ImposterUser(['sub' => 'auth0|example']);

        $response = $this->impersonateToken(CredentialEntity::create($imposter), 'auth0-api')
            ->putJson(route('api.request-items.update', $requestedItem->id), [
                'status' => 'approved',
            ]);
        $response->assertStatus(403);

        $this->assertDatabaseHas('requested_items', [
            'id' => $requestedItem->id,
            'status' => 'on_approval',
        ]);
    }

    /** @test */
    public function user_with_updateStatus_permission_can_update_request_items_status()
    {
        $requestedItem = RequestedItems::factory()->create([
            'status' => 'on_approval',
        ]);

        $imposter = new ImposterUser(['sub' => 'auth0|example']);
        $user = User::factory()->create(['auth0_id' => $imposter->getAuthIdentifier()]);
        $permission = Permission::create(['name' => 'requestedItem updateStatus']);
        $user->givePermissionTo($permission);

        $response = $this->impersonateToken(CredentialEntity::create($imposter), 'auth0-api')
            ->putJson(route('api.request-items.update', $requestedItem->id), [
                'status' => 'approved',
            ]);
        $response->assertStatus(200);

        $this->assertDatabaseHas('requested_items', [
            'id' => $requestedItem->id,
            'status' => 'approved',
        ]);
    }

    /**
     * @test
     * @dataProvider statusesForRemoveItemsRequest
     */
    public function user_can_decline_items_request_with_on_approval_status($expect, $expectedHttpStatus, $status)
    {
        $imposter = new ImposterUser(['sub' => 'auth0|example']);
        $user = User::factory()->create(['auth0_id' => $imposter->getAuthIdentifier()]);

        $requestedItem = RequestedItems::factory()->create([
            'user_id' => $user->id,
            'status' => $status,
        ]);

        $response = $this->impersonateToken(CredentialEntity::create($imposter), 'auth0-api')
            ->deleteJson(route('api.request-items.delete', $requestedItem->id));
        $response->assertStatus($expectedHttpStatus);

        $this->assertCount($expect, RequestedItems::all());
    }

    /** @test */
    public function user_items_will_be_not_requested_after_delete_request_for_items()
    {
        $imposter = new ImposterUser(['sub' => 'auth0|example']);
        $user = User::factory()->create(['auth0_id' => $imposter->getAuthIdentifier()]);

        $item = Item::factory()->create();

        $user->items()->attach($item->id);

        $inventoryItemId = $user->items()->first()->id;

        $createRequestResponse = $this->impersonateToken(CredentialEntity::create($imposter), 'auth0-api')
            ->postJson(route('api.request-items.create'), [
                'inventory_ids' => [
                    $inventoryItemId
                ],
            ]);
        $createRequestResponse->assertOk();

        $this->assertDatabaseHas('item_user', [
            'id' => $inventoryItemId,
            'is_requested' => true,
        ]);

        $requestedItem = RequestedItems::first();

        $response = $this->impersonateToken(CredentialEntity::create($imposter), 'auth0-api')
            ->deleteJson(route('api.request-items.delete', $requestedItem->id));
        $response->assertOk();

        $this->assertCount(0, RequestedItems::all());
        $this->assertDatabaseHas('item_user', [
            'id' => $inventoryItemId,
            'is_requested' => false,
        ]);
    }

    public static function statusesForRemoveItemsRequest(): array
    {
        $expectedArray = [];

        foreach (RequestedItems::AVAILABLE_STATUSES as $status => $statusName) {
            $expect = 1;
            $expectedHttpStatus = 422;
            if ($status === 'on_approval') {
                $expect = 0;
                $expectedHttpStatus = 200;
            }
            $expectedArray['test_for_' . $statusName . '_status'] = [$expect, $expectedHttpStatus, $status];
        }

        return $expectedArray;
    }
}
