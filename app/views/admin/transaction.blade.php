@extends('layout.master')

@section('content')

<?# -- WRAPPER -- ?>
<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			<h2><strong>Transacties</strong></h2>

			<table class="table table-striped">
				<?# -- table head -- ?>
				<thead>
					<tr>
						<th class="col-md-2">Transactie</th>
						<th class="col-md-2">Gebruiker</th>
						<th class="col-md-1">Bedrag</th>
						<th class="col-md-1">Status</th>
						<th class="col-md-2">Betalingswijze</th>
						<th class="col-md-2">Verlening</th>
						<th class="col-md-2">Datum</th>
					</tr>
				</thead>

				<!-- table items -->
				<tbody>
				@foreach (Payment::orderBy('created_at', 'desc')->get() as $payment)
					<tr>
						<td class="col-md-2"><a href="/admin/transaction/{{ $payment->transaction }}">{{ $payment->transaction }}</a></td>
						<td class="col-md-2">{{ ucfirst(User::find($payment->user_id)->username) }}</td>
						<td class="col-md-1">{{ '&euro; '.number_format($payment->amount, 2,",",".") }}</td>
						<td class="col-md-1">{{ $payment->status }}</td>
						<td class="col-md-2">{{ $payment->method }}</td>
						<td class="col-md-2">{{ $payment->increment.' maanden' }}</td>
						<td class="col-md-2">{{ date('d-m-Y H:i:s', strtotime(DB::table('payment')->select('created_at')->where('id','=',$payment->id)->get()[0]->created_at)) }}</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>

	</section>

</div>
<!-- /WRAPPER -->
@stop
