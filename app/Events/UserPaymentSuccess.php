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

namespace BynqIO\CalculatieTool\Events;

use BynqIO\CalculatieTool\Events\Event;
use BynqIO\CalculatieTool\Models\User;
use BynqIO\CalculatieTool\Models\Payment;
use Illuminate\Queue\SerializesModels;

class UserPaymentSuccess extends Event
{
    use SerializesModels;

    public $user;
    public $order;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, Payment $order)
    {
        $this->user = $user;
        $this->order = $order;
    }
}
