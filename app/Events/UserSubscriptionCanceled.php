<?php

namespace CalculatieTool\Events;

use CalculatieTool\Events\Event;
use Illuminate\Queue\SerializesModels;
use \CalculatieTool\Models\User;

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
