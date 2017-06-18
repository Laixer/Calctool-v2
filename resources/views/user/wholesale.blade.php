<?php

use \BynqIO\Dynq\Models\Wholesale;
use \BynqIO\Dynq\Models\WholesaleType;
?>

@extends('layout.master')

@section('title', 'Leveranciers')

@section('content')
<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			<div>
				<ol class="breadcrumb">
					<li><a href="/">Dashboard</a></li>
					<li><a href="/purchase">Inkoopfacturen</a></li>
					<li>Leveranciers</li>
				</ol>
				<div>
					<br>

					<h2><strong>Leveranciers</strong></h2>

					<div class="tabs nomargin-top">

						<ul class="nav nav-tabs">
							<li class="active">
								<a href="#wmywholesale" data-toggle="tab"><i class="fa fa-wrench"></i> Mijn leverancies</a>
							</li>
							<li>
								<a href="#wholesalers" data-toggle="tab"><i class="fa fa-cart-arrow-down"></i> Alle leverancies</a>
							</li>
						</ul>

						<div class="tab-content">
							<div id="wmywholesale" class="tab-pane active">
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
										if (!Wholesale::where('user_id','=', Auth::user()->id)->where('active',true)->count('id')) {
											echo '<tr><td colspan="6" style="text-align: center;">Er zijn nog geen leveranciers</td></tr>';
										}
										foreach (Wholesale::where('user_id','=', Auth::user()->id)->where('active',true)->orderBy('created_at', 'desc')->get() as $wholesale) {
											?>
											<tr>
												<td class="col-md-4"><a href="{{ 'wholesale-'.$wholesale->id.'/edit'}}">{{ $wholesale->company_name }}</td>
												<td class="col-md-2">{{ ucwords(WholesaleType::find($wholesale->type_id)->type_name) }}</td>
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
								<div id="wholesalers" class="tab-pane">
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
											foreach (Wholesale::whereNull('user_id')->orderBy('created_at', 'desc')->get() as $wholesale) {
												?>
												<tr>
													<td class="col-md-4"><a href="{{ 'wholesale-'.$wholesale->id.'/show'}}">{{ $wholesale->company_name }}</td>
													<td class="col-md-2">{{ ucwords(WholesaleType::find($wholesale->type_id)->type_name) }}</td>
													<td class="col-md-2">{{ $wholesale->phone }}</td>
													<td class="col-md-2">{{ $wholesale->email }}</td>
													<td class="col-md-2">{{ $wholesale->address_city }}</td>
												</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>

					</section>

				</div>
@stop
