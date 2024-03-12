<?php

namespace App\Providers;

use App\Events\CaseOpenedEvent;
use App\Events\ReceiveItemFromCaseEvent;
use App\Events\RequestItemEvent;
use App\Events\UpdateUserBalanceEvent;
use App\Listeners\CaseOpenedListener;
use App\Listeners\ReceiveItemFromCaseListener;
use App\Listeners\RequestItemListener;
use App\Listeners\SuccessfulAuth0LoginListener;
use App\Listeners\UpdateUserBalanceListener;
use Auth0\Laravel\Events\TokenVerificationSucceeded;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        CaseOpenedEvent::class => [
            CaseOpenedListener::class,
        ],
        ReceiveItemFromCaseEvent::class => [
            ReceiveItemFromCaseListener::class,
        ],
        RequestItemEvent::class => [
            RequestItemListener::class,
        ],
        UpdateUserBalanceEvent::class => [
            UpdateUserBalanceListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Event::listen(TokenVerificationSucceeded::class, [SuccessfulAuth0LoginListener::class, 'handle']);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
