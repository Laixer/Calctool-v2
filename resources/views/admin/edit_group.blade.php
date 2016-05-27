@extends('layout.master')

@section('content')

<?php
$group = \Calctool\Models\UserGroup::find(Route::input('group_id'));
?>

<script type="text/javascript">
$(document).ready(function() {
	$("[name='toggle-active']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'});
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
			  <li><a href="/admin/group">Groep</a></li>
			  <li class="active">{{ $group->name }}</li>
			</ol>
			<div>
			<br />

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

			<h2><strong>Groep</strong> {{ $group->name }}</h2>

			<div class="white-row">

				<form method="POST" action="" accept-charset="UTF-8">
                {!! csrf_field() !!}

				<div class="row company">

					<div class="col-md-5">
						<div class="form-group">
							<label for="company_name">Groepnaam</label>
							<input name="name" id="name" type="text" value="{{ Input::old('name') ? Input::old('name') : $group->name}}" class="form-control" />
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="subscription_amount">Maandbedrag</label>
							<input name="subscription_amount" id="subscription_amount" type="number" min="1" step="any"value="{{ Input::old('subscription_amount') ? Input::old('subscription_amount') : $group->subscription_amount }}" class="form-control"/>
						</div>
					</div>

				</div>

				<h4>Overig</h4>
				<div class="row">

					<div class="col-md-2">
						<div class="form-group">
							<label for="toggle-active" style="display:block;">Actief</label>
							<input name="toggle-active" type="checkbox" {{ $group->active ? 'checked' : '' }}>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label for="address_city">Token</label>
							<input type="text" value="{{ $group->token }}" disabled class="form-control"/>
						</div>
					</div>

				</div>

				<h4>Opmerkingen <a data-toggle="tooltip" data-placement="bottom" data-original-title="Niet zichtbaar voor de gebruiker." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></h4>
				<div class="row">
					<div class="form-group">
						<div class="col-md-12">
							<textarea name="note" id="note" rows="10" class="summernote form-control">{{ Input::old('note') ? Input::old('note') : $group->note }}</textarea>
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
