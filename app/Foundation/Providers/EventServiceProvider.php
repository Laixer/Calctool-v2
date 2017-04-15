<?php

/**
 * Copyright (C) 2017 Bynq.io B.V.
 * All Rights Reserved
 *
 * This file is part of the BynqIO\CalculatieTool.com.
 *
 * Content can not be copied and/or distributed without the express
 * permission of the author.
 */

namespace BynqIO\CalculatieTool\Foundation\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'BynqIO\CalculatieTool\Events\UserNotification' => [
            'BynqIO\CalculatieTool\Listeners\SendNotificationMail',
        ],
        'BynqIO\CalculatieTool\Events\UserSignup' => [
            'BynqIO\CalculatieTool\Listeners\UserSignup',
        ],
        'BynqIO\CalculatieTool\Events\UserPaymentSuccess' => [
            'BynqIO\CalculatieTool\Listeners\PaymentSuccess',
        ],
        'BynqIO\CalculatieTool\Events\UserSubscriptionCanceled' => [
            'BynqIO\CalculatieTool\Listeners\SubscriptionCanceled',
        ],        
        'Illuminate\Auth\Events\Login' => [
            'BynqIO\CalculatieTool\Listeners\LogAuthenticated',
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
