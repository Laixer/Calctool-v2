<?php

use \Calctool\Models\Chapter;
use \Calctool\Models\Activity;
use \Calctool\Models\PartType;
use \Calctool\Models\Part;
use \Calctool\Calculus\EstimateOverview;

?>
<div>

	<div>
		<label>Aanneming</label>
		<div class="toggle-content">

			<table class="table table-striped">

				<thead>
					<tr>
						<th class="col-md-3">Hoofdstuk</th>
						<th class="col-md-3">Werkzaamheden</th>
						<th class="col-md-1"><span class="pull-right">Arbeidsuren</th>
						<th class="col-md-1"><span class="pull-right">Arbeid</th>
						<th class="col-md-1"><span class="pull-right">Materiaal</th>
						<th class="col-md-1"><span class="pull-right">Materieel</th>
						<th class="col-md-1"><span class="pull-right">Totaal</th>
					</tr>
				</thead>


				<tbody>
					@foreach (Chapter::where('project_id','=', $project->id)->orderBy('created_at')->get() as $chapter)
					<?php $i = 0; ?>
					@foreach (Activity::where('chapter_id','=', $chapter->id)->whereNull('detail_id')->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->where('part_type_id','=',PartType::where('type_name','=','estimate')->first()->id)->orderBy('created_at')->get() as $activity)
					<?php $i++; ?>
					<tr>
						<td class="col-md-3">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
						<td class="col-md-3">{{ $activity->activity_name }}</td>
						<td class="col-md-1"><span class="pull-right">{{ number_format(EstimateOverview::laborTotal($activity), 2, ",",".") }}</td>
						<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(EstimateOverview::laborActivity($activity), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(EstimateOverview::materialActivityProfit($activity, $project->profit_calc_contr_mat), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::equipmentActivityProfit($activity, $project->profit_calc_contr_equip), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::activityTotalProfit($activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip), 2, ",",".") }} </td>
					</tr>
					@endforeach
					@endforeach
					<tr>
						<th class="col-md-3"><strong>Totaal Aanneming</strong></th>
						<th class="col-md-3">&nbsp;</th>
						<td class="col-md-1"><strong><span class="pull-right">{{ number_format(EstimateOverview::contrLaborTotalAmount($project), 2, ",",".") }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::contrLaborTotal($project), 2, ",",".") }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::contrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::contrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::contrTotal($project), 2, ",",".") }}</span></strong></td>
					</tr>
				</tbody>
			</table>

		</div>
	</div>

	<div>
		<label>Onderaanneming</label>
		<div class="toggle-content">

			<table class="table table-striped">
				<thead>
					<tr>
						<th class="col-md-3">Hoofdstuk</th>
						<th class="col-md-3">Werkzaamheden</th>
						<th class="col-md-1"><span class="pull-right">Arbeidsuren</th>
						<th class="col-md-1"><span class="pull-right">Arbeid</th>
						<th class="col-md-1"><span class="pull-right">Materiaal</th>
						<th class="col-md-1"><span class="pull-right">Materieel</th>
						<th class="col-md-1"><span class="pull-right">Totaal</th>
					</tr>
				</thead>


				<tbody>
					@foreach (Chapter::where('project_id','=', $project->id)->orderBy('created_at')->get() as $chapter)
					<?php $i = 0; ?>
					@foreach (Activity::where('chapter_id','=', $chapter->id)->whereNull('detail_id')->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->where('part_type_id','=',PartType::where('type_name','=','estimate')->first()->id)->orderBy('created_at')->get() as $activity)
					<?php $i++; ?>
					<tr>
						<td class="col-md-3">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
						<td class="col-md-3">{{ $activity->activity_name }}</td>
						<td class="col-md-1"><span class="pull-right">{{ number_format(EstimateOverview::laborTotal($activity), 2, ",",".") }}</td>
						<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(EstimateOverview::laborActivity($activity), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(EstimateOverview::MaterialActivityProfit($activity, $project->profit_calc_subcontr_mat), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::equipmentActivityProfit($activity, $project->profit_calc_subcontr_equip), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::activityTotalProfit($activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip), 2, ",",".") }} </td>
					</tr>
					@endforeach
					@endforeach
					<tr>
						<th class="col-md-3"><strong>Totaal Onderaanneming</strong></th>
						<th class="col-md-3">&nbsp;</th>
						<td class="col-md-1"><strong><span class="pull-right">{{ number_format(EstimateOverview::subcontrLaborTotalAmount($project), 2, ",",".") }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::subcontrLaborTotal($project), 2, ",",".") }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::subcontrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::subcontrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::subcontrTotal($project), 2, ",",".") }}</span></strong></td>
					</tr>
				</tbody>
			</table>

		</div>
	</div>

	<div>
		<label>Totalen project</label>
		<div class="toggle-content">
			<table class="table table-striped">

				<thead>
					<tr>
						<th class="col-md-3">&nbsp;</th>
						<th class="col-md-3">&nbsp;</th>
						<th class="col-md-1"><span class="pull-right">Arbeidsuren</span></th>
						<th class="col-md-1"><span class="pull-right">Arbeid</span></th>
						<th class="col-md-1"><span class="pull-right">Materiaal</span></th>
						<th class="col-md-1"><span class="pull-right">Materieel</span></th>
						<th class="col-md-1"><span class="pull-right">Totaal</span></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th class="col-md-3">&nbsp;</th>
						<th class="col-md-3">&nbsp;</th>
						<td class="col-md-1"><strong><span class="pull-right">{{ number_format(EstimateOverview::laborSuperTotalAmount($project), 2, ",",".") }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::laborSuperTotal($project), 2, ",",".") }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::materialSuperTotal($project), 2, ",",".") }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::equipmentSuperTotal($project), 2, ",",".") }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::superTotal($project), 2, ",",".") }}</span></strong></td>
					</tr>
				</tbody>
			</table>
			<h5><strong>Weergegeven bedragen zijn exclusief BTW</strong></h5>
		</div>
	</div>

</div>