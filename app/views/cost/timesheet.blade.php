@extends('layout.master')

@section('content')
<?# -- WRAPPER -- ?>
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

				<!-- tabs content -->
				<div class="tab-content">
						<div id="hour" class="tab-pane">

							<!--<div class="toggle">
								<label>Deze week</label>
								<div class="toggle-content">-->
									<table class="table table-striped">
										<?# -- table head -- ?>
										<thead>
											<tr>
												<th class="col-md-1">Datum</th>
												<th class="col-md-1">Uren</th>
												<th class="col-md-1">Soort</th>
												<th class="col-md-1">Project</th>
												<th class="col-md-1">Werkzaamheid</th>
												<th class="col-md-3">Omschrijving</th>
												<th class="col-md-1">&nbsp;</th>
												<th class="col-md-1">&nbsp;</th>
												<th class="col-md-1">&nbsp;</th>
												<th class="col-md-1">&nbsp;</th>
											</tr>
										</thead>

										<!-- table items -->
										<tbody>
											@foreach (Project::where('user_id','=',Auth::user()->id) as $project)
											@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
											@foreach (Activity::where('chapter_id','=', $chapter->id)->get() as $activity)
											@foreach (Timesheet::where('activity_id','=', $activity->id)->get() as $timesheet)
											<tr data-id="{{ $timesheet->id }}"><!-- item -->
												<td class="col-md-1">{{ $timesheet->register_date }}</td>
												<td class="col-md-1">{{ number_format($timesheet->register_hour, 2,",",".") }}</td>
												<td class="col-md-1">{{ ucwords(TimesheetKind::find($timesheet->timesheet_kind_id)->kind_name) }}</td>
												<td class="col-md-1">{{ $project->project_name }}</td>
												<td class="col-md-1">{{ $activity->activity_name }}</td>
												<td class="col-md-3">{{ $timesheet->note }}</td>
												<td class="col-md-1">&nbsp;</td>
												<td class="col-md-1">&nbsp;</td>
												<td class="col-md-1"><button class="btn btn-danger btn-xs fa fa-times deleterow"></button></td>
											</tr>
											@endforeach
											@endforeach
											@endforeach
											@endforeach
											<tr><!-- item -->
												<td class="col-md-1"><input type="date" name="date" id="date" class="form-control-sm-text"/></td>
												<td class="col-md-1"><input type="number" min="0" name="hour" id="hour" class="form-control-sm-text"/></td>
												<td class="col-md-1">
													<select name="typename" id="typename" class="form-control-sm-text">
													@foreach (TimesheetKind::all() as $typename)
														<option value="{{ $typename->id }}">{{ ucwords($typename->kind_name) }}</option>
													@endforeach
													</select>
												</td>
												<td class="col-md-1">
													<select name="typename" id="typename" class="form-control-sm-text">
													@foreach (Project::all() as $projectname)
														<option value="{{ $projectname->id }}">{{ ucwords($projectname->project_name) }}</option>
													@endforeach
													</select>
												</td>
												<td class="col-md-3">
													<select name="activity" id="activity" class="form-control-sm-text">
													@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
													@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->get() as $activity)
														<option value="{{ $activity->id }}">{{ $activity->activity_name }}</option>
													@endforeach
													@endforeach
													</select>
												</td>
												<td class="col-md-1"><input type="text" name="note" id="note" class="form-control-sm-text"/></td>
												<td class="col-md-1">&nbsp;</td>
												<td class="col-md-1">&nbsp;</td>
												<td class="col-md-1"><button id="addnew" class="btn btn-primary btn-xs"> Toevoegen</button></td>
											</tr>
										</tbody>
									</table>
								<!--</div>
							</div>-->
						</div>

					<div id="summary" class="tab-pane">
						<div class="toogle">

							<div class="toggle active">
								<label>Aanneming</label>
								<div class="toggle-content">

									<table class="table table-striped">
										<?# -- table head -- ?>
										<thead>
											<tr>
												<th class="col-md-2">&nbsp;</th>
												<th class="col-md-4">&nbsp;</th>
												<th class="col-md-2">Gecalculeerde uren</th>
												<th class="col-md-2">Geregistreerde uren</th>
												<th class="col-md-2">Verschil</th>
											</tr>
										</thead>

										<!-- table items -->
										<tbody>
											<tr><!-- item -->
												<td class="col-md-2"><strong>Hoofdstuk 1</strong></td>
												<td class="col-md-4">Werkzaamheid 1</td>
												<td class="col-md-2">6</td>
												<td class="col-md-2">42</td>
												<td class="col-md-2">83</td>
											</tr>
											<tr><!-- item -->
												<td class="col-md-2">&nbsp;</td>
												<td class="col-md-4">Werkzaamheid 2</td>
												<td class="col-md-2">6</td>
												<td class="col-md-2">42</td>
												<td class="col-md-2">42</td>
											</tr>
											<tr><!-- item -->
												<td class="col-md-2">&nbsp;</td>
												<td class="col-md-4">Werkzaamheid 3</td>
												<td class="col-md-2">6</td>
												<td class="col-md-2">42</td>
												<td class="col-md-2">83</td>
											</tr>
										</tbody>
									</table>

								</div>
							</div>

							<div class="toggle active">
								<label>Meerwerk</label>
								<div class="toggle-content">
									<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas metus nulla, commodo a sodales sed, dignissim pretium nunc. Nam et lacus neque. Ut enim massa, sodales tempor convallis et, iaculis ac massa. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas metus nulla, commodo a sodales sed, dignissim pretium nunc. Nam et lacus neque. Ut enim massa, sodales tempor convallis et, iaculis ac massa.</p>
								</div>
							</div>

							<div class="toggle active">
								<label>Stelposten	</label>
								<div class="toggle-content">
									<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas metus nulla, commodo a sodales sed, dignissim pretium nunc. Nam et lacus neque. Ut enim massa, sodales tempor convallis et, iaculis ac massa. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas metus nulla, commodo a sodales sed, dignissim pretium nunc. Nam et lacus neque. Ut enim massa, sodales tempor convallis et, iaculis ac massa.</p>
								</div>
							</div>

						</div>
					</div>

				</div>
			</div>
		</div>

	</section>

</div>
<!-- /WRAPPER -->
@stop
