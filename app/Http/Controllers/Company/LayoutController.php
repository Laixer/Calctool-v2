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

namespace BynqIO\Dynq\Http\Controllers\Company;

use BynqIO\Dynq\Models\Relation;
use BynqIO\Dynq\Http\Middleware\RequireNoCompany;
use BynqIO\Dynq\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LayoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('reqcompany')->except('setupCompany');
        $this->middleware(RequireNoCompany::class)->only('setupCompany');

        //
    }

    public function details(Request $request)
    {
        return view('company.details', ['relation' => Relation::findOrFail($request->user()->self_id)]);
    }

    public function setupCompany()
    {
        return view('company.setupcompany');
    }

    public function contacts(Request $request)
    {
        return view('company.contacts', ['relation' => Relation::findOrFail($request->user()->self_id)]);
    }

    public function financial(Request $request)
    {
        return view('company.financial', ['relation' => Relation::findOrFail($request->user()->self_id)]);
    }

    public function logo(Request $request)
    {
        return view('company.logo', ['relation' => Relation::findOrFail($request->user()->self_id)]);
    }

    public function preferences(Request $request)
    {
        return view('company.preferences',[
            'relation' => Relation::findOrFail($request->user()->self_id),
            'user' => $request->user(),
        ]);
    }
}
