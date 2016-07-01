<?php
use \Calctool\Models\Project;
use \Calctool\Models\Chapter;
use \Calctool\Models\Activity;
use \Calctool\Models\Timesheet;
use \Calctool\Models\PartType;
use \Calctool\Models\Detail;
use \Calctool\Models\TimesheetKind;
use \Calctool\Calculus\TimesheetOverview;
use \Calctool\Models\MoreLabor;
?>

@extends('layout.master')

@section('title', 'Urenregistratie')

@push('style')
<script src="/components/angular/angular.min.js"></script>
<link media="all" type="text/css" rel="stylesheet" href="/components/x-editable/dist/bootstrap3-editable/css/bootstrap-editable.css">
@endpush

@push('scripts')
<script src="/components/x-editable/dist/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
@endpush

@section('content')

<script type="text/javascript">
	$(document).ready(function() {
		$('#tab-hour').click(function(e){
			sessionStorage.toggleTabHour{{Auth::id()}} = 'hour';
		});
		$('#tab-summary').click(function(e){
			sessionStorage.toggleTabHour{{Auth::id()}} = 'summary';
		});
		if (sessionStorage.toggleTabHour{{Auth::id()}}){
			$toggleOpenTab = sessionStorage.toggleTabHour{{Auth::id()}};
			$('#tab-'+$toggleOpenTab).addClass('active');
			$('#'+$toggleOpenTab).addClass('active');
		} else {
			sessionStorage.toggleTabHour{{Auth::id()}} = 'hour';
			$('#tab-hour').addClass('active');
			$('#hour').addClass('active');
		}
		$('.getact').change(function(e){
			var $type = $('#projname option:selected').attr('data-type');
			if (isNaN($('#typename').val()))
				return;
			if ($type == 1) {
				$('#typename').prop('disabled', true);
			} else {
				$('#typename').prop('disabled', false);
			}

			$.get('/timesheet/activity/' + $('#projname').val() + '/' + $('#typename').val(), function(data){
				$('#activity').prop('disabled', false).find('option').remove();
				$('#activity').prop('disabled', false).find('optgroup').remove();
				var groups = new Array();
				$.each(data, function(idx, item) {
					var index = -1;
					for(var i = 0, len = groups.length; i < len; i++) {
					    if (groups[i].group === item.chapter) {
					        groups[i].data.push({value: item.id, text: item.activity_name});
					        index = i;
					        break;
					    }
					}
					if (index == -1) {
						groups.push({group: item.chapter, data: [{value: item.id, text: item.activity_name}]});
					}
				});
				$.each(groups, function(idx, item){
				    $('#activity').append($('<optgroup>', {
				        label: item.group
				    }));
				    $.each(item.data, function(idx2, item2){
					    $('#activity').append($('<option>', {
					        value: item2.value,
					        text : item2.text
					    }));
				    });
				});
			});
		});

		$('.datepick').datepicker();
	});
