<?php
use \BynqIO\Dynq\Models\Project;
use \BynqIO\Dynq\Models\User;
use \BynqIO\Dynq\Models\Relation;
use \BynqIO\Dynq\Models\RelationKind;
use \BynqIO\Dynq\Models\OfferPost;
use \BynqIO\Dynq\Models\InvoicePost;
use \BynqIO\Dynq\Models\Offer;
use \BynqIO\Dynq\Models\Invoice;
?>

@extends('layout.master')

@section('title', 'Opdrachtbeheer')

@section('content')
<script type="text/javascript">
    $(document).ready(function() {
        $(".offer-send").click(function(e){
            var $dataid = $(this).attr('data-id');
            $.post("/admin/snailmail/offer/done", {id: $dataid}, function(){
                location.reload();
            }).fail(function(e) { console.log(e); });
        });
        $(".invoice-send").click(function(e){
            var $dataid = $(this).attr('data-id');
            $.post("/admin/snailmail/invoice/done", {id: $dataid}, function(){
                location.reload();
            }).fail(function(e) { console.log(e); });
        });
        $(".offer-delete").click(function(e){
            var $dataid = $(this).attr('data-id');
            $.post("/admin/snailmail/offer/delete", {id: $dataid}, function(){
                location.reload();
            }).fail(function(e) { console.log(e); });
        });
        $(".invoice-delete").click(function(e){
            var $dataid = $(this).attr('data-id');
            $.post("/admin/snailmail/invoice/delete", {id: $dataid}, function(){
                location.reload();
            }).fail(function(e) { console.log(e); });
        });
    });
</script>

<div id="wrapper">

    <section class="container">

        <div class="col-md-12">

            <div>
            <ol class="breadcrumb">
              <li><a href="/">Dashboard</a></li>
              <li><a href="/admin">Admin Dashboard</a></li>
              <li class="active">Opdrachtbeheer</li>
            </ol>
            <div>

            <h2><strong>Opdrachtbeheer</strong></h2>

            <div class="white-row">
                <h4>Offertes</h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="col-md-1">ID</th>
                            <th class="col-md-2">Offerte</th>
                            <th class="col-md-2">Gebruiker</th>
                            <th class="col-md-2">Status</th>
                            <th class="col-md-2">Aangemaakt</th>
                            <th class="col-md-2">Acties</th>
                        </tr>
                    </thead>

                    <tbody>
                    @foreach (OfferPost::orderBy('created_at', 'desc')->get() as $post)
                    <?php
                        $offer = Offer::find($post->offer_id);
                        $project = Project::find($offer->project_id);
                    ?>
                        <tr>
                            <td class="col-md-1">{{ $post->id }}</td>
                            <td class="col-md-2"><a href="/res-{{ ($offer->resource_id) }}/download">{{ $offer->offer_code }}</a></td>
                            <td class="col-md-2">{{ ucfirst(User::find($project->user_id)->username) }}</td>
                            <td class="col-md-2">{{ $post->sent_date ? 'Verstuurd op '.date('d-m-Y', strtotime($post->sent_date)) : 'Open' }}</td>
                            <td class="col-md-2">{{ date('d-m-Y H:i:s', strtotime(DB::table('offer_post')->select('created_at')->where('id','=',$post->id)->get()[0]->created_at)) }}</td>
                            <td class="col-md-2">@if (!$post->sent_date)<a href="javascript:void(0);" data-id="{{ $post->id }}" class="btn btn-primary btn-xs offer-send"><i class="fa fa-paper-plane fa-fw"></i> Verstuurd</a>@endif&nbsp;<a href="javascript:void(0);" data-id="{{ $post->id }}" class="btn btn-danger btn-xs offer-delete">Delete</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="white-row">
                <h4>Facturen</h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="col-md-1">ID</th>
                            <th class="col-md-2">Offerte</th>
                            <th class="col-md-2">Gebruiker</th>
                            <th class="col-md-2">Status</th>
                            <th class="col-md-2">Aangemaakt</th>
                            <th class="col-md-2">Acties</th>
                        </tr>
                    </thead>

                    <tbody>
                    @foreach (InvoicePost::orderBy('created_at', 'desc')->get() as $post)
                    <?php
                        $invoice = Invoice::find($post->invoice_id);
                        $offer = Offer::find($invoice->offer_id);
                        $project = Project::find($offer->project_id);
                    ?>
                        <tr>
                            <td class="col-md-1">{{ $invoice->id }}</td>
                            <td class="col-md-2"><a href="/res-{{ ($invoice->resource_id) }}/download">{{ $invoice->invoice_code }}</a></td>
                            <td class="col-md-2">{{ ucfirst(User::find($project->user_id)->username) }}</td>
                            <td class="col-md-2">{{ $post->sent_date ? 'Verstuurd op '.date('d-m-Y', strtotime($post->sent_date)) : 'Open' }}</td>
                            <td class="col-md-2">{{ date('d-m-Y H:i:s', strtotime(DB::table('invoice_post')->select('created_at')->where('id','=',$post->id)->get()[0]->created_at)) }}</td>
                            <td class="col-md-2">@if (!$post->sent_date)<a href="javascript:void(0);" data-id="{{ $post->id }}" class="btn btn-primary btn-xs invoice-send"><i class="fa fa-paper-plane fa-fw"></i> Verstuurd</a>@endif&nbsp;<a href="javascript:void(0);" data-id="{{ $post->id }}" class="btn btn-danger btn-xs invoice-delete">Delete</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

</div>
@stop
