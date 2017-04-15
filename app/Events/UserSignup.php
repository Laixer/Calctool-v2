<?php

namespace BynqIO\CalculatieTool\Events;

use BynqIO\CalculatieTool\Events\Event;
use Illuminate\Queue\SerializesModels;
use \BynqIO\CalculatieTool\Models\User;
use \BynqIO\CalculatieTool\Models\Relation;
use \BynqIO\CalculatieTool\Models\Contact;

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
