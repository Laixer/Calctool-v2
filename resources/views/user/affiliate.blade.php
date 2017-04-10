<?php
use \CalculatieTool\Models\Payment;
use \CalculatieTool\Models\User;
?>

@extends('layout.master')

@section('title', 'Gebruiker transacties')

@section('content')
<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			<div>
			<ol class="breadcrumb">
			  <li><a href="/">Home</a></li>
			  <li class="active">Transacties</li>
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
				$group_id = Auth::user()->user_group;
			
				$users = User::select('id')->where('user_group', $group_id)->get();
				$selection = Payment::orderBy('created_at', 'desc')->whereIn('user_id', $users->toArray())->get();
				?>
					@foreach ($selection as $payment)
					<tr>
						<td class="col-md-2">{{ $payment->transaction }}</td>
						<td class="col-md-2">{{ ucfirst(User::find($payment->user_id)->username) }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format($payment->amount, 2,",",".") }}</td>
						<td class="col-md-1">{{ $payment->getStatusName() }}</td>
						<td class="col-md-2">{{ ucfirst($payment->method) }}</td>
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
