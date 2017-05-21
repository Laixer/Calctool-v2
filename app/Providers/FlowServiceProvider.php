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

namespace BynqIO\Dynq\Providers;

use Illuminate\Support\Facades\Blade;
use BynqIO\Dynq\ProjectManager\Providers\FlowServiceProvider as ServiceProvider;

class FlowServiceProvider extends ServiceProvider
{
    /**
     * The flow paths provided for the project.
     *
     * @var array
     */
    protected $flow = [
        'BynqIO\Dynq\ProjectManager\Flow\CalculationFlow',
        'BynqIO\Dynq\ProjectManager\Flow\DirectWorkFlow',
        'BynqIO\Dynq\ProjectManager\Flow\QuickInvoiceFlow',
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Blade::directive('ifallowed', function ($expression) {
            return "<?php if(isset($expression) && $expression === true): ?>";
        });

        Blade::directive('endifallowed', function () {
            return "<?php endif; ?>";
        });
    }
}
