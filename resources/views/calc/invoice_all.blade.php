<?php

use \Calctool\Models\Project;
use \Calctool\Models\Offer;
use \Calctool\Models\Invoice;
use \Calctool\Calculus\ResultEndresult;
use \Calctool\Http\Controllers\InvoiceController;
use \Calctool\Models\InvoiceTerm;
use \Calctool\Models\InvoiceVersion;

$common_access_error = false;
$project = Project::find(Route::Input('project_id'));
if (!$project || !$project->isOwner() || $project->is_dilapidated) {
	$common_access_error = true;
} else {
	$offer_last = Offer::where('project_id','=',$project->id)->orderBy('created_at', 'desc')->first();
}
?>

@extends('layout.master')

@section('title', 'Factuurbeheer')

@push('scripts')
<script src="/plugins/jquery.number.min.js"></script>
@endpush

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
			$curdesc = $(this).attr('data-desc');
			$('#description').val($curdesc);
			$curclosure = $(this).attr('data-closure');
			$('#closure').val($curclosure);
			$lastthis2 = $(this);
		});
		function calcend() {
			$total = {{ ResultEndresult::totalProject($project) }};
			$('.adata').each(function(){
				$total -= $(this).val().toString().split('.').join('').replace(',', '.');
			});
			$('.sdata').each(function(){
				var $sint = parseFloat($(this).text().toString().split('.').join('').replace(',', '.'));
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

		$('.dopay').click(function(e){
			if(confirm('Factuur betalen?')){
				$curThis = $(this);
				$curproj = $(this).attr('data-project');
				$curinv = $(this).attr('data-invoice');
				$.post("/invoice/pay", {project: {{ $project->id }}, id: $curinv, projectid: $curproj}, function(data){
					location.reload();
				}).fail(function(e) { console.log(e); });
			};
		});

		$('.docredit').click(function(e){
			if(confirm('Factuur in credit brengen?')){
				$curThis = $(this);
				$curproj = $(this).attr('data-project');
				$curinv = $(this).attr('data-invoice');
				$.post("/invoice/creditinvoice", {project: {{ $project->id }}, id: $curinv, projectid: $curproj}, function(data){
					location.reload();
				}).fail(function(e) { console.log(e); });
			};
		});

	});
</script>
<div id="wrapper">

	<section class="container">

		@include('calc.wizard', array('page' => 'invoice'))

			<div class="modal fade" id="codeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">

						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title" id="myModalLabel2">@if (Auth::user()->pref_use_ct_numbering)Referentie opdrachtgever @else Referentie en factuurnummer @endif</h4>
						</div>

						<div class="modal-body">
							<div class="form-horizontal">
								<div class="form-group">
									<div class="col-md-6">
										<label>Referentie van opdrachtgever</label> <a data-toggle="tooltip" data-placement="bottom" data-original-title="Als je van de opdrachtgever een referentie(nummer) hebt gekregen kan je deze hier invullen." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a>
										<input {{ $project->project_close ? 'disabled' : '' }} maxlength="30" value="" name="reference" id="reference" min="2" max="50" type="text" value="" class="form-control" />
									</div>
									@if (!Auth::user()->pref_use_ct_numbering)
									<div class="col-md-6">
										<label>Eigen factuurnummer gebruiken</label> <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit is een uniek en opvolgend factuurnummer. Eigen factuurnummering is ook mogelijk. Let op: Factuurnummers moeten opvolgend zijn, gebruik dus het een of het ander." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a>
										<input {{ $project->project_close ? 'disabled' : '' }} maxlength="30" value="" name="bookcode" id="bookcode" min="2" max="50" type="text" value="" class="form-control" />
									</div>
									@endif
								</div>
							</div>
						</div>

						<div class="modal-footer">
							<button class="btn btn-primary" data-dismiss="modal">Opslaan</button>
						</div>

					</div>
				</div>
			</div>

			<div class="modal fade" id="textModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">

						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title" id="myModalLabel2">Omschrijvingen</h4>
						</div>

						<div class="modal-body">
							<div class="form-horizontal">
								<div class="form-group">
									<div class="col-md-12">
										<label>Omschrijving voor</label>
										<textarea {{ $project->project_close ? 'disabled' : '' }} name="description" id="description" rows="5" class="form-control" data-desc=""></textarea>
									</div>
									<div class="col-md-12">
										<label>Omschrijving na</label>
										<textarea {{ $project->project_close ? 'disabled' : '' }} name="closure" id="closure" rows="5" class="form-control"></textarea>
									</div>
								</div>
							</div>
						</div>

						<div class="modal-footer">
							<button class="btn btn-primary" data-dismiss="modal">Opslaan</button>
						</div>

					</div>
				</div>
			</div>

			@if (!ResultEndresult::totalProject($project))
			<div class="alert alert-warning">
				<i class="fa fa-fa fa-info-circle"></i>
				Facturen kunnen pas worden gemaakt wanneer het project waarde bevat
			</div>
			@else

			<div class="pull-right">
				<a href="/project-{{ $project->id }}/packingslip" target="new" class="btn btn-primary">Pakbon maken</a>
			</div>
			<h2><strong>Factuurbeheer</strong></h2>

			<div class="white-row">
			<table class="table table-striped">
				<thead>
					<tr>
						<th class="col-md-2">Onderdeel</th>
						<th class="col-md-2">Bedrag (Excl. BTW) <a data-toggle="tooltip" data-placement="bottom" data-original-title="Geef hier een termijnbedrag of eindbedrag op." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></th>
						<th class="col-md-2">Factuurnummer <a data-toggle="tooltip" data-placement="bottom" data-original-title="Geef hier uw factuurnummer op dat behoort bij uw boekhouding." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></th>
						<th class="col-md-1">Nummering <a data-toggle="tooltip" data-placement="bottom" data-original-title="Geef hier een referentie en/of een debiteurennummer op." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></th>
						<th class="col-md-2">Omschrijving <a data-toggle="tooltip" data-placement="bottom" data-original-title="Hier kunt u een aanhef en een afsluiting opgeven voor op de factuur." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></th>
						<th class="col-md-1">Conditie <a data-toggle="tooltip" data-placement="bottom" data-original-title="Hier kunt u opgeven wat de betalingstermijn van de factuur is." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></th>
						<th class="col-md-2">Status <a data-toggle="tooltip" data-placement="bottom" data-original-title="Hier staat de status van uw factuur. Hij is open, te factureren of gefactureerd. Tevens is de PDF te raadplegen en te downloaden." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></th>
						<th class="col-md-1"></th>
					</tr>
				</thead>

				<tbody>
				<?php
				if ($offer_last) {
				$i=0;$skip=0;
				$close = true;
				$count = Invoice::where('offer_id',$offer_last->id)->count();
				$invoice_end = Invoice::where('offer_id',$offer_last->id)->where('isclose',true)->first();
				$creditinvoice_end = Invoice::where('offer_id',$offer_last->id)->where('isclose',true)->where('amount','<',0)->first();
				?>
				@foreach (Invoice::where('offer_id',$offer_last->id)->where('isclose',false)->orderBy('priority')->orderBy('created_at')->get() as $invoice)
				<?php $invoice_version = InvoiceVersion::where('invoice_id', $invoice->id)->orderBy('created_at','desc')->first(); ?>
					<tr>
						<td class="col-md-2">
							<?php
							$need_a = false;
							if (!$invoice->invoice_close) {
								if ($invoice_version) {
									echo  '<a href="/invoice/project-' . $project->id . '/invoice-version-'.$invoice_version->id.'">';
									$need_a = true;
								} else if (!$project->project_close) {
									echo '<a href="/invoice/project-' . $project->id . '/term-invoice-' . $invoice->id . '">';
									$need_a = true;
								}
							} else {
								echo '<a href="/invoice/project-' . $project->id . '/pdf-invoice-'.$invoice->id.'">';
								$need_a = true;
							}
							
							if ($i==0 && $offer_last->downpayment) {
								echo 'Aanbetaling';
							} else if ($invoice->amount < 0) {
								echo 'Creditfactuur ' . ($i-$skip);
								$skip++;
							} else {
								echo 'Termijnfactuur ' . (($i+1)-$skip);
							}
							
							if ($need_a) {
								echo '</a>';
							} ?>
						</td>
						<td class="col-md-2"><?php if ($invoice->invoice_close || $project->project_close){ echo "<span class='sdata'>".number_format($invoice->amount, 2, ",",".")."</span>"; } else  { ?><input data-id="{{ $invoice->id }}" class="form-control-sm-text adata" maxlength="9" name="amount" type="text" value="{{ number_format($invoice->amount, 2, ",",".") }}" /><?php } ?></td>
						<td class="col-md-2">{{ Auth::user()->pref_use_ct_numbering ? $invoice->invoice_code : ($invoice->book_code ? $invoice->book_code : $invoice->invoice_code) }}</td>
						<td class="col-md-1"><?php if (!$invoice->invoice_close && !$project->project_close) { ?><a href="#" data-toggle="modal" class="changecode adata" data-reference="{{ $invoice->reference }}" data-bookcode="{{ $invoice->book_code }}" data-id="{{ $invoice->id }}" data-target="#codeModal">bewerk</a><?php } ?></td>
						<td class="col-md-1"><?php if (!$invoice->invoice_close && !$project->project_close) { ?><a href="#" data-toggle="modal" class="changedesc" data-desc="{{ $invoice->description ? $invoice->description : Auth::user()->pref_invoice_description }}" data-closure="{{ $invoice->closure ? $invoice->closure : Auth::user()->pref_invoice_closure }}" data-id="{{ $invoice->id }}" data-target="#textModal">bewerk</a><?php } ?></td>
						<td class="col-md-2"><input {{ $project->project_close || $invoice->invoice_close ? 'disabled' : '' }} type="number" min="0" max="180" name="condition" data-id="{{ $invoice->id }}" value="{{ $invoice->payment_condition }}" class="condition form-control form-control-sm-number" /></td>
						<td class="col-md-1">
						<?php
						if ($invoice->invoice_close) {
						?>
						  <div class="btn-group" role="group">
						    <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    @if ($invoice->payment_date)
						    Betaald
						    @else
						      Gefactureerd
						     @endif
						      <span class="caret"></span>
						    </button>
						    <ul class="dropdown-menu">
						      @if (!$invoice->payment_date && !$project->project_close)
						      <li><a target="blank" href="javascript:void(0);" data-invoice="{{ $invoice->id }}" data-project="{{ $project->id }}" class="dopay">Betaald</a></li>
						      @endif
						      <li><a href="/res-{{ $invoice->resource_id }}/download">Download PDF</a></li>
						      @if ($invoice->amount > 0)
						      <li><a href="/invoice/project-{{ $project->id }}/history-invoice-{{ $invoice->id }}">Geschiedenis</a></li>
						      <?php
						      $is_credit_invoice = Invoice::where('priority',$invoice->priority)->where('reference',$invoice->invoice_code)->where('amount','<',0)->count();
						      if ($is_credit_invoice == 0 && !$project->project_close) {
						      ?>
						      <li><a href="javascript:void(0);" data-invoice="{{ $invoice->id }}" data-project="{{ $project->id }}" class="docredit">Creditfactuur</a></li>
						      <?php } ?>
						      @endif
						    </ul>
						  </div>
						<?php
						} else {
							echo 'Open';
							$close=false;
						}
						?>
						</td></td><input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
						<td class="col-md-1"><?php if (!$invoice->invoice_close && !$project->project_close) { ?><form method="POST" id="frm-delete" action="/invoice/term/delete">{!! csrf_field() !!}<input name="id" value="{{ $invoice->id }}" type="hidden"/><button class="btn btn-danger btn-xs fa fa-times deleterow"></button></form><?php } ?></td>
					</tr>
				<?php $i++; ?>
				@endforeach
				<?php if ($invoice_end) { ?>
				<?php $invoice_version = InvoiceVersion::where('invoice_id', $invoice_end->id)->orderBy('created_at','desc')->first(); ?>
					<tr>
						<td class="col-md-2">
						<?php
							if (!$invoice_end->invoice_close) {
								if ($invoice_version) {
									echo '<a href="/invoice/project-' . $project->id . '/invoice-version-'.$invoice_version->id.'">Eindfactuur</a>';
								} else if (!$project->project_close) {
									echo '<a href="/invoice/project-' . $project->id . '/invoice-' . $invoice_end->id . '">Eindfactuur</a>';
								} else {
									echo 'Eindfactuur';
								}
							} else {
								echo '<a href="/invoice/project-' . $project->id . '/pdf-invoice-'.$invoice_end->id.'">Eindfactuur</a>';
							}
						?></td>
						<td class="col-md-2"><span id="endterm">0</span></td>
						<td class="col-md-2">{{ Auth::user()->pref_use_ct_numbering ? $invoice_end->invoice_code : ($invoice_end->book_code ? $invoice_end->book_code : $invoice_end->invoice_code) }}</td>
						<td class="col-md-2"><?php if (!$invoice_end->invoice_close && !$project->project_close) { ?><a href="#" data-toggle="modal" class="changecode" data-reference="{{ $invoice_end->reference }}" data-bookcode="{{ $invoice_end->book_code }}" data-id="{{ $invoice_end->id }}" data-target="#codeModal">bewerk</a><?php } ?></td>
						<td class="col-md-2"><?php if (!$invoice_end->invoice_close && !$project->project_close) { ?><a href="#" data-toggle="modal" class="changedesc" data-desc="{{ $invoice_end->description ? $invoice_end->description : Auth::user()->pref_invoice_description }}" data-closure="{{ $invoice_end->closure ? $invoice_end->closure : Auth::user()->pref_invoice_closure }}" data-id="{{ $invoice_end->id }}" data-target="#textModal">bewerk</a><?php } ?></td>
						<td class="col-md-1"><input {{ $project->project_close || $invoice_end->invoice_close ? 'disabled' : '' }} type="number" min="0" max="180" name="condition" data-id="{{ $invoice_end->id }}" value="{{ $invoice_end->payment_condition }}" class="form-control form-control-sm-number condition" /></td>
						<td class="col-md-1">
						<?php
						if ($invoice_end->invoice_close) {
						?>
						  <div class="btn-group" role="group">
						    <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    @if ($invoice_end->payment_date)
						    Betaald
						    @else
						      Gefactureerd
						     @endif
						      <span class="caret"></span>
						    </button>
						    <ul class="dropdown-menu">
						      @if (!$invoice_end->payment_date && !$project->project_close)
						      <li><a target="blank" href="javascript:void(0);" data-invoice="{{ $invoice_end->id }}" data-project="{{ $project->id }}" class="dopay">Betaald</a></li>
						      @endif
						      <li><a href="/res-{{ $invoice_end->resource_id }}/download">Download PDF</a></li>
						      @if ($invoice_end->amount > 0)
						      <li><a href="/invoice/project-{{ $project->id }}/history-invoice-{{ $invoice_end->id }}">Geschiedenis</a></li>
						      <?php
						      $is_credit_invoice = Invoice::where('priority',$invoice_end->priority)->where('reference',$invoice_end->invoice_code)->where('isclose',true)->where('amount','<',0)->count();
						      if ($is_credit_invoice == 0 && !$project->project_close) {
						      ?>
						      <li><a href="javascript:void(0);" data-invoice="{{ $invoice_end->id }}" data-project="{{ $project->id }}" class="docredit">Creditfactuur</a></li>
						      <?php } ?>
						      @endif
						    </ul>
						  </div>
						<?php 
						} else {
							echo 'Open';
						}
						?></td></td>
						<td class="col-md-1"></td>
					</tr>
				<?php }} ?>
				<?php if ($creditinvoice_end) { ?>
					<tr>
						<td class="col-md-2"><a href="/invoice/project-{{ $project->id }}/pdf-invoice-{{ $creditinvoice_end->id }}">Creditfactuur</a></td>
						<td class="col-md-2">{{ number_format($creditinvoice_end->amount, 2, ",",".") }}</td>
						<td class="col-md-2">{{ Auth::user()->pref_use_ct_numbering ? $creditinvoice_end->invoice_code : ($creditinvoice_end->book_code ? $creditinvoice_end->book_code : $creditinvoice_end->invoice_code) }}</td>
						<td class="col-md-2"></td>
						<td class="col-md-2"></td>
						<td class="col-md-1"><input disabled type="number" name="condition" value="{{ $creditinvoice_end->payment_condition }}" class="form-control form-control-sm-number condition" /></td>
						<td class="col-md-1">
						  <div class="btn-group" role="group">
						    <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    @if ($creditinvoice_end->payment_date)
						    Betaald
						    @else
						      Gefactureerd
						     @endif
						      <span class="caret"></span>
						    </button>
						    <ul class="dropdown-menu">
						      @if (!$creditinvoice_end->payment_date && !$project->project_close)
						      <li><a target="blank" href="javascript:void(0);" data-invoice="{{ $creditinvoice_end->id }}" data-project="{{ $project->id }}" class="dopay">Betaald</a></li>
						      @endif
						      <li><a href="/res-{{ $creditinvoice_end->resource_id }}/download">Download PDF</a></li>
						    </ul>
						  </div>
						<td class="col-md-1"></td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
			@if (!$project->project_close && ($invoice_end && !$invoice_end->invoice_close))
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
		@endif
		</div>

	</section>

</div>

@stop

<?php } ?>
