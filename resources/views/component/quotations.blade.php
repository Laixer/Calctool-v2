<?php

use \BynqIO\CalculatieTool\Models\Project;
use \BynqIO\CalculatieTool\Models\Offer;
use \BynqIO\CalculatieTool\Models\ProjectType;
use \BynqIO\CalculatieTool\Calculus\CalculationEndresult;

$offer_last = Offer::where('project_id','=',$project->id)->orderBy('created_at', 'desc')->first();
?>

@extends('component.layout', ['title' => $page])

@section('component_content')
<script type="text/javascript">
$(document).ready(function() {
    @if ($offer_last)
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
    @endif
});
</script>
<style>
.datepicker{z-index:1151 !important;}
</style>

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
                <button class="btn btn-primary" id="close_offer" data-dismiss="modal">Opslaan</button>
            </div>
        </div>
    </div>
</div>

@if ($offer_last)
@if (number_format(CalculationEndresult::totalProject($project), 3, ",",".") != number_format($offer_last->offer_total, 3, ",","."))
<div class="alert alert-warning">
    <i class="fa fa-fa fa-info-circle"></i>
    Gegevens zijn gewijzigd ten op zichte van de laaste offerte
</div>
@endif
@endif

@if (!CalculationEndresult::totalProject($project))
<div class="alert alert-warning">
    <i class="fa fa-fa fa-info-circle"></i>
    Offertes kunnen pas worden gemaakt wanneer het project waarde bevat
</div>
@endif

@if ($offer_last && !$offer_last->offer_finish)
<div class="alert alert-warning">
    <i class="fa fa-fa fa-info-circle"></i>
    Zend na aanpassing van de calculatie een nieuwe offerte naar uw opdrachtgever.
</div>
@endif

<div class="white-row">
    <table class="table table-striped">
        <thead>
            <tr>
                <th class="col-md-4">Offertenummer</th>
                <th class="col-md-3">Datum</th>
                <th class="col-md-3">Offertebedrag (excl. BTW)</th>
                <th class="col-md-3">Acties</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; ?>
            @foreach(Offer::where('project_id',$project->id)->orderBy('created_at')->get() as $offer)
            <?php $i++; ?>
            <tr>
                <td class="col-md-4"><a href="/offer/project-{{ $project->id }}/offer-{{ $offer->id }}">{{ $offer->offer_code }}</a></td>
                <td class="col-md-3"><?php echo date('d-m-Y', strtotime($offer->offer_make)); ?></td>
                <td class="col-md-3">{{ '&euro; '.number_format($offer->offer_total, 2, ",",".") }}</td>
                <td class="col-md-3">
                    @if ($offer_last && $offer_last->id == $offer->id && !$offer->offer_finish && !$project->project_close)
                    <a href="#" data-toggle="modal" data-target="#confirmModal" class="btn btn-primary btn-xs"><i class="fa fa-check-square-o">&nbsp;</i>Opdracht bevestigen</a>
                    @else
                    <a href="/res-{{ ($offer_last->resource_id) }}/download" class="btn btn-primary btn-xs"><i class="fa fa-cloud-download fa-fw"></i> Downloaden</a>
                    @endif
                </td>
            </tr>
            @endforeach
            @if (!$i)
            <tr>
                <td colspan="4" style="text-align: center;">Er zijn nog geen offertes gemaakt</td>
            </tr>
            @endif
        </tbody>
    </table>
    @if (!($offer_last && $offer_last->offer_finish) && !$project->project_close)
    <a href="/project/{{ $project->id }}-{{ $project->slug() }}/quotation/new" class="btn btn-primary btn"><i class="fa fa-pencil-square-o"></i>
        <?php
        if(Offer::where('project_id', '=', $project->id)->count('id')>0) {
            echo "Laatste versie bewerken";
        } else {
            echo "Nieuwe offerte maken";
        }
        ?>
    </a>
    @endif
</div>
@stop
