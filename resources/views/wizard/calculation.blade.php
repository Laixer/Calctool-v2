<?php

use BynqIO\Dynq\Models\Offer;

?>

<?php
$show_all = false;
$offer_last = Offer::where('project_id', $project->id)->orderBy('created_at', 'desc')->first();
if ($offer_last && $offer_last->offer_finish)
    $show_all = true;
?>

<div class="wizard">
    <a href="/">Dashboard</a>
    <a href="/project/{{ $project->id }}-{{ $project->slug() }}/details"     {!! starts_with($page, 'details') ? 'class="current"' : '' !!} >Project</a>
    <a href="/project/{{ $project->id }}-{{ $project->slug() }}/calculation" {!! starts_with($page, 'calculation') ? 'class="current"' : '' !!} >Calculeren</a>
    <a href="/project/{{ $project->id }}-{{ $project->slug() }}/quotations"  {!! starts_with($page, 'quotations') ? 'class="current"' : '' !!} >Offerte</a>
    @if ($show_all)
    @if ($project->use_estimate)
    <a href="/project/{{ $project->id }}-{{ $project->slug() }}/estimate"    {!! starts_with($page, 'estimate') ? 'class="current"' : '' !!}>Stelposten stellen</a>
    @endif
    @if ($project->use_less)
    <a href="/project/{{ $project->id }}-{{ $project->slug() }}/less"        {!! starts_with($page, 'less') ? 'class="current"' : '' !!} >Minderwerk</a>
    @endif
    @if ($project->use_more)
    <a href="/project/{{ $project->id }}-{{ $project->slug() }}/more"        {!! starts_with($page, 'more') ? 'class="current"' : ''!!} >Meerwerk</a>
    @endif
    <a href="/project/{{ $project->id }}-{{ $project->slug() }}/invoices"    {!! starts_with($page, 'invoice') ? 'class="current"' : ''!!} >Factuur</a>
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
    @endif
    <a href="/project/{{ $project->id }}-{{ $project->slug() }}/result"      {!! starts_with($page, 'result') ? 'class="current"' : '' !!} >Resultaat</a>
</div>
