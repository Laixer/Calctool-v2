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
use \Calctool\Models\EstimateLabor;
use \Calctool\Models\EstimateMaterial;
use \Calctool\Models\EstimateEquipment;
use \Calctool\Models\MoreLabor;
use \Calctool\Models\MoreMaterial;
use \Calctool\Models\MoreEquipment;
use \Calctool\Models\CalculationLabor;
use \Calctool\Models\CalculationMaterial;
use \Calctool\Models\CalculationEquipment;

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

if($common_access_error){ ?>
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
<?php }else{

$type = ProjectType::find($project->type_id);

$offer_last ? $invoice_end = Invoice::where('offer_id','=', $offer_last->id)->where('isclose','=',true)->first() : $invoice_end = null;
								
$estim_total = 0;
$more_total = 0;
$less_total = 0;
$disable_estim = false;
$disable_more = false;
$disable_less = false;

foreach(Chapter::where('project_id','=', $project->id)->get() as $chap) {
	foreach(Activity::where('chapter_id','=', $chap->id)->get() as $activity) {
		$estim_total += EstimateLabor::where('activity_id','=', $activity->id)->count('id');
		$estim_total += EstimateMaterial::where('activity_id','=', $activity->id)->count('id');
		$estim_total += EstimateEquipment::where('activity_id','=', $activity->id)->count('id');

		$more_total += MoreLabor::where('activity_id','=', $activity->id)->count('id');
		$more_total += MoreMaterial::where('activity_id','=', $activity->id)->count('id');
		$more_total += MoreEquipment::where('activity_id','=', $activity->id)->count('id');	

		$less_total += CalculationLabor::where('activity_id','=', $activity->id)->where('isless',true)->count('id');
		$less_total += CalculationMaterial::where('activity_id','=', $activity->id)->where('isless',true)->count('id');
		$less_total += CalculationEquipment::where('activity_id','=', $activity->id)->where('isless',true)->count('id');	
	}
}

//
if ($offer_last) {
	$disable_estim = true;
}
if ($estim_total>0) {
	$disable_estim = true;
}

//
if ($invoice_end && $invoice_end->invoice_close) {
	$disable_more = true;
}
if ($more_total>0) {
	$disable_more = true;
}

//
if ($invoice_end && $invoice_end->invoice_close) {
	$disable_less = true;
}
if ($less_total>0) {
	$disable_less = true;
}

?>

@extends('layout.master')

@section('title', 'Projectdetails')

@push('style')
<link media="all" type="text/css" rel="stylesheet" href="/components/x-editable/dist/bootstrap3-editable/css/bootstrap-editable.css">
<link media="all" type="text/css" rel="stylesheet" href="/plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css">
<link media="all" type="text/css" rel="stylesheet" href="/components/intro.js/introjs.css">
@endpush

@push('scripts')
<script src="/components/x-editable/dist/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
<script src="/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<script src="/plugins/summernote/summernote.min.js"></script>
<!--<script src="/components/intro.js/intro.js"></script>-->
@endpush

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
			sessionStorage.toggleTabProj{{Auth::id()}} = 'project';
			$('#tab-project').addClass('active');
			$('#project').addClass('active');
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
				var json = data;
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
				var json = data;
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
					$rs = data;
					$curThis.replaceWith('Betaald op ' +$rs.payment);
				}).fail(function(e) { console.log(e); });
			}
		});
		$('.doinvclose').click(function(e){
			$curThis = $(this);
			$curproj = $(this).attr('data-project');
			$curinv = $(this).attr('data-invoice');
			$.post("/invoice/invclose", {project: {{ $project->id }}, id: $curinv, projectid: $curproj}, function(data){
				$rs = data;
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
					date: e.date.toISOString(),
					project: {{ $project->id }}
				}, function(data){
					location.reload();
				});
			}
    	});
		$('#wordexec').datepicker().on('changeDate', function(e){
			$('#wordexec').datepicker('hide');
			$.post("/project/updateworkexecution", {
				date: e.date.toISOString(),
				project: {{ $project->id }}
			}, function(data){
				location.reload();
			});
    	});
		$('#wordcompl').datepicker().on('changeDate', function(e){
			$('#wordcompl').datepicker('hide');
			$.post("/project/updateworkcompletion", {
				date: e.date.toISOString(),
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
	    $("[name='use_equipment']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'});
	    $("[name='use_subcontract']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'});
	    $("[name='use_estimate']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'});
	    $("[name='use_more']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'});
	    $("[name='use_less']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'});
	    $("[name='mail_reminder']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'});
	    $("[name='hide_null']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'});
	    
	    $("[name='hour_rate']").change(function() {
	    	if ($("[name='more_hour_rate']").val() == undefined || $("[name='more_hour_rate']").val() == '0,00')
	    		$("[name='more_hour_rate']").val($(this).val());
	    });// bootstrapSwitch({onText: 'Ja',offText: 'Nee'});

	/*if (sessionStorage.introDemo) {
		var demo = introJs().
			setOption('nextLabel', 'Volgende').
			setOption('prevLabel', 'Vorige').
			setOption('skipLabel', 'Overslaan').
			setOption('doneLabel', 'Klaar').
			setOption('showBullets', false).
			onexit(function(){
				sessionStorage.removeItem('introDemo');
			}).onbeforechange(function(){
				sessionStorage.introDemo = this._currentStep;
				if (this._currentStep == 1) {
					$('#tab-calc').addClass('active');
					$('#calc').addClass('active');

					$('#tab-project').removeClass('active');
					$('#project').removeClass('active');
				}
				if (this._currentStep == 3) {
					$('#tab-advanced').addClass('active');
					$('#advanced').addClass('active');

					$('#tab-calc').removeClass('active');
					$('#calc').removeClass('active');
				}
			}).onafterchange(function(){
				var done = this._currentStep;
				if (done == 3) {
					$('.introjs-skipbutton').css("visibility","initial");
				} else {
					$('.introjs-skipbutton').css("visibility","hidden");
				}
				$('.introjs-skipbutton').click(function(){
					if (done == 3) {
						sessionStorage.introDemo = 999;
						window.location.href = '/calculation/project-{{ $project->id }}';
					}
				});
			});

		if (sessionStorage.introDemo == 999) {
			sessionStorage.introDemo = 0;

			demo.start();
		} else {
			demo.goToStep(sessionStorage.introDemo).start();
		}

	}*/

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

			@if (count($errors) > 0)
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
							<a href="#calc" data-toggle="tab" data-step="1" data-intro="Geef je uurtarief en winstpercentages op waarmee je wilt gaan calculeren.">Uurtarief en Winstpercentages</a>
						</li>
						<li id="tab-advanced">
							<a href="#advanced" data-toggle="tab" data-toggle="tab" data-step="3" data-intro="Geef aan of je andere modules wilt laden in je project. Dit kan later ook nog.">Extra opties</a>
						</li>
						@endif
						@if ($share && $share->client_note )
						<li id="tab-communication">
							<a href="#communication" data-toggle="tab">Communicatie opdrachtgever </a>
						</li>
						@endif
					</ul>

					<div class="tab-content">

						<div id="project" class="tab-pane">
							<div class="pull-right">
								<a href="/project-{{ $project->id }}/copy" class="btn btn-primary">Project kopieren</a>
							</div>


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
								
								<h4>Projectstatussen</h4>
								
								<div class="col-md-6">

									<div class="row">
										<div class="col-md-4"><strong>Offerte stadium</strong></div>
										<div class="col-md-4"><strong></strong></div>
										<div class="col-md-4"><i>Laatste wijziging</i></div>
									</div>
									<div class="row">
										<div class="col-md-4">Calculatie gestart</div>
										<div class="col-md-4"><?php echo date('d-m-Y', strtotime(DB::table('project')->select('created_at')->where('id','=',$project->id)->get()[0]->created_at)); ?></div>
										<div class="col-md-4"><i><?php echo date('d-m-Y', strtotime(DB::table('project')->select('updated_at')->where('id','=',$project->id)->get()[0]->updated_at)); ?></i></div>
									</div>
									<div class="row">
										<div class="col-md-4">Offerte opgesteld</div>
										<div class="col-md-4"><?php if ($offer_last) { echo date('d-m-Y', strtotime(DB::table('offer')->select('created_at')->where('id','=',$offer_last->id)->get()[0]->created_at)); } ?></div>
										<div class="col-md-4"><i><?php if ($offer_last) { echo ''.date('d-m-Y', strtotime(DB::table('offer')->select('updated_at')->where('id','=',$offer_last->id)->get()[0]->updated_at)); } ?></i></div>
									</div>
									<div class="row">
										<div class="col-md-4">Opdracht <a data-toggle="tooltip" data-placement="bottom" data-original-title="Vul hier de datum in wanneer je opdracht hebt gekregen op je offerte. De calculatie slaat dan definitief dicht." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></div>
										<div class="col-md-4">{{ $offer_last && $offer_last->offer_finish ? date('d-m-Y', strtotime($offer_last->offer_finish)) : '' }}</div>
									</div>
								</div>
									
								<div class="col-md-6">
									<div class="row">
										<div class="col-md-4"><strong>Opdracht stadium</strong></div>
										<div class="col-md-4"><strong></strong></div>
										<div class="col-md-4"><i>Laatste wijziging</i></div>
									</div>
									<div class="row">
										<div class="col-md-4">Start uitvoering <a data-toggle="tooltip" data-placement="bottom" data-original-title="Vul hier de datum in dat je met uitvoering bent begonnen" href="#"><i class="fa fa-info-circle"></i></a></div>
										<div class="col-md-4"><?php if ($project->project_close) { echo $project->work_execution ? date('d-m-Y', strtotime($project->work_execution)) : ''; }else{ if ($project->work_execution){ echo date('d-m-Y', strtotime($project->work_execution)); }else{ ?><a href="#" id="wordexec">Bewerk</a><?php } } ?></div>
										<div class="col-md-4"></div>
									</div>
									<div class="row">
										<div class="col-md-4">Ospleverdatum <a data-toggle="tooltip" data-placement="bottom" data-original-title="Vul hier de datum in dat je het moet/wilt/verwacht opleveren" href="#"><i class="fa fa-info-circle"></i></a></div>
										<div class="col-md-4"><?php if ($project->project_close) { echo $project->work_completion ? date('d-m-Y', strtotime($project->work_completion)) : ''; }else{ if ($project->work_completion){ echo date('d-m-Y', strtotime($project->work_completion)); }else{ ?><a href="#" id="wordcompl">Bewerk</a><?php } } ?></div>
										<div class="col-md-4"></div>
									</div>
									@if ($project->use_estim)
									<div class="row">
										<div class="col-md-4">Stelposten gesteld</div>
										<div class="col-md-4"><i>{{ $project->start_estimate ? date('d-m-Y', strtotime($project->start_estimate)) : '' }}</i></div>
										<div class="col-md-4"><i>{{ $project->update_estimate ? ''.date('d-m-Y', strtotime($project->update_estimate)) : '' }}</i></div>
									</div>
									@endif
									@if ($project->use_more)
									<div class="row">
										<div class="col-md-4">Meerwerk toegevoegd</div>
										<div class="col-md-4">{{ $project->start_more ? date('d-m-Y', strtotime($project->start_more)) : '' }}</div>
										<div class="col-md-4"><i>{{ $project->update_more ? ''.date('d-m-Y', strtotime($project->update_more)) : '' }}</i></div>
									</div>
									@endif
									@if ($project->use_less)
									<div class="row">
										<div class="col-md-4">Minderwerk verwerkt</div>
										<div class="col-md-4">{{ $project->start_less ? date('d-m-Y', strtotime($project->start_less)) : '' }}</div>
										<div class="col-md-4"><i>{{ $project->update_less ? ''.date('d-m-Y', strtotime($project->update_less)) : '' }}</i></div>
									</div>
									@endif
										<br>
									

												@if (0)
												<div class="row">
													<div class="col-md-2"><strong>Financieel</strong></div>
													<div class="col-md-2"><strong>Gefactureerd</strong></div>
													<div class="col-md-2"><strong>Betaalstatus</strong></div>
													<div class="col-md-2"><strong>Bekijk factuur</strong></div>
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
										<div class="col-md-4"><strong>Project gesloten</strong> <a data-toggle="tooltip" data-placement="bottom" data-original-title="Vul hier de datum in wanneer je project kan worden gesloten. Zijn alle facturen betaald?" href="#"><i class="fa fa-info-circle"></i></a></div>
										<div class="col-md-4">{!! $project->project_close ? date('d-m-Y', strtotime($project->project_close)) : '<a href="#" id="projclose">Bewerk</a>' !!}</a></div>
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
						<div id="calc" class="tab-pane" data-step="2" data-intro="Geef je uurtarief en winstpercentages op waarmee je wilt gaan calculeren.">
						<form method="post" action="/project/updatecalc">
                        {!! csrf_field() !!}
						<input type="hidden" name="id" id="id" value="{{ $project->id }}"/>
							<div class="row">
								<div class="col-md-3"><h5><strong>Eigen uurtarief <a data-toggle="tooltip" data-placement="bottom" data-original-title="Geef hier uw uurtarief op wat door heel de calculatie gebruikt wordt voor dit project. Of stel deze in bij Voorkeuren om bij elk project te kunnen gebruiken." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></strong></h5></div>
								<div class="col-md-1"></div>
								@if ($type->type_name != 'regie')
								<div class="col-md-2"><h5><strong>Calculatie *</strong></h5></div>
								<div class="col-md-2"><h5><strong>Meerwerk *</strong></h5></div>
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
							</div><br/>
								<div class="row">
									<div class="col-md-12">
										<button class="btn btn-primary"><i class="fa fa-check"></i> Opslaan</button>
									</div>
								</div>
						</form>
						</div>
						@endif

						<div id="advanced" class="tab-pane" data-step="4" data-intro="Geef aan of je andere modules wilt laden in je project. Dit kan later ook nog. Klik daarna op opslaan & 'klaar'.">
							
							<form method="POST" action="/project/updateadvanced">
							{!! csrf_field() !!}
							<input type="hidden" name="id" id="id" value="{{ $project->id }}"/>
							
							



							<div class="row">
								<div class="col-md-6">	
									<div class="col-md-3">
										<label for="type"><b>BTW verlegd</b></label>
										<div class="form-group">
											<input name="tax_reverse" disabled type="checkbox" {{ $project->tax_reverse ? 'checked' : '' }}>
										</div>
									</div>
									<div class="col-md-9" style="padding-top:30px;">
										<p>Een project zonder btw bedrag invoeren.</p>.
										<ul>
										  <li>Kan na aanmaken project niet ongedaan gemaakt worden</li>
										</ul>
									</div>
								</div>
								<div class="col-md-6">	
									<div class="col-md-3">
										<label for="type"><b>Stelposten</b></label>
										<div class="form-group">
											<input name="use_estimate" {{ ($disable_estim ? 'disabled' : '') }} type="checkbox" {{ $project->use_estimate ? 'checked' : '' }}>
										</div>
									</div>
									<div class="col-md-9"  style="padding-top:30px;">		
										<p>Voeg stelposten toe aan je calculatie.</p>
										<ul>
										  <li>Definitief te maken voor factuur na opdracht</li>
										  <li>Uit te zetten indien ongebruikt</li>
										</ul>
									</div>
								</div>
							</div>
							<hr>		
							<div class="row">
								<div class="col-md-6">
									<div class="col-md-3">
										<label for="type"><b>Onderaanneming</b></label>
										<div class="form-group">
											<input name="use_subcontract" type="checkbox" {{ $project->use_subcontract ? 'disabled' : '' }} {{ $project->use_subcontract ? 'checked' : '' }}>
										</div>
									</div>
									<div class="col-md-9"  style="padding-top:30px;">
										<p>Voeg onderaanneming toe aan je calculatie.</p>
										<ul>
										  <li>Kan na toevoegen niet ongedaan gemaakt worden</li>
										</ul>
									</div>
								</div>
								<div class="col-md-6">
									<div class="col-md-3">
										<label for="type"><b>Overige</b></label>
										<div class="form-group">
											<input name="use_equipment" type="checkbox" {{ $project->use_equipment ? 'disabled' : '' }} {{ $project->use_equipment ? 'checked' : '' }}>
										</div>
									</div>
									<div class="col-md-9" style="padding-top:30px;">
										<p>Voeg naast arbeid en materiaal een extra calculeerniveau toe aan je calculatie.</p>
										<ul>
										  <li>Bijvoorbeeld voor <i>materieel</i></li>
										  <li>Kan na toevoegen niet ongedaan gemaakt worden</li>
										</ul>
									</div>
								</div>
							</div>
							<hr>
							<div class="row">
								<div class="col-md-6">
									<div class="col-md-3">
									<label for="type"><b>Meerwerk</b></label>
										<div class="form-group">
											<input name="use_more" type="checkbox" {{ ($disable_more ? 'disabled' : '') }} {{ $project->use_more ? 'checked' : '' }}>
										</div>
									</div>
									<div class="col-md-9" style="padding-top:30px;">
										<p>Voeg meerwerk toe aan je project.</p>
										<ul>
										  <li>Aanzetten is pas mogelijk na opdracht</li>
										  <li>Uit te zetten indien ongebruikt</li>
										</ul>
									</div>
								</div>
								<div class="col-md-6">
									<div class="col-md-3">
										<label for="type"><b>Minderwerk</b></label>
										<div class="form-group">
											<input name="use_less" type="checkbox" {{ ($disable_less ? 'disabled' : '') }} {{ $project->use_less ? 'checked' : '' }}>
										</div>
									</div>
									<div class="col-md-9" style="padding-top:30px;">
										<p>Voeg minderwerk toe aan je prpject.</p>
										<ul>
										  <li>Aanzetten is pas mogelijk na opdracht</li>
										  <li>Uit te zetten indien ongebruikt</li>
										</ul>
									</div>
								</div>
							</div>







							@if (0)
							<div class="row">
								<div class="col-md-2">
									<label for="type">Nulregels</label>
									<div class="form-group">
										<input name="hide_null" disabled type="checkbox" {{ $project->hide_null ? 'checked' : '' }}>
									</div>
								</div>
								<div class="col-md-10" style="padding-top:30px;">
									<p>Lege regels verbergen op de offerte en factuur.</p>
								</div>
								<div class="col-md-2">
									<label for="type">Email herinnering aanzetten</label>
									<div class="form-group">
										<input name="mail_reminder" type="checkbox" {{ $project->pref_email_reminder ? 'checked' : '' }}>
									</div>
								</div>
								<div class="col-md-10" style="padding-top:30px;">
									<p>De CalculatieTool.com kan bij digitaal verstuurde offertes en facturen respectievelijk na het verstrijken van de geldigheid van de offerte of ingestelde betalingsconditie van de factuur automatische herinneringen sturen naar je klant. Jij als gebruiker wordt hierover altijd geïnformeerd met een bericht in je notificaties. De tekst in de te verzenden mail staat default ingesteld in je 'voorkeuren' onder 'mijn account', deze is aanpasbaar per account.</p>
								</div>
							</div>
							@endif
							<br/>
							<div class="row">
								<div class="col-md-12">
									<button class="btn btn-primary"><i class="fa fa-check"></i> Opslaan</button>
								</div>
							</div>
							</form>
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
												<button class="btn btn-primary"><i class="fa fa-check"></i> Verzenden</button>
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

									<?php
										$contact=Contact::where('relation_id',$relation->id)->first();
									?>
									<div class="row">
										<label for="name">Contactpersoon</label>
										<span>{{ $contact->getFormalName() }}</span>
									</div>
									<div class="row">
										<label for="name">Telefoon</label>
										<span>{{ $contact->mobile }}</span>
									</div>		
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
