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

			<h2><strong>Nieuwe</strong> relatie</h2>

				{{ Form::open(array('url' => 'relation/new')) }}
				<div class="row">

					<div class="col-md-4">
						<div class="form-group">
							<label for="relationkind">Relatiesoort</label>
							<select name="relationkind" id="relationkind" class="form-control pointer">
							@foreach (RelationKind::all() as $kind)
								<option value="{{ $kind->id }}">{{ ucwords($kind->kind_name) }}</option>
							@endforeach
							</select>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label for="debtor">Debiteurnr</label>
							<input name="debtor" id="debtor" type="text" value="{{ Input::old('debtor') }}" class="form-control"/>
						</div>
					</div>

				</div>

				<h4 class="company">Bedrijfsgegevens</h4>
				<div class="row company">

					<div class="col-md-8">
						<div class="form-group">
							<label for="company_name">Bedrijfsnaam</label>
							<input name="company_name" id="company_name" type="text" value="{{ Input::old('company_name') }}" class="form-control" />
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label for="company_type">Bedrijfstype</label>
							<select name="company_type" id="company_type" class="form-control pointer">
							@foreach (RelationType::all() as $type)
								<option value="{{ $type->id }}">{{ ucwords($type->type_name) }}</option>
							@endforeach
							</select>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label for="kvk">KVK nr</label>
							<input name="kvk" id="kvk" type="number" value="{{ Input::old('kvk') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label for="btw">BTW nr</label>
							<input name="btw" id="btw" type="text" value="{{ Input::old('btw') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="telephone_comp">Telefoonnr</label>
							<input name="telephone_comp" id="telephone_comp" type="number" value="{{ Input::old('telephone_comp') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="email_comp">Email</label>
							<input name="email_comp" id="email_comp" type="email" value="{{ Input::old('email_comp') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="website">Website</label>
							<input name="website" id="website" type="url" value="{{ Input::old('website') }}" class="form-control"/>
						</div>
					</div>
				</div>

				<h4>Contactgegevens</h4>
				<div class="row">

					<div class="col-md-3">
						<div class="form-group">
							<label for="contact_name">Naam</label>
							<input name="contact_name" id="contact_name" type="text" value="{{ Input::old('contact_name') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="contact_firstname">Voornaam</label>
							<input name="contact_firstname" id="contact_firstname" type="text" value="{{ Input::old('contact_firstname') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="mobile">Mobiel</label>
							<input name="mobile" id="mobile" type="text" value="{{ Input::old('mobile') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="telephone">Telefoonnr</label>
							<input name="telephone" id="telephone" type="text" value="{{ Input::old('telephone') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="email">Email</label>
							<input name="email" id="email" type="email" value="{{ Input::old('email') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label for="contactfunction">Functie</label>
							<select name="contactfunction" id="contactfunction" class="form-control pointer">
							@foreach (ContactFunction::all() as $function)
								<option value="{{ $function->id }}">{{ ucwords($function->function_name) }}</option>
							@endforeach
							</select>
						</div>
					</div>

				</div>

				<h4>Adresgegevens</h4>
				<div class="row">

					<div class="col-md-4">
						<div class="form-group">
							<label for="street">Straat</label>
							<input name="street" id="street" type="text" value="{{ Input::old('street') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-1">
						<div class="form-group">
							<label for="address_number">Huisnr</label>
							<input name="address_number" id="address_number" type="text" value="{{ Input::old('address_number') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="zipcode">Postcode</label>
							<input name="zipcode" id="zipcode" type="text" value="{{ Input::old('zipcode') }}" class="form-control"/>
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

				<h4>Betalingsgegevens</h4>
				<div class="row">

					<div class="col-md-3">
						<div class="form-group">
							<label for="iban">IBAN rekeningnummer</label>
							<input name="iban" id="iban" type="text" value="{{ Input::old('iban') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="btw">IBAN naam rekeningnummer </label>
							<input name="iban_name" id="iban_name" type="text" value="{{ Input::old('iban_name') }}" class="form-control"/>
						</div>
					</div>

				</div>

				<h4>Opmerkingen</h4>
				<div class="row">
					<div class="form-group">
						<div class="col-md-12">
							<textarea name="note" id="note" rows="10" class="form-control">{{ Input::old('note') }}</textarea>
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
