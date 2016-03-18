<?php

use \Calctool\Models\RelationKind;
use \Calctool\Models\ContactFunction;
use \Calctool\Models\Contact;
use \Calctool\Models\Relation;


$common_access_error = false;
$contact = Contact::find(Route::Input('contact_id'));
if (!$contact) {
	$common_access_error = true;
} else {
	$relation = Relation::find($contact->relation_id);
	if (!$relation || !$relation->isOwner()) {
		$common_access_error = true;
	}
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
<script type="text/javascript">
	$(document).ready(function() {
		$('#rmuser').click(function(e){
			e.preventDefault();
			$.post('/relation/contact/delete', {id: {{ $contact->id }}}).fail(function(e) { console.log(e); });
		});
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

			@if (Auth::user()->myCompany() && Auth::user()->myCompany()->id == $relation->id)
			<div>
			<ol class="breadcrumb">
			  <li><a href="/">Dashboard</a></li>
			  <li><a href="/mycompany">Mijn bedrijf</a></li>
			 <li class="active">contact bewerken</li>
			</ol>
			<div>
			<br>
			@else
			<div>
			<ol class="breadcrumb">
			  <li><a href="/">Dashboard</a></li>
			  <li><a href="/relation">Relaties</a></li>
			  <li><a href="/relation-{{ $relation->id }}/edit">{{ $relation->company_name ? $relation->company_name : $contact->firstname . ' ' . $contact->lastname }}</a></li>
			  <li class="active">contact bewerken</li>
			</ol>
			<div>
			<br>
			@endif

			<div class="pull-right">
				<a href="/relation-{{ $relation->id }}/contact-{{ $contact->id }}/vcard" class="btn btn-primary">Download vCard</a>
			</div>

			<h2><strong>Contact</strong> {{ $contact->lastname }}</h2>

			<div class="white-row">
				<form method="POST" action="/relation/contact/update" accept-charset="UTF-8">
                                {!! csrf_field() !!}
				<h4>Contactgegevens</h4>
				<div class="row">

					<div class="col-md-2">
						<div class="form-group">
							<label for="contact_firstname">Voornaam</label>
							<input name="contact_firstname" id="contact_firstname" type="text" value="{{ Input::old('contact_firstname') ? Input::old('contact_firstname') : $contact->firstname }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="contact_name">Achternaam*</label>
							<input name="contact_name" id="contact_name" type="text" value="{{ Input::old('contact_name') ? Input::old('contact_name') : $contact->lastname }}" class="form-control"/>
							<input type="hidden" name="id" id="id" value="{{ $contact->id }}"/>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="mobile">Mobiel</label>
							<input name="mobile" id="mobile" type="text" maxlength="12" value="{{ Input::old('mobile') ? Input::old('mobile') : $contact->mobile }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="telephone">Telefoonnummer</label>
							<input name="telephone" id="telephone" type="text" maxlength="12" value="{{ Input::old('telephone') ? Input::old('telephone') : $contact->phone }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="email">Email*</label>
							<input name="email" id="email" type="email" value="{{ Input::old('email') ? Input::old('email') : $contact->email }}" class="form-control"/>
						</div>
					</div>

					@if (RelationKind::find($relation->kind_id)->kind_name=='zakelijk')
					<div class="col-md-4 company">
						<div class="form-group">
							<label for="contactfunction">Functie*</label>
							<select name="contactfunction" id="contactfunction" class="form-control pointer">
							@foreach (ContactFunction::all() as $function)
								<option {{ $contact->function_id==$function->id ? 'selected' : '' }} value="{{ $function->id }}">{{ ucwords($function->function_name) }}</option>
							@endforeach
							</select>
						</div>
					</div>
					@endif
					<div class="col-md-3">
						<div class="form-group">
							<label for="gender" style="display:block;">Geslacht</label>
							<select name="gender" id="gender" class="form-control pointer">
								<option value="-1">Selecteer</option>
								<option {{ $contact->gender=='M' ? 'selected' : '' }} value="M">Man</option>
								<option {{ $contact->gender=='V' ? 'selected' : '' }} value="V">Vrouw</option>
							</select>
						</div>
					</div>
					<div class="col-md-12">
						<button class="btn btn-primary"><i class="fa fa-check"></i> Opslaan</button>
					</div>

				</div>

			</form>
			</div>

		</div>

	</section>

</div>
@stop

<?php } ?>
