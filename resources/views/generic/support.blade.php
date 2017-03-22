<?php

$user = null;
if (Auth::check()) {
	$user = Auth::user();
}
?>

@extends('layout.master')

@section('title', 'Support')

@section('manifest','manifest="/main.appcache"')

@push('scripts')
<script src="/plugins/summernote/summernote.min.js"></script>
@endpush

@section('content')

<script type="text/javascript">
$(document).ready(function() {

	 $('.summernote').summernote({
	        height: $(this).attr("data-height") || 200,
	        toolbar: [
	            ["style", ["bold", "italic", "underline", "strikethrough", "clear"]],
	            ["para", ["ul", "ol", "paragraph"]],
	            ["table", ["table"]],
	            ["media", ["link", "picture"]],
	        ]
	    })
});
</script>

<div id="wrapper">

	<section id="contact" class="container">

		<h2>Stuur ons een <strong>bericht</strong>, stel een <strong>vraag</strong> of geef ons een <strong>belletje</strong></h2>

		@if (Session::has('success'))
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

		<div class="row">

			<div class="col-md-12">
				<div class="white-row">
				<h2>Helpdesk</h2>
				Onze supportmedewerkers zijn bereikbaar voor uw vragen op: <a href="tel:+31850655268">085 0655268</a><br />
				Ook zijn wij bereikbaar via WahtasApp op: <a href="tel:+31643587470">06 43587470</a> of <a href="tel:+31643587430">06 43587430</a><br />
				<i>7 dagen per week van 08:00-21:00 bereikbaar.</i>
				<br>
				</div>
			</div>

		</div>

		<div class="row">

			<div class="col-md-12">

				<form class="white-row" action="/support" method="post">
				{!! csrf_field() !!}

					<div class="row">
						<div class="col-md-12">
						<h2>Bericht</h2>
						</div>
						<div class="col-md-12">
						Stuur ons een bericht of stel een vraag.<br />
						<i>Wij antwoorden binnen 24 uur.</i>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="form-group">
							<div class="col-md-6">
								<label>Naam *</label>
								<input type="text" value="{{ $user ? $user->username : '' }}" data-msg-required="Please enter your name." maxlength="100" class="form-control" name="name" id="name">
							</div>
							<div class="col-md-6">
								<label>E-mailadres *</label>
								<input type="email" value="{{ $user ? $user->email : '' }}" data-msg-required="Please enter your email address." data-msg-email="Please enter a valid email address." maxlength="100" class="form-control" name="email" id="email">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group">
							<div class="col-md-4">
								<label for="category">Categorie (optioneel)</label>
								<select name="category" id="company_type" class="form-control pointer">
									<option>Hulp / Demo gewenst</option>
									<option>Account gerelateerd</option>
									<option>Betaling gerelateerd</option>
									<option selected="selected">Vraag / Suggestie</option>
									<option>Applicatieprobleem</option>
									<option>Wachtwoord vergeten</option>
									<option>Account opzeggen</option>
									<option>Overig</option>
								</select>
							</div>
							<div class="col-md-8">
								<label>Onderwerp (optioneel)</label>
								<input type="text" value="" data-msg-required="Please enter the subject." maxlength="100" class="form-control" name="subject" id="subject" placeholder="Ik heb een vraag over ...">
							</div>
						</div>
					</div>
					<div class="row">

						<div class="form-group">
							<div class="col-md-12">
								<label>Bericht (Omschrijf uw vraag zo duidelijk mogelijk)</label>
								<textarea name="message" id="message" rows="10" class="summernote form-control"></textarea>
							</div>
						</div>

					</div>

					<div class="row">
						<div class="col-md-12 text-right">
							<input type="submit" value="Verstuur" class="btn btn-primary btn-lg">
						</div>
					</div>

					<br>

				</form>

			</div>
		</div>

	</section>

</div>
@stop
