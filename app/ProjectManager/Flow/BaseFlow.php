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

namespace BynqIO\CalculatieTool\ProjectManager\Flow;

use Closure;

class BaseFlow
{
    protected $namespace = 'BynqIO\CalculatieTool\ProjectManager\Component';
    protected $currentStep;

    protected function bind($name, $func)
    {
        app()->bind($name, $this->namespace . '\\'. $func);
    }

    public function __construct() {
        $this->map();
    }

    public function __toString() {
        return $this->name();
    }
}
