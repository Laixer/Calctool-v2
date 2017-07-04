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

namespace BynqIO\Dynq\ProjectManager\Flow;

use BynqIO\Dynq\ProjectManager\Contracts\Flow;

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
        $this->bind('details',              'DetailComponent');
        $this->bind('printoverview',        'ProjectReportComponent');
        $this->bind('packingslip',          'PackListReportComponent');
        $this->bind('paper',                'EmptyReportComponent');
        $this->bind('calculation',          'CalculationComponent');
        $this->bind('quotations',           'QuotationComponent');
        $this->bind('quotations/new',       'QuotationNewComponent');
        $this->bind('quotations/detail',    'QuotationDetailComponent');
        $this->bind('quotations/report',    'QuotationReportComponent');
        $this->bind('estimate',             'EstimateComponent');
        $this->bind('less',                 'LessComponent');
        $this->bind('more',                 'MoreComponent');
        $this->bind('favorite',             'FavoriteComponent');
        $this->bind('invoices',             'InvoiceComponent');
        $this->bind('invoices/detail',      'InvoiceDetailComponent');
        $this->bind('invoices/report',      'InvoiceReportComponent');
        $this->bind('result',               'ResultComponent');
    }

}
