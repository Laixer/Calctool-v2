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

use BynqIO\CalculatieTool\Core\Providers\FlowServiceProvider as ServiceProvider;

class FlowServiceProvider extends ServiceProvider
{
    /**
     * The flow paths provided for the project.
     *
     * @var array
     */
    protected $flow = [
        'BynqIO\CalculatieTool\Core\Flow\CalculationFlow',
        'BynqIO\CalculatieTool\Core\Flow\DirectWorkFlow',
        'BynqIO\CalculatieTool\Core\Flow\QuickInvoiceFlow',
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
