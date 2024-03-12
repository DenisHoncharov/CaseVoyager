<?php

namespace Tests\Feature\Models;

use App\Models\Cases;
use App\Models\Item;
use App\Models\OpenCaseResult;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OpenCaseResultTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_open_case_result_can_have_an_user(): void
    {
        $user = User::factory()->create();
        $openCaseResult = OpenCaseResult::factory()->create([
            'user_id' => $user->id
        ]);

        $this->assertEquals($user->id, $openCaseResult->userOpenedCase->id);
    }

    /** @test */
    public function an_open_case_result_can_have_an_case(): void
    {
        $openedCase = Cases::factory()->create();
        $openCaseResult = OpenCaseResult::factory()->create([
            'opened_case_id' => $openedCase->id
        ]);

        $this->assertEquals($openedCase->id, $openCaseResult->openedCase->id);
    }

    /** @test */
    public function an_open_case_result_can_have_an_item(): void
    {
        $item = Item::factory()->create();
        $openCaseResult = OpenCaseResult::factory()->create([
            'item_id' => $item->id
        ]);

        $this->assertEquals($item->id, $openCaseResult->item->id);
    }
}
