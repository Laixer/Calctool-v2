@extends('component.layout', ['title' => $page])

@section('component_content')
<script src="/plugins/pdf/build/pdf.js" type="text/javascript"></script>
<script type="text/javascript">

PDFJS.workerSrc = '/plugins/pdf/build/pdf.worker.js';

PDFJS.getDocument('{!! $url !!}').then(function(pdf) {
    var pageNumber = 0;

    function buildPage(page) {
@isset($options)
        var scale = 1.25;
@else
        var scale = 1.92;
@endisset
        var viewport = page.getViewport(scale);

        // Prepare canvas using PDF page dimensions
        var canvas = document.getElementById('the-canvas' + pageNumber++);
        var context = canvas.getContext('2d');
        canvas.height = viewport.height;
        canvas.width = viewport.width;

        // Render PDF page into canvas context
        page.render({
            canvasContext: context,
            viewport: viewport
        });

        if (pageNumber < pdf.numPages) {
            pdf.getPage(pageNumber + 1).then(buildPage);
        }
    }

    // Build the canvas
    for (var i = 0; i < pdf.numPages; ++i) {
        $('#loader').remove();
        $('#pages').append('<canvas id="the-canvas' + i + '" style="border:0px solid black;text-align:center;margin-bottom:20px;border-radius:2px;"></canvas>');
    };

    pdf.getPage(pageNumber + 1).then(buildPage);
}, function (reason) { console.error(reason); });
</script>

@isset($options)
<div class="col-md-8 nopadding">
    <div id="pages" class="text-center">
        <img id="loader" src="https://www.processexcellencenetwork.com/sites/all/themes/iqpc_portal1/assets/images/thankyou-loader.gif" />
    </div>
</div>
@else
<div class="col-md-12 nopadding">
    <div id="pages" class="text-center">
        <img id="loader" src="https://www.processexcellencenetwork.com/sites/all/themes/iqpc_portal1/assets/images/thankyou-loader.gif" />
    </div>
</div>
@endisset

@isset($options)
<div class="col-md-4 nopadding">
    @include("component.{$component}.$options")
</div>
@endisset
@stop
