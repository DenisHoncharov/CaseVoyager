<?php

namespace App\Listeners;

use App\Models\User;

class SuccessfulAuth0LoginListener
{
    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        /*
         * TODO: check with FE application if we can get user data from Auth0
         * if possible (save email and other staff from Auth0 to DB)
        */
        User::firstOrCreate([
            'auth0_id' => $event->payload['sub']
        ],
        [
            'email' => $event->payload['email'] ?? '',
            'name' => $event->payload['name'] ?? '',
            'balance' => 0
        ]);
    }
}
