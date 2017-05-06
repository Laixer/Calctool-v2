<?php

use BynqIO\Dynq\Models\Offer;
use BynqIO\Dynq\Models\Invoice;
use BynqIO\Dynq\Models\Chapter;
use BynqIO\Dynq\Models\Activity;

use BynqIO\Dynq\Models\EstimateLabor;
use BynqIO\Dynq\Models\EstimateMaterial;
use BynqIO\Dynq\Models\EstimateEquipment;
use BynqIO\Dynq\Models\MoreLabor;
use BynqIO\Dynq\Models\MoreMaterial;
use BynqIO\Dynq\Models\MoreEquipment;
use BynqIO\Dynq\Models\CalculationLabor;
use BynqIO\Dynq\Models\CalculationMaterial;
use BynqIO\Dynq\Models\CalculationEquipment;

$offer_last = Offer::where('project_id',$project->id)->orderBy('created_at', 'desc')->first();
if ($offer_last)
    $cntinv = Invoice::where('offer_id',$offer_last->id)->where('invoice_close',true)->count('id');
else
    $cntinv = 0;

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

@push('style')
<link media="all" type="text/css" rel="stylesheet" href="/plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css">
@endpush

@push('scripts')
<script src="/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
@endpush

@push('jsinline')
<script type="text/javascript">
$(document).ready(function() {
    $("[name='tax_reverse']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'});
    $("[name='use_equipment']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'});
    $("[name='use_subcontract']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'});
    $("[name='use_estimate']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'});
    $("[name='use_more']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'});
    $("[name='use_less']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'});
    $("[name='mail_reminder']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'});
    $("[name='hide_null']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'});
});
</script>
@endpush

<form method="POST" action="/project/updateoptions">
    {!! csrf_field() !!}
    <input type="hidden" name="id" id="id" value="{{ $project->id }}"/>

    @if ($type != 'directwork')
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
                <p>Voeg onderaanneming toe aan je {{ $type == 'directwork' ? 'regiewerk' : 'calculatie' }}.</p>
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
                <p>Voeg naast arbeid en materiaal een extra niveau toe aan je {{ $type == 'directwork' ? 'regiewerk' : 'calculatie' }}.</p>
                <ul>
                    <li>Bijvoorbeeld voor <i>materieel</i></li>
                    <li>Kan na toevoegen niet ongedaan gemaakt worden</li>
                </ul>
            </div>
        </div>
    </div>
    @if ($type != 'directwork')
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