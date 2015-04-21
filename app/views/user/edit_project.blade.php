<?php
$project = Project::find(Route::Input('project_id'));
?>

@extends('layout.master')

@section('content')
<?# -- WRAPPER -- ?>
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

			<div class="fuelux">
				<div id="calculation-wizard" class="wizard">
					<ul class="steps">
						<li data-target="#step0" data-location="/" class="complete">Home<span class="chevron"></span></li>
						<li data-target="#step1" data-location="/project-{{ $project->id }}/edit" class="complete">Projectgegevens<span class="chevron"></span></li>
						<li data-target="#step2" data-location="/calculation/project-{{ $project->id }}" class="active">Calculatie<span class="chevron"></span></li>
						<li data-target="#step3">Offerte<span class="chevron"></span></li>
						<li data-target="#step4" data-location="/estimate/project-{{ $project->id }}" class="complete">Stelpost<span class="chevron"></span></li>
						<li data-target="#step5">Minderwerk<span class="chevron"></span></li>
						<li data-target="#step6">Meerwerk<span class="chevron"></span></li>
						<li data-target="#step7">Factuur<span class="chevron"></span></li>
						<li data-target="#step8">Winst/Verlies<span class="chevron"></span></li>
					</ul>
				</div>
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
							<a href="#hour_overview" data-toggle="tab">Uitrekstaat urenregistratie</a>
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
												<th class="col-md-1">BTW</th>
												<th class="col-md-2">Hoofdstuk</th>
												<th class="col-md-4">Werkzaamheid</th>
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
												$tax;
												if (PartType::find($timesheet->part_type_id)->type_name == 'calculation') {
													$typename = 'Aanneming';
													$tax = Tax::find($activity->tax_calc_labor_id)->tax_rate;
													if ($timesheet->detail_id) {
														if (Detail::find($timesheet->detail_id)->detail_name == 'more') {
															$typename = 'Meerwerk';
															$tax = Tax::find($activity->tax_more_labor_id)->tax_rate;
														}
													}
												} else {
													$typename = 'Stelpost';
													$tax = Tax::find($activity->tax_estimate_labor_id)->tax_rate;
												}
											?>
											<tr><!-- item -->
												<td class="col-md-1">{{ $timesheet->register_date }}</td>
												<td class="col-md-1">{{ $timesheet->register_hour }}</td>
												<td class="col-md-1">{{ $typename; }}</td>
												<td class="col-md-1">{{ $tax }}%</td>
												<td class="col-md-2">{{ $chapter->chapter_name }}</td>
												<td class="col-md-4">{{ $activity->activity_name }}</td>
												<td class="col-md-1"><button class="btn btn-primary btn-xs fa fa-comment-o"> Notitie</button></td>
												<td class="col-md-1"><button class="btn btn-danger btn-xs fa fa-times"></button></td>
											</tr>
											@endforeach
											@endforeach
											@endforeach
											<tr><!-- item -->
												<td class="col-md-1"><input type="date" class="form-control-sm-text"/></td>
												<td class="col-md-1"><input type="number" min="0" class="form-control-sm-text"/></td>
												<td class="col-md-1">
													<select name="timetype" id="type" class="form-control-sm-text">
														<option value="" selected="selected">Aanneming</option>
														<option value="" selected="selected">Meerwerk</option>
														<option value="" selected="selected">Stelpost</option>
													</select>
												</td>
												<td class="col-md-1">
													<select name="timetype" id="type" class="form-control-sm-text">
														<option value="" selected="selected">21</option>
													</select>
												</td>
												<td class="col-md-2">
													<select name="timetype" id="type" class="form-control-sm-text">
														<option value="" selected="selected">Badkamer</option>
														<option value="" selected="selected">Vloer</option>
													</select>
												</td>
												<td class="col-md-4">
													<select name="timetype" id="type" class="form-control-sm-text">
														<option value="" selected="selected">Vervangen van vloer met cement</option>
													</select>
												</td>
												<td class="col-md-1"><button class="btn btn-primary btn-xs fa fa-comment-o"> Notitie</button></td>
												<td class="col-md-1">&nbsp;</button></td>
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
