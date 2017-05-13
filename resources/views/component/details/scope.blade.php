<div class="pull-right">
    @if (!$project->project_close)
    <a href="/project/close?id={{ $project->id }}&csrf={{ csrf_token() }}" class="btn btn-primary" onclick="return confirm('Project sluiten?')"><i class="fa fa-close"></i>&nbsp;Project sluiten</a>
    @endif
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="fa fa-ellipsis-h" aria-hidden="true"></span></button>
        <ul class="dropdown-menu">
            @if (0)
            <li><a data-toggle="modal" data-target="#notepad"><i class="fa fa-file-text-o"></i>&nbsp;Kladblok</a></li>
            @endif
            <li><a href="/project/{{ $project->id }}-{{ $project->slug() }}/printoverview" target="new"><i class="fa fa-file-pdf-o"></i>&nbsp;Projectoverzicht</a></i>
            <li><a href="/project/{{ $project->id }}-{{ $project->slug() }}/packlist" target="new"><i class="fa fa-file-pdf-o"></i>&nbsp;Raaplijst</a></i>
            <li><a href="/project/copy?id={{ $project->id }}&csrf={{ csrf_token() }}" onclick="return confirm('Project kopieren?')"><i class="fa fa-copy"></i>&nbsp;Project kopieren</a></i>
            @if ($project->project_close)
            <li><a href="/project/cancel?id={{ $project->id }}&csrf={{ csrf_token() }}" onclick="return confirm('Project laten vervallen?')"><i class="fa fa-times"></i>&nbsp;Project vervallen</a></li>
            @endif
        </ul>
    </div>
</div>
