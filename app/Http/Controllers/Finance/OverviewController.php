<?php

/**
 * Copyright (C) 2017 Bynq.io B.V.
 * All Rights Reserved
 *
 * This file is part of the BynqIO\CalculatieTool.com.
 *
 * Content can not be copied and/or distributed without the express
 * permission of the author.
 */

namespace BynqIO\CalculatieTool\Http\Controllers\Finance;

use BynqIO\CalculatieTool\Http\Controllers\Controller;

class OverviewController extends Controller
{
    /**
     * Display finance overview page.
     * GET /finance/overview
     *
     * @return Response
     */
    public function overview()
    {
        return view('finance.overview');
    }
}
