<?php
use \Calctool\Models\Project;
use \Calctool\Models\Purchase;
use \Calctool\Models\Relation;
use \Calctool\Models\PurchaseKind;
use \Calctool\Models\Wholesale;
?>

@extends('layout.master')

@section('content')
<script type="text/javascript">
	$(document).ready(function() {
		$('#addnewpurchase').click(function(e) {
			$curThis = $(this);
			e.preventDefault();
			$date = $curThis.closest("tr").find("input[name='date']").val();
			$amount = $curThis.closest("tr").find("input[name='amount']").val();
			$type = $curThis.closest("tr").find("select[name='typename']").val();
			$relation = $curThis.closest("tr").find("select[name='relation']").val();
			$note = $curThis.closest("tr").find("input[name='note']").val();
			$project = $curThis.closest("tr").find("select[name='projname']").val();
			$.post("/purchase/new", {
				date: $date,
				amount: $amount,
				type: $type,
				relation: $relation,
				note: $note,
				project: $project
			}, function(data){
				var $curTable = $curThis.closest("table");
				var json = $.parseJSON(data);
				if (json.success) {
					$curTable.find("tr:eq(1)").clone().removeAttr("data-id")
					.find("td:eq(0)").text(json.date).end()
					.find("td:eq(1)").text(json.relation).end()
					.find("td:eq(2)").html(json.amount).end()
					.find("td:eq(4)").text(json.type).end()
					.find("td:eq(5)").text($note).end()
					.find("td:eq(8)").html('<button class="btn btn-danger btn-xs fa fa-times deleterowp"></button>').end()
					.prependTo($curTable);
					$curThis.closest("tr").find("input").val("");
					$curThis.closest("tr").find("select").val("");
				}
			});
		});
		$("body").on("click", ".deleterow", function(e){
			e.preventDefault();
			var $curThis = $(this);
			if($curThis.closest("tr").attr("data-id"))
				$.post("/purchase/delete", {id: $curThis.closest("tr").attr("data-id")}, function(){
					$curThis.closest("tr").hide("slow");
				}).fail(function(e) { console.log(e); });
		});
	});
</script>
<div id="wrapper">

	<section class="container" ng-app="purchaseApp">

		<div class="col-md-12">

			<div>
				<ol class="breadcrumb">
				  <li><a href="/">Home</a></li>
				  <li class="active">Inkoopfacturen</li>
				</ol>
			<div>
			<br>

			<h2><strong>Inkoopfacturen</strong></h2>

			<div class="white-row">
				<table class="table table-striped" ng-controller="purchaseController">
					<thead>
						<tr>
							<th class="col-md-1">Datum</th>
							<th class="col-md-2">Relatie</th>
							<th class="col-md-1">Bedrag</th>
							<th class="col-md-3">Project</th>
							<th class="col-md-1">Soort</th>
							<th class="col-md-3">Omschrijving</th>
							<th class="col-md-1">&nbsp;</th>
						</tr>
					</thead>

					<tbody>
						<tr ng-repeat="purchase in purchases | filter: query | orderBy:orderByField:reverseSort as results">
							<td class="col-md-1">@{{ purchase.register_date }}</td>
							<td class="col-md-2">@{{ purchase.relation }}</td>
							<td class="col-md-1">@{{ purchase.amount }}</td>
							<td class="col-md-3">@{{ purchase.project_name }}</td>
							<td class="col-md-1">@{{ purchase.purchase_kind }}</td>
							<td class="col-md-3">@{{ purchase.note }}</td>
							<td class="col-md-1"><button class="btn btn-danger btn-xs fa fa-times" ng-click="deleteRow($index)"></button></td>
						</tr>
						<tr>
							<td class="col-md-1">
								<input type="date" ng-model="date" name="date" id="date" class="form-control-sm-text"/>
							</td>
							<td class="col-md-2">
								<select name="relation" id="relation" class="form-control-sm-text" ng-model="relation">
								@foreach (Relation::where('user_id','=', Auth::id())->where('active',true)->get() as $relation)
									<option value="rel-{{ $relation->id }}">{{ ucwords($relation->company_name) }}</option>
								@endforeach
								@foreach (Wholesale::where('user_id','=', Auth::id())->where('active',true)->get() as $wholesale)
									<option value="whl-{{ $wholesale->id }}">{{ ucwords($wholesale->company_name) }}</option>
								@endforeach
								@foreach (Wholesale::whereNull('user_id')->get() as $wholesale)
									<option value="whl-{{ $wholesale->id }}">{{ ucwords($wholesale->company_name) }}</option>
								@endforeach
								</select>
							</td>
							<td class="col-md-1"><input ng-model="amount" type="text" min="0" name="amount" id="amount" class="form-control-sm-text"/></td>
							<td class="col-md-3">
								<select name="projname" id="projname" class="form-control-sm-text" ng-model="projname">
								@foreach (Project::where('user_id','=',Auth::id())->whereNull('project_close')->get() as $projectname)
									<option value="{{ $projectname->id }}">{{ ucwords($projectname->project_name) }}</option>
								@endforeach
								</select>
							</td>
							<td class="col-md-1">
								<select name="typename" id="typename" class="form-control-sm-text" ng-model="typename">
								@foreach (PurchaseKind::all() as $typename)
									<option value="{{ $typename->id }}">{{ ucwords($typename->kind_name) }}</option>
								@endforeach
								</select>
							</td>
							<td class="col-md-3"><input type="text" name="note" id="note" ng-model="note" class="form-control-sm-text"/></td>
							<td class="col-md-1"><button ng-click="addRow()" class="btn btn-primary btn-xs"> Toevoegen</button></td>
						</tr>
					</tbody>
				</table>
			</div>
			</div>

		</div>

	</section>

</div>
<script type="text/javascript">
angular.module('purchaseApp', []).controller('purchaseController', function($scope, $http) {
	$http.get('/api/v1/purchase').then(function(response){
		$scope.purchases = response.data;
	});

	$scope.orderByField = 'register_date';
	$scope.reverseSort = false;

	$scope.deleteRow = function(id) {
		var row = $scope.purchases[id];

		$http.post('/api/v1/purchase/delete', {id: row.id}).then(function(response){
			$scope.purchases.splice(id, 1);
		});
	};

	$scope.addRow = function() {
		var data = {
			date: $scope.date,
			amount: $scope.amount,
			type: $scope.typename,
			relation: $scope.relation,
			note: $scope.note,
			project: $scope.projname,
		};
		
		$http.post('/api/v1/purchase/new', data).then(function(response){
			var data = {
				register_date: response.data.date,
				amount: response.data.amount,
				relation: response.data.relation,
				project_name: response.data.project,
				purchase_kind: response.data.type,
				note: response.data.note,
			};

			$scope.purchases.push(data);
			console.log(response.data);
		});
	};
});
</script>
@stop
