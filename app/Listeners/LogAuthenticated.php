<?php

namespace Calctool\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use \Calctool\Models\Audit;

class LogAuthenticated
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
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $event->user->login_count++;
        $event->user->save();
        Audit::CreateEvent('auth.login.succces', 'Login with: ' . \Calctool::remoteAgent(), $event->user->id);
    }
}
