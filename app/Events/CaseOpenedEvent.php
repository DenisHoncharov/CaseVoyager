<?php

namespace App\Events;

use App\Models\Cases;
use App\Models\Item;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CaseOpenedEvent
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Cases $openedCase, public Item $receivedItem)
    {
        //
    }
}
