<?php
use \Calctool\Models\Relation;
use \Calctool\Models\Project;
use \Calctool\Models\RelationKind;
use \Calctool\Models\RelationType;
use \Calctool\Models\Province;
use \Calctool\Models\Country;
use \Calctool\Models\Contact;
use \Calctool\Models\ContactFunction;
use \Calctool\Models\Cashbook;
use \Calctool\Models\BankAccount;
?>

@extends('layout.master')

@section('content')

<script type="text/javascript" src="/js/iban.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('#summernote').summernote({
	        height: $(this).attr("data-height") || 200,
	        toolbar: [
	            ["style", ["bold", "italic", "underline", "strikethrough", "clear"]],
	            ["para", ["ul", "ol", "paragraph"]],
	            ["table", ["table"]],
	            ["media", ["link", "picture", "video"]],
	            ["misc", ["codeview"]]
	        ]
	    })
});

</script>

<div id="wrapper">
	<!--<div class="overlay"></div>-->

		<!-- Sidebar -->
        <!--<nav class="navbar navbar-inverse navbar-fixed-top" id="sidebar-wrapper" role="navigation">
            <ul class="nav sidebar-nav">
                <li class="sidebar-brand">
                    <a href="#">
                       Brand
                    </a>
                </li>
                <li>
                    <a href="#">Home</a>
                </li>
                <li>
                    <a href="#">About</a>
                </li>
                <li>
                    <a href="#">Events</a>
                </li>
                <li>
                    <a href="#">Team</a>
                </li>
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">Works <span class="caret"></span></a>
                  <ul class="dropdown-menu" role="menu">
                    <li class="dropdown-header">Dropdown heading</li>
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li><a href="#">Separated link</a></li>
                    <li><a href="#">One more separated link</a></li>
                  </ul>
                </li>
                <li>
                    <a href="#">Services</a>
                </li>
                <li>
                    <a href="#">Contact</a>
                </li>
                <li>
                    <a href="https://twitter.com/maridlcrmn">Follow me</a>
                </li>
            </ul>
        </nav>-->
        <!-- /#sidebar-wrapper -->

	<div id="shop">
	<section class="container">

		<button type="button" class="hamburger is-closed" data-toggle="offcanvas">
			<span class="hamb-top"></span>
			<span class="hamb-middle"></span>
			<span class="hamb-bottom"></span>
		</button>

		<div class="col-md-12">

			<h2 style="margin: 10px 0 20px 0;"><strong>Apps</strong></h2>
			<div class="row">

				<div class="col-sm-6 col-md-2">
					<div class="item-box item-box-show fixed-box">
						<figure>
							<a class="item-hover" href="javascript:void(0);" ui-href="/apps/notepad">
								<span style="background-color: rgba(0,0,0, 0.2) !important;" class="overlay color2"></span>
								<span class="inner" style="top:40%;">
									<span class="block fa fa-home fsize60"></span>
								</span>
							</a>
							<a style="opacity: 0.4;" href="javascript:void(0);" class="btn btn-primary add_to_cart"><strong> Kladblok</strong></a>

						</figure>
					</div>
				</div>

				<div class="col-sm-6 col-md-2">
					<div class="item-box item-box-show fixed-box">
						<figure>
							<a class="item-hover" href="javascript:void(0);" ui-href="/apps/cashbook">
								<span style="background-color: rgba(0,0,0, 0.2) !important;" class="overlay color2"></span>
								<span class="inner" style="top:40%;">
									<span class="block fa fa-wrench fsize60"></span>
								</span>
							</a>
							<a style="opacity: 0.4;" href="javascript:void(0);" ui-href="/apps/cashbook" class="btn btn-primary add_to_cart"><strong> Kasboek</strong></a>
						</figure>
					</div>
				</div>
			</div>
						
			<!--<div class="row">
				<form action="relation/updatemycompany" method="post">
				{!! csrf_field() !!}

					<h4>Kladblok van mijn bedrijf <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit betreft een persoonlijk kladblok je eigen bedrijf en wordt nergens anders weergegeven." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></h4>

					<div class="row">
						<div class="form-group">
							<div class="col-md-12">
								<textarea name="note" id="summernote" rows="10" class="form-control">{{-- Input::old('note') ? Input::old('note') : ($relation ? $relation->note : '') --}}</textarea>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-12">
							<button class="btn btn-primary"><i class="fa fa-check"></i> Opslaan</button>
						</div>
					</div>
				</form>
			</div>-->
		
		</div>

		@if (0)
		<div class="row">
			<form action="relation/updatemycompany" method="post">
			{!! csrf_field() !!}

				<h4>Kladblok van mijn bedrijf <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit betreft een persoonlijk kladblok je eigen bedrijf en wordt nergens anders weergegeven." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></h4>

				<div class="row">
					<div class="form-group">
						<div class="col-md-12">
							<textarea name="note" id="summernote" rows="10" class="form-control">{{-- Input::old('note') ? Input::old('note') : ($relation ? $relation->note : '') --}}</textarea>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						<button class="btn btn-primary"><i class="fa fa-check"></i> Opslaan</button>
					</div>
				</div>
			</form>
		</div>

		<h4>Rekeningen</h4>
		<div class="row">
			<div class="col-md-3"><strong>Rekening</strong></div>
			<div class="col-md-2"><strong>Saldo</strong></div>
		</div>
		@foreach (BankAccount::where('user_id', Auth::id())->get() as $account)
		<div class="row">
			<div class="col-md-3">{{ $account->account }}</div>
			<div class="col-md-2">&euro;{{ number_format(Cashbook::where('account_id', $account->id)->sum('amount'), 2, ",",".") }}</div>
			<div class="col-md-3"></div>
		</div>
		@endforeach
		<br />
		<h4>Af en bij</h4>
		<table class="table table-striped">
			<thead>
				<tr>
					<th class="col-md-2">Rekening</th>
					<th class="col-md-2">Bedrag</th>
					<th class="col-md-2">Datum</th>
					<th class="col-md-6">Omschrijving</th>
				</tr>
			</thead>

			<tbody>
				@foreach (BankAccount::where('user_id', Auth::id())->get() as $account)
				@foreach (Cashbook::where('account_id', $account->id)->orderBy('payment_date','desc')->get() as $row)
				<tr>
					<td class="col-md-2">{{ $account->account }}</a></td>
					<td class="col-md-2">{{ ($row->amount > 0 ? '+' : '') . number_format($row->amount, 2, ",",".") }}</td>
					<td class="col-md-2">{{ date('d-m-Y', strtotime($row->payment_date)) }}</td>
					<td class="col-md-6">{{ $row->description }}</td>
				</tr>
				@endforeach
				@endforeach
			</tbody>
		</table>
		<div class="row">
			<div class="col-md-12">
				<a href="#" data-toggle="modal" data-target="#cashbookModal" id="newcash" class="btn btn-primary"><i class="fa fa-pencil"></i> Nieuwe regel</a>
				<a href="#" data-toggle="modal" data-target="#accountModal" id="newacc" class="btn btn-primary"><i class="fa fa-pencil"></i> Nieuwe rekening</a>
			</div>
		</div>
		@endif
			
	</section>
</div>
</div>

@stop

