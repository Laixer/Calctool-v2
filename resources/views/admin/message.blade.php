<?php
use \Calctool\Models\User;
use \Calctool\Models\MessageBox;

?>

@extends('layout.master')

@section('content')

<script type="text/javascript">
$(document).ready(function() {

	 $('.summernote').summernote({
	        height: $(this).attr("data-height") || 200,
	        toolbar: [
	            ["style", ["bold", "italic", "underline", "strikethrough", "clear"]],
	            ["para", ["ul", "ol", "paragraph"]],
	            ["table", ["table"]],
	            ["media", ["link", "picture", "video"]],
	            ["misc", ["codeview"]]
	        ]
	    })
});
</script>

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

			@if(Session::get('success'))
			<div class="alert alert-success">
				<i class="fa fa-check-circle"></i>
				<strong>{{ Session::get('success') }}</strong>
			</div>
			@endif

			<h2><strong>Nieuw bericht</strong></h2>
			<div class="white-row">

			<h4>Bericht aan gebruiker</h4>
			<form method="POST" action="" accept-charset="UTF-8">
            {!! csrf_field() !!}

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
							<label for="subject">Onderwerp</label>
							<input name="subject" id="subject" type="text" class="form-control">
						</div>
					</div>

					<div class="form-group">
						<div class="col-md-12">
							<textarea name="message" id="message" rows="10" class="summernote form-control"></textarea>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						<button class="btn btn-primary"><i class="fa fa-check"></i> Versturen</button>
					</div>
				</div>
			</form>

			</div>
		</div>

	</section>

</div>
@stop

