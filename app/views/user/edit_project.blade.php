<?php
$project = Project::find(Route::Input('project_id'));
?>

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
				note: $note
			}, function(data){
				var $curTable = $curThis.closest("table");
				var json = $.parseJSON(data);
				$curTable.find("tr:eq(1)").clone().removeAttr("data-id")
				.find("td:eq(0)").text($date).end()
				.find("td:eq(1)").text(json.hour).end()
				.find("td:eq(2)").text(json.type).end()
				.find("td:eq(3)").text(json.activity).end()
				.find("td:eq(4)").text($note).end()
				.prependTo($curTable);
			});
		});
	});
</script>
<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			@if(Session::get('success'))
			<div class="alert alert-success">
				<i class="fa fa-check-circle"></i>
				<strong>Aangepast</strong>
			</div>
			@endif

			@if($errors->has())
			<div class="alert alert-danger">
				<i class="fa fa-frown-o"></i>
				<strong>Fout</strong>
				@foreach ($errors->all() as $error)
					{{ $error }}
				@endforeach
			</div>
			@endif

			<div class="wizard">
			    <a href="/"> Home</a>
			    <a href="javascript:void(0);" class="current">Project</a>
			    <a href="/calculation/project-{{ $project->id }}">Calculatie</a>
			    <a href="#">Offerte</a>
		    	<a href="/estimate/project-{{ $project->id }}">Stelpost</a>
		  		<a href="/less/project-{{ $project->id }}">Minderwerk</a>
		  		<a href="/more/project-{{ $project->id }}">Meerwerk</a>
			    <a href="/invoice/project-{{ $project->id }}">Factuur</a>
			    <a href="#">Winst/verlies</a>
			</div>

			<hr />

			<h2><strong>Project</strong> {{$project->project_name}}</h2>

			@if(!Relation::where('user_id','=', Auth::user()->id)->count())
			<div class="alert alert-info">
				<i class="fa fa-info-circle"></i>
				<strong>Let Op!</strong> Maak eerst een opdrachtgever aan onder {{ HTML::link('/relation/new', 'nieuwe relatie') }}.
			</div>
			@endif

			{{ Form::open(array('url' => 'project/update')) }}
				<h4>Projectgegevens</h4>
				<div class="row">

					<div class="col-md-6">
						<div class="form-group">
							<label for="name">Projectnaam</label>
							<input name="name" id="name" type="text" value="{{ Input::old('name') ? Input::old('name') : $project->project_name }}" class="form-control" />
							<input type="hidden" name="id" id="id" value="{{ $project->id }}"/>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="contractor">Opdrachtgever</label>
							<select name="contractor" id="contractor" class="form-control pointer">
							@foreach (Relation::where('user_id','=', Auth::user()->id)->get() as $relation)
								<option {{ $project->client_id==$relation->id ? 'selected' : '' }} value="{{ $relation->id }}">{{ ucwords($relation->company_name) }}</option>
							@endforeach
							</select>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label for="type">Type</label>
							<select name="type" id="type" class="form-control pointer">
								@foreach (ProjectType::all() as $type)
									<option {{ $project->type_id==$type->id ? 'selected' : '' }} value="{{ $type->id }}">{{ ucwords($type->type_name) }}</option>
								@endforeach
							</select>
						</div>
					</div>

				</div>

				<h4>Project adresgegevens</h4>
				<div class="row">

					<div class="col-md-4">
						<div class="form-group">
							<label for="street">Straat</label>
							<input name="street" id="street" type="text" value="{{ Input::old('street') ? Input::old('street') : $project->address_street}}" class="form-control"/>
						</div>
					</div>
					<div class="col-md-1">
						<div class="form-group">
							<label for="address_number">Huis nr.</label>
							<input name="address_number" id="address_number" type="text" value="{{ Input::old('address_number') ? Input::old('address_number') : $project->address_number }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="zipcode">Postcode</label>
							<input name="zipcode" id="zipcode" type="text" maxlength="6" value="{{ Input::old('zipcode') ? Input::old('zipcode') : $project->address_postal }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="city">Plaats</label>
							<input name="city" id="city" type="text" value="{{ Input::old('city') ? Input::old('city'): $project->address_city }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="province">Provincie</label>
							<select name="province" id="province" class="form-control pointer">
								@foreach (Province::all() as $province)
									<option {{ $project->province_id==$province->id ? 'selected' : '' }} value="{{ $province->id }}">{{ ucwords($province->province_name) }}</option>
								@endforeach
							</select>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label for="country">Land</label>
							<select name="country" id="country" class="form-control pointer">
								@foreach (Country::all() as $country)
									<option {{ $project->country_id==$country->id ? 'selected' : '' }} value="{{ $country->id }}">{{ ucwords($country->country_name) }}</option>
								@endforeach
							</select>
						</div>
					</div>

				</div>

				<h4>Financieel</h4>
				<div class="tabs nomargin-top">

					<?# -- tabs -- ?>
					<ul class="nav nav-tabs">
						<li class="active">
							<a href="#calc" data-toggle="tab">Winst% Calculatie</a>
						</li>
						<li>
							<a href="#more" data-toggle="tab">Winst% Meerwerk</a>
						</li>
						<li>
							<a href="#hour" data-toggle="tab">Urenregistratie</a>
						</li>
						<li>
							<a href="#hour_overview" data-toggle="tab">Uittrekstaat urenregistratie</a>
						</li>
					</ul>

					<?# -- tabs content -- ?>
					<div class="tab-content">
						<div id="calc" class="tab-pane active">
							<h5><strong>Eigen uurtarief</strong></h5>
							<div class="row">
								<div class="col-md-2"><label for="hour_rate">Uurtarief excl. BTW</label></div>
								<div class="col-md-1"><div class="pull-right">&euro;</div></div>
								<div class="col-md-2">
									<input name="hour_rate" id="hour_rate" type="text" min="0" max="1000" value="{{ Input::old('hour_rate') ? Input::old('hour_rate') : $project->hour_rate }}" class="form-control-sm-number"/>
								</div>
							</div>

							<h5><strong>Aanneming</strong></h5>
							<div class="row">
								<div class="col-md-3"><label for="profit_material_1">Winstpercentage materiaal</label></div>
								<div class="col-md-2">
									<input name="profit_material_1" id="profit_material_1" type="number" min="0" max="200" value="{{ Input::old('profit_material_1') ? Input::old('profit_material_1') : $project->profit_calc_contr_mat }}" class="form-control-sm-number"/>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3"><label for="profit_equipment_1">Winstpercentage materieel</label></div>
								<div class="col-md-2">
									<input name="profit_equipment_1" id="profit_equipment_1" type="number" min="0" max="200" value="{{ Input::old('profit_equipment_1') ? Input::old('profit_equipment_1') : $project->profit_calc_contr_equip }}" class="form-control-sm-number"/>
								</div>
							</div>

							<h5><strong>Onderaanneming</strong></h5>
							<div class="row">
								<div class="col-md-3"><label for="profit_material_2">Winstpercentage materiaal</label></div>
								<div class="col-md-2">
									<input name="profit_material_2" id="profit_material_2" type="number" min="0" max="200" value="{{ Input::old('profit_material_2') ? Input::old('profit_material_2') : $project->profit_calc_subcontr_mat }}" class="form-control-sm-number"/>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3"><label for="profit_equipment_2">Winstpercentage materieel</label></div>
								<div class="col-md-2">
									<input name="profit_equipment_2" id="profit_equipment_2" type="number" min="0" max="200" value="{{ Input::old('profit_equipment_2') ? Input::old('profit_equipment_2') : $project->profit_calc_subcontr_equip }}" class="form-control-sm-number"/>
								</div>
							</div>

							<h5><strong>Stelpost</strong></h5>
							<div class="row">
								<div class="col-md-3"><label for="profit_material_3">Winstpercentage materiaal</label></div>
								<div class="col-md-2">
									<input name="profit_material_3" id="profit_material_3" type="number" min="0" max="200" value="{{ Input::old('profit_material_3') ? Input::old('profit_material_3') : 0 }}" class="form-control-sm-number"/>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3"><label for="profit_equipment_3">Winstpercentage materieel</label></div>
								<div class="col-md-2">
									<input name="profit_equipment_3" id="profit_equipment_3" type="number" min="0" max="200" value="{{ Input::old('profit_equipment_3') ? Input::old('profit_equipment_3') : 0 }}" class="form-control-sm-number"/>
								</div>
							</div>
						</div>

						<div id="more" class="tab-pane">
							<h5><strong>Eigen uurtarief</strong></h5>
							<div class="row">
								<div class="col-md-2"><label for="more_hour_rate">Uurtarief excl. BTW</label></div>
								<div class="col-md-1"><div class="pull-right">&euro;</div></div>
								<div class="col-md-2">
									<input name="more_hour_rate" id="more_hour_rate" type="text" min="0" max="1000" value="{{ Input::old('more_hour_rate') ? Input::old('more_hour_rate') : $project->hour_rate_more }}" class="form-control-sm-number"/>
								</div>
							</div>

							<h5><strong>Aanneming</strong></h5>
							<div class="row">
								<div class="col-md-3"><label for="more_profit_material_1">Winstpercentage materiaal</label></div>
								<div class="col-md-2">
									<input name="more_profit_material_1" id="more_profit_material_1" type="number" min="0" max="200" value="{{ Input::old('more_profit_material_1') ? Input::old('more_profit_material_1') : $project->profit_more_contr_mat }}" class="form-control-sm-number"/>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3"><label for="more_profit_equipment_1">Winstpercentage materieel</label></div>
								<div class="col-md-2">
									<input name="more_profit_equipment_1" id="more_profit_equipment_1" type="number" min="0" max="200" value="{{ Input::old('more_profit_equipment_1') ? Input::old('more_profit_equipment_1') : $project->profit_more_contr_equip }}" class="form-control-sm-number"/>
								</div>
							</div>

							<h5><strong>Onderaanneming</strong></h5>
							<div class="row">
								<div class="col-md-3"><label for="more_profit_material_2">Winstpercentage materiaal</label></div>
								<div class="col-md-2">
									<input name="more_profit_material_2" id="more_profit_material_2" type="number" min="0" max="200" value="{{ Input::old('more_profit_material_2') ? Input::old('more_profit_material_2') : $project->profit_more_subcontr_mat }}" class="form-control-sm-number"/>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3"><label for="more_profit_equipment_2">Winstpercentage materieel</label></div>
								<div class="col-md-2">
									<input name="more_profit_equipment_2" id="more_profit_equipment_2" type="number" min="0" max="200" value="{{ Input::old('more_profit_equipment_2') ? Input::old('more_profit_equipment_2') : $project->profit_more_subcontr_equip }}" class="form-control-sm-number"/>
								</div>
							</div>
						</div>


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
												<th class="col-md-1">Werkzaamheid</th>
												<th class="col-md-4">Omschrijving</th>
												<th class="col-md-1">&nbsp;</th>
												<th class="col-md-1">&nbsp;</th>
												<th class="col-md-1">&nbsp;</th>
												<th class="col-md-1">&nbsp;</th>
											</tr>
										</thead>

										<!-- table items -->
										<tbody>
											@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
											@foreach (Activity::where('chapter_id','=', $chapter->id)->get() as $activity)
											@foreach (Timesheet::where('activity_id','=', $activity->id)->get() as $timesheet)
											<?php
												$typename;
												if (PartType::find($activity->part_type_id)->type_name == 'calculation') {
													$typename = 'Aanneming';
													if ($activity->detail_id) {
														if (Detail::find($activity->detail_id)->detail_name == 'more') {
															$typename = 'Meerwerk';
														}
													}
												} else {
													$typename = 'Stelpost';
												}
											?>
											<tr><!-- item -->
												<td class="col-md-1">{{ $timesheet->register_date }}</td>
												<td class="col-md-1">{{ number_format($timesheet->register_hour, 2,",",".") }}</td>
												<td class="col-md-1">{{ $typename; }}</td>
												<td class="col-md-4">{{ $activity->activity_name }}</td>
												<td class="col-md-1">{{ $timesheet->note }}</td>
												<td class="col-md-1">&nbsp;</td>
												<td class="col-md-1">&nbsp;</td>
												<td class="col-md-1"><button class="btn btn-danger btn-xs fa fa-times"></button></td>
											</tr>
											@endforeach
											@endforeach
											@endforeach
											<tr><!-- item -->
												<td class="col-md-1"><input type="date" name="date" id="date" class="form-control-sm-text"/></td>
												<td class="col-md-1"><input type="number" min="0" name="hour" id="hour" class="form-control-sm-text"/></td>
												<td class="col-md-1">
													<select name="typename" id="typename" class="form-control-sm-text">
														<option value="1" selected="selected">Aanneming</option>
														<option value="2" selected="selected">Meerwerk</option>
														<option value="3" selected="selected">Stelpost</option>
													</select>
												</td>
												<td class="col-md-4">
													<select name="activity" id="activity" class="form-control-sm-text">
													@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
													@foreach (Activity::where('chapter_id','=', $chapter->id)->get() as $activity)
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

						<div id="hour_overview" class="tab-pane">
							<div class="toogle">
								<div class="toggle">
									<label>Anneming</label>
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
							</div>
						</div>
					</div>

				<h4>Opmerkingen</h4>
				<div class="row">
					<div class="form-group">
						<div class="col-md-12">
							<textarea name="note" id="note" rows="10" class="form-control">{{ Input::old('note') ? Input::old('note') : $project->note }}</textarea>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<button class="btn btn-primary"><i class="fa fa-check"></i> Opslaan</button>
					</div>
				</div>
			{{ Form::close() }}

		</div>

	</section>

</div>
<!-- /WRAPPER -->
@stop
