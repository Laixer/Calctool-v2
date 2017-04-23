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
