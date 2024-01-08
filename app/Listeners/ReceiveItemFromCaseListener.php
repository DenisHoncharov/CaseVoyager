<?php

namespace App\Listeners;

use App\Events\ReceiveItemFromCaseEvent;
use App\Models\OpenCaseResult;

class ReceiveItemFromCaseListener
{
    /**
     * Handle the event.
     */
    public function handle(ReceiveItemFromCaseEvent $event): void
    {
        OpenCaseResult::whereIn('id', $event->openCaseResultIds)
            ->update(['is_received' => true]);
    }
}
