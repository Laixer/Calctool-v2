<?php

use \Calctool\Calculus\CalculationEndresult;
use \Calctool\Models\Relation;
use \Calctool\Models\PurchaseKind;
use \Calctool\Models\Contact;
use \Calctool\Models\Project;
use \Calctool\Models\ProjectType;
use \Calctool\Models\Offer;
use \Calctool\Models\Invoice;
use \Calctool\Models\Wholesale;
use \Calctool\Models\ProjectShare;
use \Calctool\Models\RelationKind;
use \Calctool\Models\Province;
use \Calctool\Models\Country;
use \Calctool\Models\Chapter;
use \Calctool\Models\Activity;
use \Calctool\Models\Timesheet;
use \Calctool\Models\TimesheetKind;
use \Calctool\Models\Purchase;


$common_access_error = false;
$project = Project::find(Route::Input('project_id'));
if (!$project || !$project->isOwner())
	$common_access_error = true;
else {
	$offer_last = Offer::where('project_id',$project->id)->orderBy('created_at', 'desc')->first();
	$share = ProjectShare::where('project_id', $project->id)->first();
	if ($offer_last)
		$cntinv = Invoice::where('offer_id',$offer_last->id)->where('invoice_close',true)->count('id');
	else
		$cntinv = 0;
}

