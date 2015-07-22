<?php
$project = Project::find(Route::Input('project_id'));
$offer_last = Offer::where('project_id','=',$project->id)->orderBy('created_at', 'desc')->first();
?>

@extends('layout.master')

@section('content')

<script type="text/javascript">
	$(document).ready(function() {
		$('#addnew').click(function(e) {
			$curThis = $(this);
			e.preventDefault();
			$date = $curThis.closest("tr").find("input[name='date']").val();
			$hour = $curThis.closest("tr").find("input[name='hour']").val();
			$type = $curThis.closest("tr").find("select[name='typename']").val();
			$activity = $curThis.closest("tr").find("select[name='activity']").val();
			$note = $curThis.closest("tr").find("input[name='note']").val();
			$.post("/timesheet/new", {
				date: $date,
				hour: $hour,
				type: $type,
				activity: $activity,
				note: $note,
				project: {{ $project->id }},
			}, function(data){
				var $curTable = $curThis.closest("table");
				var json = $.parseJSON(data);
				$curTable.find("tr:eq(1)").clone().removeAttr("data-id")
				.find("td:eq(0)").text($date).end()
				.find("td:eq(1)").text(json.hour).end()
				.find("td:eq(2)").text(json.type).end()
				.find("td:eq(3)").text(json.activity).end()
				.find("td:eq(4)").text($note).end()
				.find("td:eq(7)").html('<button class="btn btn-danger btn-xs fa fa-times deleterowp"></button>').end()
				.prependTo($curTable);
				$curThis.closest("tr").find("input").val("");
				$curThis.closest("tr").find("select").val("");
			});
		});
		$('#addnewpurchase').click(function(e) {
			$curThis = $(this);
			e.preventDefault();
			$date = $curThis.closest("tr").find("input[name='date']").val();
			$hour = $curThis.closest("tr").find("input[name='hour']").val();
			$type = $curThis.closest("tr").find("select[name='typename']").val();
			$relation = $curThis.closest("tr").find("select[name='relation']").val();
			$note = $curThis.closest("tr").find("input[name='note']").val();
			$.post("/purchase/new", {
				date: $date,
				hour: $hour,
				type: $type,
				relation: $relation,
				note: $note,
				project: {{ $project->id }}
			}, function(data){
				var $curTable = $curThis.closest("table");
				var json = $.parseJSON(data);
				$curTable.find("tr:eq(1)").clone().removeAttr("data-id")
				.find("td:eq(0)").text($date).end()
				.find("td:eq(1)").text(json.relation).end()
				.find("td:eq(2)").html(json.amount).end()
				.find("td:eq(3)").text(json.type).end()
				.find("td:eq(4)").text($note).end()
				.find("td:eq(7)").html('<button class="btn btn-danger btn-xs fa fa-times deleterowp"></button>').end()
				.prependTo($curTable);
				$curThis.closest("tr").find("input").val("");
				$curThis.closest("tr").find("select").val("");
			});
		});
		$("body").on("click", ".deleterow", function(e){
			e.preventDefault();
			var $curThis = $(this);
			if($curThis.closest("tr").attr("data-id"))
				$.post("/timesheet/delete", {project: {{ $project->id }}, id: $curThis.closest("tr").attr("data-id")}, function(){
					$curThis.closest("tr").hide("slow");
				}).fail(function(e) { console.log(e); });
		});
		$("body").on("click", ".deleterowp", function(e){
			e.preventDefault();
			var $curThis = $(this);
			if($curThis.closest("tr").attr("data-id"))
				$.post("/purchase/delete", {project: {{ $project->id }}, id: $curThis.closest("tr").attr("data-id")}, function(){
					$curThis.closest("tr").hide("slow");
				}).fail(function(e) { console.log(e); });
		});
		$('.dopay').click(function(e){
			$curThis = $(this);
			$curproj = $(this).attr('data-project');
			$curinv = $(this).attr('data-invoice');
			$.post("/invoice/pay", {project: {{ $project->id }}, id: $curinv, projectid: $curproj}, function(data){
				$rs = jQuery.parseJSON(data);
				$curThis.replaceWith('Betaald op ' +$rs.payment);
			}).fail(function(e) { console.log(e); });
		});
		$('.doinvclose').click(function(e){
			$curThis = $(this);
			$curproj = $(this).attr('data-project');
			$curinv = $(this).attr('data-invoice');
			$.post("/invoice/invclose", {project: {{ $project->id }}, id: $curinv, projectid: $curproj}, function(data){
				$rs = jQuery.parseJSON(data);
				$curThis.replaceWith($rs.billing);
			}).fail(function(e) { console.log(e); });
		});
		$('#projclose').editable({
			type:  'date',
			pk:    {{ $project->id }},
			name:  'wordexec',
			url:   '/project/updateprojectclose',
			send:  'always',
			emptytext: 'Bewerk',
			title: 'Selecteer einddatum',
			validate: function(value) {
				if($.trim(value) == '')
					return 'Vul een datum in';
			}
		});
		$('#wordexec').editable({
			type:  'date',
			pk:    {{ $project->id }},
			name:  'wordexec',
			url:   '/project/updateworkexecution',
			send:  'always',
			emptytext: 'Bewerk',
			title: 'Selecteer uitvoerdatum',
			validate: function(value) {
				if($.trim(value) == '')
					return 'Vul een datum in';
				}
		});
		<?php if ($offer_last) { ?>
		$('#dob').editable({
			type:  'date',
			pk:    {{ $offer_last->id }},
			name:  'dob',
			url:   '/offer/close',
			send:  'always',
			emptytext: 'Bewerk',
			title: 'Selecteer offertedatum',
			validate: function(value) {
				if($.trim(value) == '')
					return 'Vul een datum in';
			},
			params: function (params) {
				params.project_id = {{ $project->id }};
				return params;
			}
		});
		<?php } ?>
	});
