<form method="POST" action="/project/updateoptions">
    {!! csrf_field() !!}
    <input type="hidden" name="id" id="id" value="{{ $project->id }}"/>

    @if ($type->type_name != 'regie')
    <div class="row">
        <div class="col-md-6">	
            <div class="col-md-3">
                <label for="type"><b>BTW verlegd</b></label>
                <div class="form-group">
                    <input name="tax_reverse" disabled type="checkbox" {{ $project->tax_reverse ? 'checked' : '' }}>
                </div>
            </div>
            <div class="col-md-9" style="padding-top:30px;">
                <p>Een project zonder btw bedrag invoeren.</p>
                <ul>
                    <li>Kan na aanmaken project niet ongedaan gemaakt worden</li>
                </ul>
            </div>
        </div>
        <div class="col-md-6">	
            <div class="col-md-3">
                <label for="type"><b>Stelposten</b></label>
                <div class="form-group">
                    <input name="use_estimate" {{ $project->project_close ? 'disabled' : ($disable_estim ? 'disabled' : '') }} type="checkbox" {{ $project->use_estimate ? 'checked' : '' }}>
                </div>
            </div>
            <div class="col-md-9"  style="padding-top:30px;">		
                <p>Voeg stelposten toe aan je calculatie.</p>
                <ul>
                    <li>Definitief te maken voor factuur na opdracht</li>
                    <li>Uit te zetten indien ongebruikt</li>
                </ul>
            </div>
        </div>
    </div>
    <hr>
    @endif
    <div class="row">
        <div class="col-md-6">
            <div class="col-md-3">
                <label for="type"><b>Onderaanneming</b></label>
                <div class="form-group">
                    <input name="use_subcontract" type="checkbox" {{ $project->project_close ? 'disabled' : ($project->use_subcontract ? 'disabled' : '') }} {{ $project->use_subcontract ? 'checked' : '' }}>
                </div>
            </div>
            <div class="col-md-9"  style="padding-top:30px;">
                <p>Voeg onderaanneming toe aan je {{ $type->type_name == 'regie' ? 'regiewerk' : 'calculatie' }}.</p>
                <ul>
                    <li>Kan na toevoegen niet ongedaan gemaakt worden</li>
                </ul>
            </div>
        </div>
        <div class="col-md-6">
            <div class="col-md-3">
                <label for="type"><b>Overige</b></label>
                <div class="form-group">
                    <input name="use_equipment" type="checkbox" {{ $project->project_close ? 'disabled' : ($project->use_equipment ? 'disabled' : '') }} {{ $project->use_equipment ? 'checked' : '' }}>
                </div>
            </div>
            <div class="col-md-9" style="padding-top:30px;">
                <p>Voeg naast arbeid en materiaal een extra niveau toe aan je {{ $type->type_name == 'regie' ? 'regiewerk' : 'calculatie' }}.</p>
                <ul>
                    <li>Bijvoorbeeld voor <i>materieel</i></li>
                    <li>Kan na toevoegen niet ongedaan gemaakt worden</li>
                </ul>
            </div>
        </div>
    </div>
    @if ($type->type_name != 'regie')
    <hr>
    <div class="row">
        <div class="col-md-6">
            <div class="col-md-3">
                <label for="type"><b>Meerwerk</b></label>
                <div class="form-group">
                    <input name="use_more" type="checkbox" {{ $project->project_close ? 'disabled' : ($disable_more ? 'disabled' : '') }} {{ $project->use_more ? 'checked' : '' }}>
                </div>
            </div>
            <div class="col-md-9" style="padding-top:30px;">
                <p>Voeg meerwerk toe aan je project.</p>
                <ul>
                    <li>Pas invulbaar na opdracht</li>
                    <li>Uit te zetten indien ongebruikt</li>
                </ul>
            </div>
        </div>
        <div class="col-md-6">
            <div class="col-md-3">
                <label for="type"><b>Minderwerk</b></label>
                <div class="form-group">
                    <input name="use_less" type="checkbox" {{ $project->project_close ? 'disabled' : ($disable_less ? 'disabled' : '') }} {{ $project->use_less ? 'checked' : '' }}>
                </div>
            </div>
            <div class="col-md-9" style="padding-top:30px;">
                <p>Voeg minderwerk toe aan je prpject.</p>
                <ul>
                    <li>Pas invulbaar na opdracht</li>
                    <li>Uit te zetten indien ongebruikt</li>
                </ul>
            </div>
        </div>
    </div>
    @endif

    <br/>
    <div class="row">
        <div class="col-md-12">
            @if (!$project->project_close)
            <button class="btn btn-primary"><i class="fa fa-check"></i> Opslaan</button>
            @endif
        </div>
    </div>
</form>