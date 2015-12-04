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
		});
		$('#tab-endresult').click(function(e){
			sessionStorage.toggleTabLess{{Auth::user()->id}} = 'endresult';
		});
		if (sessionStorage.toggleTabLess{{Auth::user()->id}}){
			$toggleOpenTab = sessionStorage.toggleTabLess{{Auth::user()->id}};
			$('#tab-'+$toggleOpenTab).addClass('active');
			$('#'+$toggleOpenTab).addClass('active');
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
					var json = $.parseJSON(data);
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
					var json = $.parseJSON(data);
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
					var json = $.parseJSON(data);
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
					var json = $.parseJSON(data);
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
				$.post("/calculation/calc/newmaterial", {
					name: $curThis.closest("tr").find("input[name='name']").val(),
					unit: $curThis.closest("tr").find("input[name='unit']").val(),
					rate: $curThis.closest("tr").find("input[name='rate']").val(),
					amount: $curThis.closest("tr").find("input[name='amount']").val(),
					activity: $curThis.closest("table").attr("data-id"),
					project: {{ $project->id }},
				}, function(data){
					var json = $.parseJSON(data);
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
				$.post("/calculation/calc/newequipment", {
					name: $curThis.closest("tr").find("input[name='name']").val(),
					unit: $curThis.closest("tr").find("input[name='unit']").val(),
					rate: $curThis.closest("tr").find("input[name='rate']").val(),
					amount: $curThis.closest("tr").find("input[name='amount']").val(),
					activity: $curThis.closest("table").attr("data-id"),
					project: {{ $project->id }},
				}, function(data){
					var json = $.parseJSON(data);
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
		$("body").on("change", ".dsavee", function(){
			var $curThis = $(this);
			if($curThis.closest("tr").attr("data-id")){
				$.post("/calculation/estim/updatematerial", {
					id: $curThis.closest("tr").attr("data-id"),
					name: $curThis.closest("tr").find("input[name='name']").val(),
					unit: $curThis.closest("tr").find("input[name='unit']").val(),
					rate: $curThis.closest("tr").find("input[name='rate']").val(),
					amount: $curThis.closest("tr").find("input[name='amount']").val(),
					project: {{ $project->id }},
				}, function(data){
					var json = $.parseJSON(data);
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
		$("body").on("change", ".esavee", function(){
			var $curThis = $(this);
			if($curThis.closest("tr").attr("data-id")){
				$.post("/calculation/estim/updateequipment", {
					id: $curThis.closest("tr").attr("data-id"),
					name: $curThis.closest("tr").find("input[name='name']").val(),
					unit: $curThis.closest("tr").find("input[name='unit']").val(),
					rate: $curThis.closest("tr").find("input[name='rate']").val(),
					amount: $curThis.closest("tr").find("input[name='amount']").val(),
					project: {{ $project->id }},
				}, function(data){
					var json = $.parseJSON(data);
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
		$("body").on("change", ".lsavee", function(){
			var $curThis = $(this);
			if($curThis.closest("tr").attr("data-id")){
				$.post("/calculation/estim/updatelabor", {
					id: $curThis.closest("tr").attr("data-id"),
					rate: $curThis.closest("tr").find("input[name='rate']").val(),
					amount: $curThis.closest("tr").find("input[name='amount']").val(),
					project: {{ $project->id }},
				}, function(data){
					var json = $.parseJSON(data);
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
		$("body").on("blur", ".lsavee", function(){
			var flag = true;
			var $curThis = $(this);
			if($curThis.closest("tr").attr("data-id"))
				return false;
			$curThis.closest("tr").find("input").each(function(){
				if(!$(this).val())
					flag = false;
			});
			if(flag){
				$.post("/calculation/estim/newlabor", {
					rate: $curThis.closest("tr").find("input[name='rate']").val(),
					amount: $curThis.closest("tr").find("input[name='amount']").val(),
					activity: $curThis.closest("table").attr("data-id"),
					project: {{ $project->id }},
				}, function(data){
					var json = $.parseJSON(data);
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
		$("body").on("blur", ".dsavee", function(){
			var flag = true;
			var $curThis = $(this);
			if($curThis.closest("tr").attr("data-id"))
				return false;
			$curThis.closest("tr").find("input").each(function(){
				if(!$(this).val())
					flag = false;
			});
			if(flag){
				$.post("/calculation/estim/newmaterial", {
					name: $curThis.closest("tr").find("input[name='name']").val(),
					unit: $curThis.closest("tr").find("input[name='unit']").val(),
					rate: $curThis.closest("tr").find("input[name='rate']").val(),
					amount: $curThis.closest("tr").find("input[name='amount']").val(),
					activity: $curThis.closest("table").attr("data-id"),
					project: {{ $project->id }},
				}, function(data){
					var json = $.parseJSON(data);
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
		$("body").on("blur", ".esavee", function(){
			var flag = true;
			var $curThis = $(this);
			if($curThis.closest("tr").attr("data-id"))
				return false;
			$curThis.closest("tr").find("input").each(function(){
				if(!$(this).val())
					flag = false;
			});
			if(flag){
				$.post("/calculation/estim/newequipment", {
					name: $curThis.closest("tr").find("input[name='name']").val(),
					unit: $curThis.closest("tr").find("input[name='unit']").val(),
					rate: $curThis.closest("tr").find("input[name='rate']").val(),
					amount: $curThis.closest("tr").find("input[name='amount']").val(),
					activity: $curThis.closest("table").attr("data-id"),
					project: {{ $project->id }},
				}, function(data){
					var json = $.parseJSON(data);
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
		$("body").on("click", ".sresetrow", function(){
			var $curThis = $(this);
			if($curThis.closest("tr").attr("data-id"))
				$.post("/less/resetmaterial", {project: {{ $project->id }}, id: $curThis.closest("tr").attr("data-id")}, function(data){
					var json = $.parseJSON(data);
					$curThis.closest("tr").find("input[name='rate']").val(json.rate);
					$curThis.closest("tr").find("input[name='amount']").val(json.amount);
				}).fail(function(e) { console.log(e); });
		});
		$("body").on("click", ".eresetrow", function(){
			var $curThis = $(this);
			if($curThis.closest("tr").attr("data-id"))
				$.post("/less/resetequipment", {project: {{ $project->id }}, id: $curThis.closest("tr").attr("data-id")}, function(data){
					var json = $.parseJSON(data);
					$curThis.closest("tr").find("input[name='rate']").val(json.rate);
					$curThis.closest("tr").find("input[name='amount']").val(json.amount);
				}).fail(function(e) { console.log(e); });
		});
		$("body").on("click", ".lresetrow", function(){
			var $curThis = $(this);
			if($curThis.closest("tr").attr("data-id"))
				$.post("/less/resetlabor", {project: {{ $project->id }}, id: $curThis.closest("tr").attr("data-id")}, function(data){
					var json = $.parseJSON(data);
					$curThis.closest("tr").find("input[name='amount']").val(json.amount);
				}).fail(function(e) { console.log(e); });
		});
		var $notecurr;
		$('.notemod').click(function(e) {
			$notecurr = $(this);
			$curval = $(this).attr('data-note');
			$curid = $(this).attr('data-id');
			$('#note').val($curval);
			$('#noteact').val($curid);
		});
		$('#descModal').on('hidden.bs.modal', function() {
			$.post("/calculation/noteactivity", {project: {{ $project->id }}, activity: $('#noteact').val(), note: $('#note').val()}, function(){
				$notecurr.attr('data-note', $('#note').val());
			}).fail(function(e) { console.log(e); });
		});
	});
</script>
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
						<textarea name="note" id="note" rows="5" class="form-control"></textarea>
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

		@include('calc.wizard', array('page' => 'less'))

			<h2><strong>Minderwerk</strong> <strong><a data-toggle="tooltip" data-placement="bottom" data-original-title="Hier kunt u hoeveelheden in mindering brengen op de bestaande calculatie bedoeld als minderwerk op de factuur." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></strong></h2>

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

							@foreach (Chapter::where('project_id','=', $project->id)->orderBy('created_at', 'desc')->get() as $chapter)
							<div id="toggle-chapter-{{ $chapter->id }}" class="toggle toggle-chapter">
								<label>{{ $chapter->chapter_name }}</label>
								<div class="toggle-content">

									<div class="toogle">

										@foreach (Activity::where('chapter_id','=', $chapter->id)->whereNull('detail_id')->where('part_type_id','=',PartType::where('type_name','=','calculation')->first()->id)->orderBy('created_at', 'desc')->get() as $activity)
										<div id="toggle-activity-{{ $activity->id }}" class="toggle toggle-activity">
											<label>{{ $activity->activity_name }}</label>
											<div class="toggle-content">
												<div class="row">
													<div class="col-md-4"></div>
													<div class="col-md-2"></div>
	    											<div class="col-md-2"></div>
													<div class="col-md-1 text-right"><strong>{{ Part::find($activity->part_id)->part_name=='subcontracting' ? 'Onderaanneming' : 'Aanneming' }}</strong></div>
													<div class="col-md-3 text-right"><button id="pop-{{$chapter->id.'-'.$activity->id}}" data-id="{{ $activity->id }}" data-note="{{ $activity->note }}" data-toggle="modal" data-target="#descModal" class="btn btn-info btn-xs notemod">Omschrijving toevoegen</button></div>
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
														<tr data-id="{{ $labor->id }}">
															<td class="col-md-5">Arbeidsuren</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">{{ number_format($project->hour_rate, 2,",",".") }}</td>
															<td class="col-md-1"><input data-id="{{ $activity->id }}" name="amount" type="text" value="{{ number_format($labor->isless ? $labor->less_amount : $labor->amount, 2, ",",".") }}" class="form-control-sm-number labor-amount lsave" /></td>
															<td class="col-md-1"><span class="total-ex-tax">{{ '&euro; '.number_format(CalculationRegister::calcLaborTotal($labor->rate, $labor->isless ? $labor->less_amount : $labor->amount), 2, ",",".") }}</span></td>
															<td class="col-md-1"><?php
																$minderw=LessRegister::lessLaborDeltaTotal($labor);
																if($minderw <0)
																	echo "<font color=red>&euro; ".number_format($minderw, 2, ",",".")."</font>";
																else
																	echo '&euro; '.number_format($minderw, 2, ",",".");
																?>
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
															<td class="col-md-1">
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
															?>
															</td>
															<td class="col-md-1 text-right" data-profit="{{$profit}}">
																<button class="btn btn-warning btn-xs sresetrow fa fa-undo"></button>
															</td>
														</tr>
														@endforeach
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
																$profit = $project->profit_calc_contr_mat;
															} else if (Part::find($activity->part_id)->part_name=='subcontracting') {
																$profit = $project->profit_calc_subcontr_mat;
															}
															echo '&euro; '.number_format(LessRegister::lessMaterialTotalProfit($activity->id, $profit), 2, ",",".");
															?></span></td>
															<td class="col-md-1"><strong>{{'&euro; ' .number_format(LessRegister::lessMaterialDeltaTotal($activity->id, $profit), 2, ",",".") }}</strong></td>
															<td class="col-md-1">&nbsp;</td>
														</tr>
													</tbody>
												</table>

												<div class="row">
													<div class="col-md-2"><h4>Materieel</h4></div>
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
															<td class="col-md-1">
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
															?>
															</td>
															<td class="col-md-1 text-right" data-profit="{{$profit}}">
																<button class="btn btn-warning btn-xs eresetrow fa fa-undo"></button>
															</td>
														</tr>
														@endforeach
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
																$profit = $project->profit_calc_contr_equip;
															} else if (Part::find($activity->part_id)->part_name=='subcontracting') {
																$profit = $project->profit_calc_subcontr_equip;
															}
															echo '&euro; '.number_format(LessRegister::lessEquipmentTotalProfit($activity->id, $profit), 2, ",",".");
															?></span></td>
															<td class="col-md-1"><strong>{{'&euro; ' .number_format(LessRegister::lessEquipmentDeltaTotal($activity->id, $profit), 2, ",",".") }}</strong></th>
															<td class="col-md-1">&nbsp;</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
										@endforeach
									</div>

								</div>
							</div>
							@endforeach
						</div>

					</div>

					<div id="summary" class="tab-pane">
						<div class="toogle">

							<div class="toggle toggle-chapter active">
								<label>Aanneming</label>
								<div class="toggle-content">

									<table class="table table-striped">
										<thead>
											<tr>
												<th class="col-md-3">Hoofdstuk</th>
												<th class="col-md-4">Werkzaamheden</th>
												<th class="col-md-1"><span class="pull-right">Arbeidsuren</th>
												<th class="col-md-1"><span class="pull-right">Arbeid</th>
												<th class="col-md-1"><span class="pull-right">Materiaal</th>
												<th class="col-md-1"><span class="pull-right">Materieel</th>
												<th class="col-md-1"><span class="pull-right">Totaal <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit is het bedrag dat in mindering wordt gebracht op de bestaande calculatie." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></th>
											</tr>
										</thead>

										<tbody>
											@foreach (Chapter::where('project_id','=', $project->id)->orderBy('created_at', 'desc')->get() as $chapter)
											<?php $i = 0; ?>
											@foreach (Activity::where('chapter_id','=', $chapter->id)->whereNull('detail_id')->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->where('part_type_id','=',PartType::where('type_name','=','calculation')->first()->id)->orderBy('created_at', 'desc')->get() as $activity)
											<?php $i++; ?>
											<tr>
												<td class="col-md-3">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
												<td class="col-md-4">{{ $activity->activity_name }}</td>
												<td class="col-md-1"><span class="pull-right">{{ number_format(LessOverview::laborTotal($activity), 2, ",",".") }}</td>
												<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(LessOverview::laborActivity($activity), 2, ",",".") }}</span></td>
												<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(LessOverview::materialActivityProfit($activity, $project->profit_calc_contr_mat), 2, ",",".") }}</span></td>
												<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::equipmentActivityProfit($activity, $project->profit_calc_contr_equip), 2, ",",".") }}</span></td>
												<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::activityTotalProfit($activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip), 2, ",",".") }} </td>
											</tr>
											@endforeach
											@endforeach
											<tr>
												<th class="col-md-3"><strong>Totaal Aanneming</strong></th>
												<th class="col-md-2">&nbsp;</th>
												<td class="col-md-1"><strong><span class="pull-right">{{ LessOverview::contrLaborTotalAmount($project) }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::contrLaborTotal($project), 2, ",",".") }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::contrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::contrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::contrTotal($project), 2, ",",".") }}</span></strong></td>
											</tr>
										</tbody>
									</table>

								</div>
							</div>

							<div class="toggle toggle-chapter active">
								<label>Onderaanneming</label>
								<div class="toggle-content">

									<table class="table table-striped">
										<thead>
											<tr>
												<th class="col-md-3">Hoofdstuk</th>
												<th class="col-md-4">Werkzaamheden</th>
												<th class="col-md-1"><span class="pull-right">Arbeidsuren</th>
												<th class="col-md-1"><span class="pull-right">Arbeid</th>
												<th class="col-md-1"><span class="pull-right">Materiaal</th>
												<th class="col-md-1"><span class="pull-right">Materieel</th>
												<th class="col-md-1"><span class="pull-right">Totaal <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit is het bedrag dat in mindering wordt gebracht op de bestaande calculatie." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></th>
											</tr>
										</thead>

										<tbody>
											@foreach (Chapter::where('project_id','=', $project->id)->orderBy('created_at', 'desc')->get() as $chapter)
											<?php $i = 0; ?>
											@foreach (Activity::where('chapter_id','=', $chapter->id)->whereNull('detail_id')->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->where('part_type_id','=',PartType::where('type_name','=','calculation')->first()->id)->orderBy('created_at', 'desc')->get() as $activity)
											<?php $i++ ?>
											<tr>
												<td class="col-md-3">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
												<td class="col-md-4">{{ $activity->activity_name }}</td>
												<td class="col-md-1"><span class="pull-right">{{ number_format(LessOverview::laborTotal($activity), 2, ",",".") }}</td>
												<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(LessOverview::laborActivity($activity), 2, ",",".") }}</span></td>
												<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(LessOverview::materialActivityProfit($activity, $project->profit_calc_subcontr_mat), 2, ",",".") }}</span></td>
												<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::equipmentActivityProfit($activity, $project->profit_calc_subcontr_equip), 2, ",",".") }}</span></td>
												<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::activityTotalProfit($activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip), 2, ",",".") }} </td>
												<td class="col-md-1 text-center {{-- LessOverview::estimateCheck($activity) --}}"></td>
											</tr>
											@endforeach
											@endforeach
											<tr>
												<th class="col-md-3"><strong>Totaal Onderaanneming</strong></th>
												<th class="col-md-4">&nbsp;</th>
												<td class="col-md-1"><strong><span class="pull-right">{{ LessOverview::subcontrLaborTotalAmount($project) }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::subcontrLaborTotal($project), 2, ",",".") }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::subcontrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::subcontrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::subcontrTotal($project), 2, ",",".") }}</span></strong></td>
											</tr>
										</tbody>
									</table>

								</div>
							</div>

							<div class="toggle toggle-chapter active">
								<label>Totalen project</label>
								<div class="toggle-content">
									<table class="table table-striped">
										<thead>
											<tr>
												<th class="col-md-4">&nbsp;</th>
												<th class="col-md-3">&nbsp;</th>
												<th class="col-md-1"><span class="pull-right">Arbeidsuren</span></th>
												<th class="col-md-1"><span class="pull-right">Arbeid</span></th>
												<th class="col-md-1"><span class="pull-right">Materiaal</span></th>
												<th class="col-md-1"><span class="pull-right">Materieel</span></th>
												<th class="col-md-1"><span class="pull-right">Totaal <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit is het bedrag dat in mindering wordt gebracht op de bestaande calculatie." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></span></th>
											</tr>
										</thead>

										<tbody>
											<tr>
												<th class="col-md-4">&nbsp;</th>
												<th class="col-md-3">&nbsp;</th>
												<td class="col-md-1"><span class="pull-right"><strong>{{ LessOverview::laborSuperTotalAmount($project) }}</strong></span></td>
												<td class="col-md-1"><span class="pull-right"><strong>{{ '&euro; '.number_format(LessOverview::laborSuperTotal($project), 2, ",",".") }}</strong></span></td>
												<td class="col-md-1"><span class="pull-right"><strong>{{ '&euro; '.number_format(LessOverview::materialSuperTotal($project), 2, ",",".") }}</strong></span></td>
												<td class="col-md-1"><span class="pull-right"><strong>{{ '&euro; '.number_format(LessOverview::equipmentSuperTotal($project), 2, ",",".") }}</strong></span></td>
												<td class="col-md-1"><span class="pull-right"><strong>{{ '&euro; '.number_format(LessOverview::superTotal($project), 2, ",",".") }}</strong></span></td>
											</tr>
										</tbody>
									</table>
									<h5><strong>Weergegeven bedragen zijn exclusief BTW</strong></h5>
								</div>
							</div>

						</div>
					</div>

					<div id="endresult" class="tab-pane">

						<h4>Aanneming</h4>
						<table class="table table-striped">
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
									<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax1($project), 2, ",",".") }}</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr>
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax2($project), 2, ",",".") }}</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								@else
								<tr>
									<td class="col-md-4">Arbeidskosten</td>
									<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax3($project), 2, ",",".") }}</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
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
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr>
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								@else
								<tr>
									<td class="col-md-4">Materiaalkosten</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
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
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr>
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								@else
								<tr>
									<td class="col-md-4">Materieelkosten</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								@endif

								<tr>
									<td class="col-md-4"><strong>Totaal Aanneming </strong></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1"><strong>{{ '&euro; '.number_format(LessEndresult::totalContracting($project), 2, ",",".") }}</strong></td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1"><strong>{{ '&euro; '.number_format(LessEndresult::totalContractingTax($project), 2, ",",".") }}</strong></td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
							</tbody>
						</table>

						<h4>Onderaanneming</h4>
						<table class="table table-striped">
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
									<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax1($project), 2, ",",".") }}</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr>
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax2($project), 2, ",",".") }}</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								@else
								<tr>
									<td class="col-md-4">Arbeidskosten</td>
									<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax3($project), 2, ",",".") }}</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
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
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::subconCalcMaterialActivityTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr>
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::subconCalcMaterialActivityTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								@else
								<tr>
									<td class="col-md-4">Materiaalkosten</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
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
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::subconCalcEquipmentActivityTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr>
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::subconCalcEquipmentActivityTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								@else
								<tr>
									<td class="col-md-4">Materieelkosten</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								@endif

								<tr>
									<td class="col-md-4"><strong>Totaal Onderaanneming </strong></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1"><strong>{{ '&euro; '.number_format(LessEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1"><strong>{{ '&euro; '.number_format(LessEndresult::totalSubcontractingTax($project), 2, ",",".") }}</strong></td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
							</tbody>
						</table>

						<h4>Totalen Minderwerk</h4>
						<table class="table table-striped">
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
									<td class="col-md-5"><strong>Calculatief in mindering te brengen (excl. BTW)</strong></td>
									<td class="col-md-2"><strong>{{ '&euro; '.number_format(LessEndresult::totalProject($project), 2, ",",".") }}</strong></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								@if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
								<tr>
									<td class="col-md-5">BTW bedrag aanneming 21%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::totalContractingTax1($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr>
									<td class="col-md-5">BTW bedrag aanneming 6%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::totalContractingTax2($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr>
									<td class="col-md-5">BTW bedrag onderaanneming 21%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::totalSubcontractingTax1($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr>
									<td class="col-md-5">BTW bedrag onderaanneming 6%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::totalSubcontractingTax2($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								@endif
								<tr>
									<td class="col-md-5">In mindering te brengen BTW bedrag</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::totalProjectTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr>
									<td class="col-md-5"><strong>Calculatief in mindering te brengen (Incl. BTW)</strong></td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2"><strong class="pull-right">{{ '&euro; '.number_format(LessEndresult::superTotalProject($project), 2, ",",".") }}</strong></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

			</div>


		</div>

	</section>

</div>
@stop

<?php } ?>
