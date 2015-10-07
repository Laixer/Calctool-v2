@extends('layout.master')

@section('content')
<?# -- WRAPPER -- ?>
<script type="text/javascript">
	$(document).ready(function() {
		$('#addnew').click(function(e) {
			$curThis = $(this);
			e.preventDefault();
			$date = $curThis.closest("tr").find("input[name='date']").val();
			$hour = $curThis.closest("tr").find("input[name='hour']").val();
			$type = $curThis.closest("tr").find("select[name='typename']").val();
			$activity = $curThis.closest("tr").find("select[name='activity']").val();
			$note = $curThis.closest("tr").find("input[name='note']").val();
			$.post("/timesheet/new", {
				date: $date,
				hour: $hour,
				type: $type,
				activity: $activity,
				note: $note,
			}, function(data){
				var $curTable = $curThis.closest("table");
				var json = $.parseJSON(data);
				if (json.success) {
					$curTable.find("tr:eq(1)").clone().removeAttr("data-id")
					.find("td:eq(0)").text(json.date).end()
					.find("td:eq(1)").html(json.hour).end()
					.find("td:eq(2)").text(json.type).end()
					.find("td:eq(3)").text(json.project).end()
					.find("td:eq(4)").text(json.activity).end()
					.find("td:eq(5)").text($note).end()
					.find("td:eq(6)").html('<button class="btn btn-danger btn-xs fa fa-times deleterowp"></button>').end()
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
				$.post("/timesheet/delete", {id: $curThis.closest("tr").attr("data-id")}, function(){
					$curThis.closest("tr").hide("slow");
				}).fail(function(e) { console.log(e); });
		});
		$('.getact').change(function(e){
			if ($('#projname').val() == -1 || $('#typename').val() == -1)
				return;
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
	});
</script>
<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			<h2><strong>Urenregistratie</strong></h2>

			<div class="tabs nomargin">

				<!-- tabs -->
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#hour" data-toggle="tab">
							<i class="fa fa-calendar"></i> Urenregistratie
						</a>
					</li>
					<li>
						<a href="#summary" data-toggle="tab">
							<i class="fa fa-sort-amount-desc"></i> Uittrekstaat
						</a>
					</li>
				</ul>

				<div class="tab-content">
					<div id="hour" class="tab-pane active">
						<table class="table table-striped">
							<thead>
								<tr>
									<th class="col-md-1">Datum</th>
									<th class="col-md-1">Uren</th>
									<th class="col-md-1">Soort</th>
									<th class="col-md-3">Project</th>
									<th class="col-md-2">Werkzaamheid</th>
									<th class="col-md-3">Omschrijving</th>
									<th class="col-md-1">&nbsp;</th>
								</tr>
							</thead>

							<tbody>
								@foreach (Project::where('user_id','=',Auth::user()->id)->get() as $project)
								@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
								@foreach (Activity::where('chapter_id','=', $chapter->id)->get() as $activity)
								@foreach (Timesheet::where('activity_id','=', $activity->id)->get() as $timesheet)
								<tr data-id="{{ $timesheet->id }}"><!-- item -->
									<td class="col-md-1">{{ date('d-m-Y', strtotime($timesheet->register_date)) }}</td>
									<td class="col-md-1">{{ number_format($timesheet->register_hour, 2,",",".") }}</td>
									<td class="col-md-1">{{ ucwords(TimesheetKind::find($timesheet->timesheet_kind_id)->kind_name) }}</td>
									<td class="col-md-3">{{ $project->project_name }}</td>
									<td class="col-md-2">{{ $activity->activity_name }}</td>
									<td class="col-md-3">{{ $timesheet->note }}</td>
									<td class="col-md-1"><button class="btn btn-danger btn-xs fa fa-times deleterow"></button></td>
								</tr>
								@endforeach
								@endforeach
								@endforeach
								@endforeach
								<tr><!-- item -->
									<td class="col-md-1"><input type="date" name="date" id="date" class="form-control-sm-text"/></td>
									<td class="col-md-1"><input type="text" name="hour" id="hour" class="form-control-sm-text"/></td>
									<td class="col-md-1">
										<select name="typename" id="typename" class="getact form-control-sm-text">
											<option selected="selected" value="-1" >Selecteer</option>
											@foreach (TimesheetKind::all() as $typename)
											<option value="{{ $typename->id }}">{{ ucwords($typename->kind_name) }}</option>
											@endforeach
										</select>
									</td>
									<td class="col-md-3">
										<select name="projname" id="projname" class="getact form-control-sm-text">
											<option selected="selected" value="-1" >Selecteer</option>
											@foreach (Project::where('user_id','=',Auth::id())->whereNull('project_close')->get() as $projectname)
											<option value="{{ $projectname->id }}">{{ ucwords($projectname->project_name) }}</option>
											@endforeach
										</select>
									</td>
									<td class="col-md-2">
										<select disabled="disabled" name="activity" id="activity" class="form-control-sm-text"></select>
									</td>
									<td class="col-md-3"><input type="text" name="note" id="note" class="form-control-sm-text"/></td>
									<td class="col-md-1"><button id="addnew" class="btn btn-primary btn-xs"> Toevoegen</button></td>
								</tr>
							</tbody>
						</table>
					</div>

					<div id="summary" class="tab-pane">

							<div class="toogle"><!-- -->
								<div class="toggle active">
									<label>Actieve projecten</label>
									<div class="toggle-content">
									<table class="table table-striped">
										<?# -- table head -- ?>
										<thead>
											<tr>
												<th class="col-md-2">Project</th>
												<th class="col-md-2">Hoofdstuk</th>
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
												<td class="col-md-2">{{ number_format($row1, 2,",","."); }}</td>
												<td class="col-md-2">{{ number_format(Timesheet::where('activity_id','=',$activity->id)->sum('register_hour'), 2,",","."); }}</td>
												<td class="col-md-1">{{ number_format($row1 - Timesheet::where('activity_id','=',$activity->id)->sum('register_hour'), 2,",","."); }}</td>
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
										<?# -- table head -- ?>
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
												<td class="col-md-2">{{ number_format(TimesheetOverview::calcTotalAmount($activity->id), 2,",","."); }}</td>
												<td class="col-md-2">{{ number_format(Timesheet::where('activity_id','=',$activity->id)->sum('register_hour'), 2,",","."); }}</td>
												<td class="col-md-1">{{ number_format(TimesheetOverview::calcTotalAmount($activity->id)-Timesheet::where('activity_id','=',$activity->id)->sum('register_hour'), 2,",","."); }}</td>
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
												<td class="col-md-2">{{ number_format(TimesheetOverview::estimTotalAmount($activity->id), 2,",","."); }}</td>
												<td class="col-md-2">{{ number_format(Timesheet::where('activity_id','=',$activity->id)->sum('register_hour'), 2,",","."); }}</td>
												<td class="col-md-1">{{ number_format(TimesheetOverview::estimTotalAmount($activity->id)-Timesheet::where('activity_id','=',$activity->id)->sum('register_hour'), 2,",","."); }}</td>
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
												<td class="col-md-2">{{ number_format(Timesheet::where('activity_id','=',$activity->id)->where('timesheet_kind_id','=',TimesheetKind::where('kind_name','=','meerwerk')->first()->id)->sum('register_hour'), 2,",","."); }}</td>
												<td class="col-md-1">&nbsp;</td>
											</tr>
											@endforeach
											@endforeach
											@endforeach
										</tbody>
									</table>

									</div>
								</div>

							</div><!-- -->

					</div>

				</div>
			</div>
		</div>

	</section>

</div>
<!-- /WRAPPER -->
@stop
