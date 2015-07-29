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
		$('#tab-estimate').click(function(e){
			sessionStorage.toggleTabEstim{{Auth::user()->id}} = 'estimate';
		});
		$('#tab-summary').click(function(e){
			sessionStorage.toggleTabEstim{{Auth::user()->id}} = 'summary';
		});
		if (sessionStorage.toggleTabEstim{{Auth::user()->id}}){
			$toggleOpenTab = sessionStorage.toggleTabEstim{{Auth::user()->id}};
			$('#tab-'+$toggleOpenTab).addClass('active');
			$('#'+$toggleOpenTab).addClass('active');
		} else {
			sessionStorage.toggleTabEstim{{Auth::user()->id}} = 'estimate';
			$('#tab-estimate').addClass('active');
			$('#estimate').addClass('active');
		}
		$(".complete").click(function(e){
			$loc = $(this).attr('data-location');
			window.location.href = $loc;
		});
		$("body").on("change", ".form-control-sm-number", function(){
			$(this).val(parseFloat($(this).val().split('.').join('').replace(',', '.')).formatMoney(2, ',', '.'));
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
				$.post("/estimate/updatematerial", {
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
						$curThis.closest("tr").find(".total-ex-tax").text('€ '+$.number(rate*amount,2,',','.'));
						$curThis.closest("tr").find(".total-incl-tax").text('€ '+$.number(rate*amount*((100+{{$project->profit_calc_contr_mat}})/100),2,',','.'));
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
				$.post("/estimate/updateequipment", {
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
						$curThis.closest("tr").find(".total-ex-tax").text('€ '+$.number(rate*amount,2,',','.'));
						$curThis.closest("tr").find(".total-incl-tax").text('€ '+$.number(rate*amount*((100+{{$project->profit_calc_contr_mat}})/100),2,',','.'));
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
				$.post("/estimate/newmaterial", {
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
						$curThis.closest("tr").find(".total-ex-tax").text('€ '+$.number(rate*amount,2,',','.'));
						$curThis.closest("tr").find(".total-incl-tax").text('€ '+$.number(rate*amount*((100+{{$project->profit_calc_contr_mat}})/100),2,',','.'));
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
				$.post("/estimate/newequipment", {
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
						$curThis.closest("tr").find(".total-ex-tax").text('€ '+$.number(rate*amount,2,',','.'));
						$curThis.closest("tr").find(".total-incl-tax").text('€ '+$.number(rate*amount*((100+{{$project->profit_calc_contr_equip}})/100),2,',','.'));
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
				$.post("/estimate/updatematerial", {
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
						$curThis.closest("tr").find(".total-ex-tax").text('€ '+$.number(rate*amount,2,',','.'));
						$curThis.closest("tr").find(".total-incl-tax").text('€ '+$.number(rate*amount*((100+{{$project->profit_calc_contr_mat}})/100),2,',','.'));
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
				$.post("/estimate/updateequipment", {
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
						$curThis.closest("tr").find(".total-ex-tax").text('€ '+$.number(rate*amount,2,',','.'));
						$curThis.closest("tr").find(".total-incl-tax").text('€ '+$.number(rate*amount*((100+{{$project->profit_calc_contr_equip}})/100),2,',','.'));
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
				$.post("/estimate/updatelabor", {
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
				$.post("/estimate/newlabor", {
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
				$.post("/estimate/newmaterial", {
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
						$curThis.closest("tr").find(".total-ex-tax").text('€ '+$.number(rate*amount,2,',','.'));
						$curThis.closest("tr").find(".total-incl-tax").text('€ '+$.number(rate*amount*((100+{{$project->profit_calc_contr_mat}})/100),2,',','.'));
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
				$.post("/estimate/newequipment", {
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
						$curThis.closest("tr").find(".total-ex-tax").text('€ '+$.number(rate*amount,2,',','.'));
						$curThis.closest("tr").find(".total-incl-tax").text('€ '+$.number(rate*amount*((100+{{$project->profit_calc_contr_equip}})/100),2,',','.'));
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
				$date = $curThis.closest("tr").find("input[name='date']").val();
				$hour = $curThis.closest("tr").find("input[name='hour']").val();
				$type = {{ TimesheetKind::where('kind_name','=','stelpost')->first()->id }};
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
					var json = $.parseJSON(data);
					$curThis.closest("tr").find("input").removeClass("error-input");
					if (json.success) {
						$curThis.closest("tr").attr("data-id", json.id);
						var rate = {{ $project->hour_rate }};
						var amount = $curThis.closest("tr").find("input[name='hour']").val().toString().split('.').join('').replace(',', '.');
						var $curTable = $curThis.closest("table");
						var json = $.parseJSON(data);
						$curTable.find("tr:eq(1)").clone().removeAttr("data-id")
						.find("td:eq(0)").text($date).end()
						.find("td:eq(1)").text(json.hour).end()
						.find("td:eq(2)").text('€ '+$.number(rate*amount,2,',','.')).end()
						.find("td:eq(3)").text($note).end()
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
				$.post("/estimate/deletematerial", {project: {{ $project->id }}, id: $curThis.closest("tr").attr("data-id")}, function(){
					$curThis.closest("tr").hide("slow");
				}).fail(function(e) { console.log(e); });
		});
		$("body").on("click", ".sresetrow", function(){
			var $curThis = $(this);
			if($curThis.closest("tr").attr("data-id"))
				$.post("/estimate/resetmaterial", {project: {{ $project->id }}, id: $curThis.closest("tr").attr("data-id")}, function(data){
					var json = $.parseJSON(data);
					$curThis.closest("tr").find("input[name='name']").val(json.name);
					$curThis.closest("tr").find("input[name='unit']").val(json.unit);
					$curThis.closest("tr").find("input[name='rate']").val(json.rate);
					$curThis.closest("tr").find("input[name='amount']").val(json.amount);
					var rate = $curThis.closest("tr").find("input[name='rate']").val().toString().split('.').join('').replace(',', '.');
					var amount = $curThis.closest("tr").find("input[name='amount']").val().toString().split('.').join('').replace(',', '.');
					$curThis.closest("tr").find(".total-ex-tax").text('€ '+$.number(rate*amount,2,',','.'));
					$curThis.closest("tr").find(".total-incl-tax").text('€ '+$.number(rate*amount*((100+{{$project->profit_calc_contr_mat}})/100),2,',','.'));
				}).fail(function(e) { console.log(e); });
		});
		$("body").on("click", ".edeleterow", function(){
			var $curThis = $(this);
			if($curThis.closest("tr").attr("data-id"))
				$.post("/calculation/calc/deleteequipment", {project: {{ $project->id }}, id: $curThis.closest("tr").attr("data-id")}, function(){
					$curThis.closest("tr").hide("slow");
				}).fail(function(e) { console.log(e); });
		});
		$("body").on("click", ".eresetrow", function(){
			var $curThis = $(this);
			if($curThis.closest("tr").attr("data-id"))
				$.post("/estimate/resetequipment", {project: {{ $project->id }}, id: $curThis.closest("tr").attr("data-id")}, function(data){
					var json = $.parseJSON(data);
					$curThis.closest("tr").find("input[name='name']").val(json.name);
					$curThis.closest("tr").find("input[name='unit']").val(json.unit);
					$curThis.closest("tr").find("input[name='rate']").val(json.rate);
					$curThis.closest("tr").find("input[name='amount']").val(json.amount);
					var rate = $curThis.closest("tr").find("input[name='rate']").val().toString().split('.').join('').replace(',', '.');
					var amount = $curThis.closest("tr").find("input[name='amount']").val().toString().split('.').join('').replace(',', '.');
					$curThis.closest("tr").find(".total-ex-tax").text('€ '+$.number(rate*amount,2,',','.'));
					$curThis.closest("tr").find(".total-incl-tax").text('€ '+$.number(rate*amount*((100+{{$project->profit_calc_contr_equip}})/100),2,',','.'));
				}).fail(function(e) { console.log(e); });
		});
		$("body").on("click", ".sdeleterowe", function(){
			var $curThis = $(this);
			if($curThis.closest("tr").attr("data-id"))
				$.post("/estimate/deletematerial", {project: {{ $project->id }}, id: $curThis.closest("tr").attr("data-id")}, function(){
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
		$("body").on("click", ".lresetrow", function(){
			var $curThis = $(this);
			if($curThis.closest("tr").attr("data-id"))
				$.post("/estimate/resetlabor", {project: {{ $project->id }}, id: $curThis.closest("tr").attr("data-id")}, function(data){
					var json = $.parseJSON(data);
					$curThis.closest("tr").find("input[name='amount']").val(json.amount);
					var rate = json.rate.toString().split('.').join('').replace(',', '.');
					var amount = $curThis.closest("tr").find("input[name='amount']").val().toString().split('.').join('').replace(',', '.');
					$curThis.closest("tr").find(".total-ex-tax").text('€ '+$.number(rate*amount,2,',','.'));
				}).fail(function(e) { console.log(e); });
		});
		$("body").on("click", ".ldeleterow", function(){
			var $curThis = $(this);
			if($curThis.closest("tr").attr("data-id"))
				$.post("/estimate/deletelabor", {project: {{ $project->id }}, id: $curThis.closest("tr").attr("data-id")}, function(){
					$curThis.closest("tr").find("input").val("0,00");
					$curThis.closest("tr").find(".total-ex-tax").text('€ '+$.number(0,2,',','.'));
					$curThis.closest("tr").removeAttr("data-id");
				}).fail(function(e) { console.log(e); });
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

		@include('calc.wizard', array('page' => 'estimate'))

			<h2><strong>Stelpost</strong> stellen</h2>

			<div class="tabs nomargin">

				<!-- taDebiteurennummer nieuwe relatiebs -->
				<ul class="nav nav-tabs">
					<li id="tab-estimate">
						<a href="#estimate" data-toggle="tab">
							<i class="fa fa-align-justify"></i> Stelposten stellen
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

					<div id="estimate" class="tab-pane">
						<div class="toogle">

							@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
							<?php
							$acts = Activity::where('chapter_id','=', $chapter->id)->where('part_type_id','=',PartType::where('type_name','=','estimate')->first()->id)->count();
							if (!$acts)
								continue;
							?>
							<div id="toggle-chapter-{{ $chapter->id }}" class="toggle toggle-chapter">
								<label>{{ $chapter->chapter_name }}</label>
								<div class="toggle-content">

									<div class="toogle">

										<?php
										foreach(Activity::where('chapter_id','=', $chapter->id)->where('part_type_id','=',PartType::where('type_name','=','estimate')->first()->id)->get() as $activity) {
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
													<div class="col-md-2"></div>
	    											<div class="col-md-2"></div>
													<div class="col-md-1 text-right"><strong>{{ Part::find($activity->part_id)->part_name=='subcontracting' ? 'Onderaanneming' : 'Aanneming' }}</strong></div>
													<div class="col-md-3 text-right"><button id="pop-{{$chapter->id.'-'.$activity->id}}" data-id="{{ $activity->id }}" data-note="{{ $activity->note }}" data-toggle="modal" data-target="#descModal" class="btn btn-info btn-xs notemod">Omschrijving toevoegen</button></div>
												</div>
												<div class="row">
													<div class="col-md-2"><h4>Arbeid</h4></div>
													<div class="col-md-1 text-right"><strong>BTW</strong></div>
													<div class="col-md-2"><strong>{{ Tax::find($activity->tax_estimate_labor_id)->tax_rate }}%</strong></div>
													<div class="col-md-6"></div>
												</div>
												<table class="table table-striped" data-id="{{ $activity->id }}">
													<?php
													$count = EstimateLabor::where('activity_id','=', $activity->id)->whereNotNull('hour_id')->count('hour_id');
													if ($count) {
													?>
													<thead>
														<tr>
															<th class="col-md-1">Datum</th>
															<th class="col-md-1">Uren</th>
															<th class="col-md-1">Prijs</th>
															<th class="col-md-8">Omschrijving</th>
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

													<?# -- table items -- ?>
													<tbody>
														<?php
														if ($count) {
														?>
														@foreach (EstimateLabor::where('activity_id','=', $activity->id)->whereNotNull('hour_id')->get() as $labor)
														<tr data-id="{{ $labor->id }}">
															<td class="col-md-1">{{ Timesheet::find($labor->hour_id)->register_date }}</td>
															<td class="col-md-1">{{ number_format($labor->set_amount, 2,",",".") }}</td>
															<td class="col-md-1"><span class="total-ex-tax">{{ '&euro; '.number_format(EstimateRegister::estimLaborTotal($labor->original ? ($labor->isset ? $labor->set_rate : $labor->rate) : $labor->set_rate, $labor->original ? ($labor->isset ? $labor->set_amount : $labor->amount) : $labor->set_amount), 2, ",",".") }}</span></td>
															<td class="col-md-8">{{ Timesheet::find($labor->hour_id)->note }}</td>
															<td class="col-md-1 text-right"><button class="btn btn-xs fa btn-danger fa-times xdeleterow"></button></td>
														</tr>
														@endforeach
														<tr>
															<td class="col-md-1"><input type="date" name="date" id="date" class="form-control-sm-text lsave"/></td>
															<td class="col-md-1"><input type="number" min="0" name="hour" id="hour" class="form-control-sm-text lsave"/></td>
															<td class="col-md-1"><span class="total-ex-tax"></span></td>
															<td class="col-md-8"><input type="text" name="note" id="note" class="form-control-sm-text lsave"/></td>
															<td class="col-md-1">&nbsp;</td>
														</tr>
														<?php }else{ ?>
														<?php
														$labor = EstimateLabor::where('activity_id','=', $activity->id)->first();
														?>
														<tr data-id="{{ $labor['id'] }}"><?# -- item -- ?>
															<td class="col-md-5">Arbeidsuren</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">{{ number_format($project->hour_rate, 2,",",".") }}</td>
															<td class="col-md-1"><input data-id="{{ $activity->id }}" name="amount" type="text" value="{{ number_format($labor['original'] ? ($labor['isset'] ? $labor['set_amount'] : $labor['amount']) : $labor['set_amount'], 2, ",",".") }}" class="form-control-sm-number labor-amount lsavee" /></td>
															<td class="col-md-1"><span class="total-ex-tax">{{ '&euro; '.number_format(EstimateRegister::estimLaborTotal($project->hour_rate, $labor['original'] ? ($labor['isset'] ? $labor['set_amount'] : $labor['amount']) : $labor['set_amount']), 2, ",",".") }}</span></td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1 text-right"><button class="btn {{ ($labor['original'] ? 'btn-warning fa-undo lresetrow' : 'btn-danger ldeleterow fa-times' ) }} btn-xs fa"></button></td>
														</tr>
														<?php } ?>
													</tbody>
												</table>

												<div class="row">
													<div class="col-md-2"><h4>Materiaal</h4></div>
													<div class="col-md-1 text-right"><strong>BTW</strong></div>
													<div class="col-md-2"><strong>{{ Tax::find($activity->tax_estimate_material_id)->tax_rate }}%</strong></div>
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
															<td class="col-md-5"><input name="name" id="name" type="text" value="{{ $material->original ? ($material->isset ? $material->set_material_name : $material->material_name) : $material->set_material_name }}" class="form-control-sm-text dsavee newrow" /></td>
															<td class="col-md-1"><input name="unit" id="name" type="text" value="{{ $material->original ? ($material->isset ? $material->set_unit : $material->unit) : $material->set_unit }}" class="form-control-sm-text dsavee" /></td>
															<td class="col-md-1"><input name="rate" id="name" type="text" value="{{ number_format($material->original ? ($material->isset ? $material->set_rate : $material->rate) : $material->set_rate, 2,",",".") }}" class="form-control-sm-number dsavee" /></td>
															<td class="col-md-1"><input name="amount" id="name" type="text" value="{{ number_format($material->original ? ($material->isset ? $material->set_amount : $material->amount) : $material->set_amount, 2,",",".") }}" class="form-control-sm-number dsavee" /></td>
															<td class="col-md-1"><span class="total-ex-tax">{{ '&euro; '.number_format($material->original ? ($material->isset ? $material->set_rate * $material->set_amount : $material->rate * $material->amount) : $material->set_rate * $material->set_amount, 2,",",".") }}</span></td>
															<td class="col-md-1"><span class="total-incl-tax">{{ '&euro; '.number_format(($material->original ? ($material->isset ? $material->set_rate * $material->set_amount : $material->rate * $material->amount) : $material->set_rate * $material->set_amount) *((100+$profit_mat)/100), 2,",",".") }}</span></td>
															<td class="col-md-1 text-right">
																<button class="btn-xs fa fa-book" data-toggle="modal" data-target="#myModal"></button>
																<button class="btn btn-xs fa {{$material->original ? 'btn-warning fa-undo sresetrow' : 'btn-danger fa-times sdeleterow'}}"></button>
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
															<td class="col-md-1 text-right">
																<button class="btn-xs fa fa-book" data-toggle="modal" data-target="#myModal"></button>
																<button class="btn btn-xs fa btn-danger fa-times"></button>
															</td>
														</tr>
													</tbody>
													<tbody>
														<tr>
															<td class="col-md-5"><strong>Totaal</strong></td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1"><strong>{{ '&euro; '.number_format(EstimateRegister::estimMaterialTotal($activity->id, $profit_mat), 2, ",",".") }}</span></td>
															<td class="col-md-1"><strong>{{ '&euro; '.number_format(EstimateRegister::estimMaterialTotalProfit($activity->id, $profit_mat), 2, ",",".") }}</span></td>
															<td class="col-md-1">&nbsp;</td>
														</tr>
													</tbody>
												</table>

												<div class="row">
													<div class="col-md-2"><h4>Materieel</h4></div>
													<div class="col-md-1 text-right"><strong>BTW</strong></div>
													<div class="col-md-2"><strong>{{ Tax::find($activity->tax_estimate_equipment_id)->tax_rate }}%</strong></div>
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
															<td class="col-md-5"><input name="name" id="name" type="text" value="{{ $equipment->original ? ($equipment->isset ? $equipment->set_equipment_name : $equipment->equipment_name) : $equipment->set_equipment_name }}" class="form-control-sm-text esavee newrow" /></td>
															<td class="col-md-1"><input name="unit" id="name" type="text" value="{{ $equipment->original ? ($equipment->isset ? $equipment->set_unit : $equipment->unit) : $equipment->set_unit }}" class="form-control-sm-text esave" /></td>
															<td class="col-md-1"><input name="rate" id="name" type="text" value="{{ number_format($equipment->original ? ($equipment->isset ? $equipment->set_rate : $equipment->rate) : $equipment->set_rate, 2,",",".") }}" class="form-control-sm-number esavee" /></td>
															<td class="col-md-1"><input name="amount" id="name" type="text" value="{{ number_format($equipment->original ? ($equipment->isset ? $equipment->set_amount : $equipment->amount) : $equipment->set_amount, 2,",",".") }}" class="form-control-sm-number esavee" /></td>
															<td class="col-md-1"><span class="total-ex-tax">{{ '&euro; '.number_format($equipment->original ? ($equipment->isset ? $equipment->set_rate * $equipment->set_amount : $equipment->rate * $equipment->amount) : $equipment->set_rate * $equipment->set_amount, 2,",",".") }}</span></td>
															<td class="col-md-1"><span class="total-incl-tax">{{ '&euro; '.number_format(($equipment->original ? ($equipment->isset ? $equipment->set_rate * $equipment->set_amount : $equipment->rate * $equipment->amount) : $equipment->set_rate * $equipment->set_amount)*((100+$profit_equip)/100), 2,",",".") }}</span></td>
															<td class="col-md-1 text-right">
																<button class="btn-xs fa fa-book" data-toggle="modal" data-target="#myModal"></button>
																<button class="btn btn-xs fa {{$equipment->original ? 'btn-warning fa-undo eresetrow' : 'btn-danger fa-times edeleterow'}}"></button>
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
															<td class="col-md-1 text-right">
																<button class="btn-xs fa fa-book" data-toggle="modal" data-target="#myModal"></button>
																<button class="btn btn-xs fa btn-danger fa-times"></button>
															</td>
														</tr>
													</tbody>
													<tbody>
														<tr>
															<td class="col-md-5"><strong>Totaal</strong></td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1"><strong>{{ '&euro; '.number_format(EstimateRegister::estimEquipmentTotal($activity->id, $profit_equip), 2, ",",".") }}</span></td>
															<td class="col-md-1"><strong>{{ '&euro; '.number_format(EstimateRegister::estimEquipmentTotalProfit($activity->id, $profit_equip), 2, ",",".") }}</span></td>
															<td class="col-md-1">&nbsp;</td>
														</tr>
													</tbody>
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
											</tr>
										</thead>

										<!-- table items -->
										<tbody>
											@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
											@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->where('part_type_id','=',PartType::where('type_name','=','estimate')->first()->id)->get() as $activity)
											<tr><!-- item -->
												<td class="col-md-3"><strong>{{ $chapter->chapter_name }}</strong></td>
												<td class="col-md-3">{{ $activity->activity_name }}</td>
												<td class="col-md-1"><span class="pull-right">{{ number_format(EstimateOverview::laborTotal($activity), 2, ",",".") }}</td>
												<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(EstimateOverview::laborActivity($activity), 2, ",",".") }}</span></td>
												<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(EstimateOverview::materialActivityProfit($activity, $project->profit_calc_contr_mat), 2, ",",".") }}</span></td>
												<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::equipmentActivityProfit($activity, $project->profit_calc_contr_equip), 2, ",",".") }}</span></td>
												<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::activityTotalProfit($activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip), 2, ",",".") }} </td>
											</tr>
											@endforeach
											@endforeach
											<tr><!-- item -->
												<th class="col-md-3"><strong>Totaal Aanneming</strong></th>
												<th class="col-md-3">&nbsp;</th>
												<td class="col-md-1"><strong><span class="pull-right">{{ EstimateOverview::contrLaborTotalAmount($project) }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::contrLaborTotal($project), 2, ",",".") }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::contrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::contrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::contrTotal($project), 2, ",",".") }}</span></strong></td>
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
											</tr>
										</thead>

										<!-- table items -->
										<tbody>
											@foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
											@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->where('part_type_id','=',PartType::where('type_name','=','estimate')->first()->id)->get() as $activity)
											<tr><!-- item -->
												<td class="col-md-3"><strong>{{ $chapter->chapter_name }}</strong></td>
												<td class="col-md-3">{{ $activity->activity_name }}</td>
												<td class="col-md-1"><span class="pull-right">{{ number_format(EstimateOverview::laborTotal($activity), 2, ",",".") }}</td>
												<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(EstimateOverview::laborActivity($activity), 2, ",",".") }}</span></td>
												<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(EstimateOverview::MaterialActivityProfit($activity, $project->profit_calc_subcontr_mat), 2, ",",".") }}</span></td>
												<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::equipmentActivityProfit($activity, $project->profit_calc_subcontr_equip), 2, ",",".") }}</span></td>
												<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::activityTotalProfit($activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip), 2, ",",".") }} </td>
											</tr>
											@endforeach
											@endforeach
											<tr><!-- item -->
												<th class="col-md-3"><strong>Totaal Onderaanneming</strong></th>
												<th class="col-md-3">&nbsp;</th>
												<td class="col-md-1"><strong><span class="pull-right">{{ EstimateOverview::subcontrLaborTotalAmount($project) }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::subcontrLaborTotal($project), 2, ",",".") }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::subcontrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::subcontrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::subcontrTotal($project), 2, ",",".") }}</span></strong></td>
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
											</tr>
										</thead>

										<!-- table items -->
										<tbody>
											<tr><!-- item -->
												<th class="col-md-3">&nbsp;</th>
												<th class="col-md-3">&nbsp;</th>
												<td class="col-md-1"><strong><span class="pull-right">{{ EstimateOverview::laborSuperTotalAmount($project) }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::laborSuperTotal($project), 2, ",",".") }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::materialSuperTotal($project), 2, ",",".") }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::equipmentSuperTotal($project), 2, ",",".") }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::superTotal($project), 2, ",",".") }}</span></strong></td>
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
									<th class="col-md-2">BTW bedrag</th>
									<th class="col-md-1">&nbsp;</th>
								</tr>
							</thead>

							<!-- table items -->
							<tbody>
								<tr><!-- item -->
									<td class="col-md-4">Arbeidskosten</td>
									<td class="col-md-1">{{ number_format(EstimateEndresult::conCalcLaborActivityTax1($project), 2, ",",".") }}</td>
									<td class="col-md-2">{{ '&euro; '.number_format(EstimateEndresult::conCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(EstimateEndresult::conCalcLaborActivityTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">{{ number_format(EstimateEndresult::conCalcLaborActivityTax2($project), 2, ",",".") }}</td>
									<td class="col-md-2">{{ '&euro; '.number_format(EstimateEndresult::conCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(EstimateEndresult::conCalcLaborActivityTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">{{ number_format(EstimateEndresult::conCalcLaborActivityTax3($project), 2, ",",".") }}</td>
									<td class="col-md-2">{{ '&euro; '.number_format(EstimateEndresult::conCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>

								<tr><!-- item -->
									<td class="col-md-4">Materiaalkosten</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(EstimateEndresult::conCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(EstimateEndresult::conCalcMaterialActivityTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(EstimateEndresult::conCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(EstimateEndresult::conCalcMaterialActivityTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(EstimateEndresult::conCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>

								<tr><!-- item -->
									<td class="col-md-4">Materieelkosten</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(EstimateEndresult::conCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(EstimateEndresult::conCalcEquipmentActivityTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(EstimateEndresult::conCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(EstimateEndresult::conCalcEquipmentActivityTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(EstimateEndresult::conCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>

								<tr><!-- item -->
									<td class="col-md-4"><strong>Totaal Aanneming </strong></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1"><strong>{{ '&euro; '.number_format(EstimateEndresult::totalContracting($project), 2, ",",".") }}</strong></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1"><strong>{{ '&euro; '.number_format(EstimateEndresult::totalContractingTax($project), 2, ",",".") }}</strong></td>
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
									<th class="col-md-2">BTW bedrag</th>
									<th class="col-md-1">&nbsp;</th>
								</tr>
							</thead>

							<!-- table items -->
							<tbody>
								<tr><!-- item -->
									<td class="col-md-4">Arbeidskosten</td>
									<td class="col-md-1">{{ number_format(EstimateEndresult::subconCalcLaborActivityTax1($project), 2, ",",".") }}</td>
									<td class="col-md-2">{{ '&euro; '.number_format(EstimateEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(EstimateEndresult::subconCalcLaborActivityTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">{{ number_format(EstimateEndresult::subconCalcLaborActivityTax2($project), 2, ",",".") }}</td>
									<td class="col-md-2">{{ '&euro; '.number_format(EstimateEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(EstimateEndresult::subconCalcLaborActivityTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">{{ number_format(EstimateEndresult::subconCalcLaborActivityTax3($project), 2, ",",".") }}</td>
									<td class="col-md-2">{{ '&euro; '.number_format(EstimateEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>

								<tr><!-- item -->
									<td class="col-md-4">Materiaalkosten</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(EstimateEndresult::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(EstimateEndresult::subconCalcMaterialActivityTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(EstimateEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(EstimateEndresult::subconCalcMaterialActivityTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(EstimateEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>

								<tr><!-- item -->
									<td class="col-md-4">Materieelkosten</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(EstimateEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(EstimateEndresult::subconCalcEquipmentActivityTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(EstimateEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(EstimateEndresult::subconCalcEquipmentActivityTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(EstimateEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>

								<tr><!-- item -->
									<td class="col-md-4"><strong>Totaal Onderaanneming </strong></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1"><strong>{{ '&euro; '.number_format(EstimateEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1"><strong>{{ '&euro; '.number_format(EstimateEndresult::totalSubcontractingTax($project), 2, ",",".") }}</strong></td>
								</tr>
							</tbody>
						</table>

						<h4>Cumulatieven Stelpost</h4>
						<table class="table table-striped">
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
									<td class="col-md-6">Calculatief te factureren (excl. BTW)</td>
									<td class="col-md-2">{{ '&euro; '.number_format(EstimateEndresult::totalProject($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-6">BTW bedrag aanneming belast met 21%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(EstimateEndresult::totalContractingTax1($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-6">BTW bedrag aanneming belast met 6%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(EstimateEndresult::totalContractingTax2($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-6">BTW bedrag onderaanneming belast met 21%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(EstimateEndresult::totalSubcontractingTax1($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-6">BTW bedrag onderaanneming belast met 6%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(EstimateEndresult::totalSubcontractingTax2($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-6">Te factureren BTW bedrag</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(EstimateEndresult::totalProjectTax($project), 2, ",",".") }}</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-6"><strong>Calculatief te factureren (Incl. BTW)</strong></td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-2"><strong>{{ '&euro; '.number_format(EstimateEndresult::superTotalProject($project), 2, ",",".") }}</strong></td>
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
