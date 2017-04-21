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

namespace BynqIO\CalculatieTool\Http\Controllers\Project;

use BynqIO\CalculatieTool\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DetailController extends Controller
{
    public function index(Request $request)
    {
        return view('project.details');
    }

}
