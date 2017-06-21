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

namespace BynqIO\Dynq\Calculus;

use BynqIO\Dynq\Models\EstimateLabor;
use BynqIO\Dynq\Models\EstimateMaterial;
use BynqIO\Dynq\Models\EstimateEquipment;

class EstimateRegister {

    /*Calculation labor*/
    public static function laborTotal($rate, $amount) {
        return $rate * $amount;
    }

    /*Calculation Material*/
    public static function materialTotal($activity) {
        $total = 0;

        $rows = EstimateMaterial::where('activity_id', $activity)->get();
        foreach ($rows as $row)
        {
            if ($row->original) {
                if ($row->isset)
                    $total += self::laborTotal($row->set_rate, $row->set_amount);
                else
                    $total += self::laborTotal($row->rate, $row->amount);
            } else {
                $total += self::laborTotal($row->set_rate, $row->set_amount);
            }
        }

        return $total;
    }

    /*Calculation Material Profit*/
    public static function materialTotalProfit($activity, $profit) {
        $total = self::materialTotal($activity);

        return (1+($profit/100))*$total;
    }

    /*Calculation Equipment*/
    public static function equipmentTotal($activity) {
        $total = 0;

        $rows = EstimateEquipment::where('activity_id', '=', $activity)->get();
        foreach ($rows as $row)
        {
            if ($row->original) {
                if ($row->isset)
                    $total += self::laborTotal($row->set_rate, $row->set_amount);
                else
                    $total += self::laborTotal($row->rate, $row->amount);
            } else {
                $total += self::laborTotal($row->set_rate, $row->set_amount);
            }
        }

        return $total;
    }

    /*Calculation Equipment Profit*/
    public static function equipmentTotalProfit($activity, $profit) {
        $total = self::equipmentTotal($activity);

        return (1+($profit/100))*$total;
    }

    /*Calculation Timesheet*/
    public static function timesheetTotal($activity) {
        $total = 0;

        $rows = EstimateLabor::where('activity_id', $activity)->get();
        foreach ($rows as $row)
        {
            if ($row->original) {
                if ($row->isset)
                    $total += self::laborTotal($row->rate, $row->set_amount);
                else
                    $total += self::laborTotal($row->rate, $row->amount);
            } else {
                $total += self::laborTotal($row->rate, $row->set_amount);
            }
        }

        return $total;
    }
}
