<?php
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
</script>
	<div id="wrapper">

	<section class="container">

		@include('calc.wizard', array('page' => 'offer'))

		<div class="pull-right">
			<?php if (!$project->project_close) { ?>
			<a href="/offer/project-{{ $project->id }}" class="btn btn-primary">Bewerk</a>
			<a href="/res-{{ $res->id }}/download" class="btn btn-primary">Download PDF</a>
			<?php } ?>
		</div>

		<h2><strong>Offerte</strong></h2>

		<div id="pages"></div>

		<div class="row">
			<div class="col-sm-6"></div>
			<div class="col-sm-6">
				<div class="padding20 pull-right">
					<?php if (!$project->project_close) { ?>
					<a href="/offer/project-{{ $project->id }}" class="btn btn-primary">Bewerk</a>
					<a href="/res-{{ $res->id }}/download" class="btn btn-primary">Download PDF</a>
					<?php } ?>
				</div>
			</div>
		</div>

	</section>
</div>
@stop

<?php } ?>
