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
 * Class QuotationComponent.
 */
class QuotationComponent extends BaseComponent implements Component
{
    public function render()
    {
        return view("component.{$this->component}");
    }
}
