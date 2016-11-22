<?php

use \Calctool\Models\Wholesale;
use \Calctool\Models\WholesaleType;
use \Calctool\Models\Province;
use \Calctool\Models\Country;

$common_access_error = false;
$wholesale = Wholesale::find(Route::Input('wholesale_id'));
if (!$wholesale || !$wholesale->isOwner() || !$wholesale->isActive()) {
	$common_access_error = true;
}
?>

@extends('layout.master')

<?php if($common_access_error){ ?>
@section('content')
<div id="wrapper">
	<section class="container">
		<div class="alert alert-danger">
			<i class="fa fa-frown-o"></i>
			<strong>Fout</strong>
			Deze relatie bestaat niet
		</div>
	</section>
</div>
@stop
<?php }else{ ?>

@section('content')
<script type="text/javascript" src="/js/iban.js"></script>
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
	$('#kvk').blur(function() {
		var kvkcheck = $(this).val();
		if (kvkcheck.length != 8) {
			$(this).parent().addClass('has-error');
		} else {
			$(this).parent().removeClass('has-error');
		}
	});

	$('#street').blur(function() {
		var streetcheck = $(this).val();
		var regx = /^[A-Za-z]+$/;
		if( streetcheck != "" && regx.test(streetcheck)) {
			$(this).removeClass("error-input");
		}else {
			$(this).addClass("error-input");
		}
	});
});
</script>

<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			@if (Session::has('success'))
			<div class="alert alert-success">
				<i class="fa fa-check-circle"></i>
				<strong>@if (Session::get('success'))</strong>
			</div>
			@endif

			@if (count($errors) > 0)
			<div class="alert alert-danger">
				<i class="fa fa-frown-o"></i>
				<strong>Fout</strong>
				@foreach ($errors->all() as $error)
					{{ $error }}
				@endforeach
			</div>
			@endif

			<div>
			<ol class="breadcrumb">
			  <li><a href="/">Home</a></li>
			  <li><a href="/wholesale">Leveranciers</a></li>
			 <li>{{ $wholesale->company_name }}</li>
			</ol>
			<div>
			<br>

			<h2><strong>Leverancier</strong> {{ $wholesale->company_name }}</h2>

				<div class="tabs nomargin-top">

					<ul class="nav nav-tabs">
						<li class="active">
							<a href="#company" data-toggle="tab">Bedrijfsgegevens</a>
						</li>
						<li>
							<a href="#payment" data-toggle="tab">Betalingsgegevens</a>
						</li>
					</ul>

					<div class="tab-content">
						<div id="company" class="tab-pane active">
							<div class="pull-right">
								<a href="/wholesale-{{ $wholesale->id }}/delete" id="acc-deactive" class="btn btn-danger">Verwijderen</a>
							</div>

							<form method="POST" action="/wholesale/update" accept-charset="UTF-8">
			                {!! csrf_field() !!}
			                <input type="hidden" name="id" id="id" value="{{ $wholesale->id }}"/>
							<h4 class="company">Bedrijfsgegevens</h4>
							<div class="row company">

								<div class="col-md-5">
									<div class="form-group">
										<label for="company_name">Bedrijfsnaam*</label>
										<input name="company_name" id="company_name" type="text" value="{{ Input::old('company_name') ? Input::old('company_name') : $wholesale->company_name }}" class="form-control" />
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="company_type">Bedrijfstype*</label>
										<select name="company_type" id="company_type" class="form-control pointer">
										@foreach (WholesaleType::all() as $type)
											<option {{ $wholesale->type_id==$type->id ? 'selected' : '' }} value="{{ $type->id }}">{{ ucwords($type->type_name) }}</option>
										@endforeach
										</select>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="website">Website</label>
										<input name="website" id="website" type="url" value="{{ Input::old('website') ? Input::old('website') : $wholesale->website }}" class="form-control"/>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="telephone_comp">Telefoonnummer</label>
										<input name="telephone_comp" id="telephone_comp" type="text" maxlength="12" value="{{ Input::old('telephone_comp') ? Input::old('telephone_comp') : $wholesale->phone }}" class="form-control"/>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="email_comp">Email</label>
										<input name="email_comp" id="email_comp" type="email" value="{{ Input::old('email_comp') ? Input::old('email_comp') : $wholesale->email }}" class="form-control"/>
									</div>
								</div>

							</div>

							<h4>Adresgegevens</h4>
							<div class="row">

								<div class="col-md-4">
									<div class="form-group">
										<label for="street">Straat*</label>
										<input name="street" id="street" type="text" value="{{ Input::old('street') ? Input::old('street') : $wholesale->address_street }}" class="form-control"/>
									</div>
								</div>

								<div class="col-md-1">
									<div class="form-group">
										<label for="address_number">Huis nr.*</label>
										<input name="address_number" id="address_number" type="text" value="{{ Input::old('address_number') ? Input::old('address_number') : $wholesale->address_number }}" class="form-control"/>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="zipcode">Postcode*</label>
										<input name="zipcode" id="zipcode" maxlength="6" type="text" value="{{ Input::old('zipcode') ? Input::old('zipcode') : $wholesale->address_postal }}" class="form-control"/>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="city">Plaats*</label>
										<input name="city" id="city" type="text" value="{{ Input::old('city') ? Input::old('city') : $wholesale->address_city }}" class="form-control"/>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="province">Provincie*</label>
										<select name="province" id="province" class="form-control pointer">
											@foreach (Province::all() as $province)
												<option {{ $wholesale->province_id==$province->id ? 'selected' : '' }} value="{{ $province->id }}">{{ ucwords($province->province_name) }}</option>
											@endforeach
										</select>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="country">Land*</label>
										<select name="country" id="country" class="form-control pointer">
											@foreach (Country::all() as $country)
												<option {{ $wholesale->country_id==$country->id ? 'selected' : '' }} value="{{ $country->id }}">{{ ucwords($country->country_name) }}</option>
											@endforeach
										</select>
									</div>
								</div>

							</div>

							<h4>Opmerkingen</h4>
							<div class="row">
								<div class="form-group">
									<div class="col-md-12">
										<textarea name="note" id="note" rows="10" class="form-control">{{ Input::old('note') ? Input::old('note') : $wholesale->note }}</textarea>
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
						<div id="payment" class="tab-pane">
							<h4>Betalingsgegevens</h4>
							<form method="POST" action="/wholesale/iban/update" accept-charset="UTF-8">
                            {!! csrf_field() !!}
                            <input type="hidden" name="id" id="id" value="{{ $wholesale->id }}"/>
							<div class="row">

								<div class="col-md-3">
									<div class="form-group">
										<label for="iban">IBAN rekeningnummer</label>
										<input name="iban" id="iban" type="text" value="{{ Input::old('iban') ? Input::old('iban') : $wholesale->iban }}" class="form-control"/>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="btw">Naam rekeninghouder</label>
										<input name="iban_name" id="iban_name" type="text" value="{{ Input::old('iban_name') ? Input::old('iban_name') : $wholesale->iban_name }}" class="form-control"/>
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
				</div>

		</div>

	</section>

</div>
@stop

<?php } ?>
