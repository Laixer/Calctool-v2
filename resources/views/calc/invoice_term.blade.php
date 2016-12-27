<?php

use \Calctool\Models\Project;
use \Calctool\Models\Relation;
use \Calctool\Models\Contact;
use \Calctool\Models\Invoice;
use \Calctool\Models\Offer;
use \Calctool\Models\Iban;
use \Calctool\Models\ProjectType;
use \Calctool\Models\InvoiceTerm;
use \Calctool\Models\InvoiceVersion;
use \Calctool\Models\Resource;
use \Calctool\Http\Controllers\InvoiceController;

$displaytax=Input::get("displaytax");
$common_access_error = false;
$project = Project::find(Route::Input('project_id'));
if (!$project || !$project->isOwner()) {
	$common_access_error = true;
} else {
	$relation = Relation::find($project->client_id);
	$relation_self = Relation::find(Auth::user()->self_id);
	$contact_self = Contact::where('relation_id','=',$relation_self->id);
	$invoice = Invoice::find(Route::Input('invoice_id'));
	$offer_last = Offer::where('project_id','=',$project->id)->orderBy('created_at', 'desc')->first();
	$invoice_last = Offer::where('project_id','=',$project->id)->orderBy('created_at', 'desc')->first();
	$invoice_version_cnt = InvoiceVersion::where('invoice_id', $invoice->id)->count();
}
?>

@extends('layout.master')

@section('title', 'Termijnfactuur')

@push('style')
<link media="all" type="text/css" rel="stylesheet" href="/components/x-editable/dist/bootstrap3-editable/css/bootstrap-editable.css">
<link media="all" type="text/css" rel="stylesheet" href="/plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css">
@endpush

