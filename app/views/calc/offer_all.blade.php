<?php
$common_access_error = false;
$project = Project::find(Route::Input('project_id'));
if (!$project || !$project->isOwner()) {
	$common_access_error = true;
} else {
	$offer_last = Offer::where('project_id','=',$project->id)->orderBy('created_at', 'desc')->first();
}


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

			<h2><strong>Offertebeheer</strong></h2>

		<div class="white-row">
			<table class="table table-striped">
				<thead>
					<tr>
						<th class="col-md-2">Offertenummer</th>
						<th class="col-md-1">Datum</th>
						<th class="col-md-2">Status</th>
						<th class="col-md-2">Opdrachtgever</th>
						<th class="col-md-2">Offertebedrag</th>
						<th class="col-md-3">Acties</th>
					</tr>
				</thead>
				<tbody>
					@foreach(Offer::where('project_id', '=', $project->id)->get() as $offer)
					<tr>
						<td class="col-md-2"><a href="/res-{{ ($offer_last->resource_id) }}/downloads">{{ $offer->id }}</a></td>
						<td class="col-md-1">{{ $offer->created_at }}</td>
						<td class="col-md-2">Actief</td>
						<td class="col-md-2">Zandbergen Advies BV</td>
						<td class="col-md-2">6.785,97</td>
						<td class="col-md-3"><a href="/project/new" class="btn btn-primary btn-xs"><i class="fa fa-cloud-download fa-fw"></i> Downloaden</a></td>
					</tr>
					@endforeach
				</tbody>
			</table>
			<a href="/offer/project-{{ $project->id }}" class="btn btn-primary btn"><i class="fa fa-pencil"></i> Nieuwe offerte</a>
		</div>
	</div>

	</section>

</div>
<!-- /WRAPPER -->
@stop

<?php } ?>
