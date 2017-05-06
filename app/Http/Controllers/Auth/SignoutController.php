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

namespace BynqIO\Dynq\Http\Controllers\Auth;

use BynqIO\Dynq\Models\Audit;
use BynqIO\Dynq\Http\Controllers\Controller;

use Auth;

class SignoutController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Signout Controller
    |--------------------------------------------------------------------------
    |
    |
    */

    /**
     * Instantiate a new signout controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        //
    }

    /**
     * Destroy user session.
     *
     * @return Route
     */
    public function __invoke()
    {
        Audit::CreateEvent('auth.logout.success', 'User destroyed current session');
        
        Auth::logout();
        
        return redirect()->route('signin');
    }

}
