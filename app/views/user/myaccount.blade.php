<?php
$user = Auth::user();
$iban = Iban::where('user_id','=',$user->id)->where('relation_id','=',null)->first();
?>

@extends('layout.master')

@section('content')
<?# -- WRAPPER -- ?>

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
	$('#iban').blur(function() {
		if (! IBAN.isValid($(this).val()) ) {
			$(this).parent().addClass('has-error');
		} else {
			$(this).parent().removeClass('has-error');
		}
	});

	$("[name='toggle-api']").bootstrapSwitch();
	/*.on('switchChange.bootstrapSwitch', function(event, state) {
	  if (state) {
	  	$('.show-all').show();
	  	$('.show-totals').hide();
	  } else {
	  	$('.show-all').hide();
	  	$('.show-totals').show();
	  }
	});*/

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

			<h2><strong>Mijn</strong> account</h2>

				<div class="tabs nomargin-top">

					<?# -- tabs -- ?>
					<ul class="nav nav-tabs">
						<li class="active">
							<a href="#company" data-toggle="tab">Gebruikersgegevens</a>
						</li>
						<li>
							<a href="#payment" data-toggle="tab">Abonementsgegevens</a>
						</li>
						<li>
							<a href="#contact" data-toggle="tab">Beveiliging</a>
						</li>
					</ul>

					<?# -- tabs content -- ?>
					<div class="tab-content">
						<div id="company" class="tab-pane active">

							{{ Form::open(array('url' => 'myaccount/updateuser')) }}

							<h4 class="company">Gebruikersgegevens</h4>
							<div class="row company">

								<div class="col-md-4">
									<div class="form-group">
										<label for="firstname">Voornaam</label>
										<input name="firstname" id="firstname" type="text" value="{{ Input::old('firstname') ? Input::old('firstname') : $user->firstname }}" class="form-control" />
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="lastname">Achternaam</label>
										<input name="lastname" id="lastname" type="text" value="{{ Input::old('lastname') ? Input::old('lastname') : $user->lastname }}" class="form-control"/>
									</div>
								</div>

							</div>
							<div class="row company">

								<div class="col-md-2">
									<div class="form-group">
										<label for="phone">Telefoonnummer</label>
										<input name="phone" id="phone" type="text" maxlength="12" value="{{ Input::old('phone') ? Input::old('phone') : $user->phone }}" class="form-control"/>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="mobile">Mobiel</label>
										<input name="mobile" id="mobile" type="text" maxlength="12" value="{{ Input::old('mobile') ? Input::old('mobile') : $user->mobile }}" class="form-control"/>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="email">Email</label>
										<input name="email" id="email" type="email" value="{{ Input::old('email') ? Input::old('email') : $user->email }}" class="form-control"/>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="website">Website</label>
										<input name="website" id="website" type="url" value="{{ Input::old('website') ? Input::old('website') : $user->website }}" class="form-control"/>
									</div>
								</div>

							</div>

							<h4>Adresgegevens</h4>
							<div class="row">

								<div class="col-md-4">
									<div class="form-group">
										<label for="address_street">Straat</label>
										<input name="address_street" id="address_street" type="text" value="{{ Input::old('street') ? Input::old('street') : $user->address_street }}" class="form-control"/>
									</div>
								</div>

								<div class="col-md-1">
									<div class="form-group">
										<label for="address_number">Huis nr.</label>
										<input name="address_number" id="address_number" type="text" value="{{ Input::old('address_number') ? Input::old('address_number') : $user->address_number }}" class="form-control"/>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="address_zipcode">Postcode</label>
										<input name="address_zipcode" id="address_zipcode" maxlength="6" type="text" value="{{ Input::old('zipcode') ? Input::old('zipcode') : $user->address_postal }}" class="form-control"/>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="address_city">Plaats</label>
										<input name="address_city" id="address_city" type="text" value="{{ Input::old('city') ? Input::old('city') : $user->address_city }}" class="form-control"/>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="province">Provincie</label>
										<select name="province" id="province" class="form-control pointer">
											@foreach (Province::all() as $province)
												<option {{ $user->province_id==$province->id ? 'selected' : '' }} value="{{ $province->id }}">{{ ucwords($province->province_name) }}</option>
											@endforeach
										</select>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="country">Land</label>
										<select name="country" id="country" class="form-control pointer">
											@foreach (Country::all() as $country)
												<option {{ $user->country_id==$country->id ? 'selected' : '' }} value="{{ $country->id }}">{{ ucwords($country->country_name) }}</option>
											@endforeach
										</select>
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
						<div id="payment" class="tab-pane">

							<div class="pull-right">
								<a href="/payment" class="btn btn-primary">Abonement verlengen</a>
							</div>

							<h4>Abonementsgegevens</h4>
							<div class="row">
								<div class="col-md-2"><strong>Abonement actief tot:</strong></div>
								<div class="col-md-2">{{ date('j F Y', strtotime($user->expiration_date)) }}</div>
							</div>
							<br />
							<h4>Geschiedenis</h4>
							<table class="table table-striped">
								<?# -- table head -- ?>
								<thead>
									<tr>
										<th class="col-md-2">Datum</th>
										<th class="col-md-4">Bedrag</th>
										<th class="col-md-6">Omschrijving</th>
									</tr>
								</thead>

								<tbody>
									@foreach (Order::where('user_id','=', Auth::user()->id)->orderBy('created_at', 'desc')->get() as $order)
									<tr>
										<td class="col-md-2"><strong>{{ date('d-m-Y H:i:s', strtotime(DB::table('order')->select('created_at')->where('id','=',$order->id)->get()[0]->created_at)) }}</strong></td>
										<td class="col-md-4">{{ '&euro; '.number_format($order->amount, 2,",",".") }}</td>
										<td class="col-md-6">{{ $order->description }}</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
						<div id="contact" class="tab-pane">

							{{ Form::open(array('url' => 'myaccount/security/update')) }}

							<h4 class="company">Wachtwoord veranderen</h4>
							<div class="row company">

								<div class="col-md-4">
									<div class="form-group">
										<label for="secret">Wachtwoord</label>
										<input name="secret" id="secret" type="password" class="form-control" />
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="secret_confirmation">Herhaal wachtwoord</label>
										<input name="secret_confirmation" id="secret_confirmation" type="password" class="form-control"/>
									</div>
								</div>

							</div>

							<div class="row">

								<div class="col-md-4">
									<div class="form-group">
										<label for="api">API key</label>
										<input name="api" id="api" type="text" readonly="readonly" value="{{ $user->api }}" class="form-control"/>
									</div>
								</div>

							</div>
							<div class="row">

								<div class="col-md-4">
									<div class="form-group">
										<label for="api">Referral key</label>
										<input name="api" id="api" type="text" readonly="readonly" value="{{ $user->referral_key }}" class="form-control"/>
									</div>
								</div>

							</div>

							<div class="row">

								<div class="col-md-4">
									<div class="form-group">
										<label for="toggle-api" style="display:block;">API toegang</label>
										<input name="toggle-api" type="checkbox" {{ $user->api_access ? 'checked' : '' }}>
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
					</div>
				</div>

		</div>

	</section>

</div>
<?#-- /WRAPPER --?>

@stop
