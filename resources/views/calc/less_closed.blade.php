<?php

use \Calctool\Models\Project;
use \Calctool\Models\Chapter;
use \Calctool\Models\Activity;
use \Calctool\Models\PartType;
use \Calctool\Models\Part;
use \Calctool\Models\Tax;
use \Calctool\Calculus\LessRegister;
use \Calctool\Calculus\LessOverview;
use \Calctool\Models\ProjectType;
use \Calctool\Models\CalculationLabor;
use \Calctool\Models\CalculationMaterial;
use \Calctool\Models\CalculationEquipment;
use \Calctool\Calculus\LessEndresult;
use \Calctool\Calculus\CalculationRegister;

$common_access_error = false;
$project = Project::find(Route::Input('project_id'));
if (!$project || !$project->isOwner())
	$common_access_error = true;
?>

@extends('layout.master')

@section('title', 'Minderwerk')

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

			@include('calc.wizard', array('page' => 'less'))

			<h2><strong>Minderwerk</strong></h2>

			<div class="tabs nomargin">

				<ul class="nav nav-tabs">
					<li id="tab-calculate">
						<a href="#calculate" data-toggle="tab">
							<i class="fa fa-list"></i> Calculeren
						</a>
					</li>
					<li id="tab-summary">
						<a href="#summary" data-toggle="tab">
							<i class="fa fa-align-justify"></i> Uittrekstaat Minderwerk
						</a>
					</li>
					<li id="tab-endresult">
						<a href="#endresult" data-toggle="tab">
							<i class="fa fa-check-circle-o"></i> Eindresultaat Minderwerk
						</a>
					</li>
				</ul>

				<div class="tab-content">
					<div id="calculate" class="tab-pane">
						<div class="toogle">

							@foreach (Chapter::where('project_id','=', $project->id)->orderBy('created_at')->get() as $chapter)
							<div id="toggle-chapter-{{ $chapter->id }}" class="toggle toggle-chapter">
								<label>{{ $chapter->chapter_name }}</label>
								<div class="toggle-content">

									<div class="toogle">

										<?php
										foreach(Activity::where('chapter_id','=', $chapter->id)->whereNull('detail_id')->where('part_type_id','=',PartType::where('type_name','=','calculation')->first()->id)->orderBy('created_at')->get() as $activity) {
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
													<div class="col-md-10"></div>
													<div class="col-md-2 text-right label label-info"><strong>{{ Part::find($activity->part_id)->part_name=='subcontracting' ? 'Onderaanneming' : 'Aanneming' }}</strong></div>
												</div>
												<div class="row">
													<div class="col-md-2"><h4>Arbeid</h4></div>
													<div class="col-md-1 text-right label label-info"><strong>BTW {{ Tax::find($activity->tax_labor_id)->tax_rate }}%</strong></div>
												</div>
												<table class="table table-striped" data-id="{{ $activity->id }}">
													<thead>
														<tr>
															<th class="col-md-6">Omschrijving</th>
															<th class="col-md-1">&nbsp;</th>
															<th class="col-md-1">Uurtarief</th>
															<th class="col-md-1">Aantal</th>
															<th class="col-md-1">Prijs</th>
															<th class="col-md-1">Minderwerk</th>

														</tr>
													</thead>

													<tbody>
														@foreach (CalculationLabor::where('activity_id','=', $activity->id)->get() as $labor)
														<?php
														//FUCK LELIJK, MAAR DON"T FIX IF IT AIN"T BROKEN
														$rate = $labor->rate;
														if (Part::find($activity->part_id)->part_name == 'contracting') {
															$rate = $project->hour_rate;
														}
														?>
														<tr data-id="{{ $labor->id }}">
															<td class="col-md-6">Arbeidsuren</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">{{ number_format($project->hour_rate, 2,",",".") }}</td>
															<td class="col-md-1">{{ number_format($labor->isless ? $labor->less_amount : $labor->amount, 2, ",",".") }}</td>
															<td class="col-md-1">{{ '&euro; '.number_format(CalculationRegister::calcLaborTotal($rate, $labor->isless ? $labor->less_amount : $labor->amount), 2, ",",".") }}</td>
															<td class="col-md-1"><?php
																$minderw = LessRegister::lessLaborDeltaTotal($labor, $activity, $project);
																if($minderw < 0)
																	echo "<font color=red>&euro; ".number_format($minderw, 2, ",",".")."</font>";
																else
																	echo '&euro; '.number_format($minderw, 2, ",",".");
																?></span>
															</td>
														</tr>
														@endforeach
													</tbody>
												</table>

												<div class="row">
													<div class="col-md-2"><h4>Materiaal</h4></div>
													<div class="col-md-1 text-right label label-info"><strong>BTW {{ Tax::find($activity->tax_material_id)->tax_rate }}%</strong></div>

												</div>

												<table class="table table-striped">
													<thead>
														<tr>
															<th class="col-md-6">Omschrijving</th>
															<th class="col-md-1">Eenheid</th>
															<th class="col-md-1">&euro; / Eenh.</th>
															<th class="col-md-1">Aantal</th>
															<th class="col-md-1">Prijs </th>
															<th class="col-md-1">Minderwerk</th>

														</tr>
													</thead>

													<tbody>
														@foreach (CalculationMaterial::where('activity_id','=', $activity->id)->get() as $material)
														<tr data-id="{{ $material->id }}">
															<td class="col-md-6">{{ $material->material_name }}</td>
															<td class="col-md-1">{{ $material->unit }}</td>
															<td class="col-md-1">{{ number_format($material->isless ? $material->less_rate : $material->rate, 2,",",".") }}</td>
															<td class="col-md-1">{{ number_format($material->isless ? $material->less_amount : $material->amount, 2,",",".") }}</td>
															<td class="col-md-1"><span class="total-incl-tax">
															<?php
																if (Part::find($activity->part_id)->part_name=='contracting') {
																	$profit = $project->profit_calc_contr_mat;
																} else if (Part::find($activity->part_id)->part_name=='subcontracting') {
																	$profit = $project->profit_calc_subcontr_mat;
																}
																echo '&euro; '.number_format(($material->isless ? $material->less_rate * $material->less_amount : $material->rate * $material->amount) *((100+$profit)/100), 2, ",",".");
															?></span>
															</td>
															<td class="col-md-1">
															<?php
																if ($material->isless) {
																	$total = ($material->rate * $material->amount) * ((100+$profit)/100);
																	$less_total = ($material->less_rate * $material->less_amount) * ((100+$profit)/100);
																	if($less_total-$total <0)
																		echo "<font color=red>&euro; ".number_format($less_total-$total, 2, ",",".")."</font>";
																	else
																		echo '&euro; '.number_format($less_total-$total, 2, ",",".");
																} else {
																	echo '&euro; 0,00';
																}
															?>
															</td>
															
														</tr>
														@endforeach
													</tbody>
													<tbody>
														<tr>
															<td class="col-md-6"><strong>Totaal</strong></td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1"><strong>{{ '&euro; '.number_format(LessRegister::lessMaterialTotalProfit($activity->id, $profit_mat), 2, ",",".") }}</td>
															<th class="col-md-1">{{ '&euro; '.number_format(LessRegister::lessMaterialDeltaTotal($activity->id, $profit_mat), 2, ",",".") }}</th>

														</tr>
													</tbody>
												</table>

												<div class="row">
													<div class="col-md-2"><h4>Overig</h4></div>
													<div class="col-md-1 text-right label label-info"><strong>BTW {{ Tax::find($activity->tax_equipment_id)->tax_rate }}%</strong></div>
												</div>

												<table class="table table-striped">
													<thead>
														<tr>
															<th class="col-md-6">Omschrijving</th>
															<th class="col-md-1">Eenheid</th>
															<th class="col-md-1">&euro; / Eenh.</th>
															<th class="col-md-1">Aantal</th>
															<th class="col-md-1">Prijs </th>
															<th class="col-md-1">Minderwerk</th>

														</tr>
													</thead>

													<tbody>
														@foreach (CalculationEquipment::where('activity_id','=', $activity->id)->get() as $equipment)
														<tr data-id="{{ $equipment->id }}">
															<td class="col-md-6">{{ $equipment->equipment_name }}</td>
															<td class="col-md-1">{{ $equipment->unit }}</td>
															<td class="col-md-1">{{ number_format($equipment->isless ? $equipment->less_rate : $equipment->rate, 2,",",".") }}</td>
															<td class="col-md-1">{{ number_format($equipment->isless ? $equipment->less_amount : $equipment->amount, 2,",",".") }}</td>
															<td class="col-md-1"><span class="total-incl-tax">
															<?php
																if (Part::find($activity->part_id)->part_name=='contracting') {
																	$profit = $project->profit_calc_contr_equip;
																} else if (Part::find($activity->part_id)->part_name=='subcontracting') {
																	$profit = $project->profit_calc_subcontr_equip;
																}
																echo '&euro; '.number_format(($equipment->isless ? $equipment->less_rate * $equipment->less_amount : $equipment->rate * $equipment->amount) *((100+$profit)/100), 2, ",",".");
															?></span></td>
															<td class="col-md-1">
															<?php
																if ($equipment->isless) {
																	$total = ($equipment->rate * $equipment->amount) * ((100+$profit)/100);
																	$less_total = ($equipment->less_rate * $equipment->less_amount) * ((100+$profit)/100);
																	if($less_total-$total <0)
																		echo "<font color=red>&euro; ".number_format($less_total-$total, 2, ",",".")."</font>";
																	else
																		echo '&euro; '.number_format($less_total-$total, 2, ",",".");
																} else {
																	echo '&euro; 0,00';
																}
															?>
															</td>
															
														</tr>
														@endforeach
													</tbody>
													<tbody>
														<tr>
															<td class="col-md-6"><strong>Totaal</strong></td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1"><strong>{{ '&euro; '.number_format(LessRegister::lessEquipmentTotalProfit($activity->id, $profit_equip), 2, ",",".") }}</td>
															<th class="col-md-1">{{ '&euro; '.number_format(LessRegister::lessEquipmentDeltaTotal($activity->id, $profit_equip), 2, ",",".") }}</th>

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

					<div id="summary" class="tab-pane">
						<div class="toogle">

							<div class="toggle toggle-chapter active">
								<label>Aanneming</label>
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
												<th class="col-md-1"><span class="pull-right">Totaal</th>
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
												<td class="col-md-1"><span class="pull-right">{{ number_format(LessOverview::laborTotal($activity, $project), 2, ",",".") }}</td>
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

							<div class="toggle toggle-chapter active">
								<label>Onderaanneming</label>
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
												<th class="col-md-1"><span class="pull-right">Totaal</th>
											</tr>
										</thead>

										<tbody>
											@foreach (Chapter::where('project_id','=', $project->id)->orderBy('created_at')->get() as $chapter)
											<?php $i = 0; ?>
											@foreach (Activity::where('chapter_id','=', $chapter->id)->whereNull('detail_id')->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->where('part_type_id','=',PartType::where('type_name','=','calculation')->first()->id)->orderBy('created_at')->get() as $activity)
											<?php $i++; ?>
											<tr>
												<td class="col-md-3"><strong>{{ $i==1 ? $chapter->chapter_name : '' }}</strong></td>
												<td class="col-md-4">{{ $activity->activity_name }}</td>
												<td class="col-md-1"><span class="pull-right">{{ number_format(LessOverview::laborTotal($activity, $project), 2, ",",".") }}</td>
												<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(LessOverview::laborActivity($activity), 2, ",",".") }}</span></td>
												<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(LessOverview::materialActivityProfit($activity, $project->profit_calc_subcontr_mat), 2, ",",".") }}</span></td>
												<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::equipmentActivityProfit($activity, $project->profit_calc_subcontr_equip), 2, ",",".") }}</span></td>
												<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::activityTotalProfit($activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip, $project), 2, ",",".") }} </td>
												<td class="col-md-1 text-center"></td>
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

							<div class="toggle toggle-chapter active">
								<label>Totalen project</label>
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
												<th class="col-md-1"><span class="pull-right">Totaal</span></th>
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
					</div>

					<div id="endresult" class="tab-pane">

						<h4>Aanneming</h4>
						<table class="table table-striped">
							<thead>
								<tr>
									<th class="col-md-4">&nbsp;</th>
									<th class="col-md-1">Manuren</th>
									<th class="col-md-2">Bedrag (excl. BTW)</th>
									<th class="col-md-1">&nbsp;</th>
									<th class="col-md-1">BTW</th>
									<th class="col-md-2">BTW bedrag</th>
									<th class="col-md-1">&nbsp;</th>
								</tr>
							</thead>

							<tbody>
								@if (!$project->tax_reverse)
								<tr>
									<td class="col-md-4">Arbeidskosten</td>
									<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax1($project), 2, ",",".") }}</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr>
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax2($project), 2, ",",".") }}</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								@else
								<tr>
									<td class="col-md-4">Arbeidskosten</td>
									<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax3($project), 2, ",",".") }}</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								@endif

								@if (!$project->tax_reverse)
								<tr>
									<td class="col-md-4">Materiaalkosten</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr>
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								@else
								<tr>
									<td class="col-md-4">Materiaalkosten</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								@endif

								@if (!$project->tax_reverse)
								<tr>
									<td class="col-md-4">Overige kosten</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr>
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								@else
								<tr>
									<td class="col-md-4">Overige kosten</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								@endif

								<tr>
									<td class="col-md-4"><strong>Totaal Aanneming </strong></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2"><strong>{{ '&euro; '.number_format(LessEndresult::totalContracting($project), 2, ",",".") }}</strong></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2"><strong>{{ '&euro; '.number_format(LessEndresult::totalContractingTax($project), 2, ",",".") }}</strong></td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
							</tbody>
						</table>

						<h4>Onderaanneming</h4>
						<table class="table table-striped">
							<thead>
								<tr>
									<th class="col-md-4">&nbsp;</th>
									<th class="col-md-1">Manuren</th>
									<th class="col-md-2">Bedrag (excl. BTW)</th>
									<th class="col-md-1">&nbsp;</th>
									<th class="col-md-1">BTW</th>
									<th class="col-md-2">BTW bedrag</th>
									<th class="col-md-1">&nbsp;</th>
								</tr>
							</thead>

							<tbody>
								@if (!$project->tax_reverse)
								<tr>
									<td class="col-md-4">Arbeidskosten</td>
									<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax1($project), 2, ",",".") }}</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr>
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax2($project), 2, ",",".") }}</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								@else
								<tr>
									<td class="col-md-4">Arbeidskosten</td>
									<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax3($project), 2, ",",".") }}</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								@endif

								@if (!$project->tax_reverse)
								<tr>
									<td class="col-md-4">Materiaalkosten</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::subconCalcMaterialActivityTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr>
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::subconCalcMaterialActivityTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								@else
								<tr>
									<td class="col-md-4">Materiaalkosten</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								@endif

								@if (!$project->tax_reverse)
								<tr>
									<td class="col-md-4">Overige kosten</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::subconCalcEquipmentActivityTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr>
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::subconCalcEquipmentActivityTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								@else
								<tr>
									<td class="col-md-4">Overige kosten</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								@endif

								<tr>
									<td class="col-md-4"><strong>Totaal Onderaanneming </strong></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2"><strong>{{ '&euro; '.number_format(LessEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2"><strong>{{ '&euro; '.number_format(LessEndresult::totalSubcontractingTax($project), 2, ",",".") }}</strong></td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
							</tbody>
						</table>

						<h4>Totalen Minderwerk</h4>
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
									<td class="col-md-5">Calculatief in mindering te brengen (excl. BTW)</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::totalProject($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								@if (!$project->tax_reverse)
								<tr>
									<td class="col-md-5">BTW bedrag aanneming 21%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::totalContractingTax1($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr>
									<td class="col-md-5">BTW bedrag aanneming 6%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::totalContractingTax2($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr>
									<td class="col-md-5">BTW bedrag onderaanneming 21%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::totalSubcontractingTax1($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr>
									<td class="col-md-5">BTW bedrag onderaanneming 6%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::totalSubcontractingTax2($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								@endif
								<tr>
									<td class="col-md-5">In mindering te brengen BTW bedrag</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::totalProjectTax($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr>
									<td class="col-md-5"><strong>Calculatief in mindering te brengen (Incl. BTW)</strong></td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2"><strong class="pull-right">{{ '&euro; '.number_format(LessEndresult::superTotalProject($project), 2, ",",".") }}</strong></td>

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
