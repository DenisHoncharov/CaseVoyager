<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Cases;
use App\Models\Item;
use Auth0\Laravel\Entities\CredentialEntity;
use Auth0\Laravel\Traits\Impersonate;
use Auth0\Laravel\Users\ImposterUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CaseApiControllerTest extends TestCase
{
    use RefreshDatabase, Impersonate;

    /** @test */
    public function user_can_get_all(): void
    {
        $count = 2;
        Cases::factory($count)->create();

        $response = $this->impersonateToken(CredentialEntity::create(), 'auth0-api')
            ->getJson(route('api.cases.all'));
        $response->assertOk();

        $response->assertJsonCount($count, 'data');
    }

    /** @test */
    public function user_can_show(): void
    {
        $case = Cases::factory()->create();

        $response = $this->impersonateToken(CredentialEntity::create(), 'auth0-api')
            ->getJson(route('api.cases.show', ['case' => $case->id]));
        $response->assertOk();

        $response->assertJsonFragment(['id' => $case->id]);
    }

    /** @test */
    public function user_can_create(): void
    {
        $case = Cases::factory()->make();

        $response = $this->impersonateToken(CredentialEntity::create(), 'auth0-api')
            ->postJson(route('api.cases.create'), $case->toArray());
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

        $response = $this->impersonateToken(CredentialEntity::create(), 'auth0-api')
            ->putJson(route('api.cases.update', ['case' => $case->id]), $caseRaw);
        $response->assertOk();

        $this->assertDatabaseMissing('cases', ['name' => $case->name]);
        $this->assertDatabaseHas('cases', ['name' => $newName]);
    }

    /** @test */
    public function user_can_delete(): void
    {
        $case = Cases::factory()->create();

        $this->assertDatabaseHas('cases', ['id' => $case->id]);

        $response = $this->impersonateToken(CredentialEntity::create(), 'auth0-api')
            ->deleteJson(route('api.cases.delete', ['case' => $case->id]));

        $response->assertOk();

        $this->assertDatabaseMissing('cases', ['id' => $case->id]);
    }

    /** @test */
    public function user_can_open_case(): void
    {
        $this->markTestSkipped('Make test for random');
        //TODO: make test for random
        //$this->impersonateToken(CredentialEntity::create(), 'auth0-api')->
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

        $imposter = new ImposterUser(['sub' => 'auth0|example']);
        $response = $this->impersonateToken(CredentialEntity::create($imposter), 'auth0-api')
            ->postJson(route('api.cases.items', ['case' => $case->id]), ['items' => $itemsRequest]);
        $response->assertOk();

        $this->assertDatabaseHas('case_item', ['cases_id' => $case->id, 'item_id' => $items[0]->id, 'drop_percentage' => 60]);
        $this->assertDatabaseHas('case_item', ['cases_id' => $case->id, 'item_id' => $items[1]->id, 'drop_percentage' => 40]);
    }
    
    /** @test */
    public function user_can_unassign_existing_items_from_case(): void
    {
        $case = Cases::factory()->create();
        $items = Item::factory(2)->create();
        $imposter = new ImposterUser(['sub' => 'auth0|example']);
        $case->items()->attach($items, ['user_id' => $imposter->getAuthIdentifier()]);

        $response = $this->impersonateToken(CredentialEntity::create($imposter), 'auth0-api')
            ->postJson(route('api.cases.items', ['case' => $case->id]), ['items' => []]);
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
        $imposter = new ImposterUser(['sub' => 'auth0|example']);
        
        $case->items()->attach($itemExisted, ['user_id' => $imposter->getAuthIdentifier(), 'drop_percentage' => 50]);

        $itemsRequest = [
            [
                'item_id' => $itemNew->id,
                'drop_percentage' => 100
            ],
        ];
        
        $response = $this->impersonateToken(CredentialEntity::create($imposter), 'auth0-api')
            ->postJson(route('api.cases.items', ['case' => $case->id]), ['items' => $itemsRequest]);
        $response->assertOk();
        
        $this->assertDatabaseMissing('case_item', ['cases_id' => $case->id, 'item_id' => $itemExisted->id]);
        $this->assertDatabaseHas('case_item', ['cases_id' => $case->id, 'item_id' => $itemNew->id]);
    }

    /** @test */
    public function user_can_assign_existing_items_when_create_case(): void
    {
        $case = Cases::factory()->raw();
        $items = Item::factory(2)->create();;

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

        $response = $this->impersonateToken(CredentialEntity::create(new ImposterUser(['sub' => 'auth0|example'])), 'auth0-api')
            ->postJson(route('api.cases.create'), $case);
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

        $response = $this->impersonateToken(CredentialEntity::create(new ImposterUser(['sub' => 'auth0|example'])), 'auth0-api')
            ->putJson(route('api.cases.update', ['case' => $case->id]), $case->toArray());
        $response->assertOk();

        $this->assertDatabaseHas('case_item', ['cases_id' => $case->id, 'item_id' => $items[0]->id, 'drop_percentage' => 60]);
        $this->assertDatabaseHas('case_item', ['cases_id' => $case->id, 'item_id' => $items[1]->id, 'drop_percentage' => 40]);
    }

    /** @test */
    public function sum_drop_percentage_can_not_be_more_100_percentage()
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
                'drop_percentage' => 50
            ]
        ];

        $case['items'] = $itemsRequest;

        $response = $this->impersonateToken(CredentialEntity::create(new ImposterUser(['sub' => 'auth0|example'])), 'auth0-api')
            ->putJson(route('api.cases.update', ['case' => $case->id]), $case->toArray());
        $response->assertStatus(422);
    }

    /** @test */
    public function sum_drop_percentage_can_not_be_less_100_percentage()
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

        $response = $this->impersonateToken(CredentialEntity::create(new ImposterUser(['sub' => 'auth0|example'])), 'auth0-api')
            ->putJson(route('api.cases.update', ['case' => $case->id]), $case->toArray());
        $response->assertOk();
    }
}
