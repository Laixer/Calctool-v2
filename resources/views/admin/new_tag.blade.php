@extends('layout.master')

@section('title', 'Nieuwe groep')

@section('content')

<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			<div>
			<ol class="breadcrumb">
			  <li><a href="/">Home</a></li>
			  <li><a href="/admin">Admin CP</a></li>
			  <li><a href="/admin/user">Gebruikers</a></li>
			  <li><a href="/admin/user/tags">Gebruikerstags</a></li>
			  <li class="active">Nieuwe tag</li>
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

			<h2><strong>Nieuwe</strong> Gebruikerstag</h2>

			<div class="white-row">

				<form method="POST" action="" accept-charset="UTF-8">
                {!! csrf_field() !!}

				<div class="row company">

					<div class="col-md-12">
						<div class="form-group">
							<label for="company_name">Naam</label>
							<input name="name" id="name" type="text" value="{{ Input::old('name') ? Input::old('name') : ''}}" class="form-control" />
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
