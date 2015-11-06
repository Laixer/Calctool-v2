<?php

use \Calctool\Models\Project;
use \Calctool\Models\Relation;
use \Calctool\Models\Offer;
use \Calctool\Calculus\CalculationEndresult;
use \Calctool\Models\Contact;
use \Calctool\Models\ProjectType;
use \Calctool\Models\DeliverTime;
use \Calctool\Models\Valid;
use \Calctool\Models\Chapter;
use \Calctool\Models\Activity;
use \Calctool\Models\Part;
use \Calctool\Calculus\CalculationOverview;
use \Calctool\Models\Resource;


use \Calctool\Http\Controllers\OfferController;



$common_access_error = false;
$project = Project::find(Route::Input('project_id'));
if (!$project || !$project->isOwner()) {
	$common_access_error = true;
} else {
	$relation = Relation::find($project->client_id);
	$relation_self = Relation::find(Auth::user()->self_id);
	if ($relation_self)
		$contact_self = Contact::where('relation_id','=',$relation_self->id);
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
		$("[name='include-tax']").bootstrapSwitch().on('switchChange.bootstrapSwitch', function(event, state) {
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
	                $(this).find("th").eq(4).show();
	                $(this).find("th").eq(5).show();
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
	                $(this).find("th").eq(4).hide();
	                $(this).find("th").eq(5).hide();
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
		$("[name='display-worktotals']").bootstrapSwitch().on('switchChange.bootstrapSwitch', function(event, state) {
		  if (state) {
		  	$("[name='display-specification']").bootstrapSwitch('toggleDisabled');
		  	$('.show-activity').show();
		  } else {
		 	$("[name='display-specification']").bootstrapSwitch('toggleDisabled');
			$('.show-activity').hide();
		  }
		});

		$("[name='only-totals']").bootstrapSwitch().on('switchChange.bootstrapSwitch', function(event, state) {
		  if (state) {
		  	$('.show-activity').show();
		  	$("[name='seperate-subcon']").bootstrapSwitch('toggleDisabled');
		  } else {
		  	$("[name='seperate-subcon']").bootstrapSwitch('toggleDisabled');
			$('.show-activity').hide();
		  }
		});

		$("[name='seperate-subcon']").bootstrapSwitch().on('switchChange.bootstrapSwitch', function(event, state) {
		  if (state) {
		  	$('.show-all').show();
		  	$('.show-totals').hide();
		  } else {
		  	$('.show-all').hide();
		  	$('.show-totals').show();
		  }
		});
		$("[name='display-description']").bootstrapSwitch().on('switchChange.bootstrapSwitch', function(event, state) {
		  if (state) {
		  	$('.show-note').show();
		  } else {
			$('.show-note').hide();
		  }
		});
		$("[name='only-totals']").bootstrapSwitch().on('switchChange.bootstrapSwitch', function(event, state) {
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
		$("[name='display-specification']").bootstrapSwitch().on('switchChange.bootstrapSwitch', function(event, state) {
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
				$(".show-downpayment").show();
				$tpayment = true;
			} else {
				$("#amount").prop('disabled', true);
				$(".show-downpayment").hide();
				$tpayment = false;
			}

		});
		$('#terms').change(function(e){
			var q = $('#terms').val();
			if($.isNumeric(q)&&(q>1)) {
				$('.noterms').show('slow');
			} else {
				$('.noterms').hide('slow');
			}

		});
		$('#termModal').on('hidden.bs.modal', function() {
			var q = $('#terms').val();
			if($.isNumeric(q)&&(q>1)&&(q<=50)) {
				if($('input[name="toggle-payment"]').prop('checked'))
					$('#condition-text').html('Indien opdracht wordt verstrekt, wordt gefactureerd in '+q+' termijnen, waarvan het eerste termijn een aanbetaling betreft a &euro; ' +$('#amount').val()+'.');
				else
					$('#condition-text').html('Indien opdracht wordt verstrekt, wordt gefactureerd in '+q+' termijnen.');
			} else {
				$('#condition-text').text('Indien opdracht gegund wordt, ontvangt u één eindfactuur.');
			}

		});
		$('.osave').click(function(e){
			e.preventDefault();
			$('#frm-offer').submit();
		});
		$('#adressing').text($('#to_contact option:selected').text());
		$('#to_contact').change(function(e){
			$('#adressing').text($('#to_contact option:selected').text());
		});
		$('.offdate').datepicker().on('changeDate', function(e){
			$('.offdate').datepicker('hide');
			$('#offdateval').val(e.date.toLocaleString());
			$('.offdate').text(e.date.getDate() + "-" + (e.date.getMonth() + 1)  + "-" + e.date.getFullYear());
		});
		@if ($offer_last && $offer_last->offer_make)
		$('.offdate').text("{{ date('d-m-Y', strtotime($offer_last->offer_make)) }}");

		@if (!$offer_last->include_tax)
			$("[name='include-tax']").bootstrapSwitch('toggleState');
		@endif

		@if (!$offer_last->only_totals)
			$("[name='only-totals']").bootstrapSwitch('toggleState');
		@endif

		@if ($offer_last->seperate_subcon)
			$("[name='seperate-subcon']").bootstrapSwitch('toggleState');
		@endif

		@if ($offer_last->display_worktotals)
			$("[name='display-worktotals']").bootstrapSwitch('toggleState');
		@endif

		@if ($offer_last->display_specification)
			$("[name='display-specification']").bootstrapSwitch('toggleState');
		@endif

		@if ($offer_last->display_description)
			$("[name='display-description']").bootstrapSwitch('toggleState');
		@endif

		@endif
	});
	</script>
	<div id="wrapper">

	<section class="container printable fix-footer-bottom">

		@include('calc.wizard', array('page' => 'offer'))

		@if(!$relation_self)
		<div class="alert alert-danger">
			<i class="fa fa-frown-o"></i>
			Geen bedrijfsgegevens bekend. Vul de <a href="/mycompany">bedrijfsgegevens</a> aan.
		</div>
		@else

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
		<?php if (!$project->project_close) { ?>
		<?php if ($offer_last) { ?>
		<?php if (!$offer_last->offer_finish) { ?>
		<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Opties</a>
		<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#termModal">Termijnen</a>
		<button class="btn btn-primary osave">Opslaan</button>
		<?php } ?>
		<?php }else{ ?>
		<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Opties</a>
		<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#termModal">Termijnen</a>
		@if (CalculationEndresult::totalProject($project))
		<button class="btn btn-primary osave">Opslaan</button>
		@endif
		<?php } ?>
		<?php } ?>
	</div>

	<div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel2">Offerte versies</h4>
				</div>

				<div class="modal-body">
					<div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									<th>Offerte</th>
									<th>Datum</th>
								</tr>
							</thead>
							<tbody>
								@foreach (Offer::where('project_id','=',$project->id)->orderBy('created_at', 'desc')->get() as $offer)
								<tr>
									<td><a href="/{{ Resource::find($offer->resource_id)->file_location }}">Offerteversienummer {{ $offer->id }}</a></td>
									<td>{{ date('d-m-Y', strtotime(DB::table('offer')->select('created_at')->where('id','=',$offer->id)->get()[0]->created_at)) }}</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>

				</div>

				<div class="modal-footer">
					<button class="btn btn-default" data-dismiss="modal">Close</button>
				</div>

			</div>
		</div>
	</div>

	<h2><strong>Offerte</strong></h2>
	<form method="POST" id="frm-offer">
		{!! csrf_field() !!}
			<div class="modal fade" id="termModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">

						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title" id="myModalLabel2">Betalingstermijnen</h4>
						</div>

						<div class="modal-body">
							<div class="form-horizontal">
								<div class="form-group">
									<div class="col-md-6">
										<label>Aantal betalingstermijnen</label>
									</div>
									<div class="col-md-6">
										<input value="{{ ($offer_last ? $offer_last->invoice_quantity : '1') }}" name="terms" id="terms" min="1" max="50" type="number" class="form-control" />
									</div>
								</div>
							</div>
							<div class="form-horizontal">
								<div class="form-horizontal noterms" {{ ($offer_last && $offer_last->invoice_quantity >1 ? '' : 'style="display:none;"') }} >
									<div class="col-md-6">
									  <div class="form-group">
									  	<label>Aanbetaling toepassen</label>
									  </div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
									      <div class="checkbox">
									        <label>
									          <input {{ ($offer_last ? ($offer_last->downpayment ? 'checked' : '') : '') }} name="toggle-payment" type="checkbox">
									        </label>
										  </div>
										</div>
									</div>

									<div class="col-md-12 show-downpayment">
										<table id="tbl-term" class="table table-hover">
										<thead>
											<tr>
												<th>Termijnnummer</th>
												<th>Bedrag</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>Aanbetalingsbedrag</td>
												<td><input {{ ($offer_last ? ($offer_last->downpayment ? '' : 'disabled') : 'disabled') }} type="text" value="{{ ($offer_last ? number_format($offer_last->downpayment_amount, 2, ",",".") : '') }}" id="amount" name="amount" class="form-control-sm-number" /></td>
											</tr>
										</tbody>
										</table>
										<span>Indien aanbetaling wordt ingesteld wordt dit verrekend als het 1e betalingstermijn. Eventuele navolgende betalingstermijnen worden gespecficieerd op de factuurpagina.</span>
									</div>
									
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>

			<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">

						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title" id="myModalLabel">Offerte opties</h4>
						</div>

						<div class="modal-body">
							<div class="form-horizontal">

								 <div class="form-group">
								    <div class="col-sm-offset-0 col-sm-12">
								      <div class="checkbox">
								        <label>
								          <input name="include-tax" type="checkbox" checked> BTW bedragen weergeven
								        </label>
								      </div>
								    </div>
								  </div>
								  <div class="form-group">
								    <div class="col-sm-offset-0 col-sm-12">
								      <div class="checkbox">
								        <label>
								          <input name="only-totals" type="checkbox" checked> Alleen het totale offertebedrag weergeven<br>
								        </label>
								      </div>
								    </div>
								  </div>
								   <div class="form-group">
								    <div class="col-sm-offset-0 col-sm-12">
								      <div class="checkbox">
								        <label>
								          <input name="seperate-subcon" type="checkbox" disabled> Onderaanneming apart weergeven
								        </label>
								      </div>
								    </div>
								  </div>
								  <br>
								  <strong>De volgende opties worden als bijlage bijgesloten bij de offerte</strong>
								  <br>
								  <br>
								  <div class="form-group">
								    <div class="col-sm-offset-0 col-sm-12">
								      <div class="checkbox">
								        <label>
								          <input name="display-worktotals" type="checkbox"> Kostenoverizicht per werkzaamheid specificeren
								        </label>
								      </div>
								    </div>
								  </div>
								  <div class="form-group">
								    <div class="col-sm-offset-0 col-sm-12">
								      <div class="checkbox">
								        <label>
								          <input name="display-specification" type="checkbox" disabled> Aanvullend specificeren op arbeid, materiaal en materieel
								        </label>
								      </div>
								    </div>
								  </div>
								  <div class="form-group">
								    <div class="col-sm-offset-0 col-sm-12">
								      <div class="checkbox">
								        <label>
								          <input name="display-description" type="checkbox"> Omschrijving werkzaamheden weergeven
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
						<ul class="list-unstyled">
					</p>
				</div>
			</div>
		</header>
		<hr class="margin-top10 margin-bottom10">
		<!--PAGE HEADER MASTER END-->

 		<!--ADRESSING START-->
		<div class="row">
			<div class="col-sm-6">
				<ul class="list-unstyled">
					<li>{{ $relation->company_name }}</li>
					<li>T.a.v.
						@if ($offer_last && $offer_last->offer_finish)
						{{ Contact::find($offer_last->to_contact_id)->firstname . ' ' . Contact::find($offer_last->to_contact_id)->lastname }}
						@else
					<select name="to_contact" id="to_contact">
						@foreach (Contact::where('relation_id','=',$relation->id)->get() as $contact)
						<option {{ $offer_last ? ($offer_last->to_contact_id==$contact->id ? 'selected' : '') : '' }} value="{{ $contact->id }}">{{ $contact->firstname . ' ' . $contact->lastname }}</option>
						@endforeach
					</select>
					@endif
					</li>
					<li>{{ $relation->address_street . ' ' . $relation->address_number }}<br /> {{ $relation->address_postal . ', ' . $relation->address_city }}</li>
				</ul>
			</div>
			<div class="col-sm-2"></div>
			<div class="col-sm-4 text-right">
				<h4><strong>OFFERTE</strong></h4>
				<ul class="list-unstyled">
					<li><strong>Projectnaam:</strong> {{ $project->project_name }}</li>
					<li><strong>Offertedatum:</strong> <a href="#" class="offdate">Bewerk</a></li>
					<li><strong>Offertenummer:</strong> {{ OfferController::getOfferCode($project->id) }}</li>
					<li>&nbsp;</li>
					<li>&nbsp;</li>
					<input type="hidden" id="offdateval" name="offdateval" value="{{ $offer_last ? $offer_last->offer_make : '' }}" />
				</ul>
			</div>
		</div>
		<!--ADRESSING END-->

		<!--DECRIPTION-->
		<div class="row">
			<div class="col-sm-6">
			Geachte
		@if ($offer_last && $offer_last->offer_finish)
		{{ Contact::find($offer_last->to_contact_id)->firstname . ' ' . Contact::find($offer_last->to_contact_id)->lastname }}
		@else
		<span id="adressing"></span>
		@endif
		,

		</div>
		</div>
		<br>
		<div class="row">
			<div class="col-sm-12">
			@if ($offer_last && $offer_last->offer_finish)
			{{ $offer_last->description }}
			@else
			<textarea name="description" id="description" rows="5" maxlength="500" class="form-control">{{ ($offer_last ? $offer_last->description : Auth::user()->pref_offer_description) }}</textarea>
			@endif
			</div>
		</div>
		<br>
		<!--DECRIPTION END-->

		<!--CONTENT, CON & SUBCON START-->
			<div class="show-all" style="display:none;">
				<h4 class="only-total">Specificatie offerte</h4>
				<div class="only-total"><strong><u>AANNEMING</u></strong></div>
				<table class="table table-striped hide-btw1">
					<thead>
						<tr>
							<th class="col-md-4">&nbsp;</th>
							<th class="col-md-1">Uren</th>
							<th class="col-md-2">Bedrag (excl. BTW)</th>
							<th class="col-md-1">&nbsp;</th>
							<th class="col-md-1">BTW</th>
							<th class="col-md-2">BTW bedrag</th>
							<th class="col-md-1">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						@if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
						<tr>
							<td class="col-md-4">Arbeidskosten</td>
							<td class="col-md-1">{{ ''.number_format(CalculationEndresult::conCalcLaborActivityTax1($project), 2, ",",".") }}</td>
							<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1">21%</td>
							<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax1AmountTax($project), 2, ",",".") }}</td>
							<td class="col-md-1">&nbsp;</td>
						</tr>
						<tr>
							<td class="col-md-4">&nbsp;</td>
							<td class="col-md-1">{{ ''.number_format(CalculationEndresult::conCalcLaborActivityTax2($project), 2, ",",".") }}</td>
							<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1">6%</td>
							<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax2AmountTax($project), 2, ",",".") }}</td>
							<td class="col-md-1">&nbsp;</td>
						</tr>
						@else
						<tr>
							<td class="col-md-4">Arbeidskosten</td>
							<td class="col-md-1">{{ ''.number_format(CalculationEndresult::conCalcLaborActivityTax3($project), 2, ",",".") }}</td>
							<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1">0%</td>
							<td class="col-md-2">&nbsp;</td>
							<td class="col-md-1">&nbsp;</td>
						</tr>
						@endif

						@if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
						<tr>
							<td class="col-md-4">Materiaalkosten</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1">21%</td>
							<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax1AmountTax($project), 2, ",",".") }}</td>
							<td class="col-md-1">&nbsp;</td>
						</tr>
						<tr>
							<td class="col-md-4">&nbsp;</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1">6%</td>
							<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax2AmountTax($project), 2, ",",".") }}</td>
							<td class="col-md-1">&nbsp;</td>
						</tr>
						@else
						<tr>
							<td class="col-md-4">Materiaalkosten</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1">0%</td>
							<td class="col-md-2">&nbsp;</td>
							<td class="col-md-1">&nbsp;</td>
						</tr>
						@endif

						@if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
						<tr>
							<td class="col-md-4">Materieelkosten</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1">21%</td>
							<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax1AmountTax($project), 2, ",",".") }}</td>
							<td class="col-md-1">&nbsp;</td>
						</tr>
						<tr>
							<td class="col-md-4">&nbsp;</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1">6%</td>
							<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax2AmountTax($project), 2, ",",".") }}</td>
							<td class="col-md-1">&nbsp;</td>
						</tr>
						@else
						<tr>
							<td class="col-md-4">Materieelkosten</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1">0%</td>
							<td class="col-md-2">&nbsp;</td>
							<td class="col-md-1">&nbsp;</td>
						</tr>
						@endif

						<tr>
							<td class="col-md-4"><strong>Totaal aanneming</strong></td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalContracting($project), 2, ",",".") }}</strong></td>
							<td class="col-md-2">&nbsp;</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalContractingTax($project), 2, ",",".") }}</strong></td>
							<td class="col-md-2">&nbsp;</td>
						</tr>
					</tbody>
				</table>

				<div class="only-total"><strong><u>ONDERAANNEMING</u></strong></div>
				<table class="table table-striped hide-btw1">
					<thead>
						<tr>
							<th class="col-md-4">&nbsp;</th>
							<th class="col-md-1">Uren</th>
							<th class="col-md-2">Bedrag (excl. BTW)</th>
							<th class="col-md-1">&nbsp;</th>
							<th class="col-md-1">BTW</th>
							<th class="col-md-2">BTW bedrag</th>
							<th class="col-md-1">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						@if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
						<tr>
							<td class="col-md-4">Arbeidskosten</td>
							<td class="col-md-1">{{ ' '.number_format(CalculationEndresult::subconCalcLaborActivityTax1($project), 2, ",",".") }}</td>
							<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1">21%</td>
							<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax1AmountTax($project), 2, ",",".") }}</td>
							<td class="col-md-1">&nbsp;</td>
						</tr>
						<tr>
							<td class="col-md-4">&nbsp;</td>
							<td class="col-md-1">{{ ' '.number_format(CalculationEndresult::subconCalcLaborActivityTax2($project), 2, ",",".") }}</td>
							<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1">6%</td>
							<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax2AmountTax($project), 2, ",",".") }}</td>
							<td class="col-md-1">&nbsp;</td>
						</tr>
						@else
						<tr>
							<td class="col-md-4">Arbeidskosten</td>
							<td class="col-md-1">{{ ''.number_format(CalculationEndresult::subconCalcLaborActivityTax3($project), 2, ",",".") }}</td>
							<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1">0%</td>
							<td class="col-md-2">&nbsp;</td>
							<td class="col-md-1">&nbsp;</td>
						</tr>
						@endif

						@if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
						<tr>
							<td class="col-md-4">Materiaalkosten</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1">21%</td>
							<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcMaterialActivityTax1AmountTax($project), 2, ",",".") }}</td>
							<td class="col-md-1">&nbsp;</td>
						</tr>
						<tr>
							<td class="col-md-4">&nbsp;</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1">6%</td>
							<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcMaterialActivityTax2AmountTax($project), 2, ",",".") }}</td>
							<td class="col-md-1">&nbsp;</td>
						</tr>
						@else
						<tr>
							<td class="col-md-4">Materiaalkosten</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1">0%</td>
							<td class="col-md-2">&nbsp;</td>
							<td class="col-md-1">&nbsp;</td>
						</tr>
						@endif

						@if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
						<tr>
							<td class="col-md-4">Materieelkosten</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1">21%</td>
							<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcEquipmentActivityTax1AmountTax($project), 2, ",",".") }}</td>
							<td class="col-md-1">&nbsp;</td>
						</tr>
						<tr>
							<td class="col-md-4">&nbsp;</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1">6%</td>
							<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcEquipmentActivityTax2AmountTax($project), 2, ",",".") }}</td>
							<td class="col-md-1">&nbsp;</td>
						</tr>
						@else
						<tr>
							<td class="col-md-4">Materieelkosten</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1">0%</td>
							<td class="col-md-2">&nbsp;</td>
							<td class="col-md-1">&nbsp;</td>
						</tr>
						@endif

						<tr>
							<td class="col-md-4"><strong>Totaal onderaanneming</strong></td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
							<td class="col-md-2">&nbsp;</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalSubcontractingTax($project), 2, ",",".") }}</strong></td>
							<td class="col-md-2">&nbsp;</td>
						</tr>
					</tbody>
				</table>

				<h4>Totalen Offerte</h4>
				<table class="table table-striped hide-btw2">
					<?# -- table head -- ?>
					<thead>
						<tr>
							<th class="col-md-5">&nbsp;</th>
							<th class="col-md-2">Bedrag (excl. BTW)</th>
							<th class="col-md-1">&nbsp;</th>
							<th class="col-md-1">&nbsp;</th>
							<th class="col-md-1">BTW bedrag</th>
							<th class="col-md-2"><span class="pull-right">Bedrag (incl. BTW)</span></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="col-md-5">Calculatief te offreren (excl. BTW)</td>
							<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::totalProject($project), 2, ",",".") }}</td>
							<th class="col-md-1">&nbsp;</th>
							<th class="col-md-1">&nbsp;</th>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-2">&nbsp;</td>
						</tr>
						@if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
						<tr>
							<td class="col-md-5">BTW bedrag 21%</td>
							<td class="col-md-2">&nbsp;</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::totalContractingTax1($project)+CalculationEndresult::totalSubcontractingTax1($project), 2, ",",".") }}</td>
							<td class="col-md-2">&nbsp;</td>
						</tr>
						<tr>
							<td class="col-md-5">BTW bedrag 6%</td>
							<td class="col-md-2">&nbsp;</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::totalContractingTax2($project)+CalculationEndresult::totalSubcontractingTax2($project), 2, ",",".") }}</td>
							<td class="col-md-2">&nbsp;</td>
						</tr>
						@endif
						<tr>
							<td class="col-md-5"><strong>Calculatief te offreren (Incl. BTW)</strong></td>
							<td class="col-md-2">&nbsp;</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-2"><strong class="pull-right">{{ '&euro; '.number_format(CalculationEndresult::superTotalProject($project), 2, ",",".") }}</strong></td>
						</tr>
					</tbody>
				</table>
			</div>
			<!--CONTENT, CON & SUBCON END-->

			<!--CONTENT TOTAL START-->
			<div class="show-totals">
			<h4 class="only-total">Specificatie offerte</h4>
				<table class="table table-striped hide-btw1">
					<thead>
						<tr>
							<th class="col-md-4">&nbsp;</th>
							<th class="col-md-1">Uren</th>
							<th class="col-md-2">Bedrag (excl. BTW)</th>
							<th class="col-md-1">&nbsp;</th>
							<th class="col-md-1">BTW</th>
							<th class="col-md-1">BTW bedrag</th>
							<th class="col-md-2">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						@if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
						<tr>
							<td class="col-md-4">Arbeidskosten</td>
							<td class="col-md-1">{{ ''.number_format(CalculationEndresult::conCalcLaborActivityTax1($project)+CalculationEndresult::subconCalcLaborActivityTax1($project), 2, ",",".") }}</td>
							<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax1Amount($project)+CalculationEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1">21%</td>
							<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax1AmountTax($project)+CalculationEndresult::subconCalcLaborActivityTax1AmountTax($project), 2, ",",".") }}</td>
							<td class="col-md-2">&nbsp;</td>
						</tr>
						<tr>
							<td class="col-md-4">&nbsp;</td>
							<td class="col-md-1">{{ ''.number_format(CalculationEndresult::conCalcLaborActivityTax2($project)+CalculationEndresult::subconCalcLaborActivityTax2($project), 2, ",",".") }}</td>
							<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax2Amount($project)+CalculationEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1">6%</td>
							<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax2AmountTax($project)+CalculationEndresult::subconCalcLaborActivityTax2AmountTax($project), 2, ",",".") }}</td>
							<td class="col-md-2">&nbsp;</td>
						</tr>
						@else
						<tr>
							<td class="col-md-4">Arbeidskosten</td>
							<td class="col-md-1">{{ ''.number_format(CalculationEndresult::conCalcLaborActivityTax3($project)+CalculationEndresult::subconCalcLaborActivityTax3($project), 2, ",",".") }}</td>
							<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax3Amount($project)+CalculationEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1">0%</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-2">&nbsp;</td>
						</tr>
						@endif

						@if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
						<tr>
							<td class="col-md-4">Materiaalkosten</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax1Amount($project)+CalculationEndresult::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1">21%</td>
							<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax1AmountTax($project)+CalculationEndresult::subconCalcMaterialActivityTax1AmountTax($project), 2, ",",".") }}</td>
							<td class="col-md-2">&nbsp;</td>
						</tr>
						<tr>
							<td class="col-md-4">&nbsp;</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax2Amount($project)+CalculationEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1">6%</td>
							<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax2AmountTax($project)+CalculationEndresult::subconCalcMaterialActivityTax2AmountTax($project), 2, ",",".") }}</td>
							<td class="col-md-2">&nbsp;</td>
						</tr>
						@else
						<tr>
							<td class="col-md-4">Materiaalkosten</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax3Amount($project)+CalculationEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1">0%</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-2">&nbsp;</td>
						</tr>
						@endif

						@if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
						<tr>
							<td class="col-md-4">Materieelkosten</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax1Amount($project)+CalculationEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1">21%</td>
							<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax1AmountTax($project)+CalculationEndresult::subconCalcEquipmentActivityTax1AmountTax($project), 2, ",",".") }}</td>
							<td class="col-md-2">&nbsp;</td>
						</tr>
						<tr>
							<td class="col-md-4">&nbsp;</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax2Amount($project)+CalculationEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1">6%</td>
							<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax2AmountTax($project)+CalculationEndresult::subconCalcEquipmentActivityTax2AmountTax($project), 2, ",",".") }}</td>
							<td class="col-md-2">&nbsp;</td>
						</tr>
						@else
						<tr>
							<td class="col-md-4">Materieelkosten</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax3Amount($project)+CalculationEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1">0%</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-2">&nbsp;</td>
						</tr>
						@endif

						<tr>
							<td class="col-md-4"><strong>Totaal Aanneming </strong></td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-2"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalContracting($project)+CalculationEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalContractingTax($project)+CalculationEndresult::totalSubcontractingTax($project), 2, ",",".") }}</strong></td>
							<td class="col-md-2">&nbsp;</td>
						</tr>
					</tbody>
				</table>

				<h4>Totalen Offerte</h4>
				<table class="table table-striped hide-btw2">
					<thead>
						<tr>
							<th class="col-md-5">&nbsp;</th>
							<th class="col-md-2">Bedrag (excl. BTW)</th>
							<th class="col-md-1">&nbsp;</th>
							<th class="col-md-1">&nbsp;</th>
							<th class="col-md-1">BTW bedrag</th>
							<th class="col-md-2"><span class="pull-right">Bedrag (incl. BTW)</span></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="col-md-5">Calculatief te offreren (excl. BTW)</td>
							<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::totalProject($project), 2, ",",".") }}</td>
							<th class="col-md-1">&nbsp;</th>
							<th class="col-md-1">&nbsp;</th>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-2">&nbsp;</td>
						</tr>
						@if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
						<tr>
							<td class="col-md-5">BTW bedrag 21%</td>
							<td class="col-md-2">&nbsp;</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::totalContractingTax1($project)+CalculationEndresult::totalSubcontractingTax1($project), 2, ",",".") }}</td>
							<td class="col-md-2">&nbsp;</td>
						</tr>
						<tr>
							<td class="col-md-5">BTW bedrag 6%</td>
							<td class="col-md-2">&nbsp;</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::totalContractingTax2($project)+CalculationEndresult::totalSubcontractingTax2($project), 2, ",",".") }}</td>
							<td class="col-md-2">&nbsp;</td>
						</tr>
						@endif
						<tr>
							<td class="col-md-5"><strong>Calculatief te offreren (Incl. BTW)</strong></td>
							<td class="col-md-2">&nbsp;</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-2"><strong class="pull-right">{{ '&euro; '.number_format(CalculationEndresult::superTotalProject($project), 2, ",",".") }}</strong></td>
						</tr>
					</tbody>
				</table>
			</div>
			<!--CONTENT TOTAL END-->

			<!--CLOSER START-->
			@if ($offer_last && $offer_last->offer_finish)
			{{ $offer_last->closure }}
			@else
			<textarea name="closure" id="closure" rows="5" class="form-control">{{ ($offer_last ? $offer_last->closure : Auth::user()->pref_closure_offer) }}</textarea>
			@endif
			<br>
			<br>
			<div class="row">
				<div class="col-sm-12">
				<h4>Bepalingen</h4>
				<ul >
					<li>
						<span id="condition-text">Indien opdracht gegund wordt, ontvangt u één eindfactuur.</span>
					</li>
					<li style="line-height:27px">
					@if($offer_last)
						@if ($offer_last && $offer_last->offer_finish)
							@if (DeliverTime::find($offer_last->deliver_id)->delivertime_name == "per direct" || DeliverTime::find($offer_last->deliver_id)->delivertime_name == "in overleg")
								Wij kunnen de werkzaamheden
								{{ DeliverTime::find($offer_last->deliver_id)->delivertime_name }}
								starten na uw opdrachtbevestiging.
							@else
								Wij kunnen de werkzaamheden starten binnen
								{{ DeliverTime::find($offer_last->deliver_id)->delivertime_name }}
								na uw opdrachtbevestiging.
							@endif
						@else
							@if (DeliverTime::find($offer_last->deliver_id)->delivertime_name == "per direct" || DeliverTime::find($offer_last->deliver_id)->delivertime_name == "in overleg")
								<select class="pull-right" name="deliver" id="deliver">
								@foreach (DeliverTime::all() as $deliver)
								<option {{ ($offer_last ? ($offer_last->deliver_id == $deliver->id ? 'selected' : '') : '') }} value="{{ $deliver->id }}">{{ $deliver->delivertime_name }}</option>
								@endforeach
								</select>
								Wij kunnen de werkzaamheden
								{{ DeliverTime::find($offer_last->deliver_id)->delivertime_name }}
								starten na uw opdrachtbevestiging.
							@else
								Wij kunnen de werkzaamheden starten binnen
									<select class="pull-right" name="deliver" id="deliver">
									@foreach (DeliverTime::all() as $deliver)
									<option {{ ($offer_last ? ($offer_last->deliver_id == $deliver->id ? 'selected' : '') : '') }} value="{{ $deliver->id }}">{{ $deliver->delivertime_name }}</option>
									@endforeach
									</select>
									<span class="pull-right">Selecteer de levertijd: </span>

								{{ DeliverTime::find($offer_last->deliver_id)->delivertime_name }} na uw opdrachtbevestiging.
							@endif
						@endif
					@else
						Geef de leveringsvoorwaarden voor de levertijd:
						<select name="deliver" id="deliver">
						@foreach (DeliverTime::all() as $deliver)
						<option {{ ($offer_last ? ($offer_last->deliver_id == $deliver->id ? 'selected' : '') : '') }} value="{{ $deliver->id }}">{{ $deliver->delivertime_name }}</option>
						@endforeach
						</select>
					@endif
					</li>
					<li style="line-height:27px">
						Deze offerte is geldig tot
						@if ($offer_last && $offer_last->offer_finish)
						{{ Valid::find($offer_last->valid_id)->valid_name }}
						na dagtekening.
						@else
						<select name="valid" id="valid">
							@foreach (Valid::all() as $valid)
							<option {{ ($offer_last ? ($offer_last->valid_id == $valid->id ? 'selected' : '') : '') }} value="{{ $valid->id }}">{{ $valid->valid_name }}</option>
							@endforeach
						</select>
						na dagtekening.
						@endif
					</li>
				</ul>
				@if ($offer_last && $offer_last->offer_finish)
					{{ $offer_last->extracondition }}
				@else
					<textarea name="extracondition" id="extracondition" rows="3" class="form-control">{{ ($offer_last ? $offer_last->extracondition : Auth::user()->pref_closure_invoice) }}</textarea>
				@endif



				<br>
				<p>Met vriendelijke groet,
					<br>
					@if ($offer_last && $offer_last->offer_finish)
					{{ Contact::find($offer_last->from_contact_id)->firstname . ' ' . Contact::find($offer_last->from_contact_id)->lastname }}
					@else
					<select name="from_contact" id="from_contact">
						@foreach (Contact::where('relation_id','=',$relation_self->id)->get() as $contact)
						<option {{ $offer_last ? ($offer_last->from_contact_id==$contact->id ? 'selected' : '') : '' }} value="{{ $contact->id }}">{{ $contact->firstname . ' ' . $contact->lastname }}</option>
						@endforeach
					</select>
					@endif
				</p>
				</div>
			</div>
		</div class="white-row">
			<!--CLOSER END-->

		<div class="white-row show-activity" style="display:none;">
			<!--PAGE HEADER START-->
			<div class="row">
				<div class="col-sm-6">
					{!! ($relation_self && $relation_self->logo_id) ? "<img src=\"/".Resource::find($relation_self->logo_id)->file_location."\" class=\"img-responsive\" />" : '' !!}
				</div>
				<div class="col-sm-6 text-right">
					<p>
						<h4><strong>{{ $project->project_name }}</strong></h4>
						<ul class="list-unstyled">
							<li><strong>Offertedatum:</strong> <a href="#" class="offdate">Bewerk</a></li>
							<li><strong>Offertenummer:</strong> {{ OfferController::getOfferCode($project->id) }}</li>
						</ul>
					</p>
					</div>
			</div>
			<hr class="margin-top10 margin-bottom10">
			<!--PAGE HEADER END-->

			<!-- SPECIFICATION CON&SUBCON START-->
			<div class="show-all" style="display:none;">
				<h4>Specificatie werkzaamheden</h4>
				<div><strong><u>AANNEMING</u></strong></div>
				<table class="table table-striped only-end-total">
					<thead>
						<tr>
							<th class="col-md-3">Hoofdstuk</th>
							<th class="col-md-3">Werkzaamheid</th>
							<th class="col-md-1"><span class="pull-right">Arbeidsuren</th>
							<th class="col-md-1"><span class="pull-right">Arbeid</th>
							<th class="col-md-1"><span class="pull-right">Materiaal</th>
							<th class="col-md-1"><span class="pull-right">Materieel</th>
							<th class="col-md-1"><span class="pull-right">Totaal</th>
							<th class="col-md-1"><span>&nbsp;&nbsp;&nbsp;Stelpost</th>
						</tr>
					</thead>
					<tbody>
						@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
						<?php $i = 0; ?>
						@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->get() as $activity)
						<?php $i++; ?>
						<tr>
							<td class="col-md-3">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
							<td class="col-md-3">{{ $activity->activity_name }}</td>
							<td class="col-md-1"><span class="pull-right">{{ number_format(CalculationOverview::laborTotal($activity), 2, ",",".") }}</td>
							<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::laborActivity($project->hour_rate, $activity), 2, ",",".") }}</span></td>
							<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::materialActivityProfit($activity, $project->profit_calc_contr_mat), 2, ",",".") }}</span></td>
							<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::equipmentActivityProfit($activity, $project->profit_calc_contr_equip), 2, ",",".") }}</span></td>
							<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::activityTotalProfit($project->hour_rate, $activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip), 2, ",",".") }} </td>
							<td class="col-md-1 text-center {{ CalculationOverview::estimateCheck($activity) }}"></td>
						</tr>
						@endforeach
						@endforeach
						<tr>
							<td class="col-md-3"><strong>Totaal aanneming</strong></td>
							<td class="col-md-3">&nbsp;</td>
							<td class="col-md-1"><strong><span class="pull-right">{{ CalculationOverview::contrLaborTotalAmount($project) }}</span></strong></td>
							<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::contrLaborTotal($project), 2, ",",".") }}</span></strong></td>
							<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::contrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
							<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::contrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
							<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::contrTotal($project), 2, ",",".") }}</span></strong></td>
							<td class="col-md-1">&nbsp;</td>
						</tr>
					</tbody>
				</table>

				<div><strong><u>ONDERAANNEMING</u></strong></div>
				<table class="table table-striped only-end-total">
					<thead>
						<tr>
							<th class="col-md-3">Hoofdstuk</th>
							<th class="col-md-3">Werkzaamheid</th>
							<th class="col-md-1"><span class="pull-right">Arbeidsuren</th>
							<th class="col-md-1"><span class="pull-right">Arbeid</th>
							<th class="col-md-1"><span class="pull-right">Materiaal</th>
							<th class="col-md-1"><span class="pull-right">Materieel</th>
							<th class="col-md-1"><span class="pull-right">Totaal</th>
							<th class="col-md-1"><span>&nbsp;&nbsp;&nbsp;Stelpost</th>
						</tr>
					</thead>
					<tbody>
						@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
						<?php $i = 0; ?>
						@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->get() as $activity)
						<?php $i++; ?>
						<tr>
							<td class="col-md-3">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
							<td class="col-md-3">{{ $activity->activity_name }}</td>
							<td class="col-md-1"><span class="pull-right">{{ number_format(CalculationOverview::laborTotal($activity), 2, ",",".") }}</td>
							<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::laborActivity($project->hour_rate, $activity), 2, ",",".") }}</span></td>
							<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::materialActivityProfit($activity, $project->profit_calc_subcontr_mat), 2, ",",".") }}</span></td>
							<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::equipmentActivityProfit($activity, $project->profit_calc_subcontr_equip), 2, ",",".") }}</span></td>
							<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::activityTotalProfit($project->hour_rate, $activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip), 2, ",",".") }} </td>
							<td class="col-md-1 text-center {{ CalculationOverview::estimateCheck($activity) }}"></td>
						</tr>
						@endforeach
						@endforeach
						<tr>
							<td class="col-md-3"><strong>Totaal onderaanneming</strong></td>
							<td class="col-md-3">&nbsp;</td>
							<td class="col-md-1"><strong><span class="pull-right">{{ CalculationOverview::subcontrLaborTotalAmount($project) }}</span></strong></td>
							<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::subcontrLaborTotal($project), 2, ",",".") }}</span></strong></td>
							<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::subcontrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
							<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::subcontrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
							<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::subcontrTotal($project), 2, ",",".") }}</span></strong></td>
							<td class="col-md-1">&nbsp;</td>
						</tr>
					</tbody>
				</table>

				<h4>Totalen project</h4>
				<table class="table table-striped only-end-total">
					<thead>
						<tr>
							<th class="col-md-3">&nbsp;</th>
							<th class="col-md-3">&nbsp;</th>
							<th class="col-md-1"><span class="pull-right">Arbeidsuren</span></th>
							<th class="col-md-1"><span class="pull-right">Arbeid</span></th>
							<th class="col-md-1"><span class="pull-right">Materiaal</span></th>
							<th class="col-md-1"><span class="pull-right">Materieel</span></th>
							<th class="col-md-1"><span class="pull-right">Totaal</span></th>
							<th class="col-md-1">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="col-md-3">&nbsp;</td>
							<td class="col-md-3">&nbsp;</td>
							<td class="col-md-1"><strong><span class="pull-right">{{ CalculationOverview::laborSuperTotalAmount($project) }}</span></strong></td>
							<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::laborSuperTotal($project), 2, ",",".") }}</span></strong></td>
							<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::materialSuperTotal($project), 2, ",",".") }}</span></strong></td>
							<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::equipmentSuperTotal($project), 2, ",",".") }}</span></strong></td>
							<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::superTotal($project), 2, ",",".") }}</span></strong></td>
							<td class="col-md-1">&nbsp;</td>
						</tr>
					</tbody>
				</table>
			</div>
			<!-- SPECIFICATION CON&SUBCON END-->

			<!-- SPECIFICATION TOTAL START-->
			<div class="show-totals">
				<h4>Totaalkosten per werkzaamheid</h4>
				<table class="table table-striped only-end-total">
					<thead>
						<tr>
							<th class="col-md-3">Hoofdstuk</th>
							<th class="col-md-3">Werkzaamheid</th>
							<th class="col-md-1"><span class="pull-right">Arbeidsuren</th>
							<th class="col-md-1"><span class="pull-right">Arbeid</th>
							<th class="col-md-1"><span class="pull-right">Materiaal</th>
							<th class="col-md-1"><span class="pull-right">Materieel</th>
							<th class="col-md-1"><span class="pull-right">Totaal</th>
							<th class="col-md-1"><span>&nbsp;&nbsp;&nbsp;Stelpost</th>
						</tr>
					</thead>
					<tbody>
						@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
						<?php $i = 0; ?>
						@foreach (Activity::where('chapter_id','=', $chapter->id)->get() as $activity)
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
							<td class="col-md-3">{{ $activity->activity_name }}</td>
							<td class="col-md-1"><span class="pull-right">{{ number_format(CalculationOverview::laborTotal($activity), 2, ",",".") }}</td>
							<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::laborActivity($project->hour_rate, $activity), 2, ",",".") }}</span></td>
							<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::materialActivityProfit($activity, $mat_profit), 2, ",",".") }}</span></td>
							<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::equipmentActivityProfit($activity, $equip_profit), 2, ",",".") }}</span></td>
							<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::activityTotalProfit($project->hour_rate, $activity, $mat_profit, $equip_profit), 2, ",",".") }} </td>
							<td class="col-md-1 text-center {{ CalculationOverview::estimateCheck($activity) }}"></td>
						</tr>
						@endforeach
						@endforeach
					</tbody>
				</table>

				<h4>Totaalkosten project</h4>
				<table class="table table-striped only-end-total">
					<thead>
						<tr>
							<th class="col-md-3">&nbsp;</th>
							<th class="col-md-3">&nbsp;</th>
							<th class="col-md-1"><span class="pull-right">Arbeidsuren</span></th>
							<th class="col-md-1"><span class="pull-right">Arbeid</span></th>
							<th class="col-md-1"><span class="pull-right">Materiaal</span></th>
							<th class="col-md-1"><span class="pull-right">Materieel</span></th>
							<th class="col-md-1"><span class="pull-right">Totaal</span></th>
							<th class="col-md-1">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="col-md-3">&nbsp;</td>
							<td class="col-md-3">&nbsp;</td>
							<td class="col-md-1"><span class="pull-right">{{ CalculationOverview::laborSuperTotalAmount($project) }}</span></td>
							<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::laborSuperTotal($project), 2, ",",".") }}</span></td>
							<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::materialSuperTotal($project), 2, ",",".") }}</span></td>
							<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::equipmentSuperTotal($project), 2, ",",".") }}</span></td>
							<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::superTotal($project), 2, ",",".") }}</span></td>
							<td class="col-md-1">&nbsp;</td>
						</tr>
					</tbody>
				</table>
			</div>
			<h6><strong>Weergegeven bedragen zijn exclusief BTW</strong></h5>
		</div>
			<!-- SPECIFICATION TOTAL END-->

		<div class="white-row show-note" style="display:none;">
			<!--PAGE HEADER START-->
			<div class="row">
				<div class="col-sm-6">
					{!! ($relation_self && $relation_self->logo_id) ? "<img src=\"/".Resource::find($relation_self->logo_id)->file_location."\" class=\"img-responsive\" />" : '' !!}
				</div>
				<div class="col-sm-6 text-right">
					<p>
						<h4><strong>{{ $project->project_name }}</strong></h4>
						<ul class="list-unstyled">
							<li><strong>Offertedatum:</strong> <a href="#" class="offdate">Bewerk</a></li>
							<li><strong>Offertenummer:</strong> {{ OfferController::getOfferCode($project->id) }}</li>
						</ul>
					</p>
					</div>
			</div>
			<hr class="margin-top10 margin-bottom10">
			<!--PAGE HEADER END-->

			<!-- DESCRIPTION CON&SUBCON START -->
			<div class="show-all" style="display:none;">
				<h4>Omschrijving werkzaamheden</h4>
				<div><strong><u>AANNEMING</u></strong></div>
				<table class="table table-striped">
					<thead>
						<tr>
							<th class="col-md-2">Hoofdstuk</th>
							<th class="col-md-3">Werkzaamheid</th>
							<th class="col-md-7"><span>Omschrijving</th>
						</tr>
					</thead>
					<tbody>
						@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
						<?php $i = 0; ?>
						@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->get() as $activity)
						<?php $i++ ?>
						<tr>
							<td class="col-md-2">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
							<td class="col-md-3">{{ $activity->activity_name }}</td>
							<td class="col-md-7"><span>{{ $activity->note }}</td>
						</tr>
						@endforeach
						@endforeach
					</tbody>
				</table>

				<div><strong><u>ONDERAANNEMING</u></strong></div>
				<table class="table table-striped">
					<thead>
						<tr>
							<th class="col-md-2">Hoofdstuk</th>
							<th class="col-md-3">Werkzaamheid</th>
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
			<!-- DESCRIPTION CON&SUBCON END -->

			<!-- DESCRIPTION TOTAL START -->
			<div class="show-totals">
				<h4>Omschrijving werkzaamheden</h4>
				<table class="table table-striped">
					<thead>
						<tr>
							<th class="col-md-2">Hoofdstuk</th>
							<th class="col-md-3">Werkzaamheid</th>
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
					</tbody>INVOICE
				</table>
			</div>
			<!-- DESCRIPTION TOTAL END -->

		</div>
		@endif

		<!-- INVOICE FOOTER START -->
			<div class="row">
				<div class="col-sm-6"></div>
				<div class="col-sm-6">
					<div class="padding20 pull-right">
						<?php if (!$project->project_close) { ?>
						<?php if ($offer_last) { ?>
						<?php if (!$offer_last->offer_finish) { ?>
						<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Opties</a>
						<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#termModal">Termijnen</a>
						<button class="btn btn-primary osave">Opslaan</button>
						<?php } ?>
						<?php }else{ ?>
						<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Opties</a>
						<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#termModal">Termijnen</a>
						@if (CalculationEndresult::totalProject($project))
						<button class="btn btn-primary osave">Opslaan</button>
						@endif
						<?php } ?>
						<?php } ?>
					</div>
				</div>
			</div>
			<!-- INVOICE FOOTER END -->

	</section>
</div>
<!-- /WRAPPER -->
@stop

<?php } ?>
