<?php
use \Calctool\Models\Project;
use \Calctool\Models\Relation;
use \Calctool\Models\RelationKind;
use \Calctool\Models\Contact;
?>

@extends('layout.master')

@section('content')
<div id="wrapper" ng-app="projectApp">

	<section class="container">

		<div class="col-md-12">

			<ol class="breadcrumb">
				<li><a href="/">Home</a></li>
				<li class="active">Projecten</li>
			</ol>
			<div>
				<br>

				<h2><strong>Projecten</strong></h2>
				<div class="white-row" ng-controller="projectController">

					<div class="form-group">
						<input type="text" ng-model="query" class="form-control" placeholder="Zoek in projecten" />
					</div>
					<table class="table table-striped">
						<thead>
							<tr>
								<th class="col-md-3">Projectnaam</th>
								<th class="col-md-2">Opdrachtgever</th>
								<th class="col-md-1">Type</th>
								<th class="col-md-3">Adres</th>
								<th class="col-md-2">Plaats</th>
								<th class="col-md-1">Status</th>
							</tr>
						</thead>
						<tbody>
							<tr ng-repeat="project in projects | filter: query | orderBy: 'project_name' as results">
								<td class="col-md-3"><a href="/project-@{{ project.id }}/edit">@{{ project.project_name }}</a></td>
								<td class="col-md-2">{{-- RelationKind::find($relation->kind_id)->kind_name == 'zakelijk' ? ucwords($relation->company_name) : (Contact::where('relation_id','=',$relation->id)->first()['firstname'].' '.Contact::where('relation_id','=',$relation->id)->first()['lastname']) --}}</td>
								<td class="col-md-1">{{-- $project->type->type_name --}}</td>
								<td class="col-md-3">@{{ project.address_street }} @{{ project.address_number }}</td>
								<td class="col-md-2">@{{ project.address_city }}</td>
								<td class="col-md-1">@{{ project.project_close ? 'Gesloten' : 'Open' }}</td>
							</tr>
							<tr ng-show="results == 0">
								<td colspan="6" style="text-align: center;">Geen projecten beschikbaar</td>
							</tr>
						</tbody>
					</table>
					<div class="row">
						<div class="col-md-12">
							<a href="project/new" class="btn btn-primary"><i class="fa fa-pencil"></i> Nieuw project</a>
						</div>
					</div>
				</div>
			</div>
		</div>

	</section>

</div>
<script type="text/javascript">
	angular.module('projectApp', []).controller('projectController', function($scope, $http) {
		$http.get('/api/v1/projects').then(function(response){
			$scope.projects = response.data;
		});

	});
</script>
@stop
