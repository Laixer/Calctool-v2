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

class OtherAdapter
{
    private $other;

    public function __construct($other)
    {
        $this->other = $other;
    }

    public function getName()
    {
        return $this->other->equipment_name;
    }

    public function setName($name)
    {
        $this->other->equipment_name = $name;
    }

    public function getUnit()
    {
        return $this->other->unit;
    }

    public function setUnit($unit)
    {
        $this->other->unit = $unit;
    }

    public function getRate()
    {
        return $this->other->rate;
    }

    public function setRate($rate)
    {
        $this->other->rate = $rate;
    }

    public function getAmount()
    {
        return $this->other->amount;
    }

    public function setAmount($amount)
    {
        $this->other->amount = $amount;
    }

    public function setParent($parent)
    {
        $this->other->activity_id = $parent->id;
    }

    public function getOther()
    {
        return $this->other;
    }
}
