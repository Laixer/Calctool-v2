<?php
$user = Auth::user();
?>

@extends('layout.master')

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
	$('#tab-company').click(function(e){
		sessionStorage.toggleTabMyAcc{{Auth::id()}} = 'company';
	});
	$('#tab-payment').click(function(e){
		sessionStorage.toggleTabMyAcc{{Auth::id()}} = 'payment';
	});
	$('#tab-contact').click(function(e){
		sessionStorage.toggleTabMyAcc{{Auth::id()}} = 'contact';
	});
	if (sessionStorage.toggleTabMyAcc{{Auth::id()}}){
		$toggleOpenTab = sessionStorage.toggleTabMyAcc{{Auth::id()}};
		$('#tab-'+$toggleOpenTab).addClass('active');
		$('#'+$toggleOpenTab).addClass('active');
	} else {
		sessionStorage.toggleTabMyAcc{{Auth::id()}} = 'company';
		$('#tab-company').addClass('active');
		$('#company').addClass('active');
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

	$("[name='toggle-api']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'});
	$("[name='pref_use_ct_numbering']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'});
	$('#acc-deactive').click(function(e){
		e.preventDefault();
		if(confirm('Weet je zeker dat je je account wilt deactiveren?')){
			location.href = '/myaccount/deactivate'
		}
	});
	$('#promocode').blur(function(e){
		e.preventDefault();
		$field = $(this);
		if ($field.val()) {
			$.post("/payment/promocode", {
				code: $field.val()
			}, function(data) {
				if (data.success) {
					$field.addClass('success-input');
					$('#currprice').text(data.famount);
				} else {
					$field.addClass('error-input');
					$('#errmess').show();
				}
			});
		}
	});

});
</script>
<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModal" aria-hidden="true">
	<div class="modal-dialog modal-dialog">
		<div class="modal-content">

			<div class="modal-body">
				@if($errors->has())
				<div class="alert alert-danger">
					<i class="fa fa-frown-o"></i>
					<strong>Fout</strong>
					@foreach ($errors->all() as $error)
						{{ $error }}
					@endforeach
				</div>
				@endif

				<div class="bs-callout text-center styleBackground nomargin-top">
					<h2>Verleng met een maand voor &euro; <strong id="currprice">27</strong>,-</h2>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="promocode">Promotiecode</label>
							<input name="promocode" id="promocode" type="text" class="form-control">
						</div>
					</div>
					<div class="col-md-6">
						<span id="errmess" style="color:rgb(248, 97, 97);display:none;"><br />Deze promotiecode bestaat niet of is niet meer geldig.</span>
					</div>
				</div>
			</div>

			<div class="modal-footer">
				<div class="col-md-12">
					<a href="/payment" class="btn btn-primary"><i class="fa fa-check"></i> Betalen</a>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			<div>
				<ol class="breadcrumb">
				  <li><a href="/">Home</a></li>
				  <li class="active">Mijn account</li>
				</ol>
			<div>
			<br>

			@if (!Auth::user()->hasPayed())
			<div class="alert alert-danger">
				<i class="fa fa-danger"></i>
				Account is gedeactiveerd, abonnement is verlopen.
			</div>
			@endif

			@if(Session::get('success'))
			<div class="alert alert-success">
				<i class="fa fa-check-circle"></i>
				<strong>{{ Session::get('success') }}</strong>
			</div>
			@endif

			@if($errors->has())
			<div class="alert alert-danger">
				<i class="fa fa-frown-o"></i>
				<strong>Fouten in de invoer</strong>
				<ul>
					@foreach ($errors->all() as $error)
					<li><h5 class="nomargin">{{ $error }}</h5></li>
					@endforeach
				</ul>
			</div>
			@endif

			<h2><strong>Mijn</strong> account</h2>

				<div class="tabs nomargin-top">

					<ul class="nav nav-tabs">
						<li id="tab-company">
							<a href="#company" data-toggle="tab">Mijn gegevens</a>
						</li>
						<li id="tab-payment">
							<a href="#payment" data-toggle="tab">Mijn abonnement</a>
						</li>
						<li id="tab-contact">
							<a href="#contact" data-toggle="tab">Wachtwoord</a>
						</li>
					</ul>

					<div class="tab-content">
						<div id="company" class="tab-pane">

							<form method="POST" action="/myaccount/updateuser" accept-charset="UTF-8">
							{!! csrf_field() !!}

							<div data-intro="Stap 1: Voor een juiste werking van de CalculatieTool.com moeten er eerst een aantal gegevens van je bedrijf bekend zijn.">
							<h4 class="company">Contactgegevens</h4>
							<div class="row company">
								<div class="col-md-3">
									<div class="form-group">
										<label for="username">Gebruikersnaam</label>
										<input tname="username" id="username" ype="text" disabled="" value="{{ $user->username }}" class="form-control"/>
									</div>
								</div>
							</div>
							</div>
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
								<div class="col-md-3">
									<div class="form-group">
										<label for="gender" style="display:block;">Geslacht</label>
										<select name="gender" id="gender" class="form-control pointer">
											<option value="-1">Selecteer</option>
											<option {{ $user->gender=='M' ? 'selected' : '' }} value="M">Man</option>
											<option {{ $user->gender=='V' ? 'selected' : '' }} value="V">Vrouw</option>
										</select>
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
										<label for="email">Email*</label>
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

							<div class="row">
								<div class="col-md-12">
									<button class="btn btn-primary"><i class="fa fa-check"></i> Opslaan</button>
								</div>
							</div>
						</form>

						</div>
						<div id="payment" class="tab-pane">

							<div class="pull-right">
								<a href="javascript:void(0);" id="acc-deactive" class="btn btn-danger">Account deactiveren</a>
								<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#paymentModal">Abonnement verlengen</a>
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
								<thead>
									<tr>
										<th class="col-md-2">Datum</th>
										<th class="col-md-2">Bedrag</th>
										<th class="col-md-2">Status</th>
										<th class="col-md-4">Omschrijving</th>
										<th class="col-md-2">Betalingswijze</th>
									</tr>
								</thead>

								<tbody>
									@foreach (Calctool\Models\Payment::where('user_id','=', Auth::user()->id)->orderBy('created_at', 'desc')->get() as $order)
									<tr>
										<td class="col-md-2"><strong>{{ date('d-m-Y H:i:s', strtotime(DB::table('payment')->select('created_at')->where('id','=',$order->id)->get()[0]->created_at)) }}</strong></td>
										<td class="col-md-2">{{ '&euro; '.number_format($order->amount, 2,",",".") }}</td>
										<td class="col-md-2">{{ $order->status }}</td>
										<td class="col-md-4">{{ $order->description }}</td>
										<td class="col-md-2">{{ $order->method }}</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
						<div id="contact" class="tab-pane">

							<form method="POST" action="myaccount/security/update" accept-charset="UTF-8">
                            {!! csrf_field() !!}

							<h4 class="company">Wachtwoord wijzigen</h4>
							<div class="row company">

								<div class="col-md-4">
									<div class="form-group">
										<label for="curr_secret">Huidig wachtwoord</label>
										<input name="curr_secret" id="curr_secret" type="password" class="form-control" />
									</div>
								</div>

							</div>
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

							<h4>Codes</h4>

							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label for="api">API key</label>
										<input name="api" id="api" type="text" readonly="readonly" value="{{ $user->api }}" class="form-control"/>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label for="toggle-api" style="display:block;">API toegang</label>
										<input name="toggle-api" type="checkbox" {{ $user->api_access ? 'checked' : '' }}>
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
<?#-- /WRAPPER --?>

@stop
