<?php

use BynqIO\Dynq\Models\Relation;
use BynqIO\Dynq\Models\Contact;
use BynqIO\Dynq\Models\RelationKind;

?>
<?php

use BynqIO\Dynq\Models\Offer;
use BynqIO\Dynq\Models\Invoice;

$offer_last = Offer::where('project_id',$project->id)->orderBy('created_at', 'desc')->first();
?>

@inject('province', 'BynqIO\Dynq\Models\Province')
@inject('country', 'BynqIO\Dynq\Models\Country')
@inject('carbon', 'Carbon\Carbon')

@push('style')
<link media="all" type="text/css" rel="stylesheet" href="/components/x-editable/dist/bootstrap3-editable/css/bootstrap-editable.css">
@endpush

@push('scripts')
<script src="/components/x-editable/dist/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
@endpush

@if(0)
@push('jsinline')
<script type="text/javascript">
$(document).ready(function() {
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
});
</script>
@endpush
@endif

<section class="paddings">
    <div class="row text-center countTo">
        <div class="col-md-4">
            <strong data-to="1244">{{ $project->created_at->diffInDays($project->updated_at) }}</strong>
            <label>Dagen geopend</label>
        </div>
        <div class="col-md-4">
            <strong data-to="67">4/5</strong>
            <label>Afgerond</label>
        </div>
        <div class="col-md-4">
            <strong data-to="32">1</strong>
            <label>Gebruiker</label>
        </div>
    </div>
</section>

<div style="overflow:overlay;">
    <div class="col-md-6">

        <div class="row">
            <div class="col-md-12"><h3>Projectdetails</h3></div>
        </div>
        <div class="row">
            <div class="col-md-4">Gestart</div>
            <div class="col-md-3">{{ $project->created_at->toFormattedDateString() }}</div>
        </div>
        <div class="row">
            <div class="col-md-4">Laatste wijziging</div>
            <div class="col-md-3">{{ $project->updated_at->toFormattedDateString() }}</div>
        </div>
        <div class="row">
            <div class="col-md-4">Gesloten</div>
            <div class="col-md-4">{{ $project->project_close ? $carbon::parse($project->project_close)->toFormattedDateString() : '-' }}</div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="row">
            <div class="col-md-12"><h3>Projectstadium</h3></div>
        </div>

        @if (0)
        <div class="row">
            <div class="col-md-4">Start uitvoering <a data-toggle="tooltip" data-placement="bottom" data-original-title="Vul hier de datum in dat je met uitvoering bent begonnen" href="#"><i class="fa fa-info-circle"></i></a></div>
            <div class="col-md-4"><?php if ($project->project_close) { echo $project->work_execution ? date('d-m-Y', strtotime($project->work_execution)) : ''; }else{ if ($project->work_execution){ echo date('d-m-Y', strtotime($project->work_execution)); }else{ ?><a href="#" id="wordexec">Bewerk</a><?php } } ?></div>
            <div class="col-md-4"></div>
        </div>
        <div class="row">
            <div class="col-md-4">Opleverdatum <a data-toggle="tooltip" data-placement="bottom" data-original-title="Vul hier de datum in dat je het moet/wilt/verwacht opleveren" href="#"><i class="fa fa-info-circle"></i></a></div>
            <div class="col-md-4"><?php if ($project->project_close) { echo $project->work_completion ? date('d-m-Y', strtotime($project->work_completion)) : ''; }else{ if ($project->work_completion){ echo date('d-m-Y', strtotime($project->work_completion)); }else{ ?><a href="#" id="wordcompl">Bewerk</a><?php } } ?></div>
            <div class="col-md-4"><i class="fa fa-check" aria-hidden="true"></i></div>
        </div>
        @endif

        @if ($project->use_estimate)
        <div class="row">
            <div class="col-md-6"><i class="fa fa-chevron-right" aria-hidden="true"></i> Stelposten Stellen</div>
            <div class="col-md-6"><i class="fa fa-check" aria-hidden="true"></i> Afgerond op {{ $carbon::now()->toFormattedDateString() }}<i>{{ $project->start_estimate ? date('d-m-Y', strtotime($project->start_estimate)) : '' }}</i></div>
            <!--<div class="col-md-4"><i>{{ $project->update_estimate ? ''.date('d-m-Y', strtotime($project->update_estimate)) : '' }}</i></div>-->
        </div>
        @endif

        <div class="row">
            <div class="col-md-6"><i class="fa fa-chevron-right" aria-hidden="true"></i> Offerte</div>
            <div class="col-md-6"><?php if ($offer_last) { echo $offer_last->created_at->toFormattedDateString(); } else { echo '-'; } ?></div>
            <!--<div class="col-md-4"><i><?php if ($offer_last) { echo ''.date('d-m-Y', strtotime(DB::table('offer')->select('updated_at')->where('id','=',$offer_last->id)->get()[0]->updated_at)); } ?></i></div>-->
        </div>
        <div class="row">
            <div class="col-md-6"><i class="fa fa-chevron-right" aria-hidden="true"></i> Opdracht</div>
            <div class="col-md-6">{{ $offer_last && $offer_last->offer_finish ? date('d-m-Y', strtotime($offer_last->offer_finish)) : '-' }}</div>
        </div>

        @if ($project->use_more)
        <div class="row">
            <div class="col-md-6"><i class="fa fa-chevron-right" aria-hidden="true"></i> Meerwerk</div>
            <div class="col-md-6">{{ $project->start_more ? date('d-m-Y', strtotime($project->start_more)) : '-' }}</div>
            <!--<div class="col-md-4"><i>{{ $project->update_more ? ''.date('d-m-Y', strtotime($project->update_more)) : '' }}</i></div>-->
        </div>
        @endif
        @if ($project->use_less)
        <div class="row">
            <div class="col-md-6"><i class="fa fa-chevron-right" aria-hidden="true"></i> Minderwerk</div>
            <div class="col-md-6">{{ $project->start_less ? date('d-m-Y', strtotime($project->start_less)) : '-' }}</div>
            <!--<div class="col-md-4"><i>{{ $project->update_less ? ''.date('d-m-Y', strtotime($project->update_less)) : '' }}</i></div>-->
        </div>
        @endif
    </div>
</div>
