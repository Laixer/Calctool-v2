<?php

use \Calctool\Models\Project;
use \Calctool\Models\Offer;
use \Calctool\Models\ProjectType;
use \Calctool\Calculus\CalculationEndresult;


$common_access_error = false;
$project = Project::find(Route::Input('project_id'));
if (!$project || !$project->isOwner()) {
	$common_access_error = true;
} else {
	$offer_last = Offer::where('project_id','=',$project->id)->orderBy('created_at', 'desc')->first();
}
?>

@extends('layout.master')

@section('title', 'Offertebeheer')

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
	if (sessionStorage.introDemo) {
		var demo = introJs().
			setOption('nextLabel', 'Volgende').
			setOption('prevLabel', 'Vorige').
			setOption('skipLabel', 'Overslaan').
			setOption('doneLabel', 'Klaar').
			setOption('showBullets', false).
			onexit(function(){
				sessionStorage.removeItem('introDemo');
			}).onbeforechange(function(){
				sessionStorage.introDemo = this._currentStep;
				/*if (this._currentStep == 12) {
					$('#tab-summary').addClass('active');
					$('#summary').addClass('active');

					$('#tab-calculate').removeClass('active');
					$('#calculate').removeClass('active');
				}*/
			}).onafterchange(function(){
				var done = this._currentStep;
				$('.introjs-skipbutton').click(function(){
					if (done == 13) {
						sessionStorage.introDemo = 999;
						window.location.href = '/offerversions/project-{{ $project->id }}';
					}
				});
			});

		if (sessionStorage.introDemo == 999 || sessionStorage.introDemo == 0) {
			sessionStorage.clear();
			sessionStorage.introDemo = 0;
			demo.start();
		} else {
			demo.goToStep(sessionStorage.introDemo).start();
		}

	}
});
</script>
<div id="wrapper">

	<section class="container">

		@include('calc.wizard', array('page' => 'offer'))

		@if ($offer_last)
			@if (number_format(CalculationEndresult::totalProject($project), 3, ",",".") != number_format($offer_last->offer_total, 3, ",","."))
			<div class="alert alert-warning">
				<i class="fa fa-fa fa-info-circle"></i>
				Gegevens zijn gewijzigd ten op zichte van de laastte offerte
			</div>
			@endif
		@endif

		@if (!CalculationEndresult::totalProject($project))
		<div class="alert alert-warning">
			<i class="fa fa-fa fa-info-circle"></i>
			Offertes kunnen pas worden gemaakt wanneer het project waarde bevat
		</div>
		@endif

		@if ($offer_last && !$offer_last->offer_finish)
		<div class="alert alert-warning">
			<i class="fa fa-fa fa-info-circle"></i>
			Zend na aanpassing van de calculatie een nieuwe offerte naar uw opdrachtgever.
		</div>
		@endif

		<h2><strong>Offertebeheer</strong></h2>

		<div class="white-row">
			<table class="table table-striped">
				<thead>
					<tr>
						<th class="col-md-2">Offertenummer</th>
						<th class="col-md-2">Datum</th>
						<th class="col-md-3">Versie</th>
						<th class="col-md-3">Offertebedrag (excl. BTW)</th>
						<th class="col-md-3">Acties</th>
					</tr>
				</thead>
				<tbody>
					<?php $i = 0; ?>
					@foreach(Offer::where('project_id', '=', $project->id)->orderBy('created_at')->get() as $offer)
					<?php $i++; ?>
					<tr>
						<td class="col-md-2"><a href="/offer/project-{{ $project->id }}/offer-{{ $offer->id }}">{{ $offer->offer_code }}</a></td>
						<td class="col-md-2"><?php echo date('d-m-Y', strtotime($offer->offer_make)); ?></td>
						<td class="col-md-3">{{ $i }}</td>
						<td class="col-md-3">{{ '&euro; '.number_format($offer->offer_total, 2, ",",".") }}</td>
						<td class="col-md-3"><a href="/res-{{ ($offer_last->resource_id) }}/download" class="btn btn-primary btn-xs"><i class="fa fa-cloud-download fa-fw"></i> Downloaden</a></td>
					</tr>
					@endforeach
				</tbody>
			</table>
			@if (!($offer_last && $offer_last->offer_finish))
			<a href="/offer/project-{{ $project->id }}" data-step="1" data-intro="Stap 7: Maak nieuwe offerte." class="btn btn-primary btn"><i class="fa fa-pencil"></i>
				<?php
					if(Offer::where('project_id', '=', $project->id)->count('id')>0) {
						echo "Laatste versie bewerken";
					} else {
						echo "Nieuwe offerte maken";
					}
				?>
			</a>
			@endif
		</div>
	</div>

	</section>

</div>
@stop

<?php } ?>
