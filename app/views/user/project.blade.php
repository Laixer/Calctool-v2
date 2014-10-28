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
						<th class="col-md-2">Oprachtgever</th>
						<th class="col-md-1">Type</th>
						<th class="col-md-3">Adres</th>
						<th class="col-md-2">Plaats</th>
					</tr>
				</thead>

				<!-- table items -->
				<tbody>
					<tr><!-- item -->
						<td class="col-md-4">Onderhoudswerkzaamheden complex Amsterdam</td>
						<td class="col-md-2">K. Aas</td>
						<td class="col-md-1">Regiewerk</td>
						<td class="col-md-3">Anthonie van Leeuwenhoekweg 18E</td>
						<td class="col-md-2">Hamburg</td>
					</tr>
					<tr><!-- item -->
						<td class="col-md-4">Onderhoudswerkzaamheden complex Amsterdam</td>
						<td class="col-md-2">K. Aas</td>
						<td class="col-md-1">Regiewerk</td>
						<td class="col-md-3">Anthonie van Leeuwenhoekweg 18E</td>
						<td class="col-md-2">Hamburg</td>
					</tr>
					<tr><!-- item -->
						<td class="col-md-4">Onderhoudswerkzaamheden complex Amsterdam</td>
						<td class="col-md-2">K. Aas</td>
						<td class="col-md-1">Regiewerk</td>
						<td class="col-md-3">Anthonie van Leeuwenhoekweg 18E</td>
						<td class="col-md-2">Hamburg</td>
					</tr>
				</tbody>
			</table>
			<div class="row">
				<div class="col-md-12">
					<button class="btn btn-primary"><i class="fa fa-pencil"></i> Nieuw project</button>
				</div>
			</div>
		</div>

	</section>

</div>
<!-- /WRAPPER -->
@stop
