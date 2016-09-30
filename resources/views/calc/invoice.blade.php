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
use \Calctool\Models\InvoiceVersion;
use \Calctool\Models\Resource;
use \Calctool\Models\ProjectType;
use \Calctool\Models\Detail;
use \Calctool\Models\BlancRow;
use \Calctool\Models\Tax;
use \Calctool\Calculus\EstimateEndresult;
use \Calctool\Calculus\SetEstimateCalculationEndresult;
use \Calctool\Calculus\MoreEndresult;
use \Calctool\Calculus\LessEndresult;
use \Calctool\Calculus\ResultEndresult;
use \Calctool\Calculus\CalculationOverview;
use \Calctool\Calculus\EstimateOverview;
use \Calctool\Calculus\MoreOverview;
use \Calctool\Calculus\LessOverview;
use \Calctool\Calculus\BlancRowsEndresult;
use \Calctool\Http\Controllers\OfferController;
use \Calctool\Http\Controllers\InvoiceController;
use \Calctool\Calculus\CalculationLabor;

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
	$invoice_last = InvoiceVersion::where('invoice_id','=', $invoice->id)->orderBy('created_at', 'desc')->first();
}

$type = ProjectType::find($project->type_id);
?>

@extends('layout.master')

@section('title', 'Eindfactuur')

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
		$("[name='display-worktotals']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'}).on('switchChange.bootstrapSwitch', function(event, state) {
		  if (state) {
		  	$('.show-activity').show();
		  } else {
			$('.show-activity').hide();
		  }
		});
		$("[name='seperate-subcon']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'}).on('switchChange.bootstrapSwitch', function(event, state) {
		  if (state) {
		  	$('.show-subcon').hide();
		  	$('.show-all').show();
		  } else {
		  	$('.show-subcon').show();
		  	$('.show-all').hide();
		  }
		});
		$("[name='only-totals']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'}).on('switchChange.bootstrapSwitch', function(event, state) {
		  if (state) {
		  	$('.only-total').show();
		  	$("[name='display-specification']").bootstrapSwitch('disabled', false);
		  } else {
		  	$('.only-total').hide();
		  	$("[name='display-specification']").bootstrapSwitch('disabled', true);
		  }
		});
		$("[name='display-specification']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'}).on('switchChange.bootstrapSwitch', function(event, state) {
		  if (state) {
		  	$('.only-end-total-spec').show();
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
	        // $("[name='seperate-subcon']").bootstrapSwitch('disabled', false);

		  } else {
		  	$('.only-end-total-spec').hide();
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
	        // $("[name='seperate-subcon']").bootstrapSwitch('disabled', true);

		  }
		});
		$("[name='display-description']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'}).on('switchChange.bootstrapSwitch', function(event, state) {
		  if (state) {
		  	$('.show-note').show();
		  } else {
			$('.show-note').hide();
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
		$('#adressing').text($('#to_contact option:selected').text());
		$('#to_contact').change(function(e){
			$('#adressing').text($('#to_contact option:selected').text());
		});
		$('.invdate').datepicker().on('changeDate', function(e){
			$('.invdate').datepicker('hide');
			$('#invdateval').val(e.date.toISOString());
			$('.invdate').text(e.date.getDate() + "-" + (e.date.getMonth() + 1)  + "-" + e.date.getFullYear());
		});
		@if ($invoice_last)
		$('.offdate').text("{{ date('d-m-Y', strtotime($invoice_last->offer_make)) }}");

			@if (!$invoice_last->include_tax)
				$("[name='include-tax']").bootstrapSwitch('toggleState');
			@endif

			@if (!$invoice_last->only_totals)
				$("[name='only-totals']").bootstrapSwitch('toggleState');
			@endif

			@if ($invoice_last->seperate_subcon)
				$("[name='seperate-subcon']").bootstrapSwitch('toggleState');
			@endif

			@if ($invoice_last->display_worktotals)
				$("[name='display-worktotals']").bootstrapSwitch('toggleState');
			@endif

			@if ($invoice_last->display_specification)
				$("[name='display-specification']").bootstrapSwitch('toggleState');
			@endif

			@if ($invoice_last->display_description)
				$("[name='display-description']").bootstrapSwitch('toggleState');
			@endif
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
		@if (!$invoice->invoice_close)
		<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Opties</a>
		<?php
		if (!$project->project_close) {
			$prev = Invoice::where('offer_id','=', $invoice->offer_id)->where('isclose','=',false)->orderBy('priority', 'desc')->first();
			if ($prev && $prev->invoice_close) {
				echo '<button class="btn btn-primary osave">Voorbeeld</button>&nbsp;';
			} else if (!$prev) {
				echo '<button class="btn btn-primary osave">Voorbeeld</button>&nbsp;';
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
	<form method="POST" id="frm-invoice">
	{!! csrf_field() !!}
	<input name="id" value="{{ $invoice->id }}" type="hidden"/>
	<input name="projectid" value="{{ $project->id }}" type="hidden"/>

	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Factuur opties @if ($project->tax_reverse)(BTW Verlegd)@endif</h4>
				</div>

				<div class="modal-body">
					<div class="form-horizontal">

						  <div class="form-group">
						    <div class="col-sm-offset-0 col-sm-12">
						      <div class="checkbox">
						        <label>
						          <input name="only-totals" type="checkbox" checked> Calculatie, stelpost en meer-/minderwerk weergeven
						        </label>
						      </div>
						    </div>
						  </div>
						  <div class="form-group">
						    <div class="col-sm-offset-0 col-sm-12">
						      <div class="checkbox">
						        <label>
						          <input name="display-specification" type="checkbox" checked> Specificeren in arbeid, materiaal en overig
						        </label>
						      </div>
						    </div>
						  </div>
						  @if($type->type_name != 'snelle offerte en factuur')
						  @if ($project->use_subcontract)
						   <div class="form-group">
						    <div class="col-sm-offset-0 col-sm-12">
						      <div class="checkbox">
						        <label>
						          <input name="seperate-subcon" type="checkbox"> Onderaanneming apart weergeven
						        </label>
						      </div>
						    </div>
						  </div>
						  @endif
						  @endif
						  @if($type->type_name != 'snelle offerte en factuur')
						   <br>
						  <div class="alert alert-info">
             				<i class="fa fa-arrow-circle-down"></i>
                            <strong>De volgende opties worden als bijlage bijgesloten bij de factuur</strong>
                          </div>		 
						  <br>
						  <div class="form-group">
						    <div class="col-sm-offset-0 col-sm-12">
						      <div class="checkbox">
						        <label>
						          <input name="display-worktotals" type="checkbox"> Totaalkosten per werkzaamheid opnemen
						        </label>
						      </div>
						    </div>
						  </div>
						  <div class="form-group">
						    <div class="col-sm-offset-0 col-sm-12">
						      <div class="checkbox">
						        <label>
						          <input name="display-description" type="checkbox"> Omschrijving van de werkzaamheden weergeven
						        </label>
						      </div>
						    </div>
						  </div>
						  @endif
					</div>
				</div>

				<div class="modal-footer">
					<button class="btn btn-default" data-dismiss="modal">Close</button>
				</div>

			</div>
		</div>
	</div>

	<div class="white-row">
		<?#--PAGE HEADER MASTER START--?>
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
	 						<li><strong>KVK:</strong>{{ $relation_self->kvk }}&nbsp;|&nbsp;<strong>BTW:</strong> {{ $relation_self->btw }}</li>
							<li><strong>Rekeningnummer:</strong> {{ $relation_self->iban }}</li>
							<li><strong>tnv.:</strong> {{ $relation_self->iban_name }}</li>
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
				<h4><strong>FACTUUR</strong></h4>
				<ul class="list-unstyled">
					<li><strong>Projectnaam:</strong>{{ $project->project_name }}</li>
					<li><strong>Factuurdatum:</strong> <a href="#" class="invdate">Bewerk</a></li>
					@if (Auth::user()->pref_use_ct_numbering)
					<li><strong>Factuurnummer:</strong> {{ $invoice->invoice_code }}</li>
					<li><strong>Administratiefnummer:</strong> {{ $invoice->book_code }}</li>
					@else
					<li><strong>Factuurnummer:</strong> {{ $invoice->book_code }}</li>
					@endif
					<li><strong>Uw referentie:</strong> {{ $invoice->reference }}</li>
					<input type="hidden" id="invdateval" name="invdateval" />
				</ul>
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

		<?#--CONTENT, CON & SUBCON START--?>
		<div class="only-total" style="display:none;">
		<div class="show-all" style="display:none;">
			<h4>Specificatie factuur</h4>
			<h5>AANNEMING</h5>
			<table class="table table-striped">
				<thead>
					<tr>
						<th class="col-md-6">&nbsp;</th>
						<th class="col-md-1">Calculatie</th>
						@if ($project->use_more)
						<th class="col-md-1">Meerwerk</th>
						@endif
						@if ($project->use_less)
						<th class="col-md-1">Minderwerk</th>
						@endif
						@if ($project->use_more || $project->use_less)
						<th class="col-md-1">Balans</th>
						@endif
						<th class="col-md-1">@if (!$project->tax_reverse) BTW @endif</th>
						<th class="col-md-1">@if (!$project->tax_reverse) BTW bedrag @endif</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="only-end-total-spec col-md-6">Arbeidskosten</td>
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
						@if ($project->use_more)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
						@endif
						@if ($project->use_less)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
						@endif
						@if ($project->use_more || $project->use_less)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax1($project), 2, ",",".") }}</td>
						@endif
						<td class="only-end-total-spec col-md-1">@if (!$project->tax_reverse) 21% @endif</td>
						<td class="only-end-total-spec col-md-1">@if (!$project->tax_reverse) {{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax1AmountTax($project), 2, ",",".") }} @endif</td>
					</tr>
					<tr>
						<td class="only-end-total-spec col-md-6">&nbsp;</td>
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
						@if ($project->use_more)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
						@endif
						@if ($project->use_less)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
						@endif
						@if ($project->use_more || $project->use_less)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax2($project), 2, ",",".") }}</td>
						@endif
						<td class="only-end-total-spec col-md-1">@if (!$project->tax_reverse) 6% @endif</td>
						<td class="only-end-total-spec col-md-1">@if (!$project->tax_reverse) {{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax2AmountTax($project), 2, ",",".") }} @endif</td>
					</tr>
					<tr>
						<td class="only-end-total-spec col-md-6">Materiaalkosten</td>
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
						@if ($project->use_more)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
						@endif
						@if ($project->use_less)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
						@endif
						@if ($project->use_more || $project->use_less)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax1($project), 2, ",",".") }}</td>
						@endif
						<td class="only-end-total-spec col-md-1">@if (!$project->tax_reverse) 21% @endif</td>
						<td class="only-end-total-spec col-md-1">@if (!$project->tax_reverse) {{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax1AmountTax($project), 2, ",",".") }} @endif</td>
					</tr>
					<tr>
						<td class="only-end-total-spec col-md-6">&nbsp;</td>
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
						@if ($project->use_more)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
						@endif
						@if ($project->use_less)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
						@endif
						@if ($project->use_more || $project->use_less)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax2($project), 2, ",",".") }}</td>
						@endif
						<td class="only-end-total-spec col-md-1">@if (!$project->tax_reverse) 6% @endif</td>
						<td class="only-end-total-spec col-md-1">@if (!$project->tax_reverse) {{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax2AmountTax($project), 2, ",",".") }} @endif</td>
					</tr>
					@if ($project->use_equipment)
					<tr>
						<td class="only-end-total-spec col-md-6">Overige kosten</td>
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
						@if ($project->use_more)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
						@endif
						@if ($project->use_less)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
						@endif
						@if ($project->use_more || $project->use_less)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax1($project), 2, ",",".") }}</td>
						@endif
						<td class="only-end-total-spec col-md-1">@if (!$project->tax_reverse) 21% @endif</td>
						<td class="only-end-total-spec col-md-1">@if (!$project->tax_reverse) {{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax1AmountTax($project), 2, ",",".") }} @endif</td>
					</tr>
					<tr>
						<td class="only-end-total-spec col-md-6">&nbsp;</td>
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
						@if ($project->use_more)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
						@endif
						@if ($project->use_less)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
						@endif
						@if ($project->use_more || $project->use_less)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax2($project), 2, ",",".") }}</td>
						@endif
						<td class="only-end-total-spec col-md-1">@if (!$project->tax_reverse) 6% @endif</td>
						<td class="only-end-total-spec col-md-2">@if (!$project->tax_reverse) {{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax2AmountTax($project), 2, ",",".") }} @endif</td>
					</tr>
					@endif
					<tr>
						<td class="col-md-6"><strong>Totaal Aanneming </strong></td>
						<td class="col-md-1"><strong>{{ '&euro; '.number_format(SetEstimateCalculationEndresult::totalContracting($project), 2, ",",".") }}</strong></td>
						@if ($project->use_more)
						<td class="col-md-1"><strong>{{ '&euro; '.number_format(MoreEndresult::totalContracting($project), 2, ",",".") }}</strong></td>
						@endif
						@if ($project->use_less)
						<td class="col-md-1"><strong>{{ '&euro; '.number_format(LessEndresult::totalContracting($project), 2, ",",".") }}</strong></td>
						@endif
						@if ($project->use_more || $project->use_less)
						<td class="col-md-1"><strong>{{ '&euro; '.number_format(ResultEndresult::totalContracting($project), 2, ",",".") }}</strong></td>
						@endif
						<td class="col-md-1">@if (!$project->tax_reverse) &nbsp; @endif</td>
						<td class="col-md-1"><strong>@if (!$project->tax_reverse) {{ '&euro; '.number_format(ResultEndresult::totalContractingTax($project), 2, ",",".") }} @endif</strong></td>
					</tr>
				</tbody>
			</table>

			<h5>ONDERAANNEMING</h5>
			<table class="table table-striped">
				<thead>
					<tr>
						<th class="col-md-6">&nbsp;</th>
						<th class="col-md-1">Calculatie</th>
						@if ($project->use_more)
						<th class="col-md-1">Meerwerk</th>
						@endif
						@if ($project->use_less)
						<th class="col-md-1">Minderwerk</th>
						@endif
						@if ($project->use_more || $project->use_less)
						<th class="col-md-1">Balans</th>
						@endif
						<th class="col-md-1">@if (!$project->tax_reverse) BTW @endif</th>
						<th class="col-md-1s">@if (!$project->tax_reverse) BTW bedrag @endif</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="only-end-total-spec col-md-6">Arbeidskosten</td>
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
						@if ($project->use_more)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(MoreEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
						@endif
						@if ($project->use_less)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
						@endif
						@if ($project->use_more || $project->use_less)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconLaborBalanceTax1($project), 2, ",",".") }}</td>
						@endif
						<td class="only-end-total-spec col-md-1">@if (!$project->tax_reverse) 21% @endif</td>
						<td class="only-end-total-spec col-md-1">@if (!$project->tax_reverse) {{ '&euro; '.number_format(ResultEndresult::subconLaborBalanceTax1AmountTax($project), 2, ",",".") }} @endif</td>
					</tr>
					<tr>
						<td class="only-end-total-spec col-md-6">&nbsp;</td>
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
						@if ($project->use_more)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(MoreEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
						@endif
						@if ($project->use_less)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
						@endif
						@if ($project->use_more || $project->use_less)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconLaborBalanceTax2($project), 2, ",",".") }}</td>
						@endif
						<td class="only-end-total-spec col-md-1">@if (!$project->tax_reverse) 6% @endif</td>
						<td class="only-end-total-spec col-md-1">@if (!$project->tax_reverse) {{ '&euro; '.number_format(ResultEndresult::subconLaborBalanceTax2AmountTax($project), 2, ",",".") }} @endif</td>
					</tr>
					<tr>
						<td class="only-end-total-spec col-md-6">Materiaalkosten</td>
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
						@if ($project->use_more)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(MoreEndresult::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
						@endif
						@if ($project->use_less)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
						@endif
						@if ($project->use_more || $project->use_less)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconMaterialBalanceTax1($project), 2, ",",".") }}</td>
						@endif
						<td class="only-end-total-spec col-md-1">@if (!$project->tax_reverse) 21% @endif</td>
						<td class="only-end-total-spec col-md-1">@if (!$project->tax_reverse) {{ '&euro; '.number_format(ResultEndresult::subconMaterialBalanceTax1AmountTax($project), 2, ",",".") }} @endif</td>
					</tr>
					<tr>
						<td class="only-end-total-spec col-md-6">&nbsp;</td>
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
						@if ($project->use_more)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(MoreEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
						@endif
						@if ($project->use_less)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
						@endif
						@if ($project->use_more || $project->use_less)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconMaterialBalanceTax2($project), 2, ",",".") }}</td>
						@endif
						<td class="only-end-total-spec col-md-1">@if (!$project->tax_reverse) 6% @endif</td>
						<td class="only-end-total-spec col-md-1">@if (!$project->tax_reverse) {{ '&euro; '.number_format(ResultEndresult::subconMaterialBalanceTax2AmountTax($project), 2, ",",".") }} @endif</td>
					</tr>
					@if ($project->use_equipment)
					<tr>
						<td class="only-end-total-spec col-md-6">Overige kosten</td>
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
						@if ($project->use_more)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(MoreEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
						@endif
						@if ($project->use_less)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
						@endif
						@if ($project->use_more || $project->use_less)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconEquipmentBalanceTax1($project), 2, ",",".") }}</td>
						@endif
						<td class="only-end-total-spec col-md-1">@if (!$project->tax_reverse) 21% @endif</td>
						<td class="only-end-total-spec col-md-1">@if (!$project->tax_reverse) {{ '&euro; '.number_format(ResultEndresult::subconEquipmentBalanceTax1AmountTax($project), 2, ",",".") }} @endif</td>
					</tr>
					<tr>
						<td class="only-end-total-spec col-md-6">&nbsp;</td>
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
						@if ($project->use_more)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(MoreEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
						@endif
						@if ($project->use_less)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
						@endif
						@if ($project->use_more || $project->use_less)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconEquipmentBalanceTax2($project), 2, ",",".") }}</td>
						@endif
						<td class="only-end-total-spec col-md-1">@if (!$project->tax_reverse) 6% @endif</td>
						<td class="only-end-total-spec col-md-1">@if (!$project->tax_reverse) {{ '&euro; '.number_format(ResultEndresult::subconEquipmentBalanceTax2AmountTax($project), 2, ",",".") }} @endif</td>
					</tr>
					@endif
					<tr>
						<td class="col-md-6"><strong>Totaal Onderaanneming </strong></td>
						<td class="col-md-1"><strong>{{ '&euro; '.number_format(SetEstimateCalculationEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
						@if ($project->use_more)
						<td class="col-md-1"><strong>{{ '&euro; '.number_format(MoreEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
						@endif
						@if ($project->use_less)
						<td class="col-md-1"><strong>{{ '&euro; '.number_format(LessEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
						@endif
						@if ($project->use_more || $project->use_less)
						<td class="col-md-1"><strong>{{ '&euro; '.number_format(ResultEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
						@endif
						<td class="col-md-1">@if (!$project->tax_reverse) &nbsp; @endif</td>
						<td class="col-md-1"><strong>@if (!$project->tax_reverse) {{ '&euro; '.number_format(ResultEndresult::totalSubcontractingTax($project), 2, ",",".") }} @endif</strong></td>
					</tr>
				</tbody>
			</table>
		</div>
		</div>
		<?#--CONTENT, CON & SUBCON END--?>



		<?#--CONTENT, TOTAL START--?>
		<div class="only-total" >
		<div class="show-subcon" >
			<h4>Specificatie factuur</h4>
			@if($type->type_name != 'snelle offerte en factuur')
			<table class="table table-striped">
				<thead>
					<tr>
						<th class="col-md-6">&nbsp;</th>
						<th class="col-md-1">Calculatie</th>
						@if ($project->use_more)
						<th class="col-md-1">Meerwerk</th>
						@endif
						@if ($project->use_less)
						<th class="col-md-1">Minderwerk</th>
						@endif
						@if ($project->use_more || $project->use_less)
						<th class="col-md-1">Balans</th>
						@endif
						<th class="col-md-1">@if (!$project->tax_reverse) BTW @endif</th>
						<th class="col-md-1">@if (!$project->tax_reverse) BTW bedrag @endif</th>
						
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="only-end-total-spec col-md-6">Arbeidskosten</td>
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcLaborActivityTax1Amount($project)+SetEstimateCalculationEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
						@if ($project->use_more)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcLaborActivityTax1Amount($project)+MoreEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
						@endif
						@if ($project->use_less)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax1Amount($project)+LessEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
						@endif
						@if ($project->use_more || $project->use_less)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax1($project)+ResultEndresult::subconLaborBalanceTax1($project), 2, ",",".") }}</td>
						@endif
						<td class="only-end-total-spec col-md-1">@if (!$project->tax_reverse) 21% @endif</td>
						<td class="only-end-total-spec col-md-1">@if (!$project->tax_reverse) {{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax1AmountTax($project)+ResultEndresult::subconLaborBalanceTax1AmountTax($project), 2, ",",".") }} @endif</td>
					</tr>
					<tr>
						<td class="only-end-total-spec col-md-6">&nbsp;</td>
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcLaborActivityTax2Amount($project)+SetEstimateCalculationEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
						@if ($project->use_more)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcLaborActivityTax2Amount($project)+MoreEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
						@endif
						@if ($project->use_less)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax2Amount($project)+LessEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
						@endif
						@if ($project->use_more || $project->use_less)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax2($project)+ResultEndresult::subconLaborBalanceTax2($project), 2, ",",".") }}</td>
						@endif
						<td class="only-end-total-spec col-md-1">@if (!$project->tax_reverse) 6% @endif</td>
						<td class="only-end-total-spec col-md-1">@if (!$project->tax_reverse) {{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax2AmountTax($project)+ResultEndresult::subconLaborBalanceTax2AmountTax($project), 2, ",",".") }} @endif</td>
					</tr>
					
					<tr>
						<td class="only-end-total-spec col-md-6">Materiaalkosten</td>
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcMaterialActivityTax1Amount($project)+SetEstimateCalculationEndresult::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
						@if ($project->use_more)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcMaterialActivityTax1Amount($project)+MoreEndresult::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
						@endif
						@if ($project->use_less)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax1Amount($project)+LessEndresult::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
						@endif
						@if ($project->use_more || $project->use_less)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax1($project)+ResultEndresult::subconMaterialBalanceTax1($project), 2, ",",".") }}</td>
						@endif
						<td class="only-end-total-spec col-md-1">@if (!$project->tax_reverse) 21% @endif</td>
						<td class="only-end-total-spec col-md-1">@if (!$project->tax_reverse) {{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax1AmountTax($project)+ResultEndresult::subconMaterialBalanceTax1AmountTax($project), 2, ",",".") }} @endif</td>

					</tr>
					<tr>
						<td class="only-end-total-spec col-md-6">&nbsp;</td>
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcMaterialActivityTax2Amount($project)+SetEstimateCalculationEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
						@if ($project->use_more)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcMaterialActivityTax2Amount($project)+MoreEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
						@endif
						@if ($project->use_less)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax2Amount($project)+LessEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
						@endif
						@if ($project->use_more || $project->use_less)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax2($project)+ResultEndresult::subconMaterialBalanceTax2($project), 2, ",",".") }}</td>
						@endif
						<td class="only-end-total-spec col-md-1">@if (!$project->tax_reverse) 6% @endif</td>
						<td class="only-end-total-spec col-md-1">@if (!$project->tax_reverse) {{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax2AmountTax($project)+ResultEndresult::subconMaterialBalanceTax2AmountTax($project), 2, ",",".") }} @endif</td>
					</tr>
					@if ($project->use_equipment)
					<tr>
						<td class="only-end-total-spec col-md-6">Overige kosten</td>
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcEquipmentActivityTax1Amount($project)+SetEstimateCalculationEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
						@if ($project->use_more)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcEquipmentActivityTax1Amount($project)+MoreEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
						@endif
						@if ($project->use_less)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax1Amount($project)+LessEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
						@endif
						@if ($project->use_more || $project->use_less)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax1($project)+ResultEndresult::subconEquipmentBalanceTax1($project), 2, ",",".") }}</td>
						@endif
						<td class="only-end-total-spec col-md-1">@if (!$project->tax_reverse) 21% @endif</td>
						<td class="only-end-total-spec col-md-1">@if (!$project->tax_reverse) {{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax1AmountTax($project)+ResultEndresult::subconEquipmentBalanceTax1AmountTax($project), 2, ",",".") }} @endif</td>
					</tr>
					<tr>
						<td class="only-end-total-spec col-md-6">&nbsp;</td>
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcEquipmentActivityTax2Amount($project)+SetEstimateCalculationEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
						@if ($project->use_more)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcEquipmentActivityTax2Amount($project)+MoreEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
						@endif
						@if ($project->use_less)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax2Amount($project)+LessEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
						@endif
						@if ($project->use_more || $project->use_less)
						<td class="only-end-total-spec col-md-1">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax2($project)+ResultEndresult::subconEquipmentBalanceTax2($project), 2, ",",".") }}</td>
						@endif
						<td class="only-end-total-spec col-md-1">@if (!$project->tax_reverse) 6% @endif</td>
						<td class="only-end-total-spec col-md-1">@if (!$project->tax_reverse) {{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax2AmountTax($project)+ResultEndresult::subconEquipmentBalanceTax2AmountTax($project), 2, ",",".") }} @endif</td>
					</tr>
					@endif
					<tr>
						<td class="col-md-6"><strong>Totaal Aanneming </strong></td>
						<td class="col-md-1"><strong>{{ '&euro; '.number_format(SetEstimateCalculationEndresult::totalContracting($project)+SetEstimateCalculationEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
						@if ($project->use_more)
						<td class="col-md-1"><strong>{{ '&euro; '.number_format(MoreEndresult::totalContracting($project)+MoreEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
						@endif
						@if ($project->use_less)
						<td class="col-md-1"><strong>{{ '&euro; '.number_format(LessEndresult::totalContracting($project)+LessEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
						@endif
						@if ($project->use_more || $project->use_less)
						<td class="col-md-1"><strong>{{ '&euro; '.number_format(ResultEndresult::totalContracting($project)+ResultEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
						@endif
						<td class="col-md-1">@if (!$project->tax_reverse) &nbsp;</td>@endif
						<td class="col-md-1"><strong>@if (!$project->tax_reverse) {{ '&euro; '.number_format(ResultEndresult::totalContractingTax($project)+ResultEndresult::totalSubcontractingTax($project), 2, ",",".") }}</strong></td>@endif
					</tr>
				</tbody>
			</table>
			@else
			<table class="table table-striped">
				<thead>
					<tr>
						<th class="col-md-4">Omschrijving</th>
						<th class="col-md-2">€ / Eenh (excl. BTW)</th>
						<th class="col-md-1">Aantal</th>
						<th class="col-md-1">Totaal</th>
						@if (!$project->tax_reverse)<th class="col-md-1">BTW</th>@endif
						@if (!$project->tax_reverse)<th class="col-md-1">BTW bedrag</th>@endif
					</tr>
				</thead>
				<tbody>
					@foreach (BlancRow::where('project_id','=', $project->id)->get() as $row)
					<tr>
						<td class="col-md-4">{{ $row->description }}</td>
						<td class="col-md-2">{{ '&euro; '.number_format($row->rate, 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format($row->amount, 2, ",",".") }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format($row->rate * $row->amount, 2, ",",".") }}</td>
						@if (!$project->tax_reverse)<td class="col-md-1">{{ Tax::find($row->tax_id)->tax_rate }}%</td>@endif
						@if (!$project->tax_reverse)<td class="col-md-1">{{ '&euro; '.number_format(($row->rate * $row->amount/100) * Tax::find($row->tax_id)->tax_rate, 2, ",",".") }}</td>@endif
					</tr>
					@endforeach
				</tbody>
			</table>
			@endif
		</div> 
		</div>


		<h4>Totaalfactuur</h4>
		<hr>
			<table class="table table-striped">
				<tbody>
					<tr>
						<th class="col-md-7">&nbsp;</th>
						<td class="col-md-4"><strong>Calculatief te factureren</strong> @if (!$project->tax_reverse) <i>(Excl. BTW)</i> @endif</td>
						<td class="col-md-1"><strong>{{ '&euro; '.number_format(ResultEndresult::totalProject($project), 2, ",",".") }}</strong></td>
					</tr>
					@if (!$project->tax_reverse)
					<tr>
						<th class="col-md-7">&nbsp;</th>
						<td class="col-md-4">BTW bedrag 21%</td>
						<td class="col-md-1">{{ '&euro; '.number_format((ResultEndresult::totalContractingTax1($project) + ResultEndresult::totalSubcontractingTax1($project)), 2, ",",".") }}</td>
					</tr>
					<tr>
						<th class="col-md-7">&nbsp;</th>
						<td class="col-md-4">BTW bedrag 6%</td>
						<td class="col-md-1">{{ '&euro; '.number_format((ResultEndresult::totalContractingTax2($project) + ResultEndresult::totalSubcontractingTax2($project)), 2, ",",".") }}</td>
					</tr>
					<tr>
						<th class="col-md-7">&nbsp;</th>
						<td class="col-md-4"><strong>Calculatief te factureren</strong> @if (!$project->tax_reverse) <i>(Incl. BTW)</i> @endif</td>
						<td class="col-md-1"><strong>{{ '&euro; '.number_format(ResultEndresult::superTotalProject($project)+BlancRowsEndresult::rowTax1AmountTax($project)+BlancRowsEndresult::rowTax2AmountTax($project), 2, ",",".") }}</strong></td>
					</tr>
					@endif
				</tbody>
			</table>

				<?php
				$cnt = Invoice::where('offer_id','=', $invoice->offer_id)->count();
				if ($cnt>1) {
				?>
				<h4>Reeds betaald</h4>
				<hr>
				<table class="table table-striped">
					<tbody>
						<tr>
							<td class="col-md-7">&nbsp;</td>
							<td class="col-md-4"><strong>Totaal voorgaande termijn(en)</strong> @if (!$project->tax_reverse) <i>(Excl. BTW)</i> @endif</td>
							<td class="col-md-1"><strong>{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('amount'), 2, ",",".") }}</strong></td>
						</tr>
						@if (!$project->tax_reverse)
						<tr>
							
							<td class="col-md-7">&nbsp;</td>
							<td class="col-md-4">BTW bedrag 21%</td>
							<td class="col-md-1">{{ '&euro; '.number_format((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('rest_21')/100)*21, 2, ",",".") }}</td>
						</tr>
						<tr>
							<td class="col-md-7">&nbsp;</td>
							<td class="col-md-4">BTW bedrag 6%</td>
							<td class="col-md-1">{{ '&euro; '.number_format((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('rest_6')/100)*6, 2, ",",".") }}</td>
						</tr>
						<tr>
							
							<td class="col-md-7">&nbsp;</td>
							<td class="col-md-4"><strong>Calculatief reeds betaald</strong> @if (!$project->tax_reverse) <i>(Incl. BTW)</i> @endif</td>
							<td class="col-md-1"><strong>{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('amount')+((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('rest_21')/100)*21)+((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('rest_6')/100)*6), 2, ",",".") }}</strong></td>
						</tr>
						@endif
					</tbody>
				</table>

				<h4>Resterend te betalen</h4>
				<hr>
				<table class="table table-striped">
					<tbody>
						<tr>
							<td class="col-md-7">&nbsp;</td>
							<td class="col-md-3"><strong>Laatste van in totaal {{Invoice::where('offer_id','=', $invoice->offer_id)->count()}} termijnen</strong> @if (!$project->tax_reverse) <i>(Excl. BTW) @endif</td>
							<td class="col-md-1"><strong>{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->amount, 2, ",",".") }}</strong></td>
						</tr>
						@if (!$project->tax_reverse)
						<tr>
							<td class="col-md-7">&nbsp;</td>
							<td class="col-md-4">BTW bedrag 21%</td>
							<td class="col-md-1">{{ '&euro; '.number_format((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->rest_21/100)*21, 2, ",",".") }}</td>
						</tr>
						<tr>
							<td class="col-md-7">&nbsp;</td>
							<td class="col-md-4">BTW bedrag 6%</td>
							<td class="col-md-1">{{ '&euro; '.number_format((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->rest_6/100)*6, 2, ",",".") }}</td>
						</tr>
						<tr>
							<td class="col-md-7">&nbsp;</td>
							<td class="col-md-4"><strong>Resterend te betalen</strong> @if (!$project->tax_reverse) <i>(Incl. BTW)</i> @endif</td>
							<td class="col-md-1"><strong>{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->amount+((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->rest_21/100)*21)+((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->rest_6/100)*6), 2, ",",".") }}</strong></td>
						</tr>
						@endif

					</tbody>
				</table>
				<?php } ?>
				<?#--CONTENT, TOTAL END--?>

				<?#--CLOSER START--?>
				<textarea name="closure" id="closure" rows="5" class="form-control">{{ ($invoice ? ($invoice->closure ? $invoice->closure : Auth::user()->pref_invoice_closure) : Auth::user()->pref_invoice_closure) }}</textarea>
				@if($project->tax_reverse)
				<br>
				<h2>Deze factuur is <strong>BTW Verlegd</strong></h2>
				<br>
				@endif
				<div class="row">
					<div class="col-sm-12">
					<h4>Bepalingen</h4>
					<ul>
						<li>Deze factuur dient betaald te worden binnen {{ $invoice->payment_condition }} dagen na dagtekening.</li>
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

			<div class="white-row show-activity" class="show-subcon" class="show-all" style="display:none;">
				<?#--PAGE HEADER START--?>
				<div class="row">
					<div class="col-sm-6">
						{!! ($relation_self && $relation_self->logo_id) ? "<img src=\"/".Resource::find($relation_self->logo_id)->file_location."\" class=\"img-responsive\" />" : '' !!}
					</div>
					<div class="col-sm-6 text-right">
						<p>
							<h4><strong>{{ $project->project_name }}</strong></h4>
							<ul class="list-unstyled">
								<li><strong>Factuurdatum:</strong> <a href="#" class="invdate">Bewerk</a></li>
								@if (Auth::user()->pref_use_ct_numbering)
								<li><strong>Factuurnummer:</strong> {{ $invoice->invoice_code }}</li>
								<li><strong>Administratiefnummer:</strong> {{ $invoice->book_code }}</li>
								@else
								<li><strong>Factuurnummer:</strong> {{ $invoice->book_code }}</li>
								@endif
								<li><strong>Uw referentie:</strong> {{ $invoice->reference }}</li>
							</ul>
						</p>
					</div>
				</div>
				<hr class="margin-top10 margin-bottom10">
				<?#--PAGE HEADER END--?>

				<?#-- DECRIPTION CON&SUBCON START --?>
				<div class="show-all">
					<h4>Calculatie Aanneming</h4>
					<table class="table table-striped">
						<thead>
							<tr>
								<th class="col-md-3">Onderdeel</th>
								<th class="col-md-4">Werkzaamheden</th>
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Arbeidsuren</th>
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Arbeid</th>
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Materiaal</th>
								@if ($project->use_equipment)
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Overig</th>
								@endif
								<th class="col-md-1"><span class="pull-right">Totaal</th>
							</tr>
						</thead>
						<tbody>
							@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
							<?php $i = 0; ?>
							@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->where('part_type_id','=',PartType::where('type_name','=','calculation')->first()->id)->whereNull('detail_id')->get() as $activity)
							<?php $i++; ?>
							<tr>
								<td class="col-md-3">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
								<td class="col-md-4">{{ $activity->activity_name }}</td>
								<td class="only-end-total-spec col-md-1"><span class="pull-right">{{ number_format(CalculationOverview::laborTotal($activity), 2, ",",".") }}</td>
								<td class="only-end-total-spec col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::laborActivity($project->hour_rate, $activity), 2, ",",".") }}</span></td>
								<td class="only-end-total-spec col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::materialActivityProfit($activity, $project->profit_calc_contr_mat), 2, ",",".") }}</span></td>
								@if ($project->use_equipment)
								<td class="only-end-total-spec col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::equipmentActivityProfit($activity, $project->profit_calc_contr_equip), 2, ",",".") }}</span></td>
								@endif
								<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::activityTotalProfit($project->hour_rate, $activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip), 2, ",",".") }} </td>
							</tr>
							@endforeach
							@endforeach
							<tr>
								<td class="col-md-3"><strong>Totaal aanneming</strong></th>
								<td class="col-md-4">&nbsp;</th>
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ number_format(CalculationOverview::contrLaborTotalAmount($project), 2, ",",".") }}</span></strong></td>
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::contrLaborTotal($project), 2, ",",".") }}</span></strong></td>
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::contrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
								@if ($project->use_equipment)
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::contrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
								@endif
								<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::contrTotal($project), 2, ",",".") }}</span></strong></td>
							</tr>
						</tbody>
					</table>

					<h4>Calculatie Onderaanneming</h4>
					<table class="table table-striped">
						<thead>
							<tr>
								<th class="col-md-3">Onderdeel</th>
								<th class="col-md-4">Werkzaamheden</th>
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Arbeidsuren</th>
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Arbeid</th>
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Materiaal</th>
								@if ($project->use_equipment)
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Overig</th>
								@endif
								<th class="col-md-1"><span class="pull-right">Totaal</th>
							</tr>
						</thead>
						<tbody>
							@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
							<?php $i = 0; ?>
							@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->where('part_type_id','=',PartType::where('type_name','=','calculation')->first()->id)->whereNull('detail_id')->get() as $activity)
							<?php $i++ ?>
							<tr>
								<td class="col-md-3">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
								<td class="col-md-4">{{ $activity->activity_name }}</td>
								<td class="only-end-total-spec col-md-1"><span class="pull-right">{{ number_format(CalculationOverview::laborTotal($activity), 2, ",",".") }}</td>
								<td class="only-end-total-spec col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::laborActivity($project->hour_rate, $activity), 2, ",",".") }}</span></td>
								<td class="only-end-total-spec col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::materialActivityProfit($activity, $project->profit_calc_subcontr_mat), 2, ",",".") }}</span></td>
								@if ($project->use_equipment)
								<td class="only-end-total-spec col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::equipmentActivityProfit($activity, $project->profit_calc_subcontr_equip), 2, ",",".") }}</span></td>
								@endif
								<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::activityTotalProfit($project->hour_rate, $activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip), 2, ",",".") }} </td>
							</tr>
							@endforeach
							@endforeach
							<tr>
								<td class="col-md-3"><strong>Totaal onderaanneming</strong></th>
								<td class="col-md-4">&nbsp;</th>
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ number_format(CalculationOverview::subcontrLaborTotalAmount($project), 2, ",",".") }}</span></strong></td>
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::subcontrLaborTotal($project), 2, ",",".") }}</span></strong></td>
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::subcontrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
								@if ($project->use_equipment)
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::subcontrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
								@endif
								<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::subcontrTotal($project), 2, ",",".") }}</span></strong></td>
							</tr>
						</tbody>
					</table>

					<h4>Totaal voor calculatieonderdeel</h4>
					<table class="table table-striped">
						<thead>
							<tr>
								<th class="col-md-7">&nbsp;</th>
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Arbeidsuren</span></th>
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Arbeid</span></th>
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Materiaal</span></th>
								@if ($project->use_equipment)
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Overig</span></th>
								@endif
								<th class="col-md-1"><span class="pull-right">Totaal</span></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="col-md-7"><strong>Totalen calculatie</strong></td>
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ number_format(CalculationOverview::laborSuperTotalAmount($project), 2, ",",".") }}</span></strong></td>
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::laborSuperTotal($project), 2, ",",".") }}</span></strong></td>
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::materialSuperTotal($project), 2, ",",".") }}</span></strong></td>
								@if ($project->use_equipment)
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::equipmentSuperTotal($project), 2, ",",".") }}</span></strong></td>
								@endif
								<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::superTotal($project), 2, ",",".") }}</span></strong></td>
							</tr>
						</tbody>
					</table>
				</div>
				<?#-- DECRIPTION CON&SUBCON END --?>

				<?#-- DECRIPTION TOTAL START --?>
				<div class="show-subcon" style="display:none;">
					<h4>Calculatie</h4>
					<table class="table table-striped">
						<thead>
							<tr>
								<th class="col-md-3">Onderdeel</th>
								<th class="col-md-4">Werkzaamheden</th>
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Arbeidsuren</th>
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Arbeid</th>
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Materiaal</th>
								@if ($project->use_equipment)
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Overig</th>
								@endif
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
								<td class="col-md-3">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
								<td class="col-md-4">{{ $activity->activity_name }}</td>
								<td class="only-end-total-spec col-md-1"><span class="pull-right">{{ number_format(CalculationOverview::laborTotal($activity), 2, ",",".") }}</td>
								<td class="only-end-total-spec col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::laborActivity($project->hour_rate, $activity), 2, ",",".") }}</span></td>
								<td class="only-end-total-spec col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::materialActivityProfit($activity, $mat_profit), 2, ",",".") }}</span></td>
								@if ($project->use_equipment)
								<td class="only-end-total-spec col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::equipmentActivityProfit($activity, $equip_profit), 2, ",",".") }}</span></td>
								@endif
								<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::activityTotalProfit($project->hour_rate, $activity, $mat_profit, $equip_profit), 2, ",",".") }} </td>
							</tr>
							@endforeach
							@endforeach
						</tbody>
					</table>
					<table class="table table-striped only-end-total">
						<thead>
							<tr>
								<th class="col-md-7">&nbsp;</th>
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Arbeidsuren</span></th>
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Arbeid</span></th>
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Materiaal</span></th>
								@if ($project->use_equipment)
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Overig</span></th>
								@endif
								<th class="col-md-1"><span class="pull-right">Totaal</span></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="col-md-7"><strong>Totalen calculatie</strong></td>
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ number_format(CalculationOverview::laborSuperTotalAmount($project), 2, ",",".") }}</span></strong></td>
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::laborSuperTotal($project), 2, ",",".") }}</span></strong></td>
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::materialSuperTotal($project), 2, ",",".") }}</span></strong></td>
								@if ($project->use_equipment)
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::equipmentSuperTotal($project), 2, ",",".") }}</span></strong></td>
								@endif
								<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::superTotal($project), 2, ",",".") }}</span></strong></td>
							</tr>
						</tbody>
					</table>
				</div>
				<h5><strong>Weergegeven bedragen zijn exclusief BTW</strong></h5>
			</div>
			<?#-- DECRIPTION TOTAL END --?>

			@if ($project->use_estimate)
			<?#--PAGE HEADER START--?>
			<div class="white-row show-activity" class="show-subcon" class="show-all" style="display:none;">
				<div class="row">
					<div class="col-sm-6">
						{!! ($relation_self && $relation_self->logo_id) ? "<img src=\"/".Resource::find($relation_self->logo_id)->file_location."\" class=\"img-responsive\" />" : '' !!}
					</div>
					<div class="col-sm-6 text-right">
						<p>
							<h4><strong>{{ $project->project_name }}</strong></h4>
							<ul class="list-unstyled">
								<li><strong>Factuurdatum:</strong> <a href="#" class="invdate">Bewerk</a></li>
								@if (Auth::user()->pref_use_ct_numbering)
								<li><strong>Factuurnummer:</strong> {{ $invoice->invoice_code }}</li>
								<li><strong>Administratiefnummer:</strong> {{ $invoice->book_code }}</li>
								@else
								<li><strong>Factuurnummer:</strong> {{ $invoice->book_code }}</li>
								@endif
								<li><strong>Uw referentie:</strong> {{ $invoice->reference }}</li>
							</ul>
						</p>
					</div>
				</div>
				<hr class="margin-top10 margin-bottom10">
				<?#--PAGE HEADER END--?>

				<?#-- DECRIPTION ESTIM CON&SUBCON START --?>
				<div class="show-all">
					<h4>Stelposten Aanneming</h4>
					<table class="table table-striped">
						<thead>
							<tr>
								<th class="col-md-3">Onderdeel</th>
								<th class="col-md-4">Werkzaamheden</th>
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Arbeidsuren</th>
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Arbeid</th>
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Materiaal</th>
								@if ($project->use_equipment)
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Overig</th>
								@endif
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
								<td class="col-md-3">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
								<td class="col-md-4">{{ $activity->activity_name }}</td>
								<td class="only-end-total-spec col-md-1"><span class="pull-right">{{ number_format(EstimateOverview::laborTotal($activity), 2, ",",".") }}</td>
								<td class="only-end-total-spec col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(EstimateOverview::laborActivity($activity), 2, ",",".") }}</span></td>
								<td class="only-end-total-spec col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(EstimateOverview::materialActivityProfit($activity, $project->profit_calc_contr_mat), 2, ",",".") }}</span></td>
								@if ($project->use_equipment)
								<td class="only-end-total-spec col-md-1"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::equipmentActivityProfit($activity, $project->profit_calc_contr_equip), 2, ",",".") }}</span></td>
								@endif
								<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::activityTotalProfit($activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip), 2, ",",".") }} </td>
								</tr>
							@endforeach
							@endforeach
							<tr>
								<td class="col-md-3"><strong>Totaal aanneming</strong></th>
								<td class="col-md-4">&nbsp;</th>
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ number_format(EstimateOverview::contrLaborTotalAmount($project), 2, ",",".") }}</span></strong></td>
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::contrLaborTotal($project), 2, ",",".") }}</span></strong></td>
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::contrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
								@if ($project->use_equipment)
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::contrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
								@endif
								<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::contrTotal($project), 2, ",",".") }}</span></strong></td>
							</tr>
						</tbody>
					</table>

					<h4>Stelposten Onderaanneming</h4>
					<table class="table table-striped">
						<thead>
							<tr>
								<th class="col-md-3">Onderdeel</th>
								<th class="col-md-4">Werkzaamheden</th>
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Arbeidsuren</th>
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Arbeid</th>
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Materiaal</th>
								@if ($project->use_equipment)
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Overig</th>
								@endif
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
								<td class="col-md-3">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
								<td class="col-md-4">{{ $activity->activity_name }}</td>
								<td class="only-end-total-spec col-md-1"><span class="pull-right">{{ number_format(EstimateOverview::laborTotal($activity), 2, ",",".") }}</td>
								<td class="only-end-total-spec col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(EstimateOverview::laborActivity($activity), 2, ",",".") }}</span></td>
								<td class="only-end-total-spec col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(EstimateOverview::materialActivityProfit($activity, $project->profit_calc_subcontr_mat), 2, ",",".") }}</span></td>
								@if ($project->use_equipment)
								<td class="only-end-total-spec col-md-1"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::equipmentActivityProfit($activity, $project->profit_calc_subcontr_equip), 2, ",",".") }}</span></td>
								@endif
								<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::activityTotalProfit($activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip), 2, ",",".") }} </td>
							</tr>
							@endforeach
							@endforeach
							<tr>
								<td class="col-md-3"><strong>Totaal onderaanneming</strong></th>
								<td class="col-md-4">&nbsp;</th>
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ number_format(EstimateOverview::subcontrLaborTotalAmount($project), 2, ",",".") }}</span></strong></td>
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::subcontrLaborTotal($project), 2, ",",".") }}</span></strong></td>
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::subcontrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
								@if ($project->use_equipment)
								<td class="only-end-total-speccol-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::subcontrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
								@endif
								<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::subcontrTotal($project), 2, ",",".") }}</span></strong></td>
							</tr>
						</tbody>
					</table>

					<h4>Totalen stelposten</h4>
					<table class="table table-striped">
						<thead>
							<tr>
								<th class="col-md-7">&nbsp;</th>
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Arbeidsuren</span></th>
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Arbeid</span></th>
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Materiaal</span></th>
								@if ($project->use_equipment)
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Overig</span></th>
								@endif
								<th class="col-md-1"><span class="pull-right">Totaal</span></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="col-md-7"><strong>Totalen stelposten</strong></td>
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ number_format(EstimateOverview::laborSuperTotalAmount($project), 2, ",",".") }}</span></strong></td>
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::laborSuperTotal($project), 2, ",",".") }}</span></strong></td>
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::materialSuperTotal($project), 2, ",",".") }}</span></strong></td>
								@if ($project->use_equipment)
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::equipmentSuperTotal($project), 2, ",",".") }}</span></strong></td>
								@endif
								<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::superTotal($project), 2, ",",".") }}</span></strong></td>
							</tr>
						</tbody>
					</table>
				</div>
				<?#-- DECRIPTION ESTIM CON&SUBCON END --?>

				<?#-- DECRIPTION ESTIM TOTAL START --?>
				<div class="show-subcon" style="display:none;">
					<h4>Stelposten</h4>
					<table class="table table-striped">
						<thead>
							<tr>
								<th class="col-md-3">Onderdeel</th>
								<th class="col-md-4">Werkzaamheden</th>
								<th class="only-end-total-spec only-end-total-speccol-md-1"><span class="pull-right">Arbeidsuren</th>
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Arbeid</th>
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Materiaal</th>
								@if ($project->use_equipment)
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Overig</th>
								@endif
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
								<td class="col-md-3">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
								<td class="col-md-4">{{ $activity->activity_name }}</td>
								<td class="only-end-total-spec col-md-1"><span class="pull-right">{{ number_format(EstimateOverview::laborTotal($activity), 2, ",",".") }}</td>
								<td class="only-end-total-spec col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(EstimateOverview::laborActivity($activity), 2, ",",".") }}</span></td>
								<td class="only-end-total-spec col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(EstimateOverview::materialActivityProfit($activity, $mat_profit), 2, ",",".") }}</span></td>
								@if ($project->use_equipment)
								<td class="only-end-total-spec col-md-1"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::equipmentActivityProfit($activity, $equip_profit), 2, ",",".") }}</span></td>
								@endif
								<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::activityTotalProfit($activity, $mat_profit, $equip_profit), 2, ",",".") }} </td>
							</tr>
							@endforeach
							@endforeach
						</tbody>
					</table>

		<!-- 			<h4>Totalen stelposten</h4>
		 -->			<table class="table table-striped">
						<thead>
							<tr>
								<th class="col-md-7">&nbsp;</th>
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Arbeidsuren</span></th>
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Arbeid</span></th>
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Materiaal</span></th>
								@if ($project->use_equipment)
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Overig</span></th>
								@endif
								<th class="col-md-1"><span class="pull-right">Totaal</span></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="col-md-7"><strong>Totalen stelposten;</td>
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ number_format(EstimateOverview::laborSuperTotalAmount($project), 2, ",",".") }}</span></strong></td>
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::laborSuperTotal($project), 2, ",",".") }}</span></strong></td>
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::materialSuperTotal($project), 2, ",",".") }}</span></strong></td>
								@if ($project->use_equipment)
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::equipmentSuperTotal($project), 2, ",",".") }}</span></strong></td>
								@endif
								<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::superTotal($project), 2, ",",".") }}</span></strong></td>
							</tr>
						</tbody>
					</table>
				</div>
				<h5><strong>Weergegeven bedragen zijn exclusief BTW</strong></h5>
				<?#-- DECRIPTION ESTIM CON&SUBCON ENDT --?>
			</div>
			@endif

			@if ($project->use_less)
			<?#--PAGE HEADER START--?>
			<div class="white-row show-activity" class="show-subcon" class="show-all" style="display:none;">
				<div class="row">
					<div class="col-sm-6">
						{!! ($relation_self && $relation_self->logo_id) ? "<img src=\"/".Resource::find($relation_self->logo_id)->file_location."\" class=\"img-responsive\" />" : '' !!}
					</div>
					<div class="col-sm-6 text-right">
						<p>
							<h4><strong>{{ $project->project_name }}</strong></h4>
							<ul class="list-unstyled">
								<li><strong>Factuurdatum:</strong> <a href="#" class="invdate">Bewerk</a></li>
								@if (Auth::user()->pref_use_ct_numbering)
								<li><strong>Factuurnummer:</strong> {{ $invoice->invoice_code }}</li>
								<li><strong>Administratiefnummer:</strong> {{ $invoice->book_code }}</li>
								@else
								<li><strong>Factuurnummer:</strong> {{ $invoice->book_code }}</li>
								@endif
								<li><strong>Uw referentie:</strong> {{ $invoice->reference }}</li>
							</ul>
						</p>
					</div>
				</div>
				<hr class="margin-top10 margin-bottom10">
				<?#--PAGE HEADER END--?>

				<?#-- DECRIPTION LESS CON&SUBCON START --?>
				<div class="show-all">
					<h4>Minderwerk Aanneming</h4>
					<table class="table table-striped">
						<thead>
							<tr>
								<th class="col-md-3">Onderdeel</th>
								<th class="col-md-4">Werkzaamheden</th>
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Arbeidsuren</th>
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Arbeid</th>
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Materiaal</th>
								@if ($project->use_equipment)
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Overig</th>
								@endif
								<th class="col-md-1"><span class="pull-right">Totaal</th>
							</tr>
						</thead>
						<tbody>
							@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
							<?php $i = 0; ?>
							@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->get() as $activity)
							<?php
								if (!LessOverview::activityTotalProfit($activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip, $project))
									continue;
								$i++;
							?>
							<tr>
								<td class="col-md-3">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
								<td class="col-md-4">{{ $activity->activity_name }}</td>
								<td class="only-end-total-spec col-md-1"><span class="pull-right">{{ number_format(LessOverview::laborTotal($activity), 2, ",",".") }}</td>
								<td class="only-end-total-spec col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(LessOverview::laborActivity($activity, $project), 2, ",",".") }}</span></td>
								<td class="only-end-total-spec col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(LessOverview::materialActivityProfit($activity, $project->profit_calc_contr_mat), 2, ",",".") }}</span></td>
								@if ($project->use_equipment)
								<td class="only-end-total-spec col-md-1"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::equipmentActivityProfit($activity, $project->profit_calc_contr_equip), 2, ",",".") }}</span></td>
								@endif
								<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::activityTotalProfit($activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip, $project), 2, ",",".") }} </td>
							</tr>
							@endforeach
							@endforeach
							<tr>
								<td class="col-md-3"><strong>Totaal aanneming</strong></td>
								<td class="col-md-4">&nbsp;</td>
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ number_format(LessOverview::contrLaborTotalAmount($project), 2, ",",".") }}</span></strong></td>
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::contrLaborTotal($project), 2, ",",".") }}</span></strong></td>
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::contrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
								@if ($project->use_equipment)
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::contrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
								@endif
								<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::contrTotal($project), 2, ",",".") }}</span></strong></td>
							</tr>
						</tbody>
					</table>

					<h4>Minderwerk Onderaanneming</h4>
					<table class="table table-striped">
						<thead>
							<tr>
								<th class="col-md-3">Onderdeel</th>
								<th class="col-md-4">Werkzaamheden</th>
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Arbeidsuren</th>
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Arbeid</th>
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Materiaal</th>
								@if ($project->use_equipment)
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Overig</th>
								@endif
								<th class="col-md-1"><span class="pull-right">Totaal</th>
							</tr>
						</thead>
						<tbody>
							@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
							<?php $i = 0; ?>
							@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->get() as $activity)
							<?php
								if (!LessOverview::activityTotalProfit($activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip, $project))
									continue;
								$i++;
							?>
							<tr>
								<td class="col-md-3">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
								<td class="col-md-4">{{ $activity->activity_name }}</td>
								<td class="only-end-total-spec col-md-1"><span class="pull-right">{{ number_format(LessOverview::laborTotal($activity), 2, ",",".") }}</td>
								<td class="only-end-total-spec col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(LessOverview::laborActivity($activity, $project), 2, ",",".") }}</span></td>
								<td class="only-end-total-spec col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(LessOverview::materialActivityProfit($activity, $project->profit_calc_subcontr_mat), 2, ",",".") }}</span></td>
								@if ($project->use_equipment)
								<td class="only-end-total-spec col-md-1"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::equipmentActivityProfit($activity, $project->profit_calc_subcontr_equip), 2, ",",".") }}</span></td>
								@endif
								<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::activityTotalProfit($activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip, $project), 2, ",",".") }} </td>
							</tr>
							@endforeach
							@endforeach
							<tr>
								<td class="col-md-3"><strong>Totaal onderaanneming</strong></td>
								<td class="col-md-4">&nbsp;</td>
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ number_format(LessOverview::subcontrLaborTotalAmount($project), 2, ",",".") }}</span></strong></td>
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::subcontrLaborTotal($project), 2, ",",".") }}</span></strong></td>
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::subcontrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
								@if ($project->use_equipment)
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::subcontrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
								@endif
								<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::subcontrTotal($project), 2, ",",".") }}</span></strong></td>
								</tr>
						</tbody>
					</table>

					<h4>Totalen minderwerk</h4>
					<table class="table table-striped">
						<thead>
							<tr>
								<th class="col-md-3">&nbsp;</th>
								<th class="col-md-4">&nbsp;</th>
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Arbeidsuren</span></th>
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Arbeid</span></th>
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Materiaal</span></th>
								@if ($project->use_equipment)
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Overig</span></th>
								@endif
								<th class="col-md-1"><span class="pull-right">Totaal</span></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="col-md-3"><strong>Totaal minderwerk</td>
								<td class="col-md-4">&nbsp;</td>
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ number_format(LessOverview::laborSuperTotalAmount($project), 2, ",",".") }}</span></strong></td>
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::laborSuperTotal($project), 2, ",",".") }}</span></strong></td>
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::materialSuperTotal($project), 2, ",",".") }}</span></strong></td>
								@if ($project->use_equipment)
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::equipmentSuperTotal($project), 2, ",",".") }}</span></strong></td>
								@endif
								<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::superTotal($project), 2, ",",".") }}</span></strong></td>
							</tr>
						</tbody>
					</table>
				</div>
				<?#-- DECRIPTION LESS CON&SUBCON END --?>

				<?#-- DECRIPTION LESS TOTAL START --?>
				<div class="show-subcon" style="display:none;">
					<h4>Minderwerk</h4>
					<table class="table table-striped">
						<thead>
							<tr>
								<th class="col-md-3">Onderdeel</th>
								<th class="col-md-4">Werkzaamheden</th>
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Arbeidsuren</th>
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Arbeid</th>
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Materiaal</th>
								@if ($project->use_equipment)
								<th class="only-end-total-spec col-md-1"><span class="pull-right">Overig</th>
								@endif
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
								if (!LessOverview::activityTotalProfit($activity, $mat_profit, $equip_profit, $project))
									continue;
								$i++;
							?>
							<tr>
								<td class="col-md-3">{{ $i==1 ? $chapter->chapter_name :'' }}</td>
								<td class="col-md-4">{{ $activity->activity_name }}</td>
								<td class="only-end-total-spec col-md-1"><span class="pull-right">{{ number_format(LessOverview::laborTotal($activity), 2, ",",".") }}</td>
								<td class="only-end-total-spec col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(LessOverview::laborActivity($activity, $project), 2, ",",".") }}</span></td>
								<td class="only-end-total-spec col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(LessOverview::materialActivityProfit($activity, $mat_profit), 2, ",",".") }}</span></td>
								@if ($project->use_equipment)
								<td class="only-end-total-spec col-md-1"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::equipmentActivityProfit($activity, $equip_profit), 2, ",",".") }}</span></td>
								@endif
								<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::activityTotalProfit($activity, $mat_profit, $equip_profit, $project), 2, ",",".") }} </td>
							</tr>
							@endforeach
							@endforeach
						</tbody>
					</table>

		<!-- 			<h4>Totalen minderwerk</h4>
		 -->			<table class="table table-striped">
						<thead>
							<tr>
								<th class="col-md-3">Totalen minderwerk</th>
								<th class="col-md-4">&nbsp;</th>
								<th class="only-end-total-spec col-md-1"><strong><span class="pull-right">Arbeidsuren</span></strong></th>
								<th class="only-end-total-spec col-md-1"><strong><span class="pull-right">Arbeid</span></strong></th>
								<th class="only-end-total-spec col-md-1"><strong><span class="pull-right">Materiaal</span></strong></th>
								@if ($project->use_equipment)
								<th class="only-end-total-spec col-md-1"><strong><span class="pull-right">Overig</span></strong></th>
								@endif
								<th class="col-md-1"><strong><span class="pull-right">Totaal</span></strong></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="col-md-3">&nbsp;</td>
								<td class="col-md-4">&nbsp;</td>
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ number_format(LessOverview::laborSuperTotalAmount($project), 2, ",",".") }}</span></strong></td>
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::laborSuperTotal($project), 2, ",",".") }}</span></strong></td>
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::materialSuperTotal($project), 2, ",",".") }}</span></strong></td>
								@if ($project->use_equipment)
								<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::equipmentSuperTotal($project), 2, ",",".") }}</span></strong></td>
								@endif
								<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::superTotal($project), 2, ",",".") }}</span></strong></td>
							</tr>
						</tbody>
					</table>
				</div>
				<h5><strong>Weergegeven bedragen zijn exclusief BTW</strong></h5>
				<?#-- DECRIPTION LESS TOTAL END --?>
			</div>
			@endif

			@if ($project->use_more)
			<?#--PAGE HEADER START--?>
			<div class="white-row show-activity" class="show-subcon" class="show-all" style="display:none;">
			<div class="row">
				<div class="col-sm-6">
					{!! ($relation_self && $relation_self->logo_id) ? "<img src=\"/".Resource::find($relation_self->logo_id)->file_location."\" class=\"img-responsive\" />" : '' !!}
				</div>
				<div class="col-sm-6 text-right">
					<p>
						<h4><strong>{{ $project->project_name }}</strong></h4>
						<ul class="list-unstyled">
							<li><strong>Factuurdatum:</strong> {{ date("j M Y") }}</li>
							@if (Auth::user()->pref_use_ct_numbering)
							<li><strong>Factuurnummer:</strong> {{ $invoice->invoice_code }}</li>
							<li><strong>Administratiefnummer:</strong> {{ $invoice->book_code }}</li>
							@else
							<li><strong>Factuurnummer:</strong> {{ $invoice->book_code }}</li>
							@endif
						</ul>
					</p>
					</div>
			</div>
			<hr class="margin-top10 margin-bottom10">
			<?#--PAGE HEADER END--?>

			<?#-- DECRIPTION MORE CON&SUBCON START --?>
			<div class="show-all">
				<h4>Meerwerk Aanneming</h4>
				<table class="table table-striped">
					<thead>
						<tr>
							<th class="col-md-3">Onderdeel</th>
							<th class="col-md-4">Werkzaamheden</th>
							<th class="only-end-total-spec col-md-1"><span class="pull-right">Arbeidsuren</th>
							<th class="only-end-total-spec col-md-1"><span class="pull-right">Arbeid</th>
							<th class="only-end-total-spec col-md-1"><span class="pull-right">Materiaal</th>
							@if ($project->use_equipment)
							<th class="only-end-total-spec col-md-1"><span class="pull-right">Overig</th>
							@endif
							<th class="col-md-1"><span class="pull-right">Totaal</th>
						</tr>
					</thead>
					<tbody>
						@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
						<?php $i = 0; ?>
						@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->where('detail_id','=',Detail::where('detail_name','=','more')->first()->id)->get() as $activity)
						<?php $i++; ?>
						<tr>
							<td class="col-md-3">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
							<td class="col-md-4">{{ $activity->activity_name }}</td>
							<td class="only-end-total-spec col-md-1"><span class="pull-right">{{ number_format(MoreOverview::laborTotal($activity), 2, ",",".") }}</td>
							<td class="only-end-total-spec col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(MoreOverview::laborActivity($activity), 2, ",",".") }}</span></td>
							<td class="only-end-total-spec col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(MoreOverview::materialActivityProfit($activity, $project->profit_more_contr_mat), 2, ",",".") }}</span></td>
							@if ($project->use_equipment)
							<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::equipmentActivityProfit($activity, $project->profit_more_contr_equip), 2, ",",".") }}</span></td>
							@endif
							<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::activityTotalProfit($activity, $project->profit_more_contr_mat, $project->profit_more_contr_equip), 2, ",",".") }} </td>
						</tr>
						@endforeach
						@endforeach
						<tr>
							<td class="col-md-3"><strong>Totaal aanneming</strong></td>
							<td class="col-md-4">&nbsp;</td>
							<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ number_format(MoreOverview::contrLaborTotalAmount($project), 2, ",",".") }}</span></strong></td>
							<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::contrLaborTotal($project), 2, ",",".") }}</span></strong></td>
							<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::contrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
							@if ($project->use_equipment)
							<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::contrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
							@endif
							<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::contrTotal($project), 2, ",",".") }}</span></strong></td>
						</tr>
					</tbody>
				</table>

				<h4>Meerwerk Onderaanneming</h4>
				<table class="table table-striped">
					<thead>
						<tr>
							<th class="col-md-3">Onderdeel</th>
							<th class="col-md-4">Werkzaamheden</th>
							<th class="only-end-total-spec col-md-1"><span class="pull-right">Arbeidsuren</th>
							<th class="only-end-total-spec col-md-1"><span class="pull-right">Arbeid</th>
							<th class="only-end-total-spec col-md-1"><span class="pull-right">Materiaal</th>
							@if ($project->use_equipment)
							<th class="only-end-total-spec col-md-1"><span class="pull-right">Overig</th>
							@endif
							<th class="col-md-1"><span class="pull-right">Totaal</th>
						</tr>
					</thead>
					<tbody>
						@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
						<?php $i = 0; ?>
						@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->where('detail_id','=',Detail::where('detail_name','=','more')->first()->id)->get() as $activity)
						<?php $i++; ?>
						<tr>
							<td class="col-md-3">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
							<td class="col-md-4">{{ $activity->activity_name }}</td>
							<td class="only-end-total-spec col-md-1"><span class="pull-right">{{ number_format(MoreOverview::laborTotal($activity), 2, ",",".") }}</td>
							<td class="only-end-total-spec col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(MoreOverview::laborActivity($activity), 2, ",",".") }}</span></td>
							<td class="only-end-total-spec col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(MoreOverview::materialActivityProfit($activity, $project->profit_more_subcontr_mat), 2, ",",".") }}</span></td>
							@if ($project->use_equipment)
							<td class="only-end-total-spec col-md-1"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::equipmentActivityProfit($activity, $project->profit_more_subcontr_equip), 2, ",",".") }}</span></td>
							@endif
							<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::activityTotalProfit($activity, $project->profit_more_subcontr_mat, $project->profit_more_subcontr_equip), 2, ",",".") }} </td>
						</tr>
						@endforeach
						@endforeach
						<tr>
							<td class="col-md-3"><strong>Totaal onderaanneming</strong></td>
							<td class="col-md-4">&nbsp;</td>
							<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ number_format(MoreOverview::subcontrLaborTotalAmount($project), 2, ",",".") }}</span></strong></td>
							<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::subcontrLaborTotal($project), 2, ",",".") }}</span></strong></td>
							<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::subcontrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
							@if ($project->use_equipment)
							<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::subcontrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
							@endif
							<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::subcontrTotal($project), 2, ",",".") }}</span></strong></td>
						</tr>
					</tbody>
				</table>

				<h4>Totalen meerwerk</h4>
				<table class="table table-striped">
					<thead>
						<tr>
							<th class="col-md-3">&nbsp;</th>
							<th class="col-md-4">&nbsp;</th>
							<th class="only-end-total-spec col-md-1"><span class="pull-right">Arbeidsuren</span></th>
							<th class="only-end-total-spec col-md-1"><span class="pull-right">Arbeid</span></th>
							<th class="only-end-total-spec col-md-1"><span class="pull-right">Materiaal</span></th>
							@if ($project->use_equipment)
							<th class="only-end-total-spec ol-md-1"><span class="pull-right">Overig</span></th>
							@endif
							<th class="col-md-1"><span class="pull-right">Totaal</span></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="col-md-3"><strong>Totalen meerwerk</td>
							<td class="col-md-4">&nbsp;</td>
							<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ number_format(MoreOverview::laborSuperTotalAmount($project), 2, ",",".") }}</span></strong></td>
							<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::laborSuperTotal($project), 2, ",",".") }}</span></strong></td>
							<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::materialSuperTotal($project), 2, ",",".") }}</span></strong></td>
							@if ($project->use_equipment)
							<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::equipmentSuperTotal($project), 2, ",",".") }}</span></strong></td>
							@endif
							<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::superTotal($project), 2, ",",".") }}</span></strong></td>
						</tr>
					</tbody>
				</table>
			</div>
			<?#-- DECRIPTION MORE CON&SUBCON END --?>

			<?#-- DECRIPTION MORE TOTAL START --?>
			<div class="show-subcon" style="display:none;">
				<h4>Meerwerk</h4>
				<table class="table table-striped">
					<thead>
						<tr>
							<th class="col-md-3">Onderdeel</th>
							<th class="col-md-4">Werkzaamheden</th>
							<th class="only-end-total-spec col-md-1"><span class="pull-right">Arbeidsuren</th>
							<th class="only-end-total-spec col-md-1"><span class="pull-right">Arbeid</th>
							<th class="only-end-total-spec col-md-1"><span class="pull-right">Materiaal</th>
							@if ($project->use_equipment)
							<th class="only-end-total-spec col-md-1"><span class="pull-right">Overig</th>
							@endif
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
							<td class="col-md-3">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
							<td class="col-md-4">{{ $activity->activity_name }}</td>
							<td class="only-end-total-spec col-md-1"><span class="pull-right">{{ number_format(MoreOverview::laborTotal($activity), 2, ",",".") }}</td>
							<td class="only-end-total-spec col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(MoreOverview::laborActivity($activity), 2, ",",".") }}</span></td>
							<td class="only-end-total-spec col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(MoreOverview::materialActivityProfit($activity, $mat_profit), 2, ",",".") }}</span></td>
							@if ($project->use_equipment)
							<td class="only-end-total-spec col-md-1"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::equipmentActivityProfit($activity, $equip_profit), 2, ",",".") }}</span></td>
							@endif
							<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::activityTotalProfit($activity, $mat_profit, $equip_profit), 2, ",",".") }} </td>
						</tr>
						@endforeach
						@endforeach
					</tbody>
				</table>

	<!-- 			<h4>Totalen meerwerk</h4>
	 -->			<table class="table table-striped">
					<thead>
						<tr>
							<th class="col-md-3">&nbsp;</th>
							<th class="col-md-4">&nbsp;</th>
							<th class="only-end-total-spec col-md-1"><span class="pull-right">Arbeidsuren</span></th>
							<th class="only-end-total-spec col-md-1"><span class="pull-right">Arbeid</span></th>
							<th class="only-end-total-spec col-md-1"><span class="pull-right">Materiaal</span></th>
							@if ($project->use_equipment)
							<th class="only-end-total-spec col-md-1"><span class="pull-right">Overig</span></th>
							@endif
							<th class="col-md-1"><span class="pull-right">Totaal</span></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="col-md-3"><strong>Totalen meerwerk</td>
							<td class="col-md-4">&nbsp;</td>
							<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ number_format(MoreOverview::laborSuperTotalAmount($project), 2, ",",".") }}</span></strong></td>
							<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::laborSuperTotal($project), 2, ",",".") }}</span></strong></td>
							<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::materialSuperTotal($project), 2, ",",".") }}</span></strong></td>
							@if ($project->use_equipment)
							<td class="only-end-total-spec col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::equipmentSuperTotal($project), 2, ",",".") }}</span></strong></td>
							@endif
							<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::superTotal($project), 2, ",",".") }}</span></strong></td>
						</tr>
					</tbody>
				</table>
			</div>
			<h5><strong>Weergegeven bedragen zijn exclusief BTW</strong></h5>
			<?#-- DECRIPTION MORE TOTAL END --?>
		</div>
		@endif

		<div class="white-row show-note" class="show-all" style="display:none;">
			<?#--PAGE HEADER START--?>
			<div class="row">
				<div class="col-sm-6">
					{!! ($relation_self && $relation_self->logo_id) ? "<img src=\"/".Resource::find($relation_self->logo_id)->file_location."\" class=\"img-responsive\" />" : '' !!}
				</div>
				<div class="col-sm-6 text-right">
					<p>
						<h4><strong>{{ $project->project_name }}</strong></h4>
						<ul class="list-unstyled">
							<li><strong>Factuurdatum:</strong> {{ date("j M Y") }}</li>
							@if (Auth::user()->pref_use_ct_numbering)
							<li><strong>Factuurnummer:</strong> {{ $invoice->invoice_code }}</li>
							<li><strong>Administratiefnummer:</strong> {{ $invoice->book_code }}</li>
							@else
							<li><strong>Factuurnummer:</strong> {{ $invoice->book_code }}</li>
							@endif
						</ul>
					</p>
					</div>
			</div>
			<hr class="margin-top10 margin-bottom10">
			<?#--PAGE HEADER END--?>

			<?#-- DECRIPTION CON&SUBCON START --?>
			<div class="show-all" style="display:none;">
				<h4>Omschrijving werkzaamheden aanneming</h4>
				<table class="table table-striped">
					<thead>
						<tr>
							<th class="col-md-3">Onderdeel</th>
							<th class="col-md-4">Werkzaamheden</th>
							<th class="col-md-5"><span>Omschrijving</th>
						</tr>
					</thead>
					<tbody>
						@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
						<?php $i = 0; ?>
						@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->get() as $activity)
						<?php $i++; ?>
						<tr>
							<td class="col-md-3">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
							<td class="col-md-4">{{ $activity->activity_name }}</td>
							<td class="col-md-5"><span>{!! $activity->note !!}</td>
						</tr>
						@endforeach
						@endforeach
					</tbody>
				</table>

				<h4>Omschrijving werkzaamheden onderaanneming</h4>
				<table class="table table-striped">
					<thead>
						<tr>
							<th class="col-md-3">Onderdeel</th>
							<th class="col-md-4">Werkzaamheden</th>
							<th class="col-md-5"><span>Omschrijving</th>
						</tr>
					</thead>
					<tbody>
						@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
						<?php $i = 0; ?>
						@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->get() as $activity)
						<?php $i++; ?>
						<tr>
							<td class="col-md-3">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
							<td class="col-md-4">{{ $activity->activity_name }}</td>
							<td class="col-md-5"><span>{!! $activity->note !!}</td>
						</tr>
						@endforeach
						@endforeach
					</tbody>
				</table>
			</div>
			<?#-- DECRIPTION CON&SUBCON END --?>

			<?#-- DECRIPTION TOTAL START --?>
			<div class="show-subcon">
				<h4>Omschrijving werkzaamheden</h4>
				<table class="table table-striped">
					<thead>
						<tr>
							<th class="col-md-3">Onderdeel</th>
							<th class="col-md-4">Werkzaamheden</th>
							<th class="col-md-5"><span>Omschrijving</th>
						</tr>
					</thead>
					<tbody>
						@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
						<?php $i = 0; ?>
						@foreach (Activity::where('chapter_id','=', $chapter->id)->get() as $activity)
						<?php $i++; ?>
						<tr>
							<td class="col-md-3">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
							<td class="col-md-4">{{ $activity->activity_name }}</td>
							<td class="col-md-5"><span>{!! $activity->note !!}</td>
						</tr>
						@endforeach
						@endforeach
					</tbody>
				</table>
			</div>
			<?#-- DECRIPTION TOTAL END --?>
		</div>
		</form>

		<?#-- INVOICE FOOTER --?>
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
							echo '<button class="btn btn-primary osave">Voorbeeld</button>&nbsp;';
						} else if (!$prev) {
							echo '<button class="btn btn-primary osave">Voorbeeld</button>&nbsp;';
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
@stop

<?php } ?>
