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

class MaterialAdapter
{
    private $material;

    public function __construct($material)
    {
        $this->material = $material;
    }

    public function getName()
    {
        return $this->material->material_name;
    }

    public function setName($name)
    {
        $this->material->material_name = $name;
    }

    public function getUnit()
    {
        return $this->material->unit;
    }

    public function setUnit($unit)
    {
        $this->material->unit = $unit;
    }

    public function getRate()
    {
        return $this->material->rate;
    }

    public function setRate($rate)
    {
        $this->material->rate = $rate;
    }

    public function getAmount()
    {
        return $this->material->amount;
    }

    public function setAmount($amount)
    {
        $this->material->amount = $amount;
    }

    public function setParent($parent)
    {
        $this->material->activity_id = $parent->id;
    }

    public function getmaterial()
    {
        return $this->material;
    }
}
