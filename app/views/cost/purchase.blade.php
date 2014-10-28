@extends('layout.master')

@section('content')
<?# -- WRAPPER -- ?>
<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			<h2><strong>Inkoopfacturen</strong></h2>

				<table class="table table-striped">
					<?# -- table head -- ?>
					<thead>
						<tr>
							<th class="col-md-4">Leverancier</th>
							<th class="col-md-3">Factuurbedrag (excl. BTW)</th>
							<th class="col-md-3">Factuur behorende bij</th>
							<th class="col-md-1">&nbsp;</th>
							<th class="col-md-1">&nbsp;</th>
						</tr>
					</thead>

					<!-- table items -->
					<tbody>
						<tr><!-- item -->
							<td class="col-md-4">Destil</td>
							<td class="col-md-3">$206,01</td>
							<td class="col-md-3">Aanneming</td>
							<td class="col-md-1"><button class="btn btn-primary btn-xs fa fa-comment-o"> Notitie</button></td>
							<td class="col-md-1"><button class="btn btn-danger btn-xs fa fa-times"></button></td>
						</tr>
						<tr><!-- item -->
							<td class="col-md-4">Destil</td>
							<td class="col-md-3">$206,01</td>
							<td class="col-md-3">Aanneming</td>
							<td class="col-md-1"><button class="btn btn-primary btn-xs fa fa-comment-o"> Notitie</button></td>
							<td class="col-md-1"><button class="btn btn-danger btn-xs fa fa-times"></button></td>
						</tr>
						<tr><!-- item -->
							<td class="col-md-4">Destil</td>
							<td class="col-md-3">$206,01</td>
							<td class="col-md-3">Aanneming</td>
							<td class="col-md-1"><button class="btn btn-primary btn-xs fa fa-comment-o"> Notitie</button></td>
							<td class="col-md-1"><button class="btn btn-danger btn-xs fa fa-times"></button></td>
						</tr>
						<tr><!-- item -->
							<td class="col-md-4"><input type="text" class="form-control control-sm"/></td>
							<td class="col-md-3"><input type="number" class="form-control control-sm"/></td>
							<td class="col-md-3">
								<select name="type" id="type" class="form-control pointer control-sm">
									<option value="" selected="selected">Aanneming</option>
									<option value="" selected="selected">Meerwerk</option>
									<option value="" selected="selected">Stelpost</option>
								</select>
							</td>
							<td class="col-md-1"><button class="btn btn-primary btn-xs fa fa-comment-o"> Notitie</button></td>
							<td class="col-md-1">&nbsp;</td>
						</tr>
					</tbody>
				</table>
			</div>

		</div>

	</section>

</div>
<!-- /WRAPPER -->
@stop
