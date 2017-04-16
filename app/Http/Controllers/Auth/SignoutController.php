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

namespace BynqIO\CalculatieTool\Http\Controllers\Auth;

use BynqIO\CalculatieTool\Models\Audit;
use BynqIO\CalculatieTool\Http\Controllers\Controller;

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
