@extends('layout.master')

@section('content')
<?# -- WRAPPER -- ?>
<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			<h2><strong>Gebruikers</strong></h2>

			<table class="table table-striped">
				<?# -- table head -- ?>
				<thead>
					<tr>
						<th class="col-md-2">Gebruikersnaam</th>
						<th class="col-md-2">Voornaam</th>
						<th class="col-md-2">Achternaam</th>
						<th class="col-md-2">Email</th>
						<th class="col-md-2">Status</th>
						<th class="col-md-2">Actief</th>
					</tr>
				</thead>

				<!-- table items -->
				<tbody>
				@foreach (User::all() as $users)
					<tr>
						<td class="col-md-2">{{ HTML::link('/user-'.$users->id.'/edit', $users->username) }}</td>
						<td class="col-md-2">{{ $users->firstname }}</td>
						<td class="col-md-2">{{ $users->lastname }}</td>
						<td class="col-md-2">{{ $users->email }}</td>
						<td class="col-md-2">{{ $users->active }}</td>
						<td class="col-md-2">{{ $users->last_active }}</td>
					</tr>
				@endforeach
				</tbody>
			</table>
			<div class="row">
				<div class="col-md-12">
					<a href="project/new" class="btn btn-primary"><i class="fa fa-pencil"></i> Nieuwe gebruiker</a>
				</div>
			</div>
		</div>

	</section>

</div>
<!-- /WRAPPER -->
@stop
