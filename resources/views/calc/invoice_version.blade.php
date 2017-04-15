<?php

use \BynqIO\CalculatieTool\Models\Project;
use \BynqIO\CalculatieTool\Models\Invoice;
use \BynqIO\CalculatieTool\Models\Offer;
use \BynqIO\CalculatieTool\Models\InvoiceVersion;

$common_access_error = false;
$invoice = Invoice::find(Route::Input('invoice_id'));
if (!$invoice)
	$common_access_error = true;
$offer = Offer::find($invoice->offer_id);
if (!$offer)
	$common_access_error = true;
$project = Project::find($offer->project_id);
if (!$project || !$project->isOwner()) {
	$common_access_error = true;
}
?>

@extends('layout.master')

@section('title', 'Factuurgeschiedenis')

<?php if($common_access_error){ ?>
@section('content')
<div id="wrapper">
	<section class="container">
		<div class="alert alert-danger">
			<i class="fa fa-frown-o"></i>
			<strong>Fout</strong>
			Dit project bestaat niet
		</div>
	</section>
</div>
@stop
<?php }else{ ?>

@section('content')
<script type="text/javascript">

</script>
<div id="wrapper">

	<section class="container">

		@include('calc.wizard', array('page' => 'invoice'))

		<h2><strong>Factuurgeschiedenis {{ $invoice->invoice_code }}</strong></h2>

		<div class="white-row">
			<table class="table table-striped">
				<thead>
					<tr>
						<th class="col-md-2">Factuurversie</th>
						<th class="col-md-2">Datum</th>
						<th class="col-md-3">Betalingscondities</th>
						<th class="col-md-3">Factuurbedrag (excl. BTW)</th>
						<th class="col-md-3">Acties</th>
					</tr>
				</thead>
				<tbody>
					<?php $i = InvoiceVersion::where('invoice_id', '=', $invoice->id)->count(); ?>
					@foreach(InvoiceVersion::where('invoice_id', '=', $invoice->id)->orderBy('created_at')->get() as $version)
					<tr>
						<td class="col-md-2"><a href="/invoice/project-{{ $project->id }}/invoice-version-{{ $version->id }}">{{ $version->invoice_code.'-'.$i }}</a></td>
						<td class="col-md-2"><?php echo date('d-m-Y', strtotime(DB::table('invoice_version')->where('id',$version->id)->select(DB::raw('created_at'))->first()->created_at)); ?></td>
						<td class="col-md-3">{{ $version->payment_condition }} dagen</td>
						<td class="col-md-3">{{ '&euro; '.number_format($version->amount, 2, ",",".") }}</td>
						<td class="col-md-3"><a href="/res-{{ ($version->resource_id) }}/download" class="btn btn-primary btn-xs"><i class="fa fa-cloud-download fa-fw"></i> Downloaden</a></td>
					</tr>
					<?php $i--; ?>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>

	</section>

</div>
@stop

<?php } ?>
