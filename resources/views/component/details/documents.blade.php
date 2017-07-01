<?php

use BynqIO\Dynq\Models\Resource;

?>

@if (!$project->project_close)
<div class="pull-right">
    <form id="upload-file" action="/project/document/upload" method="post" enctype="multipart/form-data">
        {!! csrf_field() !!}
        <label class="btn btn-primary btn-file">
            <i class="fa fa-cloud-upload"></i>&nbsp;Upload document <input type="file" name="projectfile" id="btn-load-file" style="display: none;">
        </label>
        <input type="hidden" value="{{ $project->id }}" name="project" />
    </form>
</div>
@endif

<h4>Projectdocumenten</h4>

<div class="white-row">

    <div id="cartContent">
        <div class="item head">
            <span class="cart_img" style="width:45px;"></span>
            <span class="product_name fsize13 bold">Filename</span>
            <span class="remove_item fsize13 bold" style="width: 120px;"></span>
            <span class="total_price fsize13 bold">Grootte</span>
            <span class="qty fsize13 bold">Geupload</span>
            <div class="clearfix"></div>
        </div>
        <?php $i=0; ?>
        @foreach(Resource::where('project_id', $project->id)->get() as $file)
        <?php $i++; ?>
        <div class="item">
            <div class="cart_img" style="width:45px;"><a href="/resource/{{ $file->id }}/download/file"><i class="fa {{ $file->fa_icon() }} fsize20"></i></a></div>
            <a href="/resource/{{ $file->id }}/download/file" class="product_name">{{ $file->resource_name }}</a>
            @if (!$project->project_close)
            <a href="/resource/{{ $file->id }}/delete" class="btn btn-danger btn-xs" style="float: right;margin: 10px;">Verwijderen</a>
            @else
            <a href="#" class="btn btn-danger btn-xs disabled" style="float: right;margin: 10px;">Verwijderen</a>
            @endif
            <div class="total_price"><span>{{ round($file->file_size/1024) }}</span> Kb</div>
            <div class="qty">{{ $file->created_at->format("d-m-Y") }}</div>
            <div class="clearfix"></div>
        </div>
        @endforeach
        @if (!$i)
        <div class="item">
            <div style="width: 100%;text-align: center;" class="product_name">Er zijn nog geen documenten bij dit project</div>
        </div>
        @endif

        <div class="clearfix"></div>
    </div>
</div>
