<?php

use BynqIO\CalculatieTool\Models\Offer;

?>

<?php
$show_all = false;
$offer_last = Offer::where('project_id','=',$project->id)->orderBy('created_at', 'desc')->first();
if ($offer_last && $offer_last->offer_finish)
    $show_all = true;
?>

<div class="wizard">
    <a href="/"> Dashboard</a>
    <a href="/project/{{ $project->id }}-{{ str_slug($project->project_name) }}/details" {!! $page=='details' ? 'class="current"' : '' !!} >Project</a>
    <a href="/project/{{ $project->id }}-{{ str_slug($project->project_name) }}/calculation" {!! $page=='calculation' ? 'class="current"' : '' !!} >Calculeren</a>
    <a href="/project/{{ $project->id }}-{{ str_slug($project->project_name) }}/quotations" {!! $page=='offer' ? 'class="current"' : '' !!} >Offerte</a>
    @if ($show_all)
    @if ($project->use_estimate)
    <a href="/project/{{ $project->id }}-{{ str_slug($project->project_name) }}/estimate" {!! $page=='estimate' ? 'class="current"' : '' !!}>Stelposten stellen</a>
    @endif
    @if ($project->use_less)
    <a href="/project/{{ $project->id }}-{{ str_slug($project->project_name) }}/less" {!! $page=='less' ? 'class="current"' : '' !!} >Minderwerk</a>
    @endif
    @if ($project->use_more)
    <a href="/project/{{ $project->id }}-{{ str_slug($project->project_name) }}/more" {!! $page=='more' ? 'class="current"' : ''!!} >Meerwerk</a>
    @endif
    <a href="/invoice/project-{{ $project->id }}" {!! $page=='invoice' ? 'class="current"' : ''!!} >Factuur</a>
    <a href="/project/{{ $project->id }}-{{ str_slug($project->project_name) }}/result" {!! $page=='result' ? 'class="current"' : '' !!} >Resultaat</a>
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
