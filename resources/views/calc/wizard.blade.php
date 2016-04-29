<?php

use \Calctool\Models\ProjectType;
use \Calctool\Models\Offer;

if (empty($project)) {
?>

<div class="wizard">
	<a href="/"> Dashboard</a>
	<a href="/project/new" class="current">Project</a>
	<span>Project</span>
	<span>Calculeren</span>
	<span>Offerte</span>
	<span>Stelposten stellen</span>
	<span>Minderwerk</span>
	<span>Meerwerk</span>
	<span>Factuur</span>
	<span>Resultaat</span>
</div>

<?php
} else {

$type = ProjectType::find($project->type_id);
if ($type->type_name == 'snelle offerte en factuur') {
$show_all = false;
$offer_last = Offer::where('project_id','=',$project->id)->orderBy('created_at', 'desc')->first();
if ($offer_last && $offer_last->offer_finish)
	$show_all = true;
?>

<div class="wizard">
	<a href="/"> Dashboard</a>
	<a href="/project-{{ $project->id }}/edit" {!! $page=='project' ? 'class="current"' : '' !!} >Project</a>
	<a href="/blancrow/project-{{ $project->id }}" {!! $page=='calculation' ? 'class="current"' : '' !!} >Regels</a>
	<a href="/offerversions/project-{{ $project->id }}" {!! $page=='offer' ? 'class="current"' : '' !!} >Offerte</a>
	@if($show_all)
	<a href="/invoice/project-{{ $project->id }}" {!! $page=='invoice' ? 'class="current"' : ''!!} >Factuur</a>
	@else
	<span>Factuur</span>
	@endif
</div>

<?php
} else if ($type->type_name == 'regie') {
?>

<div class="wizard">
	<a href="/"> Dashboard</a>
	<a href="/project-{{ $project->id }}/edit" {!! $page=='project' ? 'class="current"' : '' !!} >Project</a>
	<a href="/more/project-{{ $project->id }}" {!! $page=='more' ? 'class="current"' : ''!!} >Regie</a>
	<a href="/invoice/project-{{ $project->id }}" {!! $page=='invoice' ? 'class="current"' : ''!!} >Factuur</a>
</div>

<?php
} else{

$show_all = false;
$offer_last = Offer::where('project_id','=',$project->id)->orderBy('created_at', 'desc')->first();
if ($offer_last && $offer_last->offer_finish)
	$show_all = true;

?>

<div class="wizard">
	<a href="/"> Dashboard</a>
	<a href="/project-{{ $project->id }}/edit" {!! $page=='project' ? 'class="current"' : '' !!} >Project</a>
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

<?php } } ?>
