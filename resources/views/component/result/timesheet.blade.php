<?php

use BynqIO\Dynq\Models\Project;
use BynqIO\Dynq\Models\Chapter;
use BynqIO\Dynq\Models\Activity;
use BynqIO\Dynq\Models\PartType;
use BynqIO\Dynq\Models\Part;
use BynqIO\Dynq\Models\Time;
use BynqIO\Dynq\Models\Detail;
use BynqIO\Dynq\Models\TimesheetKind;
use BynqIO\Dynq\Models\MoreLabor;
use BynqIO\Dynq\Calculus\TimesheetOverview;
use BynqIO\Dynq\Models\Timesheet;

?>

<div class="alert alert-info">
    <i class="fa fa-fa"></i>
    <strong>Het is voor onderaanneming niet mogelijk een urenregistratie bij te houden</strong>
</div>

<label><h4>Calculatie</h4></label>
<table class="table table-striped">
    <thead>
        <tr>
            <th class="col-md-3">Onderdeel</th>
            <th class="col-md-3">Werkzaamheden</th>
            <th class="col-md-2"><span class="pull-right">Gecalculeerd <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit zijn de gecalculeerde uren uit de offerte." href="javascript:void(0);"><i class="fa fa-info-circle"></i> </a></span></th>
            <th class="col-md-1"><span class="pull-right">Minderw. <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit is het bedrag dat voorkomt uit 'Calculeren Minderwerk'" href="#"><i class="fa fa-info-circle"></i></a></span></th>
            <th class="col-md-1"><span class="pull-right">Geboekt <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit zijn de geboekte uren uit de urenregistratie" href="#"><i class="fa fa-info-circle"></i></a></span></th>
            <th class="col-md-1"><span class="pull-right">Verschil <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit is het verschil tussen de gecalculeerde uren (minus de minderwerkuren) en de geboekte uren" href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></span></th>
            <th class="col-md-1"><span class="pull-right">Win./Ver. <a data-toggle="tooltip" data-placement="left" data-original-title="Dit is het verschil vertaald naar kosten op basis van het standaard uurtarief" href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></span></th>
        </tr>
    </thead>
    <tbody>
        <?php $rs_1 = 0; $rs_2 = 0; $rs_3 = 0; $rs_4 = 0; $rs_5 = 0; ?>
        @foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
        <?php $i = 0; ?>
        @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_type_id','=',PartType::where('type_name','=','calculation')->first()->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->whereNull('detail_id')->orderBy('priority')->get() as $activity)
        <?php $i++; ?>
        <?php

        $total_hours = TimesheetOverview::calcTotalAmount($activity->id);
        $total_hours_original = TimesheetOverview::calcOrigTotalAmount($activity->id);
        $total_hours_less = TimesheetOverview::calcLessTotalAmount($activity->id);
        $total_registered_hours = Timesheet::where('activity_id','=',$activity->id)->sum('register_hour');

        // col 2
        $less_hours = 0;
        if ($total_hours_less) {
            $less_hours = $total_hours_less - $total_hours_original;
        }

        // col 3
        $registerd_hours = $total_registered_hours;

        // col 4
        $difference = $total_hours-$total_registered_hours;

        // col 5
        $gain_loss = ($total_hours-$total_registered_hours)*$project->hour_rate;

        $rs_1 += $total_hours_original;
        $rs_2 += $less_hours;
        $rs_3 += $registerd_hours;
        $rs_4 += $difference;
        $rs_5 += $gain_loss;

        ?>
        <tr>
            <td class="col-md-3">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
            <td class="col-md-3">{{ $activity->activity_name }}</td>
            <td class="col-md-2"><span class="pull-right">{{ number_format($total_hours_original, 2,",",".") }}</span></td>
            <td class="col-md-1"><span class="pull-right">{{ number_format($less_hours, 2,",",".") }}</span></td>
            <td class="col-md-1"><span class="pull-right">{{ number_format($registerd_hours, 2,",",".") }}</span></td>
            <td class="col-md-1"><span class="pull-right">{{ number_format($difference, 2,",",".") }}</span></td>
            <td class="col-md-1"><span class="pull-right">{{ number_format($gain_loss, 2,",",".") }}</span></td>
        </tr>
        @endforeach
        @endforeach
        <tr>
            <th class="col-md-3"><strong>Totaal Calculatie</strong></th>
            <th class="col-md-3">&nbsp;</th>
            <td class="col-md-2"><strong><span class="pull-right">{{ number_format($rs_1, 2, ",",".") }}</span></strong></td>
            <td class="col-md-1"><strong><span class="pull-right">{{ number_format($rs_2, 2, ",",".") }}</span></strong></td>
            <td class="col-md-1"><strong><span class="pull-right">{{ number_format($rs_3, 2, ",",".") }}</span></strong></td>
            <td class="col-md-1"><strong><span class="pull-right">{{ number_format($rs_4, 2, ",",".") }}</span></strong></td>
            <td class="col-md-1"><strong><span class="pull-right">{{ number_format($rs_5, 2, ",",".") }}</span></strong></td>
        </tr>
    </tbody>
