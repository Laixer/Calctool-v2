<?php

use \Calctool\Calculus\CalculationEndresult;
use \Calctool\Models\Relation;
use \Calctool\Models\PurchaseKind;
use \Calctool\Models\Contact;
use \Calctool\Models\Project;
use \Calctool\Models\Offer;
use \Calctool\Models\Invoice;
use \Calctool\Models\Wholesale;
use \Calctool\Models\ProjectShare;

$common_access_error = false;
$share = ProjectShare::where('token', Route::Input('token'))->first();
$project = Project::find($share->project_id);
if (!$project)
	$common_access_error = true;
else {
	$offer_last = Offer::where('project_id','=',$project->id)->orderBy('created_at', 'desc')->first();
	if ($offer_last)
		$cntinv = Invoice::where('offer_id','=', $offer_last->id)->where('invoice_close',true)->count('id');
	else
		$cntinv = 0;
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
		$('#tab-status').click(function(e){
			sessionStorage.toggleTabProj{{Auth::id()}} = 'status';
		});
		$('#tab-project').click(function(e){
			sessionStorage.toggleTabProj{{Auth::id()}} = 'project';
		});
		$('#tab-desc').click(function(e){
			sessionStorage.toggleTabProj{{Auth::id()}} = 'desc';
		});
		if (sessionStorage.toggleTabProj{{Auth::id()}}){
			$toggleOpenTab = sessionStorage.toggleTabProj{{Auth::id()}};
			$('#tab-'+$toggleOpenTab).addClass('active');
			$('#'+$toggleOpenTab).addClass('active');
		} else {
			sessionStorage.toggleTabProj{{Auth::id()}} = 'status';
			$('#tab-status').addClass('active');
			$('#status').addClass('active');
		}
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
				if (json.success) {
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
				}
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
				amount: $hour,
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
			if(confirm('Factuur betalen?')){
				$curThis = $(this);
				$curproj = $(this).attr('data-project');
				$curinv = $(this).attr('data-invoice');
				$.post("/invoice/pay", {project: {{ $project->id }}, id: $curinv, projectid: $curproj}, function(data){
					$rs = jQuery.parseJSON(data);
					$curThis.replaceWith('Betaald op ' +$rs.payment);
				}).fail(function(e) { console.log(e); });
			}
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
		$('#typename').change(function(e){
			$.get('/timesheet/activity/{{ $project->id }}/' + $(this).val(), function(data){
				$('#activity').prop('disabled', false).find('option').remove();
				$('#activity').prop('disabled', false).find('optgroup').remove();
				var groups = new Array();
				$.each(data, function(idx, item) {
					var index = -1;
					for(var i = 0, len = groups.length; i < len; i++) {
					    if (groups[i].group === item.chapter) {
					        groups[i].data.push({value: item.id, text: item.activity_name});
					        index = i;
					        break;
					    }
					}
					if (index == -1) {
						groups.push({group: item.chapter, data: [{value: item.id, text: item.activity_name}]});
					}
				});
				$.each(groups, function(idx, item){
				    $('#activity').append($('<optgroup>', {
				        label: item.group
				    }));
				    $.each(item.data, function(idx2, item2){
					    $('#activity').append($('<option>', {
					        value: item2.value,
					        text : item2.text
					    }));
				    });
				});
			});
		});
		$('#projclose').datepicker().on('changeDate', function(e){
			$('#projclose').datepicker('hide');
			if(confirm('Project sluiten?')){
				$.post("/project/updateprojectclose", {
					date: e.date.toLocaleString(),
					project: {{ $project->id }}
				}, function(data){
					location.reload();
				});
			}
    	});
		$('#wordexec').datepicker().on('changeDate', function(e){
			$('#wordexec').datepicker('hide');
			$.post("/project/updateworkexecution", {
				date: e.date.toLocaleString(),
				project: {{ $project->id }}
			}, function(data){
				location.reload();
			});
    	});
		$('#wordcompl').datepicker().on('changeDate', function(e){
			$('#wordcompl').datepicker('hide');
			$.post("/project/updateworkcompletion", {
				date: e.date.toLocaleString(),
				project: {{ $project->id }}
			}, function(data){
				location.reload();
			});
    	});
		<?php if ($offer_last) { ?>
		$('#dobx').datepicker().on('changeDate', function(e){
			$('#dobx').datepicker('hide');
			$.post("/offer/close", {
				date: e.date.toLocaleString(),
				offer: {{ $offer_last->id }},
				project: {{ $project->id }}
			}, function(data){
				location.reload();
			});
    	});
    	<?php } ?>
	});
