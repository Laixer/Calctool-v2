<?php

namespace Calctool\Events;

use Calctool\Events\Event;
use Illuminate\Queue\SerializesModels;
use \Calctool\Models\User;
use \Calctool\Models\Relation;
use \Calctool\Models\Contact;

class UserSignup extends Event
{
    use SerializesModels;

    public $user;
    public $relation;
    public $contact;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, Relation $relation, Contact $contact)
    {
        $this->user = $user;
        $this->relation = $relation;
        $this->contact = $contact;
    }
}
