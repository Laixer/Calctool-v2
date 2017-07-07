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

namespace BynqIO\Dynq\Mappers;

use BynqIO\Dynq\Adapters\LaborAdapter;
use BynqIO\Dynq\Adapters\OtherAdapter;
use BynqIO\Dynq\Adapters\MaterialAdapter;
use BynqIO\Dynq\Adapters\ActivityAdapter;

class LayerMapper
{
    public static function mapActivity($activity_new, $activity_copy, $user)
    {
        $new  = new ActivityAdapter($activity_new);
        $copy = new ActivityAdapter($activity_copy);

        $new->setName($copy->getName());
        $new->setNote($copy->getNote());
        $new->setParent($user);

        $new->setLaborTax($copy->getLaborTax());
        $new->setMaterialTax($copy->getMaterialTax());
        $new->setOtherTax($copy->getOtherTax());

        return $new->getActivity();
    }

    public static function mapLabor($labor_new, $labor_copy, $activity)
    {
        $new  = new LaborAdapter($labor_new);
        $copy = new LaborAdapter($labor_copy);

        $new->setRate($copy->getRate());
        $new->setAmount($copy->getAmount());
        $new->setParent($activity);

        return $new->getLabor();
    }

    public static function mapMaterial($material_new, $material_copy, $activity)
    {
        $new  = new MaterialAdapter($material_new);
        $copy = new MaterialAdapter($material_copy);

        $new->setName($copy->getName());
        $new->setUnit($copy->getUnit());
        $new->setRate($copy->getRate());
        $new->setAmount($copy->getAmount());
        $new->setParent($activity);

        return $new->getMaterial();
    }

    public static function mapOther($other_new, $other_copy, $activity)
    {
        $new  = new OtherAdapter($other_new);
        $copy = new OtherAdapter($other_copy);

        $new->setName($copy->getName());
        $new->setUnit($copy->getUnit());
        $new->setRate($copy->getRate());
        $new->setAmount($copy->getAmount());
        $new->setParent($activity);

        return $new->getOther();
    }

}