@push('scripts')
<script src="/components/x-editable/dist/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
<script src="/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
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
        $('.only-end-total tr').each(function() {
            $(this).find("td").eq(2).hide();
            $(this).find("th").eq(2).hide();
            $(this).find("td").eq(3).hide();
            $(this).find("th").eq(3).hide();
            $(this).find("td").eq(4).hide();
            $(this).find("th").eq(4).hide();
            $(this).find("td").eq(5).hide();
            $(this).find("th").eq(5).hide();
        });
		$("[name='include-tax']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'}).on('switchChange.bootstrapSwitch', function(event, state) {
		  if (state) {
		        $('.hide-btw1 tr').each(function() {
	                $(this).find("td").eq(4).show();
	                $(this).find("th").eq(4).show();
	                $(this).find("td").eq(5).show();
	                $(this).find("th").eq(5).show();
	                $(this).find("td").eq(6).show();
	                $(this).find("th").eq(6).show();
		        });
		        $('.hide-btw2 tr').each(function() {
	                $(this).find("td").eq(2).show();
	                $(this).find("th").eq(2).show();
	                $(this).find("td").eq(3).show();
	                $(this).find("th").eq(3).show();
		        });
		        $('.hide-btw2').each(function() {
		        	$(this).find("tr").eq(2).show();
		        	$(this).find("tr").eq(3).show();
		        	$(this).find("tr").eq(4).show();
		        	$(this).find("tr").eq(5).show();
		        	$(this).find("tr").eq(6).show();
		        	$(this).find("tr").eq(7).show();
		        });
		  } else {
		        $('.hide-btw1 tr').each(function() {
	                $(this).find("td").eq(4).hide();
	                $(this).find("th").eq(4).hide();
	                $(this).find("td").eq(5).hide();
	                $(this).find("th").eq(5).hide();
	                $(this).find("td").eq(6).hide();
	                $(this).find("th").eq(6).hide();
		        });
		        $('.hide-btw2 tr').each(function() {
	                $(this).find("td").eq(2).hide();
	                $(this).find("th").eq(2).hide();
	                $(this).find("td").eq(3).hide();
	                $(this).find("th").eq(3).hide();
		        });
		        $('.hide-btw2').each(function() {
		        	$(this).find("tr").eq(2).hide();
		        	$(this).find("tr").eq(3).hide();
		        	$(this).find("tr").eq(4).hide();
		        	$(this).find("tr").eq(5).hide();
		        	$(this).find("tr").eq(6).hide();
		        	$(this).find("tr").eq(7).hide();
		        });
		  }
		});
		$("[name='toggle-activity']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'}).on('switchChange.bootstrapSwitch', function(event, state) {
		  if (state) {
		  	$('.show-activity').show();
		  	$("[name='toggle-summary']").bootstrapSwitch('toggleDisabled');
		  } else {
		  	$("[name='toggle-summary']").bootstrapSwitch('toggleDisabled');
			$('.show-activity').hide();
		  }
		});
		$("[name='toggle-note']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'}).on('switchChange.bootstrapSwitch', function(event, state) {
		  if (state) {
		  	$('.show-note').show();
		  } else {
			$('.show-note').hide();
		  }
		});
		$("[name='toggle-summary']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'}).on('switchChange.bootstrapSwitch', function(event, state) {
		  if (state) {
	        $('.only-end-total tr').each(function() {
                $(this).find("td").eq(2).show();
                $(this).find("th").eq(2).show();
                $(this).find("td").eq(3).show();
                $(this).find("th").eq(3).show();
                $(this).find("td").eq(4).show();
                $(this).find("th").eq(4).show();
                $(this).find("td").eq(5).show();
                $(this).find("th").eq(5).show();
	        });
		  } else {
	        $('.only-end-total tr').each(function() {
                $(this).find("td").eq(2).hide();
                $(this).find("th").eq(2).hide();
                $(this).find("td").eq(3).hide();
                $(this).find("th").eq(3).hide();
                $(this).find("td").eq(4).hide();
                $(this).find("th").eq(4).hide();
                $(this).find("td").eq(5).hide();
                $(this).find("th").eq(5).hide();
	        });
		  }
		});
		$tpayment = false;
		$("[name='toggle-payment']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'}).on('switchChange.bootstrapSwitch', function(event, state) {
			if (state) {
				$("#amount").prop('disabled', false);
				$tpayment = true;
			} else {
				$("#amount").prop('disabled', true);
				$tpayment = false;
			}

		});
		$('#termModal').on('hidden.bs.modal', function() {
			var q = $('#terms').val();
			if($.isNumeric(q)&&(q>1)&&(q<=50)) {
				$('#termtext').text('Indien opdracht wordt verstrekt, wordt gefactureerd in '+q+' termijnen');
				if ($tpayment)
					$('#paymenttext').html('Het eerste termijn geldt hierbij als een aanbetaling van &euro; '+$('.adata').first().val());
			}
		});
		$('.osave').click(function(e){
			e.preventDefault();
			$('#frm-invoice').get(0).setAttribute('action', '/invoice/save');
			$('#frm-invoice').submit();
		});
		$('#invdate').datepicker().on('changeDate', function(e){
			$('#invdate').datepicker('hide');
			$('#invdateval').val(e.date.toISOString());
			$('#invdate').text(e.date.getDate() + "-" + (e.date.getMonth() + 1)  + "-" + e.date.getFullYear());
		});
		$('#adressing').text($('#to_contact option:selected').text());
		$('#to_contact').change(function(e){
			$('#adressing').text($('#to_contact option:selected').text());
		});
		@if ($invoice_last && $invoice_last->invoice_make)
		$('#invdate').text("{{ date('d-m-Y', strtotime($offer_last->invoice_make)) }}");
		@endif
	});
