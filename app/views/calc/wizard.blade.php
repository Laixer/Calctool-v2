<div class="wizard">
	<a href="/"> Home</a>
	<a href="/project-{{ $project->id }}/edit" {{ $page=='project' ? 'class="current"' : '' }} >Project</a>
	<a href="/calculation/project-{{ $project->id }}">Calculatie</a>
	<a href="/offer/project-{{ $project->id }}">Offerte</a>
	<a href="/estimate/project-{{ $project->id }}">Stelpst stelle</a>
	<a href="/less/project-{{ $project->id }}">Minderwerk</a>
	<a href="/more/project-{{ $project->id }}">Meerwerk</a>
	<a href="/invoice/project-{{ $project->id }}">Factuur</a>
	<a href="/result/project-{{ $project->id }}">Resultaat</a>
</div>
<hr />