</script>
<div id="wrapper">

	<section class="container">

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

				<div class="tabs nomargin-top">

					<ul class="nav nav-tabs">
						<li id="tab-status">
							<a href="#status" data-toggle="tab">Projectstatus</a>
						</li>
						<li id="tab-project">
							<a href="#project" data-toggle="tab">Projectgegevens</a>
						</li>
						<li id="tab-desc">
							<a href="#desc" data-toggle="tab">Opmerkingen</a>
						</li>
					</ul>

					<div class="tab-content">

						<div id="status" class="tab-pane">
							<h4>Project op basis van {{ \Calctool\Models\ProjectType::find($project->type_id)->type_name }}</h4>
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
								<div class="col-md-3">Opdracht ontvangen <a data-toggle="tooltip" data-placement="bottom" data-original-title="Vul hier de datum in wanneer je opdracht hebt gekregen op je offerte. De calculatie slaat dan definitief dicht." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></div>
								<div class="col-md-2">
									<?php
										if (!\Calctool\Calculus\CalculationEndresult::totalProject($project)) {
											echo "Geen offerte bedrag";
										} else {
											if ($offer_last && $offer_last->offer_finish) {
												echo date('d-m-Y', strtotime($offer_last->offer_finish));
											} else if ($offer_last) {
												echo '<a href="#" id="dobx">Bewerk</a>';
											} else {
												echo "Geen offerte bedrag";
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
								<div class="col-md-3">Start uitvoering <a data-toggle="tooltip" data-placement="bottom" data-original-title="Vul hier de datum in dat je met uitvoering bent begonnen" href="#"><i class="fa fa-info-circle"></i></a></div>
								<div class="col-md-2"><?php if ($project->project_close) { echo $project->work_execution ? date('d-m-Y', strtotime($project->work_execution)) : ''; }else{ if ($project->work_execution){ echo date('d-m-Y', strtotime($project->work_execution)); }else{ ?><a href="#" id="wordexec">Bewerk</a><?php } } ?></div>
								<div class="col-md-3"></div>
							</div>
							<div class="row">
								<div class="col-md-3">Geplande opleverdatum <a data-toggle="tooltip" data-placement="bottom" data-original-title="Vul hier de datum in dat je het moet/wilt/verwacht opleveren" href="#"><i class="fa fa-info-circle"></i></a></div>
								<div class="col-md-2"><?php if ($project->project_close) { echo $project->work_completion ? date('d-m-Y', strtotime($project->work_completion)) : ''; }else{ if ($project->work_completion){ echo date('d-m-Y', strtotime($project->work_completion)); }else{ ?><a href="#" id="wordcompl">Bewerk</a><?php } } ?></div>
								<div class="col-md-3"></div>
							</div>
							<div class="row">
								<div class="col-md-3">Stelposten gesteld</div>
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
							$invoice_end = \Calctool\Models\Invoice::where('offer_id','=', $offer_last->id)->where('isclose','=',true)->first();
							?>
							@foreach (\Calctool\Models\Invoice::where('offer_id','=', $offer_last->id)->where('isclose','=',false)->orderBy('priority')->get() as $invoice)
							<div class="row">
								<div class="col-md-3">{{ ($i==0 && $offer_last->downpayment ? 'Aanbetaling' : 'Termijnfactuur '.($i+1)) }}</div>
								<div class="col-md-2">
								<?php
								if (!$invoice->bill_date && $close && !$project->project_close) {
									echo '<a href="javascript:void(0);" data-invoice="'.$invoice->id.'" data-project="'.$project->id.'" class="btn btn-primary btn-xxs doinvclose">Factureren</a>';
									$close=false;
								} else if (!$invoice->bill_date) {
									echo '<a href="/invoice/project-'.$project->id.'/term-invoice-'.$invoice->id . '" class="btn btn-primary btn-xxs">Bekijken</a>';
								} else
									echo date('d-m-Y', strtotime($invoice->bill_date));
								?>
								</div>
								<div class="col-md-3"><?php
								if ($invoice->invoice_close && !$invoice->payment_date && !$project->project_close)
									echo '<a href="javascript:void(0);" data-invoice="'.$invoice->id.'" data-project="'.$project->id.'" class="btn btn-primary btn-xxs dopay">Betaald</a>';
								elseif ($invoice->invoice_close && $invoice->payment_date)
									echo 'Betaald op '.date('d-m-Y', strtotime($invoice->payment_date));
								?></div>
								<div class="col-md-3"><?php if ($invoice->bill_date){ echo '<a target="blank" href="/invoice/pdf/project-'.$project->id.'/term-invoice-'.$invoice->id . ($invoice->option_query ? '?'.$invoice->option_query : '') . '" class="btn btn-primary btn-xxs">Bekijk PDF</a>'; }?></div>
							</div>
							<?php $i++; ?>
							@endforeach
							@if ($invoice_end)
							<div class="row">
								<div class="col-md-3">Eindfactuur</div>
								<div class="col-md-2">
								<?php
								if (!$invoice_end->bill_date && $close && !$project->project_close) {
									echo '<a href="javascript:void(0);" data-invoice="'.$invoice_end->id.'" data-project="'.$project->id.'" class="btn btn-primary btn-xxs doinvclose">Factureren</a>';
									$close=false;
								} else if (!$invoice_end->bill_date) {
									echo '<a href="/invoice/project-'.$project->id.'/invoice-'.$invoice_end->id.'" class="btn btn-primary btn-xxs">Bekijken</a>';
								} else
									echo date('d-m-Y', strtotime($invoice_end->bill_date));
								?>
								</div>
								<div class="col-md-3"><?php
								if ($invoice_end->invoice_close && !$invoice_end->payment_date && !$project->project_close)
									echo '<a href="javascript:void(0);" data-invoice="'.$invoice_end->id.'" data-project="'.$project->id.'" class="btn btn-primary btn-xxs dopay">Betaald</a>';
								elseif ($invoice_end->invoice_close && $invoice_end->payment_date)
									echo 'Betaald op '.date('d-m-Y', strtotime($invoice_end->payment_date));
								?></div>
								<div class="col-md-3"><?php if ($invoice_end->bill_date){ echo '<a target="blank" href="/invoice/pdf/project-'.$project->id.'/invoice-'.$invoice_end->id . ($invoice_end->option_query ? '?'.$invoice_end->option_query : '') . '" class="btn btn-primary btn-xxs">Bekijk PDF</a>'; }?></div>
							</div>
							@endif
							<?php }else{ ?>
							<div class="row">
								<div class="col-md-12">Geen geregistreerde uren</div>
							</div>
							<?php } ?>
								<br>
							<div class="row">
								<div class="col-md-3"><strong>Project gesloten</strong> <a data-toggle="tooltip" data-placement="bottom" data-original-title="Vul hier de datum in wanneer je project kan worden gesloten. Zijn alle facturen betaald?" href="#"><i class="fa fa-info-circle"></i></a></div>
								<div class="col-md-2">{!! $project->project_close ? date('d-m-Y', strtotime($project->project_close)) : '<a href="#" id="projclose">Bewerk</a>' !!}</a></div>
								<div class="col-md-3"></div>
							</div>
						</div>

						<div id="project" class="tab-pane">
						<form method="post" {!! $offer_last && $offer_last->offer_finish ? 'action="/project/update/note"' : 'action="/project/update"' !!}>
   	  	                {!! csrf_field() !!}
							<h5><strong>Gegevens</strong></h5>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="name">Projectnaam*</label>
											<input name="name" id="name" type="text" {{ $project->project_close ? 'disabled' : ($offer_last && $offer_last->offer_finish ? 'disabled' : '') }} value="{{ Input::old('name') ? Input::old('name') : $project->project_name }}" class="form-control" />
											<input type="hidden" name="id" id="id" value="{{ $project->id }}"/>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="contractor">Opdrachtgever*</label>
											@if (!Relation::find($project->client_id)->isActive())
											<select name="contractor" id="contractor" {{ $project->project_close ? 'disabled' : ($offer_last && $offer_last->offer_finish ? 'disabled' : '') }} class="form-control pointer">
											@foreach (\Calctool\Models\Relation::where('user_id','=', Auth::id())->get() as $relation)
												<option {{ $project->client_id==$relation->id ? 'selected' : '' }} value="{{ $relation->id }}">{{ \Calctool\Models\RelationKind::find($relation->kind_id)->kind_name == 'zakelijk' ? ucwords($relation->company_name) : (Contact::where('relation_id','=',$relation->id)->first()['firstname'].' '.Contact::where('relation_id','=',$relation->id)->first()['lastname']) }}</option>
											@endforeach
											</select>
											@else
											<select name="contractor" id="contractor" {{ $project->project_close ? 'disabled' : ($offer_last && $offer_last->offer_finish ? 'disabled' : '') }} class="form-control pointer">
											@foreach (\Calctool\Models\Relation::where('user_id','=', Auth::id())->where('active',true)->get() as $relation)
												<option {{ $project->client_id==$relation->id ? 'selected' : '' }} value="{{ $relation->id }}">{{ \Calctool\Models\RelationKind::find($relation->kind_id)->kind_name == 'zakelijk' ? ucwords($relation->company_name) : (Contact::where('relation_id','=',$relation->id)->first()['firstname'].' '.Contact::where('relation_id','=',$relation->id)->first()['lastname']) }}</option>
											@endforeach
											</select>
											@endif
										</div>
									</div>

								</div>
							<h5><strong>Adresgegevens</strong></h5>
									<div class="row">

									<div class="col-md-4">
										<div class="form-group">
											<label for="street">Straat*</label>
											<input name="street" id="street" {{ $project->project_close ? 'disabled' : ($offer_last && $offer_last->offer_finish ? 'disabled' : '') }} type="text" value="{{ Input::old('street') ? Input::old('street') : $project->address_street}}" class="form-control"/>
										</div>
									</div>
									<div class="col-md-1">
										<div class="form-group">
											<label for="address_number">Huis nr.*</label>
											<input name="address_number" {{ $project->project_close ? 'disabled' : ($offer_last && $offer_last->offer_finish ? 'disabled' : '') }} id="address_number" type="text" value="{{ Input::old('address_number') ? Input::old('address_number') : $project->address_number }}" class="form-control"/>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="zipcode">Postcode*</label>
											<input name="zipcode" {{ $project->project_close ? 'disabled' : ($offer_last && $offer_last->offer_finish ? 'disabled' : '') }} id="zipcode" type="text" maxlength="6" value="{{ Input::old('zipcode') ? Input::old('zipcode') : $project->address_postal }}" class="form-control"/>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="city">Plaats*</label>
											<input name="city" {{ $project->project_close ? 'disabled' : ($offer_last && $offer_last->offer_finish ? 'disabled' : '') }} id="city" type="text" value="{{ Input::old('city') ? Input::old('city'): $project->address_city }}" class="form-control"/>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="province">Provincie*</label>
											<select name="province" {{ $project->project_close ? 'disabled' : ($offer_last && $offer_last->offer_finish ? 'disabled' : '') }} id="province" class="form-control pointer">
												@foreach (\Calctool\Models\Province::all() as $province)
													<option {{ $project->province_id==$province->id ? 'selected' : '' }} value="{{ $province->id }}">{{ ucwords($province->province_name) }}</option>
												@endforeach
											</select>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="country">Land*</label>
											<select name="country" {{ $project->project_close ? 'disabled' : ($offer_last && $offer_last->offer_finish ? 'disabled' : '') }} id="country" class="form-control pointer">
												@foreach (\Calctool\Models\Country::all() as $country)
													<option {{ $project->country_id==$country->id ? 'selected' : '' }} value="{{ $country->id }}">{{ ucwords($country->country_name) }}</option>
												@endforeach
											</select>
										</div>
									</div>

								</div>

								</form>
							</div>
							
							<div id="desc" class="tab-pane">
								<form method="POST" action="myaccount/notepad/save" accept-charset="UTF-8">
	                            {!! csrf_field() !!}

								<h4>Gebruikers opmerkingen</h4>
								<div class="row">
									<div class="form-group">
										<div class="col-md-12">
											<textarea name="notepad" readonly="readonly" id="notepad" rows="15" class="form-control">{{ $share->user_note }}</textarea>
										</div>
									</div>
								</div>
								<h4>Opdrachtgever opmerkingen</h4>
								<div class="row">
									<div class="form-group">
										<div class="col-md-12">
											<textarea name="notepad" id="notepad" rows="15" class="form-control">{{ $share->client_note }}</textarea>
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

					</div>
				</div>

		</div>

	</section>

</div>
@stop
<?php } ?>
