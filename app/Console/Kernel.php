<?php

namespace Calctool\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use \Calctool\Models\Project;
use \Calctool\Models\Contact;
use \Calctool\Models\Offer;
use \Calctool\Models\Invoice;
use \Calctool\Models\User;
use \Calctool\Models\MessageBox;

use \Mailgun;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \Calctool\Console\Commands\Inspire::class,
        \Calctool\Console\Commands\DropHard::class,
        \Calctool\Console\Commands\MaterialImport::class,
        \Calctool\Console\Commands\StorageClear::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

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
                    Mailgun::send('mail.invoice_demand', $data, function($message) use ($data) {
                        $message->to($data['email'], strtolower(trim($data['client'])));
                        $message->subject('CalculatieTool.com - Vordering');
                        $message->from('info@calculatietool.com', 'CalculatieTool.com');
                        $message->replyTo('info@calculatietool.com', 'CalculatieTool.com');
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
                    Mailgun::send('mail.invoice_last_reminder', $data, function($message) use ($data) {
                        $message->to($data['email'], strtolower(trim($data['client'])));
                        $message->subject('CalculatieTool.com - Tweede betalingsherinnering');
                        $message->from('info@calculatietool.com', 'CalculatieTool.com');
                        $message->replyTo('info@calculatietool.com', 'CalculatieTool.com');
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
                    Mailgun::send('mail.invoice_first_reminder', $data, function($message) use ($data) {
                        $message->to($data['email'], strtolower(trim($data['client'])));
                        $message->subject('CalculatieTool.com - Betalingsherinnering');
                        $message->from('info@calculatietool.com', 'CalculatieTool.com');
                        $message->replyTo('info@calculatietool.com', 'CalculatieTool.com');
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
            foreach (User::where('active',true)->whereNotNull('confirmed_mail')->whereNull('banned')->get() as $user) {
                if ($user->isAlmostDue()) {
                    $data = array(
                        'email' => $user->email,
                        'username' => $user->username,
                    );
                    Mailgun::send('mail.due', $data, function($message) use ($data) {
                        $message->to($data['email'], strtolower(trim($data['username'])));
                        $message->subject('CalculatieTool.com - Account verlengen');
                        $message->from('info@calculatietool.com', 'CalculatieTool.com');
                        $message->replyTo('info@calculatietool.com', 'CalculatieTool.com');
                    });
                }
            }

        })->daily();
    }
}
