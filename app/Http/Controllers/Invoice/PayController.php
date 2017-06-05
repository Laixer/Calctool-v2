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

namespace BynqIO\Dynq\Http\Controllers\Invoice;

use Illuminate\Http\Request;
use BynqIO\Dynq\Models\Invoice;
use BynqIO\Dynq\Http\Controllers\Controller;

class PayController extends Controller
{
    public function __invoke(Request $request)
    {
        $this->validate($request, [
            'id'        => ['required', 'integer'],
        ]);

        $invoice = Invoice::findOrFail($request->get('id'));
        $invoice->payment_date = date('Y-m-d');
        $invoice->save();

        return back()->with(['success' => 'Factuur betaald']);
    }

}
