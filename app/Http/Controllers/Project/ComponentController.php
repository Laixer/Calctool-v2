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

namespace BynqIO\CalculatieTool\Http\Controllers\Project;

use BynqIO\CalculatieTool\Http\Controllers\Controller;
// use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;//?

class ComponentController extends Controller
{
    public function index($id, $name, $module)
    {
        $classInstance = 'BynqIO\\CalculatieTool\\Core\\Component\\' . studly_case($module) . 'Component';
        if (!class_exists($classInstance)) {
            abort(404);
        }

        try {
            $component = new $classInstance($id);
        } catch (Exception $e) { //TODO
            return "Kaze";
        }

        return $component->view();
    }

}
