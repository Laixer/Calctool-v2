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

namespace BynqIO\Dynq\Events;

use BynqIO\Dynq\Events\Event;
use BynqIO\Dynq\Models\User;
use BynqIO\Dynq\Models\Payment;
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
