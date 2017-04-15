<?php

namespace BynqIO\CalculatieTool\Listeners;

use BynqIO\CalculatieTool\Events\UserSignup as UserSignupEvent;
use BynqIO\CalculatieTool\Jobs\SendActivationMail;
use BynqIO\CalculatieTool\Jobs\SendNewUserMail;
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
        if (!config('app.debug'))
           dispatch(new SendNewUserMail($event->user, $event->relation, $event->contact));
    }
}
