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

namespace BynqIO\CalculatieTool\ProjectManager\Flow;

use BynqIO\CalculatieTool\ProjectManager\Contracts\Flow;

class CalculationFlow extends BaseFlow implements Flow
{
    protected $default = 'details';

    /**
     * Define the components for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->bind('details',     'DetailComponent');
        $this->bind('calculation', 'CalculationComponent');
        $this->bind('quotations',  'QuotationComponent');
        $this->bind('quotation/new',  'QuotationComponent');
        $this->bind('estimate',    'EstimateComponent');
        $this->bind('less',        'LessComponent');
        $this->bind('more',        'MoreComponent');
        $this->bind('invoices',    'InvoiceComponent');
        $this->bind('result',      'ResultComponent');
    }

}
