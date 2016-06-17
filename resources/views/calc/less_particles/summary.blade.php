<?php

use \Calctool\Models\Chapter;
use \Calctool\Models\Activity;
use \Calctool\Models\PartType;
use \Calctool\Models\Part;
use \Calctool\Calculus\LessOverview;
?>
<div>

	<div>
		<h4>Aanneming</h4>
		<div class="toggle-content">

			<table class="table table-striped">
				<thead>
					<tr>
						<th class="col-md-3">Onderdeel</th>
						<th class="col-md-4">Werkzaamheden</th>
						<th class="col-md-1"><span class="pull-right">Arbeidsuren</th>
						<th class="col-md-1"><span class="pull-right">Arbeid</th>
						<th class="col-md-1"><span class="pull-right">Materiaal</th>
						<th class="col-md-1"><span class="pull-right">Overig</th>
						<th class="col-md-1"><span class="pull-right">Totaal <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit is het bedrag dat in mindering wordt gebracht op de bestaande calculatie." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></th>
					</tr>
				</thead>

				<tbody>
					@foreach (Chapter::where('project_id','=', $project->id)->orderBy('created_at')->get() as $chapter)
					<?php $i = 0; ?>
					@foreach (Activity::where('chapter_id','=', $chapter->id)->whereNull('detail_id')->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->where('part_type_id','=',PartType::where('type_name','=','calculation')->first()->id)->orderBy('created_at')->get() as $activity)
					<?php $i++; ?>
					<tr>
						<td class="col-md-3">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
						<td class="col-md-4">{{ $activity->activity_name }}</td>
						<td class="col-md-1"><span class="pull-right">{{ number_format(LessOverview::laborTotal($activity), 2, ",",".") }}</td>
						<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(LessOverview::laborActivity($activity, $project), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(LessOverview::materialActivityProfit($activity, $project->profit_calc_contr_mat), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::equipmentActivityProfit($activity, $project->profit_calc_contr_equip), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::activityTotalProfit($activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip, $project), 2, ",",".") }} </td>
					</tr>
					@endforeach
					@endforeach
					<tr>
						<th class="col-md-3"><strong>Totaal Aanneming</strong></th>
						<th class="col-md-2">&nbsp;</th>
						<td class="col-md-1"><strong><span class="pull-right">{{ number_format(LessOverview::contrLaborTotalAmount($project), 2, ",",".") }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::contrLaborTotal($project), 2, ",",".") }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::contrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::contrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::contrTotal($project), 2, ",",".") }}</span></strong></td>
					</tr>
				</tbody>
			</table>

		</div>
	</div>

	<div>
		<h4>Onderaanneming</h4>
		<div class="toggle-content">

			<table class="table table-striped">
				<thead>
					<tr>
						<th class="col-md-3">Onderdeel</th>
						<th class="col-md-4">Werkzaamheden</th>
						<th class="col-md-1"><span class="pull-right">Arbeidsuren</th>
						<th class="col-md-1"><span class="pull-right">Arbeid</th>
						<th class="col-md-1"><span class="pull-right">Materiaal</th>
						<th class="col-md-1"><span class="pull-right">Overig</th>
						<th class="col-md-1"><span class="pull-right">Totaal <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit is het bedrag dat in mindering wordt gebracht op de bestaande calculatie." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></th>
					</tr>
				</thead>

				<tbody>
					@foreach (Chapter::where('project_id','=', $project->id)->orderBy('created_at')->get() as $chapter)
					<?php $i = 0; ?>
					@foreach (Activity::where('chapter_id','=', $chapter->id)->whereNull('detail_id')->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->where('part_type_id','=',PartType::where('type_name','=','calculation')->first()->id)->orderBy('created_at')->get() as $activity)
					<?php $i++ ?>
					<tr>
						<td class="col-md-3">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
						<td class="col-md-4">{{ $activity->activity_name }}</td>
						<td class="col-md-1"><span class="pull-right">{{ number_format(LessOverview::laborTotal($activity), 2, ",",".") }}</td>
						<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(LessOverview::laborActivity($activity), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(LessOverview::materialActivityProfit($activity, $project->profit_calc_subcontr_mat), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::equipmentActivityProfit($activity, $project->profit_calc_subcontr_equip), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::activityTotalProfit($activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip, $project), 2, ",",".") }} </td>
						<td class="col-md-1 text-center {{-- LessOverview::estimateCheck($activity) --}}"></td>
					</tr>
					@endforeach
					@endforeach
					<tr>
						<th class="col-md-3"><strong>Totaal Onderaanneming</strong></th>
						<th class="col-md-4">&nbsp;</th>
						<td class="col-md-1"><strong><span class="pull-right">{{ number_format(LessOverview::subcontrLaborTotalAmount($project), 2, ",",".") }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::subcontrLaborTotal($project), 2, ",",".") }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::subcontrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::subcontrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::subcontrTotal($project), 2, ",",".") }}</span></strong></td>
					</tr>
				</tbody>
			</table>

		</div>
	</div>

	<div>
		<h4>Totalen project</h4>
		<div class="toggle-content">
			<table class="table table-striped">
				<thead>
					<tr>
						<th class="col-md-4">&nbsp;</th>
						<th class="col-md-3">&nbsp;</th>
						<th class="col-md-1"><span class="pull-right">Arbeidsuren</span></th>
						<th class="col-md-1"><span class="pull-right">Arbeid</span></th>
						<th class="col-md-1"><span class="pull-right">Materiaal</span></th>
						<th class="col-md-1"><span class="pull-right">Overig</span></th>
						<th class="col-md-1"><span class="pull-right">Totaal <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit is het bedrag dat in mindering wordt gebracht op de bestaande calculatie." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></span></th>
					</tr>
				</thead>

				<tbody>
					<tr>
						<th class="col-md-4">&nbsp;</th>
						<th class="col-md-3">&nbsp;</th>
						<td class="col-md-1"><span class="pull-right"><strong>{{ number_format(LessOverview::laborSuperTotalAmount($project), 2, ",",".") }}</strong></span></td>
						<td class="col-md-1"><span class="pull-right"><strong>{{ '&euro; '.number_format(LessOverview::laborSuperTotal($project), 2, ",",".") }}</strong></span></td>
						<td class="col-md-1"><span class="pull-right"><strong>{{ '&euro; '.number_format(LessOverview::materialSuperTotal($project), 2, ",",".") }}</strong></span></td>
						<td class="col-md-1"><span class="pull-right"><strong>{{ '&euro; '.number_format(LessOverview::equipmentSuperTotal($project), 2, ",",".") }}</strong></span></td>
						<td class="col-md-1"><span class="pull-right"><strong>{{ '&euro; '.number_format(LessOverview::superTotal($project), 2, ",",".") }}</strong></span></td>
					</tr>
				</tbody>
			</table>
			<h5><strong>Weergegeven bedragen zijn exclusief BTW</strong></h5>
		</div>
	</div>

</div>