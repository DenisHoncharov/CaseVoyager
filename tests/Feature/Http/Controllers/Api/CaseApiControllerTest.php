<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Cases;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CaseApiControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_get_all(): void
    {
        $count = 2;
        Cases::factory($count)->create();

        $response = $this->actingAs(User::factory()->create())
            ->get(route('api.cases.all'));
        $response->assertOk();

        $response->assertJsonCount($count, 'data');
    }

    /** @test */
    public function user_can_show(): void
    {
        $case = Cases::factory()->create();

        $response = $this->actingAs(User::factory()->create())
            ->get(route('api.cases.show', ['case' => $case->id]));
        $response->assertOk();

        $response->assertJsonFragment(['id' => $case->id]);
    }

    /** @test */
    public function user_can_create(): void
    {
        $case = Cases::factory()->make();

        $response = $this->actingAs(User::factory()->create())
            ->post(route('api.cases.create'), $case->toArray());
        $response->assertOk();

        $this->assertDatabaseHas('cases', ['name' => $case->name]);
        $this->assertDatabaseCount('cases', 1);
    }

    /** @test */
    public function user_can_update(): void
    {
        $newName = 'newName';
        $case = Cases::factory()->create();

        $caseRaw = $case->toArray();
        $caseRaw['name'] = $newName;

        $response = $this->actingAs(User::factory()->create())
            ->put(route('api.cases.update', ['case' => $case->id]), $caseRaw);
        $response->assertOk();

        $this->assertDatabaseMissing('cases', ['name' => $case->name]);
        $this->assertDatabaseHas('cases', ['name' => $newName]);
    }

    /** @test */
    public function user_can_delete(): void
    {
        $case = Cases::factory()->create();

        $this->assertDatabaseHas('cases', ['id' => $case->id]);

        $response = $this->actingAs(User::factory()->create())
            ->delete(route('api.cases.delete', ['case' => $case->id]));

        $response->assertOk();

        $this->assertDatabaseMissing('cases', ['id' => $case->id]);
    }

    /** @test */
    public function user_can_open_case(): void
    {
        $this->markTestSkipped('Make test for random');
        //TODO: make test for random
        //$this->actingAs(User::factory()->create())->
    }

    /** @test */
    public function user_can_assign_existing_items_to_case(): void
    {
        $case = Cases::factory()->create();
        $items = Item::factory(2)->create();

        $itemsRequest = [
            [
                'item_id' => $items[0]->id,
                'drop_percentage' => 60
            ],
            [
                'item_id' => $items[1]->id,
                'drop_percentage' => 40
            ]
        ];

        $response = $this->actingAs(User::factory()->create())
            ->post(route('api.cases.items', ['case' => $case->id]), ['items' => $itemsRequest]);
        $response->assertOk();

        $this->assertDatabaseHas('case_item', ['cases_id' => $case->id, 'item_id' => $items[0]->id, 'drop_percentage' => 60]);
        $this->assertDatabaseHas('case_item', ['cases_id' => $case->id, 'item_id' => $items[1]->id, 'drop_percentage' => 40]);
    }
    
    /** @test */
    public function user_can_unassign_existing_items_from_case(): void
    {
        $case = Cases::factory()->create();
        $items = Item::factory(2)->create();
        $user = User::factory()->create();
        $case->items()->attach($items, ['user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->post(route('api.cases.items', ['case' => $case->id]), ['items' => []]);
        $response->assertOk();

        $this->assertDatabaseMissing('case_item', ['cases_id' => $case->id, 'item_id' => $items[0]->id]);
        $this->assertDatabaseMissing('case_item', ['cases_id' => $case->id, 'item_id' => $items[1]->id]);
    }
    
    /** @test */
    public function user_can_sync_existing_items_in_case(): void
    {
        $case = Cases::factory()->create();
        $itemExisted = Item::factory()->create();
        $itemNew = Item::factory()->create();
        $user = User::factory()->create();
        
        $case->items()->attach($itemExisted, ['user_id' => $user->id, 'drop_percentage' => 50]);

        $itemsRequest = [
            [
                'item_id' => $itemNew->id,
                'drop_percentage' => 60
            ],
        ];
        
        $response = $this->actingAs($user)
            ->post(route('api.cases.items', ['case' => $case->id]), ['items' => $itemsRequest]);
        $response->assertOk();
        
        $this->assertDatabaseMissing('case_item', ['cases_id' => $case->id, 'item_id' => $itemExisted->id]);
        $this->assertDatabaseHas('case_item', ['cases_id' => $case->id, 'item_id' => $itemNew->id]);
    }

    /** @test */
    public function user_can_assign_existing_items_when_create_case(): void
    {
        $case = Cases::factory()->raw();
        $items = Item::factory(2)->create();

        $itemsRequest = [
            [
                'item_id' => $items[0]->id,
                'drop_percentage' => 60
            ],
            [
                'item_id' => $items[1]->id,
                'drop_percentage' => 40
            ]
        ];


        $case['items'] = $itemsRequest;

        $response = $this->actingAs(User::factory()->create())
            ->post(route('api.cases.create'), $case);
        $response->assertOk();

        $this->assertDatabaseHas('case_item', ['cases_id' => $response->json('id'), 'item_id' => $items[0]->id, 'drop_percentage' => 60]);
        $this->assertDatabaseHas('case_item', ['cases_id' => $response->json('id'), 'item_id' => $items[1]->id, 'drop_percentage' => 40]);
    }

    /** @test */
    public function user_can_assign_existing_items_when_update_case(): void
    {
        $case = Cases::factory()->create();
        $items = Item::factory(2)->create();

        $itemsRequest = [
            [
                'item_id' => $items[0]->id,
                'drop_percentage' => 60
            ],
            [
                'item_id' => $items[1]->id,
                'drop_percentage' => 40
            ]
        ];

        $case['items'] = $itemsRequest;

        $response = $this->actingAs(User::factory()->create())
            ->put(route('api.cases.update', ['case' => $case->id]), $case->toArray());
        $response->assertOk();

        $this->assertDatabaseHas('case_item', ['cases_id' => $case->id, 'item_id' => $items[0]->id, 'drop_percentage' => 60]);
        $this->assertDatabaseHas('case_item', ['cases_id' => $case->id, 'item_id' => $items[1]->id, 'drop_percentage' => 40]);
    }
}
