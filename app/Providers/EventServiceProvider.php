<?php

/**
 * Copyright (C) 2017 Bynq.io B.V.
 * All Rights Reserved
 *
 * This file is part of the Dynq project.
 *
 * Content can not be copied and/or distributed without the express
 * permission of the author.
 *
 * @package  Dynq
 * @author   Yorick de Wid <y.dewid@calculatietool.com>
 */

namespace BynqIO\Dynq\Providers;

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
        'BynqIO\Dynq\Events\UserNotification' => [
            'BynqIO\Dynq\Listeners\SendNotificationMail',
        ],
        'BynqIO\Dynq\Events\UserSignup' => [
            'BynqIO\Dynq\Listeners\UserSignup',
        ],
        'BynqIO\Dynq\Events\UserPaymentSuccess' => [
            'BynqIO\Dynq\Listeners\PaymentSuccess',
        ],
        'BynqIO\Dynq\Events\UserSubscriptionCanceled' => [
            'BynqIO\Dynq\Listeners\SubscriptionCanceled',
        ],
        'Illuminate\Auth\Events\Login' => [
            'BynqIO\Dynq\Listeners\LogAuthenticated',
            'BynqIO\Dynq\Listeners\EvolutionUpgrade',
        ],
    ];

}
