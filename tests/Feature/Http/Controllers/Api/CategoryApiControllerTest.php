<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Cases;
use App\Models\Category;
use App\Models\User;
use Auth0\Laravel\Entities\CredentialEntity;
use Auth0\Laravel\Traits\Impersonate;
use Auth0\Laravel\Users\ImposterUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryApiControllerTest extends TestCase
{
    use RefreshDatabase, Impersonate;

    /** @test */
    public function user_can_get_all(): void
    {
        $count = 2;
        Category::factory($count)->create();

        $response = $this->impersonateToken(CredentialEntity::create(), 'auth0-api')
            ->getJson(route('api.categories.all'));
        $response->assertOk();

        $response->assertJsonCount($count, 'data');
    }

    /** @test */
    public function user_can_show(): void
    {
        $category = Category::factory()->create();

        $response = $this->impersonateToken(CredentialEntity::create(), 'auth0-api')
            ->getJson(route('api.categories.show', ['category' => $category->id]));
        $response->assertOk();

        $response->assertJson([
            'id' => $category->id,
            'name' => $category->name,
            'image' => $category->image
        ]);
    }

    /** @test */
    public function user_can_create(): void
    {
        $category = Category::factory()->make();

        $response = $this->impersonateToken(CredentialEntity::create(), 'auth0-api')
            ->postJson(route('api.categories.create'), $category->toArray());
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

        $response = $this->impersonateToken(CredentialEntity::create(), 'auth0-api')
            ->putJson(route('api.categories.update', ['category' => $category->id]), $categoryRaw);
        $response->assertOk();

        $this->assertDatabaseMissing('categories', ['name' => $category->name]);
        $this->assertDatabaseHas('categories', ['name' => $newName]);
    }

    /** @test */
    public function user_can_delete(): void
    {
        $category = Category::factory()->create();

        $this->assertDatabaseHas('categories', ['id' => $category->id]);

        $response = $this->impersonateToken(CredentialEntity::create(), 'auth0-api')
            ->deleteJson(route('api.categories.delete', ['category' => $category->id]));

        $response->assertOk();

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    /**
     * @test
     */
    public function user_can_assign_cases_with_category() : void
    {
        $category = Category::factory()->create();
        $cases = Cases::factory(2)->create();

        $response = $this->impersonateToken(CredentialEntity::create(new ImposterUser(['sub' => 'auth0|example'])), 'auth0-api')
            ->postJson(route('api.categories.cases', ['category' => $category->id]), ['cases' => $cases->pluck('id')->toArray()]);

        $response->assertOk();

        $this->assertDatabaseHas('case_category', ['cases_id' => $cases[0]->id, 'category_id' => $category->id]);
        $this->assertDatabaseHas('case_category', ['cases_id' => $cases[1]->id, 'category_id' => $category->id]);
    }

    /**
     * @test
     */
    public function user_can_unassign_cases_with_category() : void
    {
        $category = Category::factory()->create();
        $casesExisted = Cases::factory()->create();
        $imposter = new ImposterUser(['sub' => 'auth0|example']);

        $category->cases()->attach($casesExisted->id, ['user_id' => $imposter->getAuthIdentifier()]);

        $response = $this->impersonateToken(CredentialEntity::create($imposter), 'auth0-api')
            ->postJson(route('api.categories.cases', ['category' => $category->id]), ['cases' => []]);

        $response->assertOk();

        $this->assertDatabaseMissing('case_category', ['cases_id' => $casesExisted->id, 'category_id' => $category->id]);
    }

    /**
     * @test
     */
    public function user_can_sync_cases_with_category() : void
    {
        $category = Category::factory()->create();
        $casesExisted = Cases::factory(2)->create();
        $casesNew = Cases::factory(2)->create();
        $imposter = new ImposterUser(['sub' => 'auth0|example']);

        $category->cases()->attach($casesExisted->pluck('id'), ['user_id' => $imposter->getAuthIdentifier()]);

        $response = $this->impersonateToken(CredentialEntity::create($imposter), 'auth0-api')
            ->postJson(route('api.categories.cases', ['category' => $category->id]), ['cases' => $casesNew->pluck('id')->toArray()]);

        $response->assertOk();

        $this->assertDatabaseMissing('case_category', ['cases_id' => $casesExisted[0]->id, 'category_id' => $category->id]);
        $this->assertDatabaseMissing('case_category', ['cases_id' => $casesExisted[1]->id, 'category_id' => $category->id]);

        $this->assertDatabaseHas('case_category', ['cases_id' => $casesNew[0]->id, 'category_id' => $category->id]);
        $this->assertDatabaseHas('case_category', ['cases_id' => $casesNew[1]->id, 'category_id' => $category->id]);
    }

    /**
     * @test
     */
    public function user_can_assign_existing_cases_when_create_category() : void
    {
        $category = Category::factory()->raw();
        $cases = Cases::factory(2)->create();
        $imposter = new ImposterUser(['sub' => 'auth0|example']);

        $category['cases'] = $cases->pluck('id')->toArray();

        $response = $this->impersonateToken(CredentialEntity::create($imposter), 'auth0-api')
            ->postJson(route('api.categories.create'), $category);

        $response->assertOk();

        $this->assertDatabaseHas('case_category', ['cases_id' => $cases[0]->id, 'category_id' => $response->json('id')]);
        $this->assertDatabaseHas('case_category', ['cases_id' => $cases[1]->id, 'category_id' => $response->json('id')]);
    }

    /**
     * @test
     */
    public function user_can_assign_existing_cases_when_update_category() : void
    {
        $category = Category::factory()->create();
        $cases = Cases::factory(2)->create();
        $imposter = new ImposterUser(['sub' => 'auth0|example']);

        $category['cases'] = $cases->pluck('id')->toArray();

        $response = $this->impersonateToken(CredentialEntity::create($imposter), 'auth0-api')
            ->putJson(route('api.categories.update', ['category' => $category->id]), $category->toArray());

        $response->assertOk();

        $this->assertDatabaseHas('case_category', ['cases_id' => $cases[0]->id, 'category_id' => $response->json('id')]);
        $this->assertDatabaseHas('case_category', ['cases_id' => $cases[1]->id, 'category_id' => $response->json('id')]);
    }
}
