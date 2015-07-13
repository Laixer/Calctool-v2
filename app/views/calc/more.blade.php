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
		$(".popdesc").popover({
	        html: true,
	        trigger: 'manual',
	        container: $(this).attr('id'),
	        placement: 'bottom',
	        content: function () {
	            $return = '<div class="hover-hovercard"></div>';
	        }
	    }).on("mouseenter", function () {
	        var _this = this;
	        $(this).popover("show");
	        $(this).siblings(".popover").on("mouseleave", function () {
	            $(_this).popover('hide');
	        });
	    }).on("mouseleave", function () {
	        var _this = this;
	        setTimeout(function () {
	            if (!$(".popover:hover").length) {
	                $(_this).popover("hide")
	            }
	        }, 100);
	    });
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
				$.post("/more/updatematerial", {
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
				$.post("/more/updateequipment", {
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
				$.post("/more/updatelabor", {
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
							rate = {{$project->hour_rate_more}};
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
					var json = $.parseJSON(data);
					$curThis.closest("tr").find("input").removeClass("error-input");
					if (json.success) {
						$curThis.closest("tr").attr("data-id", json.id);
						var rate = $curThis.closest("tr").find("input[name='rate']").val()
						if (rate) {
							rate.toString().split('.').join('').replace(',', '.');
						} else {
							rate = {{$project->hour_rate_more}};
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
				$.post("/more/newequipment", {
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
		$("body").on("blur", ".tsave", function(){
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
<div id="wrapper">

	<section class="container fix-footer-bottom">

		<div class="col-md-12">

			<div class="wizard">
				<a href="/"> Home</a>
				<a href="/project-{{ $project->id }}/edit">Project</a>
				<a href="/calculation/project-{{ $project->id }}">Calculatie</a>
				<a href="/offer/project-{{ $project->id }}">Offerte</a>
				<a href="/estimate/project-{{ $project->id }}">Stelpost</a>
				<a href="/less/project-{{ $project->id }}">Minderwerk</a>
				<a href="javascript:void(0);" class="current">Meerwerk</a>
				<a href="/invoice/project-{{ $project->id }}">Factuur</a>
				<a href="/result/project-{{ $project->id }}">Resultaat</a>
			</div>

			<hr />

			<h2><strong>Meerwerk</strong></h2>

			<div class="tabs nomargin">

				<!-- tabs -->
				<ul class="nav nav-tabs">
					<li id="tab-calculate">
						<a href="#calculate" data-toggle="tab">
							<i class="fa fa-list"></i> Calculeren
						</a>
					</li>
					<li id="tab-summary">
						<a href="#summary" data-toggle="tab">
							<i class="fa fa-align-justify"></i> Uittrekstaat
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

										@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_type_id','=',PartType::where('type_name','=','calculation')->first()->id)->where('detail_id','=',Detail::where('detail_name','=','more')->first()->id)->get() as $activity)
										<div id="toggle-activity-{{ $activity->id }}" class="toggle toggle-activity">
											<label>{{ $activity->activity_name }}</label>
											<div class="toggle-content">
												<div class="row">
													<div class="col-md-4"></div>
													<div class="col-md-2"><label class="radio-inline"><input data-id="{{ $activity->id }}" class="radio-activity" name="soort{{ $activity->id }}" value="{{ Part::where('part_name','=','contracting')->first()->id }}" type="radio" {{ ( Part::find($activity->part_id)->part_name=='contracting' ? 'checked' : '') }}/>Aanneming</label></div>
	    											<div class="col-md-2"><label class="radio-inline"><input data-id="{{ $activity->id }}" class="radio-activity" name="soort{{ $activity->id }}" value="{{ Part::where('part_name','=','subcontracting')->first()->id }}" type="radio" {{ ( Part::find($activity->part_id)->part_name=='subcontracting' ? 'checked' : '') }}/>Onderaanneming</label></div>
													<div class="col-md-2 text-right"><button id="pop-{{$chapter->id.'-'.$activity->id}}" data-id="{{ $activity->id }}" data-container="body" data-toggle="popover" data-placement="bottom" data-content="<textarea></textarea>" data-original-title="A Title" title="" aria-describedby="popover499619" class="btn btn-info btn-xs popdesc">Omschrijving toevoegen</button></div>
													<div class="col-md-2 text-right"><button data-id="{{ $activity->id }}" class="btn btn-danger btn-xs deleteact">Verwijderen</button></div>
												</div>
												<div class="row">
													<div class="col-md-2"><h4>Arbeid</h4></div>
													<div class="col-md-1 text-right"><strong>BTW</strong></div>
													<div class="col-md-2">
														<select name="btw" data-id="{{ $activity->id }}" data-type="calc-labor" id="type" class="form-control-sm-text pointer select-tax">
														@foreach (Tax::all() as $tax)
															<option value="{{ $tax->id }}" {{ ($activity->tax_more_labor_id==$tax->id ? 'selected="selected"' : '') }}>{{ $tax->tax_rate }}%</option>
														@endforeach
														</select>
													</div>
													<div class="col-md-6"></div>
												</div>
												<table class="table table-striped" data-id="{{ $activity->id }}">
													<?php
													$count = MoreLabor::where('activity_id','=', $activity->id)->whereNotNull('hour_id')->count('hour_id');
													if ($count) {
													?>
													<thead>
														<tr>
															<th class="col-md-1">Datum</th>
															<th class="col-md-1">Uren</th>
															<th class="col-md-1">Prijs</th>
															<th class="col-md-5">Omschrijving</th>
															<th class="col-md-1">&nbsp;</th>
															<th class="col-md-1">&nbsp;</th>
															<th class="col-md-1">&nbsp;</th>
															<th class="col-md-1">&nbsp;</th>
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
															<th class="col-md-1">&nbsp;</th>
														</tr>
													</thead>
													<?php } ?>

													<tbody>
														<?php
														if ($count) {
														?>
														@foreach (MoreLabor::where('activity_id','=', $activity->id)->whereNotNull('hour_id')->get() as $labor)
														<tr data-id="{{ $labor->id }}">
															<td class="col-md-1">{{ Timesheet::find($labor->hour_id)->register_date }}</td>
															<td class="col-md-1">{{ number_format($labor->amount, 2,",",".") }}</td>
															<td class="col-md-1"><span class="total-ex-tax">{{ '&euro; '.number_format(MoreRegister::laborTotal($labor->rate, $labor->amount, 2, ",",".")) }}</span></td>
															<td class="col-md-5">{{ Timesheet::find($labor->hour_id)->note }}</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1 text-right"><button class="btn btn-xs fa btn-danger fa-times xdeleterow"></button></td>
														</tr>
														@endforeach
														<tr>
															<td class="col-md-1"><input type="date" name="date" id="date" class="form-control-sm-text tsave"/></td>
															<td class="col-md-1"><input type="number" min="0" name="hour" id="hour" class="form-control-sm-text tsave"/></td>
															<td class="col-md-1"><span class="total-ex-tax"></span></td>
															<td class="col-md-5"><input type="text" name="note" id="note" class="form-control-sm-text tsave"/></td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">&nbsp;</td>
														</tr>
														<?php
														}else {
															$labor = MoreLabor::where('activity_id','=', $activity->id)->first();
														?>
														<tr {{ $labor ? ('data-id="'.$labor->id.'"') : '' }} >
															<td class="col-md-5">Arbeidsuren</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-1">{{ $labor ? number_format($project->hour_rate_more, 2,",",".") : '' }}</td>
															<td class="col-md-1"><input data-id="{{ $activity->id }}" name="amount" type="text" value="{{ $labor ? number_format($labor->amount, 2, ",",".") : '' }}" class="form-control-sm-number labor-amount lsave" /></td>
															<td class="col-md-1"><span class="total-ex-tax">{{ $labor ? ('&euro; '.number_format(MoreRegister::laborTotal($labor->rate, $labor->amount, 2, ",","."))) : '' }}</span></td>
															<td class="col-md-1">&nbsp;</td>
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
														<select name="btw" data-id="{{ $activity->id }}" data-type="calc-material" id="type" class="form-control-sm-text pointer select-tax">
														@foreach (Tax::all() as $tax)
															<option value="{{ $tax->id }}" {{ ($activity->tax_more_material_id==$tax->id ? 'selected="selected"' : '') }}>{{ $tax->tax_rate }}%</option>
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
															<td class="col-md-1 text-right" data-profit="{{$profit}}">
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
															<td class="col-md-1 text-right" data-profit="{{--$profit--}}">
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
															<td class="col-md-1"><strong>
															<?php
															if (Part::find($activity->part_id)->part_name=='contracting') {
																$profit = $project->profit_more_contr_mat;
															} else if (Part::find($activity->part_id)->part_name=='subcontracting') {
																$profit = $project->profit_more_subcontr_mat;
															}
															echo '&euro; '.number_format(MoreRegister::materialTotal($activity->id, $profit), 2, ",",".");
															?></span></td>
															<td class="col-md-1"><strong>
															<?php
															if (Part::find($activity->part_id)->part_name=='contracting') {
																$profit = $project->profit_more_contr_mat;
															} else if (Part::find($activity->part_id)->part_name=='subcontracting') {
																$profit = $project->profit_more_subcontr_mat;
															}
															echo '&euro; '.number_format(MoreRegister::materialTotalProfit($activity->id, $profit), 2, ",",".");
															?></span></td>
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
															<option value="{{ $tax->id }}" {{ ($activity->tax_more_equipment_id==$tax->id ? 'selected="selected"' : '') }}>{{ $tax->tax_rate }}%</option>
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
															<td class="col-md-1 text-right" data-profit="{{--$profit--}}">
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
											</div>
										</div>
										@endforeach
									</div>

									{{ Form::open(array('url' => '/more/newactivity/' . $chapter->id)) }}
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
											<button data-id="{{ $chapter->id }}" class="btn btn-danger deletechap">Verwijderen</button>
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
									<input type="hidden" name="project" value="{{ $project->id }}">
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
												<th class="col-md-4">&nbsp;</th>
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
											@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->where('detail_id','=',Detail::where('detail_name','=','more')->first()->id)->get() as $activity)
											<tr><!-- item -->
												<td class="col-md-3"><strong>{{ $chapter->chapter_name }}</strong></td>
												<td class="col-md-4">{{ $activity->activity_name }}</td>
												<td class="col-md-1"><span class="pull-right">{{ number_format(MoreOverview::laborTotal($activity), 2, ",",".") }}</td>
												<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(MoreOverview::laborActivity($activity), 2, ",",".") }}</span></td>
												<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(MoreOverview::materialActivityProfit($activity, $project->profit_more_contr_mat), 2, ",",".") }}</span></td>
												<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::equipmentActivityProfit($activity, $project->profit_more_contr_equip), 2, ",",".") }}</span></td>
												<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::activityTotalProfit($activity, $project->profit_more_contr_mat, $project->profit_more_contr_equip), 2, ",",".") }} </td>
											</tr>
											@endforeach
											@endforeach
											<tr><!-- item -->
												<th class="col-md-3"><strong>Totaal Aanneming</strong></th>
												<th class="col-md-4">&nbsp;</th>
												<td class="col-md-1"><strong><span class="pull-right">{{ MoreOverview::contrLaborTotalAmount($project) }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::contrLaborTotal($project), 2, ",",".") }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::contrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::contrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::contrTotal($project), 2, ",",".") }}</span></strong></td>
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
												<th class="col-md-4">&nbsp;</th>
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
											@foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->where('detail_id','=',Detail::where('detail_name','=','more')->first()->id)->get() as $activity)
											<tr><!-- item -->
												<td class="col-md-3"><strong>{{ $chapter->chapter_name }}</strong></td>
												<td class="col-md-4">{{ $activity->activity_name }}</td>
												<td class="col-md-1"><span class="pull-right">{{ number_format(MoreOverview::laborTotal($activity), 2, ",",".") }}</td>
												<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(MoreOverview::laborActivity($activity), 2, ",",".") }}</span></td>
												<td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(MoreOverview::materialActivityProfit($activity, $project->profit_more_subcontr_mat), 2, ",",".") }}</span></td>
												<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::equipmentActivityProfit($activity, $project->profit_more_subcontr_equip), 2, ",",".") }}</span></td>
												<td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::activityTotalProfit($activity, $project->profit_more_subcontr_mat, $project->profit_more_subcontr_equip), 2, ",",".") }} </td>
											</tr>
											@endforeach
											@endforeach
											<tr><!-- item -->
												<th class="col-md-3"><strong>Totaal Onderaanneming</strong></th>
												<th class="col-md-4">&nbsp;</th>
												<td class="col-md-1"><strong><span class="pull-right">{{ MoreOverview::subcontrLaborTotalAmount($project) }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::subcontrLaborTotal($project), 2, ",",".") }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::subcontrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::subcontrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
												<td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::subcontrTotal($project), 2, ",",".") }}</span></strong></td>
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
												<th class="col-md-4">&nbsp;</th>
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
												<th class="col-md-4">&nbsp;</th>
												<td class="col-md-1"><span class="pull-right"><strong>{{ MoreOverview::laborSuperTotalAmount($project) }}</span></td>
												<td class="col-md-1"><span class="pull-right"><strong>{{ '&euro; '.number_format(MoreOverview::laborSuperTotal($project), 2, ",",".") }}</strong></span></td>
												<td class="col-md-1"><span class="pull-right"><strong>{{ '&euro; '.number_format(MoreOverview::materialSuperTotal($project), 2, ",",".") }}</strong></span></td>
												<td class="col-md-1"><span class="pull-right"><strong>{{ '&euro; '.number_format(MoreOverview::equipmentSuperTotal($project), 2, ",",".") }}</strong></span></td>
												<td class="col-md-1"><span class="pull-right"><strong>{{ '&euro; '.number_format(MoreOverview::superTotal($project), 2, ",",".") }}</strong></span></td>
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
									<th class="col-md-1">Manuren</th>
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
									<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcLaborActivityTax1($project), 2, ",",".") }}</td>
									<td class="col-md-2">{{ '&euro; '.number_format(MoreEndresult::conCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(MoreEndresult::conCalcLaborActivityTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcLaborActivityTax2($project), 2, ",",".") }}</td>
									<td class="col-md-2">{{ '&euro; '.number_format(MoreEndresult::conCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(MoreEndresult::conCalcLaborActivityTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcLaborActivityTax3($project), 2, ",",".") }}</td>
									<td class="col-md-2">{{ '&euro; '.number_format(MoreEndresult::conCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>

								<tr><!-- item -->
									<td class="col-md-4">Materiaalkosten</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(MoreEndresult::conCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(MoreEndresult::conCalcMaterialActivityTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(MoreEndresult::conCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(MoreEndresult::conCalcMaterialActivityTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(MoreEndresult::conCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>

								<tr><!-- item -->
									<td class="col-md-4">Materieelkosten</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(MoreEndresult::conCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(MoreEndresult::conCalcEquipmentActivityTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(MoreEndresult::conCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(MoreEndresult::conCalcEquipmentActivityTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(MoreEndresult::conCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>

								<tr><!-- item -->
									<td class="col-md-4"><strong>Totaal Aanneming </strong></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1"><strong>{{ '&euro; '.number_format(MoreEndresult::totalContracting($project), 2, ",",".") }}</strong></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1"><strong>{{ '&euro; '.number_format(MoreEndresult::totalContractingTax($project), 2, ",",".") }}</strong></td>
								</tr>
							</tbody>
						</table>

						<h4>Onderaanneming</h4>
						<table class="table table-striped">
							<?# -- table head -- ?>
							<thead>
								<tr>
									<th class="col-md-4">&nbsp;</th>
									<th class="col-md-1">Manuren</th>
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
									<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::subconCalcLaborActivityTax1($project), 2, ",",".") }}</td>
									<td class="col-md-2">{{ '&euro; '.number_format(MoreEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(MoreEndresult::subconCalcLaborActivityTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::subconCalcLaborActivityTax2($project), 2, ",",".") }}</td>
									<td class="col-md-2">{{ '&euro; '.number_format(MoreEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(MoreEndresult::subconCalcLaborActivityTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::subconCalcLaborActivityTax3($project), 2, ",",".") }}</td>
									<td class="col-md-2">{{ '&euro; '.number_format(MoreEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>

								<tr><!-- item -->
									<td class="col-md-4">Materiaalkosten</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(MoreEndresult::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(MoreEndresult::subconCalcMaterialActivityTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(MoreEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(MoreEndresult::subconCalcMaterialActivityTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(MoreEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>

								<tr><!-- item -->
									<td class="col-md-4">Materieelkosten</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(MoreEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(MoreEndresult::subconCalcEquipmentActivityTax1AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(MoreEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-2">{{ '&euro; '.number_format(MoreEndresult::subconCalcEquipmentActivityTax2AmountTax($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(MoreEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>

								<tr><!-- item -->
									<td class="col-md-4"><strong>Totaal Onderaanneming </strong></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1"><strong>{{ '&euro; '.number_format(MoreEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1"><strong>{{ '&euro; '.number_format(MoreEndresult::totalSubcontractingTax($project), 2, ",",".") }}</strong></td>
								</tr>
							</tbody>
						</table>

						<h4>Cumulatieven voor op factureren</h4>
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
									<td class="col-md-2">{{ '&euro; '.number_format(MoreEndresult::totalProject($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-6">BTW bedrag aanneming belast met 21%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(MoreEndresult::totalContractingTax1($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-6">BTW bedrag aanneming belast met 6%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(MoreEndresult::totalContractingTax2($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-6">BTW bedrag onderaanneming belast met 21%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(MoreEndresult::totalSubcontractingTax1($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-6">BTW bedrag onderaanneming belast met 6%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(MoreEndresult::totalSubcontractingTax2($project), 2, ",",".") }}</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-6">Te factureren BTW bedrag</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-2">{{ '&euro; '.number_format(MoreEndresult::totalProjectTax($project), 2, ",",".") }}</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-6"><strong>Calculatief te factureren (Incl. BTW)</strong></td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-2"><strong>{{ '&euro; '.number_format(MoreEndresult::superTotalProject($project), 2, ",",".") }}</strong></td>
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
