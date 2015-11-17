<?php
use \Calctool\Models\Project;
use \Calctool\Models\Purchase;
use \Calctool\Models\Relation;
use \Calctool\Models\PurchaseKind;
?>

@extends('layout.master')

@section('content')
<?# -- WRAPPER -- ?>
<script type="text/javascript">
	$(document).ready(function() {
		$('#addnewpurchase').click(function(e) {
			$curThis = $(this);
			e.preventDefault();
			$date = $curThis.closest("tr").find("input[name='date']").val();
			$amount = $curThis.closest("tr").find("input[name='amount']").val();
			$type = $curThis.closest("tr").find("select[name='typename']").val();
			$relation = $curThis.closest("tr").find("select[name='relation']").val();
			$note = $curThis.closest("tr").find("input[name='note']").val();
			$project = $curThis.closest("tr").find("select[name='projname']").val();
			$.post("/purchase/new", {
				date: $date,
				amount: $amount,
				type: $type,
				relation: $relation,
				note: $note,
				project: $project
			}, function(data){
				var $curTable = $curThis.closest("table");
				var json = $.parseJSON(data);
				if (json.success) {
					$curTable.find("tr:eq(1)").clone().removeAttr("data-id")
					.find("td:eq(0)").text(json.date).end()
					.find("td:eq(1)").text(json.relation).end()
					.find("td:eq(2)").html(json.amount).end()
					.find("td:eq(4)").text(json.type).end()
					.find("td:eq(5)").text($note).end()
					.find("td:eq(8)").html('<button class="btn btn-danger btn-xs fa fa-times deleterowp"></button>').end()
					.prependTo($curTable);
					$curThis.closest("tr").find("input").val("");
					$curThis.closest("tr").find("select").val("");
				}
			});
		});
		$("body").on("click", ".deleterow", function(e){
			e.preventDefault();
			var $curThis = $(this);
			if($curThis.closest("tr").attr("data-id"))
				$.post("/purchase/delete", {id: $curThis.closest("tr").attr("data-id")}, function(){
					$curThis.closest("tr").hide("slow");
				}).fail(function(e) { console.log(e); });
		});
	});
</script>
<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			<h2><strong>Inkoopfacturen</strong></h2>

			<div class="white-row">
				<table class="table table-striped">
					<?# -- table head -- ?>
					<thead>
						<tr>
							<th class="col-md-1">Datum</th>
							<th class="col-md-2">Relatie</th>
							<th class="col-md-1">Factuurbedrag</th>
							<th class="col-md-3">Project</th>
							<th class="col-md-1">Soort</th>
							<th class="col-md-3">Omschrijving</th>
							<th class="col-md-1">&nbsp;</th>
						</tr>
					</thead>

					<tbody>
						@foreach (Project::where('user_id','=',Auth::user()->id)->get() as $project)
						@foreach (Purchase::where('project_id','=', $project->id)->get() as $purchase)
						<tr data-id="{{ $purchase->id }}">
							<td class="col-md-1">{{ date('d-m-Y', strtotime($purchase->register_date)) }}</td>
							<td class="col-md-2">{{ Relation::find($purchase->relation_id)->company_name }}</td>
							<td class="col-md-1">{{ '&euro; '.number_format($purchase->amount, 2,",",".") }}</td>
							<td class="col-md-3">{{ $project->project_name }}</td>
							<td class="col-md-1">{{ ucwords(PurchaseKind::find($purchase->kind_id)->kind_name) }}</td>
							<td class="col-md-3">{{ $purchase->note }}</td>
							<td class="col-md-1"><button class="btn btn-danger btn-xs fa fa-times deleterow"></button></td>
						</tr>
						@endforeach
						@endforeach
						<tr>
							<td class="col-md-1">
								<input type="date" name="date" id="date" class="form-control-sm-text"/>
							</td>
							<td class="col-md-2">
								<select name="relation" id="relation" class="form-control-sm-text">
								@foreach (Relation::where('user_id','=', Auth::user()->id)->get() as $relation)
									<option value="{{ $relation->id }}">{{ ucwords($relation->company_name) }}</option>
								@endforeach
								</select>
							</td>
							<td class="col-md-1"><input type="text" min="0" name="amount" id="amount" class="form-control-sm-text"/></td>
							<td class="col-md-3">
								<select name="projname" id="projname" class="form-control-sm-text">
								@foreach (Project::where('user_id','=',Auth::id())->whereNull('project_close')->get() as $projectname)
									<option value="{{ $projectname->id }}">{{ ucwords($projectname->project_name) }}</option>
								@endforeach
								</select>
							</td>
							<td class="col-md-1">
								<select name="typename" id="typename" class="form-control-sm-text">
								@foreach (PurchaseKind::all() as $typename)
									<option value="{{ $typename->id }}">{{ ucwords($typename->kind_name) }}</option>
								@endforeach
								</select>
							</td>
							<td class="col-md-3"><input type="text" name="note" id="note" class="form-control-sm-text"/></td>
							<td class="col-md-1"><button id="addnewpurchase" class="btn btn-primary btn-xs"> Toevoegen</button></td>
						</tr>
					</tbody>
				</table>
			</div>
			</div>

		</div>

	</section>

</div>
<!-- /WRAPPER -->
@stop
