<?php
use \Calctool\Models\Relation;
use \Calctool\Models\Project;
use \Calctool\Models\RelationKind;
use \Calctool\Models\RelationType;
use \Calctool\Models\Province;
use \Calctool\Models\Country;
use \Calctool\Models\Contact;
use \Calctool\Models\ContactFunction;
use \Calctool\Models\SysMessage;
?>

@extends('layout.master')

@section('title', 'Dashboard')

@push('style')
<link media="all" type="text/css" rel="stylesheet" href="/plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css">
<script src="/components/angular/angular.min.js"></script>
@endpush

@push('scripts')
<script src="/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
@endpush

<?php
$next_step = null;
if (Input::get('nstep') == 'intro')
	$next_step = 'intro_'.Auth::id();

$relation = Relation::find(Auth::user()->self_id);
?>

@section('content')
<script type="text/javascript">
$(document).ready(function() {
	$('.starttour').click(function(){
		sessionStorage.introDemo = 0;
		$('#introModal').modal('hide')

		introJs().
		setOption('nextLabel', 'Volgende').
		setOption('prevLabel', 'Vorige').
		setOption('skipLabel', 'Overslaan').
		setOption('doneLabel', 'Volgende pagina').
		setOption('showBullets', false).
		setOption('exitOnOverlayClick', false).
		start().oncomplete(function(){
			window.location.href = '/mycompany';
			sessionStorage.introDemo = 0;
		});
	});
	<?php if ($next_step && $next_step=='intro_' . Auth::id()){ ?>
		$('#introModal').modal('toggle');
	<?php } ?>
});
</script>
<div class="modal fade" id="introModal" tabindex="-1" role="dialog" aria-labelledby="introModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body" id="introform">
				<div class="row">
					<div class="col-md-8">
						<h4>Welkom bij de<strong> CalculatieTool.com</strong></h4>
					</div>
					<div class="col-md-4">
						<a class="logo" href="/">
						<img src="/images/logo2.png" width="229px" alt="Calctool">
						</a>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-12">
						<h4>Al je noodzakelijke gegevens zijn bekend en je kan direct beginnen met het aanmaken van een nieuw project. </h4>
						Je kan je gegevens controleren of later aanpassen en aanvullen onder "Mijn bedrijf".
						<br>
						Mocht je vragen hebben of hulp nodig hebben laat dit dan weten in de feedback knop rechts op het scherm.
						Wij beantwoorden deze vraag dan binnen 12 uur of bellen je indien gewenst, laat hiervoor je telefoonnummer achter.
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="col-md-6"></div>
				<div class="col-md-6">
					<button class="btn btn-primary" onclick="$('#introModal').modal('hide')"> Sluiten</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="myYouTube" tabindex="-1" role="dialog" aria-labelledby="mYouTubeLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

			<iframe width="1280" height="720" src="https://www.youtube.com/embed/edTyWvLUeDo?rel=0;" frameborder="0" allowfullscreen></iframe>

			<div class="modal-body">
				<div class="form-horizontal">


				</div>
			</div>

		</div>
	</div>
</div>

