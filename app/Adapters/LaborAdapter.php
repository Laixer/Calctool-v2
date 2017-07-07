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

namespace BynqIO\Dynq\Adapters;

class LaborAdapter
{
    private $labor;

    public function __construct($labor)
    {
        $this->labor = $labor;
    }

    public function getRate()
    {
        return $this->labor->rate;
    }

    public function setRate($rate)
    {
        $this->labor->rate = $rate;
    }

    public function getAmount()
    {
        return $this->labor->amount;
    }

    public function setAmount($amount)
    {
        $this->labor->amount = $amount;
    }

    public function setParent($parent)
    {
        $this->labor->activity_id = $parent->id;
    }

    public function getLabor()
    {
        return $this->labor;
    }
}
