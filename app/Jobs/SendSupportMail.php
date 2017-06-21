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

namespace BynqIO\Dynq\Jobs;

use BynqIO\Dynq\Jobs\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Auth;
use Mail;

class SendSupportMail extends Job implements ShouldQueue
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
    public function __construct($name, $email, $subject, $category, $message)
    {
        $this->data = [
            'name' => $name,
            'email' => $email,
            'subject' => $subject,
            'category' => $category,
            'feedback_message' => $message,
            'user' => '-',
            'remote' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unknown',
            'agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'unknown',
        ];

        if (Auth::check()) {
            $this->data['user'] = Auth::user()->username;
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = $this->data;
        Mail::send('mail.support', $data, function($message) use ($data) {
            $message->to(ADMIN_EMAIL);
            $message->bcc($data['email'], $data['name']);
            $message->subject(config('app.name') . ' - Contact formulier');
        });
    }
}
