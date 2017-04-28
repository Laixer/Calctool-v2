<?php

use BynqIO\CalculatieTool\Calculus\CalculationEndresult;
use BynqIO\CalculatieTool\Models\Relation;
use BynqIO\CalculatieTool\Models\PurchaseKind;
use BynqIO\CalculatieTool\Models\Contact;
use BynqIO\CalculatieTool\Models\Project;
use BynqIO\CalculatieTool\Models\ProjectType;
use BynqIO\CalculatieTool\Models\Offer;
use BynqIO\CalculatieTool\Models\Invoice;
use BynqIO\CalculatieTool\Models\Wholesale;
use BynqIO\CalculatieTool\Models\ProjectShare;
use BynqIO\CalculatieTool\Models\RelationKind;
use BynqIO\CalculatieTool\Models\Province;
use BynqIO\CalculatieTool\Models\Country;
use BynqIO\CalculatieTool\Models\Chapter;
use BynqIO\CalculatieTool\Models\Activity;
use BynqIO\CalculatieTool\Models\Timesheet;
use BynqIO\CalculatieTool\Models\Resource;
use BynqIO\CalculatieTool\Models\TimesheetKind;
use BynqIO\CalculatieTool\Models\Purchase;
use BynqIO\CalculatieTool\Models\EstimateLabor;
use BynqIO\CalculatieTool\Models\EstimateMaterial;
use BynqIO\CalculatieTool\Models\EstimateEquipment;
use BynqIO\CalculatieTool\Models\MoreLabor;
use BynqIO\CalculatieTool\Models\MoreMaterial;
use BynqIO\CalculatieTool\Models\MoreEquipment;
use BynqIO\CalculatieTool\Models\CalculationLabor;
use BynqIO\CalculatieTool\Models\CalculationMaterial;
use BynqIO\CalculatieTool\Models\CalculationEquipment;

$offer_last = Offer::where('project_id',$project->id)->orderBy('created_at', 'desc')->first();
$share = ProjectShare::where('project_id', $project->id)->first();
if ($offer_last)
    $cntinv = Invoice::where('offer_id',$offer_last->id)->where('invoice_close',true)->count('id');
else
    $cntinv = 0;

$type = ProjectType::find($project->type_id);

$offer_last ? $invoice_end = Invoice::where('offer_id','=', $offer_last->id)->where('isclose','=',true)->first() : $invoice_end = null;

$estim_total = 0;
$more_total = 0;
$less_total = 0;
$disable_estim = false;
$disable_more = false;
$disable_less = false;

foreach(Chapter::where('project_id','=', $project->id)->get() as $chap) {
    foreach(Activity::where('chapter_id','=', $chap->id)->get() as $activity) {
        $estim_total += EstimateLabor::where('activity_id','=', $activity->id)->count('id');
        $estim_total += EstimateMaterial::where('activity_id','=', $activity->id)->count('id');
        $estim_total += EstimateEquipment::where('activity_id','=', $activity->id)->count('id');

        $more_total += MoreLabor::where('activity_id','=', $activity->id)->count('id');
        $more_total += MoreMaterial::where('activity_id','=', $activity->id)->count('id');
        $more_total += MoreEquipment::where('activity_id','=', $activity->id)->count('id');	

        $less_total += CalculationLabor::where('activity_id','=', $activity->id)->where('isless',true)->count('id');
        $less_total += CalculationMaterial::where('activity_id','=', $activity->id)->where('isless',true)->count('id');
        $less_total += CalculationEquipment::where('activity_id','=', $activity->id)->where('isless',true)->count('id');	
    }
}

if ($offer_last) {
    $disable_estim = true;
}
if ($estim_total>0) {
    $disable_estim = true;
}

if ($invoice_end && $invoice_end->invoice_close) {
    $disable_more = true;
}
if ($more_total>0) {
    $disable_more = true;
}

if ($invoice_end && $invoice_end->invoice_close) {
    $disable_less = true;
}
if ($less_total>0) {
    $disable_less = true;
}
?>

@extends('component.layout', ['title' => $page])

@push('style')
<link media="all" type="text/css" rel="stylesheet" href="/components/x-editable/dist/bootstrap3-editable/css/bootstrap-editable.css">
<link media="all" type="text/css" rel="stylesheet" href="/plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css">
@endpush

@push('scripts')
<script src="/components/x-editable/dist/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
<script src="/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
@endpush

