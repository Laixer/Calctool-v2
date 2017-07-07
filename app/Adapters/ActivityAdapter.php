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

use BynqIO\Dynq\Models\Tax;
use BynqIO\Dynq\Models\Activity;
use BynqIO\Dynq\Models\FavoriteActivity;

class ActivityAdapter
{
    private $activity;

    public function __construct($activity)
    {
        $this->activity = $activity;
    }

    public function getName()
    {
        return $this->activity->activity_name;
    }

    public function setName($name)
    {
        $this->activity->activity_name = $name;
    }

    public function getNote()
    {
        return $this->activity->note;
    }

    public function setNote($note)
    {
        $this->activity->note = $note;
    }

    public function getLaborTax()
    {
        return Tax::findOrFail($this->activity->tax_labor_id);
    }

    public function setLaborTax(Tax $tax)
    {
        return $this->activity->tax_labor_id = $tax->id;
    }

    public function getMaterialTax()
    {
        return Tax::findOrFail($this->activity->tax_material_id);
    }

    public function setMaterialTax(Tax $tax)
    {
        return $this->activity->tax_material_id = $tax->id;
    }

    public function getOtherTax()
    {
        return Tax::findOrFail($this->activity->tax_equipment_id);
    }

    public function setOtherTax(Tax $tax)
    {
        return $this->activity->tax_equipment_id = $tax->id;
    }

    public function setParent($parent)
    {
        if ($this->activity instanceof FavoriteActivity) {
            $this->activity->user_id = $parent->id;
        } else if ($this->activity instanceof Activity) {
            $this->activity->chapter_id = $parent->id;
        }
    }

    public function getActivity()
    {
        return $this->activity;
    }
}
