<?php

$user = null;
if (Auth::check()) {
	$user = Auth::user();
}
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

	<section id="contact" class="container">

		<div class="row">

			<div class="col-md-12">

				@if(Session::get('success'))
				<div class="alert alert-success">
					<i class="fa fa-check-circle"></i>
					<strong>{{ Session::get('success') }}</strong>
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


				<h2>Stuur ons een <strong>bericht</strong> of stel een <strong>vraag</strong></h2>

				<form class="white-row" action="/support" method="post">
				{!! csrf_field() !!}

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
							<div class="col-md-12">
								<label>Onderwerp</label>
								<input type="text" value="" data-msg-required="Please enter the subject." maxlength="100" class="form-control" name="subject" id="subject">
							</div>
						</div>
					</div>
					<div class="row">

						<div class="form-group">
							<div class="col-md-12">
								<textarea name="message" id="message" rows="10" class="summernote form-control"></textarea>
							</div>
						</div>

					</div>

					<br />

					<div class="row">
						<div class="col-md-12">
							<input type="submit" value="Verstuur" class="btn btn-primary btn-lg">
						</div>
					</div>
				</form>

			</div>


			<div class="col-md-4">

				<!-- <h2>Details</h2> -->

				<!--<p>
					<span class="block"><strong><i class="fa fa-envelope"></i> Email:</strong> <a href="mailto:info@calculatietool.com">info@calculatietool.com</a></span>
					<span class="block"><strong><i class="fa fa-wrench"></i> Versie:</strong> 0.6-{{-- substr(File::get('../.revision'), 0, 7) --}}</span>
					<span class="block"><strong><i class="fa fa-code"></i> Upstream:</strong> {{-- date('Y-m-d\TH:i:s') --}}</span>
					<span class="block"><strong><i class="fa fa-server"></i> Server:</strong> {{-- gethostname() --}}</span>
				</p>-->

			</div>
			<?# -- /INFO -- ?>

		</div>

	</section>

</div>
<?# -- /WRAPPER -- ?>
@stop
