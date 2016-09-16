<?php

namespace Calctool\Listeners;

use Calctool\Events\SomeEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Calctool\Events\UserNotification;

use \Mailgun;

class SendNotificationMail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SomeEvent  $event
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
        Mailgun::send('mail.notification', $data, function($message) use ($data) {
            $message->to($data['email'], ucfirst($data['firstname']) . ' ' . ucfirst($data['lastname']));
            $message->subject('CalculatieTool.com - Notificatie: ' . $data['subject']);
            $message->from('info@calculatietool.com', 'CalculatieTool.com');
            $message->replyTo('info@calculatietool.com', 'CalculatieTool.com');
        });
    }
}
