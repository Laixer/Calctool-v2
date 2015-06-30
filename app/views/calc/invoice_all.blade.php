<?php
$project = Project::find(Route::Input('project_id'));
$offer_last = Offer::where('project_id','=',$project->id)->orderBy('created_at', 'desc')->first();
?>

@extends('layout.master')

@section('content')
<?# -- WRAPPER -- ?>
<script type="text/javascript">
	$(document).ready(function() {
		$termid = 0;
		$lastthis = null;
		$('#codeModal').on('hidden.bs.modal', function() {
			console.log('save ' + $termid +' X ' + $('#reference').val() + ' Y ' + $('#bookcode').val());
			$.post("/invoice/updatecode", {id: $termid, reference: $('#reference').val(), bookcode: $('#bookcode').val()}).fail(function(e) { console.log(e); });
			$lastthis.attr('data-reference', $('#reference').val());
			$lastthis.attr('data-bookcode', $('#bookcode').val());
		});
		$('.changecode').click(function(){
			$termid = $(this).attr('data-id');
			$curreference = $(this).attr('data-reference');
			$('#reference').val($curreference);
			$curbookcode = $(this).attr('data-bookcode');
			$('#bookcode').val($curbookcode);
			$lastthis = $(this);
		});
		function calcend() {
			$total = {{ ResultEndresult::totalProject($project) }};
			$('.adata').each(function(){
				$total -= $(this).val();
			});
			$('#endterm').html('&euro; '+ $.number($total,2,',','.'));
		};
		calcend();
		<?php if (Invoice::where('offer_id','=', $offer_last->id)->count()>1) { ?>
		$('.adata').change(function(){
			var q = $(this).val();
			$termid = $(this).attr('data-id');
			calcend();
			$.post("/invoice/updateamount", {id: $termid, project: {{ $project->id }}, amount: q, totaal: $total}).fail(function(e) { console.log(e); });
		});
		<?php } ?>
		$('.condition').change(function(e){
			var $val = $(this).val();
			$termid = $(this).attr('data-id');
			$.post("/invoice/updatecondition", {id: $termid, condition: $val}).fail(function(e) { console.log(e); });
		});
	});
