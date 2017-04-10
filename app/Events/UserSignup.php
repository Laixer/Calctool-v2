<?php

namespace CalculatieTool\Events;

use CalculatieTool\Events\Event;
use Illuminate\Queue\SerializesModels;
use \CalculatieTool\Models\User;
use \CalculatieTool\Models\Relation;
use \CalculatieTool\Models\Contact;

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
