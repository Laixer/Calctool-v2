<?php
use \Calctool\Models\ProductGroup;
use \Calctool\Models\ProductCategory;
use \Calctool\Models\ProductSubCategory;
use \Calctool\Models\Supplier;
use \Calctool\Models\Product;
use \Calctool\Models\Element;
?>

@extends('layout.master')

@section('title', 'Materialen')

@section('content')
<script type="text/javascript">
$(document).ready(function() {
	$('#tab-supplier').click(function(e){
		sessionStorage.toggleTabMat{{Auth::user()->id}} = 'supplier';
	});
	$('#tab-material').click(function(e){
		sessionStorage.toggleTabMat{{Auth::user()->id}} = 'material';
	});
	$('#tab-element').click(function(e){
		sessionStorage.toggleTabMat{{Auth::user()->id}} = 'element';
	});
	$('#tab-favorite').click(function(e){
		sessionStorage.toggleTabMat{{Auth::user()->id}} = 'favorite';
	});
	if (sessionStorage.toggleTabMat{{Auth::user()->id}}){
		$toggleOpenTab = sessionStorage.toggleTabMat{{Auth::user()->id}};
		$('#tab-'+$toggleOpenTab).addClass('active');
		$('#'+$toggleOpenTab).addClass('active');
	} else {
		sessionStorage.toggleTabMat{{Auth::user()->id}} = 'supplier';
		$('#tab-supplier').addClass('active');
		$('#supplier').addClass('active');
	}
	$('#elmModal').on('hidden.bs.modal', function() {
		$name = $('#name').val();
		$desc = $('#desc').val();
		$.post("/material/element/new", {name: $name, desc: $desc}, function(data) {
			location.reload();
		});
	});
	$req = false;
	$("#search").keyup(function() {
		$val = $(this).val();
		if ($val.length > 2 && !$req) {
			$req = true;
			$.post("/material/search", {query:$val}, function(data) {
				if (data) {
					$('#alllist tbody tr').remove();
					$.each(data, function(i, item) {
						$('#alllist tbody').append('<tr data-id="'+item.id+'"><td>'+item.description+'</td><td>'+item.unit+'</td><td>'+item.price+'</td><td>'+item.tprice+'</td><td><a href="javascript:void(0);" class="toggle-fav"><i style="color:'+(item.favorite ? '#FFD600' : '#000')+';" class="fa '+(item.favorite ? 'fa-star' : 'fa-star-o')+'"></i></a></td></tr>');
					});
					$req = false;
				}
			});
		}
	});
	$('#group').change(function(){
		$val = $(this).val();
		$.post("/material/search", {group:$val}, function(data) {
			if (data) {
				$('#alllist tbody tr').remove();
				$.each(data, function(i, item) {
					$('#alllist tbody').append('<tr data-id="'+item.id+'"><td>'+item.description+'</td><td>'+item.unit+'</td><td>'+item.price+'</td><td>'+item.tprice+'</td><td><a href="javascript:void(0);" class="toggle-fav"><i style="color:'+(item.favorite ? '#FFD600' : '#000')+';" class="fa '+(item.favorite ? 'fa-star' : 'fa-star-o')+'"></i></a></td></tr>');
				});
				$req = false;
			}
		});
	});
	$("body").on("click", ".toggle-fav", function(){
		$curr = $(this);
		$matid = $curr.closest('tr').attr('data-id');
		$.post("/material/favorite", {matid:$matid}, function(data) {
			var json = data;
			if (json.success) {
				$curr.find('i').toggleClass('fa-star-o fa-star');
				if ($curr.find('i').css('color') == 'rgb(0, 0, 0)') {
					$curr.find('i').css('color','#FFD600');
				} else {
					$curr.find('i').css('color','#000');
				}
			}
		});
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
				var json = data;
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
				var json = data;
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
	$("body").on("click", ".fdeleterow", function(){
		var $curThis = $(this);
		if($curThis.closest("tr").attr("data-id"))
			$.post("/material/favorite", {matid: $curThis.closest("tr").attr("data-id")}, function(){
				$curThis.closest("tr").hide("slow");
			}).fail(function(e) { console.log(e); });
	});
	$('#btn-load-csv').change(function() {
		$('#upload-csv').submit();
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
					$('#alllist tbody tr').remove();
					$.each(data, function(i, item) {
						$('#alllist tbody').append('<tr data-id="'+item.id+'"><td>'+item.description+'</td><td>'+item.unit+'</td><td>'+item.price+'</td><td>'+item.tprice+'</td><td><a href="javascript:void(0);" class="toggle-fav"><i style="color:'+(item.favorite ? '#FFD600' : '#000')+';" class="fa '+(item.favorite ? 'fa-star' : 'fa-star-o')+'"></i></a></td></tr>');
					});
					$req = false;
				}
			});

		});

	});
});
</script>
<div id="wrapper">

	<div class="modal fade" id="elmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel2">Nieuw element</h4>
				</div>

				<div class="modal-body">
					<div class="form-horizontal">
						<div class="form-group">
							<div class="col-md-4">
								<label>Naam</label>
							</div>
							<div class="col-md-8">
								<input name="name" id="name" type="text" class="form-control" />
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-4">
								<label>Opmerking</label>
							</div>
							<div class="col-md-8">
								<input name="desc" id="desc" type="text" class="form-control" />
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-default" data-dismiss="modal">Opslaan</button>
				</div>
			</div>
		</div>
	</div>

	<section class="container">

		@if(Session::get('success'))
		<div class="alert alert-success">
			<i class="fa fa-check-circle"></i>
			<strong>Opgeslagen</strong>
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

		<div class="col-md-12">

			<div>
				<ol class="breadcrumb">
				  <li><a href="/">Home</a></li>
				  <li class="active">Producten</li>
				</ol>
			<div>
			<br>

			<!-- <p class="alert alert-warning">De prijslijsten bevinden zich nog in BETA fase.</p> -->

			<h2><strong>Prijslijsten</strong></h2>

			<div class="tabs nomargin-top">

				<ul class="nav nav-tabs">
					<li id="tab-supplier">
						<a href="#supplier" data-toggle="tab"><i class="fa fa-cart-arrow-down"></i> Producten</a>
					</li>
					<li id="tab-material">
						<a href="#material" data-toggle="tab"><i class="fa fa-wrench"></i> Mijn producten</a>
					</li>
