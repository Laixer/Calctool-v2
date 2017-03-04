@extends('layout.master')

@section('title', 'Relaties')

@push('scripts')
<script src="/components/angular/angular.min.js"></script>
@endpush

@section('content')
<div class="modal fade" id="myYouTube" tabindex="-1" role="dialog" aria-labelledby="mYouTubeLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

			<iframe width="1280" height="720" src="https://www.youtube.com/embed/CCXRWinSKsg?rel=0" frameborder="0" allowfullscreen></iframe>
			
		</div>
	</div>
</div>
		
<div id="wrapper">

	<section class="container" ng-app="relationApp">

		<div class="col-md-12">

			<div>
				<ol class="breadcrumb">
				  <li><a href="/">Dashboard</a></li>
				  <li>Relaties</li>
				</ol>
			<div>
			<br>

			<div class="pull-right">
				<a href="/import" class="btn btn-primary" type="button"><i class="fa fa-upload"></i> Importeer</a>
				<a href="/relation/export" class="btn btn-primary" type="button"><i class="fa fa-download"></i> Exporteer</a>
			</div>

			<h2 style="margin: 10px 0 20px 0;"><strong>Relaties</strong>&nbsp;&nbsp;<a class="fa fa-youtube-play yt-vid" href="javascript:void(0);" data-toggle="modal" data-target="#myYouTube"></a></h2>

			<div class="white-row" ng-controller="relationController">

				<div class="row">
					<div class="form-group col-lg-12">
						<div class="input-group">
							<input type="text" class="form-control" ng-model="query" placeholder="Zoek in relaties op naam, nummer, adres of type">
							
							<span class="input-group-btn">
								<a href="/relation/new" class="btn btn-primary" type="button"><i class="fa fa-file"></i> Nieuwe relatie</a>
							</span>
						</div>
					</div>
				</div>



				<table ng-cloak class="table table-striped">
					<thead>
						<tr>
							<th class="col-md-1">Debiteur</th>
							<th class="col-md-4">(Bedrijfs)naam</th>
							<th class="col-md-2">Relatietype</th>
							<th class="col-md-2">Telefoon</th>
							<th class="col-md-2">Email</th>
							<th class="col-md-2">Plaats</th>
						</tr>
					</thead>

					<tbody>
						<div ng-show="show" class="row text-center">
							<img src="/images/loading_icon.gif" height="100" />
						</div>
						<tr ng-repeat="relation in relations | filter: query | orderBy: orderByField:reverseSort as results">
							<td class="col-md-1"><a href="/relation-@{{ relation.id }}/edit">@{{ relation.debtor_code }}</td>
							<td class="col-md-4"><a href="/relation-@{{ relation.id }}/edit">@{{ relation.company }}</td>
							<td class="col-md-2">@{{ relation.type_name }}</td>
							<td class="col-md-2">@{{ relation._phone }}</td>
							<td class="col-md-2">@{{ relation._email }}</td>
							<td class="col-md-2">@{{ relation.address_city }}</td>
						</tr>
						<tr ng-show="results == 0">
							<td colspan="6" style="text-align: center;">Geen relaties</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

	</section>

</div>
<script type="text/javascript">
	$( document ).ready(function() {
		angular.module('relationApp', []).controller('relationController', function($scope, $http) {
			$http.get('/api/v1/relations').then(function(response){
				$scope.relations = response.data;
			});
		}).filter('capitalize', function() {
			return function(input) {
				return (!!input) ? input.charAt(0).toUpperCase() + input.substr(1).toLowerCase() : '';
			}
		});
	});
</script>
@stop
