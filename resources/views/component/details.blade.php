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
<script src="/plugins/summernote/summernote.min.js"></script>
@endpush

@section('component_content')
<script type="text/javascript">
$(document).ready(function() {
    $('#tab-project').click(function(e){
        sessionStorage.toggleTabProj{{Auth::id()}} = 'project';
    });
    $('#tab-calc').click(function(e){
        sessionStorage.toggleTabProj{{Auth::id()}} = 'calc';
    });
    $('#tab-doc').click(function(e){
        sessionStorage.toggleTabProj{{Auth::id()}} = 'doc';
    });
    $('#tab-advanced').click(function(e){
        sessionStorage.toggleTabProj{{Auth::id()}} = 'advanced';
    });
    $('#tab-communication').click(function(e){
        sessionStorage.toggleTabProj{{Auth::id()}} = 'communication';
    });
    if (sessionStorage.toggleTabProj{{Auth::id()}}){
        $toggleOpenTab = sessionStorage.toggleTabProj{{Auth::id()}};
        $('#tab-'+$toggleOpenTab).addClass('active');
        $('#'+$toggleOpenTab).addClass('active');
    } else {
        sessionStorage.toggleTabProj{{Auth::id()}} = 'project';
        $('#tab-project').addClass('active');
        $('#project').addClass('active');
    }
    $('#projclose').datepicker().on('changeDate', function(e){
        $('#projclose').datepicker('hide');
        if(confirm('Project sluiten?')){
            $.post("/project/updateprojectclose", {
                date: e.date.toISOString(),
                project: {{ $project->id }}
            }, function(data){
                location.reload();
            });
        }
    });
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

    $('.summernote').summernote({
        height: $(this).attr("data-height") || 200,
        toolbar: [
        ["style", ["bold", "italic", "underline", "strikethrough", "clear"]],
        ["para", ["ul", "ol", "paragraph"]],
        ["table", ["table"]],
        ["media", ["link", "picture", "video"]],
        ]
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
        <li id="tab-project">
            <a href="#project" data-toggle="tab"><i class="fa fa-info"></i> Projectgegevens</a>
        </li>
        @if ($type->type_name != 'snelle offerte en factuur')
        <li id="tab-advanced">
            <a href="#advanced" data-toggle="tab" data-toggle="tab"><i class="fa fa-sliders"></i> Extra opties</a>
        </li>
        <li id="tab-calc">
            <a href="#calc" data-toggle="tab"><i class="fa fa-percent"></i> Uurtarief en Winstpercentages</a>
        </li>
        <li id="tab-doc">
            <a href="#doc" data-toggle="tab"><i class="fa fa-cloud"></i> Documenten</a>
        </li>
        @endif
        @if ($share && $share->client_note)
        <li id="tab-communication">
            <a href="#communication" data-toggle="tab">Communicatie opdrachtgever </a>
        </li>
        @endif
    </ul>

    <div class="tab-content">

        <div class="pull-right">
            <a href="javascript:void(0);" data-toggle="modal" data-target="#notepad" class="btn btn-primary"><i class="fa fa-file-text-o"></i>&nbsp;Kladblok</a>
            <div class="btn-group" role="group">	
                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Acties&nbsp;&nbsp;<span class="caret"></span></button>
                <ul class="dropdown-menu">
                    <li><a href="/project/{{ $project->id }}-{{ str_slug($project->project_name) }}/printoverview" target="new"><i class="fa fa-file-pdf-o"></i>&nbsp;Projectoverzicht</a></i>
                    <li><a href="/project/{{ $project->id }}-{{ str_slug($project->project_name) }}/packlist" target="new"><i class="fa fa-file-pdf-o"></i>&nbsp;Raaplijst</a></i>
                    <li><a href="/project/{{ $project->id }}-{{ str_slug($project->project_name) }}/copy"><i class="fa fa-copy"></i>&nbsp;Project kopieren</a></i>
                    @if (!$project->project_close)
                    <li><a href="#" id="projclose"><i class="fa fa-close"></i>&nbsp;Project sluiten</a></li>
                    @else
                    <li><a href="/project/{{ $project->id }}-{{ str_slug($project->project_name) }}/cancel" onclick="return confirm('Project laten vervallen?')"><i class="fa fa-times"></i>&nbsp;Project vervallen</a></li>
                    @endif
                </ul>
            </div>
        </div>

        <div id="project" class="tab-pane">
            @include('project.details')
        </div>

        @if ($type->type_name != 'snelle offerte en factuur')
        <div id="calc" class="tab-pane">
            <form method="post" action="/project/updatecalc">
                {!! csrf_field() !!}
                <input type="hidden" name="id" id="id" value="{{ $project->id }}"/>
                <div class="row">
                    <div class="col-md-3"><h5><strong>Eigen uurtarief <a data-toggle="tooltip" data-placement="bottom" data-original-title="Geef hier uw uurtarief op wat door heel de calculatie gebruikt wordt voor dit project. Of stel deze in bij Voorkeuren om bij elk project te kunnen gebruiken." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></strong></h5></div>
                    <div class="col-md-1"></div>
                    @if ($type->type_name != 'regie')
                    <div class="col-md-2"><h5><strong>Calculatie</strong></h5></div>
                    <div class="col-md-2"><h5><strong>Meerwerk</strong></h5></div>
                    @endif
                </div>
                <div class="row">
                    <div class="col-md-3"><label for="hour_rate">Uurtarief excl. BTW</label></div>
                    <div class="col-md-1"><div class="pull-right">&euro;</div></div>
                    @if ($type->type_name != 'regie')
                    <div class="col-md-2">
                        <input name="hour_rate" {{ $project->project_close ? 'disabled' : ($offer_last && $offer_last->offer_finish ? 'disabled' : '') }} type="text" value="{{ old('hour_rate') ? old('hour_rate') : number_format($project->hour_rate, 2,",",".") }}" class="form-control form-control-sm-number"/>
                    </div>
                    @endif
                    <div class="col-md-2">
                        <input name="more_hour_rate" {{ $project->project_close ? 'disabled' : ($cntinv ? 'disabled' : '') }} id="more_hour_rate" type="text" value="{{ old('more_hour_rate') ? old('more_hour_rate') : number_format($project->hour_rate_more, 2,",",".") }}" class="form-control form-control-sm-number"/>
                    </div>
                </div>

                <h5><strong>Aanneming <a data-toggle="tooltip" data-placement="bottom" data-original-title="Geef hier uw winstpercentage op wat u over uw materiaal en overig wilt gaan rekenen. Of stel deze in bij Voorkeuren om bij elk project te kunnen gebruiken." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></strong></h5></strong></h5>
                <div class="row">
                    <div class="col-md-3"><label for="profit_material_1">Winstpercentage materiaal</label></div>
                    <div class="col-md-1"><div class="pull-right">%</div></div>
                    @if ($type->type_name != 'regie')
                    <div class="col-md-2">
                        <input name="profit_material_1" {{ $project->project_close ? 'disabled' : ($offer_last && $offer_last->offer_finish ? 'disabled' : '') }} id="profit_material_1" type="number" min="0" max="200" value="{{ old('profit_material_1') ? old('profit_material_1') : $project->profit_calc_contr_mat }}" class="form-control form-control-sm-number"/>
                    </div>
                    @endif
                    <div class="col-md-2">
                        <input name="more_profit_material_1" {{ $project->project_close ? 'disabled' : ($cntinv ? 'disabled' : '') }} id="more_profit_material_1" type="number" min="0" max="200" value="{{ old('more_profit_material_1') ? old('more_profit_material_1') : $project->profit_more_contr_mat }}" class="form-control form-control-sm-number"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3"><label for="profit_equipment_1">Winstpercentage overig</label></div>
                    <div class="col-md-1"><div class="pull-right">%</div></div>
                    @if ($type->type_name != 'regie')
                    <div class="col-md-2">
                        <input name="profit_equipment_1" {{ $project->project_close ? 'disabled' : ($offer_last && $offer_last->offer_finish ? 'disabled' : '') }} id="profit_equipment_1" type="number" min="0" max="200" value="{{ old('profit_equipment_1') ? old('profit_equipment_1') : $project->profit_calc_contr_equip }}" class="form-control form-control-sm-number"/>
                    </div>
                    @endif
                    <div class="col-md-2">
                        <input name="more_profit_equipment_1" {{ $project->project_close ? 'disabled' : ($cntinv ? 'disabled' : '') }} id="more_profit_equipment_1" type="number" min="0" max="200" value="{{ old('more_profit_equipment_1') ? old('more_profit_equipment_1') : $project->profit_more_contr_equip }}" class="form-control form-control-sm-number"/>
                    </div>
                </div>

                <h5><strong>Onderaanneming <a data-toggle="tooltip" data-placement="bottom" data-original-title="Onderaanneming: Geef hier uw winstpercentage op wat u over het materiaal en overig van uw onderaanneming wilt gaan rekenen. Of stel deze in bij Voorkeuren om bij elk project te kunnen gebruiken." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></strong></h5></strong></h5>
                <div class="row">
                    <div class="col-md-3"><label for="profit_material_2">Winstpercentage materiaal</label></div>
                    <div class="col-md-1"><div class="pull-right">%</div></div>
                    @if ($type->type_name != 'regie')
                    <div class="col-md-2">
                        <input name="profit_material_2" {{ $project->project_close ? 'disabled' : ($offer_last && $offer_last->offer_finish ? 'disabled' : '') }} id="profit_material_2" type="number" min="0" max="200" value="{{ old('profit_material_2') ? old('profit_material_2') : $project->profit_calc_subcontr_mat }}" class="form-control form-control-sm-number"/>
                    </div>
                    @endif
                    <div class="col-md-2">
                        <input name="more_profit_material_2" {{ $project->project_close ? 'disabled' : ($cntinv ? 'disabled' : '') }} id="more_profit_material_2" type="number" min="0" max="200" value="{{ old('more_profit_material_2') ? old('more_profit_material_2') : $project->profit_more_subcontr_mat }}" class="form-control form-control-sm-number"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3"><label for="profit_equipment_2">Winstpercentage overig</label></div>
                    <div class="col-md-1"><div class="pull-right">%</div></div>
                    @if ($type->type_name != 'regie')
                    <div class="col-md-2">
                        <input name="profit_equipment_2" {{ $project->project_close ? 'disabled' : ($offer_last && $offer_last->offer_finish ? 'disabled' : '') }} id="profit_equipment_2" type="number" min="0" max="200" value="{{ old('profit_equipment_2') ? old('profit_equipment_2') : $project->profit_calc_subcontr_equip }}" class="form-control form-control-sm-number"/>
                    </div>
                    @endif
                    <div class="col-md-2">
                        <input name="more_profit_equipment_2" {{ $project->project_close ? 'disabled' : ($cntinv ? 'disabled' : '') }} id="more_profit_equipment_2" type="number" min="0" max="200" value="{{ old('more_profit_equipment_2') ? old('more_profit_equipment_2') : $project->profit_more_subcontr_equip }}" class="form-control form-control-sm-number"/>
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class="col-md-12">
                        @if (!$project->project_close)
                        <button class="btn btn-primary"><i class="fa fa-check"></i> Opslaan</button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
        @endif

            <div id="advanced" class="tab-pane">

                <form method="POST" action="/project/updateoptions">
                    {!! csrf_field() !!}
                    <input type="hidden" name="id" id="id" value="{{ $project->id }}"/>

                    @if ($type->type_name != 'regie')
                    <div class="row">
                        <div class="col-md-6">	
                            <div class="col-md-3">
                                <label for="type"><b>BTW verlegd</b></label>
                                <div class="form-group">
                                    <input name="tax_reverse" disabled type="checkbox" {{ $project->tax_reverse ? 'checked' : '' }}>
                                </div>
                            </div>
                            <div class="col-md-9" style="padding-top:30px;">
                                <p>Een project zonder btw bedrag invoeren.</p>
                                <ul>
                                    <li>Kan na aanmaken project niet ongedaan gemaakt worden</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">	
                            <div class="col-md-3">
                                <label for="type"><b>Stelposten</b></label>
                                <div class="form-group">
                                    <input name="use_estimate" {{ $project->project_close ? 'disabled' : ($disable_estim ? 'disabled' : '') }} type="checkbox" {{ $project->use_estimate ? 'checked' : '' }}>
                                </div>
                            </div>
                            <div class="col-md-9"  style="padding-top:30px;">		
                                <p>Voeg stelposten toe aan je calculatie.</p>
                                <ul>
                                    <li>Definitief te maken voor factuur na opdracht</li>
                                    <li>Uit te zetten indien ongebruikt</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <hr>
                    @endif
                    <div class="row">
                        <div class="col-md-6">
                            <div class="col-md-3">
                                <label for="type"><b>Onderaanneming</b></label>
                                <div class="form-group">
                                    <input name="use_subcontract" type="checkbox" {{ $project->project_close ? 'disabled' : ($project->use_subcontract ? 'disabled' : '') }} {{ $project->use_subcontract ? 'checked' : '' }}>
                                </div>
                            </div>
                            <div class="col-md-9"  style="padding-top:30px;">
                                <p>Voeg onderaanneming toe aan je {{ $type->type_name == 'regie' ? 'regiewerk' : 'calculatie' }}.</p>
                                <ul>
                                    <li>Kan na toevoegen niet ongedaan gemaakt worden</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-3">
                                <label for="type"><b>Overige</b></label>
                                <div class="form-group">
                                    <input name="use_equipment" type="checkbox" {{ $project->project_close ? 'disabled' : ($project->use_equipment ? 'disabled' : '') }} {{ $project->use_equipment ? 'checked' : '' }}>
                                </div>
                            </div>
                            <div class="col-md-9" style="padding-top:30px;">
                                <p>Voeg naast arbeid en materiaal een extra niveau toe aan je {{ $type->type_name == 'regie' ? 'regiewerk' : 'calculatie' }}.</p>
                                <ul>
                                    <li>Bijvoorbeeld voor <i>materieel</i></li>
                                    <li>Kan na toevoegen niet ongedaan gemaakt worden</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    @if ($type->type_name != 'regie')
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="col-md-3">
                                <label for="type"><b>Meerwerk</b></label>
                                <div class="form-group">
                                    <input name="use_more" type="checkbox" {{ $project->project_close ? 'disabled' : ($disable_more ? 'disabled' : '') }} {{ $project->use_more ? 'checked' : '' }}>
                                </div>
                            </div>
                            <div class="col-md-9" style="padding-top:30px;">
                                <p>Voeg meerwerk toe aan je project.</p>
                                <ul>
                                    <li>Pas invulbaar na opdracht</li>
                                    <li>Uit te zetten indien ongebruikt</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-3">
                                <label for="type"><b>Minderwerk</b></label>
                                <div class="form-group">
                                    <input name="use_less" type="checkbox" {{ $project->project_close ? 'disabled' : ($disable_less ? 'disabled' : '') }} {{ $project->use_less ? 'checked' : '' }}>
                                </div>
                            </div>
                            <div class="col-md-9" style="padding-top:30px;">
                                <p>Voeg minderwerk toe aan je prpject.</p>
                                <ul>
                                    <li>Pas invulbaar na opdracht</li>
                                    <li>Uit te zetten indien ongebruikt</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif

                    <br/>
                    <div class="row">
                        <div class="col-md-12">
                            @if (!$project->project_close)
                            <button class="btn btn-primary"><i class="fa fa-check"></i> Opslaan</button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <div id="communication" class="tab-pane">
                <div class="form-group">
                    <div class="col-md-9">
                        <form method="POST" action="/project/update/communication" accept-charset="UTF-8">
                            {!! csrf_field() !!}
                            <input type="hidden" name="project" value="{{ $project->id }}"/>

                            <h5><strong>Vraag opmerkingen van je opdrachtgever </strong><a data-toggle="tooltip" data-placement="bottom" data-original-title="Alleen mogelijk wanneer een offerte verzonden is per e-mail op de offerte pagina." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></h5>
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <div class="white-row well">
                                            {!!  $share ? $share->client_note : ''!!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h5><strong>Jouw reactie</strong></h5>
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <textarea name="user_note" id="user_note" rows="10" class="summernote form-control">{{ $share ? $share->user_note : ''}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-primary"><i class="fa fa-check"></i> Verzenden</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-3">
                        <div class="row">
                            <h5><strong>Gegevens van uw relatie</strong></h5>
                        </div>
                        <div class="row">
                            <label>Opdrachtgever </label>
                            <?php $relation = Relation::find($project->client_id); ?>
                            @if (!$relation->isActive())
                            <span> {{ RelationKind::find($relation->kind_id)->kind_name == 'zakelijk' ? ucwords($relation->company_name) : (Contact::where('relation_id','=',$relation->id)->first()['firstname'].' '.Contact::where('relation_id','=',$relation->id)->first()['lastname']) }}</span>
                            @else
                            <span> {{ RelationKind::find($relation->kind_id)->kind_name == 'zakelijk' ? ucwords($relation->company_name) : (Contact::where('relation_id','=',$relation->id)->first()['firstname'].' '.Contact::where('relation_id','=',$relation->id)->first()['lastname']) }}</span>
                            @endif
                        </div>
                        <div class="row">
                            <label for="name">Straat</label>
                            <span>{{ $relation->address_street }} {{ $relation->address_number }}</span>
                        </div>
                        <div class="row">
                            <label for="name">Postcode</label>
                            <span>{{ $relation->address_postal }}</span>
                        </div>
                        <div class="row">
                            <label for="name">Plaats</label>
                            <span>{{ $relation->address_city }}</span>
                        </div>

                        <?php
                        $contact=Contact::where('relation_id',$relation->id)->first();
                        ?>
                        <div class="row">
                            <label for="name">Contactpersoon</label>
                            <span>{{ $contact->getFormalName() }}</span>
                        </div>
                        <div class="row">
                            <label for="name">Telefoon</label>
                            <span>{{ $contact->mobile }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div id="doc" class="tab-pane">

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
                            <div class="cart_img" style="width:45px;"><a href="/res-{{ $file->id }}/download"><i class="fa {{ $file->fa_icon() }} fsize20"></i></a></div>
                            <a href="/res-{{ $file->id }}/download" class="product_name">{{ $file->resource_name }}</a>
                            @if (!$project->project_close)
                            <a href="/res-{{ $file->id }}/delete" class="btn btn-danger btn-xs" style="float: right;margin: 10px;">Verwijderen</a>
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

            </div>
        </div>
    </div>

</div>
@stop
