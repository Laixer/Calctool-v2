<?php

namespace CalculatieTool\Listeners;

use CalculatieTool\Events\UserSignup;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use \Mail;

class InformAdminOfNewUser
{
    /**
     * Handle the event.
     *
     * @param  UserSignup  $event
     * @return void
     */
    public function handle(UserSignup $event)
    {
		if (config('app.debug'))
            return;

        $data = array(
            'email' => $event->user->email,
            'firstname' => $event->user->firstname,
            'lastname' => $event->user->lastname,
            'company' => $event->relation->company_name,
            'contact_first' => $event->contact->firstname,
            'contact_last'=> $event->contact->lastname
        );
        Mail::send('mail.inform_new_user', $data, function($message) use ($data) {
            $message->to('administratie@calculatietool.com', 'CalculatieTool.com');
            $message->subject('CalculatieTool.com - Account activatie');
            $message->from('info@calculatietool.com', 'CalculatieTool.com');
            $message->replyTo('administratie@calculatietool.com', 'CalculatieTool.com');
        });
    }
}
