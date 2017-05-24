{{--
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
--}}

{{--
 |--------------------------------------------------------------------------
 | Project summary per level specifying each layer
 |--------------------------------------------------------------------------
 |
 | You may wish to use controllers instead of, or in addition to, Closure
 | based routes. That's great! Here is an example controller method to
 | get you started. To route to this controller, just add the route:
 |
 |	Route::get('/', 'HomeController@showWelcome');
 |
--}}

<?php

use BynqIO\Dynq\Models\Chapter;
use BynqIO\Dynq\Calculus\CalculationOverview;
use BynqIO\Dynq\Models\Activity;
use BynqIO\Dynq\Models\Part;

?>

{{-- Contracting --}}
<h4>Aanneming</h4>
<table class="table table-striped">
    <thead>
        <tr>
            <th class="col-md-3">Onderdeel</th>
            <th class="col-md-3">Werkzaamheden</th>
            <th class="col-md-1"><span class="pull-right">Arbeidsuren</th>
            <th class="col-md-1"><span class="pull-right">Arbeid</th>
            <th class="col-md-1"><span class="pull-right">Materiaal</th>
            @if ($project->use_equipment)
            <th class="col-md-1"><span class="pull-right">Overig</th>
            @endif
            <th class="col-md-1"><span class="pull-right">Totaal</th>
            @if ($project->use_estimate)
            <th class="col-md-1"><span class="pull-right">Stelpost</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach ($project->chapters()->orderBy('priority')->get() as $chapter)
        <?php $i = 0; ?>
        @foreach ($filter($section, $chapter->activities()->where('part_id', Part::where('part_name', 'contracting')->firstOrFail()->id))->get() as $activity)
        <tr>
            <td class="col-md-3">{{ ++$i == 1 ? $chapter->chapter_name : ''  }}</td>
            <td class="col-md-3">{{ $activity->activity_name }}</td>
            <td class="col-md-1"><span class="pull-right">{{ number_format($calculus::laborTotal($activity), 2, ",",".") }}</td>
            <td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format($calculus::laborActivity($project->hour_rate, $activity), 2, ",",".") }}</span></td>
            <td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format($calculus::materialActivityProfit($activity, $project->{'profit_' . $key . '_contr_mat'}), 2, ",",".") }}</span></td>
            @if ($project->use_equipment)
            <td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format($calculus::equipmentActivityProfit($activity, $project->{'profit_' . $key . '_contr_equip'}), 2, ",",".") }}</span></td>
            @endif
            <td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format($calculus::activityTotalProfit($project->hour_rate, $activity, $project->{'profit_' . $key . '_contr_mat'}, $project->{'profit_' . $key . '_contr_equip'}), 2, ",",".") }} </td>
            @if ($project->use_estimate)
            <td class="col-md-1 text-center {{ CalculationOverview::estimateCheck($activity) }}"></td>
            @endif
        </tr>
        @endforeach
        @endforeach
        <tr>
            <th class="col-md-3"><strong>Totaal Aanneming</strong></th>
            <th class="col-md-3">&nbsp;</th>
            <td class="col-md-1"><strong><span class="pull-right">{{ number_format($calculus::contrLaborTotalAmount($project), 2, ",",".") }}</span></strong></td>
            <td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format($calculus::contrLaborTotal($project), 2, ",",".") }}</span></strong></td>
            <td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format($calculus::contrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
            @if ($project->use_equipment)
            <td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format($calculus::contrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
            @endif
            <td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format($calculus::contrTotal($project), 2, ",",".") }}</span></strong></td>
            @if ($project->use_estimate)
            <th class="col-md-1">&nbsp;</th>
            @endif
        </tr>
    </tbody>
</table>
{{-- /Contracting --}}

