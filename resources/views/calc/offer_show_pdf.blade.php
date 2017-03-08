<?php

use \Calctool\Models\Offer;
use \Calctool\Models\Project;
use \Calctool\Models\Resource;
use \Calctool\Models\PartType;

$common_access_error = false;
$offer = Offer::find(Route::Input('offer_id'));
if (!$offer) {
	$common_access_error = true;
} else {
	$project = Project::find($offer->project_id);
	if (!$project || !$project->isOwner()) {
		$common_access_error = true;
	} else {
		$res = Resource::find($offer->resource_id);
		$offer_last = Offer::where('project_id','=',$project->id)->orderBy('created_at', 'desc')->first();
	}
}
?>
@extends('layout.master')

@section('title', 'Offerte')

@push('style')
<link media="all" type="text/css" rel="stylesheet" href="/components/x-editable/dist/bootstrap3-editable/css/bootstrap-editable.css">
<link rel="stylesheet" href="/components/bootstrap-multiselect/dist/css/bootstrap-multiselect.css" type="text/css"/>
@endpush

@push('scripts')
<script src="/components/x-editable/dist/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
<script type="text/javascript" src="/components/bootstrap-multiselect/dist/js/bootstrap-multiselect.js"></script>
@endpush

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
		$.post("/offer/sendmail", {
			offer: {{ $offer->id }}
		}, function(data){
			var json = data;
			if (json.success) {
				$('#mailsent').show();
			}
		});
	});
	$('#sendpost').click(function(){
		$.post("/offer/sendpost", {
			offer: {{ $offer->id }}
		}, function(data){
			var json = data;
			if (json.success) {
				$('#postsent').show();
			}
		});
	});
 //    $('#dateRangePicker').datepicker().on('changeDate', function(e){
	// 	$.post("/offer/close", {
	// 		date: e.date.toISOString(),
	// 		offer: {{ $offer_last->id }},
	// 		project: {{ $project->id }}
	// 	}, function(data){
	// 		location.reload();
	// 	});
	// });
	$('#dateRangePicker').datepicker({
		format: 'dd-mm-yyyy'
	}).on('changeDate', function(ev){
		$(this).datepicker('hide');
	});
	$('#close_offer').click(function(e) {
		var from = $('#dateRangePicker').find('input').val().split("-");
		var f = new Date(from[2], from[1] - 1, from[0]);

		$.post("/offer/close", {
			date: f,
			offer: {{ $offer_last->id }},
			project: {{ $project->id }}
		}, function(data) {
			if (data.success)
				location.reload();
		});
	});
});
</script>
<style>
.datepicker{z-index:1151 !important;}
</style>
	<div id="wrapper">

	<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel2">Opdracht bevestiging</h4>
				</div>

				<div class="modal-body">
					<div class="form-horizontal">

					    <div class="form-group">
					        <label class="col-xs-3 control-label">Bevestiging</label>
					        <div class="col-xs-6 date">
					            <div class="input-group input-append date" id="dateRangePicker">
					                <input type="text" class="form-control" name="date" value="{{ date('d-m-Y') }}" />
					                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
					            </div>
					        </div>
					    </div>

					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-primary" id="close_offer" data-dismiss="modal"><i class="fa fa-check"></i> Opslaan</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="confirmModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel2">Verstuur bevestiging</h4>
				</div>

				<div class="modal-body">
					<div class="form-horizontal">

					    <p>Na bevestiging via onderstaande knop zullen wij de offerte definitief voor u versturen middel de post. De offerte en eventuele bijlage wordt in kleur geprint en verzonden in een 1/3 A4 venster enveloppe. Dit is vrij van print- en verzendkosten.</p>

						<p>Graag verzoeken wij u de offerte goed te controleren alvorens u de definitieve opdracht geeft. Annuleren van verzending kan dezelfde dag voor 16:00 via de email (info@calculatietool.com)</p>

					</div>
				</div>
				<div class="modal-footer">
					<a class="btn btn-primary pull-right" id="sendpost" data-dismiss="modal" aria-hidden="true"><i class="fa fa-check"></i> Definitief versturen per post</a>
				</div>
			</div>
		</div>
	</div>

	<section class="container">

		@include('calc.wizard', array('page' => 'offer'))

		<div id="mailsent" class="alert alert-success" style="display: none;">
			<i class="fa fa-check-circle"></i>
			<strong>Email verstuurd naar opdrachtgever</strong>
		</div>

		<div id="postsent" class="alert alert-success" style="display: none;">
			<i class="fa fa-check-circle"></i>
			<strong>De offerte zal zo snel mogelijk per post worden verzonden</strong>
		</div>

		<div class="modal fade ajax_modal_container" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content"></div>
			</div>
		</div>

		<div class="pull-right">
			<?php if (!$project->project_close && !$offer->offer_finish) { ?>
			@if ($offer_last->id == $offer->id && !$offer->offer_finish)
			<div class="btn-group" role="group">
			  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-paper-plane">&nbsp;</i>Versturen&nbsp;&nbsp;<span class="caret"></span></button>
			  <ul class="dropdown-menu">
				<li><a href="/offer/project-{{ $project->id }}/offer-{{ $offer->id }}/mail-preview" data-toggle="modal" data-target=".ajax_modal_container"><i class="fa fa-at">&nbsp;</i>Per email</a></li>
			    <li><a href="/res-{{ $res->id }}/download"><i class="fa fa-cloud-download">&nbsp;</i>Download PDF</a></i>
			    <li><a href="#" data-toggle="modal" data-target="#confirmModal2"><i class="fa fa-bolt">&nbsp;&nbsp;</i>Door calculatieTool.com</a></li>
			  </ul>
			</div>
			<a href="/offer/project-{{ $project->id }}" class="btn btn-primary"><i class="fa fa-pencil-square-o">&nbsp;</i>Bewerken</a>
			<a href="#" data-toggle="modal" data-target="#confirmModal" class="btn btn-primary"><i class="fa fa-check-square-o">&nbsp;</i>Opdracht bevestigen</a>
			@endif
			<?php } else { ?>
			<a href="/res-{{ $res->id }}/download" class="btn btn-primary"><i class="fa fa-cloud-download">&nbsp;</i>Download PDF</a>
			<?php } ?>
		</div>

		<h2><strong>Offerte</strong></h2>

		<div id="pages"></div>

		<div class="row">
			<div class="col-sm-6"></div>
			<div class="col-sm-6">
				<div class="padding20 pull-right">
					<?php if (!$project->project_close && !$offer->offer_finish) { ?>
					@if ($offer_last->id == $offer->id && !$offer->offer_finish)
					<a href="/offer/project-{{ $project->id }}" class="btn btn-primary"><i class="fa fa-pencil-square-o">&nbsp;</i>Bewerken</a>
					<a href="#" data-toggle="modal" data-target="#confirmModal" class="btn btn-primary">Opdracht bevestigen</a>
					@endif
					<?php } else { ?>
					<a href="/res-{{ $res->id }}/download" class="btn btn-primary">Download PDF</a>
					<?php } ?>
				</div>
			</div>
		</div>

	</section>
</div>
@stop

<?php } ?>



