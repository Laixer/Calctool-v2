<?php
$relation = Relation::find(Route::Input('relation_id'));
$contact = Contact::where('relation_id','=',$relation->id)->first();
?>

@extends('layout.master')

@section('content')
<?# -- WRAPPER -- ?>

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
				<strong>Fout</strong>
				@foreach ($errors->all() as $error)
					{{ $error }}
				@endforeach
			</div>
			@endif

			<div>
			<ol class="breadcrumb">
			  <li><a href="/">Home</a></li>
			  <li><a href="/relation">Relaties</a></li>
			  <li><a href="/relation-{{ $relation->id }}/edit">{{ $relation->company_name ? $relation->company_name : $contact->firstname . ' ' . $contact->lastname }}</a></li>
			 <li class="active" /relation-{{ $relation->id }}/contact/new">nieuw contact</li>
			</ol>
			<div>
			<br>

			<h2><strong>Nieuw</strong> contact</h2>

				{{ Form::open(array('url' => 'relation/contact/new')) }}
				<h4>Contactgegevens</h4>
				<div class="row">

					<div class="col-md-2">
						<div class="form-group">
							<label for="contact_firstname">Voornaam*</label>
							<input name="contact_firstname" id="contact_firstname" type="text" value="{{ Input::old('contact_firstname') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="contact_name">Achternaam*</label>
							<input name="contact_name" id="contact_name" type="text" value="{{ Input::old('contact_name') }}" class="form-control"/>
							<input type="hidden" name="id" id="id" value="{{ $relation->id }}"/>
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
							<label for="contactfunction">Functie*</label>
							<select name="contactfunction" id="contactfunction" class="form-control pointer">
							@foreach (ContactFunction::all() as $function)
								<option {{ $function->function_name=='directeur' ? 'selected' : '' }} value="{{ $function->id }}">{{ ucwords($function->function_name) }}</option>
							@endforeach
							</select>
						</div>
					</div>
					<div class="col-md-12">
						<button class="btn btn-primary"><i class="fa fa-check"></i> Opslaan</button>
					</div>

				</div>

			{{ Form::close() }}

		</div>

	</section>

</div>
<?#-- /WRAPPER --?>

@stop
