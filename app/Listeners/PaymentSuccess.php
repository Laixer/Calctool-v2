<?php

/**
 * Copyright (C) 2017 Bynq.io B.V.
 * All Rights Reserved
 *
 * This file is part of the BynqIO\CalculatieTool.com.
 *
 * Content can not be copied and/or distributed without the express
 * permission of the author.
 *
 * @package  CalculatieTool
 * @author   Yorick de Wid <y.dewid@calculatietool.com>
 */

namespace BynqIO\CalculatieTool\Listeners;

use BynqIO\CalculatieTool\Events\UserPaymentSuccess as UserPaymentSuccessEvent;
use BynqIO\CalculatieTool\Jobs\CreatePaymentInvoice;
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
