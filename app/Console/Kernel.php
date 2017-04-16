<?php

namespace BynqIO\CalculatieTool\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use \BynqIO\CalculatieTool\Models\Project;
use \BynqIO\CalculatieTool\Models\Contact;
use \BynqIO\CalculatieTool\Models\Offer;
use \BynqIO\CalculatieTool\Models\Invoice;
use \BynqIO\CalculatieTool\Models\User;
use \BynqIO\CalculatieTool\Models\UserGroup;
use \BynqIO\CalculatieTool\Models\MessageBox;
use \BynqIO\CalculatieTool\Models\Payment;

use \Mail;
use \Newsletter;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \BynqIO\CalculatieTool\Console\Commands\DropHard::class,
        \BynqIO\CalculatieTool\Console\Commands\MaterialImport::class,
        \BynqIO\CalculatieTool\Console\Commands\StorageClear::class,
        \BynqIO\CalculatieTool\Console\Commands\SessionClear::class,
        \BynqIO\CalculatieTool\Console\Commands\OauthClear::class,
        \BynqIO\CalculatieTool\Console\Commands\AdminReset::class,
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
                        $message->subject('BynqIO\CalculatieTool.com - Vordering');
                        $message->from('info@calculatietool.com', 'BynqIO\CalculatieTool.com');
                        $message->replyTo('info@calculatietool.com', 'BynqIO\CalculatieTool.com');
                    });

                    $message = new MessageBox;
                    $message->subject = 'Factuur over betalingsdatum';
                    $message->message = 'Een vordering voor '.$project->project_name.' is verzonden naar '.$contact_client->getFormalName().'. De BynqIO\CalculatieTool.com neemt nu geen verdere stappen meer voor afhandeling van deze factuur.';
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
                        $message->subject('BynqIO\CalculatieTool.com - Tweede betalingsherinnering');
                        $message->from('info@calculatietool.com', 'BynqIO\CalculatieTool.com');
                        $message->replyTo('info@calculatietool.com', 'BynqIO\CalculatieTool.com');
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
                        $message->subject('BynqIO\CalculatieTool.com - Betalingsherinnering');
                        $message->from('info@calculatietool.com', 'BynqIO\CalculatieTool.com');
                        $message->replyTo('info@calculatietool.com', 'BynqIO\CalculatieTool.com');
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
                        $message->subject('BynqIO\CalculatieTool.com - Account verlengen');
                        $message->from('info@calculatietool.com', 'BynqIO\CalculatieTool.com');
                        $message->replyTo('info@calculatietool.com', 'BynqIO\CalculatieTool.com');
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
