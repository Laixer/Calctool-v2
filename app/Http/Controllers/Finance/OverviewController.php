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

namespace BynqIO\Dynq\Http\Controllers\Finance;

use BynqIO\Dynq\Http\Controllers\Controller;

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
