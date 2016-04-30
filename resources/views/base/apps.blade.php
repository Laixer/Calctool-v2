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
?>

@extends('layout.master')

<?php

$relation = Relation::find(Auth::user()->self_id);
$user = Auth::user();
?>

@section('content')

<div id="wrapper">

<!-- <script type="text/javascript" src="/js/iban.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	function prefixURL(field) {
		var cur_val = $(field).val();
		if (!cur_val)
			return;
		var ini = cur_val.substring(0,4);
		if (ini == 'http')
			return;
		else {
			if (cur_val.indexOf("www") >=0) {
				$(field).val('http://' + cur_val);
			} else {
				$(field).val('http://www.' + cur_val);
			}
		}
	}
	
	$('#accountModal').on('hidden.bs.modal', function() {
		$.post("/mycompany/cashbook/account/new", {account: $('#account').val(), account_name: $('#account_name').val(), amount: $('#amount').val()}, function(data) {
			location.reload();
		});
	});
	$('#cashbookModal').on('hidden.bs.modal', function() {
		$.post("/mycompany/cashbook/new", {account: $('#account2').val(), amount: $('#amount2').val(), date: $('#date').val(), desc: $('#desc').val()}, function(data) {
			location.reload();
		});
	});

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

	<div class="modal fade" id="accountModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel2">Nieuwe rekening</h4>
				</div>

				<div class="modal-body">
					<div class="form-horizontal">
						<div class="form-group">
							<div class="col-md-4">
								<label>Rekening</label>
							</div>
							<div class="col-md-8">
								<input name="account" id="account" type="text" class="form-control" />
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-4">
								<label>Naam</label>
							</div>
							<div class="col-md-8">
								<input name="account_name" id="account_name" type="text" class="form-control" />
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-4">
								<label>Startbedrag</label>
							</div>
							<div class="col-md-8">
								<input name="amount" id="amount" type="text" class="form-control" />
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

	<div class="modal fade" id="cashbookModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel2">Nieuwe regel</h4>
				</div>

				<div class="modal-body">
					<div class="form-horizontal">
						<div class="form-group">
							<div class="col-md-4">
								<label>Rekening</label>
							</div>
							<div class="col-md-8">
								<select name="account2" id="account2" class="form-control pointer">
									@foreach (BankAccount::where('user_id', Auth::id())->get() as $account)
									<option value="{{ $account->id }}">{{ $account->account }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-4">
								<label>Bedrag</label>
							</div>
							<div class="col-md-8">
								<input name="amount2" id="amount2" type="text" class="form-control" />
							</div>
						</div>
					    <div class="form-group">
					        <label class="col-md-4">Datum</label>
					        <div class="col-md-8 date">
					            <div class="input-group input-append date" id="dateRangePicker">
					                <input type="text" class="form-control" name="date" id="date" />
					                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
					            </div>
					        </div>
					    </div>
						<div class="form-group">
							<div class="col-md-4">
								<label>Omschrijving</label>
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
	</div> -->

	<section class="container">

	<div id="wrapper">

		<div class="col-md-12">

			<h2 style="margin: 10px 0 20px 0;"><strong>Apps</strong></h2>
			<div class="row">

				<div class="col-sm-6 col-md-2">
					<div class="item-box item-box-show fixed-box">
						<figure>
							<a class="item-hover" href="/mycompany">
								<span class="overlay color2"></span>
								<span class="inner" style="top:40%;">
									<span class="block fa fa-home fsize60"></span>
								</span>
							</a>
							<a href="/mycompany" class="btn btn-primary add_to_cart"><strong> Kladblok</strong></a>

						</figure>
					</div>
				</div>

				<div class="col-sm-6 col-md-2">
					<div class="item-box item-box-show fixed-box">
						<figure>
							<a class="item-hover" href="/material">
								<span class="overlay color2"></span>
								<span class="inner" style="top:40%;">
									<span class="block fa fa-wrench fsize60"></span>
								</span>
							</a>
							<a href="/material" class="btn btn-primary add_to_cart"><strong> Kasboek</strong></a>
						</figure>
					</div>
				</div>
			</div>
						
			<div class="row">
				<form action="relation/updatemycompany" method="post">
				{!! csrf_field() !!}

					<h4>Kladblok van mijn bedrijf <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit betreft een persoonlijk kladblok je eigen bedrijf en wordt nergens anders weergegeven." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></h4>

					<div class="row">
						<div class="form-group">
							<div class="col-md-12">
								<textarea name="note" id="summernote" rows="10" class="form-control">{{ Input::old('note') ? Input::old('note') : ($relation ? $relation->note : '') }}</textarea>
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
		
		</div>

		<div class="row">
			<form action="relation/updatemycompany" method="post">
			{!! csrf_field() !!}

				<h4>Kladblok van mijn bedrijf <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit betreft een persoonlijk kladblok je eigen bedrijf en wordt nergens anders weergegeven." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></h4>

				<div class="row">
					<div class="form-group">
						<div class="col-md-12">
							<textarea name="note" id="summernote" rows="10" class="form-control">{{ Input::old('note') ? Input::old('note') : ($relation ? $relation->note : '') }}</textarea>
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
			
	</section>
</div>
</div>
</section>
</div>

</div> 


