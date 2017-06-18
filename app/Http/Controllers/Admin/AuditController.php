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

namespace BynqIO\Dynq\Http\Controllers\Admin;

use BynqIO\Dynq\Http\Controllers\Controller;
use BynqIO\Dynq\Models\Audit;
use Illuminate\Http\Request;
// use Illuminate\Support\Collection;

use DB;

class AuditController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Default Home Controller
    |--------------------------------------------------------------------------
    |
    | You may wish to use controllers instead of, or in addition to, Closure
    | based routes. That's great! Here is an example controller method to
    | get you started. To route to this controller, just add the route:
    |
    |	Route::get('/', 'HomeController@showWelcome');
    |
    */

    public function __invoke(Request $request)
    {
        $perPage = 20;
        $page = 1;
        if ($request->has('per_page')) {
            $perPage = (int) $request->get('per_page');
        }
        if ($request->has('page')) {
            $page = (int) $request->get('page');
        }

        $records = Audit::orderBy('created_at','desc')
                            ->offset(($page - 1) * $perPage)
                            ->limit($perPage)
                            ->get();

        return view('admin.audit', compact('records', 'page'));
    }
}
