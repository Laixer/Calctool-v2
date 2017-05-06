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

class QuickInvoiceFlow extends BaseFlow implements Flow
{
    protected $steps = [
        'DetailsComponent',
        'QuickRulesComponent',
        'QuotationsComponent',
        'InvoicesComponent',
        'ResultComponent',
    ];

    /**
     * Define the components for the application.
     *
     * @return void
     */
    public function map()
    {
        // Component::bind('detail', 'DetailsComponent');
    }

}
