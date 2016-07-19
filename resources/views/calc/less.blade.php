<?php

use \Calctool\Models\Project;
use \Calctool\Models\TimesheetKind;
use \Calctool\Models\SubGroup;
use \Calctool\Models\Chapter;
use \Calctool\Models\Activity;
use \Calctool\Models\PartType;
use \Calctool\Models\Part;
use \Calctool\Models\Tax;
use \Calctool\Models\LessLabor;
use \Calctool\Calculus\LessRegister;
use \Calctool\Models\LessMaterial;
use \Calctool\Models\LessEquipment;
use \Calctool\Calculus\LessOverview;
use \Calctool\Models\ProjectType;
use \Calctool\Models\CalculationLabor;
use \Calctool\Models\CalculationMaterial;
use \Calctool\Models\CalculationEquipment;
use \Calctool\Calculus\LessEndresult;
use \Calctool\Calculus\CalculationRegister;

$common_access_error = false;
$project = Project::find(Route::Input('project_id'));
if (!$project || !$project->isOwner())
	$common_access_error = true;
?>

@extends('layout.master')

@section('title', 'Minderwerk')

@push('scripts')
<script src="/plugins/summernote/summernote.min.js"></script>
<script src="/plugins/jquery.number.min.js"></script>
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
Number.prototype.formatMoney = function(c, d, t){
var n = this,
    c = isNaN(c = Math.abs(c)) ? 2 : c,
    d = d == undefined ? "." : d,
    t = t == undefined ? "," : t,
    s = n < 0 ? "-" : "",
    i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
    j = (j = i.length) > 3 ? j % 3 : 0;
   return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};
	$(document).ready(function() {
		$('.toggle').click(function(e){
			$id = $(this).attr('id');
			if ($(this).hasClass('active')) {
				if (sessionStorage.toggleOpen{{Auth::user()->id}}){
					$toggleOpen = JSON.parse(sessionStorage.toggleOpen{{Auth::user()->id}});
				} else {
					$toggleOpen = [];
				}
				if (!$toggleOpen.length)
					$toggleOpen.push($id);
				for(var i in $toggleOpen){
					if ($toggleOpen.indexOf( $id ) == -1)
						$toggleOpen.push($id);
				}
				sessionStorage.toggleOpen{{Auth::user()->id}} = JSON.stringify($toggleOpen);
			} else {
				$tmpOpen = [];
				if (sessionStorage.toggleOpen{{Auth::user()->id}}){
					$toggleOpen = JSON.parse(sessionStorage.toggleOpen{{Auth::user()->id}});
					for(var i in $toggleOpen){
						if($toggleOpen[i] != $id)
							$tmpOpen.push($toggleOpen[i]);
					}
				}
				sessionStorage.toggleOpen{{Auth::user()->id}} = JSON.stringify($tmpOpen);
			}
		});
		if (sessionStorage.toggleOpen{{Auth::user()->id}}){
			$toggleOpen = JSON.parse(sessionStorage.toggleOpen{{Auth::user()->id}});
			for(var i in $toggleOpen){
				$('#'+$toggleOpen[i]).addClass('active').children('.toggle-content').toggle();
			}
		}
		$('#tab-calculate').click(function(e){
			sessionStorage.toggleTabLess{{Auth::user()->id}} = 'calculate';
		});
		$('#tab-summary').click(function(e){
			sessionStorage.toggleTabLess{{Auth::user()->id}} = 'summary';
			$('#summary').load('summary/project-{{ $project->id }}');
		});
		$('#tab-endresult').click(function(e){
			sessionStorage.toggleTabLess{{Auth::user()->id}} = 'endresult';
			$('#endresult').load('endresult/project-{{ $project->id }}');
		});
		if (sessionStorage.toggleTabLess{{Auth::user()->id}}){
			$toggleOpenTab = sessionStorage.toggleTabLess{{Auth::user()->id}};
			$('#tab-'+$toggleOpenTab).addClass('active');
			$('#'+$toggleOpenTab).addClass('active');
			$('#tab-'+$toggleOpenTab).trigger("click");
		} else {
			sessionStorage.toggleTabLess{{Auth::user()->id}} = 'calculate';
			$('#tab-calculate').addClass('active');
			$('#calculate').addClass('active');
		}
		$(".complete").click(function(e){
			$loc = $(this).attr('data-location');
			window.location.href = $loc;
		});
		$("body").on("change", ".form-control-sm-number", function(){
			$(this).val(parseFloat($(this).val().split('.').join('').replace(',', '.')).formatMoney(2, ',', '.'));
		});
		$(".radio-activity").change(function(){
			$.post("/calculation/updatepart", {project: {{ $project->id }}, value: this.value, activity: $(this).attr("data-id")}).fail(function(e) { console.log(e); });
		});
		$(".select-tax").change(function(){
			$.post("/calculation/updatetax", {project: {{ $project->id }}, value: this.value, activity: $(this).attr("data-id"), type: $(this).attr("data-type")}).fail(function(e) { console.log(e); });
		});
		$("body").on("change", ".dsave", function(){
			var $curThis = $(this);
			if($curThis.closest("tr").attr("data-id")){
				$.post("/less/updatematerial", {
					id: $curThis.closest("tr").attr("data-id"),
					rate: $curThis.closest("tr").find("input[name='rate']").val(),
					amount: $curThis.closest("tr").find("input[name='amount']").val(),
					activity: $curThis.closest("table").attr("data-id"),
					project: {{ $project->id }},
				}, function(data){
					var json = data;
					if (!json.success) {
						$curThis.closest("tr").find("input[name='rate']").val($.number(json.rate,2,',','.')),
						$curThis.closest("tr").find("input[name='amount']").val($.number(json.amount,2,',','.'))
					} else {
						$curThis.closest("tr").find("input").removeClass("error-input");
						if (json.success) {
							$curThis.closest("tr").attr("data-id", json.id);
							var rate = $curThis.closest("tr").find("input[name='rate']").val().toString().split('.').join('').replace(',', '.');
							var amount = $curThis.closest("tr").find("input[name='amount']").val().toString().split('.').join('').replace(',', '.');
							var profit = $curThis.closest("tr").find('td[data-profit]').data('profit');
							$curThis.closest("tr").find(".total-ex-tax").text('€ '+$.number(rate*amount,2,',','.'));
							$curThis.closest("tr").find(".total-incl-tax").text('€ '+$.number(rate*amount*((100+profit)/100),2,',','.'));
							$curThis.closest("tr").find(".total-less").html(json.less_total);
							var sub_total = 0;
							$curThis.closest("tbody").find(".total-incl-tax").each(function(index){
								var _cal = parseInt($(this).text().substring(2).split('.').join('').replace(',', '.'));
								if (_cal)
									sub_total += _cal;
							});
							$curThis.closest("table").find('.mat_subtotal').text('€ '+$.number(sub_total,2,',','.'));
							var sub_total_profit = 0;
							$curThis.closest("tbody").find(".total-ex-tax").each(function(index){
								var _cal = parseInt($(this).text().substring(2).split('.').join('').replace(',', '.'));
								if (_cal)
									sub_total_profit += _cal;
							});
							$curThis.closest("table").find('.mat_subtotal_profit').text('€ '+$.number(sub_total_profit,2,',','.'));
						} else {
							$.each(json.message, function(i, item) {
								if(json.message['name'])
									$curThis.closest("tr").find("input[name='name']").addClass("error-input");
								if(json.message['unit'])
									$curThis.closest("tr").find("input[name='unit']").addClass("error-input");
								if(json.message['rate'])
									$curThis.closest("tr").find("input[name='rate']").addClass("error-input");
								if(json.message['amount'])
									$curThis.closest("tr").find("input[name='amount']").addClass("error-input");
							});
						}
					}
				}).fail(function(e){
					console.log(e);
				});
			}
		});
		$("body").on("change", ".esave", function(){
			var $curThis = $(this);
			if($curThis.closest("tr").attr("data-id")){
				$.post("/less/updateequipment", {
					id: $curThis.closest("tr").attr("data-id"),
					rate: $curThis.closest("tr").find("input[name='rate']").val(),
					amount: $curThis.closest("tr").find("input[name='amount']").val(),
					activity: $curThis.closest("table").attr("data-id"),
					project: {{ $project->id }},
				}, function(data){
					var json = data;
					if (!json.success) {
						$curThis.closest("tr").find("input[name='rate']").val($.number(json.rate,2,',','.')),
						$curThis.closest("tr").find("input[name='amount']").val($.number(json.amount,2,',','.'))
					} else {
						$curThis.closest("tr").find("input").removeClass("error-input");
						if (json.success) {
							$curThis.closest("tr").attr("data-id", json.id);
							var rate = $curThis.closest("tr").find("input[name='rate']").val().toString().split('.').join('').replace(',', '.');
							var amount = $curThis.closest("tr").find("input[name='amount']").val().toString().split('.').join('').replace(',', '.');
							var profit = $curThis.closest("tr").find('td[data-profit]').data('profit');
							$curThis.closest("tr").find(".total-ex-tax").text('€ '+$.number(rate*amount,2,',','.'));
							$curThis.closest("tr").find(".total-incl-tax").text('€ '+$.number(rate*amount*((100+profit)/100),2,',','.'));
							$curThis.closest("tr").find(".total-less").html(json.less_total);
							var sub_total = 0;
							$curThis.closest("tbody").find(".total-incl-tax").each(function(index){
								var _cal = parseInt($(this).text().substring(2).split('.').join('').replace(',', '.'));
								if (_cal)
									sub_total += _cal;
							});
							$curThis.closest("table").find('.equip_subtotal').text('€ '+$.number(sub_total,2,',','.'));
							var sub_total_profit = 0;
							$curThis.closest("tbody").find(".total-ex-tax").each(function(index){
								var _cal = parseInt($(this).text().substring(2).split('.').join('').replace(',', '.'));
								if (_cal)
									sub_total_profit += _cal;
							});
							$curThis.closest("table").find('.equip_subtotal_profit').text('€ '+$.number(sub_total_profit,2,',','.'));
						} else {
							$.each(json.message, function(i, item) {
								if(json.message['name'])
									$curThis.closest("tr").find("input[name='name']").addClass("error-input");
								if(json.message['unit'])
									$curThis.closest("tr").find("input[name='unit']").addClass("error-input");
								if(json.message['rate'])
									$curThis.closest("tr").find("input[name='rate']").addClass("error-input");
								if(json.message['amount'])
									$curThis.closest("tr").find("input[name='amount']").addClass("error-input");
							});
						}
					}
				}).fail(function(e){
					console.log(e);
				});
			}
		});
		$("body").on("change", ".lsave", function(){
			var $curThis = $(this);
			if($curThis.closest("tr").attr("data-id")){
				$.post("/less/updatelabor", {
					id: $curThis.closest("tr").attr("data-id"),
					rate: $curThis.closest("tr").find("input[name='rate']").val(),
					amount: $curThis.closest("tr").find("input[name='amount']").val(),
					activity: $curThis.closest("table").attr("data-id"),
					project: {{ $project->id }},
				}, function(data){
					var json = data;
					if (!json.success) {
						$curThis.closest("tr").find("input[name='amount']").val($.number(json.amount,2,',','.'))
					} else {
						$curThis.closest("tr").find("input").removeClass("error-input");
						if (json.success) {
							$curThis.closest("tr").attr("data-id", json.id);
							var rate = $curThis.closest("tr").find("input[name='rate']").val()
							if (rate) {
								rate.toString().split('.').join('').replace(',', '.');
							} else {
								rate = {{$project->hour_rate}};
							}
							var amount = $curThis.closest("tr").find("input[name='amount']").val().toString().split('.').join('').replace(',', '.');
							$curThis.closest("tr").find(".total-ex-tax").text('€ '+$.number(rate*amount ,2,',','.'));
							$curThis.closest("tr").find(".total-less").html(json.less_total);
						} else {
							$.each(json.message, function(i, item) {
								if(json.message['name'])
									$curThis.closest("tr").find("input[name='name']").addClass("error-input");
								if(json.message['unit'])
									$curThis.closest("tr").find("input[name='unit']").addClass("error-input");
								if(json.message['rate'])
									$curThis.closest("tr").find("input[name='rate']").addClass("error-input");
								if(json.message['amount'])
									$curThis.closest("tr").find("input[name='amount']").addClass("error-input");
							});
						}
					}
				}).fail(function(e){
					console.log(e);
				});
			}
		});
		$("body").on("blur", ".lsave", function(){
			var flag = true;
			var $curThis = $(this);
			if($curThis.closest("tr").attr("data-id"))
				return false;
			$curThis.closest("tr").find("input").each(function(){
				if(!$(this).val())
					flag = false;
			});
			if(flag){
				$.post("/calculation/calc/newlabor", {
					rate: $curThis.closest("tr").find("input[name='rate']").val(),
					amount: $curThis.closest("tr").find("input[name='amount']").val(),
					activity: $curThis.closest("table").attr("data-id"),
					project: {{ $project->id }},
				}, function(data){
					var json = data;
					$curThis.closest("tr").find("input").removeClass("error-input");
					if (json.success) {
						$curThis.closest("tr").attr("data-id", json.id);
						var rate = $curThis.closest("tr").find("input[name='rate']").val()
						if (rate) {
							rate.toString().split('.').join('').replace(',', '.');
						} else {
							rate = {{$project->hour_rate}};
						}
						var amount = $curThis.closest("tr").find("input[name='amount']").val().toString().split('.').join('').replace(',', '.');
						$curThis.closest("tr").find(".total-ex-tax").text('€ '+$.number(rate*amount,2,',','.'));
					} else {
						$.each(json.message, function(i, item) {
							if(json.message['rate'])
								$curThis.closest("tr").find("input[name='rate']").addClass("error-input");
							if(json.message['amount'])
								$curThis.closest("tr").find("input[name='amount']").addClass("error-input");
						});
					}
				}).fail(function(e){
					console.log(e);
				});
			}
		});
		$("body").on("click", ".sresetrow", function(){
			var $curThis = $(this);
			if($curThis.closest("tr").attr("data-id"))
				$.post("/less/resetmaterial", {project: {{ $project->id }}, id: $curThis.closest("tr").attr("data-id")}, function(data){
					var json = data;
					$curThis.closest("tr").find("input[name='rate']").val(json.rate);
					$curThis.closest("tr").find("input[name='amount']").val(json.amount);
					var sub_total = 0;
					$curThis.closest("tbody").find(".total-incl-tax").each(function(index){
						var _cal = parseInt($(this).text().substring(2).split('.').join('').replace(',', '.'));
						if (_cal)
							sub_total += _cal;
					});
					$curThis.closest("table").find('.mat_subtotal').text('€ '+$.number(sub_total,2,',','.'));
					var sub_total_profit = 0;
					$curThis.closest("tbody").find(".total-ex-tax").each(function(index){
						var _cal = parseInt($(this).text().substring(2).split('.').join('').replace(',', '.'));
						if (_cal)
							sub_total_profit += _cal;
					});
					var rate = json.rate.replace(',', '.');
					var amount = json.amount.replace(',', '.');
					var profit = $curThis.closest("tr").find('td[data-profit]').data('profit');
					$curThis.closest("tr").find(".total-incl-tax").text('€ '+$.number(rate*amount*((100+profit)/100),2,',','.'));
					$curThis.closest("tr").find(".total-less").text('€ '+$.number(0 ,2,',','.'));
					$curThis.closest("table").find('.mat_subtotal_profit').text('€ '+$.number(sub_total_profit,2,',','.'));
				}).fail(function(e) { console.log(e); });
		});
		$("body").on("click", ".eresetrow", function(){
			var $curThis = $(this);
			if($curThis.closest("tr").attr("data-id"))
				$.post("/less/resetequipment", {project: {{ $project->id }}, id: $curThis.closest("tr").attr("data-id")}, function(data){
					var json = data;
					$curThis.closest("tr").find("input[name='rate']").val(json.rate);
					$curThis.closest("tr").find("input[name='amount']").val(json.amount);
					var sub_total = 0;
					$curThis.closest("tbody").find(".total-incl-tax").each(function(index){
						var _cal = parseInt($(this).text().substring(2).split('.').join('').replace(',', '.'));
						if (_cal)
							sub_total += _cal;
					});
					$curThis.closest("table").find('.equip_subtotal').text('€ '+$.number(sub_total,2,',','.'));
					var sub_total_profit = 0;
					$curThis.closest("tbody").find(".total-ex-tax").each(function(index){
						var _cal = parseInt($(this).text().substring(2).split('.').join('').replace(',', '.'));
						if (_cal)
							sub_total_profit += _cal;
					});
					var rate = json.rate.replace(',', '.');
					var amount = json.amount.replace(',', '.');
					var profit = $curThis.closest("tr").find('td[data-profit]').data('profit');
					$curThis.closest("tr").find(".total-incl-tax").text('€ '+$.number(rate*amount*((100+profit)/100),2,',','.'));
					$curThis.closest("tr").find(".total-less").text('€ '+$.number(0 ,2,',','.'));
					$curThis.closest("table").find('.equip_subtotal_profit').text('€ '+$.number(sub_total_profit,2,',','.'));
				}).fail(function(e) { console.log(e); });
		});
		$("body").on("click", ".lresetrow", function(){
			var $curThis = $(this);
			if($curThis.closest("tr").attr("data-id"))
				$.post("/less/resetlabor", {project: {{ $project->id }}, id: $curThis.closest("tr").attr("data-id")}, function(data){
					var json = data;
					var amount = json.amount.replace(',', '.');
					var rate = $curThis.closest("tr").find("input[name='rate']").val()
					if (rate) {
						rate.toString().split('.').join('').replace(',', '.');
					} else {
						rate = {{$project->hour_rate}};
					}
					$curThis.closest("tr").find(".total-ex-tax").text('€ '+$.number(rate*amount ,2,',','.'));
					$curThis.closest("tr").find(".total-less").text('€ '+$.number(0 ,2,',','.'));
					$curThis.closest("tr").find("input[name='amount']").val(json.amount);
				}).fail(function(e) { console.log(e); });
		});
		var $notecurr;
		$('.notemod').click(function(e) {
			$notecurr = $(this);
			$curval = $(this).attr('data-note');
			$curid = $(this).attr('data-id');
			$('.summernote').code($curval);
			$('#noteact').val($curid);
		});
		$('#descModal').on('hidden.bs.modal', function() {
			$.post("/calculation/noteactivity", {project: {{ $project->id }}, activity: $('#noteact').val(), note: $('.summernote').code()}, function(){
				$notecurr.attr('data-note', $('.summernote').code());
				$('.summernote').code('');
			}).fail(function(e) { console.log(e); });
		});

        $('.summernote').summernote({
            height: $(this).attr("data-height") || 200,
            toolbar: [
                ["style", ["bold", "italic", "underline", "strikethrough", "clear"]],
                ["para", ["ul", "ol", "paragraph"]],
                ["table", ["table"]],
                ["media", ["link", "picture", "video"]],
            ]
        })

	});
