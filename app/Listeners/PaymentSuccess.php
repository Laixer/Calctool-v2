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

use BynqIO\Dynq\Events\UserPaymentSuccess as UserPaymentSuccessEvent;
use BynqIO\Dynq\Jobs\CreatePaymentInvoice;
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
