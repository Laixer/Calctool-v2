<?php

namespace BynqIO\CalculatieTool\Events;

use BynqIO\CalculatieTool\Events\Event;
use Illuminate\Queue\SerializesModels;
use \BynqIO\CalculatieTool\Models\User;
use \BynqIO\CalculatieTool\Models\Payment;

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
