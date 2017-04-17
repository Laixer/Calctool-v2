<?php

/**
 * Copyright (C) 2017 Bynq.io B.V.
 * All Rights Reserved
 *
 * This file is part of the BynqIO\CalculatieTool.com.
 *
 * Content can not be copied and/or distributed without the express
 * permission of the author.
 *
 * @package  CalculatieTool
 * @author   Yorick de Wid <y.dewid@calculatietool.com>
 */

namespace BynqIO\CalculatieTool\Http\Controllers;

use BynqIO\CalculatieTool\Models\Project;
use BynqIO\CalculatieTool\Models\SysMessage;

use Auth;
use DB;

class DashboardController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Dashboard Controller
    |--------------------------------------------------------------------------
    |
    |
    */

    /**
     * Instantiate the dashboard controller.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('payzone');

        //
    }

    /**
     * Update user status to set user online.
     *
     * @return void
     */
    protected function setUserOnline()
    {
        if (!session()->has('swap_session')) {
            Auth::user()->online_at = \DB::raw('NOW()');
            Auth::user()->save();
            DB::table('sessions')->where('user_id', Auth::id())->update(['instance' => gethostname()]);
        }
    }

    /**
     * Get the welcome message according to the
     * current time of day.
     *
     * @return void
     */
    protected function welcomeMessage()
    {
        $time = date("H");

        if ($time >= "6" && $time < "12") {
            return __('core.welcome.morning');
        } else if ($time >= "12" && $time < "17") {
            return __('core.welcome.afternoon');
        } else if ($time >= "17") {
            return __('core.welcome.evening');
        } else if ($time >= "0") {
            return __('core.welcome.night');
        }
    }

    /**
     * Show the dashboard.
     *
     * @return Response
     */
    public function __invoke()
    {
        if (Auth::user()->isSystem()) {
            return redirect('/admin');
        }

        $this->setUserOnline();

        return view('base.dashboard', [
            'welcomeMessage'  => $this->welcomeMessage(),
            'projectCount'    => Project::where('user_id', Auth::id())->count(),
            'systemMessage'   => SysMessage::where('active', true)->orderBy('created_at', 'desc')->first(),
        ]);
    }

}
