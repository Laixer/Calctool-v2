<?php

use \Calctool\Models\Relation;
use \Calctool\Models\Contact;
use \Calctool\Models\ContactFunction;

$relation = Relation::find(Auth::user()->self_id);
if (!$relation) {
	header("Location: /mycompany");
	exit();	
}

$contact = Contact::where('relation_id','=',$relation->id)->first();
?>

@extends('layout.master')

@section('title', 'Nieuw contact')

@push('style')
@endpush

@push('scripts')
@endpush

@section('content')
<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

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

			<div>
			<ol class="breadcrumb">
			  <li><a href="/">Home</a></li>
			  <li><a href="/mycompany">Mijn bedrijf</a></li>
			 <li class="active" /relation-{{ $relation->id }}/contact/new">nieuw contact</li>
			</ol>
			</div>
			<br>

			<h2><strong>Nieuw</strong> contact</h2>
			<div class="white-row">
			<form action="/mycompany/contact/new" method="post">
			{!! csrf_field() !!}
			<div data-step="1" data-intro="Geef de contactgevens op. Alleen de velden met (*) zijn verplicht.">
			<h4>Contactgegevens</h4>
			<div class="row">

				<div class="col-md-3">
					<div class="form-group">
						<label for="contact_salutation">Aanhef</label>
						<input name="contact_salutation" maxlength="16" id="contact_salutation" type="text" value="{{ Input::old('contact_salutation') }}" class="form-control"/>
						<input type="hidden" name="id" id="id" value="{{ $relation->id }}"/>
					</div>
				</div>

				<div class="col-md-3">
					<div class="form-group">
						<label for="contact_name">Achternaam*</label>
						<input name="contact_name" maxlength="50" id="contact_name" type="text" value="{{ Input::old('contact_name') }}" class="form-control"/>
					</div>
				</div>

				<div class="col-md-2">
					<div class="form-group">
						<label for="contact_firstname">Voornaam*</label>
						<input name="contact_firstname" maxlength="30" id="contact_firstname" type="text" value="{{ Input::old('contact_firstname') }}" class="form-control"/>
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
						<label for="email">Email*</label>
						<input name="email" id="email" maxlength="80" type="email" value="{{ Input::old('email') }}" class="form-control"/>
					</div>
				</div>

				<div class="col-md-4 company">
					<div class="form-group">
						<label for="contactfunction">Functie</label>
						<select name="contactfunction" id="contactfunction" class="form-control pointer">
						@foreach (ContactFunction::all() as $function)
							<option {{ $function->function_name=='directeur' ? 'selected' : '' }} value="{{ $function->id }}">{{ ucwords($function->function_name) }}</option>
						@endforeach
						</select>
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
				<div class="col-md-12">
					<button class="btn btn-primary" data-step="2" data-intro="Klik op 'Opslaan' om de contactpersoon toe te voegen aan jouw berijf."><i class="fa fa-check"></i> Opslaan</button>
				</div>
			</div>

		</div>
		</form>
		</div>
		</div>
	
	</section>

</div>
<?#-- /WRAPPER --?>

@stop
