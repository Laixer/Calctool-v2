<?php

use \Calctool\Models\Relation;
use \Calctool\Models\Contact;
use \Calctool\Models\Invoice;
use \Calctool\Models\Resource;
use \Calctool\Models\Project;
use \Calctool\Models\Tax;
use \Calctool\Models\FavoriteActivity;

$relation = Relation::find(Auth::user()->self_id);
$user = Auth::user();
?>

@extends('layout.master')

@section('title', 'Mijn bedrijf')

@push('style')
<link media="all" type="text/css" rel="stylesheet" href="/plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css">
@endpush

@push('scripts')
<script src="/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
@endpush

@section('content')
<script type="text/javascript">
$(document).ready(function() {

});
</script>

<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			<div>
				<ol class="breadcrumb">
				  <li><a href="/">Home</a></li>
				  <li class="active">Favorieten</li>
				</ol>
			<div>
			<br>

			@if (Session::has('success'))
			<div class="alert alert-success">
				<i class="fa fa-check-circle"></i>
				<strong>{{ Session::get('success') }}</strong>
			</div>
			@endif

			@if (count($errors) > 0)
			<div class="alert alert-danger">
				<i class="fa fa-frown-o"></i>
				<strong>Fouten in de invoer</strong>
				<ul>
					@foreach ($errors->all() as $error)
					<li><h5 class="nomargin">{{ $error }}</h5></li>
					@endforeach
				</ul>
			</div>
			@endif

			<h2 style="margin: 10px 0 20px 0;"><strong>Favorieten</strong></h2>

				<div class="white-row">
	
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
				</div>

		</div>

	</section>

</div>
@stop
