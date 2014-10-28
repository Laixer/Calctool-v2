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
						<th class="col-md-2">Relatietype</th>
						<th class="col-md-2">Contactpersoon</th>
						<th class="col-md-2">Telefoon</th>
						<th class="col-md-2">Plaats</th>
					</tr>
				</thead>

				<!-- table items -->
				<tbody>
					<tr><!-- item -->
						<td class="col-md-4">Timmer er onderhoudsbedrijf M. Benner</td>
						<td class="col-md-2">Groothandel</td>
						<td class="col-md-2">Jan Piet</td>
						<td class="col-md-2">010 351 7425</td>
						<td class="col-md-2">Hamburg</td>
					</tr>
					<tr><!-- item -->
						<td class="col-md-4">Timmer er onderhoudsbedrijf M. Benner</td>
						<td class="col-md-2">Groothandel</td>
						<td class="col-md-2">Jan Piet</td>
						<td class="col-md-2">010 351 7425</td>
						<td class="col-md-2">Hamburg</td>
					</tr>
					<tr><!-- item -->
						<td class="col-md-4">Timmer er onderhoudsbedrijf M. Benner</td>
						<td class="col-md-2">Groothandel</td>
						<td class="col-md-2">Jan Piet</td>
						<td class="col-md-2">010 351 7425</td>
						<td class="col-md-2">Hamburg</td>
					</tr>
				</tbody>
			</table>
			<div class="row">
				<div class="col-md-12">
					<button class="btn btn-primary"><i class="fa fa-user"></i> Nieuwe relatie</button>
				</div>
			</div>
		</div>

	</section>

</div>
<!-- /WRAPPER -->
@stop
