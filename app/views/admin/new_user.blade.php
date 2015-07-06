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

			<h2><strong>Nieuwe</strong> gebruiker</h2>

				{{ Form::open(array('url' => '/admin/user/new')) }}

				<h4 class="company">Gebruikersgegevens</h4>
				<div class="row company">


					<div class="col-md-5">
						<div class="form-group">
							<label for="company_name">Gebruikersnaam</label>
							<input name="username" id="username" type="text" value="{{ Input::old('username') }}" class="form-control" />
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="user_type">Gebruikers type</label>
							<select name="type" id="type" class="form-control pointer">
								@foreach (UserType::all() as $type)
									<option value="{{ $type->id }}">{{ ucwords($type->user_type) }}</option>
								@endforeach
							</select>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="secret">Wachtwoord</label>
							<input name="secret" id="secret" class="form-control">
						</div>
					</div>

				</div>

				<h4>Contactgegevens</h4>
				<div class="row">

					<div class="col-md-3">
						<div class="form-group">
							<label for="lastname">Naam</label>
							<input name="lastname" id="lastname" type="text" value="{{ Input::old('lastname') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="firstname">Voornaam</label>
							<input name="firstname" id="firstname" type="text" value="{{ Input::old('firstname') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="mobile">Mobiel</label>
							<input name="mobile" id="mobile" type="text" maxlength="12" value="{{ Input::old('mobile') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="telephone">Telefoonnummer</label>
							<input name="telephone" id="telephone" type="text" maxlength="12" value="{{ Input::old('telephone') }}" class="form-control"/>
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
							<label for="website">Website</label>
							<input name="website" id="website" type="url" value="{{ Input::old('website') }}" class="form-control"/>
						</div>
					</div>

				</div>

				<h4>Adresgegevens</h4>
				<div class="row">

					<div class="col-md-4">
						<div class="form-group">
							<label for="address_street">Straat</label>
							<input name="address_street" id="address_street" type="text" value="{{ Input::old('address_street') }}" class="form-control"/>
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
							<label for="address_zipcode">Postcode</label>
							<input name="address_zipcode" id="address_zipcode" maxlength="6" type="text" value="{{ Input::old('address_zipcode') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="address_city">Plaats</label>
							<input name="address_city" id="address_city" type="text" value="{{ Input::old('address_city') }}" class="form-control"/>
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
							<label for="btw">Naam rekeninghouder</label>
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

