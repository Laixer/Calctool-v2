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

class InlineController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Inline Controller
    |--------------------------------------------------------------------------
    |
    |
    */

    /**
     * Show the inline page.
     *
     * @return Response
     */
    public function __invoke(Request $request, $page, $data = [])
    {
        $this->validate($request, [
            'package'      => ['required'],
        ]);

        foreach ($request->all() as $key => $param) {
            $data[$key] = $param;
        }

        return view("{$request->get('package')}.$page", $data);
    }

}
