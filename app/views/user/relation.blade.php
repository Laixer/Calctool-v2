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
						<th class="col-md-4">(Bedrijfs)naam</th>
						<th class="col-md-2">Relatietype</th>
						<th class="col-md-2">Telefoon</th>
						<th class="col-md-2">Email</th>
						<th class="col-md-2">Plaats</th>
						<!-- <th class="col-md-2">Website</th> -->
					</tr>
				</thead>

				<!-- table items -->
				<tbody>
				<?php
				$userid = Auth::user()->self_id;
				if(Auth::user()->self_id)
					$userid = Auth::user()->self_id;
				else
					$userid = -1;
				foreach (Relation::where('user_id','=', Auth::user()->id)->where('id','!=',$userid)->get() as $relation) {
				?>
					<tr>
						<td class="col-md-4">{{ HTML::link('relation-'.$relation->id.'/edit', $relation->company_name) }}</td>
						<td class="col-md-2">{{ RelationKind::find($relation->kind_id)->kind_name }}</td>
						<td class="col-md-2">{{ $relation->phone }}</td>
						<td class="col-md-2">{{ $relation->email }}</td>
						<td class="col-md-2">{{ $relation->address_city }}</td>
						<!--m<td class="col-md-2">{{ $relation->website }}</td> -->
					</tr>
				<?php } ?>
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
