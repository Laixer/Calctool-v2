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

namespace BynqIO\Dynq\ProjectManager\Component;

use BynqIO\Dynq\ProjectManager\Contracts\Component;

/**
 * Class ResultComponent.
 */
class ResultComponent extends BaseComponent implements Component
{
    public function render()
    {
        $tabs = [
            ['name' => 'overview',    'title' => 'Projectresultaat',  'icon' => 'fa-list-ol'],
            ['name' => 'timesheet',   'title' => 'Urenregistratie',   'icon' => 'fa-sort-amount-desc'],
            ['name' => 'profitloss',  'title' => 'Winst / Verlies',    'icon' => 'fa-sort-amount-desc'],
        ];

        return $this->tabLayout($tabs);
    }
}
