<?php

use \Calctool\Models\Project;
use \Calctool\Models\ProductGroup;
use \Calctool\Models\ProductCategory;
use \Calctool\Models\ProductSubCategory;
use \Calctool\Models\ProjectType;
use \Calctool\Models\Chapter;
use \Calctool\Models\Activity;
use \Calctool\Models\FavoriteActivity;
use \Calctool\Models\TimesheetKind;
use \Calctool\Models\SubGroup;
use \Calctool\Models\PartType;
use \Calctool\Models\MoreLabor;
use \Calctool\Models\Tax;
use \Calctool\Models\MoreMaterial;
use \Calctool\Models\MoreEquipment;
use \Calctool\Models\Detail;
use \Calctool\Models\Part;
use \Calctool\Calculus\MoreOverview;
use \Calctool\Calculus\MoreEndresult;
use \Calctool\Calculus\MoreRegister;
use \Calctool\Models\Timesheet;

$common_access_error = false;
$project = Project::find(Route::Input('project_id'));
if (!$project || !$project->isOwner())
	$common_access_error = true;

$type = ProjectType::find($project->type_id);

?>

@extends('layout.master')

@section('title', 'Meerwerk')

@push('style')
<link media="all" type="text/css" rel="stylesheet" href="/components/x-editable/dist/bootstrap3-editable/css/bootstrap-editable.css">
<link media="all" type="text/css" rel="stylesheet" href="/plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css">
@endpush

