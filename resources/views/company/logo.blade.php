<?php
use \BynqIO\Dynq\Models\Resource;
?>

@extends('company.layout', ['page' => 'logo'])

@section('company_section_name', 'Logo & Voorwaarden')

@section('company_content')
<div class="row">
    <div class="col-md-6">
        <h4>Logo</h4>
        <form action="{{ url('company/uploadlogo') }}" method="post" enctype="multipart/form-data">
        {!! csrf_field() !!}
        <input type="hidden" name="id" id="id" value="{{ $relation->id }}"/>

        @if ($relation->logo_id)
        <div>
        <h5>Huidige logo</h5><img src="/res-{{ $relation->logo_id }}/view"/ width="300"></div>
        @endif

        <br />
        <div class="form-group">
            <label for="image">Afbeelding Uploaden</label>
            <div class="input-group col-md-12">
                <span class="input-group-btn">
                    <span class="btn btn-primary btn-file">
                        <i class="fa fa-file-o" aria-hidden="true"></i> Bestand&hellip; <input name="image" type="file" multiple>
                    </span>
                </span>
                <input type="text" class="form-control" readonly>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-primary"><i class="fa fa-check"></i> Opslaan</button>
            </div>
        </div>

        </form>
    </div>
    <div class="col-md-6">
        <h4>Voorwaarden</h4>
        <form action="{{ url('company/uploadagreement') }}" method="post" enctype="multipart/form-data">
        {!! csrf_field() !!}
        <input type="hidden" name="id" id="id" value="{{ $relation->id }}"/>

        @if ($relation->agreement_id)
        <div class=
            <span class="cart_img" style="width:45px;"><a href="/res-{{ $relation->agreement_id }}/download"><i class="fa fa-file-pdf-o fsize60"></i></a></span>
            <a href="/res-{{ $relation->agreement_id }}/download" class="product_name">{{ Resource::find($relation->agreement_id)->resource_name }}</a>
        </div>
        @endif

        <br />
        <div class="form-group">
            <label for="image">PDF Uploaden</label>
            <div class="input-group col-md-12">
                <span class="input-group-btn">
                    <span class="btn btn-primary btn-file">
                        <i class="fa fa-file-o" aria-hidden="true"></i> Bestand&hellip; <input name="doc" type="file" multiple>
                    </span>
                </span>
                <input type="text" class="form-control" readonly>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-primary"><i class="fa fa-check"></i> Opslaan</button>
            </div>
        </div>

        </form>
    </div>
</div>
@endsection
