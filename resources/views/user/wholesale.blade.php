<?php

use \Calctool\Models\Wholesale;
use \Calctool\Models\WholesaleType;
?>

@extends('layout.master')

@section('content')
<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			<div>
			<ol class="breadcrumb">
			  <li><a href="/">Home</a></li>
			  <li>Leveranciers</li>
			</ol>
			<div>
			<br>

			<h2><strong>Leveranciers</strong></h2>

			<div class="white-row">

				<table class="table table-striped">
					<thead>
						<tr>
							<th class="col-md-4">Leverancier</th>
							<th class="col-md-2">Type</th>
							<th class="col-md-2">Telefoon</th>
							<th class="col-md-2">Email</th>
							<th class="col-md-2">Plaats</th>
						</tr>
					</thead>

					<tbody>
					<?php
					$userid = Auth::user()->self_id;
					if(Auth::user()->self_id)
						$userid = Auth::user()->self_id;
					else
						$userid = -1;
					if (!Wholesale::where('user_id','=', Auth::user()->id)->where('id','!=',$userid)->count('id')) {
						echo '<tr><td colspan="6" style="text-align: center;">Er zijn nog geen relaties</td></tr>';
					}
					foreach (Wholesale::where('user_id','=', Auth::user()->id)->where('id','!=',$userid)->orderBy('created_at', 'desc')->get() as $wholesale) {
					?>
						<tr>
							<td class="col-md-4"><a href="{{ 'wholesale-'.$wholesale->id.'/edit'}}">{{ $wholesale->company_name }}</td>
							<td class="col-md-2">{{ WholesaleType::find($wholesale->type_id)->type_name }}</td>
							<td class="col-md-2">{{ $wholesale->phone }}</td>
							<td class="col-md-2">{{ $wholesale->email }}</td>
							<td class="col-md-2">{{ $wholesale->address_city }}</td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
				<div class="row">
					<div class="col-md-12">
						<a href="wholesale/new" class="btn btn-primary"><i class="fa fa-pencil"></i> Nieuwe leverancier</a>
					</div>
				</div>
			</div>
		</div>

	</section>

</div>
@stop
