<?php

use \CalculatieTool\Models\Project;
use \CalculatieTool\Models\Chapter;
use \CalculatieTool\Models\Activity as ProjectActivity;
use \CalculatieTool\Models\PartType;
use \CalculatieTool\Models\Part;
use \CalculatieTool\Models\Tax;
use \CalculatieTool\Models\CalculationLabor;
use \CalculatieTool\Calculus\CalculationRegister;
use \CalculatieTool\Models\CalculationMaterial;
use \CalculatieTool\Models\CalculationEquipment;
use \CalculatieTool\Models\EstimateLabor;
use \CalculatieTool\Models\EstimateMaterial;
use \CalculatieTool\Models\EstimateEquipment;
use \CalculatieTool\Models\ProjectType;
use \CalculatieTool\Calculus\CalculationOverview;
use \CalculatieTool\Calculus\CalculationEndresult;

$common_access_error = false;
$project = Project::find(Route::Input('project_id'));
if (!$project || !$project->isOwner() || $project->is_dilapidated)
	$common_access_error = true;
?>

@extends('layout.master')

@section('title', 'Calculeren')

<?php if($common_access_error){ ?>
@section('content')
<div id="wrapper">
	<section class="container">
		<div class="alert alert-danger">
			<i class="fa fa-frown-o"></i>
			<strong>Fout</strong>
			Dit project bestaat niet
		</div>
	</section>
</div>
@stop
<?php }else{ ?>

@section('content')

<script type="text/javascript">
	$(document).ready(function() {
		$('.toggle').click(function(e){
			$id = $(this).attr('id');
			if ($(this).hasClass('active')) {
				if (sessionStorage.toggleOpen{{Auth::user()->id}}){
					$toggleOpen = JSON.parse(sessionStorage.toggleOpen{{Auth::user()->id}});
				} else {
					$toggleOpen = [];
				}
				if (!$toggleOpen.length)
					$toggleOpen.push($id);
				for(var i in $toggleOpen){
					if ($toggleOpen.indexOf( $id ) == -1)
						$toggleOpen.push($id);
				}
				sessionStorage.toggleOpen{{Auth::user()->id}} = JSON.stringify($toggleOpen);
			} else {
				$tmpOpen = [];
				if (sessionStorage.toggleOpen{{Auth::user()->id}}){
					$toggleOpen = JSON.parse(sessionStorage.toggleOpen{{Auth::user()->id}});
					for(var i in $toggleOpen){
						if($toggleOpen[i] != $id)
							$tmpOpen.push($toggleOpen[i]);
					}
				}
				sessionStorage.toggleOpen{{Auth::user()->id}} = JSON.stringify($tmpOpen);
			}
		});
		if (sessionStorage.toggleOpen{{Auth::user()->id}}){
			$toggleOpen = JSON.parse(sessionStorage.toggleOpen{{Auth::user()->id}});
			for(var i in $toggleOpen){
				$('#'+$toggleOpen[i]).addClass('active').children('.toggle-content').toggle();
			}
		}
		$('#tab-calculate').click(function(e){
			sessionStorage.toggleTabCalc{{Auth::user()->id}} = 'calculate';
		});
		$('#tab-estimate').click(function(e){
			sessionStorage.toggleTabCalc{{Auth::user()->id}} = 'estimate';
		});
		$('#tab-summary').click(function(e){
			sessionStorage.toggleTabCalc{{Auth::user()->id}} = 'summary';
		});
		$('#tab-endresult').click(function(e){
			sessionStorage.toggleTabCalc{{Auth::user()->id}} = 'endresult';
		});
		if (sessionStorage.toggleTabCalc{{Auth::user()->id}}){
			$toggleOpenTab = sessionStorage.toggleTabCalc{{Auth::user()->id}};
			$('#tab-'+$toggleOpenTab).addClass('active');
			$('#'+$toggleOpenTab).addClass('active');
		} else {
			sessionStorage.toggleTabCalc{{Auth::user()->id}} = 'calculate';
			$('#tab-calculate').addClass('active');
			$('#calculate').addClass('active');
		}
	});
</script>

<div id="wrapper">

	<section class="container fix-footer-bottom">

		@include('calc.wizard', array('page' => 'calculation'))

			<div class="pull-right">
				<a href="/project-{{ $project->id }}/printoverview" class="btn btn-primary" target="new" type="button"><i class="fa fa-file-pdf-o">&nbsp;</i>Project overzicht</a>
			</div>

			<h2><strong>Calculeren</strong></h2>

			<div class="tabs nomargin">


				<ul class="nav nav-tabs">
					<li id="tab-calculate">
						<a href="#calculate" data-toggle="tab">
							<i class="fa fa-list"></i> Calculeren
						</a>
					</li>
					@if ($project->use_estimate)
					<li id="tab-estimate">
						<a href="#estimate" data-toggle="tab">
							<i class="fa fa-align-justify"></i> Stelposten
						</a>
					</li>
					@endif
					<li id="tab-summary">
						<a href="#summary" data-toggle="tab">
							<i class="fa fa-sort-amount-asc"></i> Uittrekstaat Calculeren
						</a>
					</li>
					<li id="tab-endresult">
						<a href="#endresult" data-toggle="tab">
							<i class="fa fa-check-circle-o"></i> Eindresultaat Calculeren
						</a>
					</li>
				</ul>

				<div class="tab-content">
					<div id="calculate" class="tab-pane">
						<div class="toogle">

							@foreach (Chapter::where('project_id','=', $project->id)->orderBy('priority')->get() as $chapter)
							<div id="toggle-chapter-{{ $chapter->id }}" class="toggle toggle-chapter">
								<label>{{ $chapter->chapter_name }}</label>
								<div class="toggle-content">

									<div class="toogle">

										<?php
										foreach(ProjectActivity::where('chapter_id','=', $chapter->id)->whereNull('detail_id')->where('part_type_id','=',PartType::where('type_name','=','calculation')->first()->id)->orderBy('priority')->get() as $activity) {
											if (Part::find($activity->part_id)->part_name=='contracting') {
												$profit_mat = $project->profit_calc_contr_mat;
												$profit_equip = $project->profit_calc_contr_equip;
											} else if (Part::find($activity->part_id)->part_name=='subcontracting') {
												$profit_mat = $project->profit_calc_subcontr_mat;
												$profit_equip = $project->profit_calc_subcontr_equip;
											}
										?>
										<div id="toggle-activity-{{ $activity->id }}" class="toggle toggle-activity">
											<label>{{ $activity->activity_name }}</label>
											<div class="toggle-content">
												<div class="row">
													<div class="col-md-1 text-right label label-info"><strong>{{ Part::find($activity->part_id)->part_name=='subcontracting' ? 'Onderaanneming' : 'Aanneming' }}</strong></div>
													<div class="col-md-10"></div>
												</div>
												<div class="row">
													<div class="col-md-2"><h4>Arbeid</h4></div>
													<div class="col-md-1 text-right label label-info"><strong>BTW {{ Tax::find($activity->tax_labor_id)->tax_rate }}%</strong></div>
												</div>
												<table class="table table-striped">
													<thead>
														<tr>
															<th class="col-md-5">Omschrijving</th>
															<th class="col-md-1">&nbsp;</th>
															<th class="col-md-1">Uurtarief</th>
															<th class="col-md-1">Aantal</th>
															<th class="col-md-1">&nbsp;</th>
															<th class="col-md-1">Prijs</th>
															<th class="col-md-1">&nbsp;</th>
														</tr>
													</thead>

													<tbody>
														<tr>
															<td class="col-md-5">Arbeidsuren</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">{{ number_format($project->hour_rate, 2,",",".") }}</td>
															<td class="col-md-1">{{ number_format(CalculationLabor::where('activity_id','=', $activity->id)->first()['amount'], 2, ",",".") }}</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">{{ '&euro; '.number_format(CalculationRegister::calcLaborTotal($project->hour_rate, CalculationLabor::where('activity_id','=', $activity->id)->first()['amount']),2, ",",".") }}</td>
															<td class="col-md-1"></td>
														</tr>
													</tbody>
												</table>

												<div class="row">
													<div class="col-md-2"><h4>Materiaal</h4></div>
													<div class="col-md-1 text-right label label-info"><strong>BTW {{ Tax::find($activity->tax_material_id)->tax_rate }}%</strong></div>
												</div>

												<table class="table table-striped">

													<thead>
														<tr>
															<th class="col-md-5">Omschrijving</th>
															<th class="col-md-1">Eenheid</th>
															<th class="col-md-1">&euro; / Eenh.</th>
															<th class="col-md-1">Aantal</th>
															<th class="col-md-1">Prijs</th>
															<th class="col-md-1">+ Winst %</th>
															<th class="col-md-1">&nbsp;</th>
														</tr>
													</thead>


													<tbody>
														@foreach (CalculationMaterial::where('activity_id','=', $activity->id)->get() as $material)
														<tr>
															<td class="col-md-5">{{ $material->material_name }}</td>
															<td class="col-md-1">{{ $material->unit }}</td>
															<td class="col-md-1">{{ number_format($material->rate, 2,",",".") }}</td>
															<td class="col-md-1">{{ number_format($material->amount, 2,",",".") }}</td>
															<td class="col-md-1">{{ '&euro; '.number_format($material->rate*$material->amount, 2,",",".") }}</td>
															<td class="col-md-1">{{ '&euro; '.number_format($material->rate*$material->amount*((100+$profit_mat)/100), 2,",",".") }}</td>
															<td class="col-md-1 text-right"></td>
														</tr>
														@endforeach
													</tbody>
													<tbody>
														<tr>
															<td class="col-md-5"><strong>Totaal</strong></td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1"><strong>{{ '&euro; '.number_format(CalculationRegister::calcMaterialTotal($activity->id, $profit_mat), 2, ",",".") }}</span></td>
															<td class="col-md-1"><strong>{{ '&euro; '.number_format(CalculationRegister::calcMaterialTotalProfit($activity->id, $profit_mat), 2, ",",".") }}</span></td>
															<td class="col-md-1">&nbsp;</td>
														</tr>
													</tbody>
												</table>

												@if ($project->use_equipment)
												<div class="row">
													<div class="col-md-2"><h4>Overig</h4></div>
													<div class="col-md-1 text-right label label-info"><strong>BTW {{ Tax::find($activity->tax_equipment_id)->tax_rate }}%</strong></div>

												</div>

												<table class="table table-striped">

													<thead>
														<tr>
															<th class="col-md-5">Omschrijving</th>
															<th class="col-md-1">Eenheid</th>
															<th class="col-md-1">&euro; / Eenh.</th>
															<th class="col-md-1">Aantal</th>
															<th class="col-md-1">Prijs</th>
															<th class="col-md-1">+ Winst %</th>
															<th class="col-md-1">&nbsp;</th>
														</tr>
													</thead>


													<tbody>
														@foreach (CalculationEquipment::where('activity_id','=', $activity->id)->get() as $equipment)
														<tr>
															<td class="col-md-5">{{ $equipment->equipment_name }}</td>
															<td class="col-md-1">{{ $equipment->unit }}</td>
															<td class="col-md-1">{{ number_format($equipment->rate, 2,",",".") }}</td>
															<td class="col-md-1">{{ number_format($equipment->amount, 2,",",".") }}</td>
															<td class="col-md-1">{{ '&euro; '.number_format($equipment->rate*$equipment->amount, 2,",",".") }}</td>
															<td class="col-md-1">{{ '&euro; '.number_format($equipment->rate*$equipment->amount*((100+$profit_equip)/100), 2,",",".") }}</td>
															<td class="col-md-1"></td>
														</tr>
														@endforeach
													</tbody>
													<tbody>
														<tr>
															<td class="col-md-5"><strong>Totaal</strong></td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1"><strong>{{ '&euro; '.number_format(CalculationRegister::calcEquipmentTotal($activity->id, $profit_equip), 2, ",",".") }}</span></td>
															<td class="col-md-1"><strong>{{ '&euro; '.number_format(CalculationRegister::calcEquipmentTotalProfit($activity->id, $profit_equip), 2, ",",".") }}</span></td>
															<td class="col-md-1">&nbsp;</td>
														</tr>
													</tbody>
												</table>
												@endif
											</div>
										</div>
										<?php } ?>
									</div>

								</div>
							</div>
							@endforeach
						</div>

					</div>

					@if ($project->use_estimate)
					<div id="estimate" class="tab-pane">
						<div class="toogle">

							@foreach (Chapter::where('project_id','=', $project->id)->orderBy('priority')->get() as $chapter)
							<div id="toggle-chapter-{{ $chapter->id }}" class="toggle toggle-chapter">
								<label>{{ $chapter->chapter_name }}</label>
								<div class="toggle-content">

									<div class="toogle">

										<?php
										foreach(ProjectActivity::where('chapter_id','=', $chapter->id)->whereNull('detail_id')->where('part_type_id','=',PartType::where('type_name','=','estimate')->first()->id)->orderBy('priority')->get() as $activity) {
											$profit_mat = 0;
											if (Part::find($activity->part_id)->part_name=='contracting') {
												$profit_mat = $project->profit_calc_contr_mat;
												$profit_equip = $project->profit_calc_contr_equip;
											} else if (Part::find($activity->part_id)->part_name=='subcontracting') {
												$profit_mat = $project->profit_calc_subcontr_mat;
												$profit_equip = $project->profit_calc_subcontr_equip;
											}
										?>
										<div id="toggle-activity-{{ $activity->id }}" class="toggle toggle-activity">
											<label>{{ $activity->activity_name }}</label>
											<div class="toggle-content">
												<div class="row">
													<div class="col-md-1 text-right label label-info"><strong>{{ Part::find($activity->part_id)->part_name=='subcontracting' ? 'Onderaanneming' : 'Aanneming' }}</strong></div>						
													<div class="col-md-10"></div>
												</div>
												<div class="row">
													<div class="col-md-2"><h4>Arbeid</h4></div>
													<div class="col-md-1 text-right"><strong>BTW</strong></div>
													<div class="col-md-2">{{ Tax::find($activity->tax_labor_id)->tax_rate }}%</div>
													<div class="col-md-6"></div>
												</div>
												<table class="table table-striped">

													<thead>
														<tr>
															<th class="col-md-5">Omschrijving</th>
															<th class="col-md-1">&nbsp;</th>
															<th class="col-md-1">Uurtarief</th>
															<th class="col-md-1">Aantal</th>
															<th class="col-md-1">&nbsp;</th>
															<th class="col-md-1">Prijs</th>
															<th class="col-md-1">&nbsp;</th>
														</tr>
													</thead>

													<tbody>
														<tr>
															<td class="col-md-5">Arbeidsuren</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">{{ number_format($project->hour_rate, 2,",",".") }}</td>
															<td class="col-md-1">{{ number_format(EstimateLabor::where('activity_id', $activity->id)->whereNull('hour_id')->first()['amount'], 2, ",",".") }}</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">{{ '&euro; '.number_format(CalculationRegister::estimLaborTotal(EstimateLabor::where('activity_id', $activity->id)->whereNull('hour_id')->first()['rate'], EstimateLabor::where('activity_id', $activity->id)->whereNull('hour_id')->first()['amount']), 2, ",",".") }}</td>
															<td class="col-md-1"></td>
														</tr>
													</tbody>
												</table>

												<div class="row">
													<div class="col-md-2"><h4>Materiaal</h4></div>
													<div class="col-md-1 text-right"><strong>BTW</strong></div>
													<div class="col-md-2">{{ Tax::find($activity->tax_material_id)->tax_rate }}%</div>
													<div class="col-md-2"></div>
												</div>

												<table class="table table-striped">

													<thead>
														<tr>
															<th class="col-md-5">Omschrijving</th>
															<th class="col-md-1">Eenheid</th>
															<th class="col-md-1">&euro; / Eenh.</th>
															<th class="col-md-1">Aantal</th>
															<th class="col-md-1">Prijs</th>
															<th class="col-md-1">+ Winst %</th>
															<th class="col-md-1">&nbsp;</th>
														</tr>
													</thead>


													<tbody>
														@foreach (EstimateMaterial::where('activity_id','=', $activity->id)->get() as $material)
														<tr>
															<td class="col-md-5">{{ $material->material_name }}</td>
															<td class="col-md-1">{{ $material->unit }}</td>
															<td class="col-md-1">{{ number_format($material->rate, 2,",",".") }}</td>
															<td class="col-md-1">{{ number_format($material->amount, 2,",",".") }}</td>
															<td class="col-md-1">{{ '&euro; '.number_format($material->rate*$material->amount, 2,",",".") }}</td>
															<td class="col-md-1">{{ '&euro; '.number_format($material->rate*$material->amount*((100+$profit_mat)/100), 2,",",".") }}</td>
															<td class="col-md-1"></td>
														</tr>
														@endforeach
													</tbody>
													<tbody>
														<tr>
															<td class="col-md-5"><strong>Totaal</strong></td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1"><strong>{{ '&euro; '.number_format(CalculationRegister::estimMaterialTotal($activity->id, $profit_mat), 2, ",",".") }}</span></td>
															<td class="col-md-1"><strong>{{ '&euro; '.number_format(CalculationRegister::estimMaterialTotalProfit($activity->id, $profit_mat), 2, ",",".") }}</span></td>
															<td class="col-md-1">&nbsp;</td>
														</tr>
													</tbody>
												</table>

												<div class="row">
													<div class="col-md-2"><h4>Overig</h4></div>
													<div class="col-md-1 text-right"><strong>BTW</strong></div>
													<div class="col-md-2">{{ Tax::find($activity->tax_equipment_id)->tax_rate }}%</div>
													<div class="col-md-8"></div>
												</div>

												<table class="table table-striped">

													<thead>
														<tr>
															<th class="col-md-5">Omschrijving</th>
															<th class="col-md-1">Eenheid</th>
															<th class="col-md-1">&euro; / Eenh.</th>
															<th class="col-md-1">Aantal</th>
															<th class="col-md-1">Prijs</th>
															<th class="col-md-1">+ Winst %</th>
															<th class="col-md-1">&nbsp;</th>
														</tr>
													</thead>


													<tbody>
														@foreach (EstimateEquipment::where('activity_id','=', $activity->id)->get() as $equipment)
														<tr>
															<td class="col-md-5">{{ $equipment->equipment_name }}</td>
															<td class="col-md-1">{{ $equipment->unit }}</td>
															<td class="col-md-1">{{ number_format($equipment->rate, 2,",",".") }}</td>
															<td class="col-md-1">{{ number_format($equipment->amount, 2,",",".") }}</td>
															<td class="col-md-1">{{ '&euro; '.number_format($equipment->rate*$equipment->amount, 2,",",".") }}</td>
															<td class="col-md-1">{{ '&euro; '.number_format($equipment->rate*$equipment->amount*((100+$profit_equip)/100), 2,",",".") }}</td>
															<td class="col-md-1"></td>
														</tr>
														@endforeach
													</tbody>
													<tbody>
														<tr>
															<td class="col-md-5"><strong>Totaal</strong></td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1"><strong>{{ '&euro; '.number_format(CalculationRegister::estimEquipmentTotal($activity->id, $profit_equip), 2, ",",".") }}</span></td>
															<td class="col-md-1"><strong>{{ '&euro; '.number_format(CalculationRegister::estimEquipmentTotalProfit($activity->id, $profit_equip), 2, ",",".") }}</span></td>
															<td class="col-md-1">&nbsp;</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
										<?php } ?>
									</div>

								</div>
							</div>
							@endforeach
						</div>

					</div>
					@endif

					<div id="summary" class="tab-pane">
						<div class="toogle">

							<div class="toggle toggle-chapter active">
								<label>Aanneming</label>
								<div class="toggle-content">

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
												<th class="col-md-1"><span class="text-center">&nbsp;&nbsp;&nbsp;Stelpost</th>
											</tr>
										</thead>


										<tbody>
											@foreach (Chapter::where('project_id','=', $project->id)->orderBy('priority')->get() as $chapter)
											<?php $i = 0; ?>
											@foreach (ProjectActivity::where('chapter_id','=', $chapter->id)->whereNull('detail_id')->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->orderBy('priority')->get() as $activity)
											<?php $i++; ?>
											<tr>
												<td class="col-md-3">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
												<td class="col-md-3">{{ $activity->activity_name }}</td>
												<td class="col-md-1"><span class="pull-right">{{ number_format(CalculationOverview::laborTotal($activity), 2, ",",".") }}</td>
												<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::laborActivity($project->hour_rate, $activity), 2, ",",".") }}</span></td>
												<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::materialActivityProfit($activity, $project->profit_calc_contr_mat), 2, ",",".") }}</span></td>
												@if ($project->use_equipment)
												<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::equipmentActivityProfit($activity, $project->profit_calc_contr_equip), 2, ",",".") }}</span></td>
												@endif
												<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::activityTotalProfit($project->hour_rate, $activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip), 2, ",",".") }} </td>
												<td class="col-md-1 text-center {{ CalculationOverview::estimateCheck($activity) }}"></td>
											</tr>
											@endforeach
											@endforeach
											<tr>
												<th class="col-md-3"><strong>Totaal Aanneming</strong></th>
												<th class="col-md-3">&nbsp;</th>
												<td class="col-md-1"><strong><span class="pull-right">{{ number_format(CalculationOverview::contrLaborTotalAmount($project), 2, ",",".") }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::contrLaborTotal($project), 2, ",",".") }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::contrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
												@if ($project->use_equipment)
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::contrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
												@endif
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::contrTotal($project), 2, ",",".") }}</span></strong></td>
												<th class="col-md-1">&nbsp;</th>
											</tr>
										</tbody>
									</table>

								</div>
							</div>

							<div class="toggle toggle-chapter active">
								<label>Onderaanneming</label>
								<div class="toggle-content">

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
												<th class="col-md-1"><span class="text-center">&nbsp;&nbsp;&nbsp;Stelpost</th>
											</tr>
										</thead>


										<tbody>
											@foreach (Chapter::where('project_id','=', $project->id)->orderBy('priority')->get() as $chapter)
											<?php $i = 0; ?>
											@foreach (ProjectActivity::where('chapter_id','=', $chapter->id)->whereNull('detail_id')->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->orderBy('priority')->get() as $activity)
											<?php $i++; ?>
											<tr>
												<td class="col-md-3">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
												<td class="col-md-3">{{ $activity->activity_name }}</td>
												<td class="col-md-1"><span class="pull-right">{{ number_format(CalculationOverview::laborTotal($activity), 2, ",",".") }}</td>
												<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::laborActivity($project->hour_rate, $activity), 2, ",",".") }}</span></td>
												<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::materialActivityProfit($activity, $project->profit_calc_subcontr_mat), 2, ",",".") }}</span></td>
												@if ($project->use_equipment)
												<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::equipmentActivityProfit($activity, $project->profit_calc_subcontr_equip), 2, ",",".") }}</span></td>
												@endif
												<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::activityTotalProfit($project->hour_rate, $activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip), 2, ",",".") }} </td>
												<td class="col-md-1 text-center {{ CalculationOverview::estimateCheck($activity) }}"></td>
											</tr>
											@endforeach
											@endforeach
											<tr>
												<th class="col-md-3"><strong>Totaal Onderaanneming</strong></th>
												<th class="col-md-3">&nbsp;</th>
												<td class="col-md-1"><strong><span class="pull-right">{{ number_format(CalculationOverview::subcontrLaborTotalAmount($project), 2, ",",".") }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::subcontrLaborTotal($project), 2, ",",".") }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::subcontrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
												@if ($project->use_equipment)
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::subcontrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
												@endif
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::subcontrTotal($project), 2, ",",".") }}</span></strong></td>
												<th class="col-md-1">&nbsp;</th>
											</tr>
										</tbody>
									</table>

								</div>
							</div>

							<div class="toggle toggle-chapter active">
								<label>Totalen project</label>
								<div class="toggle-content">
									<table class="table table-striped">

										<thead>
											<tr>
												<th class="col-md-3">Onderdeel</th>
												<th class="col-md-3">Werkzaamheden</th>
												<th class="col-md-1"><span class="pull-right">Arbeidsuren</span></th>
												<th class="col-md-1"><span class="pull-right">Arbeid</span></th>
												<th class="col-md-1"><span class="pull-right">Materiaal</span></th>
												@if ($project->use_equipment)
												<th class="col-md-1"><span class="pull-right">Overig</span></th>
												@endif
												<th class="col-md-1"><span class="pull-right">Totaal</span></th>
												<th class="col-md-1">&nbsp;</th>
											</tr>
										</thead>

										<tbody>
											<tr>
												<th class="col-md-3">&nbsp;</th>
												<th class="col-md-3">&nbsp;</th>
												<td class="col-md-1"><strong><span class="pull-right">{{ number_format(CalculationOverview::laborSuperTotalAmount($project), 2, ",",".") }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::laborSuperTotal($project), 2, ",",".") }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::materialSuperTotal($project), 2, ",",".") }}</span></strong></td>
												@if ($project->use_equipment)
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::equipmentSuperTotal($project), 2, ",",".") }}</span></strong></td>
												@endif
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::superTotal($project), 2, ",",".") }}</span></strong></td>
												<th class="col-md-1">&nbsp;</th>
											</tr>
										</tbody>
									</table>
									<h5><strong>Weergegeven bedragen zijn exclusief BTW</strong></h5>
								</div>
							</div>

						</div>
					</div>

					<div id="endresult" class="tab-pane">

						<h4>Aanneming</h4>
						<table class="table table-striped">

							<thead>
								<tr>
									<th class="col-md-4">&nbsp;</th>
									<th class="col-md-1">Uren</th>
									<th class="col-md-2">Bedrag (excl. BTW)</th>
									<th class="col-md-1">&nbsp;</th>
									<th class="col-md-1">BTW</th>
									<th class="col-md-1">BTW bedrag</th>
									<th class="col-md-2">&nbsp;</th>
								</tr>
							</thead>

							<tbody>
								<?php $header = false; ?>
								@if (!$project->tax_reverse)
								@if (CalculationEndresult::conCalcLaborActivityTax1Amount($project))
								<tr>
									<td class="col-md-4"><?php echo "Arbeidskosten"; $header = true; ?></td>
									<td class="col-md-1">{{ number_format(CalculationEndresult::conCalcLaborActivityTax1($project), 2, ",",".") }}</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								@endif
								@if (CalculationEndresult::conCalcLaborActivityTax2Amount($project))
								<tr>
									<td class="col-md-4"><?php echo !$header ? "Arbeidskosten" : "" ?></td>
									<td class="col-md-1">{{ number_format(CalculationEndresult::conCalcLaborActivityTax2($project), 2, ",",".") }}</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								@endif
								@else
								@if (CalculationEndresult::conCalcLaborActivityTax3Amount($project))
								<tr>
									<td class="col-md-4">Arbeidskosten</td>
									<td class="col-md-1">{{ number_format(CalculationEndresult::conCalcLaborActivityTax3($project), 2, ",",".") }}</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								@endif
								@endif

								<?php $header = false; ?>
								@if (!$project->tax_reverse)
								@if (CalculationEndresult::conCalcMaterialActivityTax1Amount($project))
								<tr>
									<td class="col-md-4"><?php echo "Materiaalkosten"; $header = true; ?></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								@endif
								@if (CalculationEndresult::conCalcMaterialActivityTax2Amount($project))
								<tr>
									<td class="col-md-4"><?php echo !$header ? "Materiaalkosten" : "" ?></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								@endif
								@else
								@if (CalculationEndresult::conCalcMaterialActivityTax3Amount($project))
								<tr>
									<td class="col-md-4">Materiaalkosten</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								@endif
								@endif

								<?php $header = false; ?>
								@if (!$project->tax_reverse)
								@if (CalculationEndresult::conCalcEquipmentActivityTax1Amount($project))
								<tr>
									<td class="col-md-4"><?php echo "Overige kosten"; $header = true; ?></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								@endif
								@if (CalculationEndresult::conCalcEquipmentActivityTax2Amount($project))
								<tr>
									<td class="col-md-4"><?php echo !$header ? "Overige kosten" : "" ?></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								@endif
								@else
								@if (CalculationEndresult::conCalcEquipmentActivityTax3Amount($project))
								<tr>
									<td class="col-md-4">Overige kosten</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								@endif
								@endif

								<tr>
									<td class="col-md-4"><strong>Totaal Aanneming</strong></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalContracting($project), 2, ",",".") }}</strong></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalContractingTax($project), 2, ",",".") }}</strong></td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
							</tbody>
						</table>

						<h4>Onderaanneming</h4>
						<table class="table table-striped">

							<thead>
								<tr>
									<th class="col-md-4">&nbsp;</th>
									<th class="col-md-1">Uren</th>
									<th class="col-md-2">Bedrag (excl. BTW)</th>
									<th class="col-md-1">&nbsp;</th>
									<th class="col-md-1">BTW</th>
									<th class="col-md-1">BTW bedrag</th>
									<th class="col-md-2">&nbsp;</th>
								</tr>
							</thead>

							<tbody>
								<?php $header = false; ?>
								@if (!$project->tax_reverse)
								@if (CalculationEndresult::subconCalcLaborActivityTax1Amount($project))
								<tr>
									<td class="col-md-4"><?php echo "Arbeidskosten"; $header = true; ?></td>
									<td class="col-md-1">{{ number_format(CalculationEndresult::subconCalcLaborActivityTax1($project), 2, ",",".") }}</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								@endif
								@if (CalculationEndresult::subconCalcLaborActivityTax2Amount($project))
								<tr>
									<td class="col-md-4"><?php echo !$header ? "Arbeidskosten" : "" ?></td>
									<td class="col-md-1">{{ number_format(CalculationEndresult::subconCalcLaborActivityTax2($project), 2, ",",".") }}</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								@endif
								@else
								@if (CalculationEndresult::subconCalcLaborActivityTax3($project))
								<tr>
									<td class="col-md-4">Arbeidskosten</td>
									<td class="col-md-1">{{ number_format(CalculationEndresult::subconCalcLaborActivityTax3($project), 2, ",",".") }}</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								@endif
								@endif

								<?php $header = false; ?>
								@if (!$project->tax_reverse)
								@if (CalculationEndresult::subconCalcMaterialActivityTax1Amount($project))
								<tr>
									<td class="col-md-4"><?php echo "Materiaalkosten"; $header = true; ?></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::subconCalcMaterialActivityTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								@endif
								@if (CalculationEndresult::subconCalcMaterialActivityTax2Amount($project))
								<tr>
									<td class="col-md-4"><?php echo !$header ? "Materiaalkosten" : "" ?></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::subconCalcMaterialActivityTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								@endif
								@else
								@if (CalculationEndresult::subconCalcMaterialActivityTax3Amount($project))
								<tr>
									<td class="col-md-4">Materiaalkosten</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								@endif
								@endif

								<?php $header = false; ?>
								@if (!$project->tax_reverse)
								@if (CalculationEndresult::subconCalcEquipmentActivityTax1Amount($project))
								<tr>
									<td class="col-md-4"><?php echo "Overige kosten"; $header = true; ?></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::subconCalcEquipmentActivityTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								@endif
								@if (CalculationEndresult::subconCalcEquipmentActivityTax2Amount($project))
								<tr>
									<td class="col-md-4"><?php echo !$header ? "Overige kosten" : "" ?></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::subconCalcEquipmentActivityTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								@endif
								@else
								@if (CalculationEndresult::subconCalcEquipmentActivityTax3Amount($project))
								<tr>
									<td class="col-md-4">Overige kosten</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</span></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								@endif
								@endif

								<tr>
									<td class="col-md-4"><strong>Totaal Onderaanneming </strong></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalSubcontractingTax($project), 2, ",",".") }}</strong></td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
							</tbody>
						</table>

						<h4>Totalen Offerte</h4>
						<table class="table table-striped">

							<thead>
								<tr>
									<th class="col-md-5">&nbsp;</th>
									<th class="col-md-2">Bedrag (excl. BTW)</th>
									<th class="col-md-1">&nbsp;</th>
									<th class="col-md-1">&nbsp;</th>
									<th class="col-md-1">BTW bedrag</th>
									<th class="col-md-2"><span class="pull-right">Bedrag (incl. BTW)</span></th>
								</tr>
							</thead>


							<tbody>
								<tr>
									<td class="col-md-5"><strong>Calculatief te offreren (excl. BTW)</strong></td>
									<td class="col-md-2"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalProject($project), 2, ",",".") }}</strong></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								@if (!$project->tax_reverse)
								@if (CalculationEndresult::totalContractingTax1($project))
								<tr>
									<td class="col-md-5">BTW bedrag aanneming 21%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::totalContractingTax1($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								@endif
								@if (CalculationEndresult::totalContractingTax2($project))
								<tr>
									<td class="col-md-5">BTW bedrag aanneming 6%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::totalContractingTax2($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								@endif
								@if (CalculationEndresult::totalSubcontractingTax1($project))
								<tr>
									<td class="col-md-5">BTW bedrag onderaanneming 21%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::totalSubcontractingTax1($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								@endif
								@if (CalculationEndresult::totalSubcontractingTax2($project))
								<tr>
									<td class="col-md-5">BTW bedrag onderaanneming 6%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::totalSubcontractingTax2($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								@endif
								@endif
								<tr>
									<td class="col-md-5"><strong>Te offreren BTW bedrag</strong></td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalProjectTax($project), 2, ",",".") }}</strong></td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr>
									<td class="col-md-5"><strong>Calculatief te offreren (Incl. BTW)</strong></td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2"><strong class="pull-right">{{ '&euro; '.number_format(CalculationEndresult::superTotalProject($project), 2, ",",".") }}</strong></td>
								</tr>

							</tbody>

						</table>

					</div>
				</div>

			</div>


		</div>

	</section>

</div>

@stop

<?php } ?>
