<div class="wizard">
    <a href="/"> Dashboard</a>
    <a href="/project/{{ $project->id }}-{{ str_slug($project->project_name) }}/details" {!! $page=='project' ? 'class="current"' : '' !!} >Project</a>
    <a href="/more/project-{{ $project->id }}" {!! $page=='more' ? 'class="current"' : ''!!} >Regiewerk</a>
    <a href="/invoice/project-{{ $project->id }}" {!! $page=='invoice' ? 'class="current"' : ''!!} >Factuur</a>
</div>
