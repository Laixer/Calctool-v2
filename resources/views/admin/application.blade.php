@extends('layout.master')

@section('content')

@section('title', 'Applicaties')

?>
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

			<h2><strong>Applicaties</strong></h2>

			<div class="white-row">
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
		</div>

	</section>

</div>
@stop