</script>
<div id="wrapper">

	<section class="container" ng-app="timesheetApp">

		<div class="col-md-12">

			<div>
				<ol class="breadcrumb">
				  <li><a href="/">Home</a></li>
				  <li class="active">Urenregistratie</li>
				</ol>
			<div>
			<br>

			<h2><strong>Urenregistratie</strong></h2>

			<div class="tabs nomargin">

				@if (0)
				<ul class="nav nav-tabs">
					<li id="tab-hour">
						<a href="#hour" data-toggle="tab">
							<i class="fa fa-calendar"></i> Urenregistratie
						</a>
					</li>
					
					<li id="tab-summary">
						<a href="#summary" data-toggle="tab">
							<i class="fa fa-sort-amount-desc"></i> Uittrekstaat
						</a>
					</li>
				</ul>
				@endif

				<div class="tab-content">
					<div id="hour" class="tab-pane active table-responsive">
						<table class="table table-striped" ng-controller="timesheetController">
							<thead>
								<tr>
									<th class="col-md-1" ng-click="orderByField='register_date'; reverseSort = !reverseSort">Datum</th>
									<th class="col-md-1" ng-click="orderByField='register_hour'; reverseSort = !reverseSort">Uren</th>
									<th class="col-md-2" ng-click="orderByField='project_name'; reverseSort = !reverseSort">Project</th>
									<th class="col-md-2" ng-click="orderByField='timesheet_kind'; reverseSort = !reverseSort">Soort</th>
									<th class="col-md-3" ng-click="orderByField='activity_name'; reverseSort = !reverseSort">Werkzaamheid</th>
									<th class="col-md-2">Omschrijving</th>
									<th class="col-md-1">&nbsp;</th>
								</tr>
							</thead>

							<tbody>
								<tr ng-repeat="timesheet in timesheets | filter: query | orderBy:orderByField:reverseSort as results">
									<td class="col-md-1">@{{ timesheet.register_date }}</td>
									<td class="col-md-1">@{{ timesheet.register_hour }}</td>
									<td class="col-md-2">@{{ timesheet.project_name }}</td>
									<td class="col-md-2">@{{ timesheet.timesheet_kind }}</td>
									<td class="col-md-3">@{{ timesheet.activity_name }}</td>
									<td class="col-md-2">@{{ timesheet.note }}</td>
									<td class="col-md-1 text-right"><button ng-click="deleteRow(timesheet.id)" class="btn btn-danger btn-xs fa fa-times"></button></td>
								</tr>
								<tr>
									<td class="col-md-1"><input type="text" name="date" id="date" class="form-control-sm-text datepick"/></td>
									<td class="col-md-1"><input type="text" name="hour" id="hour" class="form-control-sm-text" ng-model="hour"/></td>
									<td class="col-md-2">
										<select name="projname" id="projname" class="getact form-control-sm-text" ng-model="projname">
											@foreach (Project::where('user_id','=',Auth::id())->whereNull('project_close')->get() as $projectname)
											<option data-type="{{ $projectname->type_id }}" value="{{ $projectname->id }}">{{ ucwords($projectname->project_name) }}</option>
											@endforeach
										</select>
									</td>
									<td class="col-md-2">
										<select name="typename" id="typename" class="getact form-control-sm-text" ng-model="typename">
											@foreach (TimesheetKind::all() as $typename)
											<option value="{{ $typename->id }}">{{ ucwords($typename->kind_name) }}</option>
											@endforeach
										</select>
									</td>
									<td class="col-md-3">
										<select disabled="disabled" name="activity" id="activity" class="form-control-sm-text"></select>
									</td>
									<td class="col-md-2"><input type="text" ng-model="note" name="note" id="note" class="form-control-sm-text"/></td>
									<td class="col-md-1"><button ng-click="addRow()" class="btn btn-primary btn-xs"> Toevoegen</button></td>
								</tr>
							</tbody>
						</table>
					</div>

					@if (0)
					<div id="summary" class="tab-pane">

							<div class="toogle">
								<div class="toggle active">
									<label>Actieve projecten</label>
									<div class="toggle-content">
									<table class="table table-striped">
										<thead>
											<tr>
												<th class="col-md-2">Project</th>
												<th class="col-md-2">Onderdeel</th>
												<th class="col-md-3">Werkzaamheid</th>
												<th class="col-md-2">Gecalculeerde uren</th>
												<th class="col-md-2">Geregistreerde uren</th>
												<th class="col-md-1">Verschil</th>
											</tr>
										</thead>

										<tbody>
											@foreach (Project::where('user_id','=',Auth::user()->id)->where('project_close','=',null)->get() as $project)
											@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
											@foreach (Activity::where('chapter_id','=', $chapter->id)->get() as $activity)
											<?php
												$estim = $activity->part_type_id == PartType::where('type_name','=','estimate')->first()->id;
												$more = $activity->detail_id == Detail::where('detail_name','=','more')->first()->id;
												$row1 = 0;
												if ($more){
													$row1 = MoreLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->sum('amount');
												}
												else {
													if ($estim){
														$row1 = TimesheetOverview::estimTotalAmount($activity->id);
													}
													else {
														$row1 = TimesheetOverview::calcTotalAmount($activity->id);
													}
												}
											?>
											<tr>
												<td class="col-md-2"><strong>{{ $project->project_name }}</strong></td>
												<td class="col-md-2"><strong>{{ $chapter->chapter_name }}</strong></td>
												<td class="col-md-3">{{ $activity->activity_name }}</td>
												<td class="col-md-2">{{ number_format($row1, 2,",",".") }}</td>
												<td class="col-md-2">{{ number_format(Timesheet::where('activity_id','=',$activity->id)->sum('register_hour'), 2,",",".") }}</td>
												<td class="col-md-1">{{ number_format($row1 - Timesheet::where('activity_id','=',$activity->id)->sum('register_hour'), 2,",",".") }}</td>
											</tr>
											@endforeach
											@endforeach
											@endforeach

										</tbody>
									</table>
									</div>
								</div>

								<div class="toggle">
									<label>Gesloten projecten</label>
									<div class="toggle-content">

									<table class="table table-striped">
										<thead>
											<tr>
												<th class="col-md-2">&nbsp;</th>
												<th class="col-md-2">&nbsp;</th>
												<th class="col-md-3">&nbsp;</th>
												<th class="col-md-2">Gecalculeerde uren</th>
												<th class="col-md-2">Geregistreerde uren</th>
												<th class="col-md-1">Verschil</th>
											</tr>
										</thead>

										<tbody>
											@foreach (Project::where('user_id','=',Auth::user()->id)->whereNotNull('project_close')->get() as $project)
											@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
											@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_type_id','=',PartType::where('type_name','=','calculation')->first()->id)->get() as $activity)
											<tr>
												<td class="col-md-2"><strong>{{ $project->project_name }}</strong></td>
												<td class="col-md-2"><strong>{{ $chapter->chapter_name }}</strong></td>
												<td class="col-md-3">{{ $activity->activity_name }}</td>
												<td class="col-md-2">{{ number_format(TimesheetOverview::calcTotalAmount($activity->id), 2,",",".") }}</td>
												<td class="col-md-2">{{ number_format(Timesheet::where('activity_id','=',$activity->id)->sum('register_hour'), 2,",",".") }}</td>
												<td class="col-md-1">{{ number_format(TimesheetOverview::calcTotalAmount($activity->id)-Timesheet::where('activity_id','=',$activity->id)->sum('register_hour'), 2,",",".") }}</td>
											</tr>
											@endforeach
											@endforeach
											@endforeach

											@foreach (Project::where('user_id','=',Auth::user()->id)->whereNotNull('project_close')->get() as $project)
											@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
											@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_type_id','=',PartType::where('type_name','=','estimate')->first()->id)->get() as $activity)
											<tr>
												<td class="col-md-2"><strong>{{ $project->project_name }}</strong></td>
												<td class="col-md-2"><strong>{{ $chapter->chapter_name }}</strong></td>
												<td class="col-md-3">{{ $activity->activity_name }}</td>
												<td class="col-md-2">{{ number_format(TimesheetOverview::estimTotalAmount($activity->id), 2,",",".") }}</td>
												<td class="col-md-2">{{ number_format(Timesheet::where('activity_id','=',$activity->id)->sum('register_hour'), 2,",",".") }}</td>
												<td class="col-md-1">{{ number_format(TimesheetOverview::estimTotalAmount($activity->id)-Timesheet::where('activity_id','=',$activity->id)->sum('register_hour'), 2,",",".") }}</td>
											</tr>
											@endforeach
											@endforeach
											@endforeach

											@foreach (Project::where('user_id','=',Auth::user()->id)->whereNotNull('project_close')->get() as $project)
											@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
											@foreach (Activity::where('chapter_id','=', $chapter->id)->get() as $activity)
											<tr>
												<td class="col-md-2"><strong>{{ $project->project_name }}</strong></td>
												<td class="col-md-2"><strong>{{ $chapter->chapter_name }}</strong></td>
												<td class="col-md-3">{{ $activity->activity_name }}</td>
												<td class="col-md-2">&nbsp;</td>
												<td class="col-md-2">{{ number_format(Timesheet::where('activity_id','=',$activity->id)->where('timesheet_kind_id','=',TimesheetKind::where('kind_name','=','meerwerk')->first()->id)->sum('register_hour'), 2,",",".") }}</td>
												<td class="col-md-1">&nbsp;</td>
											</tr>
											@endforeach
											@endforeach
											@endforeach
										</tbody>
									</table>

									</div>
								</div>

							</div>
					</div>
					@endif

				</div>
			</div>
		</div>

	</section>

