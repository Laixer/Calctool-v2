<?php

namespace BynqIO\Dynq\Calculus;

use BynqIO\Dynq\Models\BlancRow;
use BynqIO\Dynq\Models\Tax;

/*
 * Eindresultaat
 */
class BlancRowsEndresult {

    public static function rowTax1($project) {
        $total = 0;
        $tax_id = Tax::where('tax_rate','=','21')->first()->id;

        foreach (BlancRow::where('project_id','=', $project->id)->where('tax_id', $tax_id)->get() as $row)
        {
            $total += $row->amount;
        }

        return $total;
    }

    public static function rowTax2($project) {
        $total = 0;
        $tax_id = Tax::where('tax_rate','=','6')->first()->id;

        foreach (BlancRow::where('project_id','=', $project->id)->where('tax_id', $tax_id)->get() as $row)
        {
            $total += $row->amount;
        }

        return $total;
    }

    public static function rowTax3($project) {
        $total = 0;
        $tax_id = Tax::where('tax_rate','=','0')->first()->id;

        foreach (BlancRow::where('project_id','=', $project->id)->where('tax_id', $tax_id)->get() as $row)
        {
            $total += $row->amount;
        }

        return $total;
    }

    public static function rowTax1Amount($project) {
        $total = 0;
        $tax_id = Tax::where('tax_rate','=','21')->first()->id;

        foreach (BlancRow::where('project_id','=', $project->id)->where('tax_id', $tax_id)->get() as $row)
        {
            $total += $row->amount * $row->rate;
        }

        return $total;
    }

    public static function rowTax2Amount($project) {
        $total = 0;
        $tax_id = Tax::where('tax_rate','=','6')->first()->id;

        foreach (BlancRow::where('project_id','=', $project->id)->where('tax_id', $tax_id)->get() as $row)
        {
            $total += $row->amount * $row->rate;
        }

        return $total;
    }

    public static function rowTax3Amount($project) {
        $total = 0;
        $tax_id = Tax::where('tax_rate','=','0')->first()->id;

        foreach (BlancRow::where('project_id','=', $project->id)->where('tax_id', $tax_id)->get() as $row)
        {
            $total += $row->amount * $row->rate;
        }

        return $total;
    }

    public static function rowTax1AmountTax($project) {
        return (BlancRowsEndresult::rowTax1Amount($project)/100)*21;
    }

    public static function rowTax2AmountTax($project) {
        return (BlancRowsEndresult::rowTax2Amount($project)/100)*6;
    }

    public static function totalProject($project) {
        $total = 0;

        $total += BlancRowsEndresult::rowTax1Amount($project);
        $total += BlancRowsEndresult::rowTax2Amount($project);
        $total += BlancRowsEndresult::rowTax3Amount($project);

        return $total;
    }

    public static function totalProjectTax($project) {
        $total = 0;

        $total += BlancRowsEndresult::rowTax1AmountTax($project);
        $total += BlancRowsEndresult::rowTax2AmountTax($project);

        return $total;
    }

    public static function superTotalProject($project) {
        return BlancRowsEndresult::totalProject($project) + BlancRowsEndresult::totalProjectTax($project);
    }
}
