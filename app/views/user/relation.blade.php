@extends('layout.master')

@section('content')
<?# -- WRAPPER -- ?>
<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			<h2><strong>Relaties</strong></h2>

			<table class="table table-striped">
				<?# -- table head -- ?>
				<thead>
					<tr>
						<th class="col-md-3">(Bedrijfs)naam</th>
						<th class="col-md-1">Relatietype</th>
						<th class="col-md-1">Telefoon</th>
						<th class="col-md-3">Email</th>
						<th class="col-md-2">Plaats</th>
						<th class="col-md-2">Website</th>
					</tr>
				</thead>

				<!-- table items -->
				<tbody>
				@foreach (Relation::where('user_id','=', Auth::user()->id)->get() as $relation)
					<tr>
						<td class="col-md-3">{{ $relation->company_name }}</td>
						<td class="col-md-1">{{ $relation->kind->kind_name }}</td>
						<td class="col-md-1">{{ $relation->phone }}</td>
						<td class="col-md-3">{{ $relation->email }}</td>
						<td class="col-md-2">{{ $relation->address_city }}</td>
						<td class="col-md-2">{{ $relation->website }}</td>
					</tr>
				@endforeach
				</tbody>
			</table>
			<div class="row">
				<div class="col-md-12">
					<a href="relation/new" class="btn btn-primary"><i class="fa fa-pencil"></i> Nieuwe relatie</a>
				</div>
			</div>
		</div>

	</section>

</div>
<!-- /WRAPPER -->
@stop
