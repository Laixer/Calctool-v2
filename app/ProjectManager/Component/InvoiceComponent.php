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
 * Class InvoiceComponent.
 */
class InvoiceComponent extends BaseComponent implements Component
{
    public function render()
    {
        $offer = $this->project->quotations()->orderBy('created_at', 'desc')->first();
        return $this->blockLayout(['name' => 'overview', 'offer' => $offer]);
    }
}
