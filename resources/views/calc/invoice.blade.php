<?php

use \Calctool\Models\Project;
use \Calctool\Models\Chapter;
use \Calctool\Models\Activity;
use \Calctool\Models\Part;
use \Calctool\Models\PartType;
use \Calctool\Models\Relation;
use \Calctool\Models\Contact;
use \Calctool\Models\Offer;
use \Calctool\Models\Invoice;
use \Calctool\Models\Resource;
use \Calctool\Models\Iban;
use \Calctool\Models\ProjectType;
use \Calctool\Models\Detail;
use \Calctool\Calculus\EstimateEndresult;
use \Calctool\Calculus\MoreEndresult;
use \Calctool\Calculus\LessEndresult;
use \Calctool\Calculus\ResultEndresult;
use \Calctool\Calculus\CalculationOverview;
use \Calctool\Calculus\EstimateOverview;
use \Calctool\Calculus\MoreOverview;
use \Calctool\Calculus\LessOverview;
use \Calctool\Http\Controllers\OfferController;

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
		$("[name='toggle-tax']").bootstrapSwitch().on('switchChange.bootstrapSwitch', function(event, state) {
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
		$("[name='toggle-activity']").bootstrapSwitch().on('switchChange.bootstrapSwitch', function(event, state) {
		  if (state) {
		  	$('.show-activity').show();
		  	$("[name='toggle-summary']").bootstrapSwitch('toggleDisabled');
		  } else {
		 	$("[name='toggle-summary']").bootstrapSwitch('toggleDisabled');
			$('.show-activity').hide();
		  }
		});
		$("[name='toggle-subcontr']").bootstrapSwitch().on('switchChange.bootstrapSwitch', function(event, state) {
		  if (state) {
		  	$('.show-all').show();
		  	$('.show-totals').hide();
		  } else {
		  	$('.show-all').hide();
		  	$('.show-totals').show();
		  }
		});
		$("[name='toggle-note']").bootstrapSwitch().on('switchChange.bootstrapSwitch', function(event, state) {
		  if (state) {
		  	$('.show-note').show();
		  } else {
			$('.show-note').hide();
		  }
		});
		$("[name='toggle-endresult']").bootstrapSwitch().on('switchChange.bootstrapSwitch', function(event, state) {
		  if (state) {
		  	$('.only-total').hide();
		  	$('.hide-btw1').hide();
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
		  } else {
		  	$('.only-total').show();
			$('.hide-btw1').show();
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
		  }
		});
		$("[name='toggle-summary']").bootstrapSwitch().on('switchChange.bootstrapSwitch', function(event, state) {
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
		$("[name='toggle-payment']").bootstrapSwitch().on('switchChange.bootstrapSwitch', function(event, state) {
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
			$('#frm-invoice').submit();
		});
		$('#invdate').datepicker().on('changeDate', function(e){
			$('#invdate').datepicker('hide');
			$('#invdateval').val(e.date.toLocaleString());
			$('#invdate').text(e.date.getDate() + "-" + (e.date.getMonth() + 1)  + "-" + e.date.getFullYear());
		});
		@if ($invoice_last && $invoice_last->invoice_make)
		$('#invdate').text("{{ date('d-m-Y', strtotime($offer_last->invoice_make)) }}");
		@endif
	});
</script>
<div id="wrapper">

	<section class="container printable fix-footer-bottom">

		@include('calc.wizard', array('page' => 'invoice'))

		@if(Session::get('success'))
		<div class="alert alert-success">
			<i class="fa fa-check-circle"></i>
			<strong>Opgeslagen</strong>
		</div>
		@endif

		@if($errors->has())
		<div class="alert alert-danger">
			<i class="fa fa-frown-o"></i>
			<strong>Fout</strong>
			@foreach ($errors->all() as $error)
				{{ $error }}
			@endforeach
		</div>
		@endif

	<div class="pull-right">
		@if (!$invoice->invoice_close)
		<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Opties</a>
		<?php
		if (!$project->project_close) {
			$prev = Invoice::where('offer_id','=', $invoice->offer_id)->where('isclose','=',false)->orderBy('priority', 'desc')->first();
			if ($prev && $prev->invoice_close) {
				echo '<button class="btn btn-primary osave">Factureren</button>';
			} else if (!$prev) {
				echo '<button class="btn btn-primary osave">Factureren</button>';
			}
		}
		?>
		@else
		<div class="btn-group">
		  <a target="blank" href="/invoice/pdf/project-{{ $project->id }}/invoice-{{ $invoice->id }}" class="btn btn-primary">PDF</a>
		  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		    <span class="caret"></span>
		    <span class="sr-only">Toggle Dropdown</span>
		  </button>
		  <ul class="dropdown-menu">
		    <li><a href="/invoice/pdf/project-{{ $project->id }}/invoice-{{ $invoice->id }}/download?file={{ InvoiceController::getInvoiceCode($project->id).'-factuur.pdf' }}">Download</a></li>
		  </ul>
		</div>
		@endif
	</div>

	<h2><strong>Eindfactuur</strong></h2>
	<form method="POST" id="frm-invoice" action="/invoice/close">
	{!! csrf_field() !!}
	<input name="id" value="{{ $invoice->id }}" type="hidden"/>
	<input name="projectid" value="{{ $project->id }}" type="hidden"/>

	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Factuur opties</h4>
				</div>

				<div class="modal-body">
					<div class="form-horizontal">

						 <div class="form-group">
						    <div class="col-sm-offset-0 col-sm-12">
						      <div class="checkbox">
						        <label>
						          <input name="toggle-tax" type="checkbox" checked> BTW bedragen weergeven
						        </label>
						      </div>
						    </div>
						  </div>
						  <div class="form-group">
						    <div class="col-sm-offset-0 col-sm-12">
						      <div class="checkbox">
						        <label>
						          <input name="toggle-endresult" type="checkbox"> Alleen het totale offertebedrag weergeven<br>
						        </label>
						      </div>
						    </div>
						  </div>
						   <div class="form-group">
						    <div class="col-sm-offset-0 col-sm-12">
						      <div class="checkbox">
						        <label>
						          <input name="toggle-subcontr" type="checkbox"> Kosten onderaanneming apart weergeven
						        </label>
						      </div>
						    </div>
						  </div>
						  <br>
						  <strong>De volgende opties worden als bijlage bijgesloten bij de faxctuur</strong>
						  <div class="form-group">
						    <div class="col-sm-offset-0 col-sm-12">
						      <div class="checkbox">
						        <label>
						          <input name="toggle-activity" type="checkbox" checked> Hoofdstukken en werkzaamheden weergeven
						        </label>
						      </div>
						    </div>
						  </div>
						  <div class="form-group">
						    <div class="col-sm-offset-0 col-sm-12">
						      <div class="checkbox">
						        <label>
						          <input name="toggle-summary" type="checkbox"> Kosten werkzaamheden weergeven<br>
						        </label>
						      </div>
						    </div>
						  </div>
						  <div class="form-group">
						    <div class="col-sm-offset-0 col-sm-12">
						      <div class="checkbox">
						        <label>
						          <input name="toggle-note" type="checkbox" checked> Omschrijving werkzaamheden weergeven
						        </label>
						      </div>
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
		<!--PAGE HEADER MASTER START-->
		<header>
			<div class="row">
				<div class="col-sm-6">
					{!! ($relation_self && $relation_self->logo_id) ? "<img src=\"/".Resource::find($relation_self->logo_id)->file_location."\" class=\"img-responsive\" />" : '' !!}
				</div>
				<div class="col-sm-6 text-right">
					<p>
						<h4><strong>{{ $relation_self->company_name }}</strong></h4>
		    			<ul class="list-unstyled">
	 						<li>{{ $relation_self->address_street . ' ' . $relation_self->address_number }}</li>
	  						<li>{{ $relation_self->address_postal . ', ' . $relation_self->address_city }}</li>
	 						<li><i class="fa fa-phone"></i>&nbsp;{{ $relation_self->phone }}&nbsp;|&nbsp;<i class="fa fa-envelope-o"></i>&nbsp;{{ $relation_self->email }}</li>
	 						<li>KVK:{{ $relation_self->kvk }}&nbsp;|&nbsp;BTW: {{ $relation_self->btw }}</li>
							<li>Rekeningnummer: {{ Iban::where('relation_id','=',$relation_self->id)->first()['iban'] }}&nbsp;|&nbsp;tnv.: {{ Iban::where('relation_id','=',$relation_self->id)->first()['iban_name'] }}</li>
						<ul class="list-unstyled">
					</p>
				</div>
			</div>
		</header>
		<hr class="margin-top10 margin-bottom10">
		<!--PAGE HEADER MASTER END-->

 		<!--ADRESSING START-->
 		<main>
		<div class="row">
			<div class="col-sm-6">
				<ul class="list-unstyled">
					<li>{{ $relation->company_name }}</li>
					<li>T.a.v.
						{{ Contact::find($offer_last->to_contact_id)->firstname . ' ' . Contact::find($offer_last->to_contact_id)->lastname }}
					</li>
					<li>{{ $relation->address_street . ' ' . $relation->address_number }}<br /> {{ $relation->address_postal . ', ' . $relation->address_city }}</li>
				</ul>
			</div>
			<div class="col-sm-2"></div>
			<div class="col-sm-4 text-right">
				<h4><strong>FACTUUR</strong></h4>
				<ul class="list-unstyled">
					<li><strong>Projectnaam:</strong>{{ $project->project_name }}</li>
					<li><strong>Factuurdatum:</strong> <a href="#" id="invdate">Bewerk</a> {{-- date("j M Y") --}}</li>
					<li><strong>Factuurnummer:</strong> {{ $invoice->invoice_code }}</li>
					<li><strong>Administratiefnummer:</strong> {{ $invoice->book_code }}</li>
					<li><strong>Uw referentie:</strong> {{ $invoice->reference }}</li>
					<input type="hidden" id="invdateval" name="invdateval" />
				</ul>
			</div>
		</div>
		<!--ADRESSING END-->

		<!--DECRIPTION-->
		<div class="row">
			<div class="col-sm-6">
			Geachte
				{{ Contact::find($invoice_last->to_contact_id)->firstname . ' ' . Contact::find($invoice_last->to_contact_id)->lastname }}
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
		<!--DECRIPTION END-->

		<!--CONTENT, CON & SUBCON START-->
		<div class="show-all" style="display:none;">
			<h4 class="only-total">Specificatie factuur</h4>
			<h5 class="only-total">AANNEMING</h5>
			<table class="table table-striped hide-btw1">
				<thead>
					<tr>
						<th class="col-md-5">&nbsp;</th>
						<th class="col-md-1">Calculatie</th>
						<th class="col-md-1">Meerwerk</th>
						<th class="col-md-1">Minderwerk</th>
						<th class="col-md-1">Balans</th>
						<th class="col-md-1">BTW</th>
						<th class="col-md-2">BTW bedrag</th>
					</tr>
				</thead>
				<tbody>
					@if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
					<tr>
						<td class="col-md-5">Arbeidskosten</td>
						<td class="col-md-1">{{ '&euro; '.number_format(EstimateEndresult::conCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax1($project), 2, ",",".") }}</td>
						<td class="col-md-1">21%</td>
						<td class="col-md-2">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax1AmountTax($project), 2, ",",".") }}</td>
					</tr>
					<tr>
						<td class="col-md-5">&nbsp;</td>
						<td class="col-md-1">{{ '&euro; '.number_format(EstimateEndresult::conCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax2($project), 2, ",",".") }}</td>
						<td class="col-md-1">6%</td>
						<td class="col-md-2">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax2AmountTax($project), 2, ",",".") }}</td>
					</tr>
					@else
					<tr>
						<td class="col-md-5">Arbeidskosten</td>
						<td class="col-md-1">{{ '&euro; '.number_format(EstimateEndresult::conCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax3($project), 2, ",",".") }}</td>
						<td class="col-md-1">0%</td>
						<td class="col-md-2">&nbsp;</td>
					</tr>
					@endif

					@if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
					<tr>
						<td class="col-md-5">Materiaalkosten</td>
						<td class="col-md-1">{{ '&euro; '.number_format(EstimateEndresult::conCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax1($project), 2, ",",".") }}</td>
						<td class="col-md-1">21%</td>
						<td class="col-md-2">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax1AmountTax($project), 2, ",",".") }}</td>
					</tr>
					<tr>
						<td class="col-md-5">&nbsp;</td>
						<td class="col-md-1">{{ '&euro; '.number_format(EstimateEndresult::conCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax2($project), 2, ",",".") }}</td>
						<td class="col-md-1">6%</td>
						<td class="col-md-2">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax2AmountTax($project), 2, ",",".") }}</td>
					</tr>
					@else
					<tr>
						<td class="col-md-5">Materiaalkosten</td>
						<td class="col-md-1">{{ '&euro; '.number_format(EstimateEndresult::conCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax3($project), 2, ",",".") }}</td>
						<td class="col-md-1">0%</td>
						<td class="col-md-2">&nbsp;</td>
					</tr>
					@endif

					@if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
					<tr>
						<td class="col-md-5">Materieelkosten</td>
						<td class="col-md-1">{{ '&euro; '.number_format(EstimateEndresult::conCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax1($project), 2, ",",".") }}</td>
						<td class="col-md-1">21%</td>
						<td class="col-md-2">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax1AmountTax($project), 2, ",",".") }}</td>
					</tr>
					<tr>
						<td class="col-md-5">&nbsp;</td>
						<td class="col-md-1">{{ '&euro; '.number_format(EstimateEndresult::conCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax2($project), 2, ",",".") }}</td>
						<td class="col-md-1">6%</td>
						<td class="col-md-2">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax2AmountTax($project), 2, ",",".") }}</td>
					</tr>
					@else
					<tr>
						<td class="col-md-5">Materieelkosten</td>
						<td class="col-md-1">{{ '&euro; '.number_format(EstimateEndresult::conCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax3($project), 2, ",",".") }}</td>
						<td class="col-md-1">0%</td>
						<td class="col-md-2">&nbsp;</td>
					</tr>
					@endif

					<tr>
						<td class="col-md-5"><strong>Totaal Aanneming </strong></td>
						<td class="col-md-1"><strong>{{ '&euro; '.number_format(EstimateEndresult::totalContracting($project), 2, ",",".") }}</strong></td>
						<td class="col-md-1"><strong>{{ '&euro; '.number_format(MoreEndresult::totalContracting($project), 2, ",",".") }}</strong></td>
						<td class="col-md-1"><strong>{{ '&euro; '.number_format(LessEndresult::totalContracting($project), 2, ",",".") }}</strong></td>
						<td class="col-md-1"><strong>{{ '&euro; '.number_format(ResultEndresult::totalContracting($project), 2, ",",".") }}</strong></td>
						<td class="col-md-1">&nbsp;</td>
						<td class="col-md-2"><strong>{{ '&euro; '.number_format(ResultEndresult::totalContractingTax($project), 2, ",",".") }}</strong></td>
					</tr>
				</tbody>
			</table>

			<h5 class="only-total">ONDERAANNEMING</h5>
			<table class="table table-striped hide-btw1">
				<thead>
					<tr>
						<th class="col-md-5">&nbsp;</th>
						<th class="col-md-1">Calculatie</th>
						<th class="col-md-1">Meerwerk</th>
						<th class="col-md-1">Minderwerk</th>
						<th class="col-md-1">Balans</th>
						<th class="col-md-1">BTW</th>
						<th class="col-md-2">BTW bedrag</th>
					</tr>
				</thead>
				<tbody>
					@if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
					<tr>
						<td class="col-md-5">Arbeidskosten</td>
						<td class="col-md-1">{{ '&euro; '.number_format(EstimateEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconLaborBalanceTax1($project), 2, ",",".") }}</td>
						<td class="col-md-1">21%</td>
						<td class="col-md-2">{{ '&euro; '.number_format(ResultEndresult::subconLaborBalanceTax1AmountTax($project), 2, ",",".") }}</td>
					</tr>
					<tr>
						<td class="col-md-5">&nbsp;</td>
						<td class="col-md-1">{{ '&euro; '.number_format(EstimateEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconLaborBalanceTax2($project), 2, ",",".") }}</td>
						<td class="col-md-1">6%</td>
						<td class="col-md-2">{{ '&euro; '.number_format(ResultEndresult::subconLaborBalanceTax2AmountTax($project), 2, ",",".") }}</td>
					</tr>
					@else
					<tr>
						<td class="col-md-5">Arbeidskosten</td>
						<td class="col-md-1">{{ '&euro; '.number_format(EstimateEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconLaborBalanceTax3($project), 2, ",",".") }}</td>
						<td class="col-md-1">0%</td>
						<td class="col-md-2">&nbsp;</td>
					</tr>
					@endif

					@if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
					<tr>
						<td class="col-md-5">Materiaalkosten</td>
						<td class="col-md-1">{{ '&euro; '.number_format(EstimateEndresult::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconMaterialBalanceTax1($project), 2, ",",".") }}</td>
						<td class="col-md-1">21%</td>
						<td class="col-md-2">{{ '&euro; '.number_format(ResultEndresult::subconMaterialBalanceTax1AmountTax($project), 2, ",",".") }}</td>
					</tr>
					<tr>
						<td class="col-md-5">&nbsp;</td>
						<td class="col-md-1">{{ '&euro; '.number_format(EstimateEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconMaterialBalanceTax2($project), 2, ",",".") }}</td>
						<td class="col-md-1">6%</td>
						<td class="col-md-2">{{ '&euro; '.number_format(ResultEndresult::subconMaterialBalanceTax2AmountTax($project), 2, ",",".") }}</td>
					</tr>
					@else
					<tr>
						<td class="col-md-5">Materiaalkosten</td>
						<td class="col-md-1">{{ '&euro; '.number_format(EstimateEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconMaterialBalanceTax3($project), 2, ",",".") }}</td>
						<td class="col-md-1">0%</td>
						<td class="col-md-2">&nbsp;</td>
					</tr>
					@endif

					@if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
					<tr>
						<td class="col-md-5">Materieelkosten</td>
						<td class="col-md-1">{{ '&euro; '.number_format(EstimateEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconEquipmentBalanceTax1($project), 2, ",",".") }}</td>
						<td class="col-md-1">21%</td>
						<td class="col-md-2">{{ '&euro; '.number_format(ResultEndresult::subconEquipmentBalanceTax1AmountTax($project), 2, ",",".") }}</td>
					</tr>
					<tr>
						<td class="col-md-5">&nbsp;</td>
						<td class="col-md-1">{{ '&euro; '.number_format(EstimateEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconEquipmentBalanceTax2($project), 2, ",",".") }}</td>
						<td class="col-md-1">6%</td>
						<td class="col-md-2">{{ '&euro; '.number_format(ResultEndresult::subconEquipmentBalanceTax2AmountTax($project), 2, ",",".") }}</td>
					</tr>
					@else
					<tr>
						<td class="col-md-5">Materieelkosten</td>
						<td class="col-md-1">{{ '&euro; '.number_format(EstimateEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconEquipmentBalanceTax3($project), 2, ",",".") }}</td>
						<td class="col-md-1">0%</td>
						<td class="col-md-2">&nbsp;</td>
					</tr>
					@endif

					<tr>
						<td class="col-md-5"><strong>Totaal Onderaanneming </strong></td>
						<td class="col-md-1"><strong>{{ '&euro; '.number_format(EstimateEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
						<td class="col-md-1"><strong>{{ '&euro; '.number_format(MoreEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
						<td class="col-md-1"><strong>{{ '&euro; '.number_format(LessEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
						<td class="col-md-1"><strong>{{ '&euro; '.number_format(ResultEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
						<td class="col-md-1">&nbsp;</td>
						<td class="col-md-2"><strong>{{ '&euro; '.number_format(ResultEndresult::totalSubcontractingTax($project), 2, ",",".") }}</strong></td>
					</tr>
				</tbody>
			</table>

			<h4>Totalen Factuur</h4>
			<table class="table table-striped hide-btw2">
				<thead>
					<tr>
						<th class="col-md-6">&nbsp;</th>
						<th class="col-md-2">Bedrag (excl. BTW)</th>
						<th class="col-md-2">BTW bedrag</th>
						<th class="col-md-2">&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="col-md-6">Calculatief te factureren (excl. BTW)</td>
						<td class="col-md-2">{{ '&euro; '.number_format(ResultEndresult::totalProject($project), 2, ",",".") }}</td>
						<td class="col-md-2">&nbsp;</td>
						<td class="col-md-2">&nbsp;</td>
					</tr>
					@if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
					<tr>
						<td class="col-md-6">BTW bedrag aanneming belast met 21%</td>
						<td class="col-md-2">&nbsp;</td>
						<td class="col-md-2">{{ '&euro; '.number_format(ResultEndresult::totalContractingTax1($project), 2, ",",".") }}</td>
						<td class="col-md-2">&nbsp;</td>
					</tr>
					<tr>
						<td class="col-md-6">BTW bedrag aanneming belast met 6%</td>
						<td class="col-md-2">&nbsp;</td>
						<td class="col-md-2">{{ '&euro; '.number_format(ResultEndresult::totalContractingTax2($project), 2, ",",".") }}</td>
						<td class="col-md-2">&nbsp;</td>
					</tr>
					<tr>
						<td class="col-md-6">BTW bedrag onderaanneming belast met 21%</td>
						<td class="col-md-2">&nbsp;</td>
						<td class="col-md-2">{{ '&euro; '.number_format(ResultEndresult::totalSubcontractingTax1($project), 2, ",",".") }}</td>
						<td class="col-md-2">&nbsp;</td>
					</tr>
					<tr>
						<td class="col-md-6">BTW bedrag onderaanneming belast met 6%</td>
						<td class="col-md-2">&nbsp;</td>
						<td class="col-md-2">{{ '&euro; '.number_format(ResultEndresult::totalSubcontractingTax2($project), 2, ",",".") }}</td>
						<td class="col-md-2">&nbsp;</td>
					</tr>
					@endif
					<tr>
						<td class="col-md-6">Te factureren BTW bedrag</td>
						<td class="col-md-2">&nbsp;</td>
						<td class="col-md-2">&nbsp;</td>
						<td class="col-md-2">{{ '&euro; '.number_format(ResultEndresult::totalProjectTax($project), 2, ",",".") }}</td>
					</tr>
					<tr>
						<td class="col-md-6"><strong>Calculatief te factureren (Incl. BTW)</strong></td>
						<td class="col-md-2">&nbsp;</td>
						<td class="col-md-2">&nbsp;</td>
						<td class="col-md-2"><strong>{{ '&euro; '.number_format(ResultEndresult::superTotalProject($project), 2, ",",".") }}</strong></td>
					</tr>
				</tbody>
			</table>
		</div>
		<!--CONTENT, CON & SUBCON END-->

		<!--CONTENT, TOTAL START-->
		<div class="show-totals">
			<h4 class="only-total">Specificatie factuur</h4>
			<table class="table table-striped hide-btw1">
				<thead>
					<tr>
						<th class="col-md-5">&nbsp;</th>
						<th class="col-md-1">Calculatie</th>
						<th class="col-md-1">Meerwerk</th>
						<th class="col-md-1">Minderwerk</th>
						<th class="col-md-1">Balans</th>
						<th class="col-md-1">BTW</th>
						<th class="col-md-2">BTW bedrag</th>
					</tr>
				</thead>
				<tbody>
					@if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
					<tr>
						<td class="col-md-5">Arbeidskosten</td>
						<td class="col-md-1">{{ '&euro; '.number_format(EstimateEndresult::conCalcLaborActivityTax1Amount($project)+EstimateEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcLaborActivityTax1Amount($project)+MoreEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax1Amount($project)+LessEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax1($project)+ResultEndresult::subconLaborBalanceTax1($project), 2, ",",".") }}</td>
						<td class="col-md-1">21%</td>
						<td class="col-md-2">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax1AmountTax($project)+ResultEndresult::subconLaborBalanceTax1AmountTax($project), 2, ",",".") }}</td>
					</tr>
					<tr>
						<td class="col-md-5">&nbsp;</td>
						<td class="col-md-1">{{ '&euro; '.number_format(EstimateEndresult::conCalcLaborActivityTax2Amount($project)+EstimateEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcLaborActivityTax2Amount($project)+MoreEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax2Amount($project)+LessEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax2($project)+ResultEndresult::subconLaborBalanceTax2($project), 2, ",",".") }}</td>
						<td class="col-md-1">6%</td>
						<td class="col-md-2">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax2AmountTax($project)+ResultEndresult::subconLaborBalanceTax2AmountTax($project), 2, ",",".") }}</td>
					</tr>
					@else
					<tr>
						<td class="col-md-5">Arbeidskosten</td>
						<td class="col-md-1">{{ '&euro; '.number_format(EstimateEndresult::conCalcLaborActivityTax3Amount($project)+EstimateEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcLaborActivityTax3Amount($project)+MoreEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax3Amount($project)+LessEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax3($project)+ResultEndresult::subconLaborBalanceTax3($project), 2, ",",".") }}</td>
						<td class="col-md-1">0%</td>
						<td class="col-md-2">&nbsp;</td>
					</tr>
					@endif

					@if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
					<tr>
						<td class="col-md-5">Materiaalkosten</td>
						<td class="col-md-1">{{ '&euro; '.number_format(EstimateEndresult::conCalcMaterialActivityTax1Amount($project)+EstimateEndresult::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcMaterialActivityTax1Amount($project)+MoreEndresult::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax1Amount($project)+LessEndresult::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax1($project)+ResultEndresult::subconMaterialBalanceTax1($project), 2, ",",".") }}</td>
						<td class="col-md-1">21%</td>
						<td class="col-md-2">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax1AmountTax($project)+ResultEndresult::subconMaterialBalanceTax1AmountTax($project), 2, ",",".") }}</td>
					</tr>
					<tr>
						<td class="col-md-5">&nbsp;</td>
						<td class="col-md-1">{{ '&euro; '.number_format(EstimateEndresult::conCalcMaterialActivityTax2Amount($project)+EstimateEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcMaterialActivityTax2Amount($project)+MoreEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax2Amount($project)+LessEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax2($project)+ResultEndresult::subconMaterialBalanceTax2($project), 2, ",",".") }}</td>
						<td class="col-md-1">6%</td>
						<td class="col-md-2">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax2AmountTax($project)+ResultEndresult::subconMaterialBalanceTax2AmountTax($project), 2, ",",".") }}</td>
					</tr>
					@else
					<tr>
						<td class="col-md-5">Materiaalkosten</td>
						<td class="col-md-1">{{ '&euro; '.number_format(EstimateEndresult::conCalcMaterialActivityTax3Amount($project)+EstimateEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcMaterialActivityTax3Amount($project)+MoreEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax3Amount($project)+LessEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax3($project)+ResultEndresult::subconMaterialBalanceTax3($project), 2, ",",".") }}</td>
						<td class="col-md-1">0%</td>
						<td class="col-md-2">&nbsp;</td>
					</tr>
					@endif

					@if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
					<tr>
						<td class="col-md-5">Materieelkosten</td>
						<td class="col-md-1">{{ '&euro; '.number_format(EstimateEndresult::conCalcEquipmentActivityTax1Amount($project)+EstimateEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcEquipmentActivityTax1Amount($project)+MoreEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax1Amount($project)+LessEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax1($project)+ResultEndresult::subconEquipmentBalanceTax1($project), 2, ",",".") }}</td>
						<td class="col-md-1">21%</td>
						<td class="col-md-2">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax1AmountTax($project)+ResultEndresult::subconEquipmentBalanceTax1AmountTax($project), 2, ",",".") }}</td>
					</tr>
					<tr>
						<td class="col-md-5">&nbsp;</td>
						<td class="col-md-1">{{ '&euro; '.number_format(EstimateEndresult::conCalcEquipmentActivityTax2Amount($project)+EstimateEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcEquipmentActivityTax2Amount($project)+MoreEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax2Amount($project)+LessEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax2($project)+ResultEndresult::subconEquipmentBalanceTax2($project), 2, ",",".") }}</td>
						<td class="col-md-1">6%</td>
						<td class="col-md-2">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax2AmountTax($project)+ResultEndresult::subconEquipmentBalanceTax2AmountTax($project), 2, ",",".") }}</td>
					</tr>
					@else
					<tr>
						<td class="col-md-5">Materieelkosten</td>
						<td class="col-md-1">{{ '&euro; '.number_format(EstimateEndresult::conCalcEquipmentActivityTax3Amount($project)+EstimateEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcEquipmentActivityTax3Amount($project)+MoreEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax3Amount($project)+LessEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax3($project)+ResultEndresult::subconEquipmentBalanceTax3($project), 2, ",",".") }}</td>
						<td class="col-md-1">0%</td>
						<td class="col-md-2">&nbsp;</td>
					</tr>
					@endif

					<tr>
						<td class="col-md-5"><strong>Totaal Aanneming </strong></td>
						<td class="col-md-1"><strong>{{ '&euro; '.number_format(EstimateEndresult::totalContracting($project)+EstimateEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
						<td class="col-md-1"><strong>{{ '&euro; '.number_format(MoreEndresult::totalContracting($project)+MoreEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
						<td class="col-md-1"><strong>{{ '&euro; '.number_format(LessEndresult::totalContracting($project)+LessEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
						<td class="col-md-1"><strong>{{ '&euro; '.number_format(ResultEndresult::totalContracting($project)+ResultEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
						<td class="col-md-1">&nbsp;</td>
						<td class="col-md-2"><strong>{{ '&euro; '.number_format(ResultEndresult::totalContractingTax($project)+ResultEndresult::totalSubcontractingTax($project), 2, ",",".") }}</strong></td>
					</tr>
				</tbody>
			</table>
			<h4>Totalen Factuur</h4>
			<table class="table table-striped hide-btw2">
				<thead>
					<tr>
						<th class="col-md-6">&nbsp;</th>
						<th class="col-md-2">Bedrag (excl. BTW)</th>
						<th class="col-md-2">BTW bedrag</th>
						<th class="col-md-2">Bedrag (incl. BTW);</th>
					</tr>
				</thead>

				<tbody>
					<tr>
						<td class="col-md-6">Calculatief te factureren (excl. BTW)</td>
						<td class="col-md-2"><strong>{{ '&euro; '.number_format(ResultEndresult::totalProject($project), 2, ",",".") }}</strong></td>
						<td class="col-md-2">&nbsp;</td>
						<td class="col-md-2">&nbsp;</td>
					</tr>
					@if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
					<tr>
						<td class="col-md-6">BTW bedrag 21%</td>
						<td class="col-md-2">&nbsp;</td>
						<td class="col-md-2">{{ '&euro; '.number_format(ResultEndresult::totalContractingTax1($project)+ResultEndresult::totalSubcontractingTax1($project), 2, ",",".") }}</td>
						<td class="col-md-2">&nbsp;</td>
					</tr>
					<tr>
						<td class="col-md-6">BTW bedrag 6%</td>
						<td class="col-md-2">&nbsp;</td>
						<td class="col-md-2">{{ '&euro; '.number_format(ResultEndresult::totalContractingTax2($project)+ResultEndresult::totalSubcontractingTax2($project), 2, ",",".") }}</td>
						<td class="col-md-2">&nbsp;</td>
					</tr>
					@endif
					<tr>
						<td class="col-md-6">Te factureren BTW bedrag</td>
						<td class="col-md-2">&nbsp;</td>
						<td class="col-md-2"><strong>{{ '&euro; '.number_format(ResultEndresult::totalProjectTax($project), 2, ",",".") }}</strong></td>
						<td class="col-md-2">&nbsp;</td>
					</tr>
					<tr>
						<td class="col-md-6"><strong>Calculatief te factureren (Incl. BTW)</strong></td>
						<td class="col-md-2">&nbsp;</td>
						<td class="col-md-2">&nbsp;</td>
						<td class="col-md-2"><strong>{{ '&euro; '.number_format(ResultEndresult::superTotalProject($project), 2, ",",".") }}</strong></td>
					</tr>
				</tbody>
			</table>
		</div>
		<?php
		$cnt = Invoice::where('offer_id','=', $invoice->offer_id)->count();
		if ($cnt>1) {
		?>
		<h4>Reeds betaald</h4>
		<table class="table table-striped hide-btw2">
			<thead>
				<tr>
					<th class="col-md-6">&nbsp;</th>
					<th class="col-md-2">Bedrag (excl. BTW)</th>
					<th class="col-md-2">BTW bedrag</th>
					<th class="col-md-2">Bedrag (incl. BTW);</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="col-md-6">Voorgaande termijn(en) (excl. BTW)</td>
					<td class="col-md-2"><strong>{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('amount'), 2, ",",".") }}</strong></td>
					<td class="col-md-2">&nbsp;</td>
					<td class="col-md-2">&nbsp;</td>
				</tr>
				@if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
				<tr>
					<td class="col-md-6">&nbsp;<i>Aandeel termijnfactuur in 21% BTW categorie</i></td>
					<td class="col-md-2">{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('rest_21'), 2, ",",".") }}</td>
					<td class="col-md-2">&nbsp;</td>
					<td class="col-md-2">&nbsp;</td>
				</tr>
				<tr>
					<td class="col-md-6">&nbsp;<i>Aandeel termijnfactuur in 6% BTW categorie</i></td>
					<td class="col-md-2">{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('rest_6'), 2, ",",".") }}</td>
					<td class="col-md-2">&nbsp;</td>
					<td class="col-md-2">&nbsp;</td>
				</tr>
				@else
				<tr>
					<td class="col-md-6">&nbsp;<i>Aandeel termijnfactuur in 0% BTW categorie</i></td>
					<td class="col-md-2">{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('rest_0'), 2, ",",".") }}</td>
					<td class="col-md-2">&nbsp;</td>
					<td class="col-md-2">&nbsp;</td>
				</tr>
				@endif

				@if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
				<tr>
					<td class="col-md-6">BTW bedrag 21%</td>
					<td class="col-md-2">&nbsp;</td>
					<td class="col-md-2">{{ '&euro; '.number_format((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('rest_21')/100)*21, 2, ",",".") }}</td>
					<td class="col-md-2">&nbsp;</td>
				</tr>
				<tr>
					<td class="col-md-6">BTW bedrag 6%</td>
					<td class="col-md-2">&nbsp;</td>
					<td class="col-md-2">{{ '&euro; '.number_format((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('rest_6')/100)*6, 2, ",",".") }}</td>
					<td class="col-md-2">&nbsp;</td>
				</tr>
				@endif

				<tr>
					<td class="col-md-6">Te factureren BTW bedrag</td>
					<td class="col-md-2">&nbsp;</td>
					<td class="col-md-2"><strong>{{ '&euro; '.number_format((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('rest_21')/100)*21+(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('rest_6')/100)*6, 2, ",",".") }}</strong></td>
					<td class="col-md-2">&nbsp;</td>
				</tr>
				<tr>
					<td class="col-md-6"><strong>Calculatief te factureren (Incl. BTW)</strong></td>
					<td class="col-md-2">&nbsp;</td>
					<td class="col-md-2">&nbsp;</td>
					<td class="col-md-2"><strong>{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('amount')+((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('rest_21')/100)*21)+((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('rest_6')/100)*6), 2, ",",".") }}</strong></td>
				</tr>
			</tbody>
		</table>

		<h4>Resterend te betalen</h4>
		<table class="table table-striped hide-btw2">
			<thead>
				<tr>
					<th class="col-md-6">&nbsp;</th>
					<th class="col-md-2">Bedrag (excl. BTW)</th>
					<th class="col-md-2">BTW bedrag</th>
					<th class="col-md-2">Bedrag (incl. BTW);</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="col-md-6">Laatste van in totaal {{Invoice::where('offer_id','=', $invoice->offer_id)->count()}} termijnen</td>
					<td class="col-md-2">{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->amount, 2, ",",".") }}</td>
					<td class="col-md-2">&nbsp;</td>
					<td class="col-md-2">&nbsp;</td>
				</tr>
				@if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
				<tr>
					<td class="col-md-6">&nbsp;<i>Aandeel termijnfactuur in 21% BTW categorie</i></td>
					<td class="col-md-2">{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->rest_21, 2, ",",".") }}</td>
					<td class="col-md-2">&nbsp;</td>
					<td class="col-md-2">&nbsp;</td>
				</tr>
				<tr>
					<td class="col-md-6">&nbsp;<i>Aandeel termijnfactuur in 6% BTW categorie</i></td>
					<td class="col-md-2">{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->rest_6, 2, ",",".") }}</td>
					<td class="col-md-2">&nbsp;</td>
					<td class="col-md-2">&nbsp;</td>
				</tr>
				@else
				<tr>
					<td class="col-md-6">&nbsp;<i>Aandeel termijnfactuur in 0% BTW categorie</i></td>
					<td class="col-md-2">{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->rest_0, 2, ",",".") }}</td>
					<td class="col-md-2">&nbsp;</td>
					<td class="col-md-2">&nbsp;</td>
				</tr>
				@endif

				@if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
				<tr>
					<td class="col-md-6">BTW bedrag 21%</td>
					<td class="col-md-2">&nbsp;</td>
					<td class="col-md-2">{{ '&euro; '.number_format((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->rest_21/100)*21, 2, ",",".") }}</td>
					<td class="col-md-2">&nbsp;</td>
				</tr>
				<tr>
					<td class="col-md-6">BTW bedrag 6%</td>
					<td class="col-md-2">&nbsp;</td>
					<td class="col-md-2">{{ '&euro; '.number_format((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->rest_6/100)*6, 2, ",",".") }}</td>
					<td class="col-md-2">&nbsp;</td>
				</tr>
				@endif

				<tr>
					<td class="col-md-6">Te factureren BTW bedrag</td>
					<td class="col-md-2">&nbsp;</td>
					<td class="col-md-2"><strong>{{ '&euro; '.number_format((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->rest_21/100)*21+(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->rest_6/100)*6, 2, ",",".") }}</strong></td>
					<td class="col-md-2">&nbsp;</td>
				</tr>
				<tr>
					<td class="col-md-6"><strong>Calculatief te factureren (Incl. BTW)</strong></td>
					<td class="col-md-2">&nbsp;</td>
					<td class="col-md-2">&nbsp;</td>
					<td class="col-md-2"><strong>{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->amount+((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->rest_21/100)*21)+((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->rest_6/100)*6), 2, ",",".") }}</strong></td>
				</tr>
			</tbody>
		</table>
		<?php } ?>
		<!--CONTENT, TOTAL END-->

		<!--CLOSER START-->
		<textarea name="closure" id="closure" rows="5" class="form-control">{{ ($invoice ? ($invoice->closure ? $invoice->closure : Auth::user()->pref_invoice_closure) : Auth::user()->pref_invoice_closure) }}</textarea>
		<br>
		<div class="row">
			<div class="col-sm-12">
			<h4>Bepalingen</h4>
			<ul>
				<li>Deze factuur dient betaald te worden binnen {{ $invoice->payment_condition }} dagen na dagtekening.</li>
			</ul>
			<br>
			<span>Met vriendelijke groet,
			<br>
				{{ Contact::find($offer_last->from_contact_id)->firstname . ' ' . Contact::find($offer_last->from_contact_id)->lastname }}
			</span>
			</div>
		</div>
		<!--CLOSER END-->
	</div class="white-row">

	<div class="white-row show-activity">
		<!--PAGE HEADER START-->
		<div class="row">
			<div class="col-sm-6">
				{!! ($relation_self && $relation_self->logo_id) ? "<img src=\"/".Resource::find($relation_self->logo_id)->file_location."\" class=\"img-responsive\" />" : '' !!}
			</div>
			<div class="col-sm-6 text-right">
				<p>
					<h4><strong>{{ $project->project_name }}</strong></h4>
					<ul class="list-unstyled">
						<li><strong>Factuurdatum:</strong> {{ date("j M Y") }}</li>
						<li><strong>Factuurnummer:</strong> {{ $invoice->invoice_code }}</li>
						<li><strong>Administratiefnummer:</strong> {{ $invoice->book_code }}</li>
						<li><strong>Uw referentie:</strong> {{ $invoice->reference }}</li>
					</ul>
				</p>
			</div>
		</div>
		<hr class="margin-top10 margin-bottom10">
		<!--PAGE HEADER END-->

		<!-- DECRIPTION CON&SUBCON START -->
		<div class="show-all" style="display:none;">
			<h4>Calculatie Aanneming</h4>
			<table class="table table-striped only-end-total">
				<thead>
					<tr>
						<th class="col-md-2">Hoofdstuk</th>
						<th class="col-md-3">Werkzaamheden</th>
						<th class="col-md-1"><span class="pull-right">Arbeidsuren</th>
						<th class="col-md-1"><span class="pull-right">Arbeid</th>
						<th class="col-md-1"><span class="pull-right">Materiaal</th>
						<th class="col-md-1"><span class="pull-right">Materieel</th>
						<th class="col-md-1"><span class="pull-right">Totaal</th>
					</tr>
				</thead>
				<tbody>
					@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
					<?php $i = 0; ?>
					@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->where('part_type_id','=',PartType::where('type_name','=','calculation')->first()->id)->whereNull('detail_id')->get() as $activity)
					<?php $i++; ?>
					<tr>
						<td class="col-md-2">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
						<td class="col-md-3">{{ $activity->activity_name }}</td>
						<td class="col-md-1"><span class="pull-right">{{ number_format(CalculationOverview::laborTotal($activity), 2, ",",".") }}</td>
						<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::laborActivity($project->hour_rate, $activity), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::materialActivityProfit($activity, $project->profit_calc_contr_mat), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::equipmentActivityProfit($activity, $project->profit_calc_contr_equip), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::activityTotalProfit($project->hour_rate, $activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip), 2, ",",".") }} </td>
					</tr>
					@endforeach
					@endforeach
					<tr>
						<th class="col-md-3"><strong>Totaal aanneming</strong></th>
						<th class="col-md-2">&nbsp;</th>
						<td class="col-md-1"><strong><span class="pull-right">{{ CalculationOverview::contrLaborTotalAmount($project) }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::contrLaborTotal($project), 2, ",",".") }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::contrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::contrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::contrTotal($project), 2, ",",".") }}</span></strong></td>
					</tr>
				</tbody>
			</table>

			<h4>Calculatie Onderaanneming</h4>
			<table class="table table-striped only-end-total">
				<thead>
					<tr>
						<th class="col-md-2">Hoofdstuk</th>
						<th class="col-md-3">Werkzaamheden</th>
						<th class="col-md-1"><span class="pull-right">Arbeidsuren</th>
						<th class="col-md-1"><span class="pull-right">Arbeid</th>
						<th class="col-md-1"><span class="pull-right">Materiaal</th>
						<th class="col-md-1"><span class="pull-right">Materieel</th>
						<th class="col-md-1"><span class="pull-right">Totaal</th>
					</tr>
				</thead>
				<tbody>
					@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
					<?php $i = 0; ?>
					@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->where('part_type_id','=',PartType::where('type_name','=','calculation')->first()->id)->whereNull('detail_id')->get() as $activity)
					<?php $i++ ?>
					<tr>
						<td class="col-md-2">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
						<td class="col-md-3">{{ $activity->activity_name }}</td>
						<td class="col-md-1"><span class="pull-right">{{ number_format(CalculationOverview::laborTotal($activity), 2, ",",".") }}</td>
						<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::laborActivity($project->hour_rate, $activity), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::materialActivityProfit($activity, $project->profit_calc_subcontr_mat), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::equipmentActivityProfit($activity, $project->profit_calc_subcontr_equip), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::activityTotalProfit($project->hour_rate, $activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip), 2, ",",".") }} </td>
					</tr>
					@endforeach
					@endforeach
					<tr>
						<th class="col-md-3"><strong>Totaal onderaanneming</strong></th>
						<th class="col-md-2">&nbsp;</th>
						<td class="col-md-1"><strong><span class="pull-right">{{ CalculationOverview::subcontrLaborTotalAmount($project) }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::subcontrLaborTotal($project), 2, ",",".") }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::subcontrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::subcontrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::subcontrTotal($project), 2, ",",".") }}</span></strong></td>
					</tr>
				</tbody>
			</table>

			<h4>Totalen voor calculatieonderdeel</h4>
			<table class="table table-striped only-end-total">
				<thead>
					<tr>
						<th class="col-md-3">&nbsp;</th>
						<th class="col-md-2">&nbsp;</th>
						<th class="col-md-1"><span class="pull-right">Arbeidsuren</span></th>
						<th class="col-md-1"><span class="pull-right">Arbeid</span></th>
						<th class="col-md-1"><span class="pull-right">Materiaal</span></th>
						<th class="col-md-1"><span class="pull-right">Materieel</span></th>
						<th class="col-md-1"><span class="pull-right">Totaal</span></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="col-md-3">&nbsp;</td>
						<td class="col-md-2">&nbsp;</td>
						<td class="col-md-1"><span class="pull-right">{{ CalculationOverview::laborSuperTotalAmount($project) }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::laborSuperTotal($project), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::materialSuperTotal($project), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::equipmentSuperTotal($project), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::superTotal($project), 2, ",",".") }}</span></td>
					</tr>
				</tbody>
			</table>
		</div>
		<!-- DECRIPTION CON&SUBCON END -->

		<!-- DECRIPTION TOTAL START -->
		<div class="show-totals">
			<h4>Calculatie</h4>
			<table class="table table-striped only-end-total">
				<thead>
					<tr>
						<th class="col-md-2">Hoofdstukken</th>
						<th class="col-md-3">Werkzaamheden</th>
						<th class="col-md-1"><span class="pull-right">Arbeidsuren</th>
						<th class="col-md-1"><span class="pull-right">Arbeid</th>
						<th class="col-md-1"><span class="pull-right">Materiaal</th>
						<th class="col-md-1"><span class="pull-right">Materieel</th>
						<th class="col-md-1"><span class="pull-right">Totaal</th>
					</tr>
				</thead>
				<tbody>
					@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
					<?php $i = 0; ?>
					@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_type_id','=',PartType::where('type_name','=','calculation')->first()->id)->whereNull('detail_id')->get() as $activity)
					<?php
					$i++;
					$mat_profit = 0;
					$equip_profit = 0;
					if ($activity->part_id == Part::where('part_name','=','contracting')->first()->id) {
						$mat_profit = $project->profit_calc_contr_mat;
						$equip_profit = $project->profit_calc_contr_equip;
					} else {
						$mat_profit = $project->profit_calc_subcontr_mat;
						$equip_profit = $project->profit_calc_subcontr_equip;
					}
					?>
					<tr>
						<td class="col-md-2">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
						<td class="col-md-3">{{ $activity->activity_name }}</td>
						<td class="col-md-1"><span class="pull-right">{{ number_format(CalculationOverview::laborTotal($activity), 2, ",",".") }}</td>
						<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::laborActivity($project->hour_rate, $activity), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::materialActivityProfit($activity, $mat_profit), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::equipmentActivityProfit($activity, $equip_profit), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::activityTotalProfit($project->hour_rate, $activity, $mat_profit, $equip_profit), 2, ",",".") }} </td>
					</tr>
					@endforeach
					@endforeach
				</tbody>
			</table>

			<h4>Totalen voor calculatieonderdeel</h4>
			<table class="table table-striped only-end-total">
				<thead>
					<tr>
						<th class="col-md-3">&nbsp;</th>
						<th class="col-md-2">&nbsp;</th>
						<th class="col-md-1"><span class="pull-right">Arbeidsuren</span></th>
						<th class="col-md-1"><span class="pull-right">Arbeid</span></th>
						<th class="col-md-1"><span class="pull-right">Materiaal</span></th>
						<th class="col-md-1"><span class="pull-right">Materieel</span></th>
						<th class="col-md-1"><span class="pull-right">Totaal</span></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="col-md-3">&nbsp;</td>
						<td class="col-md-2">&nbsp;</td>
						<td class="col-md-1"><span class="pull-right">{{ CalculationOverview::laborSuperTotalAmount($project) }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::laborSuperTotal($project), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::materialSuperTotal($project), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::equipmentSuperTotal($project), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::superTotal($project), 2, ",",".") }}</span></td>
					</tr>
				</tbody>
			</table>
		</div>
		<h5><strong>Weergegeven bedragen zijn exclusief BTW</strong></h5>
		<!-- DECRIPTION TOTAL END -->
	</div>

	<div class="white-row show-activity">
		<!--PAGE HEADER START-->
		<div class="row">
			<div class="col-sm-6">
				{!! ($relation_self && $relation_self->logo_id) ? "<img src=\"/".Resource::find($relation_self->logo_id)->file_location."\" class=\"img-responsive\" />" : '' !!}
			</div>
			<div class="col-sm-6 text-right">
				<p>
					<h4><strong>{{ $project->project_name }}</strong></h4>
					<ul class="list-unstyled">
						<li><strong>Factuurdatum:</strong> {{ date("j M Y") }}</li>
						<li><strong>Factuurnummer:</strong> {{ $invoice->invoice_code }}</li>
						<li><strong>Administratiefnummer:</strong> {{ $invoice->book_code }}</li>
						<li><strong>Uw referentie:</strong> {{ $invoice->reference }}</li>
					</ul>
				</p>
			</div>
		</div>
		<hr class="margin-top10 margin-bottom10">
		<!--PAGE HEADER END-->

		<!-- DECRIPTION ESTIM CON&SUBCON START -->
		<div class="show-all" style="display:none;">
			<h4>Stelposten Aanneming</h4>
			<table class="table table-striped only-end-total">
				<thead>
					<tr>
						<th class="col-md-2">Hoofdstuk</th>
						<th class="col-md-3">Werkzaamheden</th>
						<th class="col-md-1"><span class="pull-right">Arbeidsuren</th>
						<th class="col-md-1"><span class="pull-right">Arbeid</th>
						<th class="col-md-1"><span class="pull-right">Materiaal</th>
						<th class="col-md-1"><span class="pull-right">Materieel</th>
						<th class="col-md-1"><span class="pull-right">Totaal</th>
					</tr>
				</thead>
				<tbody>
					@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
					<?php $i = 0; ?>
					@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->get() as $activity)
					<?php
						if (!EstimateOverview::activityTotalProfit($activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip))
							continue;
						$i++;
					?>
					<tr>
						<td class="col-md-2">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
						<td class="col-md-3">{{ $activity->activity_name }}</td>
						<td class="col-md-1"><span class="pull-right">{{ number_format(EstimateOverview::laborTotal($activity), 2, ",",".") }}</td>
						<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(EstimateOverview::laborActivity($activity), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(EstimateOverview::materialActivityProfit($activity, $project->profit_calc_contr_mat), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::equipmentActivityProfit($activity, $project->profit_calc_contr_equip), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::activityTotalProfit($activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip), 2, ",",".") }} </td>
						</tr>
					@endforeach
					@endforeach
					<tr>
						<th class="col-md-3"><strong>Totaal aanneming</strong></th>
						<th class="col-md-2">&nbsp;</th>
						<td class="col-md-1"><strong><span class="pull-right">{{ EstimateOverview::contrLaborTotalAmount($project) }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::contrLaborTotal($project), 2, ",",".") }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::contrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::contrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::contrTotal($project), 2, ",",".") }}</span></strong></td>
					</tr>
				</tbody>
			</table>

			<h4>Stelposten Onderaanneming</h4>
			<table class="table table-striped only-end-total">
				<thead>
					<tr>
						<th class="col-md-2">Hoofdstuk</th>
						<th class="col-md-3">Werkzaamheden</th>
						<th class="col-md-1"><span class="pull-right">Arbeidsuren</th>
						<th class="col-md-1"><span class="pull-right">Arbeid</th>
						<th class="col-md-1"><span class="pull-right">Materiaal</th>
						<th class="col-md-1"><span class="pull-right">Materieel</th>
						<th class="col-md-1"><span class="pull-right">Totaal</th>
					</tr>
				</thead>
				<tbody>
					@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
					<?php $i = 0; ?>
					@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->get() as $activity)
					<?php
						if (!EstimateOverview::activityTotalProfit($activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip))
							continue;
						$i++;
					?>
					<tr>
						<td class="col-md-2">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
						<td class="col-md-3">{{ $activity->activity_name }}</td>
						<td class="col-md-1"><span class="pull-right">{{ number_format(EstimateOverview::laborTotal($activity), 2, ",",".") }}</td>
						<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(EstimateOverview::laborActivity($activity), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(EstimateOverview::materialActivityProfit($activity, $project->profit_calc_subcontr_mat), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::equipmentActivityProfit($activity, $project->profit_calc_subcontr_equip), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::activityTotalProfit($activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip), 2, ",",".") }} </td>
					</tr>
					@endforeach
					@endforeach
					<tr>
						<th class="col-md-3"><strong>Totaal onderaanneming</strong></th>
						<th class="col-md-2">&nbsp;</th>
						<td class="col-md-1"><strong><span class="pull-right">{{ EstimateOverview::subcontrLaborTotalAmount($project) }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::subcontrLaborTotal($project), 2, ",",".") }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::subcontrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::subcontrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::subcontrTotal($project), 2, ",",".") }}</span></strong></td>
					</tr>
				</tbody>
			</table>

			<h4>Totalen stelposten</h4>
			<table class="table table-striped only-end-total">
				<thead>
					<tr>
						<th class="col-md-3">&nbsp;</th>
						<th class="col-md-2">&nbsp;</th>
						<th class="col-md-1"><span class="pull-right">Arbeidsuren</span></th>
						<th class="col-md-1"><span class="pull-right">Arbeid</span></th>
						<th class="col-md-1"><span class="pull-right">Materiaal</span></th>
						<th class="col-md-1"><span class="pull-right">Materieel</span></th>
						<th class="col-md-1"><span class="pull-right">Totaal</span></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="col-md-3">&nbsp;</td>
						<td class="col-md-2">&nbsp;</td>
						<td class="col-md-1"><span class="pull-right">{{ EstimateOverview::laborSuperTotalAmount($project) }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::laborSuperTotal($project), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::materialSuperTotal($project), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::equipmentSuperTotal($project), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::superTotal($project), 2, ",",".") }}</span></td>
					</tr>
				</tbody>
			</table>
		</div>
		<!-- DECRIPTION ESTIM CON&SUBCON END -->

		<!-- DECRIPTION ESTIM TOTAL START -->
		<div class="show-totals">
			<h4>Stelposten</h4>
			<table class="table table-striped only-end-total">
				<thead>
					<tr>
						<th class="col-md-2">Hoofdstuk</th>
						<th class="col-md-3">Werkzaamheden</th>
						<th class="col-md-1"><span class="pull-right">Arbeidsuren</th>
						<th class="col-md-1"><span class="pull-right">Arbeid</th>
						<th class="col-md-1"><span class="pull-right">Materiaal</th>
						<th class="col-md-1"><span class="pull-right">Materieel</th>
						<th class="col-md-1"><span class="pull-right">Totaal</th>
					</tr>
				</thead>
				<tbody>
					@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
					<?php $i = 0; ?>
					@foreach (Activity::where('chapter_id','=', $chapter->id)->get() as $activity)
					<?php
						$mat_profit = 0;
						$equip_profit = 0;
						if ($activity->part_id == Part::where('part_name','=','contracting')->first()->id) {
							$mat_profit = $project->profit_calc_contr_mat;
							$equip_profit = $project->profit_calc_contr_equip;
						} else {
							$mat_profit = $project->profit_calc_subcontr_mat;
							$equip_profit = $project->profit_calc_subcontr_equip;
						}
						if (!EstimateOverview::activityTotalProfit($activity, $mat_profit, $equip_profit))
							continue;
						$i++;
					?>
					<tr>
						<td class="col-md-2">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
						<td class="col-md-3">{{ $activity->activity_name }}</td>
						<td class="col-md-1"><span class="pull-right">{{ number_format(EstimateOverview::laborTotal($activity), 2, ",",".") }}</td>
						<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(EstimateOverview::laborActivity($activity), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(EstimateOverview::materialActivityProfit($activity, $mat_profit), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::equipmentActivityProfit($activity, $equip_profit), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::activityTotalProfit($activity, $mat_profit, $equip_profit), 2, ",",".") }} </td>
					</tr>
					@endforeach
					@endforeach
				</tbody>
			</table>

			<h4>Totalen stelposten</h4>
			<table class="table table-striped only-end-total">
				<thead>
					<tr>
						<th class="col-md-3">&nbsp;</th>
						<th class="col-md-2">&nbsp;</th>
						<th class="col-md-1"><span class="pull-right">Arbeidsuren</span></th>
						<th class="col-md-1"><span class="pull-right">Arbeid</span></th>
						<th class="col-md-1"><span class="pull-right">Materiaal</span></th>
						<th class="col-md-1"><span class="pull-right">Materieel</span></th>
						<th class="col-md-1"><span class="pull-right">Totaal</span></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="col-md-3">&nbsp;</td>
						<td class="col-md-2">&nbsp;</td>
						<td class="col-md-1"><span class="pull-right">{{ EstimateOverview::laborSuperTotalAmount($project) }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::laborSuperTotal($project), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::materialSuperTotal($project), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::equipmentSuperTotal($project), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::superTotal($project), 2, ",",".") }}</span></td>
					</tr>
				</tbody>
			</table>
		</div>
		<h5><strong>Weergegeven bedragen zijn exclusief BTW</strong></h5>
		<!-- DECRIPTION ESTIM CON&SUBCON ENDT -->
	</div>

	<div class="white-row show-activity">
		<!--PAGE HEADER START-->
		<div class="row">
			<div class="col-sm-6">
				{!! ($relation_self && $relation_self->logo_id) ? "<img src=\"/".Resource::find($relation_self->logo_id)->file_location."\" class=\"img-responsive\" />" : '' !!}
			</div>
			<div class="col-sm-6 text-right">
				<p>
					<h4><strong>{{ $project->project_name }}</strong></h4>
					<ul class="list-unstyled">
						<li><strong>Factuurdatum:</strong> {{ date("j M Y") }}</li>
						<li><strong>Factuurnummer:</strong> {{ $invoice->invoice_code }}</li>
						<li><strong>Administratiefnummer:</strong> {{ $invoice->book_code }}</li>
						<li><strong>Uw referentie:</strong> {{ $invoice->reference }}</li>
					</ul>
				</p>
			</div>
		</div>
		<hr class="margin-top10 margin-bottom10">
		<!--PAGE HEADER END-->

		<!-- DECRIPTION LESS CON&SUBCON START -->
		<div class="show-all" style="display:none;">
			<h4>Minderwerk Aanneming</h4>
			<table class="table table-striped only-end-total">
				<thead>
					<tr>
						<th class="col-md-2">Hoofdstuk</th>
						<th class="col-md-3">Werkzaamheden</th>
						<th class="col-md-1"><span class="pull-right">Arbeidsuren</th>
						<th class="col-md-1"><span class="pull-right">Arbeid</th>
						<th class="col-md-1"><span class="pull-right">Materiaal</th>
						<th class="col-md-1"><span class="pull-right">Materieel</th>
						<th class="col-md-1"><span class="pull-right">Totaal</th>
					</tr>
				</thead>
				<tbody>
					@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
					<?php $i = 0; ?>
					@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->get() as $activity)
					<?php
						if (!LessOverview::activityTotalProfit($activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip))
							continue;
						$i++;
					?>
					<tr>
						<td class="col-md-2">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
						<td class="col-md-3">{{ $activity->activity_name }}</td>
						<td class="col-md-1"><span class="pull-right">{{ number_format(LessOverview::laborTotal($activity), 2, ",",".") }}</td>
						<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(LessOverview::laborActivity($activity), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(LessOverview::materialActivityProfit($activity, $project->profit_calc_contr_mat), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::equipmentActivityProfit($activity, $project->profit_calc_contr_equip), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::activityTotalProfit($activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip), 2, ",",".") }} </td>
					</tr>
					@endforeach
					@endforeach
					<tr>
						<td class="col-md-3"><strong>Totaal aanneming</strong></td>
						<td class="col-md-2">&nbsp;</td>
						<td class="col-md-1"><strong><span class="pull-right">{{ LessOverview::contrLaborTotalAmount($project) }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::contrLaborTotal($project), 2, ",",".") }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::contrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::contrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::contrTotal($project), 2, ",",".") }}</span></strong></td>
					</tr>
				</tbody>
			</table>

			<h4>Minderwerk Onderaanneming</h4>
			<table class="table table-striped only-end-total">
				<thead>
					<tr>
						<th class="col-md-2">Hoofdstuk</th>
						<th class="col-md-3">Werkzaamheden</th>
						<th class="col-md-1"><span class="pull-right">Arbeidsuren</th>
						<th class="col-md-1"><span class="pull-right">Arbeid</th>
						<th class="col-md-1"><span class="pull-right">Materiaal</th>
						<th class="col-md-1"><span class="pull-right">Materieel</th>
						<th class="col-md-1"><span class="pull-right">Totaal</th>
					</tr>
				</thead>
				<tbody>
					@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
					<?php $i = 0; ?>
					@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->get() as $activity)
					<?php
						if (!LessOverview::activityTotalProfit($activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip))
							continue;
						$i++;
					?>
					<tr>
						<td class="col-md-2">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
						<td class="col-md-3">{{ $activity->activity_name }}</td>
						<td class="col-md-1"><span class="pull-right">{{ number_format(LessOverview::laborTotal($activity), 2, ",",".") }}</td>
						<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(LessOverview::laborActivity($activity), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(LessOverview::materialActivityProfit($activity, $project->profit_calc_subcontr_mat), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::equipmentActivityProfit($activity, $project->profit_calc_subcontr_equip), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::activityTotalProfit($activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip), 2, ",",".") }} </td>
					</tr>
					@endforeach
					@endforeach
					<tr>
						<td class="col-md-3"><strong>Totaal onderaanneming</strong></td>
						<td class="col-md-2">&nbsp;</td>
						<td class="col-md-1"><strong><span class="pull-right">{{ LessOverview::subcontrLaborTotalAmount($project) }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::subcontrLaborTotal($project), 2, ",",".") }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::subcontrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::subcontrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::subcontrTotal($project), 2, ",",".") }}</span></strong></td>
						</tr>
				</tbody>
			</table>

			<h4>Totalen minderwerk</h4>
			<table class="table table-striped only-end-total">
				<thead>
					<tr>
						<th class="col-md-3">&nbsp;</th>
						<th class="col-md-2">&nbsp;</th>
						<th class="col-md-1"><span class="pull-right">Arbeidsuren</span></th>
						<th class="col-md-1"><span class="pull-right">Arbeid</span></th>
						<th class="col-md-1"><span class="pull-right">Materiaal</span></th>
						<th class="col-md-1"><span class="pull-right">Materieel</span></th>
						<th class="col-md-1"><span class="pull-right">Totaal</span></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="col-md-3">&nbsp;</td>
						<td class="col-md-2">&nbsp;</td>
						<td class="col-md-1"><span class="pull-right">{{ LessOverview::laborSuperTotalAmount($project) }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::laborSuperTotal($project), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::materialSuperTotal($project), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::equipmentSuperTotal($project), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::superTotal($project), 2, ",",".") }}</span></td>
					</tr>
				</tbody>
			</table>
		</div>
		<!-- DECRIPTION LESS CON&SUBCON END -->

		<!-- DECRIPTION LESS TOTAL START -->
		<div class="show-totals">
			<h4>Minderwerk</h4>
			<table class="table table-striped only-end-total">
				<thead>
					<tr>
						<th class="col-md-2">Hoofdstuk</th>
						<th class="col-md-3">Werkzaamheden</th>
						<th class="col-md-1"><span class="pull-right">Arbeidsuren</th>
						<th class="col-md-1"><span class="pull-right">Arbeid</th>
						<th class="col-md-1"><span class="pull-right">Materiaal</th>
						<th class="col-md-1"><span class="pull-right">Materieel</th>
						<th class="col-md-1"><span class="pull-right">Totaal</th>
					</tr>
				</thead>
				<tbody>
					@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
					<?php $i = 0; ?>
					@foreach (Activity::where('chapter_id','=', $chapter->id)->get() as $activity)
					<?php
						$mat_profit = 0;
						$equip_profit = 0;
						if ($activity->part_id == Part::where('part_name','=','contracting')->first()->id) {
							$mat_profit = $project->profit_calc_contr_mat;
							$equip_profit = $project->profit_calc_contr_equip;
						} else {
							$mat_profit = $project->profit_calc_subcontr_mat;
							$equip_profit = $project->profit_calc_subcontr_equip;
						}
						if (!LessOverview::activityTotalProfit($activity, $mat_profit, $equip_profit))
							continue;
						$i++;
					?>
					<tr>
						<td class="col-md-2">{{ $i==1 ? $chapter->chapter_name :'' }}</td>
						<td class="col-md-3">{{ $activity->activity_name }}</td>
						<td class="col-md-1"><span class="pull-right">{{ number_format(LessOverview::laborTotal($activity), 2, ",",".") }}</td>
						<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(LessOverview::laborActivity($activity), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(LessOverview::materialActivityProfit($activity, $mat_profit), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::equipmentActivityProfit($activity, $equip_profit), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::activityTotalProfit($activity, $mat_profit, $equip_profit), 2, ",",".") }} </td>
					</tr>
					@endforeach
					@endforeach
				</tbody>
			</table>

			<h4>Totalen minderwerk</h4>
			<table class="table table-striped only-end-total">
				<thead>
					<tr>
						<th class="col-md-3">&nbsp;</th>
						<th class="col-md-2">&nbsp;</th>
						<th class="col-md-1"><span class="pull-right">Arbeidsuren</span></th>
						<th class="col-md-1"><span class="pull-right">Arbeid</span></th>
						<th class="col-md-1"><span class="pull-right">Materiaal</span></th>
						<th class="col-md-1"><span class="pull-right">Materieel</span></th>
						<th class="col-md-1"><span class="pull-right">Totaal</span></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="col-md-3">&nbsp;</td>
						<td class="col-md-2">&nbsp;</td>
						<td class="col-md-1"><span class="pull-right">{{ LessOverview::laborSuperTotalAmount($project) }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::laborSuperTotal($project), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::materialSuperTotal($project), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::equipmentSuperTotal($project), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::superTotal($project), 2, ",",".") }}</span></td>
					</tr>
				</tbody>
			</table>
		</div>
		<h5><strong>Weergegeven bedragen zijn exclusief BTW</strong></h5>
		<!-- DECRIPTION LESS TOTAL END -->
	</div>

	<div class="white-row show-activity">
		<!--PAGE HEADER START-->
		<div class="row">
			<div class="col-sm-6">
				{!! ($relation_self && $relation_self->logo_id) ? "<img src=\"/".Resource::find($relation_self->logo_id)->file_location."\" class=\"img-responsive\" />" : '' !!}
			</div>
			<div class="col-sm-6 text-right">
				<p>
					<h4><strong>{{ $project->project_name }}</strong></h4>
					<ul class="list-unstyled">
						<li><strong>Offertedatum:</strong> {{ date("j M Y") }}</li>
						<li><strong>Offertenummer:</strong> {{ OfferController::getOfferCode($project->id) }}</li>
					</ul>
				</p>
				</div>
		</div>
		<hr class="margin-top10 margin-bottom10">
		<!--PAGE HEADER END-->

		<!-- DECRIPTION MORE CON&SUBCON START -->
		<div class="show-all" style="display:none;">
			<h4>Meerwerk Aanneming</h4>
			<table class="table table-striped only-end-total">
				<thead>
					<tr>
						<th class="col-md-2">Hoofdstuk</th>
						<th class="col-md-3">Werkzaamheden</th>
						<th class="col-md-1"><span class="pull-right">Arbeidsuren</th>
						<th class="col-md-1"><span class="pull-right">Arbeid</th>
						<th class="col-md-1"><span class="pull-right">Materiaal</th>
						<th class="col-md-1"><span class="pull-right">Materieel</th>
						<th class="col-md-1"><span class="pull-right">Totaal</th>
					</tr>
				</thead>
				<tbody>
					@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
					<?php $i = 0; ?>
					@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->where('detail_id','=',Detail::where('detail_name','=','more')->first()->id)->get() as $activity)
					<?php $i++; ?>
					<tr>
						<td class="col-md-2">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
						<td class="col-md-3">{{ $activity->activity_name }}</td>
						<td class="col-md-1"><span class="pull-right">{{ number_format(MoreOverview::laborTotal($activity), 2, ",",".") }}</td>
						<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(MoreOverview::laborActivity($activity), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(MoreOverview::materialActivityProfit($activity, $project->profit_more_contr_mat), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::equipmentActivityProfit($activity, $project->profit_more_contr_equip), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::activityTotalProfit($activity, $project->profit_more_contr_mat, $project->profit_more_contr_equip), 2, ",",".") }} </td>
					</tr>
					@endforeach
					@endforeach
					<tr>
						<th class="col-md-3"><strong>Totaal aanneming</strong></th>
						<th class="col-md-2">&nbsp;</th>
						<td class="col-md-1"><strong><span class="pull-right">{{ MoreOverview::contrLaborTotalAmount($project) }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::contrLaborTotal($project), 2, ",",".") }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::contrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::contrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::contrTotal($project), 2, ",",".") }}</span></strong></td>
					</tr>
				</tbody>
			</table>

			<h4>Meerwerk Onderaanneming</h4>
			<table class="table table-striped only-end-total">
				<thead>
					<tr>
						<th class="col-md-2">Hoofdstuk</th>
						<th class="col-md-3">Werkzaamheden</th>
						<th class="col-md-1"><span class="pull-right">Arbeidsuren</th>
						<th class="col-md-1"><span class="pull-right">Arbeid</th>
						<th class="col-md-1"><span class="pull-right">Materiaal</th>
						<th class="col-md-1"><span class="pull-right">Materieel</th>
						<th class="col-md-1"><span class="pull-right">Totaal</th>
					</tr>
				</thead>
				<tbody>
					@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
					<?php $i = 0; ?>
					@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->where('detail_id','=',Detail::where('detail_name','=','more')->first()->id)->get() as $activity)
					<?php $i++; ?>
					<tr>
						<td class="col-md-2">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
						<td class="col-md-3">{{ $activity->activity_name }}</td>
						<td class="col-md-1"><span class="pull-right">{{ number_format(MoreOverview::laborTotal($activity), 2, ",",".") }}</td>
						<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(MoreOverview::laborActivity($activity), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(MoreOverview::materialActivityProfit($activity, $project->profit_more_subcontr_mat), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::equipmentActivityProfit($activity, $project->profit_more_subcontr_equip), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::activityTotalProfit($activity, $project->profit_more_subcontr_mat, $project->profit_more_subcontr_equip), 2, ",",".") }} </td>
					</tr>
					@endforeach
					@endforeach
					<tr>
						<td class="col-md-3"><strong>Totaal onderaanneming</strong></td>
						<td class="col-md-2">&nbsp;</td>
						<td class="col-md-1"><strong><span class="pull-right">{{ MoreOverview::subcontrLaborTotalAmount($project) }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::subcontrLaborTotal($project), 2, ",",".") }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::subcontrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::subcontrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
						<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::subcontrTotal($project), 2, ",",".") }}</span></strong></td>
					</tr>
				</tbody>
			</table>

			<h4>Totalen meerwerk</h4>
			<table class="table table-striped only-end-total">
				<thead>
					<tr>
						<th class="col-md-3">&nbsp;</th>
						<th class="col-md-2">&nbsp;</th>
						<th class="col-md-1"><span class="pull-right">Arbeidsuren</span></th>
						<th class="col-md-1"><span class="pull-right">Arbeid</span></th>
						<th class="col-md-1"><span class="pull-right">Materiaal</span></th>
						<th class="col-md-1"><span class="pull-right">Materieel</span></th>
						<th class="col-md-1"><span class="pull-right">Totaal</span></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="col-md-3">&nbsp;</td>
						<td class="col-md-2">&nbsp;</td>
						<td class="col-md-1"><span class="pull-right">{{ MoreOverview::laborSuperTotalAmount($project) }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::laborSuperTotal($project), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::materialSuperTotal($project), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::equipmentSuperTotal($project), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::superTotal($project), 2, ",",".") }}</span></td>
					</tr>
				</tbody>
			</table>
		</div>
		<!-- DECRIPTION MORE CON&SUBCON END -->

		<!-- DECRIPTION MORE TOTAL START -->
		<div class="show-totals">
			<h4>Meerwerk</h4>
			<table class="table table-striped only-end-total">
				<thead>
					<tr>
						<th class="col-md-2">Hoofdstuk</th>
						<th class="col-md-3">Werkzaamheden</th>
						<th class="col-md-1"><span class="pull-right">Arbeidsuren</th>
						<th class="col-md-1"><span class="pull-right">Arbeid</th>
						<th class="col-md-1"><span class="pull-right">Materiaal</th>
						<th class="col-md-1"><span class="pull-right">Materieel</th>
						<th class="col-md-1"><span class="pull-right">Totaal</th>
					</tr>
				</thead>
				<tbody>
					@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
					<?php $i = 0; ?>
					@foreach (Activity::where('chapter_id','=', $chapter->id)->where('detail_id','=',Detail::where('detail_name','=','more')->first()->id)->get() as $activity)
					<?php
						$mat_profit = 0;
						$equip_profit = 0;
						if ($activity->part_id == Part::where('part_name','=','contracting')->first()->id) {
							$mat_profit = $project->profit_more_contr_mat;
							$equip_profit = $project->profit_more_contr_equip;
						} else {
							$mat_profit = $project->profit_more_subcontr_mat;
							$equip_profit = $project->profit_more_subcontr_equip;
						}
						$i++;
					?>
					<tr>
						<td class="col-md-2">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
						<td class="col-md-3">{{ $activity->activity_name }}</td>
						<td class="col-md-1"><span class="pull-right">{{ number_format(MoreOverview::laborTotal($activity), 2, ",",".") }}</td>
						<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(MoreOverview::laborActivity($activity), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(MoreOverview::materialActivityProfit($activity, $mat_profit), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::equipmentActivityProfit($activity, $equip_profit), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::activityTotalProfit($activity, $mat_profit, $equip_profit), 2, ",",".") }} </td>
					</tr>
					@endforeach
					@endforeach
				</tbody>
			</table>

			<h4>Totalen meerwerk</h4>
			<table class="table table-striped only-end-total">
				<thead>
					<tr>
						<th class="col-md-3">&nbsp;</th>
						<th class="col-md-2">&nbsp;</th>
						<th class="col-md-1"><span class="pull-right">Arbeidsuren</span></th>
						<th class="col-md-1"><span class="pull-right">Arbeid</span></th>
						<th class="col-md-1"><span class="pull-right">Materiaal</span></th>
						<th class="col-md-1"><span class="pull-right">Materieel</span></th>
						<th class="col-md-1"><span class="pull-right">Totaal</span></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="col-md-3">&nbsp;</td>
						<td class="col-md-2">&nbsp;</td>
						<td class="col-md-1"><span class="pull-right">{{ MoreOverview::laborSuperTotalAmount($project) }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::laborSuperTotal($project), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::materialSuperTotal($project), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::equipmentSuperTotal($project), 2, ",",".") }}</span></td>
						<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::superTotal($project), 2, ",",".") }}</span></td>
					</tr>
				</tbody>
			</table>
		</div>
		<h5><strong>Weergegeven bedragen zijn exclusief BTW</strong></h5>
		<!-- DECRIPTION MORE TOTAL END -->
	</div>

	<div class="white-row show-note">
		<!--PAGE HEADER START-->
		<div class="row">
			<div class="col-sm-6">
				{!! ($relation_self && $relation_self->logo_id) ? "<img src=\"/".Resource::find($relation_self->logo_id)->file_location."\" class=\"img-responsive\" />" : '' !!}
			</div>
			<div class="col-sm-6 text-right">
				<p>
					<h4><strong>{{ $project->project_name }}</strong></h4>
					<ul class="list-unstyled">
						<li><strong>Offertedatum:</strong> {{ date("j M Y") }}</li>
						<li><strong>Offertenummer:</strong> {{ OfferController::getOfferCode($project->id) }}</li>
					</ul>
				</p>
				</div>
		</div>
		<hr class="margin-top10 margin-bottom10">
		<!--PAGE HEADER END-->

		<!-- DECRIPTION CON&SUBCON START -->
		<div class="show-all" style="display:none;">
			<h4>Omschrijving werkzaamheden aanneming</h4>
			<table class="table table-striped">
				<thead>
					<tr>
						<th class="col-md-2">Hoofdstuk</th>
						<th class="col-md-3">Werkzaamheden</th>
						<th class="col-md-7"><span>Omschrijving</th>
					</tr>
				</thead>
				<tbody>
					@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
					<?php $i = 0; ?>
					@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->get() as $activity)
					<?php $i++; ?>
					<tr>
						<td class="col-md-2">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
						<td class="col-md-3">{{ $activity->activity_name }}</td>
						<td class="col-md-7"><span>{{ $activity->note }}</td>
					</tr>
					@endforeach
					@endforeach
				</tbody>
			</table>

			<h4>Omschrijving werkzaamheden onderaanneming</h4>
			<table class="table table-striped">
				<thead>
					<tr>
						<th class="col-md-2">Hoofdstuk</th>
						<th class="col-md-3">Werkzaamheden</th>
						<th class="col-md-7"><span>Omschrijving</th>
					</tr>
				</thead>
				<tbody>
					@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
					<?php $i = 0; ?>
					@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->get() as $activity)
					<?php $i++; ?>
					<tr>
						<td class="col-md-2">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
						<td class="col-md-3">{{ $activity->activity_name }}</td>
						<td class="col-md-7"><span>{{ $activity->note }}</td>
					</tr>
					@endforeach
					@endforeach
				</tbody>
			</table>
		</div>
		<!-- DECRIPTION CON&SUBCON END -->

		<!-- DECRIPTION TOTAL START -->
		<div class="show-totals">
			<h4>Omschrijving werkzaamheden</h4>
			<table class="table table-striped">
				<thead>
					<tr>
						<th class="col-md-2">Hoofdstuk</th>
						<th class="col-md-3">Werkzaamheden</th>
						<th class="col-md-7"><span>Omschrijving</th>
					</tr>
				</thead>
				<tbody>
					@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
					<?php $i = 0; ?>
					@foreach (Activity::where('chapter_id','=', $chapter->id)->get() as $activity)
					<?php $i++; ?>
					<tr>
						<td class="col-md-2">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
						<td class="col-md-3">{{ $activity->activity_name }}</td>
						<td class="col-md-7"><span>{{ $activity->note }}</td>
					</tr>
					@endforeach
					@endforeach
				</tbody>
			</table>
		</div>
		<!-- DECRIPTION TOTAL END -->
	</div>
	</form>

	<!-- INVOICE FOOTER -->
	<div class="row">
	<div class="col-sm-6"></div>
		<div class="col-sm-6 text-right">
			<div class="padding20">
				@if (!$invoice->invoice_close)
				<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Opties</a>
				<?php
				if (!$project->project_close) {
					$prev = Invoice::where('offer_id','=', $invoice->offer_id)->where('isclose','=',false)->orderBy('priority', 'desc')->first();
					if ($prev && $prev->invoice_close) {
						echo '<button class="btn btn-primary osave">Factureren</button>';
					} else if (!$prev) {
						echo '<button class="btn btn-primary osave">Factureren</button>';
					}
				}
				?>
				@else
				<div class="btn-group">
				  <a target="blank" href="/invoice/pdf/project-{{ $project->id }}/invoice-{{ $invoice->id }}" class="btn btn-primary">PDF</a>
				  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				    <span class="caret"></span>
				    <span class="sr-only">Toggle Dropdown</span>
				  </button>
				  <ul class="dropdown-menu">
				    <li><a href="/invoice/pdf/project-{{ $project->id }}/invoice-{{ $invoice->id }}/download?file={{ InvoiceController::getInvoiceCode($project->id).'-factuur.pdf' }}">Download</a></li>
				  </ul>
				</div>
				@endif
			</div>
		</div>
	</div>
	</section>
</div>
<!-- /WRAPPER -->
@stop

<?php } ?>
