<div class="wizard">
	<a href="/"> Home</a>
	<a href="/project-{{ $project->id }}/edit" {{ $page=='project' ? 'class="current"' : '' }} >Project</a>
	<a href="/calculation/project-{{ $project->id }}" {{ $page=='calculation' ? 'class="current"' : '' }} >Calculatie</a>
	<a href="/offer/project-{{ $project->id }}" {{ $page=='offer' ? 'class="current"' : '' }}>Offerte</a>
	<a href="/estimate/project-{{ $project->id }}" {{ $page=='estimate' ? 'class="current"' : '' }} >Stelpst stelle</a>
	<a href="/less/project-{{ $project->id }}" {{ $page=='less' ? 'class="current"' : '' }} >Minderwerk</a>
	<a href="/more/project-{{ $project->id }}" {{ $page=='more' ? 'class="current"' : '' }} >Meerwerk</a>
	<a href="/invoice/project-{{ $project->id }}" {{ $page=='invoice' ? 'class="current"' : '' }} >Factuur</a>
	<a href="/result/project-{{ $project->id }}" {{ $page=='result' ? 'class="current"' : '' }} >Resultaat</a>
</div>
<hr />

