<?php
$project = Project::find(Route::Input('project_id'));
$relation = Relation::find($project->client_id);
$relation_self = Relation::find(Auth::user()->self_id);
//$iban_self = Iban::find($relation_self->id);
$contact_self = Contact::where('relation_id','=',$relation_self->id)
?>

@extends('layout.master')

@section('content')
<?# -- WRAPPER -- ?>
<script type="text/javascript">
	$(document).ready(function() {
		$.fn.editable.defaults.mode = 'inline';
		$('#description').editable({
			title: 'Omschrijving op de offerte'
		});
		$('#closure').editable({
			title: 'Voetnoot op de offerte'
		});
		$('#starttime').editable({
			value: 3,
			source: [
				{value: 1, text: '1 dag'},
				{value: 2, text: '2 dagen'},
				{value: 3, text: '1 week'}
			]
		});
		$('#endtime').editable({
			value: 5,
			source: [
				{value: 1, text: '1 dag'},
				{value: 2, text: '2 dagen'},
				{value: 3, text: '1 week'},
				{value: 4, text: '2 weken'},
				{value: 5, text: '1 maand'}
			]
		});
	});
</script>
<div id="wrapper">

	<section class="container printable fix-footer-bottom">

		<div class="col-md-12">

		<div class="wizard">
			<a href="/"> Home</a>
			<a href="/project-{{ $project->id }}/edit">Project</a>
			<a href="/calculation/project-{{ $project->id }}">Calculatie</a>
			<a href="/offer/project-{{ $project->id }}" class="current">Offerte</a>
			<a href="/estimate/project-{{ $project->id }}">Stelpost</a>
			<a href="/less/project-{{ $project->id }}">Minderwerk</a>
			<a href="/more/project-{{ $project->id }}">Meerwerk</a>
			<a href="/invoice/project-{{ $project->id }}">Factuur</a>
			<a href="/result/project-{{ $project->id }}">Resultaat</a>
		</div>

		<hr />

	<div class="pull-right">
		<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Opties</a>
	</div>

	<!-- modal dialog -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">

				<div class="modal-header"><!-- modal header -->
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Offerte opties</h4>
				</div><!-- /modal header -->

				<!-- modal body -->
				<div class="modal-body">
				<div class="row">
					<div class="col-sm-3">
						<span><strong>BTW zichtbaar</strong></span>
						<span><strong>BTW zichtbaar</strong></span>
						<span><strong>BTW zichtbaar</strong></span>
						<span><strong>BTW zichtbaar</strong></span>
						<span><strong>BTW zichtbaar</strong></span>
						<span><strong>BTW zichtbaar</strong></span>
					</div>

					<div class="col-sm-6">
						<div class="checkbox">
  <label>
    <input type="checkbox" data-toggle="toggle">
    Option one is enabled
  </label>
