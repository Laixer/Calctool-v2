<?php

namespace BynqIO\CalculatieTool\Events;

use BynqIO\CalculatieTool\Events\Event;
use Illuminate\Queue\SerializesModels;
use \BynqIO\CalculatieTool\Models\User;

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
