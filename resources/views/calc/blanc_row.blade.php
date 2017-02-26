<?php

use \Calctool\Models\Project;
use \Calctool\Models\SubGroup;
use \Calctool\Models\Chapter;
use \Calctool\Models\BlancRow;
use \Calctool\Calculus\CalculationOverview;
use \Calctool\Models\ProjectType;
use \Calctool\Models\Activity as ProjectActivity;
use \Calctool\Models\PartType;
use \Calctool\Models\Part;
use \Calctool\Calculus\CalculationEndresult;
use \Calctool\Models\Tax;
use \Calctool\Models\CalculationLabor;
use \Calctool\Calculus\CalculationRegister;
use \Calctool\Models\CalculationMaterial;
use \Calctool\Models\CalculationEquipment;
use \Calctool\Models\EstimateLabor;
use \Calctool\Models\EstimateMaterial;
use \Calctool\Models\EstimateEquipment;

$common_access_error = false;
$project = Project::find(Route::Input('project_id'));
if (!$project || !$project->isOwner())
	$common_access_error = true;
?>

@extends('layout.master')

@section('title', 'Offerteregels')

@push('scripts')
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
				$.post("/blancrow/updaterow", {
					id: $curThis.closest("tr").attr("data-id"),
					name: $curThis.closest("tr").find("input[name='name']").val(),
					tax: $curThis.closest("tr").find("select[name='tax']").val(),
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
				$.post("/blancrow/newrow", {
					name: $curThis.closest("tr").find("input[name='name']").val(),
					tax: $curThis.closest("tr").find("select[name='tax']").val(),
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
		$("body").on("click", ".ldeleterow", function(){
			var $curThis = $(this);
			if($curThis.closest("tr").attr("data-id"))
				$.post("/calculation/calc/deletelabor", {project: {{ $project->id }}, id: $curThis.closest("tr").attr("data-id")}, function(){
					$curThis.closest("tr").find("input").val("0,00");
					$curThis.closest("tr").find(".total-ex-tax").text('€ 0,00');
				}).fail(function(e) { console.log(e); });
		});
		$("body").on("click", ".ldeleterowe", function(){
			var $curThis = $(this);
			if($curThis.closest("tr").attr("data-id"))
				$.post("/calculation/estim/deletelabor", {project: {{ $project->id }}, id: $curThis.closest("tr").attr("data-id")}, function(){
					$curThis.closest("tr").find("input").val("0,00");
					$curThis.closest("tr").find(".total-ex-tax").text('€ 0,00');
				}).fail(function(e) { console.log(e); });
		});
	});
</script>
<div id="wrapper">

	<section class="container">

		@include('calc.wizard', array('page' => 'calculation'))

			<h2><strong>Offerteregels</strong></h2>

			<div class="white-row">

				<table class="table table-striped">
					<thead>
						<tr>
							<th class="col-md-6">Omschrijving</th>
							<th class="col-md-1">&euro; / Eenh.</th>
							<th class="col-md-1">Aantal</th>
							<th class="col-md-1">BTW</th>
							<th class="col-md-1">Prijs</th>
							<th class="col-md-1">&nbsp;</th>
						</tr>
					</thead>

					<tbody>
						@foreach (BlancRow::where('project_id','=', $project->id)->get() as $row)
						<tr data-id="{{ $row->id }}" >
							<td class="col-md-6"><input name="name" id="name" type="text" value="{{ $row->description }}" class="form-control-sm-text dsave newrow" /></td>
							<td class="col-md-1"><input name="rate" id="name" type="text" value="{{ number_format($row->rate, 2,",",".") }}" class="form-control-sm-number dsave" /></td>
							<td class="col-md-1"><input name="amount" id="name" type="text" value="{{ number_format($row->amount, 2,",",".") }}" class="form-control-sm-number dsave" /></td>
							<td class="col-md-1">
							@if ($project->tax_reverse)
								<span>0%</span>
							@else
								<select name="tax" id="type" class="dsave form-control-sm-text">
									@foreach (Tax::orderBy('tax_rate', 'desc')->get() as $tax)
									<?php
									if ($tax->id == 1)
										continue;
									?>
									<option value="{{ $tax->id }}">{{ $tax->tax_rate }}%</option>
									@endforeach
								</select>
							@endif
							</td>
							<td class="col-md-1"><span class="total-ex-tax">{{ '&euro; '.number_format($row->rate*$row->amount, 2,",",".") }}</span></td>
							<td class="col-md-1 text-right">
								<button class="btn btn-danger btn-xs sdeleterow fa fa-times"></button>
							</td>
						</tr>
						@endforeach
						<tr>
							<td class="col-md-6"><input name="name" id="name" type="text" class="form-control-sm-text dsave newrow" /></td>
							<td class="col-md-1"><input name="rate" id="name" type="text" class="form-control-sm-number dsave" /></td>
							<td class="col-md-1"><input name="amount" id="name" type="text" class="form-control-sm-number dsave" /></td>
							<td class="col-md-1">
							@if ($project->tax_reverse)
								<span>0%</span>
							@else
								<select name="tax" id="type" class="dsave form-control-sm-text">
									@foreach (Tax::orderBy('tax_rate', 'desc')->get() as $tax)
									<?php
									if ($tax->id == 1)
										continue;
									?>
									<option value="{{ $tax->id }}">{{ $tax->tax_rate }}%</option>
									@endforeach
								</select>
							@endif
							</td>
							<td class="col-md-1"><span class="total-ex-tax"></span></td>
							<td class="col-md-1 text-right">
								<button class="btn btn-danger btn-xs sdeleterow fa fa-times"></button>
							</td>
						</tr>
					</tbody>
					<tbody>
						<tr>
							<td class="col-md-6"><strong>Totaal</strong></td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1">&nbsp;</td>
							<td class="col-md-1"><strong>{{-- '&euro; '.number_format(CalculationRegister::calcMaterialTotal($activity->id, $profit_mat), 2, ",",".") --}}</span></td>
							<td class="col-md-1">&nbsp;</td>
						</tr>
					</tbody>
				</table>


			</div>


		</div>

	</section>

</div>

@stop

<?php } ?>
