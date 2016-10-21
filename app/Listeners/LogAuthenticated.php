<?php

namespace Calctool\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use \Calctool\Models\Audit;
use \Calctool\Models\User;

use \Cookie;
use \Auth;

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
        if (session()->has('swap_session')) {
            $swapinfo = session()->get('swap_session');

            $admin_username = User::find($swapinfo['admin_id'])->username;

            /* User is taken over */
            if ($swapinfo['user_id'] == Auth::id()) {
                Audit::CreateEvent('auth.swap.session.succces', 'Session takeover by admin: ' . $admin_username);
            } else {
                session()->forget('swap_session');
            }
        } else {
            $event->user->login_count++;
            $event->user->save();
            Audit::CreateEvent('auth.login.succces', 'Login with: ' . \Calctool::remoteAgent(), $event->user->id);
        }
    }
}