</script>
<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			<div class="wizard">
				<a href="/"> Home</a>
				<a href="/project-{{ $project->id }}/edit">Project</a>
				<a href="/calculation/project-{{ $project->id }}">Calculatie</a>
				<a href="/offer/project-{{ $project->id }}">Offerte</a>
				<a href="/estimate/project-{{ $project->id }}">Stelpost</a>
				<a href="/less/project-{{ $project->id }}">Minderwerk</a>
				<a href="/more/project-{{ $project->id }}">Meerwerk</a>
				<a href="/invoice/project-{{ $project->id }}" class="current">Factuur</a>
				<a href="/result/project-{{ $project->id }}">Resultaat</a>
			</div>

			<div class="modal fade" id="codeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">

						<div class="modal-header"><!-- modal header -->
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title" id="myModalLabel2">Factuurnummers</h4>
						</div><!-- /modal header -->

						<!-- modal body -->
						<div class="modal-body">
							<div class="form-horizontal">
								<div class="form-group">
									<div class="col-md-6">
										<label>Referentie</label>
										<input value="" name="reference" id="reference" min="2" max="50" type="text" value="" class="form-control" />
									</div>
									<div class="col-md-6">
										<label>Boekhoudkundignummer</label>
										<input value="" name="bookcode" id="bookcode" min="2" max="50" type="text" value="" class="form-control" />
									</div>
								</div>
							</div>
						</div>
						<!-- /modal body -->

						<div class="modal-footer"><!-- modal footer -->
							<button class="btn btn-default" data-dismiss="modal">Close</button>
						</div><!-- /modal footer -->

					</div>
				</div>
			</div>

			<hr />

			<h2><strong>Factuurbeheer</strong></h2>
			<table class="table table-striped">
				<?# -- table head -- ?>
				<thead>
					<tr>
						<th class="col-md-4">Onderdeel</th>
		Factuurbeheer				<th class="col-md-2">Factuurbedrag</th>
						<th class="col-md-1">Faxtuurnummer</th>
						<th class="col-md-3">Omschrijving</th>
						<th class="col-md-2">Betalingscondities</th>
						<th class="col-md-2">Aangemaakt</th>
						<th class="col-md-2">Status</th>
					</tr>
				</thead>

				<tbody>
				<?php
				$i=0;
				$close = true;
				$count = Invoice::where('offer_id','=', $offer_last->id)->count();
				?>
				@foreach (Invoice::where('offer_id','=', $offer_last->id)->where('isclose','=',false)->orderBy('priority')->get() as $invoice)
					<tr>
						<td class="col-md-4"><a href="/invoice/project-{{ $project->id }}/term-invoice-{{ $invoice->id }}">{{ ($i==0 && $offer_last->downpayment ? 'Aanbetaling' : 'Termijnfactuur '.($i+1)) }}</a></td>
						<td class="col-md-2"><?php if ($invoice->invoice_close){ echo "<span>".$invoice->amount."</span>"; } else  { ?><input data-id="{{ $invoice->id }}" class="form-control-sm-text adata" name="amount" type="text" value="{{ $invoice->amount }}" /><?php } ?></td>
						<td class="col-md-1"><a href="#" data-toggle="modal" class="changecode" data-reference="{{ $invoice->reference }}" data-bookcode="{{ $invoice->book_code }}" data-id="{{ $invoice->id }}" data-target="#codeModal">{{ $invoice->invoice_code }}</a></td>
						<td class="col-md-3">{{ $invoice->description }}</td>
						<td class="col-md-2"><input type="number" name="condition" data-id="{{ $invoice->id }}" value="{{ $invoice->payment_condition }}" class="condition form-control" /></td>
						<td class="col-md-2">{{-- $invoice->created_at --}}</td>
						<td class="col-md-2"><?php if ($invoice->invoice_close) { echo 'Gefactureerd'; } else if ($close) { echo '<form method="POST" id="frm-invoice" action="/invoice/close"><input name="id" value="'.$invoice->id.'" type="hidden"/><input name="projectid" value="'.$project->id.'" type="hidden"/><input type="submit" class="btn btn-primary btn-xs" value="Factureren"/></form>'; $close=false; } else { echo 'Open'; } ?></td></td>
					</tr>
				<?php $i++; ?>
				@endforeach
				<?php $invoice_end = Invoice::where('offer_id','=', $offer_last->id)->where('isclose','=',true)->first(); ?>
					<tr>
						<td class="col-md-4"><a href="/invoice/project-{{ $project->id }}/invoice-{{ $invoice_end->id }}">Eindfactuur</a></td>
						<td class="col-md-2"><span id="endterm">0</span></td>
						<td class="col-md-1"><a href="#" data-toggle="modal" class="changecode" data-reference="{{ $invoice_end->reference }}" data-bookcode="{{ $invoice_end->book_code }}" data-id="{{ $invoice_end->id }}" data-target="#codeModal">{{ $invoice_end->invoice_code }}</a></td>
						<td class="col-md-3">{{ $invoice_end->description }}</td>
						<td class="col-md-2"><input type="number" name="condition" data-id="{{ $invoice_end->id }}" value="{{ $invoice_end->payment_condition }}" class="form-control condition" /></td>
						<td class="col-md-2">{{-- $invoice_end->created_at --}}</td>
						<td class="col-md-2"><?php if ($invoice_end->invoice_close) { echo 'Gefactureerd'; } else if ($close) { echo '<form method="POST" id="frm-invoice" action="/invoice/close"><input name="id" value="'.$invoice_end->id.'" type="hidden"/><input name="projectid" value="'.$project->id.'" type="hidden"/><input type="submit" class="btn btn-primary btn-xs" value="Factureren"/></form>'; $close=false; } else { echo 'Open'; } ?></td></td>
					</tr>
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
