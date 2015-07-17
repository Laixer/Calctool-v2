<?php
$project = Project::find(Route::Input('project_id'));
$relation = Relation::find($project->client_id);
$relation_self = Relation::find(Auth::user()->self_id);
if ($relation_self)
	$contact_self = Contact::where('relation_id','=',$relation_self->id);
$offer_last = Offer::where('project_id','=',$project->id)->orderBy('created_at', 'desc')->first();
?>

@extends('layout.master')

@section('content')
<?# -- WRAPPER -- ?>
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
		$('#terms').change(function(e){
			var q = $('#terms').val();
			if($.isNumeric(q)&&(q>1))
				$('.noterms').show('slow');
			else
				$('.noterms').hide('slow');

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
			$('#frm-offer').submit();
		});
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
		<?php if ($offer_last) { ?>
		<?php if (!$offer_last->offer_finish) { ?>
		<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Opties</a>
		<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#termModal">Termijnen</a>
		<button class="btn btn-primary osave">Offerte  maken</button>
		<?php } ?>
		<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#historyModal">Versies</a>

		<div class="btn-group">
		  <a target="blank" href="/offer/pdf/project-{{ $project->id }}{{ $offer_last->option_query ? '?'.$offer_last->option_query : '' }}" class="btn btn-primary">PDF</a>
		  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		    <span class="caret"></span>
		    <span class="sr-only">Toggle Dropdown</span>
		  </button>
		  <ul class="dropdown-menu">
		    <li><a href="/offer/pdf/project-{{ $project->id }}/download?file={{ OfferController::getOfferCode($project->id).'-offerte.pdf' }}{{ $offer_last->option_query ? '&'.$offer_last->option_query : '' }}">Download</a></li>
		  </ul>
		</div>
		<?php }else{ ?>
		<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Opties</a>
		<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#termModal">Termijnen</a>
		<button class="btn btn-primary osave">Offerte  maken</button>
		<?php } ?>
	</div>

	<!-- modal dialog -->
	<div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">

				<div class="modal-header"><!-- modal header -->
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel2">Offerte versies</h4>
				</div><!-- /modal header -->

				<!-- modal body -->
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
									<td><a href="#">{{ $offer->id }}</a></td>
									<td>{{ date('d-m-Y', strtotime(DB::table('offer')->select('created_at')->where('id','=',$offer->id)->get()[0]->created_at)) }}</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>

				</div>
				<!-- /modal body -->

				<div class="modal-footer"><!-- modal footer -->
					<button class="btn btn-default" data-dismiss="modal">Close</button>
				</div><!-- /modal footer -->

			</div>
		</div>
	</div>

	<h2><strong>Offerte</strong></h2>
	<form method="POST" id="frm-offer">
			<div class="modal fade" id="termModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">

						<div class="modal-header"><!-- modal header -->
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title" id="myModalLabel2">Termijnfacturen</h4>
						</div><!-- /modal header -->

						<!-- modal body -->
						<div class="modal-body">
							<div class="form-horizontal">
								<div class="form-group">
									<div class="col-md-6">
										<label>Termijnen</label>
										<input value="{{ ($offer_last ? $offer_last->invoice_quantity : '1') }}" name="terms" id="terms" min="1" max="50" type="number" class="form-control" />
									</div>
								</div>
								<div class="form-horizontal noterms" style="display:none;">
									<div class="col-md-6">
									  <div class="form-group">
									  <label>Aanbetaling</label>
									    <div class="col-sm-offset-0 col-sm-12">
									      <div class="checkbox">
									        <label>
									          <input {{ ($offer_last ? ($offer_last->downpayment ? 'checked' : '') : '') }} name="toggle-payment" type="checkbox">
									        </label>
									      </div>
									    </div>
									  </div>
									</div>
								</div>
							</div>
							<div class="table-responsive noterms" style="display:none;">
								<table id="tbl-term" class="table table-hover">
									<thead>
										<tr>
											<th>Termijnnummer</th>
											<th>Bedrag</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Aanbetaling</td>
											<td><input {{ ($offer_last ? ($offer_last->downpayment ? '' : 'disabled') : 'disabled') }} type="text" value="{{ ($offer_last ? $offer_last->downpayment_amount : '') }}" id="amount" name="amount" class="form-control-sm-text" /></td>
										</tr>
									</tbody>
								</table>
							</div>

						</div>
						<!-- /modal body -->

						<div class="modal-footer"><!-- modal footer -->
							<button class="btn btn-default" data-dismiss="modal">Close</button>
						</div><!-- /modal footer -->

					</div>
				</div>
			</div>

			<!-- modal dialog -->
			<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">

						<div class="modal-header"><!-- modal header -->
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title" id="myModalLabel">Offerte opties</h4>
						</div><!-- /modal header -->

						<!-- modal body -->
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
								          <input name="toggle-endresult" type="checkbox"> Alleen het totale offertebedrag weergeven
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
								          <input name="toggle-summary" type="checkbox"> Kosten werkzaamheden specificeren
								        </label>
								      </div>
								    </div>
								  </div>
								  <div class="form-group">
								    <div class="col-sm-offset-0 col-sm-12">
								      <div class="checkbox">
								        <label>
								          <input name="toggle-note" type="checkbox" checked> Omschrijving werkzaamheden in bijlage weergeven
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

				<div class="row">

					<div class="col-sm-6">
						{{ ($relation_self && $relation_self->logo_id) ? "<img src=\"/".Resource::find($relation_self->logo_id)->file_location."\" class=\"img-responsive\" />" : '' }}
					</div>

					<div class="col-sm-6 text-right">
						<p>
							{{ OfferController::getOfferCode($project->id) }} &bull; <strong>{{ date("j M Y") }}</strong>
							<br />
							{{ $project->project_name }}
						</p>
					</div>

				</div>

				<hr class="margin-top10 margin-bottom10" /><!-- separator -->

				<!-- DETAILS -->
				<div class="row">

					<div class="col-sm-6">

						<ul class="list-unstyled">
							<br>
							<br>
							<br>
							<br>
							<li>{{ $relation->company_name }}</li>
							<li>t.a.v.
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

					<div class="col-sm-4">

						<h4><strong>Opdrachtnemer</strong></h4>
						<ul class="list-unstyled">
							<li><strong>Bedrijfsnaam:</strong> {{ $relation_self->company_name }}</li>
							<li><strong>Adres:</strong> {{ $relation_self->address_street . ' ' . $relation_self->address_number }}</li>
							<li style="margin-left: 48px;">{{ $relation_self->address_postal . ', ' . $relation_self->address_city }}</li>
							<li><strong>Telefoon:</strong> {{ $relation_self->phone }}</li>
							<li><strong>Email:</strong> {{ $relation_self->email }}</li>
							<li><strong>KVK:</strong>{{ $relation_self->kvk }}</li>
						</ul>

						<h4><strong>Offerte gegevens</strong></h4>
						<ul class="list-unstyled">
							<li><strong>Offertedatum:</strong> {{ date("j M Y") }}</li>
							<li><strong>Offertenummer:</strong> {{ OfferController::getOfferCode($project->id) }}</li>
						</ul>

					</div>

				</div>

					@if ($offer_last && $offer_last->offer_finish)
					{{ $offer_last->description }}
					@else
					<textarea name="description" id="description" rows="5" class="form-control">{{ ($offer_last ? $offer_last->description : '') }}</textarea>
					@endif
					<br>
					<div class="show-all" style="display:none;">
						<h4 class="only-total">Aanneming</h4>
						<table class="table table-striped hide-btw1">
							<?# -- table head -- ?>
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
								<tr><!-- item -->
									<td class="col-md-4">Arbeidskosten</td>
									<td class="col-md-1">{{ ''.number_format(CalculationEndresult::conCalcLaborActivityTax1($project), 2, ",",".") }}</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">{{ ''.number_format(CalculationEndresult::conCalcLaborActivityTax2($project), 2, ",",".") }}</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">{{ ''.number_format(CalculationEndresult::conCalcLaborActivityTax3($project), 2, ",",".") }}</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>

								<tr><!-- item -->
									<td class="col-md-4">Materiaalkosten</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>

								<tr><!-- item -->
									<td class="col-md-4">Materieelkosten</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>

								<tr><!-- item -->
									<td class="col-md-4"><strong>Totaal Aanneming </strong></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalContracting($project), 2, ",",".") }}</strong></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalContractingTax($project), 2, ",",".") }}</strong></td>
								</tr>
							</tbody>
						</table>

						<h4 class="only-total">Onderaanneming</h4>
						<table class="table table-striped hide-btw1">
							<?# -- table head -- ?>
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

							<!-- table items -->
							<tbody>
								<tr><!-- item -->
									<td class="col-md-4">Arbeidskosten</td>
									<td class="col-md-1">{{ ' '.number_format(CalculationEndresult::subconCalcLaborActivityTax1($project), 2, ",",".") }}</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">{{ ' '.number_format(CalculationEndresult::subconCalcLaborActivityTax2($project), 2, ",",".") }}</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">{{ ''.number_format(CalculationEndresult::subconCalcLaborActivityTax3($project), 2, ",",".") }}</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>

								<tr><!-- item -->
									<td class="col-md-4">Materiaalkosten</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcMaterialActivityTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcMaterialActivityTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>

								<tr><!-- item -->
									<td class="col-md-4">Materieelkosten</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcEquipmentActivityTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcEquipmentActivityTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>

								<tr><!-- item -->
									<td class="col-md-4"><strong>Totaal Onderaanneming </strong></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalSubcontractingTax($project), 2, ",",".") }}</strong></td>
								</tr>
							</tbody>
						</table>

						<h4>Cumulatieven Offerte</h4>
						<table class="table table-striped hide-btw2">
							<?# -- table head -- ?>
							<thead>
								<tr>
									<th class="col-md-6">&nbsp;</th>
									<th class="col-md-2">Bedrag (excl. BTW)</th>
									<th class="col-md-2">BTW bedrag</th>
									<th class="col-md-2">&nbsp;</th>
								</tr>
							</thead>

							<!-- table items -->
							<tbody>
								<tr><!-- item -->
									<td class="col-md-6">Calculatief te offereren (excl. BTW)</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::totalProject($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-6">BTW bedrag aanneming belast met 21%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::totalContractingTax1($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-6">BTW bedrag aanneming belast met 6%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::totalContractingTax2($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-6">BTW bedrag onderaanneming belast met 21%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::totalSubcontractingTax1($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-6">BTW bedrag onderaanneming belast met 6%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::totalSubcontractingTax2($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-6">Te offereren BTW bedrag</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::totalProjectTax($project), 2, ",",".") }}</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-6"><strong>Calculatief te offereren (Incl. BTW)</strong></td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-2"><strong>{{ '&euro; '.number_format(CalculationEndresult::superTotalProject($project), 2, ",",".") }}</strong></td>
								</tr>

							</tbody>

						</table>
					</div>

					<div class="show-totals">
					<h4 class="only-total">Totaalkosten project</h4>
						<table class="table table-striped hide-btw1">
							<?# -- table head -- ?>
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

							<!-- table items -->
							<tbody>
								<tr><!-- item -->
									<td class="col-md-4">Arbeidskosten</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax1($project)+CalculationEndresult::subconCalcLaborActivityTax1($project), 2, ",",".") }}</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax1Amount($project)+CalculationEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax1AmountTax($project)+CalculationEndresult::subconCalcLaborActivityTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax2($project)+CalculationEndresult::subconCalcLaborActivityTax2($project), 2, ",",".") }}</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax2Amount($project)+CalculationEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax2AmountTax($project)+CalculationEndresult::subconCalcLaborActivityTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax3($project)+CalculationEndresult::subconCalcLaborActivityTax3($project), 2, ",",".") }}</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax3Amount($project)+CalculationEndresult::conCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>

								<tr><!-- item -->
									<td class="col-md-4">Materiaalkosten</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax1Amount($project)+CalculationEndresult::conCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax1AmountTax($project)+CalculationEndresult::subconCalcMaterialActivityTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax2Amount($project)+CalculationEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax2AmountTax($project)+CalculationEndresult::subconCalcMaterialActivityTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax3Amount($project)+CalculationEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>

								<tr><!-- item -->
									<td class="col-md-4">Materieelkosten</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax1Amount($project)+CalculationEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax1AmountTax($project)+CalculationEndresult::subconCalcEquipmentActivityTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax2Amount($project)+CalculationEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax2AmountTax($project)+CalculationEndresult::subconCalcEquipmentActivityTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax3Amount($project)+CalculationEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>

								<tr><!-- item -->
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

						<h4>Cumulatieven Offerte</h4>
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

							<!-- table items -->
							<tbody>
								<tr><!-- item -->
									<td class="col-md-5">Calculatief te offereren (excl. BTW)</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::totalProject($project), 2, ",",".") }}</td>
									<th class="col-md-1">&nbsp;</th>
									<th class="col-md-1">&nbsp;</th>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>

								</tr>
								<tr><!-- item -->
									<td class="col-md-5">BTW bedrag calculatie belast met 21%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::totalContractingTax1($project)+CalculationEndresult::totalSubcontractingTax1($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>

								<tr><!-- item -->
									<td class="col-md-5">BTW bedrag calculatie belast met 6%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::totalContractingTax2($project)+CalculationEndresult::totalSubcontractingTax2($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-5">Te offereren BTW bedrag</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalProjectTax($project), 2, ",",".") }}</strong></td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-5"><strong>Calculatief te offereren (Incl. BTW)</strong></td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2"><strong class="pull-right">{{ '&euro; '.number_format(CalculationEndresult::superTotalProject($project), 2, ",",".") }}</strong></td>

								</tr>

							</tbody>

						</table>
					</div>

					@if ($offer_last && $offer_last->offer_finish)
					{{ $offer_last->closure }}
					@else
					<textarea name="closure" id="closure" rows="5" class="form-control">{{ ($offer_last ? $offer_last->closure : '') }}</textarea>
					@endif
					<br>
					<p id="termtext">Indien opdracht gegund wordt, ontvangt u één eindfactuur.</p>
					<p id="paymenttext"></p>

					<p>Wij kunnen de werkzaamheden starten binnen
						@if ($offer_last && $offer_last->offer_finish)
						{{ DeliverTime::find($offer_last->deliver_id)->delivertime_name }}
						@else
						<select name="deliver" id="deliver">
							@foreach (DeliverTime::all() as $deliver)
							<option {{ ($offer_last ? ($offer_last->deliver_id == $deliver->id ? 'selected' : '') : '') }} value="{{ $deliver->id }}">{{ $deliver->delivertime_name }}</option>
							@endforeach
						</select>
						@endif
						na uw opdrachtbevestiging.
					</p>

					<p>Deze offerte is geldig tot
						@if ($offer_last && $offer_last->offer_finish)
						{{ Valid::find($offer_last->valid_id)->valid_name }}
						@else
						<select name="valid" id="valid">
							@foreach (Valid::all() as $valid)
							<option {{ ($offer_last ? ($offer_last->valid_id == $valid->id ? 'selected' : '') : '') }} value="{{ $valid->id }}">{{ $valid->valid_name }}</option>
							@endforeach
						</select> na dagtekening.
						@endif
					</p>

					<p>Cheers,
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

			<div class="white-row show-activity">

				<div class="row">

					<div class="col-sm-6">
						{{ ($relation_self && $relation_self->logo_id) ? "<img src=\"/".Resource::find($relation_self->logo_id)->file_location."\" class=\"img-responsive\" />" : '' }}
					</div>

					<div class="col-sm-6 text-right">
						<p>
							#{{ sprintf("%06d", $project->id) }} &bull; <strong>{{ date("j M Y") }}</strong>
							<br />
							{{ $project->project_name }}
						</p>
					</div>

				</div>

				<hr class="margin-top10 margin-bottom10" /><!-- separator -->


				<!-- /DETAILS -->

							<div class="show-all" style="display:none;">

								<h4>Aanneming</h4>

								<table class="table table-striped only-end-total">
									<?# -- table head -- ?>
									<thead>
										<tr>
											<th class="col-md-2">Hoofdstuk</th>
											<th class="col-md-3">Werkzaamheid</th>
											<th class="col-md-1"><span class="pull-right">Arbeidsuren</th>
											<th class="col-md-1"><span class="pull-right">Arbeid</th>
											<th class="col-md-1"><span class="pull-right">Materiaal</th>
											<th class="col-md-1"><span class="pull-right">Materieel</th>
											<th class="col-md-1"><span class="pull-right">Totaal</th>
											<th class="col-md-1"><span class="text-center">Stelpost</th>
										</tr>
									</thead>

									<!-- table items -->
									<tbody>
										@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
										@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->get() as $activity)
										<tr><!-- item -->
											<td class="col-md-2"><strong>{{ $chapter->chapter_name }}</strong></td>
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
										<tr><!-- item -->
											<td class="col-md-2"><strong>Totaal aanneming</strong></td>
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

								<h4>Onderaanneming</h4>

								<table class="table table-striped only-end-total">
									<?# -- table head -- ?>
									<thead>
										<tr>
											<th class="col-md-2">&nbsp;</th>
											<th class="col-md-3">&nbsp;</th>
											<th class="col-md-1"><span class="pull-right">Arbeidsuren</th>
											<th class="col-md-1"><span class="pull-right">Arbeid</th>
											<th class="col-md-1"><span class="pull-right">Materiaal</th>
											<th class="col-md-1"><span class="pull-right">Materieel</th>
											<th class="col-md-1"><span class="pull-right">Totaal</th>
											<th class="col-md-1"><span class="text-center">Stelpost</th>
										</tr>
									</thead>

									<!-- table items -->
									<tbody>
										@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
										@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->get() as $activity)
										<tr><!-- item -->
											<td class="col-md-2"><strong>{{ $chapter->chapter_name }}</strong></td>
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
										<tr><!-- item -->
											<td class="col-md-2"><strong>Totaal onderaanneming</strong></td>
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
									<?# -- table head -- ?>
									<thead>
										<tr>
											<th class="col-md-2">&nbsp;</th>
											<th class="col-md-3">&nbsp;</th>
											<th class="col-md-1"><span class="pull-right">Arbeidsuren</span></th>
											<th class="col-md-1"><span class="pull-right">Arbeid</span></th>
											<th class="col-md-1"><span class="pull-right">Materiaal</span></th>
											<th class="col-md-1"><span class="pull-right">Materieel</span></th>
											<th class="col-md-1"><span class="pull-right">Totaal</span></th>
											<th class="col-md-1">&nbsp;</th>
										</tr>
									</thead>

									<!-- table items -->
									<tbody>
										<tr><!-- item -->
											<td class="col-md-2">&nbsp;</td>
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

							<div class="show-totals">

								<h4>Totaalkosten per werkzaamheden</h4>

								<table class="table table-striped only-end-total">
									<?# -- table head -- ?>
									<thead>
										<tr>
											<th class="col-md-2">&nbsp;</th>
											<th class="col-md-3">&nbsp;</th>
											<th class="col-md-1"><span class="pull-right">Arbeidsuren</th>
											<th class="col-md-1"><span class="pull-right">Arbeid</th>
											<th class="col-md-1"><span class="pull-right">Materiaal</th>
											<th class="col-md-1"><span class="pull-right">Materieel</th>
											<th class="col-md-1"><span class="pull-right">Totaal</th>
											<th class="col-md-1"><span class="text-center">Stelpost</th>
										</tr>
									</thead>

									<!-- table items -->
									<tbody>
										@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
										@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->get() as $activity)
										<tr><!-- item -->
											<td class="col-md-2"><strong>{{ $chapter->chapter_name }}</strong></td>
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
										@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
										@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->get() as $activity)
										<tr><!-- item -->
											<td class="col-md-2"><strong>{{ $chapter->chapter_name }}</strong></td>
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
									</tbody>
								</table>

								<h4>Totaalkosten project</h4>

								<table class="table table-striped only-end-total">
									<?# -- table head -- ?>
									<thead>
										<tr>
											<th class="col-md-2">&nbsp;</th>
											<th class="col-md-3">&nbsp;</th>
											<th class="col-md-1"><span class="pull-right">Arbeidsuren</span></th>
											<th class="col-md-1"><span class="pull-right">Arbeid</span></th>
											<th class="col-md-1"><span class="pull-right">Materiaal</span></th>
											<th class="col-md-1"><span class="pull-right">Materieel</span></th>
											<th class="col-md-1"><span class="pull-right">Totaal</span></th>
											<th class="col-md-1">&nbsp;</th>
										</tr>
									</thead>

									<!-- table items -->
									<tbody>
										<tr><!-- item -->
											<td class="col-md-2">&nbsp;</td>
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
						<h5><strong>Weergegeven bedragen zijn exclusief BTW</strong></h5>

				</div>

			<div class="white-row show-note">

				<div class="row">

					<div class="col-sm-6">
						{{ ($relation_self && $relation_self->logo_id) ? "<img src=\"/".Resource::find($relation_self->logo_id)->file_location."\" class=\"img-responsive\" />" : '' }}
					</div>

					<div class="col-sm-6 text-right">
						<p>
							#{{ sprintf("%06d", $project->id) }} &bull; <strong>{{ date("j M Y") }}</strong>
							<br />
							{{ $project->project_name }}
						</p>
					</div>

				</div>

				<hr class="margin-top10 margin-bottom10" /><!-- separator -->


				<!-- /DETAILS -->

							<div class="show-all" style="display:none;">

								<h4>Aanneming</h4>

								<table class="table table-striped">
									<?# -- table head -- ?>
									<thead>
										<tr>
											<th class="col-md-2">&nbsp;</th>
											<th class="col-md-3">&nbsp;</th>
											<th class="col-md-7"><span>Omschrijving</th>
										</tr>
									</thead>

									<!-- table items -->
									<tbody>
										@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
										@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->get() as $activity)
										<tr><!-- item -->
											<td class="col-md-2"><strong>{{ $chapter->chapter_name }}</strong></td>
											<td class="col-md-3">{{ $activity->activity_name }}</td>
											<td class="col-md-7"><span>{{ $activity->note }}</td>
										</tr>
										@endforeach
										@endforeach
									</tbody>
								</table>

								<h4>Onderaanneming</h4>

								<table class="table table-striped">
									<?# -- table head -- ?>
									<thead>
										<tr>
											<th class="col-md-2">&nbsp;</th>
											<th class="col-md-3">&nbsp;</th>
											<th class="col-md-7"><span>Omschrijving</th>
										</tr>
									</thead>

									<!-- table items -->
									<tbody>
										@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
										@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->get() as $activity)
										<tr><!-- item -->
											<td class="col-md-2"><strong>{{ $chapter->chapter_name }}</strong></td>
											<td class="col-md-3">{{ $activity->activity_name }}</td>
											<td class="col-md-7"><span>{{ $activity->note }}</td>
										</tr>
										@endforeach
										@endforeach
									</tbody>
								</table>
							</div>

							<div class="show-totals">
								<h4>Omschrijving werkzaamheden</h4>

								<table class="table table-striped">
									<?# -- table head -- ?>
									<thead>
										<tr>
											<th class="col-md-2">&nbsp;</th>
											<th class="col-md-3">&nbsp;</th>
											<th class="col-md-7"><span>Omschrijving</th>
										</tr>
									</thead>

									<!-- table items -->
									<tbody>
										@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
										@foreach (Activity::where('chapter_id','=', $chapter->id)->get() as $activity)
										<tr><!-- item -->
											<td class="col-md-2"><strong>{{ $chapter->chapter_name }}</strong></td>
											<td class="col-md-3">{{ $activity->activity_name }}</td>
											<td class="col-md-7"><span>{{ $activity->note }}</td>
										</tr>
										@endforeach
										@endforeach
									</tbody>
								</table>

							</div>

				</div>
			</form>

			<!-- INVOICE FOOTER -->
			<div class="row">

				<div class="col-sm-6"></div>

				<div class="col-sm-6">
					<div class="padding20 pull-right">
						<?php if ($offer_last) { ?>
						<?php if (!$offer_last->offer_finish) { ?>
						<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Opties</a>
						<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#termModal">Termijnen</a>
						<button class="btn btn-primary osave">Offerte  maken</button>
						<?php } ?>
						<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#historyModal">Versies</a>

						<div class="btn-group">
						  <a target="blank" href="/offer/pdf/project-{{ $project->id }}" class="btn btn-primary">PDF</a>
						  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    <span class="caret"></span>
						    <span class="sr-only">Toggle Dropdown</span>
						  </button>
						  <ul class="dropdown-menu">
						    <li><a href="/offer/pdf/project-{{ $project->id }}/download?file={{ OfferController::getOfferCode($project->id).'-offerte.pdf' }}">Download</a></li>
						  </ul>
						</div>
						<?php }else{ ?>
						<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Opties</a>
						<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#termModal">Termijnen</a>
						<button class="btn btn-primary osave">Offerte  maken</button>
						<?php } ?>
					</div>
				</div>

			</div>
		</div>
		@endif

	</section>

</div>
<!-- /WRAPPER -->
@stop