</script>
<div class="modal fade" id="descModal" tabindex="-1" role="dialog" aria-labelledby="descModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel">Omschrijving aanpassen</h4>
			</div>

			<div class="modal-body">
				<div class="form-group">
					<div class="col-md-12">
						<textarea name="note" id="note" rows="5" class="summernote form-control"></textarea>
						<input type="hidden" name="noteact" id="noteact" />
					</div>
				</div>
			</div>

			<div class="modal-footer">
				<button class="btn btn-default" data-dismiss="modal">Sluiten</button>
			</div>

		</div>
	</div>
</div>


<div class="modal fade" id="myYouTube" tabindex="-1" role="dialog" aria-labelledby="mYouTubeLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

			<iframe width="1280" height="720" src="https://www.youtube.com/embed/ojk-rii0UeY?rel=0" frameborder="0" allowfullscreen></iframe>
			
			<div class="modal-body">
				<div class="form-horizontal">


				</div>
			</div>

		</div>
	</div>
</div>


<div id="wrapper">

	<section class="container fix-footer-bottom">

		@include('calc.wizard', array('page' => 'less'))

			<h2 style="margin: 10px 0 20px 0;"><strong>Minderwerk </strong><strong><a data-toggle="tooltip" data-placement="bottom" data-original-title="Hier kunt u hoeveelheden in mindering brengen op de bestaande calculatie bedoeld als minderwerk op de factuur." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a>&nbsp;&nbsp;<a class="fa fa-youtube-play" href="javascript:void(0);" data-toggle="modal" data-target="#myYouTube"></a></strong></h2>

			/h2>

			<div class="tabs nomargin">

				<ul class="nav nav-tabs">
					<li id="tab-calculate">
						<a href="#calculate" data-toggle="tab">
							<i class="fa fa-list"></i> Calculeren minderwerk
						</a>
					</li>
					<li id="tab-summary">
						<a href="#summary" data-toggle="tab">
							<i class="fa fa-align-justify"></i> Uittrekstaat Minderwerk
						</a>
					</li>
					<li id="tab-endresult">
						<a href="#endresult" data-toggle="tab">
							<i class="fa fa-check-circle-o"></i> Eindresultaat Minderwerk
						</a>
					</li>
				</ul>

				<div class="tab-content">
					<div id="calculate" class="tab-pane">
						<div class="toogle">

							@foreach (Chapter::where('project_id','=', $project->id)->orderBy('created_at')->get() as $chapter)
							<div id="toggle-chapter-{{ $chapter->id }}" class="toggle toggle-chapter">
								<label>{{ $chapter->chapter_name }}</label>
								<div class="toggle-content">

									<div class="toogle">
										<?php
										$activity_total = 0;
										foreach (Activity::where('chapter_id','=', $chapter->id)->whereNull('detail_id')->where('part_type_id','=',PartType::where('type_name','=','calculation')->first()->id)->orderBy('created_at')->get() as $activity) {
											if (Part::find($activity->part_id)->part_name=='contracting') {
												$activity_total = LessOverview::activityTotalProfit($activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip, $project);
											} else if (Part::find($activity->part_id)->part_name=='subcontracting') {
												$activity_total = LessOverview::activityTotalProfit($activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip, $project);
											}
										?>
										<div id="toggle-activity-{{ $activity->id }}" class="toggle toggle-activity">
											<label>
												<span>{{ $activity->activity_name }}</span>
												<span style="float: right;margin-right: 30px;">{{ '&euro; '.number_format($activity_total, 2, ",",".") }}</span>
											</label>
											<div class="toggle-content">
												<div class="row">
													<div class="col-md-4"></div>
													<div class="col-md-2"></div>
	    											<div class="col-md-2"></div>
													<div class="col-md-1 text-right"><strong>{{ Part::find($activity->part_id)->part_name=='subcontracting' ? 'Onderaanneming' : 'Aanneming' }}</strong></div>
													<div class="col-md-3 text-right"><button id="pop-{{$chapter->id.'-'.$activity->id}}" data-id="{{ $activity->id }}" data-note="{{ $activity->note }}" data-toggle="modal" data-target="#descModal" class="btn btn-info btn-xs notemod">Omschrijving aanpassen</button></div>
												</div>
												<div class="row">
													<div class="col-md-2"><h4>Arbeid</h4></div>
													<div class="col-md-1 text-right"><strong>BTW</strong></div>
													<div class="col-md-2">{{ Tax::find($activity->tax_labor_id)->tax_rate }}%</div>
													<div class="col-md-6"></div>
												</div>
												<table class="table table-striped" data-id="{{ $activity->id }}">
													<thead>
														<tr>
															<th class="col-md-5">Omschrijving</th>
															<th class="col-md-1">&nbsp;</th>
															<th class="col-md-1">Uurtarief</th>
															<th class="col-md-1">Aantal <a data-toggle="tooltip" data-placement="bottom" data-original-title="Geef hier het nieuwe aantal op." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></th>
															<th class="col-md-1">Prijs</th>
															<th class="col-md-1">Minderw. <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit is het bedrag dat in mindering wordt gebracht op de bestaande calculatie." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></th>
															<th class="col-md-1">&nbsp;</th>
														</tr>
													</thead>

													<tbody>
														@foreach (CalculationLabor::where('activity_id','=', $activity->id)->get() as $labor)
														<?php
														//FUCK LELIJK, MAAR DON"T FIX IF IT AIN"T BROKEN
														$rate = $labor->rate;
														if (Part::find($activity->part_id)->part_name == 'contracting') {
															$rate = $project->hour_rate;
														}
														?>
														<tr data-id="{{ $labor->id }}">
															<td class="col-md-5">Arbeidsuren</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">{{ number_format($project->hour_rate, 2,",",".") }}</td>
															<td class="col-md-1"><input data-id="{{ $activity->id }}" name="amount" type="text" value="{{ number_format($labor->isless ? $labor->less_amount : $labor->amount, 2, ",",".") }}" class="form-control-sm-number labor-amount lsave" /></td>
															<td class="col-md-1"><span class="total-ex-tax">{{ '&euro; '.number_format(CalculationRegister::calcLaborTotal($rate, $labor->isless ? $labor->less_amount : $labor->amount), 2, ",",".") }}</span></td>
															<td class="col-md-1"><span class="total-less"><?php
																$minderw = LessRegister::lessLaborDeltaTotal($labor, $activity, $project);
																if($minderw < 0)
																	echo "<font color=red>&euro; ".number_format($minderw, 2, ",",".")."</font>";
																else
																	echo '&euro; '.number_format($minderw, 2, ",",".");
																?></span>
															</td>
															<td class="col-md-1 text-right"><button class="btn btn-warning lresetrow btn-xs fa fa-undo"></button></td>
														</tr>
														@endforeach
													</tbody>
												</table>

												<div class="row">
													<div class="col-md-2"><h4>Materiaal</h4></div>
													<div class="col-md-1 text-right"><strong>BTW</strong></div>
													<div class="col-md-2">{{ Tax::find($activity->tax_material_id)->tax_rate }}%</div>
													<div class="col-md-2"></div>
												</div>

												<table class="table table-striped" data-id="{{ $activity->id }}">
													<thead>
														<tr>
															<th class="col-md-5">Omschrijving</th>
															<th class="col-md-1">Eenheid</th>
															<th class="col-md-1">&euro; / Eenh. <a data-toggle="tooltip" data-placement="bottom" data-original-title="Geef hier de nieuwe prijs per eenheid op." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></th>
															<th class="col-md-1">Aantal <a data-toggle="tooltip" data-placement="bottom" data-original-title="Geef hier het nieuwe aantal op." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></th>
															<th class="col-md-1">Prijs  <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit is het totaalbedrag van de &euro;/Eenh.vermenigvuldigd met het Aantal, incl. het winstpercentage" href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></th>
															<th class="col-md-1">Minderw. <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit is het bedrag dat in mindering wordt gebracht op de bestaande calculatie." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></th>
															<th class="col-md-1">&nbsp;</th>
														</tr>
													</thead>

													<tbody>
														@foreach (CalculationMaterial::where('activity_id','=', $activity->id)->get() as $material)
														<tr data-id="{{ $material->id }}">
															<td class="col-md-5">{{ $material->material_name }}</td>
															<td class="col-md-1">{{ $material->unit }}</td>
															<td class="col-md-1"><input name="rate" id="name" type="text" value="{{ number_format($material->isless ? $material->less_rate : $material->rate, 2,",",".") }}" class="form-control-sm-number dsave" /></td>
															<td class="col-md-1"><input name="amount" id="name" type="text" value="{{ number_format($material->isless ? $material->less_amount : $material->amount, 2,",",".") }}" class="form-control-sm-number dsave" /></td>
															<td class="col-md-1"><span class="total-incl-tax">
															<?php
																if (Part::find($activity->part_id)->part_name=='contracting') {
																	$profit = $project->profit_calc_contr_mat;
																} else if (Part::find($activity->part_id)->part_name=='subcontracting') {
																	$profit = $project->profit_calc_subcontr_mat;
																}
																echo '&euro; '.number_format(($material->isless ? $material->less_rate * $material->less_amount : $material->rate * $material->amount) *((100+$profit)/100), 2, ",",".");
															?></span>
															</td>
															<td class="col-md-1"><span class="total-less">
															<?php
																if ($material->isless) {
																	$total = ($material->rate * $material->amount) * ((100+$profit)/100);
																	$less_total = ($material->less_rate * $material->less_amount) * ((100+$profit)/100);
																	if($less_total-$total <0)
																		echo "<font color=red>&euro; ".number_format($less_total-$total, 2, ",",".")."</font>";
																	else
																		echo '&euro; '.number_format($less_total-$total, 2, ",",".");
																} else {
																	echo '&euro; 0,00';
																}
															?></span>
															</td>
															<td class="col-md-1 text-right" data-profit="{{$profit}}">
																<button class="btn btn-warning btn-xs sresetrow fa fa-undo"></button>
															</td>
														</tr>
														@endforeach
													</tbody>
													@if (0)
													<tbody>
														<tr>
															<td class="col-md-5"><strong>Totaal</strong></td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">&nbsp;</td>

															<td class="col-md-1"><strong class="mat_subtotal">
															<?php
															if (Part::find($activity->part_id)->part_name=='contracting') {
																$profit = $project->profit_calc_contr_mat;
															} else if (Part::find($activity->part_id)->part_name=='subcontracting') {
																$profit = $project->profit_calc_subcontr_mat;
															}
															echo '&euro; '.number_format(LessRegister::lessMaterialTotalProfit($activity->id, $profit), 2, ",",".");
															?></strong></td>
															<td class="col-md-1"><strong class="mat_subtotal_profit">{{'&euro; ' .number_format(LessRegister::lessMaterialDeltaTotal($activity->id, $profit), 2, ",",".") }}</strong></td>
															<td class="col-md-1">&nbsp;</td>
														</tr>
													</tbody>
													@endif
												</table>

												<div class="row">
													<div class="col-md-2"><h4>Overig</h4></div>
													<div class="col-md-1 text-right"><strong>BTW</strong></div>
													<div class="col-md-2">{{ Tax::find($activity->tax_equipment_id)->tax_rate }}%</div>
													<div class="col-md-8"></div>
												</div>

												<table class="table table-striped" data-id="{{ $activity->id }}">
													<thead>
														<tr>
															<th class="col-md-5">Omschrijving</th>
															<th class="col-md-1">Eenheid</th>
															<th class="col-md-1">&euro; / Eenh. <a data-toggle="tooltip" data-placement="bottom" data-original-title="Geef hier de nieuwe prijs per eenheid op." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></th>
															<th class="col-md-1">Aantal <a data-toggle="tooltip" data-placement="bottom" data-original-title="Geef hier het nieuwe aantal op." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></th>
															<th class="col-md-1">Prijs  <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit is het totaalbedrag van de &euro;/Eenh.vermenigvuldigd met het Aantal, incl. het winstpercentage" href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></th>
															<th class="col-md-1">Minderw. <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit is het bedrag dat in mindering wordt gebracht op de bestaande calculatie." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></th>
															<th class="col-md-1">&nbsp;</th>
														</tr>
													</thead>

													<tbody>
														@foreach (CalculationEquipment::where('activity_id','=', $activity->id)->get() as $equipment)
														<tr data-id="{{ $equipment->id }}">
															<td class="col-md-5">{{ $equipment->equipment_name }}</td>
															<td class="col-md-1">{{ $equipment->unit }}</td>
															<td class="col-md-1"><input name="rate" id="name" type="text" value="{{ number_format($equipment->isless ? $equipment->less_rate : $equipment->rate, 2,",",".") }}" class="form-control-sm-number esave" /></td>
															<td class="col-md-1"><input name="amount" id="name" type="text" value="{{ number_format($equipment->isless ? $equipment->less_amount : $equipment->amount, 2,",",".") }}" class="form-control-sm-number esave" /></td>
															<td class="col-md-1"><span class="total-incl-tax">
															<?php
																if (Part::find($activity->part_id)->part_name=='contracting') {
																	$profit = $project->profit_calc_contr_equip;
																} else if (Part::find($activity->part_id)->part_name=='subcontracting') {
																	$profit = $project->profit_calc_subcontr_equip;
																}
																echo '&euro; '.number_format(($equipment->isless ? $equipment->less_rate * $equipment->less_amount : $equipment->rate * $equipment->amount) *((100+$profit)/100), 2, ",",".");
															?></span></td>
															<td class="col-md-1"><span class="total-less">
															<?php
																if ($equipment->isless) {
																	$total = ($equipment->rate * $equipment->amount) * ((100+$profit)/100);
																	$less_total = ($equipment->less_rate * $equipment->less_amount) * ((100+$profit)/100);
																	if($less_total-$total <0)
																		echo "<font color=red>&euro; ".number_format($less_total-$total, 2, ",",".")."</font>";
																	else
																		echo '&euro; '.number_format($less_total-$total, 2, ",",".");
																} else {
																	echo '&euro; 0,00';
																}
															?></span>
															</td>
															<td class="col-md-1 text-right" data-profit="{{$profit}}">
																<button class="btn btn-warning btn-xs eresetrow fa fa-undo"></button>
															</td>
														</tr>
														@endforeach
													</tbody>
													@if (0)
													<tbody>
														<tr>
															<td class="col-md-5"><strong>Totaal</strong></td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1"><strong class="equip_subtotal">
															<?php
															if (Part::find($activity->part_id)->part_name=='contracting') {
																$profit = $project->profit_calc_contr_equip;
															} else if (Part::find($activity->part_id)->part_name=='subcontracting') {
																$profit = $project->profit_calc_subcontr_equip;
															}
															echo '&euro; '.number_format(LessRegister::lessEquipmentTotalProfit($activity->id, $profit), 2, ",",".");
															?></strong></td>
															<td class="col-md-1"><strong class="equip_subtotal_profit">{{'&euro; ' .number_format(LessRegister::lessEquipmentDeltaTotal($activity->id, $profit), 2, ",",".") }}</strong></th>
															<td class="col-md-1">&nbsp;</td>
														</tr>
													</tbody>
													@endif
												</table>
											</div>
										</div>
										<?php } ?>
									</div>

								</div>
							</div>
							@endforeach
						</div>

					</div>

					<div id="summary" class="tab-pane">
						<div class="row text-center">
							<img src="/images/loading_icon.gif" height="120" />
						</div>
					</div>

					<div id="endresult" class="tab-pane">
						<div class="row text-center">
							<img src="/images/loading_icon.gif" height="120" />
						</div>
					</div>
				</div>

			</div>


		</div>

	</section>

</div>
@stop

<?php } ?>