<div id="wrapper">

	<div id="shop">
		<section class="container">

			@if (SysMessage::where('active','=',true)->count()>0)
			@if (SysMessage::where('active','=',true)->orderBy('created_at', 'desc')->first()->level==1)
			<div class="alert alert-warning">
				<i class="fa fa-fa fa-info-circle"></i>
				{{ SysMessage::where('active','=',true)->orderBy('created_at', 'desc')->first()->content }}
			</div>
			@else
			<div class="alert alert-danger">
				<i class="fa fa-warning"></i>
				<strong>{{ SysMessage::where('active','=',true)->orderBy('created_at', 'desc')->first()->content }}</strong>
			</div>
			@endif
			@endif

			<h2 style="margin: 10px 0 20px 0;"><strong>Welkom</strong> {{ Auth::user()->firstname }}&nbsp;&nbsp;<a class="fa fa-youtube-play" href="javascript:void(0);" data-toggle="modal" data-target="#myYouTube"></a></h2>

			<div class="row">

				<div class="col-sm-6 col-md-2" data-step="1" data-intro="Klik op 'Mijn Bedrijf' om je bedrijfsgegevens in te vullen.">
					<div class="item-box item-box-show fixed-box">
						<figure>
							<a class="item-hover" href="/mycompany">
								<span class="overlay color2"></span>
								<span class="inner" style="top:40%;">
									<span class="block fa fa-home fsize60"></span>
								</span>
							</a>
							<a href="/mycompany" class="btn btn-primary add_to_cart"><strong> Mijn Bedrijf</strong></a>

						</figure>
					</div>
				</div>



				<div class="col-sm-6 col-md-2">
					<div class="item-box item-box-show fixed-box">
						<figure>
							<a class="item-hover" href="/material">
								<span class="overlay color2"></span>
								<span class="inner" style="top:40%;">
									<span class="block fa fa-wrench fsize60"></span>
								</span>
							</a>
							<a href="/material" class="btn btn-primary add_to_cart"><strong> Prijslijsten</strong></a>
						</figure>
					</div>
				</div>

				<div class="col-sm-6 col-md-2">
					<div class="item-box item-box-show fixed-box">
						<figure>
							<a class="item-hover" href="/timesheet">
								<span class="overlay color2"></span>
								<span class="inner" style="top:40%;">
									<span class="block fa fa-clock-o fsize60"></span>
								</span>
							</a>
							<a href="/timesheet" class="btn btn-primary add_to_cart"><strong> Urenregistratie</strong></a>
						</figure>
					</div>
				</div>

				<div class="col-sm-6 col-md-2">
					<div class="item-box item-box-show fixed-box">
						<figure>
							<a class="item-hover" href="/purchase">
								<span class="overlay color2"></span>
								<span class="inner" style="top:40%;">
									<span class="block fa fa-shopping-cart fsize60"></span>
								</span>
							</a>
							<a href="/purchase" class="btn btn-primary add_to_cart"><strong> Inkoopfacturen</strong></a>
						</figure>
					</div>
				</div>

				<div class="col-sm-6 col-md-2">
					<div class="item-box item-box-show fixed-box">
						<figure>
							<a class="item-hover" href="/relation">
								<span class="overlay color2"></span>
								<span class="inner" style="top:40%;">
									<span class="block fa fa-users fsize60"></span>
								</span>
							</a>
							<a href="/relation" class="btn btn-primary add_to_cart"><strong> Relaties</strong></a>
						</figure>
					</div>
				</div>

				<div class="col-sm-6 col-md-2 hidden-xs">
					<div class="item-box item-box-show fixed-box">
						<figure>
							<a class="item-hover" href="/offer_invoice">
								<span class="overlay color2"></span>
								<span class="inner" style="top:40%;">
									<span class="block fa fa-line-chart fsize60"></span>
								</span>
							</a>
							<a href="/offer_invoice" class="btn btn-primary add_to_cart"><strong> Overzichten</strong></a>
						</figure>
					</div>
				</div>
				
			</div>

			<div class="row">

				<div id="wrapper" ng-app="projectApp" class="nopadding-top">

					<div class="col-md-12">
						<br>
						@if (Project::where('user_id','=', Auth::user()->id)->count('id')>0)
						<h2><strong>Jouw</strong> projecten</h2>

						<div class="white-row" ng-controller="projectController">
							<div class="row">
								<div class="form-group col-md-8">
									<input type="text" ng-model="query" class="form-control" placeholder="Zoek in projecten">
								</div>
								<div class="form-group col-md-4 hidden-xs" STYLE="text-align: right;" >
									<span><strong>Projectstatus: &nbsp;</strong></span>
									<input name="toggle-close" type="checkbox">
								</div>
							</div>
							<!-- <div class="table-responsive"> -->
							<table ng-cloak class="table table-striped">
								<thead>
									<tr>
										<th class="col-md-5" ng-click="orderByField='project_name'; reverseSort = !reverseSort">Projectnaam</th>
										<th class="col-md-3" ng-click="orderByField='relation'; reverseSort = !reverseSort">Opdrachtgever</th>
										<th class="col-md-2 hidden-sm hidden-xs" ng-click="orderByField='type_name'; reverseSort = !reverseSort">Type</th>
										<th class="col-md-2 hidden-xs" ng-click="orderByField='address_city'; reverseSort = !reverseSort">Plaats</th>
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="project in projects | filter: query | orderBy: orderByField:reverseSort as results">
										<td class="col-md-5"><a href="/project-@{{ project.id }}/edit">@{{ project.project_name }}</a></td>
										<td class="col-md-3">@{{ project.relation }}</td>
										<td class="col-md-2 hidden-sm hidden-xs">@{{ project.type.type_name }}</td>
										<td class="col-md-2 hidden-xs">@{{ project.address_city }}</td>
									</tr>
									<tr ng-show="results == 0">
										<td colspan="6" style="text-align: center;">Geen projecten beschikbaar</td>
									</tr>
								</tbody>
							</table>
							<!-- </div> -->
							<div class="row">
								<div class="col-md-3">
									<div class="btn-group item-full">
								  		<a href="/project/new" class="btn btn-primary item-full"><i class="fa fa-pencil"></i> Nieuw project</a>
								 		
									</div>
								</div>
							</div>

						</div>
						@else
						<h2><strong>De eerste</strong> stappen...</h2>
						<div class="bs-callout text-center whiteBg">
							<h3>			
								<a href="/project/new" class="btn btn-primary btn-lg">Start nieuw project</a>
								<strong>OF</strong>
								<a href="/mycompany" class="btn btn-primary btn-lg">Maak je bedrijfsgegevens compleet</a>
<!-- 							<button class="starttour btn btn-primary btn-lg">Neem de Rondleiding</button>
 -->							</h3>
						</div>
						@endif
					</div>

				</div>
				<script type="text/javascript">
				angular.module('projectApp', []).controller('projectController', function($scope, $http) {
					$http.get('/api/v1/projects').then(function(response){
						$scope._projects = response.data;
						$scope.projects = [];
						angular.forEach($scope._projects, function(value, key) {
						  if (value.project_close == null) {
							$scope.projects.push(value);
						  }
						});
					});
					$("[name='toggle-close']").bootstrapSwitch({onText: 'Gesloten', offText: 'Open'});
					$("[name='toggle-close']").on('switchChange.bootstrapSwitch', function (event, state) {
				        if (state == false) {
							$scope.projects = [];
							angular.forEach($scope._projects, function(value, key) {
							  if (value.project_close == null) {
								$scope.projects.push(value);
							  }
							});
							$scope.$apply();
				        } else {
							$scope.projects = [];
							angular.forEach($scope._projects, function(value, key) {
							  if (value.project_close != null) {
								$scope.projects.push(value);
							  }
							});
							$scope.$apply();
				        }
					});

				});
				</script>
			</div>
		</div>
	</div> 

</div>
@stop
