<div class="pull-right">
    @if (!$project->project_close)
    <a href="/project/close?id={{ $project->id }}&csrf={{ csrf_token() }}" class="btn btn-primary" onclick="return confirm('Project sluiten?')"><i class="fa fa-close"></i>&nbsp;Project sluiten</a>
    @endif
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="fa fa-ellipsis-h" aria-hidden="true"></span></button>
        <ul class="dropdown-menu">
            @if (0)
            <li><a data-toggle="modal" data-target="#notepad">Kladblok</a></li>
            @endif
            <li><a href="/project/{{ $project->id }}-{{ $project->slug() }}/printoverview" target="new">Projectoverzicht</a></li>
            <li><a href="/project/{{ $project->id }}-{{ $project->slug() }}/packingslip" target="new">Pakbon maken</a></li>
            @if (Input::has('options'))
            <li><a href="/project/{{ $project->id }}-{{ $project->slug() }}/paper" target="new">Briefpapier</a></li>
            @endif
            <li class="divider" style="margin:5px 0;"></li>
            <li><a href="/project/copy?id={{ $project->id }}&csrf={{ csrf_token() }}" onclick="return confirm('Project kopieren?')">Project kopieren</a></li>
            @if ($project->project_close)
            <li><a href="/project/cancel?id={{ $project->id }}&csrf={{ csrf_token() }}" onclick="return confirm('Project laten vervallen? Na deze handeling zal het project niet meer zichtbaar zijn.')">Project vervallen</a></li>
            @endif
        </ul>
    </div>
</div>
