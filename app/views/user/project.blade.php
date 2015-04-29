@extends('layout.master')

@section('content')
<?# -- WRAPPER -- ?>
<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			<h2><strong>Projecten</strong></h2>

			<table class="table table-striped">
				<?# -- table head -- ?>
				<thead>
					<tr>
						<th class="col-md-4">Projectnaam</th>
						<th class="col-md-2">Opdrachtgever</th>
						<th class="col-md-1">Type</th>
						<th class="col-md-3">Adres</th>
						<th class="col-md-2">Plaats</th>
					</tr>
				</thead>

				<!-- table items -->
				<tbody>
				@foreach (Project::where('user_id','=', Auth::user()->id)->get() as $project)
					<tr>
						<td class="col-md-4">{{ HTML::link('/project-'.$project->id.'/edit', $project->project_name) }}</td>
						<td class="col-md-2">{{ $project->contactor->company_name }}</td>
						<td class="col-md-1">{{ $project->type->type_name }}</td>
						<td class="col-md-3">{{ $project->address_street }}</td>
						<td class="col-md-2">{{ $project->address_city }}</td>
					</tr>
				@endforeach
				</tbody>
			</table>
			<div class="row">
				<div class="col-md-12">
					<a href="project/new" class="btn btn-primary"><i class="fa fa-pencil"></i> Nieuw project</a>
				</div>
			</div>
		</div>

	</section>

</div>
<!-- /WRAPPER -->
@stop
