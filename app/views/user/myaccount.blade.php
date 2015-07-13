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
	$("[name='pref_mailings_optin']").bootstrapSwitch();
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
							<a href="#company" data-toggle="tab">Mijn gegevens</a>
						</li>
						<li>
							<a href="#payment" data-toggle="tab">Mijn abonnement</a>
						</li>
						<li>
							<a href="#contact" data-toggle="tab">Wacthwoord</a>
						</li>
						<li>
							<a href="#prefs" data-toggle="tab">Voorkeuren</a>
						</li>
					</ul>

					<?# -- tabs content -- ?>
					<div class="tab-content">
						<div id="company" class="tab-pane active">

							{{ Form::open(array('url' => 'myaccount/updateuser')) }}

							<h4 class="company">Contactgegevens</h4>
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
												<option {{ $user->country_id==$country->id ? 'selected' : ($country->country_name=='nederland' ? 'selected' : '') }} value="{{ $country->id }}">{{ ucwords($country->country_name) }}</option>
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
								<a href="/payment" class="btn btn-primary">Abonnement verlengen</a>
							</div>

							<h4>Abonnementsduur</h4>
							<div class="row">
								<div class="col-md-3"><strong>Abonnement actief tot:</strong></div>
								<div class="col-md-2">{{ date('j F Y', strtotime($user->expiration_date)) }}</div>
								<div class="col-md-7">&nbsp;</div>
							</div>
							<br />
							<h4>Betalingsgeschiedenis</h4>
							<table class="table table-striped">
								<?# -- table head -- ?>
								<thead>
									<tr>
										<th class="col-md-2">Datum</th>
										<th class="col-md-2">Bedrag</th>
										<th class="col-md-2">Status</th>
										<th class="col-md-6">Omschrijving</th>
									</tr>
								</thead>

								<tbody>
									@foreach (Order::where('user_id','=', Auth::user()->id)->orderBy('created_at', 'desc')->get() as $order)
									<tr>
										<td class="col-md-2"><strong>{{ date('d-m-Y H:i:s', strtotime(DB::table('order')->select('created_at')->where('id','=',$order->id)->get()[0]->created_at)) }}</strong></td>
										<td class="col-md-2">{{ '&euro; '.number_format($order->amount, 2,",",".") }}</td>
										<td class="col-md-2">{{ $order->status }}</td>
										<td class="col-md-6">{{ $order->description }}</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
						<div id="contact" class="tab-pane">

							{{ Form::open(array('url' => 'myaccount/security/update')) }}

							<h4 class="company">Wachtwoord wijzigen</h4>
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
						<div id="prefs" class="tab-pane">

							{{ Form::open(array('url' => 'myaccount/preferences/update')) }}

							<h4 class="company">Voorkeuren</h4>
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label for="pref_mailings_optin" style="display:block;">pref_mailings_optin</label>
										<input name="pref_mailings_optin" type="checkbox" {{ $user->pref_mailings_optin ? 'checked' : '' }}>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label for="pref_hourrate_calc">pref_hourrate_calc</label>
										<input name="pref_hourrate_calc" id="pref_hourrate_calc" type="text" class="form-control" value="{{ $user->pref_hourrate_calc }}" />
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label for="pref_hourrate_more">pref_hourrate_more</label>
										<input name="pref_hourrate_more" id="pref_hourrate_more" type="text" class="form-control" value="{{ $user->pref_hourrate_more }}" />
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label for="pref_profit_calc_contr_mat">pref_profit_calc_contr_mat</label>
										<input name="pref_profit_calc_contr_mat" id="pref_profit_calc_contr_mat" type="text" class="form-control" value="{{ $user->pref_profit_calc_contr_mat }}" />
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label for="pref_profit_calc_contr_equip">pref_profit_calc_contr_equip</label>
										<input name="pref_profit_calc_contr_equip" id="pref_profit_calc_contr_equip" type="text" class="form-control" value="{{ $user->pref_profit_calc_contr_equip }}" />
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label for="pref_profit_calc_subcontr_mat">pref_profit_calc_subcontr_mat</label>
										<input name="pref_profit_calc_subcontr_mat" id="pref_profit_calc_subcontr_mat" type="text" class="form-control" value="{{ $user->pref_profit_calc_subcontr_mat }}" />
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label for="pref_profit_calc_subcontr_equip">pref_profit_calc_subcontr_equip</label>
										<input name="pref_profit_calc_subcontr_equip" id="pref_profit_calc_subcontr_equip" type="text" class="form-control" value="{{ $user->pref_profit_calc_subcontr_equip }}" />
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label for="pref_profit_more_contr_mat">pref_profit_more_contr_mat</label>
										<input name="pref_profit_more_contr_mat" id="pref_profit_more_contr_mat" type="text" class="form-control" value="{{ $user->pref_profit_more_contr_mat }}" />
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label for="pref_profit_more_contr_equip">pref_profit_more_contr_equip</label>
										<input name="pref_profit_more_contr_equip" id="pref_profit_more_contr_equip" type="text" class="form-control" value="{{ $user->pref_profit_more_contr_equip }}" />
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label for="pref_profit_more_subcontr_mat">pref_profit_more_subcontr_mat</label>
										<input name="pref_profit_more_subcontr_mat" id="pref_profit_more_subcontr_mat" type="text" class="form-control" value="{{ $user->pref_profit_more_subcontr_mat }}" />
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label for="pref_profit_more_subcontr_equip">pref_profit_more_subcontr_equip</label>
										<input name="pref_profit_more_subcontr_equip" id="pref_profit_more_subcontr_equip" type="text" class="form-control" value="{{ $user->pref_profit_more_subcontr_equip }}" />
									</div>
								</div>
							</div>

							<h5>pref_email_offer</h5>
							<div class="row">
								<div class="form-group">
									<div class="col-md-12">
										<textarea name="pref_email_offer" id="pref_email_offer" rows="5" class="form-control">{{ $user->pref_email_offer }}</textarea>
									</div>
								</div>
							</div>

							<h5>pref_offer_description</h5>
							<div class="row">
								<div class="form-group">
									<div class="col-md-12">
										<textarea name="pref_offer_description" id="pref_offer_description" rows="5" class="form-control">{{ $user->pref_offer_description }}</textarea>
									</div>
								</div>
							</div>

							<h5>pref_closure_offer</h5>
							<div class="row">
								<div class="form-group">
									<div class="col-md-12">
										<textarea name="pref_closure_offer" id="pref_closure_offer" rows="5" class="form-control">{{ $user->pref_closure_offer }}</textarea>
									</div>
								</div>
							</div>

							<h5>pref_email_invoice</h5>
							<div class="row">
								<div class="form-group">
									<div class="col-md-12">
										<textarea name="pref_email_invoice" id="pref_email_invoice" rows="5" class="form-control">{{ $user->pref_email_invoice }}</textarea>
									</div>
								</div>
							</div>

							<h5>pref_invoice_description</h5>
							<div class="row">
								<div class="form-group">
									<div class="col-md-12">
										<textarea name="pref_invoice_description" id="pref_invoice_description" rows="5" class="form-control">{{ $user->pref_invoice_description }}</textarea>
									</div>
								</div>
							</div>

							<h5>pref_invoice_closure</h5>
							<div class="row">
								<div class="form-group">
									<div class="col-md-12">
										<textarea name="pref_invoice_closure" id="pref_invoice_closure" rows="5" class="form-control">{{ $user->pref_invoice_closure }}</textarea>
									</div>
								</div>
							</div>

							<h5>pref_email_invoice_first_reminder</h5>
							<div class="row">
								<div class="form-group">
									<div class="col-md-12">
										<textarea name="pref_email_invoice_first_reminder" id="pref_email_invoice_first_reminder" rows="5" class="form-control">{{ $user->pref_email_invoice_first_reminder }}</textarea>
									</div>
								</div>
							</div>

							<h5>pref_email_invoice_last_reminder</h5>
							<div class="row">
								<div class="form-group">
									<div class="col-md-12">
										<textarea name="pref_email_invoice_last_reminder" id="pref_email_invoice_last_reminder" rows="5" class="form-control">{{ $user->pref_email_invoice_last_reminder }}</textarea>
									</div>
								</div>
							</div>

							<h5>pref_email_invoice_first_demand</h5>
							<div class="row">
								<div class="form-group">
									<div class="col-md-12">
										<textarea name="pref_email_invoice_first_demand" id="pref_email_invoice_first_demand" rows="5" class="form-control">{{ $user->pref_email_invoice_first_demand }}</textarea>
									</div>
								</div>
							</div>

							<h5>pref_email_invoice_last_demand</h5>
							<div class="row">
								<div class="form-group">
									<div class="col-md-12">
										<textarea name="pref_email_invoice_last_demand" id="pref_email_invoice_last_demand" rows="5" class="form-control">{{ $user->pref_email_invoice_last_demand }}</textarea>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label for="offernumber_prefix">offernumber_prefix</label>
										<input name="offernumber_prefix" id="offernumber_prefix" type="text" class="form-control" value="{{ $user->offernumber_prefix }}" />
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label for="offer_counter">offer_counter</label>
										<input name="offer_counter" id="offer_counter" type="text" class="form-control" value="{{ $user->offer_counter }}" />
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label for="invoicenumber_prefix">invoicenumber_prefix</label>
										<input name="invoicenumber_prefix" id="invoicenumber_prefix" type="text" class="form-control" value="{{ $user->invoicenumber_prefix }}" />
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label for="invoice_counter">invoice_counter</label>
										<input name="invoice_counter" id="invoice_counter" type="text" class="form-control" value="{{ $user->invoice_counter }}" />
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label for="administration_cost">administration_cost</label>
										<input name="administration_cost" id="administration_cost" type="text" class="form-control" value="{{ $user->administration_cost }}" />
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
