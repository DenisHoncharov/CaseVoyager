<?php

namespace App\Listeners;

use App\Events\RequestItemEvent;
use App\Models\UserInventory;

class RequestItemListener
{
    /**
     * Handle the event.
     */
    public function handle(RequestItemEvent $event): void
    {
        UserInventory::whereIn('id', $event->requestedItemsIds)->update([
            'is_requested' => $event->isItemRequested,
        ]);
    }
}
