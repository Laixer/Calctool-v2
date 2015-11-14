<?php
use \Calctool\Models\UserType;
use \Calctool\Models\Province;
use \Calctool\Models\Country;
?>

@extends('layout.master')

@section('content')

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
	$('#website').blur(function(e) {
		prefixURL($(this));
	});
	$('#iban').blur(function() {
		if (! IBAN.isValid($(this).val()) ) {
			$(this).parent().addClass('has-error');
		} else {
			$(this).parent().removeClass('has-error');
		}
	});

	$("[name='toggle-api']").bootstrapSwitch();
	$("[name='toggle-active']").bootstrapSwitch();
});
</script>

<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			<div>
			<ol class="breadcrumb">
			  <li><a href="/">Home</a></li>
			  <li><a href="/admin">Admin CP</a></li>
			  <li><a href="/admin/user">Gebruikers</a></li>
			  <li class="active">Nieuwe gebruiker</li>
			</ol>
			<div>
			<br />

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
			<div class="white-row" >
				<form method="POST" action="/admin/user/new" accept-charset="UTF-8">
                {!! csrf_field() !!}

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
									<option {{ $type->user_type=='user' ? 'selected' : '' }} value="{{ $type->id }}">{{ ucwords($type->user_type) }}</option>
								@endforeach
							</select>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="secret">Wachtwoord</label>
							<input name="secret" type="password" id="secret" class="form-control">
						</div>
					</div>

				</div>

				<h4>Contactgegevens</h4>
				<div class="row">

					<div class="col-md-2">
						<div class="form-group">
							<label for="firstname">Voornaam</label>
							<input name="firstname" id="firstname" type="text" value="{{ Input::old('firstname') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="lastname">Achternaam</label>
							<input name="lastname" id="lastname" type="text" value="{{ Input::old('lastname') }}" class="form-control"/>
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
					<div class="col-md-3">
						<div class="form-group">
							<label for="gender" style="display:block;">Geslacht</label>
							<select name="gender" id="gender" class="form-control pointer">
								<option value="-1">Selecteer</option>
								<option value="M">Man</option>
								<option value="V">Vrouw</option>
							</select>
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
									<option {{ $country->country_name=='nederland' ? 'selected' : '' }} value="{{ $country->id }}">{{ ucwords($country->country_name) }}</option>
								@endforeach
							</select>
						</div>
					</div>

				</div>

				<h4>Overig</h4>
				<div class="row">

					<div class="col-md-3">
						<div class="form-group">
							<label for="iban">Abonnement verloopdatum</label>
							<input name="expdate" id="expdate" type="date" value="{{ Input::old('expdate') ? Input::old('expdate') : date('Y-m-d', strtotime('+1 month', time())) }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="iban">Activeringsdatum</label>
							<input name="confirmdate" id="confirmdate" type="date" value="{{ Input::old('confirmdate') ? Input::old('confirmdate') : date('Y-m-d') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="iban">Blokkeeringsdatum</label>
							<input name="bandate" id="bandate" type="date" value="{{ Input::old('bandate') ? Input::old('bandate') : '' }}" class="form-control"/>
						</div>
					</div>
				</div>
				<div class="row">

					<div class="col-md-2">
						<div class="form-group">
							<label for="toggle-api" style="display:block;">API toegang</label>
							<input name="toggle-api" type="checkbox">
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="toggle-active" style="display:block;">Actief</label>
							<input name="toggle-active" type="checkbox" checked>
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
				<h4>Kladblok</h4>
				<div class="row">
					<div class="form-group">
						<div class="col-md-12">
							<textarea name="notepad" id="notepad" rows="10" class="form-control">{{ Input::old('notepad') }}</textarea>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<button class="btn btn-primary"><i class="fa fa-check"></i> Opslaan</button>
					</div>
				</div>

			</form>
			</div>

		</div>

	</section>

</div>

@stop