$type = ProjectType::find($project->type_id);
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
		$('#tab-calc').click(function(e){
			sessionStorage.toggleTabProj{{Auth::id()}} = 'calc';
		});
		$('#tab-hour').click(function(e){
			sessionStorage.toggleTabProj{{Auth::id()}} = 'hour';
		});
		$('#tab-advanced').click(function(e){
			sessionStorage.toggleTabProj{{Auth::id()}} = 'advanced';
		});
		$('#tab-purchase').click(function(e){
			sessionStorage.toggleTabProj{{Auth::id()}} = 'purchase';
		});
		$('#tab-communication').click(function(e){
			sessionStorage.toggleTabProj{{Auth::id()}} = 'communication';
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
    			
        $('#summernote').summernote({
            height: $(this).attr("data-height") || 200,
            toolbar: [
                ["style", ["bold", "italic", "underline", "strikethrough", "clear"]],
                ["para", ["ul", "ol", "paragraph"]],
                ["table", ["table"]],
                ["media", ["link", "picture", "video"]],
            ]
        })

        $('.summernote').summernote({
            height: $(this).attr("data-height") || 200,
            toolbar: [
                ["style", ["bold", "italic", "underline", "strikethrough", "clear"]],
                ["para", ["ul", "ol", "paragraph"]],
                ["table", ["table"]],
                ["media", ["link", "picture", "video"]],
            ]
        })
	    $("[name='tax_reverse']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'});
	    $("[name='use_estimate']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'});
	    $("[name='use_more']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'});
	    $("[name='use_less']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'});
	    $("[name='mail_reminder']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'});
	});

</script>
<div id="wrapper">

	<section class="container">

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

			@if ($offer_last)
				@if (CalculationEndresult::totalProject($project) != $offer_last->offer_total)
				<div class="alert alert-warning">
					<i class="fa fa-fa fa-info-circle"></i>
					De invoergegevens zijn gewijzigd ten op zichte van de laatste offerte
				</div>
				@endif
			@endif

			<h2><strong>Project</strong> {{$project->project_name}}</h2>

			@if(!Relation::where('user_id','=', Auth::user()->id)->count())
			<div class="alert alert-info">
				<i class="fa fa-info-circle"></i>
				<strong>Let Op!</strong> Maak eerst een opdrachtgever aan onder <a href="/relation/new">nieuwe relatie</a>.
			</div>
			@endif

				<div class="tabs nomargin-top">

					<ul class="nav nav-tabs">
						<li id="tab-project">
							<a href="#project" data-toggle="tab">Projectgegevens</a>
						</li>
						@if ($type->type_name != 'snelle offerte en factuur')
						<li id="tab-calc">
							<a href="#calc" data-toggle="tab">Uurtarief & Winstpercentages</a>
						</li>
						<li id="tab-advanced">
							<a href="#advanced" data-toggle="tab">Geavanceerd</a>
						</li>
						<li id="tab-status">
							<a href="#status" data-toggle="tab">Projectstatus</a>
						</li>
						@endif
						@if ($share && $share->client_note )
						<li id="tab-communication">
							<a href="#communication" data-toggle="tab">Communicatie opdrachtgever </a>
						</li>
						@endif
					</ul>

					<div class="tab-content">

						<div id="status" class="tab-pane">
							<h4>Project op basis van {{ ProjectType::find($project->type_id)->type_name }}</h4>
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
								<div class="col-md-2">{{ $offer_last && $offer_last->offer_finish ? date('d-m-Y', strtotime($offer_last->offer_finish)) : '' }}</div>
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
							@if (0)
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
							@endif
							<div class="row">
								<div class="col-md-3"><strong>Project gesloten</strong> <a data-toggle="tooltip" data-placement="bottom" data-original-title="Vul hier de datum in wanneer je project kan worden gesloten. Zijn alle facturen betaald?" href="#"><i class="fa fa-info-circle"></i></a></div>
								<div class="col-md-2">{!! $project->project_close ? date('d-m-Y', strtotime($project->project_close)) : '<a href="#" id="projclose">Bewerk</a>' !!}</a></div>
								<div class="col-md-3"></div>
							</div>
						</div>

						<div id="project" class="tab-pane">
						<form method="post" {!! $offer_last && $offer_last->offer_finish ? 'action="/project/update/note"' : 'action="/project/update"' !!}>
   	  	                {!! csrf_field() !!}
						<h4>Projectgegevens</h4>	
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
											@foreach (Relation::where('user_id','=', Auth::id())->get() as $relation)
												<option {{ $project->client_id==$relation->id ? 'selected' : '' }} value="{{ $relation->id }}">{{ RelationKind::find($relation->kind_id)->kind_name == 'zakelijk' ? ucwords($relation->company_name) : (Contact::where('relation_id','=',$relation->id)->first()['firstname'].' '.Contact::where('relation_id','=',$relation->id)->first()['lastname']) }}</option>
											@endforeach
											</select>
											@else
											<select name="contractor" id="contractor" {{ $project->project_close ? 'disabled' : ($offer_last && $offer_last->offer_finish ? 'disabled' : '') }} class="form-control pointer">
											@foreach (Relation::where('user_id','=', Auth::id())->where('active',true)->get() as $relation)
												<option {{ $project->client_id==$relation->id ? 'selected' : '' }} value="{{ $relation->id }}">{{ RelationKind::find($relation->kind_id)->kind_name == 'zakelijk' ? ucwords($relation->company_name) : (Contact::where('relation_id','=',$relation->id)->first()['firstname'].' '.Contact::where('relation_id','=',$relation->id)->first()['lastname']) }}</option>
											@endforeach
											</select>
											@endif
										</div>
									</div>
									<!-- <div class="col-md-2">
										<label for="type">BTW verlegd</label>
										<div class="form-group">
											<input name="tax_reverse" type="checkbox" {{ $project->project_close ? 'disabled' : ($offer_last && $offer_last->offer_finish ? 'disabled' : '') }} {{ $project->tax_reverse ? 'checked' : '' }}>
										</div>
									</div> -->

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
												@foreach (Province::all() as $province)
													<option {{ $project->province_id==$province->id ? 'selected' : '' }} value="{{ $province->id }}">{{ ucwords($province->province_name) }}</option>
												@endforeach
											</select>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="country">Land*</label>
											<select name="country" {{ $project->project_close ? 'disabled' : ($offer_last && $offer_last->offer_finish ? 'disabled' : '') }} id="country" class="form-control pointer">
												@foreach (Country::all() as $country)
													<option {{ $project->country_id==$country->id ? 'selected' : '' }} value="{{ $country->id }}">{{ ucwords($country->country_name) }}</option>
												@endforeach
											</select>
										</div>
									</div>

								</div>
								<h4>Kladblok van project <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit betreft een persoonlijk kladblok van dit project en wordt nergens anders weergegeven." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></h4>
								<div class="row">
									<div class="form-group ">
										<div class="col-md-12">
										<textarea name="note" id="summernote" data-height="200" class="form-control">{{ Input::old('note') ? Input::old('note') : $project->note }}</textarea>

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

						@if ($type->type_name != 'snelle offerte en factuur')
						<div id="calc" class="tab-pane">
						<form method="post" action="/project/updatecalc">
                        {!! csrf_field() !!}
						<input type="hidden" name="id" id="id" value="{{ $project->id }}"/>
							<div class="row">
								<div class="col-md-3"><h5><strong>Eigen uurtarief <a data-toggle="tooltip" data-placement="bottom" data-original-title="Geef hier uw uurtarief op wat door heel de calculatie gebruikt wordt voor dit project. Of stel deze in bij Voorkeuren om bij elk project te kunnen gebruiken." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></strong></h5></div>
								<div class="col-md-1"></div>
								@if ($type->type_name != 'regie')
								<div class="col-md-2"><h5><strong>Calculatie *</strong></h5></div>
								<div class="col-md-2"><h5><strong>Meerwerk</strong></h5></div>
								@endif
							</div>
							<div class="row">
								<div class="col-md-3"><label for="hour_rate">Uurtarief excl. BTW</label></div>
								<div class="col-md-1"><div class="pull-right">&euro;</div></div>
								@if ($type->type_name != 'regie')
								<div class="col-md-2">
									<input name="hour_rate" {{ $project->project_close ? 'disabled' : ($offer_last && $offer_last->offer_finish ? 'disabled' : '') }} type="text" value="{{ Input::old('hour_rate') ? Input::old('hour_rate') : number_format($project->hour_rate, 2,",",".") }}" class="form-control form-control-sm-number"/>
								</div>
								@endif
								<div class="col-md-2">
									<input name="more_hour_rate" {{ $project->project_close ? 'disabled' : ($cntinv ? 'disabled' : '') }} id="more_hour_rate" type="text" value="{{ Input::old('more_hour_rate') ? Input::old('more_hour_rate') : number_format($project->hour_rate_more, 2,",",".") }}" class="form-control form-control-sm-number"/>
								</div>
							</div>

							<h5><strong>Aanneming <a data-toggle="tooltip" data-placement="bottom" data-original-title="Geef hier uw winstpercentage op wat u over uw materiaal en overig wilt gaan rekenen. Of stel deze in bij Voorkeuren om bij elk project te kunnen gebruiken." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></strong></h5></strong></h5>
							<div class="row">
								<div class="col-md-3"><label for="profit_material_1">Winstpercentage materiaal</label></div>
								<div class="col-md-1"><div class="pull-right">%</div></div>
								@if ($type->type_name != 'regie')
								<div class="col-md-2">
									<input name="profit_material_1" {{ $project->project_close ? 'disabled' : ($offer_last && $offer_last->offer_finish ? 'disabled' : '') }} id="profit_material_1" type="number" min="0" max="200" value="{{ Input::old('profit_material_1') ? Input::old('profit_material_1') : $project->profit_calc_contr_mat }}" class="form-control form-control-sm-number"/>
								</div>
								@endif
								<div class="col-md-2">
									<input name="more_profit_material_1" {{ $project->project_close ? 'disabled' : ($cntinv ? 'disabled' : '') }} id="more_profit_material_1" type="number" min="0" max="200" value="{{ Input::old('more_profit_material_1') ? Input::old('more_profit_material_1') : $project->profit_more_contr_mat }}" class="form-control form-control-sm-number"/>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3"><label for="profit_equipment_1">Winstpercentage overig</label></div>
								<div class="col-md-1"><div class="pull-right">%</div></div>
								@if ($type->type_name != 'regie')
								<div class="col-md-2">
									<input name="profit_equipment_1" {{ $project->project_close ? 'disabled' : ($offer_last && $offer_last->offer_finish ? 'disabled' : '') }} id="profit_equipment_1" type="number" min="0" max="200" value="{{ Input::old('profit_equipment_1') ? Input::old('profit_equipment_1') : $project->profit_calc_contr_equip }}" class="form-control form-control-sm-number"/>
								</div>
								@endif
								<div class="col-md-2">
									<input name="more_profit_equipment_1" {{ $project->project_close ? 'disabled' : ($cntinv ? 'disabled' : '') }} id="more_profit_equipment_1" type="number" min="0" max="200" value="{{ Input::old('more_profit_equipment_1') ? Input::old('more_profit_equipment_1') : $project->profit_more_contr_equip }}" class="form-control form-control-sm-number"/>
								</div>
							</div>

							<h5><strong>Onderaanneming <a data-toggle="tooltip" data-placement="bottom" data-original-title="Onderaanneming: Geef hier uw winstpercentage op wat u over het materiaal en overig van uw onderaanneming wilt gaan rekenen. Of stel deze in bij Voorkeuren om bij elk project te kunnen gebruiken." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></strong></h5></strong></h5>
							<div class="row">
								<div class="col-md-3"><label for="profit_material_2">Winstpercentage materiaal</label></div>
								<div class="col-md-1"><div class="pull-right">%</div></div>
								@if ($type->type_name != 'regie')
								<div class="col-md-2">
									<input name="profit_material_2" {{ $project->project_close ? 'disabled' : ($offer_last && $offer_last->offer_finish ? 'disabled' : '') }} id="profit_material_2" type="number" min="0" max="200" value="{{ Input::old('profit_material_2') ? Input::old('profit_material_2') : $project->profit_calc_subcontr_mat }}" class="form-control form-control-sm-number"/>
								</div>
								@endif
								<div class="col-md-2">
									<input name="more_profit_material_2" {{ $project->project_close ? 'disabled' : ($cntinv ? 'disabled' : '') }} id="more_profit_material_2" type="number" min="0" max="200" value="{{ Input::old('more_profit_material_2') ? Input::old('more_profit_material_2') : $project->profit_more_subcontr_mat }}" class="form-control form-control-sm-number"/>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3"><label for="profit_equipment_2">Winstpercentage overig</label></div>
								<div class="col-md-1"><div class="pull-right">%</div></div>
								@if ($type->type_name != 'regie')
								<div class="col-md-2">
									<input name="profit_equipment_2" {{ $project->project_close ? 'disabled' : ($offer_last && $offer_last->offer_finish ? 'disabled' : '') }} id="profit_equipment_2" type="number" min="0" max="200" value="{{ Input::old('profit_equipment_2') ? Input::old('profit_equipment_2') : $project->profit_calc_subcontr_equip }}" class="form-control form-control-sm-number"/>
								</div>
								@endif
								<div class="col-md-2">
									<input name="more_profit_equipment_2" {{ $project->project_close ? 'disabled' : ($cntinv ? 'disabled' : '') }} id="more_profit_equipment_2" type="number" min="0" max="200" value="{{ Input::old('more_profit_equipment_2') ? Input::old('more_profit_equipment_2') : $project->profit_more_subcontr_equip }}" class="form-control form-control-sm-number"/>
								</div>
							</div><br />
								<div class="row">
								<div class="col-md-12">
									<button class="btn btn-primary {{ ($cntinv ? 'disabled' : '') }}"><i class="fa fa-check"></i> Opslaan</button>
								</div>
								</div>
						</form>
						</div>
						@endif

						<div id="advanced" class="tab-pane">
							
							<form method="POST" action="/project/updateadvanced" accept-charset="UTF-8">
							{!! csrf_field() !!}
							<input type="hidden" name="id" id="id" value="{{ $project->id }}"/>
							<div class="row">

								<div class="col-md-4">
									<div class="white-row">
										<h5><strong for="type">BTW verlegd</strong></h5>
										<div class="form-group">
											<input name="tax_reverse" type="checkbox" {{ $project->tax_reverse ? 'checked' : '' }}>
										</div>
										<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.</p>
									</div>
								</div>

								<div class="col-md-4">
									<div class="white-row">
										<h5><strong for="type">Stelposten</strong></h5>
										<div class="form-group">
											<input name="use_estimate" type="checkbox" {{ $project->use_estimate ? 'checked' : '' }}>
										</div>
										<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.</p>
									</div>
								</div>

								<div class="col-md-4">
									<div class="white-row">
										<h5><strong for="type">Meerwerk</strong></h5>
										<div class="form-group">
											<input name="use_more" type="checkbox" {{ $project->use_more ? 'checked' : '' }}>
										</div>
										<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.</p>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									<div class="white-row">
										<h5><strong for="type">Minderwerk</strong></h5>
										<div class="form-group">
											<input name="use_less" type="checkbox" {{ $project->use_less ? 'checked' : '' }}>
										</div>
										<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.</p>
									</div>
								</div>

								<div class="col-md-4">
									<div class="white-row">
										<h5><strong for="type">Email herinnering aanzetten</strong></h5>
										<div class="form-group">
											<input name="mail_reminder" type="checkbox" {{ $project->pref_email_reminder ? 'checked' : '' }}>
										</div>
										<p>De CalculatieTool.com kan bij digitaal verstuurde offertes en facturen respectievelijk na het verstrijken van geldigheid van de offerte of ingestelde betalingsconditie van de factuur auomatische herinneringen sturen naar je klant. Jij als gebruiker wordt hierover altijd geinformeerd met een bericht in je notificaties. De teskt in de te verzenden mail staat default ingesteld in je 'voorkeuren' onder 'mijn account', deze is aanpasbaar per account.</p>
									</div>
								</div>
							</div>
							<br/>
							<div class="row">
								<div class="col-md-12">
									<button class="btn btn-primary {{ ($cntinv ? 'disabled' : '') }}"><i class="fa fa-check"></i> Opslaan</button>
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
										<th class="col-md-3">Soort <a data-toggle="tooltip" data-placement="bottom" data-original-title="Het is niet mogelijk een urenregistratie bij te houden van onderaanneming." href="#"><i class="fa fa-info-circle"></i></a></th>
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
									@foreach (Timesheet::where('activity_id','=', $activity->id)->orderBy('register_date','desc')->get() as $timesheet)
									<tr data-id="{{ $timesheet->id }}">
										<td class="col-md-1">{{ date('d-m-Y', strtotime($timesheet->register_date)) }}</td>
										<td class="col-md-1">{{ number_format($timesheet->register_hour, 2,",",".") }}</td>
										<td class="col-md-3">{{ ucwords(\Calctool\Models\TimesheetKind::find($timesheet->timesheet_kind_id)->kind_name) }}</td>
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
												<option selected="selected" >Selecteer</option>
												@foreach (TimesheetKind::all() as $typename)
												<option value="{{ $typename->id }}">{{ ucwords($typename->kind_name) }}</option>
												@endforeach
											</select>
										</td>
										<td class="col-md-4">
											<select disabled="disabled" name="activity" id="activity" class="form-control-sm-text"></select>
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

						<div id="purchase" class="tab-pane">

							<!--<div class="toggle">
								<label>Deze week</label>
								<div class="toggle-content">-->
									<table class="table table-striped">
										<thead>
											<tr>
												<th class="col-md-1">Datum</th>
												<th class="col-md-2">Relatie <a data-toggle="tooltip" data-placement="bottom" data-original-title="Kies hier uw relatie waar de inkoopfactuur betrekking op heeft. Staat uw relatie er nog niet bij, maak dan eerst een nieuwe relatie aan." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></th>
												<th class="col-md-2">Bedrag (Excl. BTW) <a data-toggle="tooltip" data-placement="bottom" data-original-title="Hier plaatst u alle facturen van uw project (facturen materiaal, overig en onderaannemers). Deze worden gebruikt voor uw winst en verlies berekening." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></th>

												<th class="col-md-2">Soort <a data-toggle="tooltip" data-placement="bottom" data-original-title="Geef hier aan waar de inkoopfactuur betrekking op heeft." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></th>
												<th class="col-md-4">Omschrijving</th>
												<th class="col-md-1">&nbsp;</th>
											</tr>
										</thead>

										<tbody>
											@foreach (Purchase::where('project_id','=', $project->id)->get() as $purchase)
											<tr data-id="{{ $purchase->id }}">
												<td class="col-md-1">{{ date('d-m-Y', strtotime($purchase->register_date)) }}</td>
												<td class="col-md-2">{{ $purchase->relation_id ? Relation::find($purchase->relation_id)->company_name : Wholesale::find($purchase->wholesale_id)->company_name }}</td>
												<td class="col-md-1">{{ '&euro; '.number_format($purchase->amount, 2,",",".") }}</td>
												<td class="col-md-2">{{ ucwords(PurchaseKind::find($purchase->kind_id)->kind_name) }}</td>
												<td class="col-md-4">{{ $purchase->note }}</td>
												<td class="col-md-1">@if (!$project->project_close)<button class="btn btn-danger btn-xs fa fa-times deleterowp"></button>@endif</td>
											</tr>
											@endforeach
											@if (!$project->project_close)
											<tr>
												<td class="col-md-1">
													<input type="date" name="date" id="date" class="form-control-sm-text"/>
												</td>
												<td class="col-md-2">
													<select name="relation" id="relation" class="form-control-sm-text">
													@foreach (Relation::where('user_id','=', Auth::id())->where('active',true)->get() as $relation)
														<option value="rel-{{ $relation->id }}">{{ ucwords($relation->company_name) }}</option>
													@endforeach
													@foreach (Wholesale::where('user_id','=', Auth::id())->where('active',true)->get() as $wholesale)
														<option value="whl-{{ $wholesale->id }}">{{ ucwords($wholesale->company_name) }}</option>
													@endforeach
													@foreach (Wholesale::whereNull('user_id')->get() as $wholesale)
														<option value="whl-{{ $wholesale->id }}">{{ ucwords($wholesale->company_name) }}</option>
													@endforeach
													</select>
												</td>
												<td class="col-md-2"><input type="text" name="hour" id="hour" class="form-control-sm-text"/></td>
												<td class="col-md-2">
													<select name="typename" id="typename" class="form-control-sm-text">
													@foreach (PurchaseKind::all() as $typename)
														<option value="{{ $typename->id }}">{{ ucwords($typename->kind_name) }}</option>
													@endforeach
													</select>
												</td>
												<td class="col-md-4"><input type="text" name="note" id="note" class="form-control-sm-text"/></td>
												<td class="col-md-1"><button id="addnewpurchase" class="btn btn-primary btn-xs"> Toevoegen</button></td>
											</tr>
											@endif
										</tbody>
									</table>
								<!--</div>
							</div>-->
						</div>
						<div id="communication" class="tab-pane">
							<div class="form-group">
								<div class="col-md-9">
									<form method="POST" action="/project/update/communication" accept-charset="UTF-8">
		                            {!! csrf_field() !!}
		                            <input type="hidden" name="project" value="{{ $project->id }}"/>

		                           	<h5><strong>Vraag opmerkingen van je opdrachtgever </strong><a data-toggle="tooltip" data-placement="bottom" data-original-title="Alleen mogelijk wanneer een offerte verzonden is per e-mail op de offerte pagina." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></h5>
									<div class="row">
										<div class="form-group">
											<div class="col-md-12">
												<div class="white-row well">
													{!!  $share ? $share->client_note : ''!!}
												</div>
											</div>
										</div>
									</div>
									<h5><strong>Jouw reactie</strong></h5>
									<div class="row">
										<div class="form-group">
											<div class="col-md-12">
												<textarea name="user_note" id="user_note" rows="10" class="summernote form-control">{{ $share ? $share->user_note : ''}}</textarea>
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
								<div class="col-md-3">
									<div class="row">
										<h5><strong>Gegevens van uw relatie</strong></h5>
									</div>
									<div class="row">
										<label>Opdrachtgever </label>
										<?php $relation = Relation::find($project->client_id); ?>
										@if (!$relation->isActive())
											<span> {{ RelationKind::find($relation->kind_id)->kind_name == 'zakelijk' ? ucwords($relation->company_name) : (Contact::where('relation_id','=',$relation->id)->first()['firstname'].' '.Contact::where('relation_id','=',$relation->id)->first()['lastname']) }}</span>
										@else
											<span> {{ RelationKind::find($relation->kind_id)->kind_name == 'zakelijk' ? ucwords($relation->company_name) : (Contact::where('relation_id','=',$relation->id)->first()['firstname'].' '.Contact::where('relation_id','=',$relation->id)->first()['lastname']) }}</span>
										@endif
									</div>
									<div class="row">
										<label for="name">Straat</label>
										<span>{{ $relation->address_street }} {{ $relation->address_number }}</span>
									</div>
									<div class="row">
										<label for="name">Postcode</label>
										<span>{{ $relation->address_postal }}</span>
									</div>
									<div class="row">
										<label for="name">Plaats</label>
										<span>{{ $relation->address_city }}</span>
									</div>
									<div class="row">
										<label for="name">Contactpersoon</label>
										<span>{{ $relation->address_city }}</span>
									</div>
									<div class="row">
										<label for="name">Telefoon</label>
										<span>{{ $relation->address_city }}</span>
									</div>									
								</div>
							</div>
						</div>
				</div>

		</div>

	</section>

</div>
@stop
<?php } ?>
