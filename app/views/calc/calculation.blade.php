<?php
$project = Project::find(Route::Input('project_id'));
?>

@extends('layout.master')

@section('content')
<?# -- WRAPPER -- ?>

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
			sessionStorage.toggleTabCalc{{Auth::user()->id}} = 'calculate';
		});
		$('#tab-estimate').click(function(e){
			sessionStorage.toggleTabCalc{{Auth::user()->id}} = 'estimate';
		});
		$('#tab-summary').click(function(e){
			sessionStorage.toggleTabCalc{{Auth::user()->id}} = 'summary';
		});
		$('#tab-endresult').click(function(e){
			sessionStorage.toggleTabCalc{{Auth::user()->id}} = 'endresult';
		});
		if (sessionStorage.toggleTabCalc{{Auth::user()->id}}){
			$toggleOpenTab = sessionStorage.toggleTabCalc{{Auth::user()->id}};
			$('#tab-'+$toggleOpenTab).addClass('active');
			$('#'+$toggleOpenTab).addClass('active');
		} else {
			sessionStorage.toggleTabCalc{{Auth::user()->id}} = 'calculate';
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
		$(".select-estim-tax").change(function(){
			$.post("/calculation/updateestimatetax", {project: {{ $project->id }}, value: this.value, activity: $(this).attr("data-id"), type: $(this).attr("data-type")}).fail(function(e) { console.log(e); });
		});
		$("body").on("change", ".newrow", function(){
			var i = 1;
			if($(this).val()){
				if(!$(this).closest("tr").next().length){
					var $curTable = $(this).closest("table");
					$curTable.find("tr:eq(1)").clone().removeAttr("data-id").find("input").each(function(){
						$(this).val("").removeClass("error-input").attr("id", function(_, id){ return id + i });
					}).end().find(".total-ex-tax, .total-incl-tax").text("").end().appendTo($curTable);
					i++;
				}
			}
		});
		$("body").on("change", ".dsave", function(){
			var $curThis = $(this);
			if($curThis.closest("tr").attr("data-id")){
				$.post("/calculation/calc/updatematerial", {
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
		$("body").on("change", ".esave", function(){
			var $curThis = $(this);
			if($curThis.closest("tr").attr("data-id")){
				$.post("/calculation/calc/updateequipment", {
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
		$("body").on("change", ".lsave", function(){
			var $curThis = $(this);
			if($curThis.closest("tr").attr("data-id")){
				$.post("/calculation/calc/updatelabor", {
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
		$("body").on("click", ".sdeleterow", function(){
			var $curThis = $(this);
			if($curThis.closest("tr").attr("data-id"))
				$.post("/calculation/calc/deletematerial", {project: {{ $project->id }}, id: $curThis.closest("tr").attr("data-id")}, function(){
					$curThis.closest("tr").hide("slow");
				}).fail(function(e) { console.log(e); });
		});
		$("body").on("click", ".edeleterow", function(){
			var $curThis = $(this);
			if($curThis.closest("tr").attr("data-id"))
				$.post("/calculation/calc/deleteequipment", {project: {{ $project->id }}, id: $curThis.closest("tr").attr("data-id")}, function(){
					$curThis.closest("tr").hide("slow");
				}).fail(function(e) { console.log(e); });
		});
		$("body").on("click", ".sdeleterowe", function(){
			var $curThis = $(this);
			if($curThis.closest("tr").attr("data-id"))
				$.post("/calculation/estim/deletematerial", {project: {{ $project->id }}, id: $curThis.closest("tr").attr("data-id")}, function(){
					$curThis.closest("tr").hide("slow");
				}).fail(function(e) { console.log(e); });
		});
		$("body").on("click", ".edeleterowe", function(){
			var $curThis = $(this);
			if($curThis.closest("tr").attr("data-id"))
				$.post("/calculation/estim/deleteequipment", {project: {{ $project->id }}, id: $curThis.closest("tr").attr("data-id")}, function(){
					$curThis.closest("tr").hide("slow");
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
					$.post("/calculation/deletechapter", {project: {{ $project->id }}, chapter: $curThis.attr("data-id")}, function(){
						$curThis.closest('.toggle-chapter').hide('slow');
					}).fail(function(e) { console.log(e); });
			}
		});
		$req = false;
		$("#search").keyup(function() {
			$val = $(this).val();
			if ($val.length > 3 && !$req) {
				$group = $('#group').val();
				$req = true;
				$.post("/material/search", {project: {{ $project->id }}, query: $val, group: $group}, function(data) {
					if (data) {
						$('#tbl-material tbody tr').remove();
						$.each(data, function(i, item) {
							$('#tbl-material tbody').append('<tr><td><a data-name="'+data[i].description+'" data-unit="'+data[i].unit+'" data-price="'+data[i].pricenum+'" href="javascript:void(0);">'+data[i].description+'</a></td><td>'+data[i].package+'</td><td>'+data[i].price+'</td></tr>');
						});
						$('#tbl-material tbody a').on("click", onmaterialclick);
						$req = false;
					}
				});
			}
		});
		var $newinputtr;
		$("button[data-toggle='modal']").click(function(e) {
			$newinputtr = $(this).closest("tr");
		});
		function onmaterialclick(e) {
			$newinputtr.find("input[name='name']").val($(this).attr('data-name'));
			$newinputtr.find("input[name='unit']").val($(this).attr('data-unit'));
			$newinputtr.find("input[name='rate']").val($(this).attr('data-price'));
			$newinputtr.find(".newrow").change();
			$('#myModal').modal('toggle');
		}
		$('.notemod').click(function(e) {
			$curval = $(this).attr('data-note');
			$curid = $(this).attr('data-id');
			$('#note').val($curval);
			$('#noteact').val($curid);
		});
		$('#descModal').on('hidden.bs.modal', function() {
			$.post("/calculation/noteactivity", {project: {{ $project->id }}, activity: $('#noteact').val(), note: $('#note').val()}, function(){
				$('#toggle-activity-'+$curThis.attr("data-id")).hide('slow');
			}).fail(function(e) { console.log(e); });
		});
	});
</script>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header"><!-- modal header -->
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel">Materialen</h4>
			</div>

			<div class="modal-body">
					<div class="form-group input-group input-group-lg">
						<input type="text" id="search" value="" class="form-control" placeholder="Zoek materiaal">
					      <span class="input-group-btn">
					        <select id="group" class="btn">
					        <option value="0" selected>Alles</option>
					        @foreach (SubGroup::all() as $group)
					          <option value="{{ $group->id }}">{{ $group->group_type }}</option>
					        @endforeach
					        </select>
					      </span>
					</div>
					<div class="table-responsive">
						<table id="tbl-material" class="table table-hover">
							<thead>
								<tr>
									<th>Omschrijving</th>
									<th>Afmeting</th>
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
<div class="modal fade" id="descModal" tabindex="-1" role="dialog" aria-labelledby="descModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header"><!-- modal header -->
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel">Omschrijving werkzaamheid</h4>
			</div>

			<div class="modal-body">
				<div class="form-group">
					<div class="col-md-12">
						<textarea name="note" id="note" rows="5" class="form-control">testopmerking</textarea>
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

		@include('calc.wizard', array('page' => 'calculation'))

			<h2><strong>Calculeren</strong></h2>

			<div class="tabs nomargin">

				<!-- tabs -->
				<ul class="nav nav-tabs">
					<li id="tab-calculate">
						<a href="#calculate" data-toggle="tab">
							<i class="fa fa-list"></i> Calculeren
						</a>
					</li>
					<li id="tab-estimate">
						<a href="#estimate" data-toggle="tab">
							<i class="fa fa-align-justify"></i> Stelposten
						</a>
					</li>
					<li id="tab-summary">
						<a href="#summary" data-toggle="tab">
							<i class="fa fa-sort-amount-asc"></i> Uittrekstaat
						</a>
					</li>
					<li id="tab-endresult">
						<a href="#endresult" data-toggle="tab">
							<i class="fa fa-check-circle-o"></i> Eindresultaat
						</a>
					</li>
				</ul>

				<!-- tabs content -->
				<div class="tab-content">
					<div id="calculate" class="tab-pane">
						<div class="toogle">

							@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
							<div id="toggle-chapter-{{ $chapter->id }}" class="toggle toggle-chapter">
								<label>{{ $chapter->chapter_name }}</label>
								<div class="toggle-content">

									<div class="toogle">

										<?php
										foreach(Activity::where('chapter_id','=', $chapter->id)->where('part_type_id','=',PartType::where('type_name','=','calculation')->first()->id)->get() as $activity) {
											if (Part::find($activity->part_id)->part_name=='contracting') {
												$profit_mat = $project->profit_calc_contr_mat;
												$profit_equip = $project->profit_calc_contr_equip;
											} else if (Part::find($activity->part_id)->part_name=='subcontracting') {
												$profit_mat = $project->profit_calc_subcontr_mat;
												$profit_equip = $project->profit_calc_subcontr_equip;
											}
										?>
										<div id="toggle-activity-{{ $activity->id }}" class="toggle toggle-activity">
											<label>{{ $activity->activity_name }}</label>
											<div class="toggle-content">
												<div class="row">
													<div class="col-md-4"></div>
													<div class="col-md-2"><label class="radio-inline"><input data-id="{{ $activity->id }}" class="radio-activity" name="soort{{ $activity->id }}" value="{{ Part::where('part_name','=','contracting')->first()->id }}" type="radio" {{ ( Part::find($activity->part_id)->part_name=='contracting' ? 'checked' : '') }}/>Aanneming</label></div>
	    											<div class="col-md-2"><label class="radio-inline"><input data-id="{{ $activity->id }}" class="radio-activity" name="soort{{ $activity->id }}" value="{{ Part::where('part_name','=','subcontracting')->first()->id }}" type="radio" {{ ( Part::find($activity->part_id)->part_name=='subcontracting' ? 'checked' : '') }}/>Onderaanneming</label></div>
													<div class="col-md-2 text-right"><button id="pop-{{$chapter->id.'-'.$activity->id}}" data-id="{{ $activity->id }}" data-note="{{ $activity->note }}" data-toggle="modal" data-target="#descModal" class="btn btn-info btn-xs notemod">Omschrijving toevoegen</button></div>
													<div class="col-md-2 text-right"><button data-id="{{ $activity->id }}" class="btn btn-danger btn-xs deleteact">Verwijderen</button></div>
												</div>
												<div class="row">
													<div class="col-md-2"><h4>Arbeid</h4></div>
													<div class="col-md-1 text-right"><strong>BTW</strong></div>
													<div class="col-md-2">
														<select name="btw" data-id="{{ $activity->id }}" data-type="calc-labor" id="type" class="form-control-sm-text pointer select-tax">
																@foreach (Tax::all() as $tax)
																	<option value="{{ $tax->id }}" {{ ($activity->tax_calc_labor_id==$tax->id ? 'selected="selected"' : '') }}>{{ $tax->tax_rate }}%</option>
																@endforeach
														</select>
													</div>
													<div class="col-md-6"></div>
												</div>
												<table class="table table-striped" data-id="{{ $activity->id }}">
													<?# -- table head -- ?>
													<thead>
														<tr>
															<th class="col-md-5">Omschrijving</th>
															<th class="col-md-1">&nbsp;</th>
															<th class="col-md-1">Uurtarief</th>
															<th class="col-md-1">Aantal</th>
															<th class="col-md-1">Prijs</th>
															<th class="col-md-1">&nbsp;</th>
															<th class="col-md-1">&nbsp;</th>
															<th class="col-md-1">&nbsp;</th>
														</tr>
													</thead>

													<?# -- table items -- ?>
													<tbody>
														<tr data-id="{{ CalculationLabor::where('activity_id','=', $activity->id)->first()['id'] }}"><?# -- item -- ?>
															<td class="col-md-5">Arbeidsuren</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">{{ number_format($project->hour_rate, 2,",",".") }}</td>
															<td class="col-md-1"><input data-id="{{ $activity->id }}" name="amount" type="text" value="{{ number_format(CalculationLabor::where('activity_id','=', $activity->id)->first()['amount'], 2, ",",".") }}" class="form-control-sm-number labor-amount lsave" /></td>
															<td class="col-md-1"><span class="total-ex-tax">{{ '&euro; '.number_format(CalculationRegister::calcLaborTotal($project->hour_rate, CalculationLabor::where('activity_id','=', $activity->id)->first()['amount']), 2, ",",".") }}</span></td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1 text-right"><button class="btn btn-danger btn-xs fa fa-times"></button></td>
														</tr>
													</tbody>
												</table>

												<div class="row">
													<div class="col-md-2"><h4>Materiaal</h4></div>
													<div class="col-md-1 text-right"><strong>BTW</strong></div>
													<div class="col-md-2">
														<select name="btw" data-id="{{ $activity->id }}" data-type="calc-material" id="type" class="form-control-sm-text pointer select-tax">
														@foreach (Tax::all() as $tax)
															<option value="{{ $tax->id }}" {{ ($activity->tax_calc_material_id==$tax->id ? 'selected="selected"' : '') }}>{{ $tax->tax_rate }}%</option>
														@endforeach
														</select>
													</div>
													<div class="col-md-2"></div>
												</div>

												<table class="table table-striped" data-id="{{ $activity->id }}">
													<?# -- tadble head -- ?>
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

													<?# -- table items -- ?>
													<tbody>
														@foreach (CalculationMaterial::where('activity_id','=', $activity->id)->get() as $material)
														<tr data-id="{{ $material->id }}">
															<td class="col-md-5"><input name="name" id="name" type="text" value="{{ $material->material_name }}" class="form-control-sm-text dsave newrow" /></td>
															<td class="col-md-1"><input name="unit" id="name" type="text" value="{{ $material->unit }}" class="form-control-sm-text dsave" /></td>
															<td class="col-md-1"><input name="rate" id="name" type="text" value="{{ number_format($material->rate, 2,",",".") }}" class="form-control-sm-number dsave" /></td>
															<td class="col-md-1"><input name="amount" id="name" type="text" value="{{ number_format($material->amount, 2,",",".") }}" class="form-control-sm-number dsave" /></td>
															<td class="col-md-1"><span class="total-ex-tax">{{ '&euro; '.number_format($material->rate*$material->amount, 2,",",".") }}</span></td>
															<td class="col-md-1"><span class="total-incl-tax">{{ '&euro; '.number_format($material->rate*$material->amount*((100+$profit_mat)/100), 2,",",".") }}</span></td>
															<td class="col-md-1 text-right" data-profit="{{$profit_mat}}">
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
															<td class="col-md-1"><strong>{{ '&euro; '.number_format(CalculationRegister::calcMaterialTotal($activity->id, $profit_mat), 2, ",","."); }}</span></td>
															<td class="col-md-1"><strong>{{ '&euro; '.number_format(CalculationRegister::calcMaterialTotalProfit($activity->id, $profit_mat), 2, ",","."); }}</span></td>
															<td class="col-md-1">&nbsp;</td>
														</tr>
													</tbody>
												</table>

												<div class="row">
													<div class="col-md-2"><h4>Materieel</h4></div>
													<div class="col-md-1 text-right"><strong>BTW</strong></div>
													<div class="col-md-2">
														<select name="btw" data-id="{{ $activity->id }}" data-type="calc-equipment" id="type" class="form-control-sm-text pointer select-tax">
														@foreach (Tax::all() as $tax)
															<option value="{{ $tax->id }}" {{ ($activity->tax_calc_equipment_id==$tax->id ? 'selected="selected"' : '') }}>{{ $tax->tax_rate }}%</option>
														@endforeach
														</select>
													</div>
													<div class="col-md-8"></div>
												</div>

												<table class="table table-striped" data-id="{{ $activity->id }}">
													<?# -- table head -- ?>
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

													<?# -- table items -- ?>
													<tbody>
														@foreach (CalculationEquipment::where('activity_id','=', $activity->id)->get() as $equipment)
														<tr data-id="{{ $equipment->id }}">
															<td class="col-md-5"><input name="name" id="name" type="text" value="{{ $equipment->equipment_name }}" class="form-control-sm-text esave newrow" /></td>
															<td class="col-md-1"><input name="unit" id="name" type="text" value="{{ $equipment->unit }}" class="form-control-sm-text esave" /></td>
															<td class="col-md-1"><input name="rate" id="name" type="text" value="{{ number_format($equipment->rate, 2,",",".") }}" class="form-control-sm-number esave" /></td>
															<td class="col-md-1"><input name="amount" id="name" type="text" value="{{ number_format($equipment->amount, 2,",",".") }}" class="form-control-sm-number esave" /></td>
															<td class="col-md-1"><span class="total-ex-tax">{{ '&euro; '.number_format($equipment->rate*$equipment->amount, 2,",",".") }}</span></td>
															<td class="col-md-1"><span class="total-incl-tax">{{ '&euro; '.number_format($equipment->rate*$equipment->amount*((100+$profit_equip)/100), 2,",",".") }}</span></td>
															<td class="col-md-1 text-right" data-profit="{{ $profit_equip }}">
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
															<td class="col-md-1"><strong>{{ '&euro; '.number_format(CalculationRegister::calcEquipmentTotal($activity->id, $profit_equip), 2, ",",".") }}</span></td>
															<td class="col-md-1"><strong>{{ '&euro; '.number_format(CalculationRegister::calcEquipmentTotalProfit($activity->id, $profit_equip), 2, ",",".") }}</span></td>
															<td class="col-md-1">&nbsp;</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
										<?php } ?>
									</div>

									{{ Form::open(array('url' => '/calculation/calc/newactivity/' . $chapter->id)) }}
									<div class="row">
										<div class="col-md-6">

											<div class="input-group">
												<input type="text" class="form-control" name="activity" id="activity" value="" placeholder="Nieuwe Werkzaamheid">
												<span class="input-group-btn">
													<button class="btn btn-primary btn-primary-activity">Voeg toe</button>
												</span>
											</div>
										</div>
										<div class="col-md-6 text-right">
											<button data-id="{{ $chapter->id }}" class="btn btn-danger deletechap">Hoofdstuk verwijderen</button>
										</div>
									</div>
									{{ Form::close() }}
								</div>
							</div>
							@endforeach
						</div>

						{{ Form::open(array('url' => '/calculation/newchapter/'.$project->id)) }}
						<div class="row">
							<div class="col-md-6">
								<div class="input-group">
									<input type="text" class="form-control" name="chapter" id="chapter" value="" placeholder="Nieuw Hoofdstuk">
									<span class="input-group-btn">
										<button class="btn btn-primary btn-primary-chapter">Voeg toe</button>
									</span>
								</div>
							</div>
						</div>
						{{ Form::close() }}
					</div>

					<div id="estimate" class="tab-pane">
						<div class="toogle">

							@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
							<div id="toggle-chapter-{{ $chapter->id }}" class="toggle toggle-chapter">
								<label>{{ $chapter->chapter_name }}</label>
								<div class="toggle-content">

									<div class="toogle">

										<?php
										foreach(Activity::where('chapter_id','=', $chapter->id)->where('part_type_id','=',PartType::where('type_name','=','estimate')->first()->id)->get() as $activity) {
											$profit_mat = 0;
											if (Part::find($activity->part_id)->part_name=='contracting') {
												$profit_mat = $project->profit_calc_contr_mat;
												$profit_equip = $project->profit_calc_contr_equip;
											} else if (Part::find($activity->part_id)->part_name=='subcontracting') {
												$profit_mat = $project->profit_calc_subcontr_mat;
												$profit_equip = $project->profit_calc_subcontr_equip;
											}
										?>
										<div id="toggle-activity-{{ $activity->id }}" class="toggle toggle-activity">
											<label>{{ $activity->activity_name }}</label>
											<div class="toggle-content">
												<div class="row">
													<div class="col-md-4"></div>
													<div class="col-md-2"><label class="radio-inline"><input data-id="{{ $activity->id }}" class="radio-activity" name="soorte{{ $activity->id }}" value="{{ Part::where('part_name','=','contracting')->first()->id }}" type="radio" {{ ( Part::find($activity->part_id)->part_name=='contracting' ? 'checked' : '') }}/>Aanneming</label></div>
	    											<div class="col-md-2"><label class="radio-inline"><input data-id="{{ $activity->id }}" class="radio-activity" name="soorte{{ $activity->id }}" value="{{ Part::where('part_name','=','subcontracting')->first()->id }}" type="radio" {{ ( Part::find($activity->part_id)->part_name=='subcontracting' ? 'checked' : '') }}/>Onderaanneming</label></div>
													<div class="col-md-2 text-right"><button id="pop-{{$chapter->id.'-'.$activity->id}}" data-id="{{ $activity->id }}" data-note="{{ $activity->note }}" data-toggle="modal" data-target="#descModal" class="btn btn-info btn-xs notemod">Omschrijving toevoegen</button></div>
													<div class="col-md-2 text-right"><button data-id="{{ $activity->id }}" class="btn btn-danger btn-xs deleteact">Verwijderen</button></div>
												</div>
												<div class="row">
													<div class="col-md-2"><h4>Arbeid</h4></div>
													<div class="col-md-1 text-right"><strong>BTW</strong></div>
													<div class="col-md-2">
														<select name="btw" data-id="{{ $activity->id }}" data-type="calc-labor" id="type" class="form-control-sm-text pointer select-estim-tax">
																@foreach (Tax::all() as $tax)
																	<option value="{{ $tax->id }}" {{ ($activity->tax_calc_labor_id==$tax->id ? 'selected="selected"' : '') }}>{{ $tax->tax_rate }}%</option>
																@endforeach
														</select>
													</div>
													<div class="col-md-6"></div>
												</div>
												<table class="table table-striped" data-id="{{ $activity->id }}">
													<?# -- table head -- ?>
													<thead>
														<tr>
															<th class="col-md-5">Omschrijving</th>
															<th class="col-md-1">&nbsp;</th>
															<th class="col-md-1">Uurtarief</th>
															<th class="col-md-1">Aantal</th>
															<th class="col-md-1">Prijs</th>
															<th class="col-md-1">&nbsp;</th>
															<th class="col-md-1">&nbsp;</th>
															<th class="col-md-1">&nbsp;</th>
														</tr>
													</thead>

													<?# -- table items -- ?>
													<tbody>
														<tr data-id="{{ EstimateLabor::where('activity_id','=', $activity->id)->first()['id'] }}"><?# -- item -- ?>
															<td class="col-md-5">Arbeidsuren</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">{{ number_format($project->hour_rate, 2,",",".") }}</td>
															<td class="col-md-1"><input data-id="{{ $activity->id }}" name="amount" type="text" value="{{ number_format(EstimateLabor::where('activity_id','=', $activity->id)->first()['amount'], 2, ",",".") }}" class="form-control-sm-number labor-amount lsavee" /></td>
															<td class="col-md-1"><span class="total-ex-tax">{{ '&euro; '.number_format(CalculationRegister::estimLaborTotal(EstimateLabor::where('activity_id','=', $activity->id)->first()['rate'], EstimateLabor::where('activity_id','=', $activity->id)->first()['amount'], 2, ",",".")) }}</span></td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1 text-right"><button class="btn btn-danger btn-xs fa fa-times"></button></td>
														</tr>
													</tbody>
												</table>

												<div class="row">
													<div class="col-md-2"><h4>Materiaal</h4></div>
													<div class="col-md-1 text-right"><strong>BTW</strong></div>
													<div class="col-md-2">
														<select name="btw" data-id="{{ $activity->id }}" data-type="calc-material" id="type" class="form-control-sm-text pointer select-estim-tax">
														@foreach (Tax::all() as $tax)
															<option value="{{ $tax->id }}" {{ ($activity->tax_calc_material_id==$tax->id ? 'selected="selected"' : '') }}>{{ $tax->tax_rate }}%</option>
														@endforeach
														</select>
													</div>
													<div class="col-md-2"></div>
												</div>

												<table class="table table-striped" data-id="{{ $activity->id }}">
													<?# -- tadble head -- ?>
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

													<?# -- table items -- ?>
													<tbody>
														@foreach (EstimateMaterial::where('activity_id','=', $activity->id)->get() as $material)
														<tr data-id="{{ $material->id }}">
															<td class="col-md-5"><input name="name" id="name" type="text" value="{{ $material->material_name }}" class="form-control-sm-text dsavee newrow" /></td>
															<td class="col-md-1"><input name="unit" id="name" type="text" value="{{ $material->unit }}" class="form-control-sm-text dsavee" /></td>
															<td class="col-md-1"><input name="rate" id="name" type="text" value="{{ number_format($material->rate, 2,",",".") }}" class="form-control-sm-number dsavee" /></td>
															<td class="col-md-1"><input name="amount" id="name" type="text" value="{{ number_format($material->amount, 2,",",".") }}" class="form-control-sm-number dsavee" /></td>
															<td class="col-md-1"><span class="total-ex-tax">{{ '&euro; '.number_format($material->rate*$material->amount, 2,",",".") }}</span></td>
															<td class="col-md-1"><span class="total-incl-tax">{{ '&euro; '.number_format($material->rate*$material->amount*((100+$profit_mat)/100), 2,",",".") }}</span></td>
															<td class="col-md-1 text-right" data-profit="{{ $profit_mat }}">
																<button class="btn-xs fa fa-book" data-toggle="modal" data-target="#myModal"></button>
																<button class="btn btn-danger btn-xs sdeleterowe fa fa-times"></button>
															</td>
														</tr>
														@endforeach
														<tr>
															<td class="col-md-5"><input name="name" id="name" type="text" class="form-control-sm-text dsavee newrow" /></td>
															<td class="col-md-1"><input name="unit" id="name" type="text" class="form-control-sm-text dsavee" /></td>
															<td class="col-md-1"><input name="rate" id="name" type="text" class="form-control-sm-number dsavee" /></td>
															<td class="col-md-1"><input name="amount" id="name" type="text" class="form-control-sm-number dsavee" /></td>
															<td class="col-md-1"><span class="total-ex-tax"></span></td>
															<td class="col-md-1"><span class="total-incl-tax"></span></td>
															<td class="col-md-1 text-right" data-profit="{{ $profit_mat }}">
																<button class="btn-xs fa fa-book" data-toggle="modal" data-target="#myModal"></button>
																<button class="btn btn-danger btn-xs sdeleterowe fa fa-times"></button>
															</td>
														</tr>
													</tbody>
													<tbody>
														<tr>
															<td class="col-md-5"><strong>Totaal</strong></td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1"><strong>{{ '&euro; '.number_format(CalculationRegister::estimMaterialTotal($activity->id, $profit_mat), 2, ",","."); }}</span></td>
															<td class="col-md-1"><strong>{{ '&euro; '.number_format(CalculationRegister::estimMaterialTotalProfit($activity->id, $profit_mat), 2, ",","."); }}</span></td>
															<td class="col-md-1">&nbsp;</td>
														</tr>
													</tbody>
												</table>

												<div class="row">
													<div class="col-md-2"><h4>Materieel</h4></div>
													<div class="col-md-1 text-right"><strong>BTW</strong></div>
													<div class="col-md-2">
														<select name="btw" data-id="{{ $activity->id }}" data-type="calc-equipment" id="type" class="form-control-sm-text pointer select-estim-tax">
														@foreach (Tax::all() as $tax)
															<option value="{{ $tax->id }}" {{ ($activity->tax_calc_equipment_id==$tax->id ? 'selected="selected"' : '') }}>{{ $tax->tax_rate }}%</option>
														@endforeach
														</select>
													</div>
													<div class="col-md-8"></div>
												</div>

												<table class="table table-striped" data-id="{{ $activity->id }}">
													<?# -- table head -- ?>
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

													<?# -- table items -- ?>
													<tbody>
														@foreach (EstimateEquipment::where('activity_id','=', $activity->id)->get() as $equipment)
														<tr data-id="{{ $equipment->id }}">
															<td class="col-md-5"><input name="name" id="name" type="text" value="{{ $equipment->equipment_name }}" class="form-control-sm-text esavee newrow" /></td>
															<td class="col-md-1"><input name="unit" id="name" type="text" value="{{ $equipment->unit }}" class="form-control-sm-text esave" /></td>
															<td class="col-md-1"><input name="rate" id="name" type="text" value="{{ number_format($equipment->rate, 2,",",".") }}" class="form-control-sm-number esavee" /></td>
															<td class="col-md-1"><input name="amount" id="name" type="text" value="{{ number_format($equipment->amount, 2,",",".") }}" class="form-control-sm-number esavee" /></td>
															<td class="col-md-1"><span class="total-ex-tax">{{ '&euro; '.number_format($equipment->rate*$equipment->amount, 2,",",".") }}</span></td>
															<td class="col-md-1"><span class="total-incl-tax">{{ '&euro; '.number_format($equipment->rate*$equipment->amount*((100+$profit_equip)/100), 2,",",".") }}</span></td>
															<td class="col-md-1 text-right" data-profit="{{ $profit_equip }}">
																<button class="btn-xs fa fa-book" data-toggle="modal" data-target="#myModal"></button>
																<button class="btn btn-danger btn-xs edeleterowe fa fa-times"></button>
															</td>
														</tr>
														@endforeach
														<tr>
															<td class="col-md-5"><input name="name" id="name" type="text" class="form-control-sm-text esavee newrow" /></td>
															<td class="col-md-1"><input name="unit" id="name" type="text" class="form-control-sm-text esavee" /></td>
															<td class="col-md-1"><input name="rate" id="name" type="text" class="form-control-sm-number esavee" /></td>
															<td class="col-md-1"><input name="amount" id="name" type="text" class="form-control-sm-number esavee" /></td>
															<td class="col-md-1"><span class="total-ex-tax"></span></td>
															<td class="col-md-1"><span class="total-incl-tax"></span></td>
															<td class="col-md-1 text-right" data-profit="{{ $profit_equip }}">
																<button class="btn-xs fa fa-book" data-toggle="modal" data-target="#myModal"></button>
																<button class="btn btn-danger btn-xs edeleterowe fa fa-times"></button>
															</td>
														</tr>
													</tbody>
													<tbody>
														<tr>
															<td class="col-md-5"><strong>Totaal</strong></td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1"><strong>{{ '&euro; '.number_format(CalculationRegister::estimEquipmentTotal($activity->id, $profit_equip), 2, ",","."); }}</span></td>
															<td class="col-md-1"><strong>{{ '&euro; '.number_format(CalculationRegister::estimEquipmentTotalProfit($activity->id, $profit_equip), 2, ",","."); }}</span></td>
															<td class="col-md-1">&nbsp;</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
										<?php } ?>
									</div>

									{{ Form::open(array('url' => '/calculation/estim/newactivity/' . $chapter->id)) }}
									<div class="row">
										<div class="col-md-6">

											<div class="input-group">
												<input type="text" class="form-control" name="activity" id="activity" value="" placeholder="Nieuwe Werkzaamheid">
												<span class="input-group-btn">
													<button class="btn btn-primary btn-primary-activity">Voeg toe</button>
												</span>
											</div>
										</div>
										<div class="col-md-6 text-right">
											<button data-id="{{ $chapter->id }}" class="btn btn-danger deletechap">Hoofdstuk verwijderen</button>
										</div>
									</div>
									{{ Form::close() }}
								</div>
							</div>
							@endforeach
						</div>

						{{ Form::open(array('url' => '/calculation/newchapter/'.$project->id)) }}
						<div class="row">
							<div class="col-md-6">
								<div class="input-group">
									<input type="text" class="form-control" name="chapter" id="chapter" value="" placeholder="Nieuw Hoofdstuk">
									<span class="input-group-btn">
										<button class="btn btn-primary btn-primary-chapter">Voeg toe</button>
									</span>
								</div>
							</div>
						</div>
						{{ Form::close() }}
					</div>

					<div id="summary" class="tab-pane">
						<div class="toogle">

							<div class="toggle toggle-chapter active">
								<label>Aanneming</label>
								<div class="toggle-content">

									<table class="table table-striped">
										<?# -- table head -- ?>
										<thead>
											<tr>
												<th class="col-md-3">&nbsp;</th>
												<th class="col-md-3">&nbsp;</th>
												<th class="col-md-1"><span class="pull-right">Arbeidsuren</th>
												<th class="col-md-1"><span class="pull-right">Arbeid</th>
												<th class="col-md-1"><span class="pull-right">Materiaal</th>
												<th class="col-md-1"><span class="pull-right">Materieel</th>
												<th class="col-md-1"><span class="pull-right">Totaal</th>
												<th class="col-md-1"><span class="text-center">&nbsp;&nbsp;&nbsp;Stelpost</th>
											</tr>
										</thead>

										<!-- table items -->
										<tbody>
											@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
											@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->get() as $activity)
											<tr><!-- item -->
												<td class="col-md-3"><strong>{{ $chapter->chapter_name }}</strong></td>
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
												<th class="col-md-3"><strong>Totaal Aanneming</strong></th>
												<th class="col-md-3">&nbsp;</th>
												<td class="col-md-1"><strong><span class="pull-right">{{ CalculationOverview::contrLaborTotalAmount($project) }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::contrLaborTotal($project), 2, ",",".") }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::contrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::contrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::contrTotal($project), 2, ",",".") }}</span></strong></td>
												<th class="col-md-1">&nbsp;</th>
											</tr>
										</tbody>
									</table>

								</div>
							</div>

							<div class="toggle toggle-chapter active">
								<label>Onderaanneming</label>
								<div class="toggle-content">

									<table class="table table-striped">
										<?# -- table head -- ?>
										<thead>
											<tr>
												<th class="col-md-3">&nbsp;</th>
												<th class="col-md-3">&nbsp;</th>
												<th class="col-md-1"><span class="pull-right">Arbeidsuren</th>
												<th class="col-md-1"><span class="pull-right">Arbeid</th>
												<th class="col-md-1"><span class="pull-right">Materiaal</th>
												<th class="col-md-1"><span class="pull-right">Materieel</th>
												<th class="col-md-1"><span class="pull-right">Totaal</th>
												<th class="col-md-1"><span class="text-center">&nbsp;&nbsp;&nbsp;Stelpost</th>
											</tr>
										</thead>

										<!-- table items -->
										<tbody>
											@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
											@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->get() as $activity)
											<tr><!-- item -->
												<td class="col-md-3"><strong>{{ $chapter->chapter_name }}</strong></td>
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
												<th class="col-md-3"><strong>Totaal Onderaanneming</strong></th>
												<th class="col-md-3">&nbsp;</th>
												<td class="col-md-1"><strong><span class="pull-right">{{ CalculationOverview::subcontrLaborTotalAmount($project) }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::subcontrLaborTotal($project), 2, ",",".") }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::subcontrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::subcontrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::subcontrTotal($project), 2, ",",".") }}</span></strong></td>
												<th class="col-md-1">&nbsp;</th>
											</tr>
										</tbody>
									</table>

								</div>
							</div>

							<div class="toggle toggle-chapter active">
								<label>Totalen project</label>
								<div class="toggle-content">
									<table class="table table-striped">
										<?# -- table head -- ?>
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

										<!-- table items -->
										<tbody>
											<tr><!-- item -->
												<th class="col-md-3">&nbsp;</th>
												<th class="col-md-3">&nbsp;</th>
												<td class="col-md-1"><strong><span class="pull-right">{{ CalculationOverview::laborSuperTotalAmount($project) }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::laborSuperTotal($project), 2, ",",".") }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::materialSuperTotal($project), 2, ",",".") }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::equipmentSuperTotal($project), 2, ",",".") }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::superTotal($project), 2, ",",".") }}</span></strong></td>
												<th class="col-md-1">&nbsp;</th>
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
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax1($project), 2, ",",".") }}</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax2($project), 2, ",",".") }}</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax3($project), 2, ",",".") }}</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>

								<tr><!-- item -->
									<td class="col-md-4">Materiaalkosten</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>

								<tr><!-- item -->
									<td class="col-md-4">Materieelkosten</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>

								<tr><!-- item -->
									<td class="col-md-4"><strong>Totaal Aanneming</strong></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalContracting($project), 2, ",",".") }}</strong></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalContractingTax($project), 2, ",",".") }}</strong></td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
							</tbody>
						</table>

						<h4>Onderaanneming</h4>
						<table class="table table-striped">
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
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax1($project), 2, ",",".") }}</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax2($project), 2, ",",".") }}</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax3($project), 2, ",",".") }}</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>

								<tr><!-- item -->
									<td class="col-md-4">Materiaalkosten</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::subconCalcMaterialActivityTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::subconCalcMaterialActivityTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>

								<tr><!-- item -->
									<td class="col-md-4">Materieelkosten</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::subconCalcEquipmentActivityTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::subconCalcEquipmentActivityTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</span></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>

								<tr><!-- item -->
									<td class="col-md-4"><strong>Totaal Onderaanneming </strong></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalSubcontractingTax($project), 2, ",",".") }}</strong></td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
							</tbody>
						</table>

						<h4>Cumulatieven Offerte</h4>
						<table class="table table-striped">
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
									<td class="col-md-2"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalProject($project), 2, ",",".") }}</strong></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-5">BTW bedrag aanneming belast met 21%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::totalContractingTax1($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-5">BTW bedrag aanneming belast met 6%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::totalContractingTax2($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-5">BTW bedrag onderaanneming belast met 21%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::totalSubcontractingTax1($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-5">BTW bedrag onderaanneming belast met 6%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::totalSubcontractingTax2($project), 2, ",",".") }}</td>
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
				</div>

			</div>


		</div>

	</section>

</div>
<!-- /WRAPPER -->
@stop
