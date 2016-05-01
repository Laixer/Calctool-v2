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

@section('content')

<script type="text/javascript">
$(document).ready(function() {
	if (sessionStorage.introDemo) {
		introJs().
			setOption('nextLabel', 'Volgende').
			setOption('prevLabel', 'Vorige').
			setOption('skipLabel', 'Overslaan').
			setOption('doneLabel', 'Klaar').
			setOption('showBullets', false).
			onexit(function(){
				sessionStorage.removeItem('introDemo');
			}).onafterchange(function(){
				var done = this._currentStep;
				$('.introjs-skipbutton').click(function(){
					if (done == 1) {
						window.location.href = '/mycompany';
					}
				});
			}).start();
	}
});
</script>
<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			@if(Session::get('success'))
			<div class="alert alert-success">
				<i class="fa fa-check-circle"></i>
				<strong>Contact toegevoegd aan relatie</strong>
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

			<div>
			<ol class="breadcrumb">
			  <li><a href="/">Home</a></li>
			  <li><a href="/mycompany">Mijn bedrijf</a></li>
			 <li class="active" /relation-{{ $relation->id }}/contact/new">nieuw contact</li>
			</ol>
			</div>
		</div>
		<br>

		<h2><strong>Nieuw</strong> contact</h2>
		<div class="white-row">
		<form action="/mycompany/contact/new" method="post">
		{!! csrf_field() !!}
<div data-step="1" data-intro="Stap 6: Voeg een nieuw contact toe. Alleen de velden met (*) zijn verplicht.">
		<h4>Contactgegevens</h4>
		<div class="row">

			<div class="col-md-3">
				<div class="form-group">
					<label for="contact_name">Achternaam*</label>
					<input name="contact_name" id="contact_name" type="text" value="{{ Input::old('contact_name') }}" class="form-control"/>
					<input type="hidden" name="id" id="id" value="{{ $relation->id }}"/>
				</div>
			</div>

			<div class="col-md-2">
				<div class="form-group">
					<label for="contact_firstname">Voornaam</label>
					<input name="contact_firstname" id="contact_firstname" type="text" value="{{ Input::old('contact_firstname') }}" class="form-control"/>
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
					<input name="email" id="email" type="email" value="{{ Input::old('email') }}" class="form-control"/>
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
	
		</div>
</div>
		<div class="white-row">
			<div class="col-md-2">
				<button class="btn btn-primary" data-step="2" data-intro="Stap 7: Sla je contact van je bedrijf op."><i class="fa fa-check"></i> Opslaan</button>
			</div>
		</div>
		</form>
		</div>
	
	</section>

</div>
<?#-- /WRAPPER --?>

@stop
