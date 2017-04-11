<?php

namespace CalculatieTool\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use \Mail;

class SendNewUserMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The data object containing the mail info.
     *
     * @var array
     */
    protected $data;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 10;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 2;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $relation, $contact)
    {
        $this->data = [
            'email' => $user->email,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'company' => $relation->company_name,
            'contact_first' => $contact->firstname,
            'contact_last'=> $contact->lastname
        ];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = $this->data;
        Mail::send('mail.inform_new_user', $data, function($message) use ($data) {
            $message->to('administratie@calculatietool.com', 'CalculatieTool.com');
            $message->subject('CalculatieTool.com - Account activatie');
            $message->from('info@calculatietool.com', 'CalculatieTool.com');
            $message->replyTo('administratie@calculatietool.com', 'CalculatieTool.com');
        });
    }
}
