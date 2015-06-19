<?php
$project = Project::find(Route::Input('project_id'));
$offer_last = Offer::where('project_id','=',$project->id)->orderBy('created_at', 'desc')->first();
?>

@extends('layout.master')

@section('content')
<?# -- WRAPPER -- ?>
<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			<div class="wizard">
				<a href="/"> Home</a>
				<a href="/project-{{ $project->id }}/edit">Project</a>
				<a href="/calculation/project-{{ $project->id }}">Calculatie</a>
				<a href="/offer/project-{{ $project->id }}">Offerte</a>
				<a href="/estimate/project-{{ $project->id }}">Stelpost</a>
				<a href="/less/project-{{ $project->id }}">Minderwerk</a>
				<a href="/more/project-{{ $project->id }}">Meerwerk</a>
				<a href="/invoice/project-{{ $project->id }}" class="current">Factuur</a>
				<a href="/result/project-{{ $project->id }}">Resultaat</a>
			</div>

			<hr />

			<h2><strong>Factuurbeheer</strong></h2>

			<table class="table table-striped">
				<?# -- table head -- ?>
				<thead>
					<tr>
						<th class="col-md-4">Onderdeel</th>
						<th class="col-md-2">Factuurbedrag</th>
						<th class="col-md-1">Faxtuurnummer</th>
						<th class="col-md-3">Omschrijving</th>
						<th class="col-md-2">Betalingscondities</th>
						<th class="col-md-2">Aangemaakt</th>
						<th class="col-md-2">Status</th>
					</tr>
				</thead>

				<!-- table items -->
				<tbody>
				@foreach (Invoice::where('offer_id','=', $offer_last->id)->get() as $invoice)
					<tr>
						<td class="col-md-4">?</td>
						<td class="col-md-2"><input class="form-control-sm-text" type="text" value="{{ $invoice->amount }}" /></td>
						<td class="col-md-1">{{ $invoice->reference }}</td>
						<td class="col-md-3">{{ $invoice->description }}</td>
						<td class="col-md-2">{{ $invoice->payment_condition }}</td>
						<td class="col-md-2">{{ $invoice->attributes['created_at'] }}</td>
						<td class="col-md-2">?</td>
					</tr>
				@endforeach
				</tbody>
			</table>
			<div class="row">
				<div class="col-md-12">
					<a href="project/new" class="btn btn-primary"><i class="fa fa-pencil"></i> Nieuw project</a>
				</div>
			</div>
		</div>

	</section>

</div>
<!-- /WRAPPER -->
@stop
