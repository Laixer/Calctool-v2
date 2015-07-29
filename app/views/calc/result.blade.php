<?php
$project = Project::find(Route::Input('project_id'));
?>

@extends('layout.master')

@section('content')

<script type="text/javascript">
$(document).ready(function() {

	$('#tab-result').click(function(e){
		sessionStorage.toggleTabRes{{Auth::user()->id}} = 'result';
	});
	$('#tab-budget').click(function(e){
		sessionStorage.toggleTabRes{{Auth::user()->id}} = 'budget';
	});
	$('#tab-hour_overview').click(function(e){
		sessionStorage.toggleTabRes{{Auth::user()->id}} = 'hour_overview';
	});

	if (sessionStorage.toggleTabRes{{Auth::user()->id}}){
		$toggleOpenTab = sessionStorage.toggleTabRes{{Auth::user()->id}};
		$('#tab-'+$toggleOpenTab).addClass('active');
		$('#'+$toggleOpenTab).addClass('active');
	} else {
		sessionStorage.toggleTabRes{{Auth::user()->id}} = 'result';
		$('#tab-result').addClass('active');
		$('#result').addClass('active');
	}

});
</script>
<div id="wrapper">

	<section class="container fix-footer-bottom">

		@include('calc.wizard', array('page' => 'result'))

			<h2><strong>Resultaat Project</strong> {{$project->project_name}}</h2>

			<div class="tabs nomargin">

				<!-- tabs -->
				<ul class="nav nav-tabs">
					<li id="tab-result">
						<a href="#result" data-toggle="tab">
							<i class="fa fa-list-ol"></i> Projectresultaat
						</a>
					</li>
					<li id="tab-hour_overview">
						<a href="#hour_overview" data-toggle="tab">
							<i class="fa fa-sort-amount-desc"></i> Resultaat urenregistratie
						</a>
					</li>
					<li id="tab-budget">
						<a href="#budget" data-toggle="tab">
							<i class="fa fa-sort-amount-desc"></i> Winst / Verlies
						</a>
					</li>
				</ul>

				<!-- tabs content -->
				<div class="tab-content">
					<div id="result" class="tab-pane">

						<h4>Aanneming</h4>
						<table class="table table-striped">
							<?# -- table head -- ?>
							<thead>
								<tr>
									<th class="col-md-4">&nbsp;</th>
									<th class="col-md-1">Calculatie</th>
									<th class="col-md-1">Meerwerk</th>
									<th class="col-md-1">Minderwerk</th>
									<th class="col-md-1">Balans</th>
									<th class="col-md-1">BTW</th>
									<th class="col-md-1">BTW bedrag</th>
									<th class="col-md-2">&nbsp;</th>
								</tr>
							</thead>

							<!-- table items -->
							<tbody>
								<tr><!-- item -->
									<td class="col-md-4">Arbeidskosten</td>
									<td class="col-md-1">{{ '&euro; '.number_format(EstimateEndresult::conCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax1($project), 2, ",",".") }}</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(EstimateEndresult::conCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax2($project), 2, ",",".") }}</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(EstimateEndresult::conCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax3($project), 2, ",",".") }}</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>

								<tr><!-- item -->
									<td class="col-md-4">Materiaalkosten</td>
									<td class="col-md-1">{{ '&euro; '.number_format(EstimateEndresult::conCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax1($project), 2, ",",".") }}</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(EstimateEndresult::conCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax2($project), 2, ",",".") }}</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(EstimateEndresult::conCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax3($project), 2, ",",".") }}</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>

								<tr><!-- item -->
									<td class="col-md-4">Materieelkosten</td>
									<td class="col-md-1">{{ '&euro; '.number_format(EstimateEndresult::conCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax1($project), 2, ",",".") }}</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(EstimateEndresult::conCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax2($project), 2, ",",".") }}</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(EstimateEndresult::conCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax3($project), 2, ",",".") }}</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>

								<tr><!-- item -->
									<td class="col-md-4"><strong>Totaal Aanneming </strong></td>
									<td class="col-md-1"><strong>{{ '&euro; '.number_format(EstimateEndresult::totalContracting($project), 2, ",",".") }}</strong></td>
									<td class="col-md-1"><strong>{{ '&euro; '.number_format(MoreEndresult::totalContracting($project), 2, ",",".") }}</strong></td>
									<td class="col-md-1"><strong>{{ '&euro; '.number_format(LessEndresult::totalContracting($project), 2, ",",".") }}</strong></td>
									<td class="col-md-1"><strong>{{ '&euro; '.number_format(ResultEndresult::totalContracting($project), 2, ",",".") }}</strong></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1"><strong>{{ '&euro; '.number_format(ResultEndresult::totalContractingTax($project), 2, ",",".") }}</strong></td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
							</tbody>
						</table>

						<h4>Onderaanneming</h4>
						<table class="table table-striped">
							<?# -- table head -- ?>
							<thead>
								<tr>
									<th class="col-md-4">&nbsp;</th>
									<th class="col-md-1">Calculatie</th>
									<th class="col-md-1">Meerwerk</th>
									<th class="col-md-1">Minderwerk</th>
									<th class="col-md-1">Balans</th>
									<th class="col-md-1">BTW</th>
									<th class="col-md-1">BTW bedrag</th>
									<th class="col-md-2">&nbsp;</th>
								</tr>
							</thead>

							<!-- table items -->
							<tbody>
								<tr><!-- item -->
									<td class="col-md-4">Arbeidskosten</td>
									<td class="col-md-1">{{ '&euro; '.number_format(EstimateEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconLaborBalanceTax1($project), 2, ",",".") }}</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconLaborBalanceTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(EstimateEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconLaborBalanceTax2($project), 2, ",",".") }}</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconLaborBalanceTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(EstimateEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconLaborBalanceTax3($project), 2, ",",".") }}</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>

								<tr><!-- item -->
									<td class="col-md-4">Materiaalkosten</td>
									<td class="col-md-1">{{ '&euro; '.number_format(EstimateEndresult::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconMaterialBalanceTax1($project), 2, ",",".") }}</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconMaterialBalanceTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(EstimateEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconMaterialBalanceTax2($project), 2, ",",".") }}</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconMaterialBalanceTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(EstimateEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconMaterialBalanceTax3($project), 2, ",",".") }}</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>

								<tr><!-- item -->
									<td class="col-md-4">Materieelkosten</td>
									<td class="col-md-1">{{ '&euro; '.number_format(EstimateEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconEquipmentBalanceTax1($project), 2, ",",".") }}</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconEquipmentBalanceTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(EstimateEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconEquipmentBalanceTax2($project), 2, ",",".") }}</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconEquipmentBalanceTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(EstimateEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconEquipmentBalanceTax3($project), 2, ",",".") }}</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>

								<tr><!-- item -->
									<td class="col-md-4"><strong>Totaal Onderaanneming </strong></td>
									<td class="col-md-1"><strong>{{ '&euro; '.number_format(EstimateEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
									<td class="col-md-1"><strong>{{ '&euro; '.number_format(MoreEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
									<td class="col-md-1"><strong>{{ '&euro; '.number_format(LessEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
									<td class="col-md-1"><strong>{{ '&euro; '.number_format(ResultEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1"><strong>{{ '&euro; '.number_format(ResultEndresult::totalSubcontractingTax($project), 2, ",",".") }}</strong></td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
							</tbody>
						</table>

						<h4>Cumulatieven</h4>
						<table class="table table-striped">
							<?# -- table head -- ?>
							<thead>
								<tr>
									<th class="col-md-4">&nbsp;</th>
									<th class="col-md-3">&nbsp;</th>
									<th class="col-md-2">Bedrag (excl. BTW)</th>
									<th class="col-md-1">BTW bedrag</th>
									<th class="col-md-2"><span class="pull-right">Bedrag (incl. BTW)</span></th>
								</tr>
							</thead>

							<!-- table items -->
							<tbody>
								<tr><!-- item -->
									<td class="col-md-4">Cumulatief project (excl. BTW)</td>
									<td class="col-md-3">&nbsp;</td>
									<td class="col-md-2"><strong>{{ '&euro; '.number_format(ResultEndresult::totalProject($project), 2, ",",".") }}</strong></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">BTW bedrag aanneming belast met 21%</td>
									<th class="col-md-3">&nbsp;</th>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::totalContractingTax1($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">BTW bedrag aanneming belast met 6%</td>
									<th class="col-md-3">&nbsp;</th>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::totalContractingTax2($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">BTW bedrag onderaanneming belast met 21%</td>
									<th class="col-md-3">&nbsp;</th>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::totalSubcontractingTax1($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">BTW bedrag onderaanneming belast met 6%</td>
									<th class="col-md-3">&nbsp;</th>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::totalSubcontractingTax2($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">Cumulatief BTW bedrag</td>
									<th class="col-md-3">&nbsp;</th>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1"><strong>{{ '&euro; '.number_format(ResultEndresult::totalProjectTax($project), 2, ",",".") }}</strong></td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4"><strong>Cumulatief project (Incl. BTW)</strong></td>
									<th class="col-md-3">&nbsp;</th>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2"><strong><span class="pull-right">{{ '&euro; '.number_format(ResultEndresult::superTotalProject($project), 2, ",",".") }}</strong></span></td>
								</tr>
							</tbody>
						</table>
					</div>

						<div id="hour_overview" class="tab-pane">
							<div class="toogle">
								<div class="toggle active">
									<label>Aanneming</label>
									<div class="toggle-content">
									<table class="table table-striped">
										<?# -- table head -- ?>
										<thead>
											<tr>
												<th class="col-md-3">&nbsp;</th>
												<th class="col-md-4">&nbsp;</th>
												<th class="col-md-1"><span class="pull-right">Gecalculeerd</span></th>
												<th class="col-md-1"><span class="pull-right">Minderwerk</span></th>
												<th class="col-md-1"><span class="pull-right">Geboekt</span></th>
												<th class="col-md-1"><span class="pull-right">Winst/verlies</span></th>
												<th class="col-md-1"><span class="pull-right">Kosten</span></th>
											</tr>
										</thead>

										<tbody>
											@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
											@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_type_id','=',PartType::where('type_name','=','calculation')->first()->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->whereNull('detail_id')->get() as $activity)
											<tr>
												<td class="col-md-3"><strong>{{ $chapter->chapter_name }}</strong></td>
												<td class="col-md-4">{{ $activity->activity_name }}</td>
												<td class="col-md-1"><span class="pull-right">{{ number_format(TimesheetOverview::calcTotalAmount($activity->id), 2,",","."); }}</span></td>
<!-- TODO Totaal moet gequeryd worden -->
												<td class="col-md-1"><span class="pull-right">volgt</span></td>
												<td class="col-md-1"><span class="pull-right">{{ number_format(Timesheet::where('activity_id','=',$activity->id)->sum('register_hour'), 2,",","."); }}</span></td>
												<td class="col-md-1"><span class="pull-right">{{ number_format(TimesheetOverview::calcTotalAmount($activity->id)-Timesheet::where('activity_id','=',$activity->id)->sum('register_hour'), 2,",","."); }}</span></td>
												<td class="col-md-1"><span class="pull-right">{{ number_format((TimesheetOverview::calcTotalAmount($activity->id)-Timesheet::where('activity_id','=',$activity->id)->sum('register_hour'))*$project->hour_rate, 2,",","."); }}</span></td>
											</tr>
											@endforeach
											@endforeach
											<tr>
												<th class="col-md-3"><strong>Totaal Aanneming</strong></th>
												<th class="col-md-4">&nbsp;</th>
												<td class="col-md-1"><strong><span class="pull-right">{{ number_format(TimesheetOverview::calcTotalCalculation($project), 2, ",",".") }}</span></strong></td>
<!-- TODO Totaal moet gequeryd worden -->
												<td class="col-md-1"><strong><span class="pull-right">volgt</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ number_format(TimesheetOverview::calcTotalTimesheet($project), 2, ",",".") }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ number_format(TimesheetOverview::calcTotalCalculation($project)-TimesheetOverview::calcTotalTimesheet($project), 2, ",",".") }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format((TimesheetOverview::calcTotalCalculation($project)-TimesheetOverview::calcTotalTimesheet($project))*$project->hour_rate, 2, ",",".") }}</span></strong></td>
											</tr>
										</tbody>
									</table>
									</div>
								</div>

								<div class="toggle active">
									<label>Stelposten</label>
									<div class="toggle-content">
									<table class="table table-striped">
										<?# -- table head -- ?>
										<thead>
											<tr>
												<th class="col-md-3">&nbsp;</th>
												<th class="col-md-4">&nbsp;</th>
												<th class="col-md-1"><span class="pull-right">Gecalculeerd</span></th>
												<th class="col-md-1"><span class="pull-right">Gesteld&nbsp;<a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit zijn de gestelde uren van de calculatie, zoals die op de factuur vermeld gaan worden." href="#"><i class="fa fa-info-circle"></i></a></span></th>
												<th class="col-md-1"><span class="pull-right">Geboekt</span></th>
												<th class="col-md-1"><span class="pull-right">Winst/verlies</span></th>
												<th class="col-md-1"><span class="pull-right">Kosten</span></th>
											</tr>
										</thead>

										<tbody>
											@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
											@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_type_id','=',PartType::where('type_name','=','estimate')->first()->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->get() as $activity)
											<tr>
												<td class="col-md-3"><strong>{{ $chapter->chapter_name }}</strong></td>
												<td class="col-md-4">{{ $activity->activity_name }}</td>
												<td class="col-md-1"><span class="pull-right">{{ number_format(TimesheetOverview::estimTotalAmount($activity->id), 2,",","."); }}</span></td>
<!-- TODO Totaal moet gequeyd worden -->
												<td class="col-md-1"><span class="pull-right">Volgt</span></td>
												<td class="col-md-1"><span class="pull-right">{{ number_format(Timesheet::where('activity_id','=',$activity->id)->sum('register_hour'), 2,",","."); }}</span></td>
												<td class="col-md-1"><span class="pull-right">{{ number_format(TimesheetOverview::estimTotalAmount($activity->id)-Timesheet::where('activity_id','=',$activity->id)->sum('register_hour'), 2,",","."); }}</span></td>
												<td class="col-md-1"><span class="pull-right">{{ number_format((TimesheetOverview::estimTotalAmount($activity->id)-Timesheet::where('activity_id','=',$activity->id)->sum('register_hour'))*$project->hour_rate, 2,",","."); }}</span></td>
											</tr>
											@endforeach
											@endforeach
											<tr>
												<th class="col-md-3"><strong>Totaal Stelposten</strong></th>
												<th class="col-md-4">&nbsp;</th>
												<td class="col-md-1"><strong><span class="pull-right">{{ number_format(TimesheetOverview::estimTotalCalculation($project), 2, ",",".") }}</span></strong></td>
<!-- TODO Totaal moet gequeryd worden -->
												<td class="col-md-1"><strong><span class="pull-right">Volgt</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ number_format(TimesheetOverview::estimTotalTimesheet($project), 2, ",",".") }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ number_format(TimesheetOverview::estimTotalCalculation($project)-TimesheetOverview::estimTotalTimesheet($project), 2, ",",".") }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format((TimesheetOverview::estimTotalCalculation($project)-TimesheetOverview::estimTotalTimesheet($project))*$project->hour_rate, 2, ",",".") }}</span></strong></td>
											</tr>
										</tbody>
									</table>
									</div>
								</div>
								<div class="toggle active">
									<label>Meerwerk aanneming</label>
									<div class="toggle-content">
									<table class="table table-striped">
										<?# -- table head -- ?>
										<thead>
											<tr>
												<th class="col-md-3">&nbsp;</th>
												<th class="col-md-4">&nbsp;</th>
												<th class="col-md-1"><span class="pull-right">Opgegeven&nbsp;<a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit zijn de (mondeling) opgegeven uren die als prijsopgaaf kunnen dienen naar de klant. Wordt de urenregistratie bijgehouden dan is die bindend." href="#"><i class="fa fa-info-circle"></i></a></span></th>
												<th class="col-md-1"><span class="pull-right">&nbsp;</span></th>
												<th class="col-md-1"><span class="pull-right">Geboekt</span></th>
												<th class="col-md-1"><span class="pull-right">&nbsp;</span></th>
												<th class="col-md-1"><span class="pull-right">&nbsp;</span></th>
											</tr>
										</thead>

										<tbody>
											@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
											@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_type_id','=',PartType::where('type_name','=','calculation')->first()->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->where('detail_id','=',Detail::where('detail_name','=','more')->first()->id)->get() as $activity)
											<tr>
												<td class="col-md-3"><strong>{{ $chapter->chapter_name }}</strong></td>
												<td class="col-md-4">{{ $activity->activity_name }}</td>
<!-- TODO Totaal moet gequaryd worden -->
												<td class="col-md-1"><span class="pull-right">volgt</span></td>
												<td class="col-md-1"><span class="pull-right">&nbsp;</span></td>
												<td class="col-md-1"><span class="pull-right">{{ number_format(Timesheet::where('activity_id','=',$activity->id)->where('timesheet_kind_id','=',TimesheetKind::where('kind_name','=','meerwerk')->first()->id)->sum('register_hour'), 2,",","."); }}</span></td>
												<td class="col-md-1"><span class="pull-right">&nbsp;</span></td>
												<td class="col-md-1"><span class="pull-right">&nbsp;</span></td>
											</tr>
											@endforeach
											@endforeach
											<tr>
												<td class="col-md-3"><strong>Totaal Meerwerk</strong></td>
												<td class="col-md-4">&nbsp;</td>
<!-- TODO Totaal moet gequaryd worden -->
												<td class="col-md-1"><strong><span class="pull-right">volgt</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">&nbsp;</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ number_format(TimesheetOverview::estimTotalTimesheet($project), 2, ",",".") }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">&nbsp;</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">&nbsp;</span></strong></td>
											</tr>
										</tbody>
									</table>
									</div>
								</div>

							</div>
						</div>

						<div id="budget" class="tab-pane">

							<table class="table table-striped">
								<?# -- table head -- ?>
								<thead>
									<tr>
										<th class="col-md-2">&nbsp;</th>
										<th class="col-md-2">Balans project</th>
										<th class="col-md-3">Totaalkosten urenregistratie</th>
										<th class="col-md-3">Totaalkosten inkoopfacturen</th>
										<th class="col-md-2">Winst / Verlies project</th>
									</tr>
								</thead>

								<!-- table items -->
								<tbody>
									<tr><!-- item -->
										<td class="col-md-2"><strong>Aanneming</strong></td>
										<td class="col-md-2">{{ '&euro; '.number_format(ResultEndresult::totalContracting($project), 2, ",",".") }}</td>
										<td class="col-md-3">{{ '&euro; '.number_format(ResultEndresult::totalTimesheet($project), 2, ",",".") }}</td>
										<td class="col-md-3">{{ '&euro; '.number_format(ResultEndresult::totalContractingPurchase($project), 2, ",",".") }}</td>
										<td class="col-md-2">{{ '&euro; '.number_format(ResultEndresult::totalContractingBudget($project), 2, ",",".") }}</td>
									</tr>
									<tr><!-- item -->
										<td class="col-md-2"><strong>Onderaanneming</strong></td>
										<td class="col-md-2">{{ '&euro; '.number_format(ResultEndresult::totalSubcontracting($project), 2, ",",".") }}</td>
										<td class="col-md-3">-</td>
										<td class="col-md-3">{{ '&euro; '.number_format(ResultEndresult::totalSubcontractingPurchase($project), 2, ",",".") }}</td>
										<td class="col-md-2">{{ '&euro; '.number_format(ResultEndresult::totalSubcontractingBudget($project), 2, ",",".") }}</td>
									</tr>
								</tbody>
							</table>

						</div>

				</div>

			</div>


		</div>

	</section>

</div>
<!-- /WRAPPER -->
@stop
