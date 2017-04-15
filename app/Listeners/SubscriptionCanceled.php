<?php

namespace BynqIO\CalculatieTool\Listeners;

use BynqIO\CalculatieTool\Events\UserSubscriptionCanceled as SubscriptionCanceledEvent;
use BynqIO\CalculatieTool\Jobs\SendNewUserMail;
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
