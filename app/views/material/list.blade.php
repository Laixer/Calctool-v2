@extends('layout.master')

@section('content')
<script type="text/javascript">
$(document).ready(function() {
	$req = false;
	$("#search").keyup(function() {
		$val = $(this).val();
		if ($val.length > 3 && !$req) {
			$group = $('#group').val();
			$req = true;
			$.post("/material/search", {query:$val,group:$group}, function(data) {
				if (data) {
					$('#alllist tbody tr').remove();
					$.each(data, function(i, item) {
						$('#alllist tbody').append('<tr><td>'+item.description+'</td><td>'+item.unit+'</td><td>'+item.price+'</td><td>'+item.tprice+'</td></tr>');
					});
					$req = false;
				}
			});
		}
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
			$.post("/material/updatematerial", {
				id: $curThis.closest("tr").attr("data-id"),
				name: $curThis.closest("tr").find("input[name='name']").val(),
				unit: $curThis.closest("tr").find("input[name='unit']").val(),
				rate: $curThis.closest("tr").find("input[name='rate']").val(),
				group: $curThis.closest("tr").find("select[name='ngroup']").val()
			}, function(data){
				var json = $.parseJSON(data);
				$curThis.closest("tr").find("input").removeClass("error-input");
				if (json.success) {
					$curThis.closest("tr").attr("data-id", json.id);
				} else {
					$.each(json.message, function(i, item) {
						if(json.message['name'])
							$curThis.closest("tr").find("input[name='name']").addClass("error-input");
						if(json.message['unit'])
							$curThis.closest("tr").find("input[name='unit']").addClass("error-input");
						if(json.message['rate'])
							$curThis.closest("tr").find("input[name='rate']").addClass("error-input");
						if(json.message['group'])
							$curThis.closest("tr").find("input[select='ngroup']").addClass("error-input");
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
		$curThis.closest("tr").find("select").each(function(){
			if($(this).val()=='0')
				flag = false;
		});
		if(flag){
			$.post("/material/newmaterial", {
				name: $curThis.closest("tr").find("input[name='name']").val(),
				unit: $curThis.closest("tr").find("input[name='unit']").val(),
				rate: $curThis.closest("tr").find("input[name='rate']").val(),
				group: $curThis.closest("tr").find("select[name='ngroup']").val()
			}, function(data){
				var json = $.parseJSON(data);
				$curThis.closest("tr").find("input").removeClass("error-input");
				if (json.success) {
					$curThis.closest("tr").attr("data-id", json.id);
				} else {
					$.each(json.message, function(i, item) {
						if(json.message['name'])
							$curThis.closest("tr").find("input[name='name']").addClass("error-input");
						if(json.message['unit'])
							$curThis.closest("tr").find("input[name='unit']").addClass("error-input");
						if(json.message['rate'])
							$curThis.closest("tr").find("input[name='rate']").addClass("error-input");
						if(json.message['group'])
							$curThis.closest("tr").find("input[select='ngroup']").addClass("error-input");
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
			$.post("/material/deletematerial", {id: $curThis.closest("tr").attr("data-id")}, function(){
				$curThis.closest("tr").hide("slow");
			}).fail(function(e) { console.log(e); });
	});
});
</script>
<div id="wrapper">

	<section class="container">

		<div class="col-md-12">


			<h2><strong>Materialenlijst</strong></h2>

			<div class="tabs nomargin-top">

				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#supplier" data-toggle="tab"><i class="fa fa-cart-arrow-down"></i> Alle leverancies</a>
					</li>
					<li>
						<a href="#material" data-toggle="tab"><i class="fa fa-wrench"></i> Mijn materiaal</a>
					</li>
					@if (0)
					<li>
						<a href="#element" data-toggle="tab"><i class="fa fa-th-list"></i> Elementen</a>
					</li>
					<li>
						<a href="#favorite" data-toggle="tab"><i class="fa fa-star-o"></i> Favorieten</a>
					</li>
					@endif
				</ul>

				<div class="tab-content">
					<div id="supplier" class="tab-pane active">

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
							<table id="alllist" class="table table-striped">
								<thead>
									<tr>
										<th>Omschrijving</th>
										<th>Eenheid</th>
										<th>&euro; / Eenheid</th>
										<th>Totaalprijs</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>

					<div id="material" class="tab-pane">
						<div class="row">
							<div class="col-md-2"><h4>Mijn materiaal</h4></div>
						</div>

						<table class="table table-striped">
							<thead>
								<tr>
									<th class="col-md-5">Omschrijving</th>
									<th class="col-md-1">Eenheid</th>
									<th class="col-md-2">&euro; / Eenheid</th>
									<th class="col-md-3">Categorie</th>
									<th class="col-md-1">&nbsp;</th>
								</tr>
							</thead>

							<tbody>
								<?php
									$mysupplier = Supplier::where('user_id','=',Auth::id())->first();
									if ($mysupplier) {
								?>
								@foreach (Product::where('supplier_id','=', $mysupplier->id)->get() as $product)
								<tr data-id="{{ $product->id }}">
									<td class="col-md-5"><input name="name" type="text" value="{{ $product->description }}" class="form-control-sm-text dsave newrow" /></td>
									<td class="col-md-1"><input name="unit" type="text" value="{{ $product->unit }}" class="form-control-sm-text dsave" /></td>
									<td class="col-md-1"><input name="rate" type="text" value="{{ number_format($product->price, 2,",",".") }}" class="form-control-sm-number dsave" /></td>
									<td class="col-md-1">
										<select name="ngroup" class="form-control-sm-text pointer dsave">
								        @foreach (SubGroup::all() as $group)
								        	<option {{ ($product->group_id == $group->id ? 'selected' : '') }} value="{{ $group->id }}">{{ $group->group_type }}</option>
								        @endforeach
										</select>
									</td>
									<td class="col-md-1"><span class="total-ex-tax"></span></td>
									<td class="col-md-1"><span class="total-incl-tax"></span></td>
									<td class="col-md-2 text-right">
										<button class="btn btn-danger btn-xs sdeleterow fa fa-times"></button>
									</td>
								</tr>
								@endforeach
								<?php } ?>
								<tr>
									<td class="col-md-5"><input name="name" type="text" class="form-control-sm-text dsave newrow"></td>
									<td class="col-md-1"><input name="unit" type="text" class="form-control-sm-text dsave"></td>
									<td class="col-md-1"><input name="rate" type="text" class="form-control-sm-number dsave"></td>
									<td class="col-md-1">
										<select name="ngroup" class="form-control-sm-text pointer dsave">
										<option value="0">Selecteer</option>
								        @foreach (SubGroup::all() as $group)
								        	<option value="{{ $group->id }}">{{ $group->group_type }}</option>
								        @endforeach
										</select>
									</td>
									<td class="col-md-1"><span class="total-ex-tax"></span></td>
									<td class="col-md-1"><span class="total-incl-tax"></span></td>
									<td class="col-md-2 text-right">
										<button class="btn btn-danger btn-xs sdeleterow fa fa-times"></button>
									</td>
								</tr>
							</tbody>
							<tbody>
								<tr></tr>
							</tbody>
						</table>


					</div>

					<div id="element" class="tab-pane">
					</div>
				</div>

			</div>

		</div>

	</section>

</div>
@stop