</script>
<div id="wrapper">

	<section class="container printable fix-footer-bottom">

		@include('calc.wizard', array('page' => 'invoice'))

		@if (Session::has('success'))
		<div class="alert alert-success">
			<i class="fa fa-check-circle"></i>
			<strong>{{ Session::get('success') }}</strong>
		</div>
		@endif

		@if (count($errors) > 0)
		<div class="alert alert-danger">
			<i class="fa fa-frown-o"></i>
			<strong>Fout</strong>
			@foreach ($errors->all() as $error)
				{{ $error }}
			@endforeach
		</div>
		@endif

	<div class="pull-right">
		<!-- @if (!$invoice->invoice_close)
		<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Opties</a> -->
		<?php
		if (!$project->project_close) {
			$prev = Invoice::where('offer_id','=', $invoice->offer_id)->where('isclose','=',false)->where('priority','<',$invoice->priority)->orderBy('priority', 'desc')->first();
			$next = Invoice::where('offer_id','=', $invoice->offer_id)->where('isclose','=',false)->where('priority','>',$invoice->priority)->orderBy('priority')->first();
			$end = Invoice::where('offer_id','=', $invoice->offer_id)->where('isclose','=',true)->first();
			if ($prev && $prev->invoice_close && $next && !$next->invoice_close) {
				echo '<button class="btn btn-primary osave">Voorbeeld</button>&nbsp;';
			} else if (!$prev && $next && !$next->invoice_close) {
				echo '<button class="btn btn-primary osave">Voorbeeld</button>&nbsp;';
			} else if (!$prev && !$next) {
				echo '<button class="btn btn-primary osave">Voorbeeld</button>&nbsp;';
			} else if ($prev && $prev->invoice_close && $end && !$end->invoice_close) {
				echo '<button class="btn btn-primary osave">Voorbeeld</button>&nbsp;';
			}
		}
		?>
		@else
		<div class="btn-group">
		  <a target="blank" href="/invoice/pdf/project-{{ $project->id }}/term-invoice-{{ $invoice->id }}" class="btn btn-primary">PDF</a>
		  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		    <span class="caret"></span>
		    <span class="sr-only">Toggle Dropdown</span>
		  </button>
		  <ul class="dropdown-menu">
		    <li><a href="/invoice/pdf/project-{{ $project->id }}/term-invoice-{{ $invoice->id }}/download?file={{ InvoiceController::getInvoiceCode($project->id).'-factuur.pdf' }}">Download</a></li>
		  </ul>
		</div>
		@endif
	</div>


	<h2><strong>Termijnfactuur</strong></h2>
	<form method="POST" id="frm-invoice">
	{!! csrf_field() !!}

		<input name="id" value="{{ $invoice->id }}" type="hidden"/>
		<input name="projectid" value="{{ $project->id }}" type="hidden"/>


		<div class="white-row">
			<?#!--PAGE HEADER MASTER START--?>
			<header>
				<div class="row">
					<div class="col-sm-6">
						{!! ($relation_self && $relation_self->logo_id) ? "<img src=\"/".Resource::find($relation_self->logo_id)->file_location."\" class=\"img-responsive\" />" : '' !!}
					</div>
					<div class="col-sm-2"></div>
					<div class="col-sm-4 text-left">
						<p>
							<h4><strong>{{ $relation_self->company_name }}</strong></h4>
				    			<ul class="list-unstyled">
			 						<li>{{ $relation_self->address_street . ' ' . $relation_self->address_number }}</li>
			  						<li>{{ $relation_self->address_postal . ', ' . $relation_self->address_city }}</li>
			 						<li><i class="fa fa-phone"></i>&nbsp;{{ $relation_self->phone }}&nbsp;|&nbsp;<i class="fa fa-envelope-o"></i>&nbsp;{{ $relation_self->email }}</li>
			 						<li><strong>KVK: </strong>{{ $relation_self->kvk }}</li>
									<li><strong>BTW: </strong> {{ $relation_self->btw }}</li>
									<li><strong>IBAN: </strong> {{ $relation_self->iban }}</li>
									<li><strong>T.n.v.: </strong> {{ $relation_self->iban_name }}</li>
								<ul class="list-unstyled">
						</p>
					</div>
				</div>
			</header>
			<hr class="margin-top10 margin-bottom10">
			<?#--PAGE HEADER MASTER END--?>

	 		<?#--ADRESSING START--?>
	 		<main>
			<div class="row">
				<div class="col-sm-6">
					<ul class="list-unstyled">
						<li>{{ $relation->company_name }}</li>
						<li>T.a.v.
						@if ($invoice_last && $invoice_last->invoice_make)
							{{ Contact::find($invoice_last->to_contact_id)->getFormalName() }}
							@else
						<select name="to_contact" id="to_contact">
							@foreach (Contact::where('relation_id','=',$relation->id)->get() as $contact)
							<option {{ $invoice_last ? ($invoice_last->to_contact_id==$contact->id ? 'selected' : '') : '' }} value="{{ $contact->id }}">{{ Contact::find($contact->id)->getFormalName() }}</option>
							@endforeach
						</select>
						@endif
						</li>
						<li>{{ $relation->address_street . ' ' . $relation->address_number }}<br /> {{ $relation->address_postal . ', ' . $relation->address_city }}</li>
					</ul>
				</div>
				<div class="col-sm-2"></div>
				<div class="col-sm-4 text-left">
					<h4><strong>TERMIJNFACTUUR</strong></h4>
					<ul class="list-unstyled">
						<li><strong>Factuurnummer: </strong><?php if (Auth::user()->pref_use_ct_numbering) { echo $invoice->invoice_code; } else { echo $invoice->book_code; } ?></li>
						<li><strong>Projectnaam: </strong>{{ $project->project_name }}</li>
						<li><strong>Uw referentie: </strong> {{ $invoice->reference }}</li>
						<li><strong>Factuurdatum: </strong> <a href="#" id="invdate">Bewerk</a> {{-- date("j M Y") --}}</li>
						<input type="hidden" id="invdateval" name="invdateval" />
				</div>
			</div>
			<?#--ADRESSING END--?>

			<?#--DECRIPTION--?>
			<div class="row">
				<div class="col-sm-6">
				Geachte
				@if ($invoice_last && $invoice_last->invoice_make)
				{{ Contact::find($offer_last->to_contact_id)->getFormalName() }}
				@else
				<span id="adressing"></span>
				@endif
				,

				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-sm-12">
				@if ($invoice_last && $invoice_last->invoice_close)
				{{ $invoice_last->description }}
				@else
					<textarea name="description" id="description" rows="5" maxlength="500" class="form-control">{{ ($invoice ? ($invoice->description ? $invoice->description : Auth::user()->pref_invoice_description) : Auth::user()->pref_invoice_description) }}</textarea>
				@endif
				</div>
			</div>
			<br>
			<?#--DECRIPTION END--?>

			<?#--CONTENT START--?>
				<div class="show-totals">
					<h4>Specificatie termijnfactuur</h4>
					<hr>
					<table class="table table-striped hide-btw2">
						<tbody>
						<?php
						$count = Invoice::where('offer_id', $invoice->offer_id)->where('priority','<',$invoice->priority)->whereRaw('(amount > 0 OR amount IS NULL)')->count();
						?>
							<tr>
								<td class="col-md-7">&nbsp;</td>
								<td class="col-md-4"><strong>{{ $count+1 }}e van in totaal {{ Invoice::where('offer_id', $invoice->offer_id)->whereRaw('(amount > 0 OR amount IS NULL)')->count() }} betalingstermijnen</strong> @if (!$project->tax_reverse) <i>(Excl. BTW)</i> @endif</td>
								<td class="col-md-1"><strong>{{ '&euro; '.number_format($invoice->amount, 2, ",",".") }}</strong></td>

							</tr>
							@if (!$project->tax_reverse)
							<tr>
								<td class="col-md-7">&nbsp;</td>
								<td class="col-md-4"><i>Aandeel termijnfactuur in 21% BTW categorie</i></td>
								<td class="col-md-1"><i>{{ '&euro; '.number_format($invoice->rest_21, 2, ",",".") }}</i></td>
							</tr>
							<tr>
								<td class="col-md-7">&nbsp;</td>
								<td class="col-md-4"><i>Aandeel termijnfactuur in 6% BTW categorie</i></td>
								<td class="col-md-1"><i>{{ '&euro; '.number_format($invoice->rest_6, 2, ",",".") }}</i></td>
							</tr>
							@else
