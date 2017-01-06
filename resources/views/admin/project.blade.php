<?php
use \Calctool\Models\Project;
use \Calctool\Models\User;
use \Calctool\Models\Relation;
use \Calctool\Models\RelationKind;
use \Calctool\Models\Contact;
?>

@extends('layout.master')

@section('title', 'Projectbeheer')

@section('content')
<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			<div>
			<ol class="breadcrumb">
			  <li><a href="/">Home</a></li>
			  <li><a href="/admin">Admin CP</a></li>
			  <li class="active">Projectbeheer</li>
			</ol>
			<div>
			<br />

			<h2><strong>Projectbeheer</strong></h2>

			<div class="white-row">
			<table class="table table-striped">
				<thead>
					<tr>
						<th class="col-md-1">ID</th>
						<th class="col-md-3">Projectnaam</th>
						<th class="col-md-2">Opdrachtgever</th>
						<th class="col-md-2">Gebruiker</th>
						<th class="col-md-1">Status</th>
						<th class="col-md-2">Aangemaakt</th>
					</tr>
				</thead>

				<tbody>
				@foreach (Project::orderBy('created_at', 'desc')->limit(50)->get() as $project)
				<?php $relation = Relation::find($project->client_id); ?>
					<tr>
						<td class="col-md-1">{{ $project->id }}</td>
						<td class="col-md-3">{{ $project->project_name }}</td>
						<td class="col-md-2">{{ RelationKind::find($relation->kind_id)->kind_name == 'zakelijk' ? ucwords($relation->company_name) : (Contact::where('relation_id','=',$relation->id)->first()['firstname'].' '.Contact::where('relation_id','=',$relation->id)->first()['lastname']) }}</td>
						<td class="col-md-2">{{ ucfirst(User::find($project->user_id)->username) }}</td>
						<td class="col-md-1">{{ $project->project_close ? 'Gesloten' : 'Open' }}</td>
						<td class="col-md-2">{{ date('d-m-Y H:i:s', strtotime(DB::table('project')->select('created_at')->where('id','=',$project->id)->get()[0]->created_at)) }}</td>
					</tr>
				@endforeach
				</tbody>
			</table>
			</div>
		</div>

	</section>

</div>
@stop
