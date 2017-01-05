<?php
use \Calctool\Models\ProductGroup;
use \Calctool\Models\ProductCategory;
use \Calctool\Models\ProductSubCategory;
use \Calctool\Models\Supplier;
use \Calctool\Models\Product;
use \Calctool\Models\Element;
use \Calctool\Models\Tax;
use \Calctool\Models\FavoriteActivity;
use \Calctool\Models\FavoriteMaterial;
use \Calctool\Models\FavoriteLabor;
use \Calctool\Models\FavoriteEquipment;
use \Calctool\Calculus\CalculationRegister;
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
	$('#tab-favorite-activity').click(function(e){
		sessionStorage.toggleTabMat{{Auth::user()->id}} = 'favorite-activity';
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

		@if (Session::has('success'))
		<div class="alert alert-success">
			<i class="fa fa-check-circle"></i>
			<strong>{{ Session::get('success') }}</strong>
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
					<!-- <li id="tab-element">
						<a href="#element" data-toggle="tab"><i class="fa fa-th-list"></i> Elementen</a>
					</li> -->
					<li id="tab-favorite">
						<a href="#favorite" data-toggle="tab"><i class="fa fa-star"></i> Favorieten producten</a>
					</li>
					<li id="tab-favorite-activity">
						<a href="#favorite-activity" data-toggle="tab"><i class="fa fa-star"></i> Favorieten werkzaamheden</a>
					</li>
				</ul>

				<div class="tab-content">
					<div id="supplier" class="tab-pane">

						<div class="form-group input-group input-group-lg">
						
							<input type="text" id="search" value="" class="form-control" placeholder="Zoek product">

							<span class="input-group-btn">
						        <select id="group2" class="btn getsub" style="background-color: #E5E7E9; color:#000; border-radius:0px;">
							        <option value="0" selected>Selecteer categorie</option>
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
						        <option value="0" selected>Selecteer subcategorie</option>
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
							<!-- <div class="pull-right">
					            <form id="upload-csv" action="material/upload" method="post" enctype="multipart/form-data">
					            {!! csrf_field() !!}
						            <label class="btn btn-primary btn-file">
									    CSV laden <input type="file" name="csvfile" id="btn-load-csv" style="display: none;">
									</label>
								</form>
							</div> -->

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
								@foreach (Product::where('supplier_id','=', $mysupplier->id)->limit(50)->get() as $product)
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
						</table>
					</div>

					<div id="favorite-activity" class="tab-pane">
						<div class="row">
							<div class="col-md-6"><h4>Favorieten Werkzaamheden</h4></div>
						</div>


							<?php
							$activity_total = 0;
							foreach(FavoriteActivity::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get() as $activity) {
							?>
							<div id="toggle-activity-{{ $activity->id }}" class="toggle toggle-activity">
								<label><span>{{ $activity->activity_name }}</span></label>
								<div class="toggle-content">
									<div class="row">
										<div class="col-md-12 text-right">
											<button id="pop-{{ $activity->id }}" data-id="{{ $activity->id }}" data-note="{{ $activity->note }}" data-toggle="modal" data-target="#descModal" class="btn btn-default btn-xs notemod">Omschrijving</button>
											<div class="btn-group" role="group">
											  <button type="button" class="btn btn-primary dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Werkzaamheid&nbsp;&nbsp;<span class="caret"></span></button>
											  <ul class="dropdown-menu">
											    <li><a href="#" data-id="{{ $activity->id }}" data-name="{{ $activity->activity_name }}" data-toggle="modal" data-target="#nameChangeModal" class="changename">Naam wijzigen</a></li>
											    <li><a href="/favorite/{{ $activity->id }}/delete">Verwijderen</a></li>
											  </ul>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-md-2"><h4>Arbeid</h4></div>
										<div class="col-md-9"></div>
									</div>

									<table class="table table-striped" data-id="{{ $activity->id }}">
										<thead>
											<tr>
												<th class="col-md-5">Omschrijving</th>
												<th class="col-md-1">&nbsp;</th>
												<th class="col-md-1">Uurtarief</th>
												<th class="col-md-1">Aantal</th>
												<th class="col-md-1">&nbsp;</th>
												<th class="col-md-1">Prijs</th>
												<th class="col-md-1">&nbsp;</th>
											</tr>
										</thead>

										<tbody>
											<tr data-id="{{ FavoriteLabor::where('activity_id',$activity->id)->first()['id'] }}">
												<td class="col-md-5">Arbeidsuren</td>
												<td class="col-md-1">&nbsp;</td>
												<td class="col-md-1"><span class="rate">{{ number_format(FavoriteLabor::where('activity_id',$activity->id)->first()['rate'], 2,",",".") }}</span></td>
												<td class="col-md-1"><input data-id="{{ $activity->id }}" name="amount" type="text" value="{{ number_format(FavoriteLabor::where('activity_id','=', $activity->id)->first()['amount'], 2, ",",".") }}" class="form-control-sm-number labor-amount lsave" /></td>
												<td class="col-md-1">&nbsp;</td>
												<td class="col-md-1"><span class="total-ex-tax">{{ '&euro; '.number_format(CalculationRegister::calcLaborTotal(FavoriteLabor::where('activity_id',$activity->id)->first()['rate'], FavoriteLabor::where('activity_id',$activity->id)->first()['amount']), 2, ",",".") }}</span></td>
												<td class="col-md-1 text-right"><button class="btn btn-danger ldeleterow btn-xs fa fa-times"></button></td>
											</tr>
										</tbody>
									</table>

									<div class="row">
										<div class="col-md-2"><h4>Materiaal</h4></div>
										<div class="col-md-9"></div>
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
											@foreach (FavoriteMaterial::where('activity_id', $activity->id)->get() as $material)
											<tr data-id="{{ $material->id }}">
												<td class="col-md-5"><input name="name" id="name" type="text" value="{{ $material->material_name }}" class="form-control-sm-text dsave newrow" /></td>
												<td class="col-md-1"><input name="unit" id="name" type="text" value="{{ $material->unit }}" class="form-control-sm-text dsave" /></td>
												<td class="col-md-1"><input name="rate" id="name" type="text" value="{{ number_format($material->rate, 2,",",".") }}" class="form-control-sm-number dsave" /></td>
												<td class="col-md-1"><input name="amount" id="name" type="text" value="{{ number_format($material->amount, 2,",",".") }}" class="form-control-sm-number dsave" /></td>
												<td class="col-md-1"><span class="total-ex-tax">{{ '&euro; '.number_format($material->rate * $material->amount, 2,",",".") }}</span></td>
												<td class="col-md-1"><span class="total-incl-tax">{{ '&euro; '.number_format($material->rate * $material->amount, 2,",",".") }}</span></td>
												<td class="col-md-1 text-right"">
													<button class="fa fa-book" data-toggle="modal" data-target="#myModal"></button>
													<button class="fa fa-star" data-toggle="modal" data-target="#myModal2"></button>
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
												<td class="col-md-1 text-right">
													<button class="fa fa-book" data-toggle="modal" data-target="#myModal"></button>
													<button class="fa fa-star" data-toggle="modal" data-target="#myModal2"></button>
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
												<td class="col-md-1"><strong class="mat_subtotal">{{ '&euro; '.number_format(CalculationRegister::calcMaterialTotal($activity->id, 0), 2, ",",".") }}</span></td>
												<td class="col-md-1"><strong class="mat_subtotal_profit">{{ '&euro; '.number_format(CalculationRegister::calcMaterialTotalProfit($activity->id, 0), 2, ",",".") }}</span></td>
												<td class="col-md-1">&nbsp;</td>
											</tr>
										</tbody>
									</table>
									
									<div class="row">
										<div class="col-md-2"><h4>Overig</h4></div>
										<div class="col-md-9"></div>
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
											@foreach (FavoriteEquipment::where('activity_id','=', $activity->id)->get() as $equipment)
											<tr data-id="{{ $equipment->id }}">
												<td class="col-md-5"><input name="name" id="name" type="text" value="{{ $equipment->equipment_name }}" class="form-control-sm-text esave newrow" /></td>
												<td class="col-md-1"><input name="unit" id="name" type="text" value="{{ $equipment->unit }}" class="form-control-sm-text esave" /></td>
												<td class="col-md-1"><input name="rate" id="name" type="text" value="{{ number_format($equipment->rate, 2,",",".") }}" class="form-control-sm-number esave" /></td>
												<td class="col-md-1"><input name="amount" id="name" type="text" value="{{ number_format($equipment->amount, 2,",",".") }}" class="form-control-sm-number esave" /></td>
												<td class="col-md-1"><span class="total-ex-tax">{{ '&euro; '.number_format($equipment->rate * $equipment->amount, 2,",",".") }}</span></td>
												<td class="col-md-1"><span class="total-incl-tax">{{ '&euro; '.number_format($equipment->rate * $equipment->amount, 2,",",".") }}</span></td>
												<td class="col-md-1 text-right">
													<button class="fa fa-book" data-toggle="modal" data-target="#myModal"></button>
													<button class="fa fa-star" data-toggle="modal" data-target="#myModal2"></button>
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
												<td class="col-md-1 text-right">
													<button class="fa fa-book" data-toggle="modal" data-target="#myModal"></button>
													<button class="fa fa-star" data-toggle="modal" data-target="#myModal2"></button>
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
												<td class="col-md-1"><strong class="equip_subtotal">{{ '&euro; '.number_format(CalculationRegister::calcEquipmentTotal($activity->id, 0), 2, ",",".") }}</span></td>
												<td class="col-md-1"><strong class="equip_subtotal_profit">{{ '&euro; '.number_format(CalculationRegister::calcEquipmentTotalProfit($activity->id, 0), 2, ",",".") }}</span></td>
												<td class="col-md-1">&nbsp;</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<?php } ?>


						@if(0)
						<table class="table table-striped">
							<thead>
								<tr>
									<th class="col-md-5">Omschrijving</th>
									<th class="col-md-2">Datum</th>
									<th class="col-md-1">BTW Arbeid</th>
									<th class="col-md-2">BTW Materiaal</th>
									<th class="col-md-1">BTW Overig</th>
									<th class="col-md-1"></th>
								</tr>
							</thead>
							<tbody>
								<?php $i=0; ?>
								@foreach(FavoriteActivity::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get() as $activity)
								<?php $i++; ?>
								<tr>
									<td class="col-md-5">{{ $activity->activity_name }}</td>
									<td class="col-md-2"><?php echo date('d-m-Y', strtotime($activity->created_at)); ?></td>
									<td class="col-md-1">{{ Tax::find($activity->tax_labor_id)->tax_rate }}%</td>
									<td class="col-md-2">{{ Tax::find($activity->tax_material_id)->tax_rate }}%</td>
									<td class="col-md-1">{{ Tax::find($activity->tax_equipment_id)->tax_rate }}%</td>
									<td class="col-md-1"><a href="/favorite/{{ $activity->id }}/delete" class="btn btn-danger btn-xs"> Verwijderen</a></td>
								</tr>
								@endforeach
								@if (!$i)
								<tr>
									<td colspan="6" style="text-align: center;">Er zijn nog geen favorieten werkzaamheden</td>
								</tr>
								@endif
							</tbody>
						</table>
						@endif
					</div>

				</div>

			</div>

		</div>

	</section>

</div>
@stop
