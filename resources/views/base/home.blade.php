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
use \Jenssegers\Agent\Agent;
?>

@extends('layout.master')

@section('title', 'Dashboard')

@push('scripts')
<script src="/components/angular/angular.min.js"></script>
@endpush

<?php
$agent = new Agent();
if (!session()->has('swap_session')) {
	Auth::user()->online_at = \DB::raw('NOW()');
	Auth::user()->save();
}
?>

@section('content')
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

		</div>
	</div>
</div>

<div id="wrapper">

	<div id="shop">
		<section class="container">

			@if (SysMessage::where('active',true)->count()>0)
			@if (SysMessage::where('active',true)->orderBy('created_at', 'desc')->first()->level==1)
			<div class="alert alert-warning">
				<i class="fa fa-fa fa-info-circle"></i>
				{{ SysMessage::where('active','=',true)->orderBy('created_at', 'desc')->first()->content }}
			</div>
			@else
			<div class="alert alert-danger">
				<i class="fa fa-warning"></i>
				<strong>{{ SysMessage::where('active',true)->orderBy('created_at', 'desc')->first()->content }}</strong>
			</div>
			@endif
			@endif

			@if ($agent->isMobile())
			<div class="alert alert-warning">
				<i class="fa fa-warning"></i>
				<strong>De applicatie werkt het beste op desktop of tablet</strong>
			</div>
			@endif

			<h2 style="margin: 10px 0 20px 0;"><strong>
				<?php
				$time = date("H");
				if ($time >= "6" && $time < "12") {
					echo "Goedemorgen";
				} else if ($time >= "12" && $time < "17") {
					echo "Goedemiddag";
				} else if ($time >= "17") {
					echo "Goedenavond";
				} else if ($time >= "0") {
					echo "Goedenacht";
				}
				?>
			</strong> {{ Auth::user()->firstname }}&nbsp;&nbsp;<a class="fa fa-youtube-play yt-vid" href="javascript:void(0);" data-toggle="modal" data-target="#myYouTube"></a></h2>

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
							<a href="/material" class="btn btn-primary add_to_cart"><strong> Producten</strong></a>
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

				<div class="col-sm-6 col-md-2 hidden-xs">
					<div class="item-box item-box-show fixed-box">
						<figure>
							<a class="item-hover" href="/finance/overview">
								<span class="overlay color2"></span>
								<span class="inner" style="top:40%;">
									<span class="block fa fa-usd fsize60"></span>
								</span>
							</a>
							<a href="/finance/overview" class="btn btn-primary add_to_cart"><strong> Financieel</strong></a>
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
				
			</div>

			<div class="row">

				<div id="wrapper" ng-app="projectApp" class="nopadding-top">

					<div class="col-md-12">
						<br>
						@if (Project::where('user_id', Auth::user()->id)->count('id')>0)
						<h2><strong>Jouw</strong> projecten</h2>

						<div class="white-row" ng-controller="projectController">
							<div class="row">
								<div class="form-group col-lg-12">
									<div class="input-group">
										<input type="text" class="form-control" ng-model="query" placeholder="Zoek in projecten...">
										<span class="input-group-btn">
											<a href="/project/new" class="btn btn-primary" type="button"><i class="fa fa-file"></i> Nieuw project</a>
										</span>
									</div>
								</div>
							</div>

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
									<div ng-show="show" class="row text-center">
										<img src="/images/loading_icon.gif" height="100" />
									</div>
									<tr ng-repeat="project in projects | filter: query | orderBy: orderByField:reverseSort as results">
										<td class="col-md-5"><a href="/project-@{{ project.id }}/edit">@{{ project.project_name }}</a></td>
										<td class="col-md-3">@{{ project.relation }}</td>
										<td class="col-md-2 hidden-sm hidden-xs">@{{ project.type.type_name | capitalize }}</td>
										<td class="col-md-2 hidden-xs">@{{ project.address_city }}</td>
									</tr>
									<tr ng-show="results == 0">
										<td colspan="6" style="text-align: center;">Geen projecten beschikbaar</td>
									</tr>
								</tbody>
							</table>

							<div class="row">
								<div class="col-md-3">
									<div class="btn-group item-full">
										<button class="btn btn-primary" name="toggle-close">Gesloten projecten</a>
										</div>
									</div>
								</div>

							</div>
							@else
							<h2><strong>De eerste</strong> stap... </h2>
							<div class="bs-callout text-center whiteBg" style="margin:0">
								<h3>			
									<a href="/project/new" class="btn btn-primary btn-lg">Maak je eerste project aan <i class="fa fa-arrow-right"></i></a>
								</h3>
							</div>
							@endif
						</div>

					</div>
				</div>
			</div>
		</div> 

	</div>
	<script type="text/javascript">
		$( document ).ready(function() {
			angular.module('projectApp', []).controller('projectController', function($scope, $http) {
				$http.get('/api/v1/projects').then(function(response){
					$scope._projects = response.data;
					$scope.filter_close = false;
					$scope.projects = [];
					angular.forEach($scope._projects, function(value, key) {
						if (value.project_close == null) {
							$scope.projects.push(value);
						}
					});
				});
				$("[name='toggle-close']").click(function() {
					if ($scope.filter_close) {
						$scope.projects = [];
						angular.forEach($scope._projects, function(value, key) {
							if (value.project_close == null) {
								$scope.projects.push(value);
							}
						});
						$scope.$apply();
						$scope.filter_close = false;
						$("[name='toggle-close']").text('Gesloten projecten');
					} else {
						$scope.projects = [];
						angular.forEach($scope._projects, function(value, key) {
							if (value.project_close != null) {
								$scope.projects.push(value);
							}
						});
						$scope.$apply();
						$scope.filter_close = true;
						$("[name='toggle-close']").text('Open projecten');
					}
				});

			}).filter('capitalize', function() {
				return function(input) {
					return (!!input) ? input.charAt(0).toUpperCase() + input.substr(1).toLowerCase() : '';
				}
			});
		});
	</script>
	@stop
