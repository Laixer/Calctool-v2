<?php
use \Calctool\Models\User;
use \Calctool\Models\MessageBox;

?>

@extends('layout.master')

@section('content')

<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			<div>
			<ol class="breadcrumb">
			  <li><a href="/">Home</a></li>
			  <li><a href="/admin">Admin CP</a></li>
			  <li class="active">Berichtenbox</li>
			</ol>
			<div>
			<br />

			<h2><strong>Nieuw bericht</strong></h2>
			<div class="white-row">

			<h4>Bericht aan gebruiker</h4>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="gender" style="display:block;">Gebruiker</label>
							<select name="user" id="user" class="form-control pointer">
								<option value="-1">Selecteer</option>
								@foreach(User::where('active',true)->orderBy('username')->get() as $user)
								<option value="{{ $user->id }}">{{ ucfirst($user->username) }}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="firstname">Onderwerp</label>
							<input name="firstname" id="firstname" type="text" class="form-control">
						</div>
					</div>

					<div class="form-group">
						<div class="col-md-12">
							<textarea name="notepad" id="notepad" rows="10" class="form-control"></textarea>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						<button class="btn btn-primary"><i class="fa fa-check"></i> Versturen</button>
					</div>
				</div>

			</div>
		</div>

	</section>

</div>
@stop

