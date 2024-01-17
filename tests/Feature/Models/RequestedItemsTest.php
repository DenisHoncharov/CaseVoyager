<?php

namespace Tests\Unit\Models;

use App\Models\RequestedItems;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RequestedItemsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_list_can_owed_by_user(): void
    {
        $user = User::factory()->create();
        $orderList = RequestedItems::factory()->create([
            'user_id' => $user->id
        ]);

        $this->assertEquals($user->id, $orderList->user->id);
    }
}