{{-- Subcontracting --}}
@if ($project->use_subcontract)
<h4>Onderaanneming</h4>
<table class="table table-striped">
    <thead>
        <tr>
            <th class="col-md-3">Onderdeel</th>
            <th class="col-md-3">Werkzaamheden</th>
            <th class="col-md-1"><span class="pull-right">Arbeidsuren</th>
            <th class="col-md-1"><span class="pull-right">Arbeid</th>
            <th class="col-md-1"><span class="pull-right">Materiaal</th>
            @if ($project->use_equipment)
            <th class="col-md-1"><span class="pull-right">Overig</th>
            @endif
            <th class="col-md-1"><span class="pull-right">Totaal</th>
            @if ($project->use_estimate)
            <th class="col-md-1"><span class="pull-right">Stelpost</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach ($project->chapters()->orderBy('priority')->get() as $chapter)
        <?php $i = 0; ?>
        @foreach ($filter($section, $chapter->activities()->where('part_id', Part::where('part_name', 'subcontracting')->firstOrFail()->id))->get() as $activity)
        <tr>
            <td class="col-md-3">{{ ++$i == 1 ? $chapter->chapter_name : '' }}</td>
            <td class="col-md-3">{{ $activity->activity_name }}</td>
            <td class="col-md-1"><span class="pull-right">{{ number_format($calculus::laborTotal($activity), 2, ",",".") }}</td>
            <td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format($calculus::laborActivity($project->hour_rate, $activity), 2, ",",".") }}</span></td>
            <td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format($calculus::materialActivityProfit($activity, $project->{'profit_' . $key . '_subcontr_mat'}), 2, ",",".") }}</span></td>
            @if ($project->use_equipment)
            <td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format($calculus::equipmentActivityProfit($activity, $project->{'profit_' . $key . '_subcontr_equip'}), 2, ",",".") }}</span></td>
            @endif
            <td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format($calculus::activityTotalProfit($project->hour_rate, $activity, $project->{'profit_' . $key . '_subcontr_mat'}, $project->{'profit_' . $key . '_subcontr_equip'}), 2, ",",".") }} </td>
            @if ($project->use_estimate)
            <td class="col-md-1 text-center {{ CalculationOverview::estimateCheck($activity) }}"></td>
            @endif
        </tr>
        @endforeach
        @endforeach
        <tr>
            <th class="col-md-3"><strong>Totaal Onderaanneming</strong></th>
            <th class="col-md-3">&nbsp;</th>
            <td class="col-md-1"><strong><span class="pull-right">{{ number_format($calculus::subcontrLaborTotalAmount($project), 2, ",",".") }}</span></strong></td>
            <td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format($calculus::subcontrLaborTotal($project), 2, ",",".") }}</span></strong></td>
            <td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format($calculus::subcontrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
            @if ($project->use_equipment)
            <td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format($calculus::subcontrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
            @endif
            <td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format($calculus::subcontrTotal($project), 2, ",",".") }}</span></strong></td>
            @if ($project->use_estimate)
            <th class="col-md-1">&nbsp;</th>
            @endif
        </tr>
    </tbody>
</table>
@endif
{{-- /Subcontracting  -}}

{{-- Project totals --}}
<h4>Totalen project</h4>
<table class="table table-striped">
    <thead>
        <tr>
            <th class="col-md-3">&nbsp;</th>
            <th class="col-md-3">&nbsp;</th>
            <th class="col-md-1"><span class="pull-right">Arbeidsuren</span></th>
            <th class="col-md-1"><span class="pull-right">Arbeid</span></th>
            <th class="col-md-1"><span class="pull-right">Materiaal</span></th>
            @if ($project->use_equipment)
            <th class="col-md-1"><span class="pull-right">Overig</span></th>
            @endif
            <th class="col-md-1"><span class="pull-right">Totaal</span></th>
            @if ($project->use_estimate)
            <th class="col-md-1">&nbsp;</th>
            @endif
        </tr>
    </thead>
    <tbody>
        <tr>
            <th class="col-md-3">&nbsp;</th>
            <th class="col-md-3">&nbsp;</th>
            <td class="col-md-1"><strong><span class="pull-right">{{ $calculus::laborSuperTotalAmount($project) }}</span></strong></td>
            <td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format($calculus::laborSuperTotal($project), 2, ",",".") }}</span></strong></td>
            <td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format($calculus::materialSuperTotal($project), 2, ",",".") }}</span></strong></td>
            @if ($project->use_equipment)
            <td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format($calculus::equipmentSuperTotal($project), 2, ",",".") }}</span></strong></td>
            @endif
            <td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format($calculus::superTotal($project), 2, ",",".") }}</span></strong></td>
            @if ($project->use_estimate)
            <th class="col-md-1">&nbsp;</th>
            @endif
        </tr>
    </tbody>
</table>
{{-- /Project totals --}}

<h5><strong><i class="fa fa-info-circle" aria-hidden="true"></i> Weergegeven bedragen zijn exclusief BTW</strong></h5>
