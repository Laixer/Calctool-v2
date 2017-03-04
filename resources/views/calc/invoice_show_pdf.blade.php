<?php

use \Calctool\Models\Project;
use \Calctool\Models\Resource;
use \Calctool\Models\PartType;
use \Calctool\Models\Invoice;
use \Calctool\Models\Offer;
use \Calctool\Models\InvoiceVersion;

$common_access_error = false;
$invoice = InvoiceVersion::find(Route::Input('invoice_id'));
if (!$invoice) {
	$common_access_error = true;
} else {
	$_invoice = Invoice::find($invoice->invoice_id);
	if (!$_invoice)
		$common_access_error = true;
	$offer = Offer::find($_invoice->offer_id);
	if (!$offer)
		$common_access_error = true;
	$project = Project::find($offer->project_id);
	if (!$project || !$project->isOwner()) {
		$common_access_error = true;
	} else {
		$res = Resource::find($invoice->resource_id);
	}
}
?>
@extends('layout.master')

@section('title', 'Conceptfactuur')

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
<script src="/plugins/pdf/build/pdf.js" type="text/javascript"></script>
<script id="script">
	var url = '/{{ $res->file_location }}';
	var numPages = 0;
	PDFJS.workerSrc = '/plugins/pdf/build/pdf.worker.js';
	PDFJS.getDocument(url).then(function getPdf(pdf) {

	for (var i = 0; i < pdf.numPages; i++) {
		var ndr = '<div class="white-row"><canvas id="the-canvas'+i+'" style="border:0px solid black;text-align:center;"/></canvas></div>';
		$('#pages').append(ndr);
	};

	pdf.getPage(1).then(function getPage(page) {
		var viewport = page.getViewport(1.85);
		var canvas = document.getElementById('the-canvas'+numPages);
		var context = canvas.getContext('2d');
		canvas.height = viewport.height;
		canvas.width = viewport.width;

		page.render({canvasContext: context, viewport: viewport});
		numPages++;
		if (numPages <= pdf.numPages) {
			pdf.getPage(numPages+1).then(getPage);
		}
	});

	$('.oclose').click(function(e){
		e.preventDefault();
		$('#progress').show();

		var timerId = 0;
		var ctr=5;
		var max=9;

		timerId = setInterval(function () {
			ctr++;
			$('#progress div').width(ctr*max + "%");
			if (ctr == max)
				clearInterval(timerId);
		}, 250);
		$.post("/invoice/close", {projectid: {{ $project->id }}, id: {{ $_invoice->id }} }, function(data) {
			$('#progress div').width("100%");
			window.location.href = '/invoice/project-'+{{ $project->id }}+'/pdf-invoice-'+{{ $_invoice->id }};
		}).fail(function(e) { console.log(e); });
	});

});
</script>

<div id="wrapper">

	<section class="container">

		@include('calc.wizard', array('page' => 'invoice'))

		<div class="pull-right">
			<?php if (!$project->project_close) { ?>
			<?php if (!$_invoice->invoice_close){ ?>
			<?php if (!$_invoice->isclose) { ?>
				<a href="/invoice/project-{{ $project->id }}/term-invoice-{{ $_invoice->id }}" class="btn btn-primary">Bewerk</a>

			<?php
			$prev = Invoice::where('offer_id','=', $_invoice->offer_id)->where('isclose','=',false)->where('priority','<',$_invoice->priority)->orderBy('priority', 'desc')->first();
			$next = Invoice::where('offer_id','=', $_invoice->offer_id)->where('isclose','=',false)->where('priority','>',$_invoice->priority)->orderBy('priority')->first();
			$end = Invoice::where('offer_id','=', $_invoice->offer_id)->where('isclose','=',true)->first();
			if ($prev && $prev->invoice_close && $next && !$next->invoice_close) {
				echo '<button class="btn btn-primary oclose">Factureren</button>';
			} else if (!$prev && $next && !$next->invoice_close) {
				echo '<button class="btn btn-primary oclose">Factureren</button>';
			} else if (!$prev && !$next) {
				echo '<button class="btn btn-primary oclose">Factureren</button>';
			} else if ($prev && $prev->invoice_close && $end && !$end->invoice_close) {
				echo '<button class="btn btn-primary oclose">Factureren</button>';
			}
			?>

			<?php }else{ ?>
				<a href="/invoice/project-{{ $project->id }}/invoice-{{ $_invoice->id }}" class="btn btn-primary">Bewerk</a>

			<?php
			$prev = Invoice::where('offer_id','=', $_invoice->offer_id)->where('isclose','=',false)->orderBy('priority', 'desc')->first();
			if ($prev && $prev->invoice_close) {
				echo '<button class="btn btn-primary oclose">Factureren</button>';
			} else if (!$prev) {
				echo '<button class="btn btn-primary oclose">Factureren</button>';
			}
			?>

			<?php } ?>
			<?php } ?>
			<a href="/res-{{ $res->id }}/download" class="btn btn-primary">Download PDF</a>
			<?php } ?>
		</div>

		<h2><strong>Concept factuur</strong></h2>

		<div id="progress" class="progress" style="height: 15px;display: none;">
		  <div class="progress-bar progress-bar-striped active" role="progressbar"
		  aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width:20%;background-color:#89a550;">
		  </div>
		</div>

		<div id="pages"></div>

		<div class="row">
			<div class="col-sm-6"></div>
			<div class="col-sm-6">
				<div class="padding20 pull-right">
					<?php if (!$project->project_close) { ?>
					<?php if (!$_invoice->invoice_close){ ?>
					<?php if (!$_invoice->isclose) { ?>
						<a href="/invoice/project-{{ $project->id }}/term-invoice-{{ $_invoice->id }}" class="btn btn-primary">Bewerk</a>

					<?php
					$prev = Invoice::where('offer_id','=', $_invoice->offer_id)->where('isclose','=',false)->where('priority','<',$_invoice->priority)->orderBy('priority', 'desc')->first();
					$next = Invoice::where('offer_id','=', $_invoice->offer_id)->where('isclose','=',false)->where('priority','>',$_invoice->priority)->orderBy('priority')->first();
					$end = Invoice::where('offer_id','=', $_invoice->offer_id)->where('isclose','=',true)->first();
					if ($prev && $prev->invoice_close && $next && !$next->invoice_close) {
						echo '<button class="btn btn-primary oclose">Factureren</button>';
					} else if (!$prev && $next && !$next->invoice_close) {
						echo '<button class="btn btn-primary oclose">Factureren</button>';
					} else if (!$prev && !$next) {
						echo '<button class="btn btn-primary oclose">Factureren</button>';
					} else if ($prev && $prev->invoice_close && $end && !$end->invoice_close) {
						echo '<button class="btn btn-primary oclose">Factureren</button>';
					}
					?>

					<?php }else{ ?>
						<a href="/invoice/project-{{ $project->id }}/invoice-{{ $_invoice->id }}" class="btn btn-primary">Bewerk</a>

					<?php
					$prev = Invoice::where('offer_id','=', $_invoice->offer_id)->where('isclose','=',false)->orderBy('priority', 'desc')->first();
					if ($prev && $prev->invoice_close) {
						echo '<button class="btn btn-primary oclose">Factureren</button>';
					} else if (!$prev) {
						echo '<button class="btn btn-primary oclose">Factureren</button>';
					}
					?>

					<?php } ?>
					<?php } ?>
					<a href="/res-{{ $res->id }}/download" class="btn btn-primary">Download PDF</a>
					<?php } ?>
				</div>
			</div>
		</div>

	</section>
</div>
@stop

<?php } ?>
