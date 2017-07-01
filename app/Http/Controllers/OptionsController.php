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

namespace BynqIO\Dynq\Http\Controllers;

use Illuminate\Http\Request;

class OptionsController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Options Controller
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
        //
    }

    /**
     * Show the dashboard.
     *
     * @return Response
     */
    public function __invoke(Request $request)
    {
        if ($request->has('beta')) {
            return redirect('/')->cookie('beta', 1);
        }

        abort(404);
    }

}
