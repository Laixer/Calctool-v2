@extends('layout.master')

@section('title', 'Transactiedetails')

@section('content')
<?php
try {
	$mollie = new \Mollie_API_Client;
	$mollie->setApiKey(config('services.mollie.key'));

	$payment = $mollie->payments->get(Route::Input('transcode'));
} catch (Mollie_API_Exception $e) {
	exit();
}
?>
<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			<div>
			<ol class="breadcrumb">
			  <li><a href="/">Home</a></li>
			  <li><a href="/admin">Admin CP</a></li>
			  <li><a href="/admin/payment">Transacties & Betalingen</a></li>
			  <li class="active">{{ Route::Input('transcode') }}</li>
			</ol>
			<div>
			<br />

			<h2><strong>Transactie {{ Route::Input('transcode') }}</strong></h2>

			<div class="white-row">
				<h4>Transactiedetails</h4>
				<div class="row">
					<div class="col-md-2"><strong>Transactiecode</strong></div>
					<div class="col-md-2">{{ $payment->id }}</div>
				</div>
				<div class="row">
					<div class="col-md-2">Profielcode</div>
					<div class="col-md-2">{{ $payment->profileId }}</div>
				</div>
				<div class="row">
					<div class="col-md-2">Ordertoken</div>
					<div class="col-md-6">{{ $payment->metadata->token }}</div>
				</div>
				<div class="row">
					<div class="col-md-2">Authorisatie object</div>
					<div class="col-md-6">{{ $payment->metadata->uid }}</div>
				</div>
				<div class="row">
					<div class="col-md-2">Mode</div>
					<div class="col-md-2">{{ $payment->mode }}</div>
				</div>
				</br>
				<div class="row">
					<div class="col-md-2"><strong>Bedrag</strong></div>
					<div class="col-md-2"><strong>{{ '&euro; '.number_format($payment->amount, 2,",",".") }}</strong></div>
				</div>
				<div class="row">
					<div class="col-md-2">Bedrag teruggestort</div>
					<div class="col-md-2">{{ '&euro; '.number_format($payment->amountRefunded, 2,",",".") }}</div>
				</div>
				<div class="row">
					<div class="col-md-2">Restbedrag</div>
					<div class="col-md-2">{{ '&euro; '.number_format($payment->amountRemaining, 2,",",".") }}</div>
				</div>
				<div class="row">
					<div class="col-md-2">Betaalwzije</div>
					<div class="col-md-2">{{ $payment->method }}</div>
				</div>
				<div class="row">
					<div class="col-md-2"><strong>Status</strong></div>
					<div class="col-md-2"><strong>{{ $payment->status }}</strong></div>
				</div>
				<div class="row">
					<div class="col-md-2">Betalingsperiode</div>
					<div class="col-md-2">{{ $payment->expiryPeriod }}</div>
				</div>
				<br />
				<div class="row">
					<div class="col-md-2"><strong>Transactiedatum</strong></div>
					<div class="col-md-4"><strong>{{ date('d-m-Y H:i:s', strtotime($payment->createdDatetime)) }}</strong></div>
				</div>
				<div class="row">
					<div class="col-md-2">Betalingsdatum</div>
					<div class="col-md-4">{{ date('d-m-Y H:i:s', strtotime($payment->paidDatetime)) }}</div>
				</div>
				<div class="row">
					<div class="col-md-2">Annuleringsdatum</div>
					<div class="col-md-4">{{ $payment->cancelledDatetime }}</div>
				</div>
				<div class="row">
					<div class="col-md-2">Verlooopdatum</div>
					<div class="col-md-4">{{ $payment->expiredDatetime }}</div>
				</div>
				<br />
				<div class="row">
					<div class="col-md-2"><strong>Increment</strong></div>
					<div class="col-md-6"><strong>{{ $payment->metadata->incr }}M</strong></div>
				</div>
				<div class="row">
					<div class="col-md-2">Omschrijving</div>
					<div class="col-md-6">{{ $payment->description }}</div>
				</div>

			</div>

			<div class="white-row">
				<h4>Terugstortingen ({{ $mollie->payments_refunds->with($payment)->all()->totalCount }})</h4>
				@if ($payment->method == 'bitcoin' || $payment->method == 'paysafecard')
				<span>Terugstorten niet mogelijk voor deze betaaloptie</span>
				@else
				@if ($mollie->payments_refunds->with($payment)->all()->totalCount > 0)
				<div class="row">
					<div class="col-md-2"><strong>Terugstortcode</strong></div>
					<div class="col-md-2"><strong>Bedrag</strong></div>
					<div class="col-md-4"><strong>Datum</strong></div>
				</div>
				@foreach ($mollie->payments_refunds->with($payment)->all() as $refund)
				<div class="row">
					<div class="col-md-2">{{ $refund->id }}</div>
					<div class="col-md-2">{{ '&euro; '.number_format($refund->amount, 2,",",".") }}</div>
					<div class="col-md-4">{{ date('d-m-Y H:i:s', strtotime($refund->refundedDatetime)) }}</div>
				</div>
				@endforeach
				@endif
				<br />
				<form name="frm-refund" action="/admin/transaction/{{ $payment->id }}/refund" method="post">
				{!! csrf_field() !!}
					<div class="input-group col-md-3">
					  <input type="text" name="amount" {{ $payment->amount-$payment->amountRefunded ? '' : 'disabled' }} value="{{ ($payment->amount-$payment->amountRefunded) }}" class="form-control">
				      <span class="input-group-btn">
				        <input type="submit" class="btn btn-primary {{ $payment->amount-$payment->amountRefunded ? '' : 'disabled' }}" value="Terugstorten" />
				      </span>
					</div>
				</form>
				@endif
			</div>

		</div>

	</section>

</div>
@stop