</div>
<script type="text/javascript">
angular.module('timesheetApp', []).controller('timesheetController', function($scope, $http) {
	$http.get('/api/v1/timesheet').then(function(response){
		$scope.timesheets = response.data;
	});

	$scope.orderByField = 'register_date';
	$scope.reverseSort = false;

	$scope.deleteRow = function(id) {
		$http.post('/api/v1/timesheet/delete', {id: id}).then(function(response){
			angular.forEach($scope.timesheets, function(value, key) {
				if (value.id == id) {
					$scope.timesheets.splice(key, 1);
				}
			});
		});
	};

	$scope.addRow = function() {
		var data = {
			date: $('#date').val(),
			hour: $scope.hour,
			type: $scope.typename,
			activity: $('#activity').val(),
			note: $scope.note,
		};
		
		$http.post('/api/v1/timesheet/new', data).then(function(response){
			var data = {
				register_date: response.data.date,
				register_hour: response.data.hour,
				project_name: response.data.project,
				timesheet_kind: response.data.type,
				activity_name: response.data.activity,
				note: response.data.note,
			};

			$scope.timesheets.push(data);

			$('#date').val('');
			$scope.hour = '';
			$scope.typename = '';
			$('#activity').val('');
			$scope.note = '';
		});
	};
});
</script>
@stop