<!-- 							<tr>
								<td class="col-md-6">&nbsp;</td>
								<td class="col-md-4">&nbsp;<i>Aandeel termijnfactuur in 0% BTW categorie</i></td>
								<td class="col-md-2"><strong>{{ '&euro; '.number_format($invoice->rest_0, 2, ",",".") }}</strong></td>
							</tr> -->
							@endif

							@if (!$project->tax_reverse)
							<tr>
								<td class="col-md-7">&nbsp;</td>
								<td class="col-md-4">BTW bedrag 21%</td>
								<td class="col-md-1">{{ '&euro; '.number_format(($invoice->rest_21/100)*21, 2, ",",".") }}</td>
							</tr>
							<tr>
								<td class="col-md-7">&nbsp;</td>
								<td class="col-md-4">BTW bedrag 6%</td>
								<td class="col-md-1">{{ '&euro; '.number_format(($invoice->rest_6/100)*6, 2, ",",".") }}</td>
							</tr>
							@endif
							<tr>
								<td class="col-md-7">&nbsp;</td>
								<td class="col-md-4"><strong>Calculatief te betalen</strong> @if (!$project->tax_reverse) <i>(Incl. BTW)</i> @endif</td>
								<td class="col-md-1"><strong>{{ '&euro; '.number_format($invoice->amount+(($invoice->rest_21/100)*21)+(($invoice->rest_6/100)*6), 2, ",",".") }}</strong></td>
							</tr>
						</tbody>
					</table>
				</div>
				<?#--CONTENT, TOTAL END--?>

				<?#--CLOSER START--?>
				<textarea name="closure" id="closure" rows="5" class="form-control">{{ ($invoice ? ($invoice->closure ? $invoice->closure : Auth::user()->pref_invoice_closure) : Auth::user()->pref_invoice_closure) }}</textarea>
				<br>
				<div class="row">
					<div class="col-sm-12">

						@if($project->tax_reverse)
						<br>
						<h2>Deze factuur is <strong>BTW Verlegd</strong></h2>
						<br>
						@endif

					<h4>Bepalingen</h4>
					<ul>
						<li>Deze factuur dient betaald te worden binnen {{ $invoice->payment_condition }} dagen na dagtekening.</li>
						<li>Gaarne bij betaling factuurnummer vermelden.</li>
					</ul>
					<br>
					<p>Met vriendelijke groet,
						<br>
						@if ($invoice_last && $invoice_last->invoice_make)
						{{ Contact::find($invoice_last->from_contact_id)->firstname . ' ' . Contact::find($invoice_last->from_contact_id)->lastname }}
						@else
						<select name="from_contact" id="from_contact">
							@foreach (Contact::where('relation_id','=',$relation_self->id)->get() as $contact)
							<option {{ $invoice_last ? ($invoice_last->from_contact_id==$contact->id ? 'selected' : '') : '' }} value="{{ $contact->id }}">{{ $contact->firstname . ' ' . $contact->lastname }}</option>
							@endforeach
						</select>
						@endif
					</p>
					</div>
				</div>
				</div class="white-row">
				<?#--CLOSER END--?>

			<?#-- INVOICE FOOTER --?>
			<div class="row">

				<div class="col-sm-6"></div>

				<div class="col-sm-6 text-right">

					<div class="padding20">
						<!-- @if (!$invoice->invoice_close)
						<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Opties</a> -->
						<?php
						if (!$project->project_close) {
							$prev = Invoice::where('offer_id','=', $invoice->offer_id)->where('isclose','=',false)->where('priority','<',$invoice->priority)->orderBy('priority', 'desc')->first();
							$next = Invoice::where('offer_id','=', $invoice->offer_id)->where('isclose','=',false)->where('priority','>',$invoice->priority)->orderBy('priority')->first();
							$end = Invoice::where('offer_id','=', $invoice->offer_id)->where('isclose','=',true)->first();
							if ($prev && $prev->invoice_close && $next && !$next->invoice_close) {
								echo '<button class="btn btn-primary osave">Voorbeeld</button>&nbsp;';
							} else if (!$prev && $next && !$next->invoice_close) {
								echo '<button class="btn btn-primary osave">Voorbeeld</button>&nbsp;';
							} else if ($prev && $prev->invoice_close && $end && !$end->invoice_close) {
								echo '<button class="btn btn-primary osave">Voorbeeld</button>&nbsp;';
							}
						}
						?>
						@else
						<div class="btn-group">
						  <a target="blank" href="/invoice/pdf/project-{{ $project->id }}/term-invoice-{{ $invoice->id }}" class="btn btn-primary">PDF</a>
						  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    <span class="caret"></span>
						    <span class="sr-only">Toggle Dropdown</span>
						  </button>
						  <ul class="dropdown-menu">
						    <li><a href="/invoice/pdf/project-{{ $project->id }}/term-invoice-{{ $invoice->id }}/download?file={{ InvoiceController::getInvoiceCode($project->id).'-factuur.pdf' }}">Download</a></li>
						  </ul>
						</div>
						@endif
					</div>

				</div>

			</div>
		</div>

	</section>

</div>

@stop

<?php } ?>
