@extends('layout.master')

@section('content')
<?# -- WRAPPER -- ?>
<script type="text/javascript">
	$(document).ready(function() {
		$req = false;
		$( "#search" ).keyup(function() {
			$val = $(this).val();
			if ($val.length > 3 && !$req) {
				$req = true;
				$.post("/material/search", {query:$val}, function(data) {
					if (data) {
						$('table tbody tr').remove();
						$.each(data, function(i, item) {
    						$('table tbody').append('<tr><td>'+item.description+'</td><td>'+item.package+'</td><td>'+item.unit+'</td><td>'+item.price+'</td><td>'+item.minimum_quantity+'</td></tr>');
						});
						$req = false;
					}
				});
			}
		});
	});
</script>
<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			<h2><strong>Materialenlijst</strong></h2>

			<div class="form-group input-group-lg">
				<input type="text" id="search" value="" class="form-control" placeholder="Input Placeholder">
			</div>

			<div class="table-responsive">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Omschrijving</th>
							<th>Afmeting</th>
							<th>Eenheid</th>
							<th>Prijs</th>
							<th>Minimum</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>

		</div>

	</section>

</div>
<!-- /WRAPPER -->
@stop