</table>

@if ($project->use_estimate)
<label><h4>Stelposten</h4></label>
<table class="table table-striped">
    <thead>
        <tr>
            <th class="col-md-3">Onderdeel</th>
            <th class="col-md-3">Werkzaamheden</th>
            <th class="col-md-2"><span class="pull-right">Gecalculeerd <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit zijn de gecalculeerde uren uit de calculatie." href="javascript:void(0);"><i class="fa fa-info-circle"></i> </a></span></th>
            <th class="col-md-1"><span class="pull-right">Gesteld <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit zijn de gestelde uren vanuit 'Stelposten Stellen'" href="#"><i class="fa fa-info-circle"></i></a></span></th>
            <th class="col-md-1"><span class="pull-right">Geboekt <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit zijn de geboekte uren uit de urenregistratie" href="#"><i class="fa fa-info-circle"></i></a></span></th>
            <th class="col-md-1"><span class="pull-right">Verschil <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit is het verschil tussen de gecalculeerde OF de gestelde uren minus de geboekte uren." href="#"><i class="fa fa-info-circle"></i></a></span></th>
            <th class="col-md-1"><span class="pull-right">Win./Ver. <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit is het verschil vertaald naar kosten op basis van het standaard uurtarief" href="#"><i class="fa fa-info-circle"></i></a></span></th>
        </tr>
    </thead>
    <tbody>
        <?php $rs_1 = 0; $rs_2 = 0; $rs_3 = 0; $rs_4 = 0; $rs_5 = 0; ?>
        @foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
        <?php $i = 0; ?>
        @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_type_id','=',PartType::where('type_name','=','estimate')->first()->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->orderBy('priority')->get() as $activity)
        <?php $i++; ?>
        <tr>
            <td class="col-md-3">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
            <td class="col-md-3">{{ $activity->activity_name }}</td>
            <td class="col-md-2"><span class="pull-right"><?php $rs_set = TimesheetOverview::estimOrigTotalAmount($activity->id); $rs_1 += $rs_set; echo $rs_set ? number_format($rs_set, 2,",",".") : '-'; ?></span></td></span></td>
            <td class="col-md-1"><span class="pull-right"><?php $rs_set = TimesheetOverview::estimSetTotalAmount($activity->id); $rs_set2 = TimesheetOverview::estimTimesheetTotalAmount($activity->id); $rs_2 = ($activity->use_timesheet ? $rs_set2 : $rs_set); echo ($activity->use_timesheet ? number_format($rs_set2, 2,",",".") : ($rs_set ? number_format($rs_set, 2,",",".") : '-')); ?></span></td></span></td>
            <td class="col-md-1"><span class="pull-right"><?php $rs_set = TimesheetOverview::estimTimesheetTotalAmount($activity->id); $rs_3 += $rs_set; echo $rs_set ? number_format($rs_set, 2,",",".") : '-'; ?></span></td></span></td>
            <td class="col-md-1"><span class="pull-right"><?php $rs_set = TimesheetOverview::estimSetTotalAmount($activity->id); $rs_set2 = TimesheetOverview::estimTimesheetTotalAmount($activity->id); $rs_2 = ($activity->use_timesheet ? $rs_set2 : $rs_set); $Z = Timesheet::where('activity_id','=',$activity->id)->sum('register_hour'); $rs_4 += ($rs_2-$Z); echo number_format($rs_2-$Z, 2,",","."); ?></span></td>
            <td class="col-md-1"><span class="pull-right"><?php $rs_set = TimesheetOverview::estimSetTotalAmount($activity->id); $rs_set2 = TimesheetOverview::estimTimesheetTotalAmount($activity->id); $rs_2 = ($activity->use_timesheet ? $rs_set2 : $rs_set); $Z = Timesheet::where('activity_id','=',$activity->id)->sum('register_hour'); $rs_5 += (($rs_2-$Z)*$project->hour_rate); echo number_format(($rs_2-$Z)*$project->hour_rate, 2,",","."); ?></span></td>
        </tr>
        @endforeach
        @endforeach
        <tr>
            <th class="col-md-3"><strong>Totaal Stelposten</strong></th>
            <th class="col-md-3">&nbsp;</th>
            <td class="col-md-2"><strong><span class="pull-right">{{ number_format($rs_1, 2, ",",".") }}</span></strong></td>
            <td class="col-md-1"><strong><span class="pull-right">{{ number_format($rs_2, 2, ",",".") }}</span></strong></td>
            <td class="col-md-1"><strong><span class="pull-right">{{ number_format($rs_3, 2, ",",".") }}</span></strong></td>
            <td class="col-md-1"><strong><span class="pull-right">{{ number_format($rs_4, 2, ",",".") }}</span></strong></td>
            <td class="col-md-1"><strong><span class="pull-right">{{ number_format($rs_5, 2, ",",".") }}</span></strong></td>
        </tr>
    </tbody>
