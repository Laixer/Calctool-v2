@extends('layout.master')

@section('content')

@section('title', 'Applicaties')

?>
<script type="text/javascript">
$(document).ready(function() {
	$('#btn-load-csv').change(function() {
		$('#upload-csv').submit();
	});
});
</script>
<div id="wrapper">

	<section class="container">
		<div class="col-md-12">

			<div>
			<ol class="breadcrumb">
			  <li><a href="/">Home</a></li>
			  <li><a href="/admin">Admin CP</a></li>
			  <li class="active">Applicaties</li>
			</ol>
			<div>
			<br />

			@if (Session::has('success'))
			<div class="alert alert-success">
				<i class="fa fa-check-circle"></i>
				<strong>{!! Session::get('success') !!}</strong>
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

			<div class="pull-right">
				<div class="pull-right">
		            <form id="upload-csv" action="product/upload" method="post" enctype="multipart/form-data">
		            {!! csrf_field() !!}
			            <label class="btn btn-primary btn-file">
						    CSV laden <input type="file" name="csvfile" id="btn-load-csv" style="display: none;">
						</label>
					</form>
				</div>
			</div>

			<h2><strong>Producten</strong></h2>

			<div class="white-row">

			@if(0)
			<table class="table table-striped">
				<thead>
					<tr>
						<th class="col-md-2">Naam</th>
						<th class="col-md-3">Client ID</th>
						<th class="col-md-5">Endpoint</th>
						<th class="col-md-1 hidden-sm hidden-xs">Sessies</th>
						<th class="col-md-1 hidden-sm hidden-xs">Actief</th>
					</tr>
				</thead>

				<tbody>
				<?php
				$clients = DB::table('oauth_clients')->join('oauth_client_endpoints', 'oauth_clients.id', '=', 'oauth_client_endpoints.client_id')->select('oauth_clients.*', 'oauth_client_endpoints.redirect_uri')->get();
				?>
				@foreach ($clients as $client)
					<tr>
						<td class="col-md-2"><a href="{{ '/admin/application/'.$client->id.'/edit' }}">{{ $client->name }}</a></td>
						<td class="col-md-3">{{ $client->id }}</td>
						<td class="col-md-5">{{ $client->redirect_uri }}</td>
						<td class="col-md-1 hidden-sm hidden-xs">{{ DB::table('oauth_sessions')->where('client_id',$client->id)->count() }}</td>
						<td class="col-md-1 hidden-sm hidden-xs">{{ $client->active ? 'Ja' : 'Nee' }}</td>
					</tr>
				@endforeach
				</tbody>
			</table>
			<div class="row">
				<div class="col-md-12">
					<a href="/admin/application/new" class="btn btn-primary"><i class="fa fa-pencil"></i> Nieuwe applicatie</a>
				</div>
			</div>
			</div>
			@endif

		</div>

	</section>

</div>
@stop
