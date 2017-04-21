<?php

use BynqIO\CalculatieTool\Models\Offer;

?>

@if (empty($project))
<div class="wizard">
    <a href="/"> Dashboard</a>
    <a href="/project/new" class="current">Project</a>
    <span>...</span>
    <span>Factuur</span>
    <span>Resultaat</span>
</div>
@else

@if ($project->type->type_name == 'snelle offerte en factuur')

<?php
$show_all = false;
$offer_last = Offer::where('project_id',$project->id)->orderBy('created_at', 'desc')->first();
if ($offer_last && $offer_last->offer_finish)
    $show_all = true;
?>
<div class="wizard">
    <a href="/"> Dashboard</a>
    <a href="/project/{{ $project->id }}-{{ str_slug($project->project_name) }}/details" {!! $page=='project' ? 'class="current"' : '' !!} >Project</a>
    <a href="/blancrow/project-{{ $project->id }}" {!! $page=='calculation' ? 'class="current"' : '' !!} >Regels</a>
    <a href="/offerversions/project-{{ $project->id }}" {!! $page=='offer' ? 'class="current"' : '' !!} >Offerte</a>
    @if ($show_all)
    <a href="/invoice/project-{{ $project->id }}" {!! $page=='invoice' ? 'class="current"' : ''!!} >Factuur</a>
    @else
    <span>Factuur</span>
    @endif
</div>

@elseif ($project->type->type_name == 'regie')

<div class="wizard">
    <a href="/"> Dashboard</a>
    <a href="/project/{{ $project->id }}-{{ str_slug($project->project_name) }}/details" {!! $page=='project' ? 'class="current"' : '' !!} >Project</a>
    <a href="/more/project-{{ $project->id }}" {!! $page=='more' ? 'class="current"' : ''!!} >Regiewerk</a>
    <a href="/invoice/project-{{ $project->id }}" {!! $page=='invoice' ? 'class="current"' : ''!!} >Factuur</a>
</div>

@else

<?php
$show_all = false;
$offer_last = Offer::where('project_id','=',$project->id)->orderBy('created_at', 'desc')->first();
if ($offer_last && $offer_last->offer_finish)
    $show_all = true;
?>

<div class="wizard">
    <a href="/"> Dashboard</a>
    <a href="/project/{{ $project->id }}-{{ str_slug($project->project_name) }}/details" {!! $page=='project' ? 'class="current"' : '' !!} >Project</a>
    <a href="/calculation/project-{{ $project->id }}" {!! $page=='calculation' ? 'class="current"' : '' !!} >Calculeren</a>
    <a href="/offerversions/project-{{ $project->id }}" {!! $page=='offer' ? 'class="current"' : '' !!} >Offerte</a>
    @if($show_all)
    @if ($project->use_estimate)
    <a href="/estimate/project-{{ $project->id }}" {!! $page=='estimate' ? 'class="current"' : '' !!}>Stelposten stellen</a>
    @endif
    @if ($project->use_less)
    <a href="/less/project-{{ $project->id }}" {!! $page=='less' ? 'class="current"' : '' !!} >Minderwerk</a>
    @endif
    @if ($project->use_more)
    <a href="/more/project-{{ $project->id }}" {!! $page=='more' ? 'class="current"' : ''!!} >Meerwerk</a>
    @endif
    <a href="/invoice/project-{{ $project->id }}" {!! $page=='invoice' ? 'class="current"' : ''!!} >Factuur</a>
    <a href="/result/project-{{ $project->id }}" {!! $page=='result' ? 'class="current"' : '' !!} >Resultaat</a>
    @else
    @if ($project->use_estimate)
    <span>Stelposten stellen</span>
    @endif
    @if ($project->use_less)
    <span>Minderwerk</span>
    @endif
    @if ($project->use_more)
    <span>Meerwerk</span>
    @endif
    <span>Factuur</span>
    <span>Resultaat</span>
    @endif
</div>
@endif

@endif
