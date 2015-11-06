@extends('layout.master')

@section('content')
<script type="text/javascript">
$(document).ready(function() {
	$(window).keydown(function(event){
		if(event.keyCode == 13 && !$('#tos').prop('checked')) {
			event.preventDefault();
			return false;
		}
	});
	$("[name='tos']").bootstrapSwitch().on('switchChange.bootstrapSwitch', function(event, state) {
		if (state) {
			$('#btn-submit').removeClass('disabled');
		} else {
			$('#btn-submit').addClass('disabled');
		}
	});
});
</script>
<div id="wrapper">

	<div id="shop">

		<section class="container">

			<div class="row">

				<div class="col-md-6">

					<h2>Maak <strong>Account</strong> aan</h2>

					<form action="/register" method="post" class="white-row">
					{!! csrf_field() !!}

						@if(Session::get('success'))
						<div class="alert alert-success">
							<i class="fa fa-check-circle"></i>
							<strong>{{ Session::get('success') }}</strong>
						</div>
						@endif

						<?# -- alert failed -- ?>
						@if($errors->any())
						<div class="alert alert-danger">
							<i class="fa fa-frown-o"></i>
							@foreach ($errors->all() as $error)
								{{ $error }}
							@endforeach
						</div>
						@endif

						<div class="row">
							<div class="form-group">
								<div class="col-md-12">
									<label for="username">Gebruikersnaam</label>
									<input class="form-control" name="username" type="text" id="username">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="form-group">
								<div class="col-md-12">
									<label for="email">E-mail adres</label>
									<input class="form-control" name="email" type="text" id="email">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="form-group">
								<div class="col-md-6">
									<label for="secret">Wachtwoord</label>
									<input class="form-control" name="secret" type="password" value="" id="secret">
								</div>
								<div class="col-md-6">
									<label for="secret_confirmation">Herhaal wachtwoord</label>
									<input class="form-control" name="secret_confirmation" type="password" value="" id="secret_confirmation">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<span class="form-group">
									<input name="tos" type="checkbox" value="1" id="tos">
									<label for="tos" style="margin-left:10px;">Ik ga akkoord met de <a target="blank" href="/terms-and-conditions">algemene voorwaarden</a></label>
								</span>
							</div>
							<div class="col-md-12">
								<input type="submit" id="btn-submit" value="Aanmelden" class="btn btn-primary pull-right push-bottom disabled" data-loading-text="Loading...">
							</div>
						</div>

					</form>

				</div>

				<div class="col-md-6">

					<h2>Waarom <strong> Registreren</strong>?</h2>

					<div class="white-row">

					<h4>Registreren is snel, makkelijk en gratis</h4>

						<p>Als je eenmaal geregistreerd bent, kun je:</p>
						<ul class="list-icon check">
							<li>Alle opties van het programma gebruiken.</li>
							<li>Calculaties van A-Z opzetten.</li>
							<li>In één handomdraai offertes en facturen genereren.</li>
							<li>Een totale administratie voeren voor elk gewenst project.</li>
						</ul>

						<hr class="half-margins">

						<p>
							Heb je al een account?
							<a href="/login">log dan hier in</a>
						</p>
					</div>

					</div>

			</div>

			<div class="white-row">
						<h4>Klantenservice</h4>
						<p>
							Als u op zoek bent naar hulp of gewoon een vraag wilt stellen, neem dan <a href="about">contact</a> met ons op.
						</p>
					</div>

		</section>

	</div>
</div>
@stop
