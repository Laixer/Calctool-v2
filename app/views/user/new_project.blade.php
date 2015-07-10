@extends('layout.master')

@section('content')
<?# -- WRAPPER -- ?>
<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			@if(Session::get('success'))
			<div class="alert alert-success">
				<i class="fa fa-check-circle"></i>
				<strong>Opgeslagen</strong>
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

			<h2><strong>Nieuw</strong> project</h2>

			@if(!Relation::where('user_id','=', Auth::user()->id)->count())
			<div class="alert alert-info">
				<i class="fa fa-info-circle"></i>
				<strong>Let Op!</strong> Maak eerst een opdrachtgever aan onder {{ HTML::link('/relation/new', 'nieuwe relatie') }}.
			</div>
			@endif

			{{ Form::open(array('url' => 'project/new')) }}
				<h4>Projectgegevens</h4>
				<div class="row">

					<div class="col-md-6">
						<div class="form-group">
							<label for="name">Projectnaam</label>
							<input name="name" id="name" type="text" value="{{ Input::old('name') }}" class="form-control" />
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="contractor">Opdrachtgever</label>
							<select name="contractor" id="contractor" class="form-control pointer">
							@foreach (Relation::where('user_id','=', Auth::user()->id)->get() as $relation)
								<option value="{{ $relation->id }}">{{ ucwords($relation->company_name) }}</option>
							@endforeach
							</select>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label for="type">Type</label>
							<select name="type" id="type" class="form-control pointer">
								@foreach (ProjectType::all() as $type)
									<option value="{{ $type->id }}">{{ ucwords($type->type_name) }}</option>
								@endforeach
							</select>
						</div>
					</div>

				</div>

				<h4>Adres project</h4>
				<div class="row">

					<div class="col-md-4">
						<div class="form-group">
							<label for="street">Straat</label>
							<input name="street" id="street" type="text" value="{{ Input::old('street') }}" class="form-control"/>
						</div>
					</div>
					<div class="col-md-1">
						<div class="form-group">
							<label for="address_number">Huis nr.</label>
							<input name="address_number" id="address_number" type="text" value="{{ Input::old('address_number') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="zipcode">Postcode</label>
							<input name="zipcode" id="zipcode" type="text" maxlength="6" value="{{ Input::old('zipcode') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="city">Plaats</label>
							<input name="city" id="city" type="text" value="{{ Input::old('city') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="province">Provincie</label>
							<select name="province" id="province" class="form-control pointer">
								@foreach (Province::all() as $province)
									<option value="{{ $province->id }}">{{ ucwords($province->province_name) }}</option>
								@endforeach
							</select>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label for="country">Land</label>
							<select name="country" id="country" class="form-control pointer">
								@foreach (Country::all() as $country)
									<option value="{{ $country->id }}">{{ ucwords($country->country_name) }}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>

				<h4>Opmerkingen</h4>
				<div class="row">
					<div class="form-group">
						<div class="col-md-12">
							<textarea name="note" id="note" rows="5" class="form-control">{{ Input::old('note') }}</textarea>
						</div>
					</div>
				</div>

				<h4>Financieel</h4>
				<div class="tabs nomargin-top">

					<?# -- tabs -- ?>
					<ul class="nav nav-tabs">
						<li class="active">
							<a href="#calc" data-toggle="tab">Uurtarief & Winstpercentages</a>
						</li>
					</ul>

					<?# -- tabs content -- ?>
					<div class="tab-content">
						<div id="calc" class="tab-pane active">
							<div class="row">
								<div class="col-md-3"><h5><strong>Eigen uurtarief</strong></h5></div>
								<div class="col-md-1"></div>
								<div class="col-md-2"><h5><strong>Calculatie</strong></h5></div>
								<div class="col-md-2"><h5><strong>Meerwerk</strong></h5></div>
							</div>
							<div class="row">
								<div class="col-md-3"><label for="hour_rate">Uurtarief excl. BTW</label></div>
								<div class="col-md-1"><div class="pull-right">&euro;</div></div>
								<div class="col-md-2">
									<input name="hour_rate" id="hour_rate" type="text" min="0" value="{{ Input::old('hour_rate') ? Input::old('hour_rate') : 0 }}" class="form-control-sm-number"/>
								</div>
								<div class="col-md-2">
									<input name="more_hour_rate" id="more_hour_rate" type="text" min="0" max="1000" value="{{ Input::old('more_hour_rate') ? Input::old('more_hour_rate') : 0 }}" class="form-control-sm-number"/>
								</div>
							</div>
							<h5><strong>Aanneming</strong></h5>
							<div class="row">
								<div class="col-md-3"><label for="profit_material_1">Winstpercentage materiaal</label></div>
								<div class="col-md-1"><div class="pull-right">%</div></div>
								<div class="col-md-2">
									<input name="profit_material_1" id="profit_material_1" type="number" min="0" max="200" value="{{ Input::old('profit_material_1') ? Input::old('profit_material_1') : 0 }}" class="form-control-sm-number"/>
								</div>
								<div class="col-md-2">
									<input name="more_profit_material_1" id="more_profit_material_1" type="number" min="0" max="200" value="{{ Input::old('more_profit_material_1') ? Input::old('more_profit_material_1') : 0 }}" class="form-control-sm-number"/>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3"><label for="profit_equipment_1">Winstpercentage materieel</label></div>
								<div class="col-md-1"><div class="pull-right">%</div></div>
								<div class="col-md-2">
									<input name="profit_equipment_1" id="profit_equipment_1" type="number" min="0" max="200" value="{{ Input::old('profit_equipment_1') ? Input::old('profit_equipment_1') : 0 }}" class="form-control-sm-number"/>
								</div>
							<div class="col-md-2">
									<input name="more_profit_equipment_1" id="more_profit_equipment_1" type="number" min="0" max="200" value="{{ Input::old('more_profit_equipment_1') ? Input::old('more_profit_equipment_1') : 0 }}" class="form-control-sm-number"/>
								</div>
							</div>
							<h5><strong>Onderaanneming</strong></h5>
							<div class="row">
								<div class="col-md-3"><label for="profit_material_2">Winstpercentage materiaal</label></div>
								<div class="col-md-1"><div class="pull-right">%</div></div>
								<div class="col-md-2">
									<input name="profit_material_2" id="profit_material_2" type="number" min="0" max="200" value="{{ Input::old('profit_material_2') ? Input::old('profit_material_2') : 0 }}" class="form-control-sm-number"/>
								</div>
							<div class="col-md-2">
									<input name="more_profit_material_2" id="more_profit_material_2" type="number" min="0" max="200" value="{{ Input::old('more_profit_material_2') ? Input::old('more_profit_material_2') : 0 }}" class="form-control-sm-number"/>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3"><label for="profit_equipment_2">Winstpercentage materieel</label></div>
								<div class="col-md-1"><div class="pull-right">%</div></div>
								<div class="col-md-2">
									<input name="profit_equipment_2" id="profit_equipment_2" type="number" min="0" max="200" value="{{ Input::old('profit_equipment_2') ? Input::old('profit_equipment_2') : 0 }}" class="form-control-sm-number"/>
								</div>
							<div class="col-md-2">
									<input name="more_profit_equipment_2" id="more_profit_equipment_2" type="number" min="0" max="200" value="{{ Input::old('more_profit_equipment_2') ? Input::old('more_profit_equipment_2') : 0 }}" class="form-control-sm-number"/>
								</div>
							</div>
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