</table>
@endif

@if ($project->use_more)
<label><h4>Meerwerk</h4></label>
<table class="table table-striped">
    <thead>
        <tr>
            <th class="col-md-3">Onderdeel</th>
            <th class="col-md-3">Werkzaamheden</th>
            <th class="col-md-2"><span class="pull-right">Gecalculeerd <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit zijn de (mondeling) opgegeven uren bij de tab 'Calculeren Meerwerk' die als prijsopgaaf kunnen dienen naar de klant. Wordt de urenregistratie bijgehouden dan is die bindend." href="#"><i class="fa fa-info-circle"></i></a></span></th>
            <th class="col-md-1"><span class="pull-right">&nbsp;</span></th>
            <th class="col-md-1"><span class="pull-right">Geboekt <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit zijn de geboekte uren uit de urenregistratie" href="#"><i class="fa fa-info-circle"></i></a></span></th>
            <th class="col-md-1"><span class="pull-right">Verschil <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit is het verschil tussen de gecalculeerde uren minus de geboekte uren." href="#"><i class="fa fa-info-circle"></i></a></span></th>
            <th class="col-md-1"><span class="pull-right">Win./Ver. <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit is het verschil vertaald naar kosten op basis van het standaard uurtarief" href="#"><i class="fa fa-info-circle"></i></a></span></th>
        </tr>
    </thead>
    <tbody>
        <?php $rs_1 = 0; $rs_2 = 0; $rs_3 = 0; $rs_4 = 0; ?>
        @foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
        <?php $i = 0; ?>
        @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_type_id','=',PartType::where('type_name','=','calculation')->first()->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->where('detail_id','=',Detail::where('detail_name','=','more')->first()->id)->get() as $activity)
        <?php $i++; ?>
        <tr>
            <td class="col-md-3">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
            <td class="col-md-3">{{ $activity->activity_name }}</td>
            <td class="col-md-2"><span class="pull-right">
            <?php
                $rs_set = Timesheet::where('activity_id','=',$activity->id)->where('timesheet_kind_id','=',TimesheetKind::where('kind_name','=','meerwerk')->first()->id)->sum('register_hour');
                $x = ($activity->use_timesheet ? $rs_set : MoreLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->sum('amount'));
                $rs_1 += $x;
                echo number_format($activity->use_timesheet ? $rs_set : MoreLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->sum('amount'), 2,",",".")
            ?>													
            </span></td>
            <td class="col-md-1"><span class="pull-right">&nbsp;</span></td>
            <td class="col-md-1"><span class="pull-right">
            <?php
                $rs_set = Timesheet::where('activity_id','=',$activity->id)->where('timesheet_kind_id','=',TimesheetKind::where('kind_name','=','meerwerk')->first()->id)->sum('register_hour');
                $y = $rs_set;
                $rs_2 += $y;
                echo number_format($rs_set, 2,",",".")
            ?></span></td>
            <td class="col-md-1"><span class="pull-right"><?php $rs_3 += ($x-$y); echo number_format($x-$y, 2,",",".") ?></span></td>
            <td class="col-md-1"><span class="pull-right"><?php $rs_4 += ($x-$y)*$project->hour_rate_more; echo number_format(($x-$y)*$project->hour_rate_more, 2,",",".") ?></span></td>
        </tr>
        @endforeach
        @endforeach
        <tr>
            <td class="col-md-3"><strong>Totaal Meerwerk</strong></td>
            <td class="col-md-3">&nbsp;</td>
            <td class="col-md-2"><strong><span class="pull-right">{{ number_format($rs_1, 2, ",",".") }}</span></strong></td>
            <td class="col-md-1"><strong><span class="pull-right">&nbsp;</span></strong></td>
            <td class="col-md-1"><strong><span class="pull-right">{{ number_format($rs_2, 2, ",",".") }}</span></strong></td>
            <td class="col-md-1"><strong><span class="pull-right">{{ number_format($rs_3, 2, ",",".") }}</span></strong></td>
            <td class="col-md-1"><strong><span class="pull-right">{{ number_format($rs_4, 2, ",",".") }}</span></strong></td>
        </tr>
    </tbody>
</table>
@endif

