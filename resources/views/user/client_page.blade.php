<?php

use \Calctool\Calculus\CalculationEndresult;
use \Calctool\Models\Relation;
use \Calctool\Models\Resource;
use \Calctool\Models\PurchaseKind;
use \Calctool\Models\RelationKind;
use \Calctool\Models\Contact;
use \Calctool\Models\Country;
use \Calctool\Models\Province;
use \Calctool\Models\Project;
use \Calctool\Models\Offer;
use \Calctool\Models\Invoice;
use \Calctool\Models\Wholesale;
use \Calctool\Models\ProjectShare;
use \Calctool\Models\User;


$common_access_error = false;
$share = ProjectShare::where('token', Route::Input('token'))->first();
$project = Project::find($share->project_id);
if (!$project)
	$common_access_error = true;
else {
	$offer_last = Offer::where('project_id','=',$project->id)->orderBy('created_at', 'desc')->first();
	$relation_self = Relation::find(User::find($project->user_id)->self_id);
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


@section('header')

<header id="topNav" class="topHead">
	<div class="container">

		<button class="btn btn-mobile" data-toggle="collapse" data-target=".nav-main-collapse">
			<i class="fa fa-bars"></i>
		</button>

		<div class="row">
			<div class="col-md-3">
				<a class="logo" href="/">
				{!! ($relation_self && $relation_self->logo_id) ? "<img src=\"/".Resource::find($relation_self->logo_id)->file_location."\" class=\"img-responsive\" width=\"200px\" />" : '' !!}
				</a>
			</div>
		</div>
	</div>
</header>
@endsection

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
						<li id="tab-desc">
							<a href="#desc" data-toggle="tab">Communicatie met uw vakaman</a>
						</li>
					</ul>

					<div class="tab-content">

						<div id="status" class="tab-pane">
							<div class="row">
								<div class="col-md-12">
								<br>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="col-md-4">	
										<div class="row">
											<h4>Projectgegevens</h4>
										</div>
										<div class="row">
											<label for="name">Projectnaam</label>
											<span>{{ $project->project_close ? 'disabled' : ($offer_last && $offer_last->offer_finish ? 'disabled' : $project->project_name) }}</span>
										</div>
										<div class="row">
											<label for="street">Straat </label>
											<span> {{ $project->project_close ? 'disabled' : ($offer_last && $offer_last->offer_finish ? 'disabled' : $project->address_street) }} {{ $project->project_close ? 'disabled' : ($offer_last && $offer_last->offer_finish ? 'disabled' : $project->address_number) }}</span>
										</div>
										<div class="row">
											<label for="zipcode">Postcode </label>
											<span> {{ $project->project_close ? 'disabled' : ($offer_last && $offer_last->offer_finish ? 'disabled' : $project->address_postal) }}</span>
										</div>
										<div class="row">
											<label for="city">Plaats </label>
											<span> {{ $project->project_close ? 'disabled' : ($offer_last && $offer_last->offer_finish ? 'disabled' : $project->address_city) }}</span>
										</div>
										<div class="row">
											<label for="province">Provincie </label>
											<span> {{ ucwords(Province::find($project->province_id)->province_name) }} </span>		
										</div>
										<div class="row">
											<label for="country">Land </label>
											<span> {{ ucwords(Country::find($project->country_id)->country_name) }} </span>
										</div>
									</div>

									<div class="col-md-4">
										<div class="row">
											<h4>Uw gegevens</h4>
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

									<div class="col-md-4">	
										<div class="row">
											<h4>Uw vakman</h4>
										</div>

										<div class="row">
											<label for="name">Bedrijfsnaam</label>
											<span>{{ $relation_self->company_name }} </span>
										</div>
										<div class="row">
											<label for="name">Straat</label>
											<span>{{ $relation_self->address_street }} {{ $relation->address_number }}</span>
										</div>
										<div class="row">
											<label for="name">Postcode</label>
											<span>{{ $relation_self->address_postal }}</span>
										</div>
										<div class="row">
											<label for="name">Plaats</label>
											<span>{{ $relation_self->address_city }}</span>
										</div>
										<div class="row">
											<label for="name">Contactpersoon</label>
											<span>{{ $relation_self->address_city }}</span>
										</div>
										<div class="row">
											<label for="name">Telefoon</label>
											<span>{{ $relation_self->address_city }}</span>
										</div>		
									</div>
								</div>
							</div>

							<br>
					
							<div class="row">
								<div class="col-md-12">
								<h4>Projectacties</h4>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">Offerte opgesteld</div>
								<div class="col-md-2"><?php if ($offer_last) { echo date('d-m-Y', strtotime(DB::table('offer')->select('created_at')->where('id','=',$offer_last->id)->get()[0]->created_at)); } ?></div>
								<div class="col-md-3"><i><?php if ($offer_last) { echo 'Laatste wijziging: '.date('d-m-Y', strtotime(DB::table('offer')->select('updated_at')->where('id','=',$offer_last->id)->get()[0]->updated_at)); } ?></i></div>
							</div>
							<div class="row">
								<div class="col-md-3">Opdracht geven <a data-toggle="tooltip" data-placement="bottom" data-original-title="Vul hier de datum in wanneer u opdracht geeft aan uw vakaman. U gaat met deze actie dus akkoord met de offerte." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></div>
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
						</div>
									
						<div id="desc" class="tab-pane">
							<form method="POST" action="/ex-project-overview/{{ $share->token }}/update" accept-charset="UTF-8">
                            {!! csrf_field() !!}

							<h4>Gebruikers opmerkingen</h4>
							<div class="row">
								<div class="form-group">
									<div class="col-md-12">
										<textarea name="user_note" readonly="readonly" id="user_note" rows="15" class="form-control">{{ $share->user_note }}</textarea>
									</div>
								</div>
							</div>
							<h4>Opdrachtgever opmerkingen</h4>
							<div class="row">
								<div class="form-group">
									<div class="col-md-12">
										<textarea name="client_note" id="client_note" rows="15" class="form-control">{{ $share->client_note }}</textarea>
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

