<?php
$relation = Relation::find(Route::Input('relation_id'));
?>

@extends('layout.master')

@section('content')
<?# -- WRAPPER -- ?>

<script type="text/javascript">
$(document).ready(function() {
	function prefixURL(field) {
		var cur_val = $(field).val();
		if (!cur_val)
			return;
		var ini = cur_val.substring(0,4);
		if (ini == 'http')
			return;
		else {
			if (cur_val.indexOf("www") >=0) {
				$(field).val('http://' + cur_val);
			} else {
				$(field).val('http://www.' + cur_val);
			}
		}
	}

	$('#website').blur(function(e){
		prefixURL($(this));
	});
});
</script>

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

			<h2><strong>Relatie</strong> {{ $relation->company_name }}</h2>

				{{ Form::open(array('url' => 'relation/update')) }}
				<div class="row">

					<div class="col-md-2">
						<div class="form-group">
							<label for="relationkind">Relatiesoort</label>
							<select name="relationkind" id="relationkind" class="form-control pointer">
							@foreach (RelationKind::all() as $kind)
								<option {{ $relation->kind_id==$kind->id ? 'selected' : '' }} value="{{ $kind->id }}">{{ ucwords($kind->kind_name) }}</option>
							@endforeach
							</select>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="debtor">Debiteurennummer</label>
							<input name="debtor" id="debtor" type="text" value="{{ Input::old('debtor') ? Input::old('debtor') : $relation->debtor_code }}" class="form-control"/>
							<input type="hidden" name="id" id="id" value="{{ $relation->id }}"/>
						</div>
					</div>

				</div>

				<h4 class="company">Bedrijfsgegevens</h4>
				<div class="row company">

					<div class="col-md-5">
						<div class="form-group">
							<label for="company_name">Bedrijfsnaam</label>
							<input name="company_name" id="company_name" type="text" value="{{ Input::old('company_name') ? Input::old('company_name') : $relation->company_name }}" class="form-control" />
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="company_type">Bedrijfstype</label>
							<select name="company_type" id="company_type" class="form-control pointer">
							@foreach (RelationType::all() as $type)
								<option {{ $relation->type_id==$type->id ? 'selected' : '' }} value="{{ $type->id }}">{{ ucwords($type->type_name) }}</option>
							@endforeach
							</select>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label for="website">Website</label>
							<input name="website" id="website" type="url" value="{{ Input::old('website') ? Input::old('website') : $relation->website }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="kvk">K.v.K nummer</label>
							<input name="kvk" id="kvk" type="text" maxlength="12" value="{{ Input::old('kvk') ? Input::old('kvk') : $relation->kvk }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="btw">BTW nummer</label>
							<input name="btw" id="btw" type="text" maxlength="14" value="{{ Input::old('btw') ? Input::old('btw') : $relation->btw }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="telephone_comp">Telefoonnummer</label>
							<input name="telephone_comp" id="telephone_comp" type="text" maxlength="12" value="{{ Input::old('telephone_comp') ? Input::old('telephone_comp') : $relation->phone }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="email_comp">Email</label>
							<input name="email_comp" id="email_comp" type="email" value="{{ Input::old('email_comp') ? Input::old('email_comp') : $relation->email }}" class="form-control"/>
						</div>
					</div>

				</div>

				<h4>Adresgegevens</h4>
				<div class="row">

					<div class="col-md-4">
						<div class="form-group">
							<label for="street">Straat</label>
							<input name="street" id="street" type="text" value="{{ Input::old('street') ? Input::old('street') : $relation->address_street }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-1">
						<div class="form-group">
							<label for="address_number">Huis nr.</label>
							<input name="address_number" id="address_number" type="text" value="{{ Input::old('address_number') ? Input::old('address_number') : $relation->address_number }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="zipcode">Postcode</label>
							<input name="zipcode" id="zipcode" maxlength="6" type="text" value="{{ Input::old('zipcode') ? Input::old('zipcode') : $relation->address_postal }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="city">Plaats</label>
							<input name="city" id="city" type="text" value="{{ Input::old('city') ? Input::old('city') : $relation->address_city }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="province">Provincie</label>
							<select name="province" id="province" class="form-control pointer">
								@foreach (Province::all() as $province)
									<option {{ $relation->province_id==$province->id ? 'selected' : '' }} value="{{ $province->id }}">{{ ucwords($province->province_name) }}</option>
								@endforeach
							</select>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label for="country">Land</label>
							<select name="country" id="country" class="form-control pointer">
								@foreach (Country::all() as $country)
									<option {{ $relation->country_id==$country->id ? 'selected' : '' }} value="{{ $country->id }}">{{ ucwords($country->country_name) }}</option>
								@endforeach
							</select>
						</div>
					</div>

				</div>

				<h4>Opmerkingen</h4>
				<div class="row">
					<div class="form-group">
						<div class="col-md-12">
							<textarea name="note" id="note" rows="10" class="form-control">{{ Input::old('note') ? Input::old('note') : $relation->note }}</textarea>
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
<?#-- /WRAPPER --?>
<script type="text/javascript">
$(document).ready(function() {
	<?php $response = RelationKind::where('id','=',Input::old('relationkind'))->first(); ?>
	if('{{ ($response ? $response->kind_name : 'zakelijk') }}'=='particulier'){
		$('.company').hide();
		$('#relationkind option[value="{{ Input::old('relationkind') }}"]').attr("selected",true);
	}
	$('#relationkind').change(function() {
		$('.company').toggle('slow');
		console.log('check');
	});
});
</script>
@stop
