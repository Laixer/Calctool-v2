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

namespace BynqIO\Dynq\Console;

use BynqIO\Dynq\Models\Project;
use BynqIO\Dynq\Models\Contact;
use BynqIO\Dynq\Models\Offer;
use BynqIO\Dynq\Models\Invoice;
use BynqIO\Dynq\Models\User;
use BynqIO\Dynq\Models\UserGroup;
use BynqIO\Dynq\Models\MessageBox;
use BynqIO\Dynq\Models\Payment;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use Mail;
use Newsletter;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \BynqIO\Dynq\Console\Commands\DropHard::class,
        \BynqIO\Dynq\Console\Commands\MaterialImport::class,
        \BynqIO\Dynq\Console\Commands\StorageClear::class,
        \BynqIO\Dynq\Console\Commands\SessionClear::class,
        \BynqIO\Dynq\Console\Commands\OauthClear::class,
        \BynqIO\Dynq\Console\Commands\AdminReset::class,
        \BynqIO\Dynq\Console\Commands\Upgrade::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function() {
            foreach(Invoice::whereNotNull('bill_date')->whereNull('payment_date')->get() as $invoice) {
                $offer = Offer::find($invoice->offer_id);
                $project = Project::find($offer->project_id);
                $user = User::find($project->user_id);

                if (!$project->pref_email_reminder)
                    continue;

                $contact_client = Contact::find($invoice->to_contact_id);
                $contact_user = Contact::find($invoice->from_contact_id);

                if ($invoice->isExpiredDemand()) {
                    $data = array(
                        'email' => $contact_client->email,
                        'project_name' => $project->project_name,
                        'client' => $contact_client->getFormalName(),
                        'pref_email_invoice_demand' => $user->pref_email_invoice_demand,
                        'user' => $contact_user->getFormalName()
                    );
                    Mail::send('mail.invoice_demand', $data, function($message) use ($data) {
                        $message->to($data['email'], strtolower(trim($data['client'])));
                        $message->subject(config('app.name') . ' - Vordering');
                        $message->from(APP_EMAIL);
                    });

                    $message = new MessageBox;
                    $message->subject = 'Factuur over betalingsdatum';
                    $message->message = 'Een vordering voor '.$project->project_name.' is verzonden naar '.$contact_client->getFormalName().'. De CalculatieTool.com neemt nu geen verdere stappen meer voor afhandeling van deze factuur.';
                    $message->from_user = User::where('username', 'system')->first()['id'];
                    $message->user_id = $project->user_id;

                    $message->save();

                } else if ($invoice->isExpiredSecond()) {
                    $data = array(
                        'email' => $contact_client->email,
                        'project_name' => $project->project_name,
                        'client' => $contact_client->getFormalName(),
                        'pref_email_invoice_last_reminder' => $user->pref_email_invoice_last_reminder,
                        'user' => $contact_user->getFormalName()
                    );
                    Mail::send('mail.invoice_last_reminder', $data, function($message) use ($data) {
                        $message->to($data['email'], strtolower(trim($data['client'])));
                        $message->subject(config('app.name') . ' - Tweede betalingsherinnering');
                        $message->from(APP_EMAIL);
                    });

                    $message = new MessageBox;
                    $message->subject = 'Factuur over betalingsdatum';
                    $message->message = 'Een 2e betalingsherinnering voor '.$project->project_name.' is verzonden naar '.$contact_client->getFormalName().'.';
                    $message->from_user = User::where('username', 'system')->first()['id'];
                    $message->user_id = $project->user_id;

                    $message->save();

                } else if ($invoice->isExpiredFirst()) {
                    $data = array(
                        'email' => $contact_client->email,
                        'project_name' => $project->project_name,
                        'client' => $contact_client->getFormalName(),
                        'pref_email_invoice_first_reminder' => $user->pref_email_invoice_first_reminder,
                        'user' => $contact_user->getFormalName()
                    );
                    Mail::send('mail.invoice_first_reminder', $data, function($message) use ($data) {
                        $message->to($data['email'], strtolower(trim($data['client'])));
                        $message->subject(config('app.name') . ' - Betalingsherinnering');
                        $message->from(APP_EMAIL);
                    });

                    $message = new MessageBox;
                    $message->subject = 'Factuur over betalingsdatum';
                    $message->message = 'Een 1e betalingsherinnering voor '.$project->project_name.' is verzonden naar '.$contact_client->getFormalName().'.';
                    $message->from_user = User::where('username', 'system')->first()['id'];
                    $message->user_id = $project->user_id;

                    $message->save();
                }
            }
 
        })->dailyAt('06:30');

        $schedule->call(function() {
            foreach (User::where('active',true)->whereNotNull('confirmed_mail')->whereNull('banned')->whereNull('payment_subscription_id')->get() as $user) {
                if ($user->isAlmostDue()) {
                    if (UserGroup::find($user->user_group)->subscription_amount == 0)
                        continue;

                    $data = array(
                        'email' => $user->email,
                        'firstname' => $user->firstname,
                        'lastname' => $user->lastname
                    );

                    Mail::send('mail.due', $data, function($message) use ($data) {
                        $message->to($data['email'], ucfirst($data['firstname']) . ' ' . ucfirst($data['lastname']));
                        $message->subject(config('app.name') . ' - Account verlengen');
                        $message->from(APP_EMAIL);
                    });
                }
            }
        })->daily();

        $schedule->call(function() {
            foreach (User::where('active',true)->whereNull('confirmed_mail')->get() as $user) {
                if ($user->canArchive()) {
                    $user->active = false;
                    $user->save();
                }
            }

        })->daily();

        $schedule->call(function() {
            foreach (Payment::select('user_id')->where('status','paid')->groupBy('user_id')->get() as $payment) {
                $user = User::find($payment->user_id);
                if (!$user)
                    continue;
                if (!$user->active)
                    continue;

                /* Paying */
                Newsletter::subscribe($user->email, [
                    'FNAME' => $user->firstname,
                    'LNAME' => $user->lastname
                ], 'paying');

                /* Registration */
                Newsletter::unsubscribe($user->email);
            }
        })->daily();

        $schedule->call(function() {
            foreach (User::where('active',true)->whereRaw("\"confirmed_mail\" > NOW() - '1 week'::INTERVAL")->get() as $user) {

                /* Paying */
                Newsletter::subscribe($user->email, [
                    'FNAME' => $user->firstname,
                    'LNAME' => $user->lastname
                ]);

                /* Registration */
                Newsletter::unsubscribe($user->email, 'noaccount');
            }
        })->daily();

        $schedule->command('oauth:clear')->daily();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
