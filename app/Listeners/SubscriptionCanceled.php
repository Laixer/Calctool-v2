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

namespace BynqIO\Dynq\Listeners;

use BynqIO\Dynq\Events\UserSubscriptionCanceled as SubscriptionCanceledEvent;
use BynqIO\Dynq\Jobs\SendNewUserMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubscriptionCanceled
{
    /**
     * Handle the event.
     *
     * @param  UserSignup  $event
     * @return void
     */
    public function handle(SubscriptionCanceledEvent $event)
    {
        /* In production notify administration canceled subscription */
        if (!config('app.debug'))
           dispatch(new SendSubscriptionCanceledMail($event->user, $event->subscription));
    }
}
