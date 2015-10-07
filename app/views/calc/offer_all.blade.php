<?php
$common_access_error = false;
$project = Project::find(Route::Input('project_id'));
if (!$project || !$project->isOwner()) {
	$common_access_error = true;
} else {
	$offer_last = Offer::where('project_id','=',$project->id)->orderBy('created_at', 'desc')->first();
}

$relation = Relation::find($project->client_id);

?>

@extends('layout.master')

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
<?# -- WRAPPER -- ?>
<script type="text/javascript">

</script>
<div id="wrapper">

	<section class="container">

		@include('calc.wizard', array('page' => 'offer'))

		@if ($offer_last)
			@if (CalculationEndresult::totalProject($project) != $offer_last->offer_total)
			<div class="alert alert-warning">
				<i class="fa fa-fa fa-info-circle"></i>
				Gegevens zijn gewijzigd ten op zichte van de laastte offerte
			</div>
			@endif
		@endif

			<h2><strong>Offertebeheer</strong></h2>

		<div class="white-row">
			<table class="table table-striped">
				<thead>
					<tr>
						<th class="col-md-3">Offertenummer</th>
						<th class="col-md-2">Datum</th>
						<th class="col-md-3">Opdrachtgever</th>
						<th class="col-md-2">Offertebedrag</th>
						<th class="col-md-3">Acties</th>
					</tr>
				</thead>
				<tbody>
					@foreach(Offer::where('project_id', '=', $project->id)->get() as $offer)
					<tr>
						<td class="col-md-3"><a href="/offer/project-{{ $project->id }}/offer-{{ $offer->id }}">{{ $offer->offer_code }}</a></td>
						<td class="col-md-2"><?php echo date('d-m-Y', strtotime($offer->offer_make)); ?></td>
						<td class="col-md-3">{{ $relation->company_name }}</td>
						<td class="col-md-2">{{ '&euro; '.number_format($offer->offer_total, 2, ",",".") }}</td>
						<td class="col-md-3"><a href="/res-{{ ($offer_last->resource_id) }}/download" class="btn btn-primary btn-xs"><i class="fa fa-cloud-download fa-fw"></i> Downloaden</a></td>
					</tr>
					@endforeach
				</tbody>
			</table>
			<a href="/offer/project-{{ $project->id }}" class="btn btn-primary btn"><i class="fa fa-pencil"></i>
				<?php
						if(Offer::where('project_id', '=', $project->id)->count('id')>0) {
							echo "Laatste versie bewerken";
						} else {
							echo "Nieuwe offerte maken";
						}
				?>
			</a>
		</div>
	</div>

	</section>

</div>
<!-- /WRAPPER -->
@stop

<?php } ?>
