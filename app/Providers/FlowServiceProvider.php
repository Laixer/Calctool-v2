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

namespace BynqIO\CalculatieTool\Providers;

use BynqIO\CalculatieTool\ProjectManager\Providers\FlowServiceProvider as ServiceProvider;

class FlowServiceProvider extends ServiceProvider
{
    /**
     * The flow paths provided for the project.
     *
     * @var array
     */
    protected $flow = [
        'BynqIO\CalculatieTool\ProjectManager\Flow\CalculationFlow',
        'BynqIO\CalculatieTool\ProjectManager\Flow\DirectWorkFlow',
        'BynqIO\CalculatieTool\ProjectManager\Flow\QuickInvoiceFlow',
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
