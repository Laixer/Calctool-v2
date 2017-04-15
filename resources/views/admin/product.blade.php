<?php
use \BynqIO\CalculatieTool\Models\Wholesale;
use \BynqIO\CalculatieTool\Models\ProductGroup;
use \BynqIO\CalculatieTool\Models\ProductCategory;
use \BynqIO\CalculatieTool\Models\ProductSubCategory;
?>

@extends('layout.master')

@section('content')

@section('title', 'Producten')

?>
<script type="text/javascript">
$(document).ready(function() {
	$('#btn-load-csv').change(function() {
		$('#upload-csv').submit();
	});
});
</script>
<div id="wrapper">

	<section class="container">
		<div class="col-md-12">

			<div>
			<ol class="breadcrumb">
			  <li><a href="/">Home</a></li>
			  <li><a href="/admin">Admin CP</a></li>
			  <li class="active">Producten</li>
			</ol>
			<div>
			<br />

			@if (Session::has('success'))
			<div class="alert alert-success">
				<i class="fa fa-check-circle"></i>
				<strong>{!! Session::get('success') !!}</strong>
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

			<div class="pull-right">
				<div class="pull-right">
		            <form id="upload-csv" action="product/upload" method="post" enctype="multipart/form-data">
		            {!! csrf_field() !!}
			            <label class="btn btn-primary btn-file">
						    CSV laden <input type="file" name="csvfile" id="btn-load-csv" style="display: none;">
						</label>
					</form>
				</div>
			</div>

			<h2><strong>Producten</strong></h2>

			<div class="white-row">

				<form action="/admin/product/emptylist" method="post">
				{!! csrf_field() !!}
					<div class="row">
						<div class="col-md-10">
							<select name="supplier" class="form-control" style="background-color: #E5E7E9; color:#000;">
								@foreach (Wholesale::all() as $wholesale)
								<option {{ $wholesale->company_name=='Bouwmaat NL' ? 'selected' : '' }} value="{{ $wholesale->id }}">{{ $wholesale->company_name }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-md-2">
							<button class="btn btn-danger btn dsave">Lijst legen</button>
						</div>
					</div>
				</form>

				<br />

				<form action="/admin/product/delete_group" method="post">
				{!! csrf_field() !!}
					<div class="row">
						<div class="col-md-10">
							<select name="group" class="form-control" style="background-color: #E5E7E9; color:#000;">
								@foreach (ProductGroup::all() as $group)
								<option data-name="group" value="{{ $group->id }}">{{ $group->group_name }}</option>
								@foreach (ProductCategory::where('group_id', $group->id)->get() as $cat)
								<option data-name="cat" value="{{ $cat->id }}"> - {{ $cat->category_name }}</option>
								@endforeach
								@endforeach
							</select>
						</div>
						<div class="col-md-2">
							<button class="btn btn-danger btn dsave">Verwijderen</button>
						</div>
					</div>
				</form>

				<br />

				<form action="/admin/product/delete_subcat" method="post">
				{!! csrf_field() !!}
					<div class="row">
						<div class="col-md-10">
							<select name="subcat" class="form-control" style="background-color: #E5E7E9; color:#000;">
								@foreach (ProductSubCategory::all() as $subcat)
								<option value="{{ $subcat->id }}">{{ $subcat->sub_category_name }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-md-2">
							<button class="btn btn-danger btn dsave">Verwijderen</button>
						</div>
					</div>
				</form>

			</div>

		</div>

	</section>

</div>
@stop