@push('scripts')
<script src="/components/x-editable/dist/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
<script src="/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
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
		var $newinputtr;
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
			sessionStorage.toggleTabMore{{Auth::user()->id}} = 'calculate';
		});
		$('#tab-summary').click(function(e){
			sessionStorage.toggleTabMore{{Auth::user()->id}} = 'summary';
			$('#summary').load('summary/project-{{ $project->id }}');
		});
		$('#tab-endresult').click(function(e){
			sessionStorage.toggleTabMore{{Auth::user()->id}} = 'endresult';
			$('#endresult').load('endresult/project-{{ $project->id }}');
		});
		if (sessionStorage.toggleTabMore{{Auth::user()->id}}){
			$toggleOpenTab = sessionStorage.toggleTabMore{{Auth::user()->id}};
			$('#tab-'+$toggleOpenTab).addClass('active');
			$('#'+$toggleOpenTab).addClass('active');
			$('#tab-'+$toggleOpenTab).trigger("click");
		} else {
			sessionStorage.toggleTabMore{{Auth::user()->id}} = 'calculate';
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
			if ($(this).val()==2) {
				$(this).closest('.toggle-content').find(".rate").html('<input name="rate" type="text" value="{{ number_format($project->hour_rate_more, 2,",",".") }}" class="form-control-sm-number labor-amount lsave">');
				$(this).closest('.row').find('.hide_if_subcon').hide();
			} else {
				$(this).closest('.toggle-content').find(".rate").text('{{ number_format($project->hour_rate_more, 2,",",".") }}');
				$(this).closest('.row').find('.hide_if_subcon').show();
			}
			$.post("/calculation/updatepart", {project: {{ $project->id }}, value: this.value, activity: $(this).attr("data-id")}).fail(function(e) { console.log(e); });
		});
		$(".select-tax").change(function(){
			$.post("/calculation/updatetax", {project: {{ $project->id }}, value: this.value, activity: $(this).attr("data-id"), type: $(this).attr("data-type")}).fail(function(e) { console.log(e); });
		});
		$("body").on("change", ".newrow", function(){
			var i = 1;
			if($(this).val()){
				if(!$(this).closest("tr").next().length){
					var $curTable = $(this).closest("table");
					$curTable.find("tr:eq(1)").clone().removeAttr("data-id").find("input").each(function(){
						$(this).val("").removeClass("error-input").attr("id", function(_, id){ return id + i });
					}).end().find(".total-ex-tax, .total-incl-tax").text("").end().appendTo($curTable);
					$("button[data-target='#myModal']").on("click", function() {
						$newinputtr = $(this).closest("tr");
					});
					i++;
				}
			}
		});
		$("body").on("change", ".dsave", function(){
			var $curThis = $(this);
			if($curThis.closest("tr").attr("data-id")){
				$.post("/more/updatematerial", {
					id: $curThis.closest("tr").attr("data-id"),
					name: $curThis.closest("tr").find("input[name='name']").val(),
					unit: $curThis.closest("tr").find("input[name='unit']").val(),
					rate: $curThis.closest("tr").find("input[name='rate']").val(),
					amount: $curThis.closest("tr").find("input[name='amount']").val(),
					project: {{ $project->id }},
				}, function(data){
					var json = data;
					$curThis.closest("tr").find("input").removeClass("error-input");
					if (json.success) {
						$curThis.closest("tr").attr("data-id", json.id);
						var rate = $curThis.closest("tr").find("input[name='rate']").val().toString().split('.').join('').replace(',', '.');
						var amount = $curThis.closest("tr").find("input[name='amount']").val().toString().split('.').join('').replace(',', '.');
						var profit = $curThis.closest("tr").find('td[data-profit]').data('profit');
						$curThis.closest("tr").find(".total-ex-tax").text('€ '+$.number(rate*amount,2,',','.'));
						$curThis.closest("tr").find(".total-incl-tax").text('€ '+$.number(rate*amount*((100+profit)/100),2,',','.'));
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
				}).fail(function(e){
					console.log(e);
				});
			}
		});
		$("body").on("change", ".esave", function(){
			var $curThis = $(this);
			if($curThis.closest("tr").attr("data-id")){
				$.post("/more/updateequipment", {
					id: $curThis.closest("tr").attr("data-id"),
					name: $curThis.closest("tr").find("input[name='name']").val(),
					unit: $curThis.closest("tr").find("input[name='unit']").val(),
					rate: $curThis.closest("tr").find("input[name='rate']").val(),
					amount: $curThis.closest("tr").find("input[name='amount']").val(),
					project: {{ $project->id }},
				}, function(data){
					var json = data;
					$curThis.closest("tr").find("input").removeClass("error-input");
					if (json.success) {
						$curThis.closest("tr").attr("data-id", json.id);
						var rate = $curThis.closest("tr").find("input[name='rate']").val().toString().split('.').join('').replace(',', '.');
						var amount = $curThis.closest("tr").find("input[name='amount']").val().toString().split('.').join('').replace(',', '.');
						var profit = $curThis.closest("tr").find('td[data-profit]').data('profit');
						$curThis.closest("tr").find(".total-ex-tax").text('€ '+$.number(rate*amount,2,',','.'));
						$curThis.closest("tr").find(".total-incl-tax").text('€ '+$.number(rate*amount*((100+profit)/100),2,',','.'));
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
				}).fail(function(e){
					console.log(e);
				});
			}
		});
		$("body").on("change", ".lsave", function(){
			var $curThis = $(this);
			if($curThis.closest("tr").attr("data-id")){
				$.post("/more/updatelabor", {
					id: $curThis.closest("tr").attr("data-id"),
					rate: $curThis.closest("tr").find("input[name='rate']").val(),
					amount: $curThis.closest("tr").find("input[name='amount']").val(),
					project: {{ $project->id }},
				}, function(data){
					var json = data;
					$curThis.closest("tr").find("input").removeClass("error-input");
					if (json.success) {
						$curThis.closest("tr").attr("data-id", json.id);
						var rate = $curThis.closest("tr").find("input[name='rate']").val()
						if (rate) {
							rate = rate.toString().split('.').join('').replace(',', '.');
						} else {
							rate = {{ $project->hour_rate_more ? $project->hour_rate_more : 0 }};
						}
						var amount = $curThis.closest("tr").find("input[name='amount']").val().toString().split('.').join('').replace(',', '.');
						$curThis.closest("tr").find(".total-ex-tax").text('€ '+$.number(rate*amount,2,',','.'));
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
				$.post("/more/newlabor", {
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
							rate = {{ $project->hour_rate_more ? $project->hour_rate_more : 0 }};
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
		$("body").on("blur", ".dsave", function(){
			var flag = true;
			var $curThis = $(this);
			if($curThis.closest("tr").attr("data-id"))
				return false;
			$curThis.closest("tr").find("input").each(function(){
				if(!$(this).val())
					flag = false;
			});
			if(flag){
				$.post("/more/newmaterial", {
					name: $curThis.closest("tr").find("input[name='name']").val(),
					unit: $curThis.closest("tr").find("input[name='unit']").val(),
					rate: $curThis.closest("tr").find("input[name='rate']").val(),
					amount: $curThis.closest("tr").find("input[name='amount']").val(),
					activity: $curThis.closest("table").attr("data-id"),
					project: {{ $project->id }},
				}, function(data){
					var json = data;
					$curThis.closest("tr").find("input").removeClass("error-input");
					if (json.success) {
						$curThis.closest("tr").attr("data-id", json.id);
						var rate = $curThis.closest("tr").find("input[name='rate']").val().toString().split('.').join('').replace(',', '.');
						var amount = $curThis.closest("tr").find("input[name='amount']").val().toString().split('.').join('').replace(',', '.');
						var profit = $curThis.closest("tr").find('td[data-profit]').data('profit');
						$curThis.closest("tr").find(".total-ex-tax").text('€ '+$.number(rate*amount,2,',','.'));
						$curThis.closest("tr").find(".total-incl-tax").text('€ '+$.number(rate*amount*((100+profit)/100),2,',','.'));
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
				}).fail(function(e){
					console.log(e);
				});
			}
		});
		$("body").on("blur", ".esave", function(){
			var flag = true;
			var $curThis = $(this);
			if($curThis.closest("tr").attr("data-id"))
				return false;
			$curThis.closest("tr").find("input").each(function(){
				if(!$(this).val())
					flag = false;
			});
			if(flag){
				$.post("/more/newequipment", {
					name: $curThis.closest("tr").find("input[name='name']").val(),
					unit: $curThis.closest("tr").find("input[name='unit']").val(),
					rate: $curThis.closest("tr").find("input[name='rate']").val(),
					amount: $curThis.closest("tr").find("input[name='amount']").val(),
					activity: $curThis.closest("table").attr("data-id"),
					project: {{ $project->id }},
				}, function(data){
					var json = data;
					$curThis.closest("tr").find("input").removeClass("error-input");
					if (json.success) {
						$curThis.closest("tr").attr("data-id", json.id);
						var rate = $curThis.closest("tr").find("input[name='rate']").val().toString().split('.').join('').replace(',', '.');
						var amount = $curThis.closest("tr").find("input[name='amount']").val().toString().split('.').join('').replace(',', '.');
						var profit = $curThis.closest("tr").find('td[data-profit]').data('profit');
						$curThis.closest("tr").find(".total-ex-tax").text('€ '+$.number(rate*amount,2,',','.'));
						$curThis.closest("tr").find(".total-incl-tax").text('€ '+$.number(rate*amount*((100+profit)/100),2,',','.'));
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
				}).fail(function(e){
					console.log(e);
				});
			}
		});
		$(".tsave-save").click(function(){
			var flag = true;
			var $curThis = $(this);
			if(flag){
				$date = $curThis.closest("tr").find("input[name='date']").val();
				$hour = $curThis.closest("tr").find("input[name='hour']").val();
				$type = {{ TimesheetKind::where('kind_name','=','meerwerk')->first()->id }};
				$activity = $curThis.closest("table").attr("data-id");
				$note = $curThis.closest("tr").find("input[name='note']").val();
				$.post("/timesheet/new", {
					date: $date,
					hour: $hour,
					type: $type,
					activity: $activity,
					note: $note,
					project: {{ $project->id }},
				}, function(data){
					var json = data;
					$curThis.closest("tr").find("input").removeClass("error-input");
					if (json.success) {
						$curThis.closest("tr").attr("data-id", json.id);
						var rate = {{ $project->hour_rate_more ? $project->hour_rate_more : 0 }};
						var amount = $curThis.closest("tr").find("input[name='hour']").val().toString().split('.').join('').replace(',', '.');
						var $curTable = $curThis.closest("table");
						var json = data;
						$curTable.find("tr:eq(1)").clone().removeAttr("data-id")
						.find("td:eq(0)").text(json.date).end()
						.find("td:eq(1)").text(json.hour).end()
						.find("td:eq(2)").text('€ '+$.number(rate*amount,2,',','.')).end()
						.find("td:eq(3)").text($note).end()
						.find("td:eq(4)").html('').end()
						.prependTo($curTable);
						$curThis.closest("tr").find("input").val("");
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
				}).fail(function(e){
					console.log(e);
				});
			}
		});
		$("body").on("click", ".xdeleterow", function(){
			var $curThis = $(this);
			if($curThis.closest("tr").attr("data-id"))
				$.post("/timesheet/delete", {project: {{ $project->id }}, id: $curThis.closest("tr").attr("data-id")}, function(){
					$curThis.closest("tr").hide("slow");
				}).fail(function(e) { console.log(e); });
		});
		$("body").on("click", ".sdeleterow", function(){
			var $curThis = $(this);
			if($curThis.closest("tr").attr("data-id"))
				$.post("/more/deletematerial", {project: {{ $project->id }}, id: $curThis.closest("tr").attr("data-id")}, function(){
					$curThis.closest("tr").hide("slow");
				}).fail(function(e) { console.log(e); });
		});
		$("body").on("click", ".edeleterow", function(){
			var $curThis = $(this);
			if($curThis.closest("tr").attr("data-id"))
				$.post("/more/deleteequipment", {project: {{ $project->id }}, id: $curThis.closest("tr").attr("data-id")}, function(){
					$curThis.closest("tr").hide("slow");
				}).fail(function(e) { console.log(e); });
		});
		$("body").on("click", ".ldeleterow", function(){
			var $curThis = $(this);
			if($curThis.closest("tr").attr("data-id"))
				$.post("/more/deletelabor", {project: {{ $project->id }}, id: $curThis.closest("tr").attr("data-id")}, function(){
					$curThis.closest("tr").find("input").val("0,00");
					$curThis.closest("tr").find(".total-ex-tax").text('€ 0,00');
				}).fail(function(e) { console.log(e); });
		});
		$("body").on("click", ".deleteact", function(e){
			e.preventDefault();
			if(confirm('Weet je het zeker?')){
				var $curThis = $(this);
				if($curThis.attr("data-id"))
					$.post("/calculation/deleteactivity", {project: {{ $project->id }}, activity: $curThis.attr("data-id")}, function(){
						$('#toggle-activity-'+$curThis.attr("data-id")).hide('slow');
					}).fail(function(e) { console.log(e); });
			}
		});
		$("body").on("click", ".deletechap", function(e){
			e.preventDefault();
			if(confirm('Weet je het zeker?')){
				var $curThis = $(this);
				if($curThis.attr("data-id"))
					$.post("/more/deletechapter", {project: {{ $project->id }}, chapter: $curThis.attr("data-id")}, function(){
						$curThis.closest('.toggle-chapter').hide('slow');
					}).fail(function(e) { console.log(e); });
			}
		});

		var $favchapid;
		$('.favselect').click(function(e) {
			$favchapid = $(this).attr('data-id');
		});

		$('.favselect').click(function(e) {
			$favchapid = $(this).attr('data-id');
		});

		$('.favlink').click(function(e) {
			window.location.href = '/more/project-{{ $project->id }}/chapter-' + $favchapid + '/fav-' + $(this).attr('data-id');
		});

		$('.changename').click(function(e) {
			$activityid = $(this).attr('data-id');
			$activity_name = $(this).attr('data-name');
			$('#nc_activity').val($activityid);
			$('#nc_activity_name').val($activity_name);
		});

		$req = false;
		$("#search").keyup(function() {
			$val = $(this).val();
			if ($val.length > 2 && !$req) {
				$req = true;
				$.post("/material/search", {project: {{ $project->id }}, query: $val}, function(data) {
					if (data) {
						$('#tbl-material tbody tr').remove();
						$.each(data, function(i, item) {
							$('#tbl-material tbody').append('<tr><td><a data-name="'+item.description+'" data-unit="'+item.punit+'" data-price="'+item.pricenum+'" href="javascript:void(0);">'+item.description+'</a></td><td>'+item.unit+'</td><td>'+item.price+'</td><td>'+item.tprice+'</td></tr>');
						});
						$('#tbl-material tbody a').on("click", onmaterialclick);
						$req = false;
					}
				});
			}
		});

		$('#group').change(function(){
			$val = $(this).val();
			$.post("/material/search", {group:$val}, function(data) {
				if (data) {
					$('#tbl-material tbody tr').remove();
					$.each(data, function(i, item) {
						$('#tbl-material tbody').append('<tr><td><a data-name="'+item.description+'" data-unit="'+item.punit+'" data-price="'+item.pricenum+'" href="javascript:void(0);">'+item.description+'</a></td><td>'+item.unit+'</td><td>'+item.price+'</td><td>'+item.tprice+'</td></tr>');
					});
					$('#tbl-material tbody a').on("click", onmaterialclick);
					$req = false;
				}
			});
		});

		$('.getsub').change(function(e){
			var $name = $('#group2 option:selected').attr('data-name');
			var $value = $('#group2 option:selected').val();

			$.get('/material/subcat/' + $name + '/' + $value, function(data) {
				$('#group').find('option').remove();
			    $.each(data, function(idx, item){
				    $('#group').append($('<option>', {
				        value: item.id,
				        text: item.name
				    }));
			    });

				$.post("/material/search", {group:data[0].id}, function(data) {
					if (data) {
						$('#tbl-material tbody tr').remove();
						$.each(data, function(i, item) {
							$('#tbl-material tbody').append('<tr><td><a data-name="'+item.description+'" data-unit="'+item.punit+'" data-price="'+item.pricenum+'" href="javascript:void(0);">'+item.description+'</a></td><td>'+item.unit+'</td><td>'+item.price+'</td><td>'+item.tprice+'</td></tr>');
						});
						$('#tbl-material tbody a').on("click", onmaterialclick);
						$req = false;
					}
				});
			    
			});

		});
		$("button[data-target='#myModal']").click(function(e) {
			$newinputtr = $(this).closest("tr");
		});
		function onmaterialclick(e) {
			$newinputtr.find("input[name='name']").val($(this).attr('data-name'));
			$newinputtr.find("input[name='unit']").val($(this).attr('data-unit'));
			$newinputtr.find("input[name='rate']").val($(this).attr('data-price'));
			$newinputtr.find(".newrow").change();
			$('#myModal').modal('toggle');
		}
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
		$('.use-timesheet').bootstrapSwitch({onText: 'Ja',offText: 'Nee'}).on('switchChange.bootstrapSwitch', function(event, state) {
			$.post("/calculation/activity/usetimesheet", {project: {{ $project->id }}, activity: $(this).data('id'), state: state}, function(){
				location.reload();
			}).fail(function(e) { console.log(e); });
		});

		$('.datepick').datepicker();

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
<div class="modal fade" id="nameChangeModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
		<form method="POST" action="/calculation/calc/rename_activity" accept-charset="UTF-8">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel2">Naam werkzaamheid</h4>
			</div>

			<div class="modal-body">
				<div class="form-horizontal">
					{!! csrf_field() !!}
					<div class="form-group">
						<div class="col-md-4">
							<label>Naam</label>
						</div>
						<div class="col-md-12">
							<input value="" name="activity_name" id="nc_activity_name" class="form-control" />
							<input value="" name="activity" id="nc_activity" type="hidden" class="form-control" />
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-default">Opslaan</button>
			</div>
		</div>
		</form>
	</div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel">{{ $type->type_name == 'regie' ? 'Regie' : 'Producten' }}</h4>
			</div>

			<div class="modal-body">
					<div class="form-group input-group input-group-lg">
						<input type="text" id="search" value="" class="form-control" placeholder="Zoek producten">
							<span class="input-group-btn">
					        <select id="group2" class="btn getsub" style="background-color: #E5E7E9; color:#000">
						        <option value="0" selected>of selecteer subcategorie</option>
						        @foreach (ProductGroup::all() as $group)
						        <option data-name="group" value="{{ $group->id }}">{{ $group->group_name }}</option>
						        	@foreach (ProductCategory::where('group_id', $group->id)->get() as $cat)
						        	<option data-name="cat" value="{{ $cat->id }}"> - {{ $cat->category_name }}</option>
						        	@endforeach
						        @endforeach
					        </select>
					      </span>
					      <span class="input-group-btn">
					        <select id="group" class="btn" style="background-color: #E5E7E9; color:#000">
					        <option value="0" selected>en subcategorie</option>
					        @foreach (ProductSubCategory::all() as $subcat)
					          <option value="{{ $subcat->id }}">{{ $subcat->sub_category_name }}</option>
					        @endforeach
					        </select>
					      </span>
					</div>
					<div class="table-responsive">
						<table id="tbl-material" class="table table-hover">
							<thead>
								<tr>
									<th>Omschrijving</th>
									<th>Eenheid</th>
									<th>Prijs per eenheid</th>
									<th>Totaalprijs</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
			</div>

			<div class="modal-footer">
				<button class="btn btn-default" data-dismiss="modal">Sluiten</button>
			</div>

		</div>
	</div>
</div>
<div class="modal fade" id="myFavAct" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel">Favoriete werkzaamheden</h4>
			</div>

			<div class="modal-body">
				
			          <div class="table-responsive">
			            <table class="table table-hover">
			              <thead>
			                <tr>
			                  <th>Omschrijving</th>
			                  <th class="text-right">Aangemaakt</th>
			                </tr>
			              </thead>
			              <tbody>
			              	@foreach (FavoriteActivity::where('user_id', Auth::id())->orderBy('created_at')->get() as $favact)	
			              	<tr>
			              		<td><a class="favlink" href="#" data-id="{{ $favact->id }}">{{ $favact->activity_name }}</a></td>
			              		<td class="text-right">{{ $favact->created_at->toDateString() }}</td>
			              	</tr>
			              	@endforeach
						  </tbody>
					</table>
					<!--<input type="hidden" name="noteact" id="favact" />-->
				</div>
			</div>

			<div class="modal-footer">
				<button class="btn btn-default" data-dismiss="modal">Sluiten</button>
			</div>

		</div>
	</div>
</div>
<div class="modal fade" id="descModal" tabindex="-1" role="dialog" aria-labelledby="descModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel">Omschrijving werkzaamheid</h4>
			</div>

			<div class="modal-body">
				<div class="form-group">
					<div class="col-md-12">
						<textarea name="note" id="note" rows="5" class="form-control summernote"></textarea>
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
<div id="wrapper">

	<section class="container fix-footer-bottom">

		@include('calc.wizard', array('page' => 'more'))

			<h2><strong>{{ $type->type_name == 'regie' ? 'Regie' : 'Meerwerk' }}</strong> <strong><a data-toggle="tooltip" data-placement="bottom" data-original-title="Hier kunt u meerwerk op basis van regie toevoegen bestemd voor op de factuur." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></strong></h2>

			<div class="tabs nomargin">

				<ul class="nav nav-tabs">
					<li id="tab-calculate">
						<a href="#calculate" data-toggle="tab">
							<i class="fa fa-list"></i> Calculeren {{ $type->type_name == 'regie' ? 'Regie' : 'Meerwerk' }}
						</a>
					</li>
					<li id="tab-summary">
						<a href="#summary" data-toggle="tab">
							<i class="fa fa-align-justify"></i> Uittrekstaat {{ $type->type_name == 'regie' ? 'Regie' : 'Meerwerk' }}
						</a>
					</li>
					<li id="tab-endresult">
						<a href="#endresult" data-toggle="tab">
							<i class="fa fa-check-circle-o"></i> Eindresultaat {{ $type->type_name == 'regie' ? 'Regie' : 'Meerwerk' }}
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
										foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_type_id','=',PartType::where('type_name','=','calculation')->first()->id)->where('detail_id','=',Detail::where('detail_name','=','more')->first()->id)->orderBy('created_at')->get() as $activity) {
											if (Part::find($activity->part_id)->part_name=='contracting') {
												$profit_mat = $project->profit_more_contr_mat;
												$profit_equip = $project->profit_more_contr_equip;
												$activity_total = MoreOverview::activityTotalProfit($activity, $project->profit_more_contr_mat, $project->profit_more_contr_equip);
											} else if (Part::find($activity->part_id)->part_name=='subcontracting') {
												$profit_mat = $project->profit_more_subcontr_mat;
												$profit_equip = $project->profit_more_subcontr_equip;
												$activity_total = MoreOverview::activityTotalProfit($activity, $project->profit_more_subcontr_mat, $project->profit_more_subcontr_equip);
											}
										?>
										<div id="toggle-activity-{{ $activity->id }}" class="toggle toggle-activity">
											<label>
												<span>{{ $activity->activity_name }}</span>
												<span style="float: right;margin-right: 30px;">{{ '&euro; '.number_format($activity_total, 2, ",",".") }}</span>
											</label>
											<div class="toggle-content">
												<div class="row">
													<?php
													if ($activity->use_timesheet) {
													?>
													<div class="col-md-4"><strong>Aanneming</strong></div>
													<?php } else { ?>
														@if ($project->use_subcontract)
														<div class="col-md-2"><label class="radio-inline"><input data-id="{{ $activity->id }}" class="radio-activity" name="soort{{ $activity->id }}" value="{{ Part::where('part_name','=','contracting')->first()->id }}" type="radio" {{ ( Part::find($activity->part_id)->part_name=='contracting' ? 'checked' : '') }}/>Aanneming</label></div>
		    											<div class="col-md-2"><label class="radio-inline"><input data-id="{{ $activity->id }}" class="radio-activity" name="soort{{ $activity->id }}" value="{{ Part::where('part_name','=','subcontracting')->first()->id }}" type="radio" {{ ( Part::find($activity->part_id)->part_name=='subcontracting' ? 'checked' : '') }}/>Onderaanneming</label></div>
		    											@else
		    											<div class="col-md-4"></div>
		    											@endif
													<div class="col-md-4 text-right">
	    												<div class="form-group hide_if_subcon" {!! ( Part::find($activity->part_id)->part_name=='subcontracting' ? 'style="display:none;"' : '') !!}>
	    													<label for="use_timesheet">Urenregistratie gebruiken&nbsp;</label>
															<input name="use_timesheet" class="use-timesheet" data-id="{{ $activity->id }}" type="checkbox" data-size="small" {{ $activity->use_timesheet ? 'checked' : '' }}>
														</div>
													</div>
	    											<?php } ?>
													<div class="col-md-4 text-right">
														<button id="pop-{{$chapter->id.'-'.$activity->id}}" data-id="{{ $activity->id }}" data-note="{{ $activity->note }}" data-toggle="modal" data-target="#descModal" class="btn btn-info btn-xs notemod">Omschrijving</button>
														<div class="btn-group" role="group">
														  <button type="button" class="btn btn-primary dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Werkzaamheid&nbsp;&nbsp;<span class="caret"></span></button>
														  <ul class="dropdown-menu">
														    <li><a href="#" data-id="{{ $activity->id }}" data-name="{{ $activity->activity_name }}" data-toggle="modal" data-target="#nameChangeModal" class="changename">Naam wijzigen</a></li>
														    <li><a href="#" data-id="{{ $activity->id }}" class="deleteact">Verwijderen</a></li>
														  </ul>
														</div>
													</div>





												</div>
												<div class="row">
													<div class="col-md-2"><h4>Arbeid<strong> <a data-toggle="tooltip" data-placement="bottom" data-original-title="Hier kunt u meerwerk op basis van regie toevoegen bestemd voor op de factuur. Voor arbeid geldt: uren die bij de urenregistratie geboekt worden overschrijven de opgegeven hoeveel arbeid voor deze werkzaamheid." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></strong></h4></div>
													<div class="col-md-1 text-right"><strong>BTW</strong></div>
													<div class="col-md-2">
													@if ($project->tax_reverse)
														<span>0%</span>
													@else
														<select name="btw" data-id="{{ $activity->id }}" data-type="calc-labor" id="type" class="form-control-sm-text pointer select-tax">
														@foreach (Tax::all() as $tax)
															<?php
															if ($tax->id == 1)
																continue;
															?>
															<option value="{{ $tax->id }}" {{ ($activity->tax_labor_id==$tax->id ? 'selected="selected"' : '') }}>{{ $tax->tax_rate }}%</option>
														@endforeach
														</select>
													@endif
													</div>
													<div class="col-md-6"></div>
												</div>
												<table class="table table-striped" data-id="{{ $activity->id }}">
													<?php
													if ($activity->use_timesheet) {
													?>
													<thead>
														<tr>
															<th class="col-md-2">Datum</th>
															<th class="col-md-1">Uren</th>
															<th class="col-md-1">Prijs</th>
															<th class="col-md-7">Omschrijving</th>
															<th class="col-md-1">&nbsp;</th>
														</tr>
													</thead>
													<?php }else { ?>
													<thead>
														<tr>
															<th class="col-md-5">Omschrijving</th>
															<th class="col-md-1">&nbsp;</th>
															<th class="col-md-1">Uurtarief</th>
															<th class="col-md-1">Aantal</th>
															<th class="col-md-1">Prijs</th>
															<th class="col-md-1">&nbsp;</th>
															<th class="col-md-1">&nbsp;</th>
														</tr>
													</thead>
													<?php } ?>

													<tbody>
														<?php
														if ($activity->use_timesheet) {

														$collection = collect();
														?>
														@foreach (MoreLabor::where('activity_id','=', $activity->id)->whereNotNull('hour_id')->orderBy('hour_id', 'desc')->get() as $labor)
															<?php $collection->push(['date' => strtotime(Timesheet::find($labor->hour_id)->register_date), 'labor' => $labor]); ?>
														@endforeach
														@foreach ($collection->sortByDesc('date')->all() as $labor)
														<tr data-id="{{ Timesheet::find($labor['labor']->hour_id)->id }}">
															<td class="col-md-2">{{ date('d-m-Y', $labor['date']) }}</td>
															<td class="col-md-1">{{ number_format($labor['labor']->amount, 2,",",".") }}</td>
															<td class="col-md-1"><span class="total-ex-tax">{{ '&euro; '.number_format(MoreRegister::laborTotal($project->hour_rate_more, $labor['labor']->amount), 2, ",",".") }}</span></td>
															<td class="col-md-7">{{ Timesheet::find($labor['labor']->hour_id)->note }}</td>
															<td class="col-md-1 text-right"><!--<button class="btn btn-xs fa btn-danger fa-times xdeleterow"></button>--></td>
														</tr>
														@endforeach
														<tr>
															<td class="col-md-2"><input type="text" class="form-control-sm-text datepick" name="date" /></td>
															<td class="col-md-1"><input type="text" name="hour" id="hour" class="form-control-sm-text"/></td>
															<td class="col-md-1"><span class="total-ex-tax"></span></td>
															<td class="col-md-7"><input type="text" name="note" id="note" class="form-control-sm-text"/></td>
															<td class="col-md-1"><button class="btn btn-primary btn-xs tsave-save"> Toevoegen</button></td>
														</tr>
														<?php
														}else {
															$labor = MoreLabor::where('activity_id','=', $activity->id)->whereNull("hour_id")->first();
															if (Part::find($activity->part_id)->part_name=='subcontracting')
																$rate = $labor['rate'];
															else
																$rate = $project->hour_rate_more;
														?>
														<tr {!! $labor ? ('data-id="'.$labor->id.'"') : '' !!} >
															<td class="col-md-5">Arbeidsuren</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1"><span class="rate">{!! Part::find($activity->part_id)->part_name=='subcontracting' ? '<input name="rate" type="text" value="'.($labor ? number_format($labor->rate, 2,",",".") : "").'" class="form-control-sm-number labor-amount lsave">' : number_format($project->hour_rate_more, 2,",",".") !!}</span></td>
															<td class="col-md-1"><input data-id="{{ $activity->id }}" name="amount" type="text" value="{{ $labor ? number_format($labor->amount, 2, ",",".") : '' }}" class="form-control-sm-number labor-amount lsave" /></td>
															<td class="col-md-1"><span class="total-ex-tax">{{ $labor ? ('&euro; '.number_format(MoreRegister::laborTotal($rate, $labor->amount), 2, ",",".")) : '' }}</span></td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1 text-right"><button class="btn btn-danger ldeleterow btn-xs fa fa-times"></button></td>
														</tr>
														<?php } ?>
													</tbody>
												</table>

												<div class="row">
													<div class="col-md-2"><h4>Materiaal</h4></div>
													<div class="col-md-1 text-right"><strong>BTW</strong></div>
													<div class="col-md-2">
													@if ($project->tax_reverse)
														<span>0%</span>
													@else
														<select name="btw" data-id="{{ $activity->id }}" data-type="calc-material" id="type" class="form-control-sm-text pointer select-tax">
														@foreach (Tax::all() as $tax)'
															<?php
															if ($tax->id == 1)
																continue;
															?>
															<option value="{{ $tax->id }}" {{ ($activity->tax_material_id==$tax->id ? 'selected="selected"' : '') }}>{{ $tax->tax_rate }}%</option>
														@endforeach
														</select>
													@endif
													</div>
													<div class="col-md-2"></div>
												</div>

												<table class="table table-striped" data-id="{{ $activity->id }}">
													<thead>
														<tr>
															<th class="col-md-5">Omschrijving</th>
															<th class="col-md-1">Eenheid</th>
															<th class="col-md-1">&euro; / Eenh.</th>
															<th class="col-md-1">Aantal</th>
															<th class="col-md-1">Prijs</th>
															<th class="col-md-1">+ Winst %</th>
															<th class="col-md-1">&nbsp;</th>
														</tr>
													</thead>

													<tbody>
														@foreach (MoreMaterial::where('activity_id','=', $activity->id)->get() as $material)
														<tr data-id="{{ $material->id }}">
															<td class="col-md-5"><input name="name" id="name" type="text" value="{{ $material->material_name }}" class="form-control-sm-text dsave newrow" /></td>
															<td class="col-md-1"><input name="unit" id="name" type="text" value="{{ $material->unit }}" class="form-control-sm-text dsave" /></td>
															<td class="col-md-1"><input name="rate" id="name" type="text" value="{{ number_format($material->rate, 2,",",".") }}" class="form-control-sm-number dsave" /></td>
															<td class="col-md-1"><input name="amount" id="name" type="text" value="{{ number_format($material->amount, 2,",",".") }}" class="form-control-sm-number dsave" /></td>
															<td class="col-md-1"><span class="total-ex-tax">{{ '&euro; '.number_format($material->rate*$material->amount, 2,",",".") }}</span></td>
															<td class="col-md-1"><span class="total-incl-tax">
															<?php
																if (Part::find($activity->part_id)->part_name=='contracting') {
																	$profit = $project->profit_more_contr_mat;
																} else if (Part::find($activity->part_id)->part_name=='subcontracting') {
																	$profit = $project->profit_more_subcontr_mat;
																}
																echo '&euro; '.number_format($material->rate*$material->amount*((100+$profit)/100), 2,",",".")
															?></span></td>
															<td class="col-md-1 text-right" data-profit="{{ $profit }}">
																<button class="btn-xs fa fa-book" data-toggle="modal" data-target="#myModal"></button>
																<button class="btn btn-danger btn-xs sdeleterow fa fa-times"></button>
															</td>
														</tr>
														@endforeach
														<tr>
															<td class="col-md-5"><input name="name" id="name" type="text" class="form-control-sm-text dsave newrow" /></td>
															<td class="col-md-1"><input name="unit" id="name" type="text" class="form-control-sm-text dsave" /></td>
															<td class="col-md-1"><input name="rate" id="name" type="text" class="form-control-sm-number dsave" /></td>
															<td class="col-md-1"><input name="amount" id="name" type="text" class="form-control-sm-number dsave" /></td>
															<td class="col-md-1"><span class="total-ex-tax"></span></td>
															<td class="col-md-1"><span class="total-incl-tax"></span></td>
															<td class="col-md-1 text-right" data-profit="{{ $profit_mat }}">
																<button class="btn-xs fa fa-book" data-toggle="modal" data-target="#myModal"></button>
																<button class="btn btn-danger btn-xs sdeleterow fa fa-times"></button>
															</td>
														</tr>
													</tbody>
													<tbody>
														<tr>
															<td class="col-md-5"><strong>Totaal</strong></td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1"><strong class="mat_subtotaal">
															<?php
															if (Part::find($activity->part_id)->part_name=='contracting') {
																$profit = $project->profit_more_contr_mat;
															} else if (Part::find($activity->part_id)->part_name=='subcontracting') {
																$profit = $project->profit_more_subcontr_mat;
															}
															echo '&euro; '.number_format(MoreRegister::materialTotal($activity->id, $profit), 2, ",",".");
															?></strong></td>
															<td class="col-md-1"><strong class="mat_subtotaal_profit">
															<?php
															if (Part::find($activity->part_id)->part_name=='contracting') {
																$profit = $project->profit_more_contr_mat;
															} else if (Part::find($activity->part_id)->part_name=='subcontracting') {
																$profit = $project->profit_more_subcontr_mat;
															}
															echo '&euro; '.number_format(MoreRegister::materialTotalProfit($activity->id, $profit), 2, ",",".");
															?></strong></td>
															<td class="col-md-1">&nbsp;</td>
														</tr>
													</tbody>
												</table>

												@if ($project->use_equipment)
												<div class="row">
													<div class="col-md-2"><h4>Overig</h4></div>
													<div class="col-md-1 text-right"><strong>BTW</strong></div>
													<div class="col-md-2">
													@if ($project->tax_reverse)
														<span>0%</span>
													@else
														<select name="btw" data-id="{{ $activity->id }}" data-type="calc-equipment" id="type" class="form-control-sm-text pointer select-tax">
														@foreach (Tax::all() as $tax)
															<?php
															if ($tax->id == 1)
																continue;
															?>
															<option value="{{ $tax->id }}" {{ ($activity->tax_equipment_id==$tax->id ? 'selected="selected"' : '') }}>{{ $tax->tax_rate }}%</option>
														@endforeach
														</select>
													@endif
													</div>
													<div class="col-md-8"></div>
												</div>

												<table class="table table-striped" data-id="{{ $activity->id }}">
													<thead>
														<tr>
															<th class="col-md-5">Omschrijving</th>
															<th class="col-md-1">Eenheid</th>
															<th class="col-md-1">&euro; / Eenh.</th>
															<th class="col-md-1">Aantal</th>
															<th class="col-md-1">Prijs</th>
															<th class="col-md-1">+ Winst %</th>
															<th class="col-md-1">&nbsp;</th>
														</tr>
													</thead>

													<tbody>
														@foreach (MoreEquipment::where('activity_id','=', $activity->id)->get() as $equipment)
														<tr data-id="{{ $equipment->id }}">
															<td class="col-md-5"><input name="name" id="name" type="text" value="{{ $equipment->equipment_name }}" class="form-control-sm-text esave newrow" /></td>
															<td class="col-md-1"><input name="unit" id="name" type="text" value="{{ $equipment->unit }}" class="form-control-sm-text esave" /></td>
															<td class="col-md-1"><input name="rate" id="name" type="text" value="{{ number_format($equipment->rate, 2,",",".") }}" class="form-control-sm-number esave" /></td>
															<td class="col-md-1"><input name="amount" id="name" type="text" value="{{ number_format($equipment->amount, 2,",",".") }}" class="form-control-sm-number esave" /></td>
															<td class="col-md-1"><span class="total-ex-tax">{{ '&euro; '.number_format($equipment->rate*$equipment->amount, 2,",",".") }}</span></td>
															<td class="col-md-1"><span class="total-incl-tax">
															<?php
																if (Part::find($activity->part_id)->part_name=='contracting') {
																	$profit = $project->profit_more_contr_equip;
																} else if (Part::find($activity->part_id)->part_name=='subcontracting') {
																	$profit = $project->profit_more_subcontr_equip;
																}
																echo '&euro; '.number_format($equipment->rate*$equipment->amount*((100+$profit)/100), 2,",",".")
															?></span></td>
															<td class="col-md-1 text-right" data-profit="{{$profit}}">
																<button class="btn-xs fa fa-book" data-toggle="modal" data-target="#myModal"></button>
																<button class="btn btn-danger btn-xs edeleterow fa fa-times"></button>
															</td>
														</tr>
														@endforeach
														<tr>
															<td class="col-md-5"><input name="name" id="name" type="text" class="form-control-sm-text esave newrow" /></td>
															<td class="col-md-1"><input name="unit" id="name" type="text" class="form-control-sm-text esave" /></td>
															<td class="col-md-1"><input name="rate" id="name" type="text" class="form-control-sm-number esave" /></td>
															<td class="col-md-1"><input name="amount" id="name" type="text" class="form-control-sm-number esave" /></td>
															<td class="col-md-1"><span class="total-ex-tax"></span></td>
															<td class="col-md-1"><span class="total-incl-tax"></span></td>
															<td class="col-md-1 text-right" data-profit="{{ $profit_equip }}">
																<button class="btn-xs fa fa-book" data-toggle="modal" data-target="#myModal"></button>
																<button class="btn btn-danger btn-xs edeleterow fa fa-times"></button>
															</td>
														</tr>
													</tbody>
													<tbody>
														<tr>
															<td class="col-md-5"><strong>Totaal</strong></td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1"><strong>
															<?php
															if (Part::find($activity->part_id)->part_name=='contracting') {
																$profit = $project->profit_more_contr_equip;
															} else if (Part::find($activity->part_id)->part_name=='subcontracting') {
																$profit = $project->profit_more_subcontr_equip;
															}
															echo '&euro; '.number_format(MoreRegister::equipmentTotal($activity->id, $profit), 2, ",",".");
															?></span></td>
															<td class="col-md-1"><strong>
															<?php
															if (Part::find($activity->part_id)->part_name=='contracting') {
																$profit = $project->profit_more_contr_equip;
															} else if (Part::find($activity->part_id)->part_name=='subcontracting') {
																$profit = $project->profit_more_subcontr_equip;
															}
															echo '&euro; '.number_format(MoreRegister::equipmentTotalProfit($activity->id, $profit), 2, ",",".");
															?></span></td>
															<td class="col-md-1">&nbsp;</td>
														</tr>
													</tbody>
												</table>
												@endif
											</div>
										</div>
										<?php } ?>
									</div>

									<form action="/more/newactivity/{{ $chapter->id }}" method="post">
									{!! csrf_field() !!}
									<div class="row">
										<div class="col-md-6">

											<div class="input-group">
												<input type="text" class="form-control" name="activity" id="activity" value="" placeholder="Nieuwe Werkzaamheid">
												<input type="hidden" name="project" value="{{ $project->id }}">
												<span class="input-group-btn">
													<button class="btn btn-primary btn-primary-activity">Voeg toe</button>
												</span>
											</div>
										</div>
										<div class="col-md-6 text-right">
											<button type="button" class="btn btn-primary favselect" data-id="{{ $chapter->id }}" data-toggle="modal" data-target="#myFavAct">Favoriet selecteren</button>
											@if ($chapter->more)
											<button data-id="{{ $chapter->id }}" class="btn btn-danger deletechap">Onderdeel verwijderen</button>
											@endif
										</div>
									</div>
									</form>
								</div>
							</div>
							@endforeach
						</div>

						<form action="/more/newchapter/{{ $project->id }}" method="post">
						{!! csrf_field() !!}
						<div><hr></div>
						<div class="row">
							<div class="col-md-6">
								<div class="input-group">
									<input type="text" class="form-control" name="chapter" id="chapter" value="" placeholder="Nieuw Onderdeel">
									<input type="hidden" name="project" value="{{ $project->id }}">
									<span class="input-group-btn">
										<button class="btn btn-primary btn-primary-chapter">Voeg toe</button>
									</span>
								</div>
							</div>
						</div>
						</form>
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
