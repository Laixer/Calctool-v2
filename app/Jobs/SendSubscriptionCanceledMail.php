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

use Mail;

class SendSubscriptionCanceledMail extends Job implements ShouldQueue
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
    public $tries = 1;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $subscription)
    {
        $this->data = [
            'user' => $user->username,
            'subscription' => $subscription,
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
        Mail::send('mail.payment_stopped', $data, function($message) use ($data) {
            $message->to(ADMIN_EMAIL);
            $message->subject(config('app.name') . ' - Automatische incasso gestopt');
            $message->from(APP_EMAIL);
        });
    }
}
