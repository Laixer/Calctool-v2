<?php

namespace CalculatieTool\Providers;

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
        'CalculatieTool\Events\UserNotification' => [
            'CalculatieTool\Listeners\SendNotificationMail',
        ],
        'CalculatieTool\Events\UserSignup' => [
            'CalculatieTool\Listeners\SendActivationMail',
            'CalculatieTool\Listeners\InformAdminOfNewUser',
        ],
        'Illuminate\Auth\Events\Login' => [
            'CalculatieTool\Listeners\LogAuthenticated',
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
