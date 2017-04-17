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

namespace BynqIO\CalculatieTool\Listeners;

use BynqIO\CalculatieTool\Events\UserNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Mail;

class SendNotificationMail
{
    /**
     * Handle the event.
     *
     * @param  UserNotification  $event
     * @return void
     */
    public function handle(UserNotification $event)
    {
        $data = array(
            'email' => $event->user->email,
            'firstname' => $event->user->firstname,
            'lastname' => $event->user->lastname,
            'subject' => $event->subject,
            'body' => $event->text
        );
        Mail::send('mail.notification', $data, function($message) use ($data) {
            $message->to($data['email'], ucfirst($data['firstname']) . ' ' . ucfirst($data['lastname']));
            $message->subject('BynqIO\CalculatieTool.com - Notificatie: ' . $data['subject']);
            $message->from('info@calculatietool.com', 'BynqIO\CalculatieTool.com');
            $message->replyTo('info@calculatietool.com', 'BynqIO\CalculatieTool.com');
        });
    }
}
