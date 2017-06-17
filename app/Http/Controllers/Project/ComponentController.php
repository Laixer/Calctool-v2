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

namespace BynqIO\Dynq\Http\Controllers\Project;

use BynqIO\Dynq\Http\Controllers\Controller;

use Flow;

/**
 * Class ComponentController.
 */
class ComponentController extends Controller
{
    public function index($id, $name, $component, $subcomponent = null)
    {
        if (!is_null($subcomponent)) {
            $component .= '/' . $subcomponent;
        }

        return Flow::make($id, $component)->response();
    }
}
