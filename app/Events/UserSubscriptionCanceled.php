<?php

namespace BynqIO\CalculatieTool\Events;

use BynqIO\CalculatieTool\Events\Event;
use Illuminate\Queue\SerializesModels;
use \BynqIO\CalculatieTool\Models\User;

class UserSubscriptionCanceled extends Event
{
    use SerializesModels;

    public $user;
    public $subscription;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, $subscription)
    {
        $this->user = $user;
        $this->subscription = $subscription;
    }
}
