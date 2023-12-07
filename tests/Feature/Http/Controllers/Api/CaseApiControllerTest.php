<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Cases;
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
}