</div>
					</div>
					</div>
				</div>
				<!-- /modal body -->

				<div class="modal-footer"><!-- modal footer -->
					<button class="btn btn-default" data-dismiss="modal">Close</button> <button class="btn btn-primary">Save changes</button>
				</div><!-- /modal footer -->

			</div>
		</div>
	</div>


	<h2><strong>Offerte</strong></h2>

			<div class="white-row">

				<div class="row">

					<div class="col-sm-6">
						<img class="img-responsive" src="/images/logo2.png" style="height: 75px;" alt="" />
					</div>

					<div class="col-sm-6 text-right">
						<p>
							#{{ sprintf("%06d", $project->id) }} &bull; <strong>{{ date("j M Y") }}</strong>
							<br />
							{{ $project->project_name }}
						</p>
					</div>

				</div>

				<hr class="margin-top10 margin-bottom10" /><!-- separator -->

				<!-- DETAILS -->
				<div class="row">

					<div class="col-sm-6">

						<h4><strong>Klantgegevens</strong></h4>
						<ul class="list-unstyled">
							<li><strong>Naam:</strong> {{ $relation->company_name }}</li>
							<li><strong>Voornaam:</strong> Doe</li>
							<li>{{ $relation->address_street . ' ' . $relation->address_number }}<br /> {{ $relation->address_postal . ', ' . $relation->address_city }}</li>
							<li><strong>Land:</strong> U.S.A.</li>
						</ul>

					</div>

					<div class="col-sm-2"></div>

					<div class="col-sm-4">

						<h4><strong>Opdrachtgever</strong></h4>
						<ul class="list-unstyled">
							<li><strong>Bedrijfsnaam:</strong> {{ $relation_self->company_name }}</li>
							<li><strong>Adres:</strong> {{ $relation_self->address_street . ' ' . $relation_self->address_number }}</li>
							<li style="margin-left: 48px;">{{ $relation_self->address_postal . ', ' . $relation_self->address_city }}</li>
							<li><strong>Telefoon:</strong> {{ $relation_self->phone }}</li>
							<li><strong>Email:</strong> {{ $relation_self->email }}</li>
							<li><strong>KVK:</strong>{{ $relation_self->kvk }}</li>
						</ul>

						<h4><strong>Offerte gegevens</strong></h4>
						<ul class="list-unstyled">
							<li><strong>Offertedatum:</strong> {{ date("j M Y") }}</li>
							<li><strong>Offertenummer:</strong> #{{ sprintf("%06d", $project->id) }}</li>
						</ul>

					</div>

				</div>
				<!-- /DETAILS -->

				<!--<div class="panel-body">-->

					<p><a id="description" href="javascript:void(0);" style="border-bottom: 0" data-type="textarea">Geef hier een omschrijving voor op de offerte</a></p>

					<h4>Aanneming</h4>
					<table class="table table-striped">
						<?# -- table head -- ?>
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

						<!-- table items -->
						<tbody>
							<tr><!-- item -->
								<td class="col-md-4">Arbeidskosten</td>
								<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax1($project), 2, ",",".") }}</td>
								<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
								<td class="col-md-1">&nbsp;</td>
								<td class="col-md-1">21%</td>
								<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax1AmountTax($project), 2, ",",".") }}</td>
								<td class="col-md-1">&nbsp;</td>
							</tr>
							<tr><!-- item -->
								<td class="col-md-4">&nbsp;</td>
								<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax2($project), 2, ",",".") }}</td>
								<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
								<td class="col-md-1">&nbsp;</td>
								<td class="col-md-1">6%</td>
								<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax2AmountTax($project), 2, ",",".") }}</td>
								<td class="col-md-1">&nbsp;</td>
							</tr>
							<tr><!-- item -->
								<td class="col-md-4">&nbsp;</td>
								<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax3($project), 2, ",",".") }}</td>
								<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
								<td class="col-md-1">&nbsp;</td>
								<td class="col-md-1">0%</td>
								<td class="col-md-2">&nbsp;</td>
								<td class="col-md-1">&nbsp;</td>
							</tr>

							<tr><!-- item -->
								<td class="col-md-4">Materiaalkosten</td>
								<td class="col-md-1">&nbsp;</td>
								<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
								<td class="col-md-1">&nbsp;</td>
								<td class="col-md-1">21%</td>
								<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax1AmountTax($project), 2, ",",".") }}</td>
								<td class="col-md-1">&nbsp;</td>
							</tr>
							<tr><!-- item -->
								<td class="col-md-4">&nbsp;</td>
								<td class="col-md-1">&nbsp;</td>
								<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
								<td class="col-md-1">&nbsp;</td>
								<td class="col-md-1">6%</td>
								<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax2AmountTax($project), 2, ",",".") }}</td>
								<td class="col-md-1">&nbsp;</td>
							</tr>
							<tr><!-- item -->
								<td class="col-md-4">&nbsp;</td>
								<td class="col-md-1">&nbsp;</td>
								<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
								<td class="col-md-1">&nbsp;</td>
								<td class="col-md-1">0%</td>
								<td class="col-md-2">&nbsp;</td>
								<td class="col-md-1">&nbsp;</td>
							</tr>

							<tr><!-- item -->
								<td class="col-md-4">Materieelkosten</td>
								<td class="col-md-1">&nbsp;</td>
								<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
								<td class="col-md-1">&nbsp;</td>
								<td class="col-md-1">21%</td>
								<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax1AmountTax($project), 2, ",",".") }}</td>
								<td class="col-md-1">&nbsp;</td>
							</tr>
							<tr><!-- item -->
								<td class="col-md-4">&nbsp;</td>
								<td class="col-md-1">&nbsp;</td>
								<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
								<td class="col-md-1">&nbsp;</td>
								<td class="col-md-1">6%</td>
								<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax2AmountTax($project), 2, ",",".") }}</td>
								<td class="col-md-1">&nbsp;</td>
							</tr>
							<tr><!-- item -->
								<td class="col-md-4">&nbsp;</td>
								<td class="col-md-1">&nbsp;</td>
								<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
								<td class="col-md-1">&nbsp;</td>
								<td class="col-md-1">0%</td>
								<td class="col-md-2">&nbsp;</td>
								<td class="col-md-1">&nbsp;</td>
							</tr>

							<tr><!-- item -->
								<td class="col-md-4"><strong>Totaal Aanneming </strong></td>
								<td class="col-md-1">&nbsp;</td>
								<td class="col-md-2">&nbsp;</td>
								<td class="col-md-1"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalContracting($project), 2, ",",".") }}</strong></td>
								<td class="col-md-1">&nbsp;</td>
								<td class="col-md-2">&nbsp;</td>
								<td class="col-md-1"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalContractingTax($project), 2, ",",".") }}</strong></td>
							</tr>
						</tbody>
					</table>

					<h4>Onderaanneming</h4>
					<table class="table table-striped">
						<?# -- table head -- ?>
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

						<!-- table items -->
						<tbody>
							<tr><!-- item -->
								<td class="col-md-4">Arbeidskosten</td>
								<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax1($project), 2, ",",".") }}</td>
								<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
								<td class="col-md-1">&nbsp;</td>
								<td class="col-md-1">21%</td>
								<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax1AmountTax($project), 2, ",",".") }}</td>
								<td class="col-md-1">&nbsp;</td>
							</tr>
							<tr><!-- item -->
								<td class="col-md-4">&nbsp;</td>
								<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax2($project), 2, ",",".") }}</td>
								<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
								<td class="col-md-1">&nbsp;</td>
								<td class="col-md-1">6%</td>
								<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax2AmountTax($project), 2, ",",".") }}</td>
								<td class="col-md-1">&nbsp;</td>
							</tr>
							<tr><!-- item -->
								<td class="col-md-4">&nbsp;</td>
								<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax3($project), 2, ",",".") }}</td>
								<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
								<td class="col-md-1">&nbsp;</td>
								<td class="col-md-1">0%</td>
								<td class="col-md-2">&nbsp;</td>
								<td class="col-md-1">&nbsp;</td>
							</tr>

							<tr><!-- item -->
								<td class="col-md-4">Materiaalkosten</td>
								<td class="col-md-1">&nbsp;</td>
								<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
								<td class="col-md-1">&nbsp;</td>
								<td class="col-md-1">21%</td>
								<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcMaterialActivityTax1AmountTax($project), 2, ",",".") }}</td>
								<td class="col-md-1">&nbsp;</td>
							</tr>
							<tr><!-- item -->
								<td class="col-md-4">&nbsp;</td>
								<td class="col-md-1">&nbsp;</td>
								<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
								<td class="col-md-1">&nbsp;</td>
								<td class="col-md-1">6%</td>
								<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcMaterialActivityTax2AmountTax($project), 2, ",",".") }}</td>
								<td class="col-md-1">&nbsp;</td>
							</tr>
							<tr><!-- item -->
								<td class="col-md-4">&nbsp;</td>
								<td class="col-md-1">&nbsp;</td>
								<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
								<td class="col-md-1">&nbsp;</td>
								<td class="col-md-1">0%</td>
								<td class="col-md-2">&nbsp;</td>
								<td class="col-md-1">&nbsp;</td>
							</tr>

							<tr><!-- item -->
								<td class="col-md-4">Materieelkosten</td>
								<td class="col-md-1">&nbsp;</td>
								<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
								<td class="col-md-1">&nbsp;</td>
								<td class="col-md-1">21%</td>
								<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcEquipmentActivityTax1AmountTax($project), 2, ",",".") }}</td>
								<td class="col-md-1">&nbsp;</td>
							</tr>
							<tr><!-- item -->
								<td class="col-md-4">&nbsp;</td>
								<td class="col-md-1">&nbsp;</td>
								<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
								<td class="col-md-1">&nbsp;</td>
								<td class="col-md-1">6%</td>
								<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcEquipmentActivityTax2AmountTax($project), 2, ",",".") }}</td>
								<td class="col-md-1">&nbsp;</td>
							</tr>
							<tr><!-- item -->
								<td class="col-md-4">&nbsp;</td>
								<td class="col-md-1">&nbsp;</td>
								<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
								<td class="col-md-1">&nbsp;</td>
								<td class="col-md-1">0%</td>
								<td class="col-md-2">&nbsp;</td>
								<td class="col-md-1">&nbsp;</td>
							</tr>

							<tr><!-- item -->
								<td class="col-md-4"><strong>Totaal Onderaanneming </strong></td>
								<td class="col-md-1">&nbsp;</td>
								<td class="col-md-2">&nbsp;</td>
								<td class="col-md-1"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
								<td class="col-md-1">&nbsp;</td>
								<td class="col-md-2">&nbsp;</td>
								<td class="col-md-1"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalSubcontractingTax($project), 2, ",",".") }}</strong></td>
							</tr>
						</tbody>
					</table>

					<h4>Cumulatieven Offerte</h4>
					<table class="table table-striped">
						<?# -- table head -- ?>
						<thead>
							<tr>
								<th class="col-md-6">&nbsp;</th>
								<th class="col-md-2">Bedrag (excl. BTW)</th>
								<th class="col-md-2">BTW bedrag</th>
								<th class="col-md-2">&nbsp;</th>
							</tr>
						</thead>

						<!-- table items -->
						<tbody>
							<tr><!-- item -->
								<td class="col-md-6">Calculatief te offereren (excl. BTW)</td>
								<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::totalProject($project), 2, ",",".") }}</td>
								<td class="col-md-2">&nbsp;</td>
								<td class="col-md-2">&nbsp;</td>
							</tr>
							<tr><!-- item -->
								<td class="col-md-6">BTW bedrag aanneming belast met 21%</td>
								<td class="col-md-2">&nbsp;</td>
								<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::totalContractingTax1($project), 2, ",",".") }}</td>
								<td class="col-md-2">&nbsp;</td>
							</tr>
							<tr><!-- item -->
								<td class="col-md-6">BTW bedrag aanneming belast met 6%</td>
								<td class="col-md-2">&nbsp;</td>
								<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::totalContractingTax2($project), 2, ",",".") }}</td>
								<td class="col-md-2">&nbsp;</td>
							</tr>
							<tr><!-- item -->
								<td class="col-md-6">BTW bedrag onderaanneming belast met 21%</td>
								<td class="col-md-2">&nbsp;</td>
								<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::totalSubcontractingTax1($project), 2, ",",".") }}</td>
								<td class="col-md-2">&nbsp;</td>
							</tr>
							<tr><!-- item -->
								<td class="col-md-6">BTW bedrag onderaanneming belast met 6%</td>
								<td class="col-md-2">&nbsp;</td>
								<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::totalSubcontractingTax2($project), 2, ",",".") }}</td>
								<td class="col-md-2">&nbsp;</td>
							</tr>
							<tr><!-- item -->
								<td class="col-md-6">Te offereren BTW bedrag</td>
								<td class="col-md-2">&nbsp;</td>
								<td class="col-md-2">&nbsp;</td>
								<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::totalProjectTax($project), 2, ",",".") }}</td>
							</tr>
							<tr><!-- item -->
								<td class="col-md-6"><strong>Calculatief te offereren (Incl. BTW)</strong></td>
								<td class="col-md-2">&nbsp;</td>
								<td class="col-md-2">&nbsp;</td>
								<td class="col-md-2"><strong>{{ '&euro; '.number_format(CalculationEndresult::superTotalProject($project), 2, ",",".") }}</strong></td>
							</tr>

						</tbody>

					</table>

					<p><a id="closure" href="javascript:void(0);" style="border-bottom: 0" data-type="textarea">Zet hier een voetnoot</a></p>

					<p>Wij kunnen de werkzaamheden starten binnen <a href="javascript:void(0);" style="border-bottom: 0" id="starttime" data-type="select" data-title="Starten werkzaamheden"></a> na dagtekening</p>

					<p>Deze offerte doet stand tot <a href="javascript:void(0);" style="border-bottom: 0" id="endtime" data-type="select" data-title="Stand offerte"></a> na dagtekening</p>

				</div>

			<div class="white-row">

				<div class="row">

					<div class="col-sm-6">
						<img class="img-responsive" src="/images/logo2.png" style="height: 75px;" alt="" />
					</div>

					<div class="col-sm-6 text-right">
						<p>
							#{{ sprintf("%06d", $project->id) }} &bull; <strong>{{ date("j M Y") }}</strong>
							<br />
							{{ $project->project_name }}
						</p>
					</div>

				</div>

				<hr class="margin-top10 margin-bottom10" /><!-- separator -->


				<!-- /DETAILS -->

				<!--<div class="panel-body">-->

								<h4>Aanneming</h4>

								<table class="table table-striped">
									<?# -- table head -- ?>
									<thead>
										<tr>
											<th class="col-md-2">&nbsp;</th>
											<th class="col-md-3">&nbsp;</th>
											<th class="col-md-1"><span class="pull-right">Arbeidsuren</th>
											<th class="col-md-1"><span class="pull-right">Arbeid</th>
											<th class="col-md-1"><span class="pull-right">Materiaal</th>
											<th class="col-md-1"><span class="pull-right">Materieel</th>
											<th class="col-md-1"><span class="pull-right">Totaal</th>
											<th class="col-md-1"><span class="text-center">Stelpost</th>
										</tr>
									</thead>

									<!-- table items -->
									<tbody>
										@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
										@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->get() as $activity)
										<tr><!-- item -->
											<td class="col-md-2"><strong>{{ $chapter->chapter_name }}</strong></td>
											<td class="col-md-3">{{ $activity->activity_name }}</td>
											<td class="col-md-1"><span class="pull-right">{{ number_format(CalculationOverview::laborTotal($activity), 2, ",",".") }}</td>
											<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::laborActivity($project->hour_rate, $activity), 2, ",",".") }}</span></td>
											<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::materialActivityProfit($activity, $project->profit_calc_contr_mat), 2, ",",".") }}</span></td>
											<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::equipmentActivityProfit($activity, $project->profit_calc_contr_equip), 2, ",",".") }}</span></td>
											<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::activityTotalProfit($project->hour_rate, $activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip), 2, ",",".") }} </td>
											<td class="col-md-1 text-center {{ CalculationOverview::estimateCheck($activity) }}"></td>
										</tr>
										@endforeach
										@endforeach
										<tr><!-- item -->
											<th class="col-md-3"><strong>Totaal aanneming</strong></th>
											<th class="col-md-2">&nbsp;</th>
											<td class="col-md-1"><strong><span class="pull-right">{{ CalculationOverview::contrLaborTotalAmount($project) }}</span></strong></td>
											<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::contrLaborTotal($project), 2, ",",".") }}</span></strong></td>
											<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::contrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
											<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::contrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
											<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::contrTotal($project), 2, ",",".") }}</span></strong></td>
											<th class="col-md-1">&nbsp;</th>
										</tr>
									</tbody>
								</table>

								<h4>Onderaanneming</h4>

								<table class="table table-striped">
									<?# -- table head -- ?>
									<thead>
										<tr>
											<th class="col-md-2">&nbsp;</th>
											<th class="col-md-3">&nbsp;</th>
											<th class="col-md-1"><span class="pull-right">Arbeidsuren</th>
											<th class="col-md-1"><span class="pull-right">Arbeid</th>
											<th class="col-md-1"><span class="pull-right">Materiaal</th>
											<th class="col-md-1"><span class="pull-right">Materieel</th>
											<th class="col-md-1"><span class="pull-right">Totaal</th>
											<th class="col-md-1"><span class="text-center">Stelpost</th>
										</tr>
									</thead>

									<!-- table items -->
									<tbody>
										@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
										@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->get() as $activity)
										<tr><!-- item -->
											<td class="col-md-2"><strong>{{ $chapter->chapter_name }}</strong></td>
											<td class="col-md-3">{{ $activity->activity_name }}</td>
											<td class="col-md-1"><span class="pull-right">{{ number_format(CalculationOverview::laborTotal($activity), 2, ",",".") }}</td>
											<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::laborActivity($project->hour_rate, $activity), 2, ",",".") }}</span></td>
											<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::materialActivityProfit($activity, $project->profit_calc_subcontr_mat), 2, ",",".") }}</span></td>
											<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::equipmentActivityProfit($activity, $project->profit_calc_subcontr_equip), 2, ",",".") }}</span></td>
											<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::activityTotalProfit($project->hour_rate, $activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip), 2, ",",".") }} </td>
											<td class="col-md-1 text-center {{ CalculationOverview::estimateCheck($activity) }}"></td>
										</tr>
										@endforeach
										@endforeach
										<tr><!-- item -->
											<th class="col-md-3"><strong>Totaal onderaanneming</strong></th>
											<th class="col-md-2">&nbsp;</th>
											<td class="col-md-1"><strong><span class="pull-right">{{ CalculationOverview::subcontrLaborTotalAmount($project) }}</span></strong></td>
											<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::subcontrLaborTotal($project), 2, ",",".") }}</span></strong></td>
											<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::subcontrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
											<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::subcontrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
											<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::subcontrTotal($project), 2, ",",".") }}</span></strong></td>
											<th class="col-md-1">&nbsp;</th>
										</tr>
									</tbody>
								</table>

								<h4>Totalen project</h4>

								<table class="table table-striped">
									<?# -- table head -- ?>
									<thead>
										<tr>
											<th class="col-md-3">&nbsp;</th>
											<th class="col-md-2">&nbsp;</th>
											<th class="col-md-1"><span class="pull-right">Arbeidsuren</span></th>
											<th class="col-md-1"><span class="pull-right">Arbeid</span></th>
											<th class="col-md-1"><span class="pull-right">Materiaal</span></th>
											<th class="col-md-1"><span class="pull-right">Materieel</span></th>
											<th class="col-md-1"><span class="pull-right">Totaal</span></th>
											<th class="col-md-1">&nbsp;</th>
										</tr>
									</thead>

									<!-- table items -->
									<tbody>
										<tr><!-- item -->
											<th class="col-md-3">&nbsp;</th>
											<th class="col-md-2">&nbsp;</th>
											<td class="col-md-1"><span class="pull-right">{{ CalculationOverview::laborSuperTotalAmount($project) }}</span></td>
											<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::laborSuperTotal($project), 2, ",",".") }}</span></td>
											<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::materialSuperTotal($project), 2, ",",".") }}</span></td>
											<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::equipmentSuperTotal($project), 2, ",",".") }}</span></td>
											<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::superTotal($project), 2, ",",".") }}</span></td>
											<th class="col-md-1">&nbsp;</th>
										</tr>
									</tbody>
								</table>
								<h5>Weergegeven bedragen zijn exclusief BTW</h5>

				</div>

			<!--<hr class="half-margins invisible" />--><!-- separator -->

			<!-- INVOICE FOOTER -->
			<div class="row">

				<div class="col-sm-6">
					<!--<h4><strong>Contact</strong> Details</h4>

					<p class="nomargin nopadding">
						<strong>Note:</strong>
						Like other components, easily make a panel more meaningful to a particular context by adding any of the contextual state classes.
					</p><br />

					<address>
						PO Box 21132 <br>
						Vivas 2355 Australia<br>
						Phone: 1-800-565-2390 <br>
						Fax: 1-800-565-2390 <br>
						Email:support@yourname.com
					</address>-->

				</div>

				<div class="col-sm-6 text-right">

					<!--<ul class="list-unstyled invoice-total-info">
						<li><strong>Sub - Total Amount:</strong> $2162.00</li>
						<li><strong>Discount:</strong> 10.0%</li>
						<li><strong>VAT ($6):</strong> $12.0</li>
						<li><strong>Grand Total:</strong> $1958.0</li>
					</ul>-->

					<div class="padding20">
						<!--<button class="btn btn-default" onclick="window.print();"><i class="fa fa-print"></i> Print</button>-->
						<button class="btn btn-primary">Offerte sluiten</button>
					</div>

				</div>

			</div>
		</div>

	</section>

</div>
<!-- /WRAPPER -->
@stop
