<?php

namespace Calctool\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Calctool\Events\UserNotification' => [
            'Calctool\Listeners\SendNotificationMail',
        ],
        'Illuminate\Auth\Events\Login' => [
            'Calctool\Listeners\LogAuthenticated',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
