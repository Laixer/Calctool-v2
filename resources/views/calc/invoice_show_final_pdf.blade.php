<?php

use \Calctool\Models\Project;
use \Calctool\Models\Resource;
use \Calctool\Models\PartType;
use \Calctool\Models\Invoice;
use \Calctool\Models\Offer;

$common_access_error = false;
$invoice = Invoice::find(Route::Input('invoice_id'));
if (!$invoice) {
	$common_access_error = true;
} else {
	$offer = Offer::find($invoice->offer_id);
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
});
$(document).ready(function() {
	$('#sendmail').click(function(){
		$.post("/invoice/sendmail", {
			invoice: {{ $invoice->id }}
		}, function(data){
			var json = $.parseJSON(data);
			if (json.success) {
				$('#mailsent').show();
			}
		});
	});
	$('#sendpost').click(function(){
		$.post("/invoice/sendpost", {
			invoice: {{ $invoice->id }}
		}, function(data){
			var json = $.parseJSON(data);
			if (json.success) {
				$('#postsent').show();
			}
		});
	});
});
</script>
	<div id="wrapper">

	<section class="container">

		@include('calc.wizard', array('page' => 'invoice'))

		<div id="mailsent" class="alert alert-success" style="display: none;">
			<i class="fa fa-check-circle"></i>
			<strong>Email verstuurd naar opdrachtgever</strong>
		</div>

		<div id="postsent" class="alert alert-success" style="display: none;">
			<i class="fa fa-check-circle"></i>
			<strong>De factuur zal zo snel mogelijk per post worden verzonden</strong>
		</div>

		<div class="modal fade ajax_modal_container" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content"></div>
			</div>
		</div>

		<div class="pull-right">
			<?php if (!$project->project_close) { ?>
			<div class="btn-group" role="group">
			  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Versturen&nbsp;&nbsp;<span class="caret"></span></button>
			  <ul class="dropdown-menu">
				<li><a href="/invoice/project-{{ $project->id }}/invoice-{{ $invoice->id }}/mail-preview" data-toggle="modal" data-target=".ajax_modal_container">Per email</a></li>
			    <li><a href="/res-{{ $res->id }}/download">Per post (download PDF)</a></i>
			    <li><a href="javascript:void(0);" id="sendpost">Door calculatieTool.com</a></li>
			  </ul>
			  </div>
			<?php } ?>
		</div>

		<h2><strong>Factuur</strong></h2>

		<div id="pages"></div>

		<div class="row">
			<div class="col-sm-6"></div>
			<div class="col-sm-6">
				<div class="padding20 pull-right">
					<?php if (!$project->project_close) { ?>
					<div class="btn-group" role="group">
					  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Versturen&nbsp;&nbsp;<span class="caret"></span></button>
					  <ul class="dropdown-menu">
						<li><a href="/invoice/project-{{ $project->id }}/invoice-{{ $invoice->id }}/mail-preview" data-toggle="modal" data-target=".ajax_modal_container">Per email</a></li>
					    <li><a href="/res-{{ $res->id }}/download">Per post (download PDF)</a></i>
					    <li><a href="javascript:void(0);" id="sendpost">Door calculatieTool.com</a></li>
					  </ul>
					  </div>
					<?php } ?>
				</div>
			</div>
		</div>

	</section>
</div>
@stop

<?php } ?>
