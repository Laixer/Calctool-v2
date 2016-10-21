<?php
use \Calctool\Models\UserGroup;

$user = Auth::user();
?>

@extends('layout.master')

@section('title', 'Mijn account')

@push('style')
<link media="all" type="text/css" rel="stylesheet" href="/plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css">
@endpush

@push('scripts')
<script src="/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<script type="text/javascript" src="/js/iban.js"></script>
@endpush

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
	$('#tab-company').click(function(e){
		sessionStorage.toggleTabMyAcc{{Auth::id()}} = 'company';
	});
	$('#tab-payment').click(function(e){
		sessionStorage.toggleTabMyAcc{{Auth::id()}} = 'payment';
	});
	$('#tab-contact').click(function(e){
		sessionStorage.toggleTabMyAcc{{Auth::id()}} = 'contact';
	});
	$('#tab-apps').click(function(e){
		sessionStorage.toggleTabMyAcc{{Auth::id()}} = 'apps';
	});
	$('#tab-other').click(function(e){
		sessionStorage.toggleTabMyAcc{{Auth::id()}} = 'other';
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
		location.href = '/myaccount/deactivate?reason=' + $('#reason').val();
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
				@if (count($errors) > 0)
				<div class="alert alert-danger">
					<i class="fa fa-frown-o"></i>
					<strong>Fout</strong>
					@foreach ($errors->all() as $error)
						{{ $error }}
					@endforeach
				</div>
				@endif

				<div class="bs-callout text-center styleBackground nomargin-top">
					<h2>Verleng met een maand voor &euro; <strong id="currprice">{{ number_format(UserGroup::find($user->user_group)->subscription_amount, 2,",",".") }}</strong></h2>
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
<div class="modal fade" id="deactivateModal" tabindex="-1" role="dialog" aria-labelledby="deactivateModal" aria-hidden="true">
	<div class="modal-dialog modal-dialog">
		<div class="modal-content">

			<div class="modal-body">
				@if (count($errors) > 0)
				<div class="alert alert-danger">
					<i class="fa fa-frown-o"></i>
					<strong>Fout</strong>
					@foreach ($errors->all() as $error)
						{{ $error }}
					@endforeach
				</div>
				@endif

				<div class="bs-callout text-center styleBackground nomargin">
					<h2><strong><i class="fa fa-frown-o fsize40" aria-hidden="true"></i></strong> Definitief opzeggen?</h2>
				</div>
				<div class="row">
					<div class="col-md-12">
						<label>Reden voor opzegging:</label>
						<textarea name="reason" id="reason" rows="5" class="form-control"></textarea>
					</div>
				</div>
			</div>

			<div class="modal-footer">
				<div class="col-md-6 text-left" style="padding: 0;">
					<button class="btn btn-primary" data-dismiss="modal">Annuleren</button>
				</div>
				<div class="col-md-6" style="padding: 0;">
					<a href="/payment" class="btn btn-danger" id="acc-deactive">Definitief deactiveren</a>
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

			@if (count($errors) > 0)
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

			<?php
				$clients = DB::table('oauth_sessions')
							->join('oauth_clients', 'oauth_sessions.client_id', '=', 'oauth_clients.id')
							->leftJoin('oauth_access_tokens', 'oauth_sessions.id', '=', 'oauth_access_tokens.session_id')
							->select('oauth_sessions.*', 'oauth_clients.name', 'oauth_clients.active', 'oauth_access_tokens.created_at as last_used')
							->where('owner_id',Auth::id())->get();
			?>

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
						@if (count($clients))
						<li id="tab-apps">
							<a href="#apps" data-toggle="tab">Applicaties</a>
						</li>
						@endif
						<li id="tab-other">
							<a href="#other" data-toggle="tab">Overig</a>
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
										<input name="username" id="username" type="text" disabled="" value="{{ $user->username }}" class="form-control"/>
									</div>
								</div>
							</div>
							</div>
							<div class="row company">
								<div class="col-md-4">
									<div class="form-group">
										<label for="firstname">Voornaam</label>
										<input {{ session()->has('swap_session') ? 'disabled' : '' }} name="firstname" id="firstname" type="text" value="{{ Input::old('firstname') ? Input::old('firstname') : $user->firstname }}" class="form-control" />
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="lastname">Achternaam</label>
										<input {{ session()->has('swap_session') ? 'disabled' : '' }} name="lastname" id="lastname" type="text" value="{{ Input::old('lastname') ? Input::old('lastname') : $user->lastname }}" class="form-control"/>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="gender" style="display:block;">Geslacht</label>
										<select {{ session()->has('swap_session') ? 'disabled' : '' }} name="gender" id="gender" class="form-control pointer">
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
										<input {{ session()->has('swap_session') ? 'disabled' : '' }} name="phone" id="phone" type="text" maxlength="12" value="{{ Input::old('phone') ? Input::old('phone') : $user->phone }}" class="form-control"/>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="mobile">Mobiel</label>
										<input {{ session()->has('swap_session') ? 'disabled' : '' }} name="mobile" id="mobile" type="text" maxlength="12" value="{{ Input::old('mobile') ? Input::old('mobile') : $user->mobile }}" class="form-control"/>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="email">Email*</label>
										<input {{ session()->has('swap_session') ? 'disabled' : '' }} name="email" id="email" type="email" value="{{ Input::old('email') ? Input::old('email') : $user->email }}" class="form-control"/>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="website">Website</label>
										<input {{ session()->has('swap_session') ? 'disabled' : '' }} name="website" id="website" type="url" value="{{ Input::old('website') ? Input::old('website') : $user->website }}" class="form-control"/>
									</div>
								</div>

							</div>

							<div class="row">
								<div class="col-md-12">
									<button {{ session()->has('swap_session') ? 'disabled' : '' }} class="btn btn-primary {{ session()->has('swap_session') ? 'disabled' : '' }}"><i class="fa fa-check"></i> Opslaan</button>
								</div>
							</div>
						</form>

						</div>
						<div id="payment" class="tab-pane">

							@if(!session()->has('swap_session'))
							<div class="pull-right">
								<a href="#" data-toggle="modal" data-target="#deactivateModal" class="btn btn-danger">Account deactiveren</a>
								@if (UserGroup::find(Auth::user()->user_group)->subscription_amount == 0)
								<a href="/payment/increasefree" class="btn btn-primary">Abonnement verlengen</a>
								@else
								<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#paymentModal">Abonnement verlengen</a>
								@endif
							</div>
							@endif

							<h4>Abonnementsduur</h4>
							<div class="row">
								<div class="col-md-3"><strong>Abonnement actief tot:</strong></div>
								<div class="col-md-2">{{ $user->dueDateHuman() }}</div>
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
										<input {{ session()->has('swap_session') ? 'disabled' : '' }} name="curr_secret" id="curr_secret" type="password" class="form-control" autocomplete="off"/>
									</div>
								</div>

							</div>
							<div class="row company">

								<div class="col-md-4">
									<div class="form-group">
										<label for="secret">Wachtwoord</label>
										<input {{ session()->has('swap_session') ? 'disabled' : '' }} name="secret" id="secret" type="password" class="form-control" autocomplete="off"/>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="secret_confirmation">Herhaal wachtwoord</label>
										<input {{ session()->has('swap_session') ? 'disabled' : '' }} name="secret_confirmation" id="secret_confirmation" type="password" class="form-control" autocomplete="off"/>
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
									<button {{ session()->has('swap_session') ? 'disabled' : '' }} class="btn btn-primary {{ session()->has('swap_session') ? 'disabled' : '' }}" name="save-password"><i class="fa fa-check"></i> Opslaan</button>
								</div>
							</div>
						</form>

						</div>
						
						@if (count($clients))
						<div id="apps" class="tab-pane">

							<h4>Externe applicaties</h4>
							<table class="table table-striped">
								<thead>
									<tr>
										<th class="col-md-2">Applicatie</th>
										<th class="col-md-2">Datum akkoord</th>
										<th class="col-md-4">Laatst vernieuwd</th>
										<th class="col-md-2">Actief</th>
										<th class="col-md-2"></th>
									</tr>
								</thead>

								<tbody>

								<?php
									?>
									@foreach ($clients as $client)
									<tr>
										<td class="col-md-2"><strong>{{ $client->name }}</strong></td>
										<td class="col-md-2">{{ date('d-m-Y H:i:s', strtotime($client->created_at)) }}</td>
										<td class="col-md-4">{{ $client->last_used ?date('d-m-Y H:i:s', strtotime($client->last_used)) : '-' }}</td>
										<td class="col-md-2">{{ $client->active ? 'Ja' : 'Nee' }}</td>
										<td class="col-md-2" style="text-align:right"><a href="/myaccount/oauth/session/{{ $client->id }}/revoke" class="btn btn-danger btn-xs">Intrekken</a></td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
						@endif

						<div id="other" class="tab-pane">

							<!--<form method="POST" action="myaccount/security/update" accept-charset="UTF-8">-->
                            {{-- !! csrf_field() !! --}}

							<h4 class="company">Demoproject</h4>
							<div class="row">
								<div class="col-md-12">
									<a href="/myaccount/loaddemo" class="btn btn-primary"><i class="fa fa-check"></i> Laad demoproject</a>
								</div>
							</div>
						<!--</form>-->

						</div>
					</div>
				</div>

		</div>

	</section>

</div>
<?#-- /WRAPPER --?>

@stop
