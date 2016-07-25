@extends('layout.master')

@section('title', 'Nieuwe groep')

@push('style')
<link media="all" type="text/css" rel="stylesheet" href="/plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css">
@endpush

@push('scripts')
<script src="/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<script src="/plugins/summernote/summernote.min.js"></script>
@endpush

<?php

$clients = DB::table('oauth_clients')->join('oauth_client_endpoints', 'oauth_clients.id', '=', 'oauth_client_endpoints.client_id')->select('oauth_clients.*', 'oauth_client_endpoints.redirect_uri')->where('oauth_clients.id',Route::input('client_id'))->get();

$client = $clients[0];

?>

@section('content')
<script type="text/javascript">
$(document).ready(function() {
	$("[name='toggle-active']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'});
	$("[name='toggle-beta']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'});
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
			  <li><a href="/admin/application">applicaties</a></li>
			  <li class="active">Nieuwe applicaties</li>
			</ol>
			<div>
			<br />

			@if(Session::get('success'))
			<div class="alert alert-success">
				<i class="fa fa-check-circle"></i>
				<strong>Opgeslagen</strong>
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

			<h2><strong>Applicatie</strong> {{ $client->name }}</h2>

			<div class="white-row">

				<form method="POST" action="" accept-charset="UTF-8">
                {!! csrf_field() !!}

				<div class="row">

					<div class="col-md-5">
						<div class="form-group">
							<label for="company_name">Client ID</label>
							<input name="appid" id="appid" type="text" value="{{ $client->id }}" readonly="" class="form-control" />
						</div>
					</div>

					<div class="col-md-5">
						<div class="form-group">
							<label for="company_name">Client Secret</label>
							<input name="secret" id="secret" type="text" value="{{ $client->secret }}" readonly="" class="form-control" />
						</div>
					</div>

				</div>
				<div class="row">

					<div class="col-md-5">
						<div class="form-group">
							<label for="company_name">Naam</label>
							<input name="name" id="name" type="text" value="{{ Input::old('name') ? Input::old('name') : $client->name }}" class="form-control" />
						</div>
					</div>

				</div>
				<div class="row">

					<div class="col-md-5">
						<div class="form-group">
							<label for="company_name">Endpoint</label>
							<input name="endpoint" id="endpoint" type="text" value="{{ Input::old('endpoint') ? Input::old('endpoint') : $client->redirect_uri }}" class="form-control" />
						</div>
					</div>

				</div>

				<h4>Overig</h4>
				<div class="row">

					<div class="col-md-2">
						<div class="form-group">
							<label for="toggle-active" style="display:block;">Actief</label>
							<input name="toggle-active" type="checkbox" {{ $client->active ? 'checked' : '' }}>
						</div>
					</div>

				</div>

				<h4>Omschrijving</h4>
				<div class="row">
					<div class="form-group">
						<div class="col-md-12">
							<textarea name="note" id="note" rows="10" class="summernote form-control">{{ Input::old('note') ? Input::old('note') : $client->note }}</textarea>
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
