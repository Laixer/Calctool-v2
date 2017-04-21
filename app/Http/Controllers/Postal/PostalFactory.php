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

class PostalFactory
{
    /**
     * Postal code.
     *
     * @var string
     */
    private $code;

    /**
     * Postal code.
     *
     * @var string
     */
    private $number;

    /**
     * Other spatial data.
     *
     * @var mixed
     */
    private $other;

    /*
     * Create new postal factory instance.
     */
    public function __construct($code, $number, $other = null)
    {
        $this->code = $code;
        $this->number = $number;
        $this->other = $other;
    }

    /**
     * Return postal object for the given
     * locale.
     *
     * @return PostalInterface
     */
    public function getPostal($locale)
    {
        switch ($locale) {
            case 'nl':
                return new NLPostcode($this->code, $this->number);
        }
    }
}
