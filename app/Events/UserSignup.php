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
use BynqIO\CalculatieTool\Models\Relation;
use BynqIO\CalculatieTool\Models\Contact;
use Illuminate\Queue\SerializesModels;

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
