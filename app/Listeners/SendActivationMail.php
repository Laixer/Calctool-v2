<?php

namespace CalculatieTool\Listeners;

use CalculatieTool\Events\UserSignup;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use \Mail;

class SendActivationMail
{
    /**
     * Handle the event.
     *
     * @param  UserSignup  $event
     * @return void
     */
    public function handle(UserSignup $event)
    {
		$data = array(
			'email' => $event->user->email,
			'token' => $event->user->reset_token,
			'firstname' => $event->user->firstname,
			'lastname' => $event->user->lastname
		);
		Mail::send('mail.confirm', $data, function ($message) use ($data) {
            $message->to($data['email'], ucfirst($data['firstname']) . ' ' . ucfirst($data['lastname']));
			$message->subject('CalculatieTool.com - Account activatie');
			$message->from('info@calculatietool.com', 'CalculatieTool.com');
			$message->replyTo('support@calculatietool.com', 'CalculatieTool.com');
        });
    }
}
