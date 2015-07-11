@extends('layout.master')

@section('content')
<?# -- WRAPPER -- ?>
<script type="text/javascript">
	$(document).ready(function() {
		$req = false;
		$("#search").keyup(function() {
			$val = $(this).val();
			if ($val.length > 3 && !$req) {
				$group = $('#group').val();
				$req = true;
				$.post("/material/search", {query:$val,group:$group}, function(data) {
					if (data) {
						$('table tbody tr').remove();
						$.each(data, function(i, item) {
							$('table tbody').append('<tr><td>'+item.description+'</td><td>'+item.package+'</td><td>'+item.minimum_quantity+'</td><td>'+item.unit+'</td><td>'+item.price+'</td></tr>');
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

			<!--<div class="form-group input-group-lg">-->
			<div class="form-group input-group input-group-lg">
				<input type="text" id="search" value="" class="form-control" placeholder="Zoek materiaal">
			      <span class="input-group-btn">
			        <select id="group" class="btn">
			        <option value="0" selected>Alles</option>
			        @foreach (SubGroup::all() as $group)
			          <option value="{{ $group->id }}">{{ $group->group_type }}</option>
			        @endforeach
			        </select>
			      </span>
			</div>

			<div class="table-responsive">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Omschrijving</th>
							<th>Afmeting</th>
							<th>Minimum hoeveelheid</th>
							<th>Eenheid</th>
							<th>Totaalprijs</th>
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
