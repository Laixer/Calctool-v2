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

namespace BynqIO\CalculatieTool\Http\Controllers\Postal;

interface PostalInterface
{
    /**
     * Load the messages for the given locale.
     *
     * @return array
     */
    public function postal();

    /**
     * Fetch validator.
     *
     * @return array
     */
    public static function validator();
}
