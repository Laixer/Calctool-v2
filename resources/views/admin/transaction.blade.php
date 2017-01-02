<?php
use \Calctool\Models\Payment;
use \Calctool\Models\User;

$user_id = null;
if (Input::get('user_id')) {
	$user_id = Input::get('user_id');
}

?>

@extends('layout.master')

@section('title', 'Transacties')

@section('content')
<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			<div>
			<ol class="breadcrumb">
			  <li><a href="/">Home</a></li>
			  <li><a href="/admin">Admin CP</a></li>
			  <li class="active">Transacties & Betalingen</li>
			</ol>
			<div>
			<br />

			<h2><strong>Transacties</strong></h2>

			<div class="white-row">
			<table class="table table-striped">
				<thead>
					<tr>
						<th class="col-md-2">Transactie</th>
						<th class="col-md-2">Gebruiker</th>
						<th class="col-md-1">Bedrag</th>
						<th class="col-md-1">Status</th>
						<th class="col-md-2">Betalingswijze</th>
						<th class="col-md-2">Verlenging</th>
						<th class="col-md-2">Datum</th>
					</tr>
				</thead>

				<tbody>
				<?php
				$selection = null;
				if ($user_id) {
					$selection = Payment::orderBy('created_at', 'desc')->where('user_id', $user_id)->get();
				} else {
					$selection  = Payment::orderBy('created_at', 'desc')->get();
				} ?>
					@foreach ($selection as $payment)
					<tr>
						<td class="col-md-2">
						@if(substr($payment->transaction, 0, 3) == 'tr_')
						<a href="/admin/transaction/{{ $payment->transaction }}">{{ $payment->transaction }}</a>
						@else
						{{ $payment->transaction }}
						@endif
						</td>
						<td class="col-md-2">{{ ucfirst(User::find($payment->user_id)->username) }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format($payment->amount, 2,",",".") }}</td>
						<td class="col-md-1">{{ $payment->getStatusName() }}</td>
						<td class="col-md-2">{{ $payment->method }}</td>
						<td class="col-md-2">{{ $payment->increment.' maanden' }}</td>
						<td class="col-md-2">{{ date('d-m-Y H:i:s', strtotime(DB::table('payment')->select('created_at')->where('id','=',$payment->id)->get()[0]->created_at)) }}</td>
					</tr>
					@endforeach
				</tbody>
			</table>
			</div>
		</div>

	</section>

</div>
@stop