</script>
<div id="wrapper">

	<section class="container fix-footer-bottom">

			@include('calc.wizard', array('page' => 'project'))

			@if(Session::get('success'))
			<div class="alert alert-success">
				<i class="fa fa-check-circle"></i>
				<strong>{{ Session::get('success') }}</strong>
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

			<h2><strong>Project</strong> {{$project->project_name}}</h2>

			@if(!Relation::where('user_id','=', Auth::user()->id)->count())
			<div class="alert alert-info">
				<i class="fa fa-info-circle"></i>
				<strong>Let Op!</strong> Maak eerst een opdrachtgever aan onder {{ HTML::link('/relation/new', 'nieuwe relatie') }}.
			</div>
			@endif

				<div class="tabs nomargin-top">

					<?# -- tabs -- ?>
					<ul class="nav nav-tabs">
						<li class="active">
							<a href="#status" data-toggle="tab">Projectstatus</a>
						</li>
						<li>
							<a href="#project" data-toggle="tab">Projectgegevens</a>
						</li>
						<li>
							<a href="#calc" data-toggle="tab">Uurtarief & Winstpercentages</a>
						</li>
						<li>
							<a href="#hour" data-toggle="tab">Urenregistratie</a>
						</li>
						<li>
							<a href="#hour_overview" data-toggle="tab">Uittrekstaat urenregistratie</a>
						</li>
						<li>
							<a href="#purchase" data-toggle="tab">Inkoopfacturen</a>
						</li>
					</ul>

					<?# -- tabs content -- ?>
					<div class="tab-content">

						<div id="status" class="tab-pane active">
							<div class="row">
								<div class="col-md-3"><strong>Offerte stadium</strong></div>
								<div class="col-md-2"><strong></strong></div>
							</div>
							<div class="row">
								<div class="col-md-3">Calculatie gestart</div>
								<div class="col-md-2"><?php echo date('d-m-Y', strtotime(DB::table('project')->select('created_at')->where('id','=',$project->id)->get()[0]->created_at)); ?></div>
								<div class="col-md-3"><i>Laatste wijziging: <?php echo date('d-m-Y', strtotime(DB::table('project')->select('updated_at')->where('id','=',$project->id)->get()[0]->updated_at)); ?></i></div>
							</div>
							<div class="row">
								<div class="col-md-3">Offerte opgesteld</div>
								<div class="col-md-2"><?php if ($offer_last) { echo date('d-m-Y', strtotime(DB::table('offer')->select('created_at')->where('id','=',$offer_last->id)->get()[0]->created_at)); } ?></div>
								<div class="col-md-3"><i><?php if ($offer_last) { echo 'Laatste wijziging: '.date('d-m-Y', strtotime(DB::table('offer')->select('updated_at')->where('id','=',$offer_last->id)->get()[0]->updated_at)); } ?></i></div>
							</div>
							<div class="row">
								<div class="col-md-3">Opdracht ontvangen</div>
								<div class="col-md-2">
									<?php
										if (!CalculationEndresult::totalProject($project)) {
											echo "Geen offerte bedrag";
										} else {
											if ($offer_last && $offer_last->offer_finish) {
												echo date('d-m-Y', strtotime($offer_last->offer_finish));
											} else {
												echo '<a href="#" id="dob" data-format="dd-mm-yyyy"></a>';
											}
										}
									?>
								</div>
							</div>
								<br>
							<div class="row">
								<div class="col-md-3"><strong>Opdracht stadium</strong></div>
							</div>
							<div class="row">
								<div class="col-md-3">Start uitvoering</div>
								<div class="col-md-2"><a href="#" id="wordexec" data-format="dd-mm-yyyy">{{ $project->work_execution ? date('d-m-Y', strtotime($project->work_execution)) : '' }}</a></div>
								<div class="col-md-3"></div>
							</div>
							<div class="row">
								<div class="col-md-3">Stelposten stellen</div>
								<div class="col-md-2"><i>{{ $project->start_estimate ? date('d-m-Y', strtotime($project->start_estimate)) : '' }}</i></div>
								<div class="col-md-3"><i>{{ $project->update_estimate ? 'Laatste wijziging: '.date('d-m-Y', strtotime($project->update_estimate)) : '' }}</i></div>
							</div>
							<div class="row">
								<div class="col-md-3">Meerwerk toegevoegd</div>
								<div class="col-md-2">{{ $project->start_more ? date('d-m-Y', strtotime($project->start_more)) : '' }}</div>
								<div class="col-md-3"><i>{{ $project->update_more ? 'Laatste wijziging: '.date('d-m-Y', strtotime($project->update_more)) : '' }}</i></div>
							</div>
							<div class="row">
								<div class="col-md-3">Minderwerk verwerkt</div>
								<div class="col-md-2">{{ $project->start_less ? date('d-m-Y', strtotime($project->start_less)) : '' }}</div>
								<div class="col-md-3"><i>{{ $project->update_less ? 'Laatste wijziging: '.date('d-m-Y', strtotime($project->update_less)) : '' }}</i></div>
							</div>
								<br>
							<div class="row">
								<div class="col-md-3"><strong>Financieel</strong></div>
								<div class="col-md-2"><strong>Gefactureerd</strong></div>
								<div class="col-md-3"><strong>Betaalstatus</strong></div>
								<div class="col-md-3"><strong>Bekijk factuur</strong></div>
							</div>
							<?php
							if ($offer_last) {
							$i=0;
							$close = true;
							$invoice_end = Invoice::where('offer_id','=', $offer_last->id)->where('isclose','=',true)->first();
							?>
							@foreach (Invoice::where('offer_id','=', $offer_last->id)->where('isclose','=',false)->orderBy('priority')->get() as $invoice)
							<div class="row">
								<div class="col-md-3">{{ ($i==0 && $offer_last->downpayment ? 'Aanbetaling' : 'Termijnfactuur '.($i+1)) }}</div>
								<div class="col-md-2">
								<?php
								if (!$invoice->bill_date && $close) {
									echo '<a href="javascript:void(0);" data-invoice="'.$invoice->id.'" data-project="'.$project->id.'" class="btn btn-primary btn-xxs doinvclose">Factureren</a>';
									$close=false;
								} else if (!$invoice->bill_date) {
									echo '<a href="/invoice/project-'.$project->id.'/term-invoice-'.$invoice->id.'" class="btn btn-primary btn-xxs">Bekijken</a>';
								} else
									echo date('d-m-Y', strtotime($invoice->bill_date));
								?>
								</div>
								<div class="col-md-3"><?php
								if ($invoice->invoice_close && !$invoice->payment_date)
									echo '<a href="javascript:void(0);" data-invoice="'.$invoice->id.'" data-project="'.$project->id.'" class="btn btn-primary btn-xxs dopay">Betaald</a>';
								elseif ($invoice->invoice_close && $invoice->payment_date)
									echo 'Betaald op '.date('d-m-Y', strtotime($invoice->payment_date));
								?></div>
								<div class="col-md-3"><?php if ($invoice->bill_date){ echo '<a target="blank" href="/invoice/pdf/project-'.$project->id.'/term-invoice-'.$invoice->id.'" class="btn btn-primary btn-xxs">Bekijk PDF</a>'; }?></div>
							</div>
							<?php $i++; ?>
							@endforeach
							@if ($invoice_end)
							<div class="row">
								<div class="col-md-3">Eindfactuur</div>
								<div class="col-md-2">
								<?php
								if (!$invoice_end->bill_date && $close) {
									echo '<a href="javascript:void(0);" data-invoice="'.$invoice_end->id.'" data-project="'.$project->id.'" class="btn btn-primary btn-xxs doinvclose">Factureren</a>';
									$close=false;
								} else if (!$invoice_end->bill_date) {
									echo '<a href="/invoice/project-'.$project->id.'/invoice-'.$invoice_end->id.'" class="btn btn-primary btn-xxs">Bekijken</a>';
								} else
									echo date('d-m-Y', strtotime($invoice_end->bill_date));
								?>
								</div>
								<div class="col-md-3"><?php
								if ($invoice_end->invoice_close && !$invoice_end->payment_date)
									echo '<a href="javascript:void(0);" data-invoice="'.$invoice_end->id.'" data-project="'.$project->id.'" class="btn btn-primary btn-xxs dopay">Betaald</a>';
								elseif ($invoice_end->invoice_close && $invoice_end->payment_date)
									echo 'Betaald op '.date('d-m-Y', strtotime($invoice_end->payment_date));
								?></div>
								<div class="col-md-3"><?php if ($invoice_end->bill_date){ echo '<a target="blank" href="/invoice/pdf/project-'.$project->id.'/invoice-'.$invoice_end->id.'" class="btn btn-primary btn-xxs">Bekijk PDF</a>'; }?></div>
							</div>
							@endif
							<?php }else{ ?>
							<div class="row">
								<div class="col-md-12">Geen geregistreerde uren</div>
							</div>
							<?php } ?>
								<br>
							<div class="row">
								<div class="col-md-3"><strong>Project gesloten</strong></div>
								<div class="col-md-2">{{ $project->project_close ? date('d-m-Y', strtotime($project->project_close)) : '<a href="#" id="projclose" data-format="dd-mm-yyyy">' }}</a></div>
								<div class="col-md-3"></div>
							</div>
						</div>

						<div id="project" class="tab-pane">
						<form method="post" action="/project/update">
							<h5><strong>Gegevens</strong></h5>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="name">Projectnaam*</label>
											<input name="name" id="name" type="text" {{ $project->project_close ? 'disabled' : '' }} value="{{ Input::old('name') ? Input::old('name') : $project->project_name }}" class="form-control" />
											<input type="hidden" name="id" id="id" value="{{ $project->id }}"/>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="contractor">Opdrachtgever*</label>
											<select name="contractor" id="contractor" {{ $project->project_close ? 'disabled' : '' }} class="form-control pointer">
											@foreach (Relation::where('user_id','=', Auth::user()->id)->get() as $relation)
												<option {{ $project->client_id==$relation->id ? 'selected' : '' }} value="{{ $relation->id }}">{{ ucwords($relation->company_name) }}</option>
											@endforeach
											</select>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label for="type">Type</label>
											<select name="type" id="type" {{ $project->project_close ? 'disabled' : '' }} class="form-control pointer">
												@foreach (ProjectType::all() as $type)
													<option {{ $project->type_id==$type->id ? 'selected' : '' }} value="{{ $type->id }}">{{ ucwords($type->type_name) }}</option>
												@endforeach
											</select>
										</div>
									</div>
								</div>
							<h5><strong>Adresgegevens</strong></h5>
									<div class="row">

									<div class="col-md-4">
										<div class="form-group">
											<label for="street">Straat*</label>
											<input name="street" id="street" {{ $project->project_close ? 'disabled' : '' }} type="text" value="{{ Input::old('street') ? Input::old('street') : $project->address_street}}" class="form-control"/>
										</div>
									</div>
									<div class="col-md-1">
										<div class="form-group">
											<label for="address_number">Huis nr.*</label>
											<input name="address_number" {{ $project->project_close ? 'disabled' : '' }} id="address_number" type="text" value="{{ Input::old('address_number') ? Input::old('address_number') : $project->address_number }}" class="form-control"/>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="zipcode">Postcode*</label>
											<input name="zipcode" {{ $project->project_close ? 'disabled' : '' }} id="zipcode" type="text" maxlength="6" value="{{ Input::old('zipcode') ? Input::old('zipcode') : $project->address_postal }}" class="form-control"/>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="city">Plaats*</label>
											<input name="city" {{ $project->project_close ? 'disabled' : '' }} id="city" type="text" value="{{ Input::old('city') ? Input::old('city'): $project->address_city }}" class="form-control"/>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="province">Provincie*</label>
											<select name="province" {{ $project->project_close ? 'disabled' : '' }} id="province" class="form-control pointer">
												@foreach (Province::all() as $province)
													<option {{ $project->province_id==$province->id ? 'selected' : '' }} value="{{ $province->id }}">{{ ucwords($province->province_name) }}</option>
												@endforeach
											</select>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="country">Land*</label>
											<select name="country" {{ $project->project_close ? 'disabled' : '' }} id="country" class="form-control pointer">
												@foreach (Country::all() as $country)
													<option {{ $project->country_id==$country->id ? 'selected' : '' }} value="{{ $country->id }}">{{ ucwords($country->country_name) }}</option>
												@endforeach
											</select>
										</div>
									</div>

								</div>
							<h5><strong>Opmerkingen</strong></h5>
								<div class="row">
									<div class="form-group">
										<div class="col-md-12">
											<textarea name="note" id="note" rows="5" class="form-control">{{ Input::old('note') ? Input::old('note') : $project->note }}</textarea>
										</div>
									</div>
								</div>
								<div class="row">
								<div class="col-md-12">
									<button class="btn btn-primary"><i class="fa fa-check"></i> Opslaan</button>
								</div>
								</div>

								</form>
							</div>

						<div id="calc" class="tab-pane">
						<form method="post" action="/project/updatecalc">
						<input type="hidden" name="id" id="id" value="{{ $project->id }}"/>
							<div class="row">
								<div class="col-md-3"><h5><strong>Eigen uurtarief*</strong></h5></div>
								<div class="col-md-1"></div>
								<div class="col-md-2"><h5><strong>Calculatie</strong></h5></div>
								<div class="col-md-2"><h5><strong>Meerwerk</strong></h5></div>
							</div>
							<div class="row">
								<div class="col-md-3"><label for="hour_rate">Uurtarief excl. BTW</label></div>
								<div class="col-md-1"><div class="pull-right">&euro;</div></div>
								<div class="col-md-2">
									<input name="hour_rate" {{ $project->project_close ? 'disabled' : '' }} id="hour_rate" type="text" value="{{ Input::old('hour_rate') ? Input::old('hour_rate') : number_format($project->hour_rate, 2,",",".") }}" class="form-control form-control-sm-number"/>
								</div>
								<div class="col-md-2">
									<input name="more_hour_rate" {{ $project->project_close ? 'disabled' : '' }} id="more_hour_rate" type="text" value="{{ Input::old('more_hour_rate') ? Input::old('more_hour_rate') : number_format($project->hour_rate_more, 2,",",".") }}" class="form-control form-control-sm-number"/>
								</div>
							</div>

							<h5><strong>Aanneming</strong></h5>
							<div class="row">
								<div class="col-md-3"><label for="profit_material_1">Winstpercentage materiaal</label></div>
								<div class="col-md-1"><div class="pull-right">%</div></div>
								<div class="col-md-2">
									<input name="profit_material_1" {{ $project->project_close ? 'disabled' : '' }} id="profit_material_1" type="number" min="0" max="200" value="{{ Input::old('profit_material_1') ? Input::old('profit_material_1') : $project->profit_calc_contr_mat }}" class="form-control form-control-sm-number"/>
								</div>
								<div class="col-md-2">
									<input name="more_profit_material_1" {{ $project->project_close ? 'disabled' : '' }} id="more_profit_material_1" type="number" min="0" max="200" value="{{ Input::old('more_profit_material_1') ? Input::old('more_profit_material_1') : $project->profit_more_contr_mat }}" class="form-control form-control-sm-number"/>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3"><label for="profit_equipment_1">Winstpercentage materieel</label></div>
								<div class="col-md-1"><div class="pull-right">%</div></div>
								<div class="col-md-2">
									<input name="profit_equipment_1" {{ $project->project_close ? 'disabled' : '' }} id="profit_equipment_1" type="number" min="0" max="200" value="{{ Input::old('profit_equipment_1') ? Input::old('profit_equipment_1') : $project->profit_calc_contr_equip }}" class="form-control form-control-sm-number"/>
								</div>
								<div class="col-md-2">
									<input name="more_profit_equipment_1" {{ $project->project_close ? 'disabled' : '' }} id="more_profit_equipment_1" type="number" min="0" max="200" value="{{ Input::old('more_profit_equipment_1') ? Input::old('more_profit_equipment_1') : $project->profit_more_contr_equip }}" class="form-control form-control-sm-number"/>
								</div>
							</div>

							<h5><strong>Onderaanneming</strong></h5>
							<div class="row">
								<div class="col-md-3"><label for="profit_material_2">Winstpercentage materiaal</label></div>
								<div class="col-md-1"><div class="pull-right">%</div></div>
								<div class="col-md-2">
									<input name="profit_material_2" {{ $project->project_close ? 'disabled' : '' }} id="profit_material_2" type="number" min="0" max="200" value="{{ Input::old('profit_material_2') ? Input::old('profit_material_2') : $project->profit_calc_subcontr_mat }}" class="form-control form-control-sm-number"/>
								</div>
								<div class="col-md-2">
									<input name="more_profit_material_2" {{ $project->project_close ? 'disabled' : '' }} id="more_profit_material_2" type="number" min="0" max="200" value="{{ Input::old('more_profit_material_2') ? Input::old('more_profit_material_2') : $project->profit_more_subcontr_mat }}" class="form-control form-control-sm-number"/>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3"><label for="profit_equipment_2">Winstpercentage materieel</label></div>
								<div class="col-md-1"><div class="pull-right">%</div></div>
								<div class="col-md-2">
									<input name="profit_equipment_2" {{ $project->project_close ? 'disabled' : '' }} id="profit_equipment_2" type="number" min="0" max="200" value="{{ Input::old('profit_equipment_2') ? Input::old('profit_equipment_2') : $project->profit_calc_subcontr_equip }}" class="form-control form-control-sm-number"/>
								</div>
								<div class="col-md-2">
									<input name="more_profit_equipment_2" {{ $project->project_close ? 'disabled' : '' }} id="more_profit_equipment_2" type="number" min="0" max="200" value="{{ Input::old('more_profit_equipment_2') ? Input::old('more_profit_equipment_2') : $project->profit_more_subcontr_equip }}" class="form-control form-control-sm-number"/>
								</div>
							</div><br />
								<div class="row">
								<div class="col-md-12">
									<button class="btn btn-primary"><i class="fa fa-check"></i> Opslaan</button>
								</div>
								</div>
						</form>
						</div>

						<div id="hour" class="tab-pane">
							<table class="table table-striped">
								<thead>
									<tr>
										<th class="col-md-1">Datum</th>
										<th class="col-md-1">Uren</th>
										<th class="col-md-3">Soort</th>
										<th class="col-md-1">Werkzaamheid</th>
										<th class="col-md-3">Omschrijving</th>
										<th class="col-md-1">&nbsp;</th>
										<th class="col-md-1">&nbsp;</th>
										<th class="col-md-1">&nbsp;</th>
										<th class="col-md-1">&nbsp;</th>
									</tr>
								</thead>

								<tbody>
									@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
									@foreach (Activity::where('chapter_id','=', $chapter->id)->get() as $activity)
									@foreach (Timesheet::where('activity_id','=', $activity->id)->get() as $timesheet)
									<tr data-id="{{ $timesheet->id }}">
										<td class="col-md-1">{{ date('d-m-Y', strtotime($timesheet->register_date)) }}</td>
										<td class="col-md-1">{{ number_format($timesheet->register_hour, 2,",",".") }}</td>
										<td class="col-md-3">{{ ucwords(TimesheetKind::find($timesheet->timesheet_kind_id)->kind_name) }}</td>
										<td class="col-md-3">{{ $activity->activity_name }}</td>
										<td class="col-md-1">{{ $timesheet->note }}</td>
										<td class="col-md-1">&nbsp;</td>
										<td class="col-md-1">&nbsp;</td>
										<td class="col-md-1">@if (!$project->project_close)<button class="btn btn-danger btn-xs fa fa-times deleterow"></button>@endif</td>
									</tr>
									@endforeach
									@endforeach
									@endforeach
									@if (!$project->project_close)
									<tr>
										<td class="col-md-1"><input type="date" name="date" id="date" class="form-control-sm-text"/></td>
										<td class="col-md-1"><input type="text" name="hour" id="hour" class="form-control-sm-text"/></td>
										<td class="col-md-2">
											<select name="typename" id="typename" class="form-control-sm-text">
											@foreach (TimesheetKind::all() as $typename)
												<option value="{{ $typename->id }}">{{ ucwords($typename->kind_name) }}</option>
											@endforeach
											</select>
										</td>
										<td class="col-md-4">
											<select name="activity" id="activity" class="form-control-sm-text">
											@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
											@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->get() as $activity)
												<option value="{{ $activity->id }}">{{ $activity->activity_name }}</option>
											@endforeach
											@endforeach
											</select>
										</td>
										<td class="col-md-1"><input type="text" name="note" id="note" class="form-control-sm-text"/></td>
										<td class="col-md-1">&nbsp;</td>
										<td class="col-md-1">&nbsp;</td>
										<td class="col-md-1"><button id="addnew" class="btn btn-primary btn-xs"> Toevoegen</button></td>
									</tr>
									@endif
								</tbody>
							</table>
						</div>

						<div id="hour_overview" class="tab-pane">
							<div class="toogle">
								<div class="toggle active">
									<label>Aanneming</label>
									<div class="toggle-content">
									<table class="table table-striped">
										<?# -- table head -- ?>
										<thead>
											<tr>
												<th class="col-md-2">&nbsp;</th>
												<th class="col-md-4">&nbsp;</th>
												<th class="col-md-2">Gecalculeerde uren</th>
												<th class="col-md-2">Geregistreerde uren</th>
												<th class="col-md-2">Verschil</th>
											</tr>
										</thead>

										<tbody>
											@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
											@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_type_id','=',PartType::where('type_name','=','calculation')->first()->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->get() as $activity)											<tr>
												<td class="col-md-2"><strong>{{ $chapter->chapter_name }}</strong></td>
												<td class="col-md-4">{{ $activity->activity_name }}</td>
												<td class="col-md-2">{{ number_format(TimesheetOverview::calcTotalAmount($activity->id), 2,",","."); }}</td>
												<td class="col-md-2">{{ number_format(Timesheet::where('activity_id','=',$activity->id)->sum('register_hour'), 2,",","."); }}</td>
												<td class="col-md-2">{{ number_format(TimesheetOverview::calcTotalAmount($activity->id)-Timesheet::where('activity_id','=',$activity->id)->sum('register_hour'), 2,",","."); }}</td>
											</tr>
											@endforeach
											@endforeach
										</tbody>
									</table>
									</div>
								</div>

								<div class="toggle active">
									<label>Stelpost</label>
									<div class="toggle-content">
									<table class="table table-striped">
										<?# -- table head -- ?>
										<thead>
											<tr>
												<th class="col-md-2">&nbsp;</th>
												<th class="col-md-4">&nbsp;</th>
												<th class="col-md-2">Gecalculeerde uren</th>
												<th class="col-md-2">Geregistreerde uren</th>
												<th class="col-md-2">Verschil</th>
											</tr>
										</thead>

										<tbody>
											@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
											@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_type_id','=',PartType::where('type_name','=','estimate')->first()->id)->get() as $activity)
											<tr>
												<td class="col-md-2"><strong>{{ $chapter->chapter_name }}</strong></td>
												<td class="col-md-4">{{ $activity->activity_name }}</td>
												<td class="col-md-2">{{ number_format(TimesheetOverview::estimTotalAmount($activity->id), 2,",","."); }}</td>
												<td class="col-md-2">{{ number_format(Timesheet::where('activity_id','=',$activity->id)->sum('register_hour'), 2,",","."); }}</td>
												<td class="col-md-2">{{ number_format(TimesheetOverview::estimTotalAmount($activity->id)-Timesheet::where('activity_id','=',$activity->id)->sum('register_hour'), 2,",","."); }}</td>
											</tr>
											@endforeach
											@endforeach
										</tbody>
									</table>
									</div>
								</div>

								<div class="toggle active">
									<label>Meerwerk</label>
									<div class="toggle-content">
									<table class="table table-striped">
										<?# -- table head -- ?>
										<thead>
											<tr>
												<th class="col-md-2">&nbsp;</th>
												<th class="col-md-4">&nbsp;</th>
												<th class="col-md-2">&nbsp;</th>
												<th class="col-md-2">Geregistreerde uren</th>
												<th class="col-md-2">&nbsp;</th>
											</tr>
										</thead>

										<tbody>
											@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
											@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_type_id','=',PartType::where('type_name','=','calculation')->first()->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->where('detail_id','=',Detail::where('detail_name','=','more')->first()->id)->get() as $activity)
											<tr>
												<td class="col-md-2"><strong>{{ $chapter->chapter_name }}</strong></td>
												<td class="col-md-4">{{ $activity->activity_name }}</td>
												<td class="col-md-2">&nbsp;</td>
												<td class="col-md-2">{{ number_format(Timesheet::where('activity_id','=',$activity->id)->where('timesheet_kind_id','=',TimesheetKind::where('kind_name','=','meerwerk')->first()->id)->sum('register_hour'), 2,",","."); }}</td>
												<td class="col-md-2">&nbsp;</td>
											</tr>
											@endforeach
											@endforeach
										</tbody>
									</table>
									</div>
								</div>

							</div>
						</div>

						<div id="purchase" class="tab-pane">

							<!--<div class="toggle">
								<label>Deze week</label>
								<div class="toggle-content">-->
									<table class="table table-striped">
										<?# -- table head -- ?>
										<thead>
											<tr>
												<th class="col-md-1">Datum</th>
												<th class="col-md-1">Relatie</th>
												<th class="col-md-3">Factuurbedrag</th>
												<th class="col-md-1">Soort</th>
												<th class="col-md-2">Omschrijving</th>
												<th class="col-md-1">&nbsp;</th>
												<th class="col-md-1">&nbsp;</th>
												<th class="col-md-1">&nbsp;</th>
												<th class="col-md-1">&nbsp;</th>
											</tr>
										</thead>

										<tbody>
											@foreach (Purchase::where('project_id','=', $project->id)->get() as $purchase)
											<tr data-id="{{ $purchase->id }}">
												<td class="col-md-1">{{ date('d-m-Y', strtotime($purchase->register_date)) }}</td>
												<td class="col-md-1">{{ Relation::find($purchase->relation_id)->company_name }}</td>
												<td class="col-md-3">{{ '&euro; '.number_format($purchase->amount, 2,",",".") }}</td>
												<td class="col-md-1">{{ ucwords(PurchaseKind::find($purchase->kind_id)->kind_name) }}</td>
												<td class="col-md-3">{{ $purchase->note }}</td>
												<td class="col-md-1">&nbsp;</td>
												<td class="col-md-1">&nbsp;</td>
												<td class="col-md-1">@if (!$project->project_close)<button class="btn btn-danger btn-xs fa fa-times deleterowp"></button>@endif</td>
											</tr>
											@endforeach
											@if (!$project->project_close)
											<tr>
												<td class="col-md-1">
													<input type="date" name="date" id="date" class="form-control-sm-text"/>
												</td>
												<td class="col-md-4">
													<select name="relation" id="relation" class="form-control-sm-text">
													@foreach (Relation::where('user_id','=', Auth::user()->id)->get() as $relation)
														<option {{ $project->client_id==$relation->id ? 'selected' : '' }} value="{{ $relation->id }}">{{ ucwords($relation->company_name) }}</option>
													@endforeach
													</select>
												</td>
												<td class="col-md-2"><input type="text" name="hour" id="hour" class="form-control-sm-text"/></td>
												<td class="col-md-1">
													<select name="typename" id="typename" class="form-control-sm-text">
													@foreach (PurchaseKind::all() as $typename)
														<option value="{{ $typename->id }}">{{ ucwords($typename->kind_name) }}</option>
													@endforeach
													</select>
												</td>
												<td class="col-md-1"><input type="text" name="note" id="note" class="form-control-sm-text"/></td>
												<td class="col-md-1">&nbsp;</td>
												<td class="col-md-1">&nbsp;</td>
												<td class="col-md-1"><button id="addnewpurchase" class="btn btn-primary btn-xs"> Toevoegen</button></td>
											</tr>
											@endif
										</tbody>
									</table>
								<!--</div>
							</div>-->
						</div>
					</div>
				</div>

		</div>

	</section>

</div>
<!-- /WRAPPER -->
@stop
