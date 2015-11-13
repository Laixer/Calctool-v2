<?php

use \Calctool\Models\Project;
use \Calctool\Models\Offer;
use \Calctool\Models\Invoice;
use \Calctool\Calculus\ResultEndresult;
use \Calctool\Http\Controllers\InvoiceController;
use \Calctool\Models\InvoiceTerm;

$common_access_error = false;
$project = Project::find(Route::Input('project_id'));
if (!$project || !$project->isOwner()) {
	$common_access_error = true;
} else {
	$offer_last = Offer::where('project_id','=',$project->id)->orderBy('created_at', 'desc')->first();
}
?>

@extends('layout.master')

<?php if($common_access_error){ ?>
@section('content')
<div id="wrapper">
	<section class="container">
		<div class="alert alert-danger">
			<i class="fa fa-frown-o"></i>
			<strong>Fout</strong>
			Dit project bestaat niet
		</div>
	</section>
</div>
@stop
<?php }else{ ?>

@section('content')
<script type="text/javascript">
	$(document).ready(function() {
		$termid = 0;
		$lastthis = null;
		$lastthis2 = null;
		$('#codeModal').on('hidden.bs.modal', function() {
			$.post("/invoice/updatecode", {project: {{ $project->id }}, id: $termid, reference: $('#reference').val(), bookcode: $('#bookcode').val()}).fail(function(e) { console.log(e); });
			$lastthis.attr('data-reference', $('#reference').val());
			$lastthis.attr('data-bookcode', $('#bookcode').val());
		});
		$('#textModal').on('hidden.bs.modal', function() {
			$.post("/invoice/updatedesc", {project: {{ $project->id }}, id: $termid, description: $('#description').val(), closure: $('#closure').val()}).fail(function(e) { console.log(e); });
			$lastthis2.attr('data-desc', $('#description').val());
			$lastthis2.attr('data-closure', $('#closure').val());
		});
		$('.changecode').click(function(){
			$termid = $(this).attr('data-id');
			$curreference = $(this).attr('data-reference');
			$('#reference').val($curreference);
			$curbookcode = $(this).attr('data-bookcode');
			$('#bookcode').val($curbookcode);
			$lastthis = $(this);
		});
		$('.changedesc').click(function(){
			$termid = $(this).attr('data-id');
			$curdesc = $(this).attr('data-desc');
			$('#description').val($curdesc);
			$curclosure = $(this).attr('data-closure');
			$('#closure').val($curclosure);
			$lastthis2 = $(this);
		});
		function calcend() {
			$total = {{ ResultEndresult::totalProject($project) }};
			$('.adata').each(function(){
				$total -= $(this).val().toString().split('.').join('').replace(',', '.');;
			});
			$('.sdata').each(function(){
				var $sint = parseFloat($(this).text());
				if (!isNaN($sint))
					$total -= $sint;
			});
			$('#endterm').html($.number($total,2,',','.'));
		};
		calcend();
		<?php
		if ($offer_last) {
		if (Invoice::where('offer_id','=', $offer_last->id)->count()>1) {
		?>
		$('.adata').change(function(){
			var q = $(this).val();
			$termid = $(this).attr('data-id');
			calcend();
			$.post("/invoice/updateamount", {project: {{ $project->id }}, id: $termid, project: {{ $project->id }}, amount: q, totaal: $total}).fail(function(e) { console.log(e); });
		});
		<?php
		}
		}
		?>
		$('.condition').change(function(e){
			var $val = $(this).val();
			$termid = $(this).attr('data-id');
			$.post("/invoice/updatecondition", {project: {{ $project->id }}, id: $termid, condition: $val}).fail(function(e) { console.log(e); });
		});
		$('#new-term').click(function(e) {
			e.preventDefault();
			$('#frm-add').submit();
		});
		$('.deleterow').click(function(e){
			$(this).parent().find('form').submit();
		});
	});
</script>
<div id="wrapper">

	<section class="container">

		@include('calc.wizard', array('page' => 'invoice'))

			<div class="modal fade" id="codeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">

						<div class="modal-header"><!-- modal header -->
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title" id="myModalLabel2">Administratienummers</h4>
						</div><!-- /modal header -->

						<!-- modal body -->
						<div class="modal-body">
							<div class="form-horizontal">
								<div class="form-group">
									<div class="col-md-6">
										<label>Referentie van opdrachtgever</label> <a data-toggle="tooltip" data-placement="bottom" data-original-title="Als je van de opdrachtgever een referentie(nummer) hebt gekregen kan je deze hier invullen." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a>
										<input {{ $project->project_close ? 'disabled' : '' }} value="" name="reference" id="reference" min="2" max="50" type="text" value="" class="form-control" />
									</div>
									<div class="col-md-6">
										<label>Eigen factuurnummer gebruiken</label> <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit is een uniek en opvolgend factuurnummer. Eigen factuurnummering is ook mogelijk. Let op: Factuurnummers moeten opvolgend zijn, gebruik dus het een of het ander." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a>
										<input {{ $project->project_close ? 'disabled' : '' }} value="" name="bookcode" id="bookcode" min="2" max="50" type="text" value="" class="form-control" />
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

			<div class="modal fade" id="textModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">

						<div class="modal-header"><!-- modal header -->
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title" id="myModalLabel2">Omschrijvingen</h4>
						</div><!-- /modal header -->

						<!-- modal body -->
						<div class="modal-body">
							<div class="form-horizontal">
								<div class="form-group">
									<div class="col-md-12">
										<label>Omschrijving voor</label>
										<textarea {{ $project->project_close ? 'disabled' : '' }} name="description" id="description" rows="5" class="form-control"></textarea>
									</div>
									<div class="col-md-12">
										<label>Omschrijving na</label>
										<textarea {{ $project->project_close ? 'disabled' : '' }} name="closure" id="closure" rows="5" class="form-control"></textarea>
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

			<div class="white-row">
			<h2><strong>Factuurbeheer</strong></h2>
			<table class="table table-striped">
				<?# -- table head -- ?>
				<thead>
					<tr>
						<th class="col-md-2">Onderdeel</th>
						<th class="col-md-2">Factuurbedrag (&euro;) <a data-toggle="tooltip" data-placement="bottom" data-original-title="Geef hier een termijnbedrag of eindbedrag op." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></th>
						<th class="col-md-2">Factuurnummer <a data-toggle="tooltip" data-placement="bottom" data-original-title="Geef hier uw factuurnummer op dat behoort bij uw boekhouding." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></th>
						<th class="col-md-1">Administratie <a data-toggle="tooltip" data-placement="bottom" data-original-title="Geef hier een referentie en/of een debiteurennummer op." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></th>
						<th class="col-md-2">Omschrijving <a data-toggle="tooltip" data-placement="bottom" data-original-title="Hier kunt u een aanhef en een afsluiting opgeven voor op de factuur." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></th>
						<th class="col-md-2">Betalingscondities <a data-toggle="tooltip" data-placement="bottom" data-original-title="Hier kunt u opgeven wat de betalingstermijn van de factuur is." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></th>
						<th class="col-md-1">Status <a data-toggle="tooltip" data-placement="bottom" data-original-title="Hier staat de status van uw factuur. Hij is open, te factureren of gefactureerd. Tevens is de PDF te raadplegen en te downloaden. Op de tab 'projectstatus' kunt u aangeven of de factuur betaald is. " href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></th>
						<th class="col-md-1"></th>
					</tr>
				</thead>

				<tbody>
				<?php
				if ($offer_last) {
				$i=0;
				$close = true;
				$count = Invoice::where('offer_id','=', $offer_last->id)->count();
				$invoice_end = Invoice::where('offer_id','=', $offer_last->id)->where('isclose','=',true)->first();
				?>
				@foreach (Invoice::where('offer_id','=', $offer_last->id)->where('isclose','=',false)->orderBy('priority')->get() as $invoice)
					<tr>
						<td class="col-md-2"><?php if (!$invoice->invoice_close && !$project->project_close) { echo '<a href="/invoice/project-' . $project->id . '/term-invoice-' . $invoice->id . '">'; } ?>{{ ($i==0 && $offer_last->downpayment ? 'Aanbetaling' : 'Termijnfactuur '.($i+1)) }}<?php if ($invoice->invoice_close) { echo '</a>'; }?></td>
						<td class="col-md-2"><?php if ($invoice->invoice_close || $project->project_close){ echo "<span class='sdata'>".number_format($invoice->amount, 2, ",",".")."</span>"; } else  { ?><input data-id="{{ $invoice->id }}" class="form-control-sm-text adata" name="amount" type="text" value="{{ number_format($invoice->amount, 2, ",",".") }}" /><?php } ?></td>
						<td class="col-md-2">{{ $invoice->invoice_code }}</td>
						<td class="col-md-1"><?php if (!$invoice->invoice_close && !$project->project_close) { ?><a href="#" data-toggle="modal" class="changecode" data-reference="{{ $invoice->reference }}" data-bookcode="{{ $invoice->book_code }}" data-id="{{ $invoice->id }}" data-target="#codeModal">bewerk</a><?php } ?></td>
						<td class="col-md-1"><?php if (!$invoice->invoice_close && !$project->project_close) { ?><a href="#" data-toggle="modal" class="changedesc" data-desc="{{ $invoice->description }}" data-closure="{{ $invoice->closure }}" data-id="{{ $invoice->id }}" data-target="#textModal">bewerk</a><?php } ?></td>
						<td class="col-md-2"><input {{ $project->project_close || $invoice->invoice_close ? 'disabled' : '' }} type="number" name="condition" data-id="{{ $invoice->id }}" value="{{ $invoice->payment_condition }}" class="condition form-control form-control-sm-number" /></td>
						<td class="col-md-1">
						<?php
						if ($invoice->invoice_close) {
						?>
						  <div class="btn-group" role="group">
						    <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						      Gefactureerd
						      <span class="caret"></span>
						    </button>
						    <ul class="dropdown-menu">
						      <li><a target="blank" href="/invoice/pdf/project-{{ $project->id }}/term-invoice-{{ $invoice->id }}{{ $invoice->option_query ? '?'.$invoice->option_query : '' }}">Bekijk PDF</a></li>
						      <li><a href="/invoice/pdf/project-{{ $project->id }}/term-invoice-{{ $invoice->id }}/download?file={{ InvoiceController::getInvoiceCode($project->id).'-factuur.pdf' }}{{ $invoice->option_query ? '&'.$invoice->option_query : '' }}">Download PDF</a></li>
						    </ul>
						  </div>
						<?php
						} else if ($close && !$project->project_close) {
							echo '<form method="POST" id="frm-invoice" action="/invoice/close"><input name="id" value="'.$invoice->id.'" type="hidden"/><input type="hidden" name="_token" value="'.csrf_token().'"><input name="projectid" value="'.$project->id.'" type="hidden"/><input type="submit" class="btn btn-primary btn-xs" value="Factureren"/></form>'; $close=false;
						} else {
							echo 'Open';
						}
						?>
						</td></td><input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
						<td class="col-md-1"><?php if (!$invoice->invoice_close && !$project->project_close) { ?><form method="POST" id="frm-delete" action="/invoice/term/delete">{!! csrf_field() !!}<input name="id" value="{{ $invoice->id }}" type="hidden"/><button class="btn btn-danger btn-xs fa fa-times deleterow"></button></form><?php } ?></td>
					</tr>
				<?php $i++; ?>
				@endforeach
				<?php if ($invoice_end) { ?>
					<tr>
						<td class="col-md-2"><?php if (!$invoice_end->invoice_close && !$project->project_close) { echo '<a href="/invoice/project-' . $project->id . '/invoice-' . $invoice_end->id . '">Eindfactuur</a>'; } else { echo 'Eindfactuur'; } ?></td>
						<td class="col-md-2"><span id="endterm">0</span></td>
						<td class="col-md-2">{{ $invoice_end->invoice_code }}</td>
						<td class="col-md-2"><?php if (!$invoice_end->invoice_close && !$project->project_close) { ?><a href="#" data-toggle="modal" class="changecode" data-reference="{{ $invoice_end->reference }}" data-bookcode="{{ $invoice_end->book_code }}" data-id="{{ $invoice_end->id }}" data-target="#codeModal">bewerk</a><?php } ?></td>
						<td class="col-md-2"><?php if (!$invoice_end->invoice_close && !$project->project_close) { ?><a href="#" data-toggle="modal" class="changedesc" data-desc="{{ $invoice_end->description }}" data-closure="{{ $invoice_end->closure }}" data-id="{{ $invoice_end->id }}" data-target="#textModal">bewerk</a><?php } ?></td>
						<td class="col-md-1"><input {{ $project->project_close || $invoice_end->invoice_close ? 'disabled' : '' }} type="number" name="condition" data-id="{{ $invoice_end->id }}" value="{{ $invoice_end->payment_condition }}" class="form-control form-control-sm-number condition" /></td>
						<td class="col-md-1">
						<?php
						if ($invoice_end->invoice_close) {
						?>
						  <div class="btn-group" role="group">
						    <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						      Gefactureerd
						      <span class="caret"></span>
						    </button>
						    <ul class="dropdown-menu">
						      <li><a target="blank" href="/invoice/pdf/project-{{ $project->id }}/invoice-{{ $invoice_end->id }}{{ $invoice_end->option_query ? '?'.$invoice_end->option_query : '' }}">Bekijk PDF</a></li>
						      <li><a href="/invoice/pdf/project-{{ $project->id }}/invoice-{{ $invoice_end->id }}/download?file={{ InvoiceController::getInvoiceCode($project->id).'-factuur.pdf' }}{{ $invoice_end->option_query ? '&'.$invoice_end->option_query : '' }}">Download PDF</a></li>
						    </ul>
						  </div>
						<?php } else if ($close && !$project->project_close) {
							echo '<form method="POST" id="frm-invoice" action="/invoice/close"><input type="hidden" name="_token" value="'.csrf_token().'"><input name="id" value="'.$invoice_end->id.'" type="hidden"/><input name="projectid" value="'.$project->id.'" type="hidden"/><input type="submit" class="btn btn-primary btn-xs" value="Factureren"/></form>'; $close=false;
						} else {
							echo 'Open';
						}
						?></td></td>
						<td class="col-md-1"></td>
					</tr>
				<?php }} ?>
				</tbody>
			</table>
			@if (!$project->project_close && !$invoice_end->invoice_close)
			<div class="row">
				<div class="col-md-12">
					<form method="POST" id="frm-add" action="/invoice/term/add">
					{!! csrf_field() !!}
						<input type="hidden" value="{{ $project->id }}" name="projectid" />
						<a href="#" id="new-term" class="btn btn-primary"><i class="fa fa-pencil"></i> Nieuw termijn toevoegen</a>
					</form>
				</div>
			</div>
			@endif
		</div>
		</div>

	</section>

</div>
<!-- /WRAPPER -->
@stop

<?php } ?>