<!-- 					<li id="tab-element">
						<a href="#element" data-toggle="tab"><i class="fa fa-th-list"></i> Elementen</a>
					</li> -->
					<li id="tab-favorite">
						<a href="#favorite" data-toggle="tab"><i class="fa fa-star"></i> Favorieten</a>
					</li>
				</ul>

				<div class="tab-content">
					<div id="supplier" class="tab-pane">

						<div class="form-group input-group input-group-lg">
						
							<input type="text" id="search" value="" class="form-control" placeholder="Zoek product">

							<span class="input-group-btn">
						        <select id="group2" class="btn getsub" style="background-color: #E5E7E9; color:#000">
							        <option value="0" selected>Selecteer</option>
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
						        <option value="0" selected>Selecteer</option>
						        @foreach (ProductSubCategory::all() as $subcat)
						          <option value="{{ $subcat->id }}">{{ $subcat->sub_category_name }}</option>
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
										<th></th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>

					<div id="material" class="tab-pane">
							<div class="pull-right">
					            <form id="upload-csv" action="material/upload" method="post" enctype="multipart/form-data">
					            {!! csrf_field() !!}
						            <label class="btn btn-primary btn-file">
									    CSV laden <input type="file" name="csvfile" id="btn-load-csv" style="display: none;">
									</label>
								</form>
							</div>

						<div class="row">
							<div class="col-md-2"><h4>Mijn producten</h4></div>
						</div>

						<table class="table table-striped">
							<thead>
								<tr>
									<th class="col-md-5">Omschrijving</th>
									<th class="col-md-1">Eenheid</th>
									<th class="col-md-1">&euro; / Eenheid</th>
									<th class="col-md-2">Productgroep</th>
									<th class="col-md-1">&nbsp;</th>
									<th class="col-md-1">&nbsp;</th>
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
								        @foreach (ProductSubCategory::all() as $subcat)
								        	<option {{ ($product->group_id == $subcat->id ? 'selected' : '') }} value="{{ $subcat->id }}">{{ $subcat->sub_category_name }}</option>
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
								        @foreach (ProductSubCategory::all() as $subcat)
								        	<option value="{{ $subcat->id }}">{{ $subcat->sub_category_name }}</option>
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
						<h4>Elementen</h4>
						<table class="table table-striped">
							<thead>	
								<tr>
									<th class="col-md-3">Naam</th>
									<th class="col-md-2">Onderdelen</th>
									<th class="col-md-4">Omschrijving</th>
									<th class="col-md-1"></th>
									<th class="col-md-2"></th>
								</tr>
							</thead>

							<tbody>
							@if (!Element::where('user_id','=', Auth::id())->count('id'))
							<tr>
								<td colspan="6" style="text-align: center;">Er zijn geen elementen</td>
							</tr>
							@endif
							@foreach (Element::where('user_id','=', Auth::id())->get() as $element)
								<tr>
									<td class="col-md-3"><a href="/material/element-{{ $element->id }}">{{ $element->name }}</a></td>
									<td class="col-md-2">{{--  --}}</td>
									<td class="col-md-4">{{ $element->description }}</td>
									<td class="col-md-1"></td>
									<td class="col-md-2 text-right"><button class="btn btn-danger btn-xs sdeleterow fa fa-times"></button></td>
								</tr>
							@endforeach
							</tbody>
						</table>
						<div class="row">
							<div class="col-md-12">
								<a href="#" data-toggle="modal" data-target="#elmModal" id="newelm" class="btn btn-primary"><i class="fa fa-pencil"></i> Nieuw element</a>
							</div>
						</div>

					</div>

					<div id="favorite" class="tab-pane">
						<div class="row">
							<div class="col-md-2"><h4>Favorieten</h4></div>
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
								@foreach (Auth::user()->productFavorite()->get() as $product)
								<tr data-id="{{ $product->id }}">
									<td class="col-md-5">{{ $product->description }}</td>
									<td class="col-md-1">{{ $product->unit }}</td>
									<td class="col-md-1">{{ number_format($product->price, 2,",",".") }}</td>
									<td class="col-md-1">{{ ProductSubCategory::find($product->group_id)->sub_category_name }}</td>
									<td class="col-md-1"><span class="total-ex-tax"></span></td>
									<td class="col-md-1"><span class="total-incl-tax"></span></td>
									<td class="col-md-2 text-right">
										<button class="btn btn-danger btn-xs fdeleterow fa fa-times"></button>
									</td>
								</tr>
								@endforeach
							</tbody>
							<tbody>
								<tr></tr>
							</tbody>
						</table>


					</div>

				</div>

			</div>

		</div>

	</section>

</div>
@stop
