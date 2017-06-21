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

use BynqIO\Dynq\Events\UserSignup as UserSignupEvent;
use BynqIO\Dynq\Jobs\SendActivationMail;
use BynqIO\Dynq\Jobs\SendNewUserMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserSignup
{
    /**
     * Handle the event.
     *
     * @param  UserSignup  $event
     * @return void
     */
    public function handle(UserSignupEvent $event)
    {
        dispatch(new SendActivationMail($event->user));

        /* In production notify administration of new user */
        if (!config('app.debug')) {
           dispatch(new SendNewUserMail($event->user, $event->relation, $event->contact));
        }
    }
}
