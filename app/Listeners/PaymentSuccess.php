<?php

namespace CalculatieTool\Listeners;

use CalculatieTool\Events\UserPaymentSuccess as UserPaymentSuccessEvent;
use CalculatieTool\Jobs\CreatePaymentInvoice;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PaymentSuccess
{
    /**
     * Handle the event.
     *
     * @param  UserSignup  $event
     * @return void
     */
    public function handle(UserPaymentSuccessEvent $event)
    {
        dispatch(new CreatePaymentInvoice($event->user, $event->order));
    }
}
