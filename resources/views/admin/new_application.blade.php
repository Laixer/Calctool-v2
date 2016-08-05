@extends('layout.master')

@section('title', 'Nieuwe groep')

@push('style')
<link media="all" type="text/css" rel="stylesheet" href="/plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css">
@endpush

@push('scripts')
<script src="/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<script src="/plugins/summernote/summernote.min.js"></script>
@endpush

@section('content')
<script type="text/javascript">
$(document).ready(function() {
	$("[name='toggle-active']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'});
	$("[name='toggle-grant_authorization_code']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'});
	$("[name='toggle-grant_implicit']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'});
	$("[name='toggle-grant_password']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'});
	$("[name='toggle-grant_client_credential']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'});
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

			<h2><strong>Nieuwe</strong> Applicatie</h2>

			<div class="white-row">

				<form method="POST" action="" accept-charset="UTF-8">
                {!! csrf_field() !!}

				<div class="row">

					<div class="col-md-5">
						<div class="form-group">
							<label for="company_name">Client ID</label>
							<input name="appid" id="appid" type="text" value="{{ sha1(mt_rand()) }}" readonly="" class="form-control" />
						</div>
					</div>

					<div class="col-md-5">
						<div class="form-group">
							<label for="company_name">Client Secret</label>
							<input name="secret" id="secret" type="text" value="{{ sha1(mt_rand()) }}" readonly="" class="form-control" />
						</div>
					</div>

				</div>
				<div class="row">

					<div class="col-md-5">
						<div class="form-group">
							<label for="company_name">Naam</label>
							<input name="name" id="name" type="text" value="{{ Input::old('name') ? Input::old('name') : ''}}" class="form-control" />
						</div>
					</div>

				</div>
				<div class="row">

					<div class="col-md-5">
						<div class="form-group">
							<label for="company_name">Endpoint</label>
							<input name="endpoint" id="endpoint" type="text" value="{{ Input::old('endpoint') ? Input::old('endpoint') : ''}}" class="form-control" />
						</div>
					</div>

				</div>

				<h4>Authorization grants</h4>
				<div class="row">

					<div class="col-md-2">
						<div class="form-group">
							<label for="toggle-grant_authorization_code" style="display:block;">Authorization code</label>
							<input name="toggle-grant_authorization_code" type="checkbox">
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="toggle-grant_implicit" style="display:block;">Implicit grant</label>
							<input name="toggle-grant_implicit" type="checkbox">
						</div>
					</div>

				</div>

				<div class="row">

					<div class="col-md-2">
						<div class="form-group">
							<label for="toggle-grant_password" style="display:block;">Password credentials</label>
							<input name="toggle-grant_password" type="checkbox">
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="toggle-grant_client_credential" style="display:block;">Client credentials</label>
							<input name="toggle-grant_client_credential" type="checkbox">
						</div>
					</div>

				</div>

				<h4>Overig</h4>
				<div class="row">

					<div class="col-md-2">
						<div class="form-group">
							<label for="toggle-active" style="display:block;">Actief</label>
							<input name="toggle-active" type="checkbox" checked>
						</div>
					</div>

				</div>

				<h4>Omschrijving</h4>
				<div class="row">
					<div class="form-group">
						<div class="col-md-12">
							<textarea name="note" id="note" rows="10" class="summernote form-control">{{ Input::old('note') ? Input::old('note') : '' }}</textarea>
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