@section('component_content')
<script type="text/javascript">
$(document).ready(function() {
    $('#tab-settings').click(function(e){
        sessionStorage.toggleTabProj{{Auth::id()}} = 'settings';
    });
    $('#tab-options').click(function(e){
        sessionStorage.toggleTabProj{{Auth::id()}} = 'options';
    });
    $('#tab-financial').click(function(e){
        sessionStorage.toggleTabProj{{Auth::id()}} = 'financial';
    });
    $('#tab-documents').click(function(e){
        sessionStorage.toggleTabProj{{Auth::id()}} = 'documents';
    });
    $('#tab-communication').click(function(e){
        sessionStorage.toggleTabProj{{Auth::id()}} = 'communication';
    });
    if (sessionStorage.toggleTabProj{{Auth::id()}}){
        $toggleOpenTab = sessionStorage.toggleTabProj{{Auth::id()}};
        $('#tab-'+$toggleOpenTab).addClass('active');
        $('#'+$toggleOpenTab).addClass('active');
    } else {
        sessionStorage.toggleTabProj{{Auth::id()}} = 'settings';
        $('#tab-settings').addClass('active');
        $('#settings').addClass('active');
    }

    $('#wordexec').datepicker().on('changeDate', function(e){
        $('#wordexec').datepicker('hide');
        $.post("/project/updateworkexecution", {
            date: e.date.toISOString(),
            project: {{ $project->id }}
        }, function(data){
            location.reload();
        });
    });

    $('#wordcompl').datepicker().on('changeDate', function(e){
        $('#wordcompl').datepicker('hide');
        $.post("/project/updateworkcompletion", {
            date: e.date.toISOString(),
            project: {{ $project->id }}
        }, function(data){
            location.reload();
        });
    });

    $("[name='tax_reverse']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'});
    $("[name='use_equipment']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'});
    $("[name='use_subcontract']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'});
    $("[name='use_estimate']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'});
    $("[name='use_more']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'});
    $("[name='use_less']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'});
    $("[name='mail_reminder']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'});
    $("[name='hide_null']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'});

    $("[name='hour_rate']").change(function() {
        if ($("[name='more_hour_rate']").val() == undefined || $("[name='more_hour_rate']").val() == '0,00')
            $("[name='more_hour_rate']").val($(this).val());
    });

    $('#btn-load-file').change(function() {
        $('#upload-file').submit();
    });
});
</script>

@if ($offer_last)
@if (CalculationEndresult::totalProject($project) != $offer_last->offer_total)
<div class="alert alert-warning">
    <i class="fa fa-fa fa-info-circle"></i> De invoergegevens zijn gewijzigd ten op zichte van de laatste offerte
</div>
@endif
@endif

<div class="tabs nomargin-top">

    <ul class="nav nav-tabs">
        <li id="tab-settings">
            <a href="#settings" data-toggle="tab"><i class="fa fa-info"></i> Projectgegevens</a>
        </li>

        @if ($type->type_name != 'snelle offerte en factuur')
        <li id="tab-options">
            <a href="#options" data-toggle="tab" data-toggle="tab"><i class="fa fa-sliders"></i> Extra opties</a>
        </li>

        <li id="tab-financial">
            <a href="#financial" data-toggle="tab"><i class="fa fa-percent"></i> Uurtarief en Winstpercentages</a>
        </li>

        <li id="tab-documents">
            <a href="#documents" data-toggle="tab"><i class="fa fa-cloud"></i> Documenten</a>
        </li>
        @endif

        @if ($share && $share->client_note)
        <li id="tab-communication">
            <a href="#communication" data-toggle="tab">Communicatie opdrachtgever </a>
        </li>
        @endif
    </ul>

    <div class="tab-content">

        <div id="settings" class="tab-pane">
            @include("component.{$page}.settings")
        </div>

        <div id="options" class="tab-pane">
            @include("component.{$page}.options")
        </div>

        @if ($type->type_name != 'snelle offerte en factuur')
        <div id="financial" class="tab-pane">
            @include("component.{$page}.financial")
        </div>
        @endif

        <div id="documents" class="tab-pane">
            @include("component.{$page}.documents")
        </div>

        @if ($share && $share->client_note)
        <div id="communication" class="tab-pane">
            @include("component.{$page}.communication")
        </div>
        @endif

    </div>

</div>
@stop
