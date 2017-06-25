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

namespace BynqIO\Dynq\Listeners;

use Carbon\Carbon;
use BynqIO\Dynq\Models\Audit;
use BynqIO\Dynq\Models\User;
use Illuminate\Auth\Events\Login;

use Cache;

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
        if (session()->has('swap_session')) { /* Admin switches back */
            session()->forget('swap_session');
        } else if (Cache::has('keepsesionstate')) { /* Admin switched into user */
            Cache::forget('keepsesionstate');
        } else {
            $event->user->login_count++;
            $event->user->online_at = Carbon::now();
            $event->user->save();
            Audit::CreateEvent('auth.login.succces', 'Login with: ' . Audit::UserAgent(), $event->user->id);
        }
    }
}
