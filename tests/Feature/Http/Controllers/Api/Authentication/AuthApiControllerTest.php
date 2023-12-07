<?php

namespace Tests\Feature\Http\Controllers\Api\Authentication;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthApiControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function user_can_login_with_correct_credentials(): void
    {
        $userPwd = 'password';

        $user = User::factory()->create([
            'password' => bcrypt($userPwd)
        ]);

        $response = $this->actingAs($user)->post(route('api.login'), [
            'email' => $user->email,
            'password' => $userPwd
        ]);

        $response->assertOk();
    }

    /**
     * @test
     */
    public function user_can_not_login_with_in_correct_credentials(): void
    {
        $userPwd = 'password';

        $user = User::factory()->create([
            'password' => bcrypt($userPwd)
        ]);

        $response = $this->actingAs($user)->post(route('api.login'), [
            'email' => $user->email,
            'password' => 'random_password'
        ]);

        $response->assertStatus(403);
    }
}
