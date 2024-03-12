<?php

namespace App\Listeners;

use App\Events\CaseOpenedEvent;
use App\Models\OpenCaseResult;

class CaseOpenedListener
{
    /**
     * Handle the event.
     */
    public function handle(CaseOpenedEvent $event): void
    {
        OpenCaseResult::create([
            'user_id' => app()->make('getUserFromDBUsingAuth0')?->id,
            'opened_case_id' => $event->openedCase->id,
            'item_id' => $event->receivedItem->id,
        ]);
    }
}
