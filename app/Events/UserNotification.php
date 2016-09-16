<?php

namespace Calctool\Events;

use Calctool\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use \Calctool\Models\User;

class UserNotification extends Event
{
    use SerializesModels;

    public $user;
    public $subject;
    public $text;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, $subject, $text)
    {
        $this->user = $user;
        $this->subject = $subject;
        $this->text = $text;
    }
}
