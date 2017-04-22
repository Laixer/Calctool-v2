<?php
use \BynqIO\CalculatieTool\Models\UserType;
use \BynqIO\CalculatieTool\Models\Province;
use \BynqIO\CalculatieTool\Models\Country;
?>

@extends('layout.master')

@section('title', 'Nieuwe gebruiker')

@push('style')
<link media="all" type="text/css" rel="stylesheet" href="/plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css">
@endpush

@push('scripts')
<script src="/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
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
	$("[name='toggle-active']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'});
});
</script>

<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			<div>
			<ol class="breadcrumb">
			  <li><a href="/">Home</a></li>
			  <li><a href="/admin">Admin CP</a></li>
			  <li><a href="/admin/user">Gebruikers</a></li>
			  <li class="active">Nieuwe gebruiker</li>
			</ol>
			<div>
			<br />

			@if (Session::has('success'))
			<div class="alert alert-success">
				<i class="fa fa-check-circle"></i>
				<strong>{{ Session::get('success') }}</strong>
			</div>
			@endif

			@if (count($errors) > 0)
			<div class="alert alert-danger">
				<i class="fa fa-frown-o"></i>
				<strong>Fout</strong>
				@foreach ($errors->all() as $error)
					{{ $error }}
				@endforeach
			</div>
			@endif

			<h2><strong>Nieuwe</strong> gebruiker</h2>
			<div class="white-row" >
				<form method="POST" action="/admin/user/new" accept-charset="UTF-8">
                {!! csrf_field() !!}

				<h4 class="company">Gebruikersgegevens</h4>
				<div class="row company">


					<div class="col-md-5">
						<div class="form-group">
							<label for="company_name">Gebruikersnaam*</label>
							<input name="username" id="username" type="text" value="{{ old('username') }}" class="form-control" />
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="user_type">Gebruikers type</label>
							<select name="type" id="type" class="form-control pointer">
								@foreach (UserType::all() as $type)
									<option {{ $type->user_type=='user' ? 'selected' : '' }} value="{{ $type->id }}">{{ ucwords($type->user_type) }}</option>
								@endforeach
							</select>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="secret">Wachtwoord</label>
							<input name="secret" type="password" id="secret" class="form-control" autocomplete="off">
						</div>
					</div>

				</div>

				<h4>Contactgegevens</h4>
				<div class="row">

					<div class="col-md-2">
						<div class="form-group">
							<label for="firstname">Voornaam</label>
							<input name="firstname" id="firstname" type="text" value="{{ old('firstname') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="lastname">Achternaam</label>
							<input name="lastname" id="lastname" type="text" value="{{ old('lastname') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="mobile">Mobiel</label>
							<input name="mobile" id="mobile" type="text" maxlength="12" value="{{ old('mobile') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="telephone">Telefoonnummer</label>
							<input name="telephone" id="telephone" type="text" maxlength="12" value="{{ old('telephone') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="email">Email*</label>
							<input name="email" id="email" type="email" value="{{ old('email') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label for="website">Website</label>
							<input name="website" id="website" type="url" value="{{ old('website') }}" class="form-control"/>
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

				<h4>Overig</h4>
				<div class="row">

					<div class="col-md-3">
						<div class="form-group">
							<label for="iban">Account verloopdatum</label>
							<input name="expdate" id="expdate" type="date" value="{{ old('expdate') ? old('expdate') : date('Y-m-d', strtotime('+1 month', time())) }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="iban">Activeringsdatum</label>
							<input name="confirmdate" id="confirmdate" type="date" value="{{ old('confirmdate') ? old('confirmdate') : date('Y-m-d') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="iban">Blokkeeringsdatum</label>
							<input name="bandate" id="bandate" type="date" value="{{ old('bandate') ? old('bandate') : '' }}" class="form-control"/>
						</div>
					</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="gender" style="display:block;">Gebruikersgroep</label>
								<select name="group" id="group" class="form-control pointer">
									@foreach (\BynqIO\CalculatieTool\Models\UserGroup::all() as $group)
										<option value="{{ $group->id }}">{{ ucwords($group->name) }}</option>
									@endforeach
								</select>
							</div>
						</div>
				</div>
				<div class="row">

					<div class="col-md-2">
						<div class="form-group">
							<label for="toggle-api" style="display:block;">API toegang</label>
							<input name="toggle-api" type="checkbox">
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="toggle-active" style="display:block;">Actief</label>
							<input name="toggle-active" type="checkbox" checked>
						</div>
					</div>

				</div>

				<h4>Opmerkingen</h4>
				<div class="row">
					<div class="form-group">
						<div class="col-md-12">
							<textarea name="note" id="note" rows="10" class="form-control">{{ old('note') }}</textarea>
						</div>
					</div>
				</div>
				<h4>Kladblok</h4>
				<div class="row">
					<div class="form-group">
						<div class="col-md-12">
							<textarea name="notepad" id="notepad" rows="10" class="form-control">{{ old('notepad') }}</textarea>
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

	</section>

</div>

@stop
