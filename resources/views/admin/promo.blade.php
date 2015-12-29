<?php
use \Calctool\Models\User;
use \Calctool\Models\Promotion;

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
			  <li class="active">Promoties</li>
			</ol>
			<div>
			<br />

			@if(Session::get('success'))
			<div class="alert alert-success">
				<i class="fa fa-check-circle"></i>
				<strong>{{ Session::get('success') }}</strong>
			</div>
			@endif

			<h2><strong>Promoties</strong></h2>
			<div class="white-row">

			<h4>Actieve promoties</h4>
			<table class="table table-striped">
				<thead>
					<tr>
						<th class="col-md-1">Actie</th>
						<th class="col-md-3">Code</th>
						<th class="col-md-2">Nieuw Bedrag</th>
						<th class="col-md-2">Aangemaakt</th>
						<th class="col-md-2">Geldig tot</th>
						<th class="col-md-1"></th>
					</tr>
				</thead>

				<tbody>
				@foreach (Promotion::where('valid','>=',date('Y-m-d H:i:s'))->where('active',true)->orderBy('created_at', 'desc')->get() as $code)
					<tr>
						<td class="col-md-1">{{ $code->name }}</td>
						<td class="col-md-3">{{ strtoupper($code->code) }}</td>
						<td class="col-md-2">{{ '&euro;'.number_format($code->amount, 2,",",".") }}</td>
						<td class="col-md-2">{{ date('d-m-Y H:i:s', strtotime(DB::table('promotion')->select('created_at')->where('id','=',$code->id)->get()[0]->created_at)) }}</td>
						<td class="col-md-2">{{ date('d-m-Y H:i:s', strtotime(DB::table('promotion')->select('valid')->where('id','=',$code->id)->get()[0]->valid)) }}</td>
						<td class="col-md-1"><a href="/admin/promo/{{ $code->id }}/delete" class="btn btn-danger btn-xxs">Verwijder</a></td>
					</tr>
				@endforeach
				</tbody>
			</table>

			<h4>Nieuwe actie</h4>
			<form method="POST" action="" accept-charset="UTF-8">
            {!! csrf_field() !!}

				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label for="name">Naam</label>
							<input name="name" id="name" type="text" class="form-control">
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="code">Code</label>
							<input name="code" id="code" type="text" class="form-control">
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="amount">Bedrag</label>
							<input name="amount" id="amount" type="number" min="0" max="100" class="form-control">
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="valid">Verloopdatum</label>
							<input name="valid" id="valid" type="date" class="form-control">
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

