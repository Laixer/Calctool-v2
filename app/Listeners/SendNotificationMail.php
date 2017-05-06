<?php

/**
 * Copyright (C) 2017 Bynq.io B.V.
 * All Rights Reserved
 *
 * This file is part of the Dynq project.
 *
 * Content can not be copied and/or distributed without the express
 * permission of the author.
 *
 * @package  Dynq
 * @author   Yorick de Wid <y.dewid@calculatietool.com>
 */

namespace BynqIO\Dynq\Listeners;

use BynqIO\Dynq\Events\UserNotification;
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
            $message->subject(config('app.name') . ' - Notificatie: ' . $data['subject']);
            $message->from(APP_EMAIL);
        });
    }
}
