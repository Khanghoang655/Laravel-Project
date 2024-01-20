<?php

namespace App\Providers;

use App\Events\OrderEvent;
use App\Events\SendVerificationEmail;
use App\Listeners\AdminAuthenticatedListener;
use App\Listeners\MinusQtySeat;
use App\Listeners\SendPaymentSuccessfulEmail;
use App\Listeners\SendVerificationEmailListener;
use Illuminate\Auth\Events\Authenticated;
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
       SendVerificationEmail::class=>[
        SendVerificationEmailListener::class,
       ],
       OrderEvent::class=>[
        MinusQtySeat::class,
        SendPaymentSuccessfulEmail::class
       ]
    //    Authenticated::class => [
    //     AdminAuthenticatedListener::class,
    //    ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}