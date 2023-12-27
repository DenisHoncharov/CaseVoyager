<?php

namespace App\Listeners;

use App\Events\UpdateUserBalanceEvent;

class UpdateUserBalanceListener
{
    /**
     * Handle the event.
     */
    public function handle(UpdateUserBalanceEvent $event): void
    {
        $event->user->update([
            'balance' => $event->user->balance + round($event->amount, 2)
        ]);
    }
}
