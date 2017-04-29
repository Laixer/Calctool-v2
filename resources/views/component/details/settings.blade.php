<?php

use BynqIO\CalculatieTool\Models\Relation;
use BynqIO\CalculatieTool\Models\Contact;
use BynqIO\CalculatieTool\Models\RelationKind;

?>
<?php

use BynqIO\CalculatieTool\Models\Offer;
use BynqIO\CalculatieTool\Models\Invoice;

$offer_last = Offer::where('project_id',$project->id)->orderBy('created_at', 'desc')->first();
?>

@inject('province', 'BynqIO\CalculatieTool\Models\Province')
@inject('country', 'BynqIO\CalculatieTool\Models\Country')

@push('style')
<link media="all" type="text/css" rel="stylesheet" href="/components/x-editable/dist/bootstrap3-editable/css/bootstrap-editable.css">
@endpush

@push('scripts')
<script src="/components/x-editable/dist/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
@endpush

@push('jsinline')
<script type="text/javascript">
$(document).ready(function() {
    $('#wordexec').datepicker().on('changeDate', function(e){
        $('#wordexec').datepicker('hide');
        $.post("/project/updateworkexecution", {
            date: e.date.toISOString(),
            project: {{ $project->id }}
        }, function(data){
            location.reload();
        });
    });

    $('#wordcompl').datepicker().on('changeDate', function(e){
        $('#wordcompl').datepicker('hide');
        $.post("/project/updateworkcompletion", {
            date: e.date.toISOString(),
            project: {{ $project->id }}
        }, function(data){
            location.reload();
        });
    });
});
</script>
@endpush

<div class="pull-right">
    <a data-toggle="modal" data-target="#notepad" class="btn btn-primary"><i class="fa fa-file-text-o"></i>&nbsp;Kladblok</a>
    <div class="btn-group" role="group">	
        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Acties&nbsp;&nbsp;<span class="caret"></span></button>
        <ul class="dropdown-menu">
            <li><a href="/project/{{ $project->id }}-{{ $project->slug() }}/printoverview" target="new"><i class="fa fa-file-pdf-o"></i>&nbsp;Projectoverzicht</a></i>
            <li><a href="/project/{{ $project->id }}-{{ $project->slug() }}/packlist" target="new"><i class="fa fa-file-pdf-o"></i>&nbsp;Raaplijst</a></i>
            <li><a href="/project/copy?id={{ $project->id }}&csrf={{ csrf_token() }}" onclick="return confirm('Project totaan offerte kopieren?')"><i class="fa fa-copy"></i>&nbsp;Project kopieren</a></i>
            @if (!$project->project_close)
            <li><a href="/project/close?id={{ $project->id }}&csrf={{ csrf_token() }}" onclick="return confirm('Project sluiten?')"><i class="fa fa-close"></i>&nbsp;Project sluiten</a></li>
            @else
            <li><a href="/project/cancel?id={{ $project->id }}&csrf={{ csrf_token() }}" onclick="return confirm('Project laten vervallen?')"><i class="fa fa-times"></i>&nbsp;Project vervallen</a></li>
            @endif
        </ul>
    </div>
</div>

<form method="post" {!! $offer_last && $offer_last->offer_finish ? 'action="/project/update/note"' : 'action="/project/update"' !!}>
    {!! csrf_field() !!}

    <div class="modal fade" id="notepad" tabindex="-1" role="dialog" aria-labelledby="notepad" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel2">Project kladblok</h4>
                </div>

                <div class="modal-body">
                    <div class="form-group ">
                        <div class="col-md-12">
                            <textarea name="note" rows="10" class="form-control">{{ old('note') ? old('note') : $project->note }}</textarea>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary"><i class="fa fa-check"></i> Opslaan</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <h5><strong>Gegevens</strong></h5>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Projectnaam*</label>
                <input name="name" maxlength="50" id="name" type="text" {{ $project->project_close ? 'disabled' : ($offer_last && $offer_last->offer_finish ? 'disabled' : '') }} value="{{ old('name') ? old('name') : $project->project_name }}" class="form-control" />
                <input type="hidden" name="id" id="id" value="{{ $project->id }}"/>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="contractor">Opdrachtgever*</label>
                @if (!Relation::find($project->client_id)->isActive())
                <select name="contractor" id="contractor" {{ $project->project_close ? 'disabled' : ($offer_last && $offer_last->offer_finish ? 'disabled' : '') }} class="form-control pointer">
                    @foreach (Relation::where('user_id','=', Auth::id())->get() as $relation)
                    <option {{ $project->client_id==$relation->id ? 'selected' : '' }} value="{{ $relation->id }}">{{ RelationKind::find($relation->kind_id)->kind_name == 'zakelijk' ? ucwords($relation->company_name) : (Contact::where('relation_id','=',$relation->id)->first()['firstname'].' '.Contact::where('relation_id','=',$relation->id)->first()['lastname']) }}</option>
                    @endforeach
                </select>
                @else
                <select name="contractor" id="contractor" {{ $project->project_close ? 'disabled' : ($offer_last && $offer_last->offer_finish ? 'disabled' : '') }} class="form-control pointer">
                    @foreach (Relation::where('user_id','=', Auth::id())->where('active',true)->get() as $relation)
                    <option {{ $project->client_id==$relation->id ? 'selected' : '' }} value="{{ $relation->id }}">{{ RelationKind::find($relation->kind_id)->kind_name == 'zakelijk' ? ucwords($relation->company_name) : (Contact::where('relation_id','=',$relation->id)->first()['firstname'].' '.Contact::where('relation_id','=',$relation->id)->first()['lastname']) }}</option>
                    @endforeach
                </select>
                @endif
            </div>
        </div>
    </div>
    <h5><strong>Adresgegevens</strong></h5>
    <div class="row">

        <div class="col-md-4">
            <div class="form-group">
                <label for="street">Straat*</label>
                <input name="street" id="street" maxlength="60" {{ $project->project_close ? 'disabled' : ($offer_last && $offer_last->offer_finish ? 'disabled' : '') }} type="text" value="{{ old('street') ? old('street') : $project->address_street}}" class="form-control"/>
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group">
                <label for="address_number">Huis nr.*</label>
                <input name="address_number" maxlength="5" {{ $project->project_close ? 'disabled' : ($offer_last && $offer_last->offer_finish ? 'disabled' : '') }} id="address_number" type="text" value="{{ old('address_number') ? old('address_number') : $project->address_number }}" class="form-control"/>
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="zipcode">Postcode*</label>
                <input name="zipcode" maxlength="6" {{ $project->project_close ? 'disabled' : ($offer_last && $offer_last->offer_finish ? 'disabled' : '') }} id="zipcode" type="text" maxlength="6" value="{{ old('zipcode') ? old('zipcode') : $project->address_postal }}" class="form-control"/>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="city">Plaats*</label>
                <input name="city" maxlength="35" {{ $project->project_close ? 'disabled' : ($offer_last && $offer_last->offer_finish ? 'disabled' : '') }} id="city" type="text" value="{{ old('city') ? old('city'): $project->address_city }}" class="form-control"/>
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="province">Provincie*</label>
                <select name="province" {{ $project->project_close ? 'disabled' : ($offer_last && $offer_last->offer_finish ? 'disabled' : '') }} id="province" class="form-control pointer">
                    @foreach ($province::all() as $province)
                    <option {{ $project->province_id==$province->id ? 'selected' : '' }} value="{{ $province->id }}">{{ ucwords($province->province_name) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="country">Land*</label>
                <select name="country" {{ $project->project_close ? 'disabled' : ($offer_last && $offer_last->offer_finish ? 'disabled' : '') }} id="country" class="form-control pointer">
                    @foreach ($country::all() as $country)
                    <option {{ $project->country_id==$country->id ? 'selected' : '' }} value="{{ $country->id }}">{{ ucwords($country->country_name) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <h4>Projectstatussen</h4>

    <div class="col-md-6">

        <div class="row">
            <div class="col-md-4"><strong>Offerte stadium</strong></div>
            <div class="col-md-4"><strong></strong></div>
            <div class="col-md-4"><i>Laatste wijziging</i></div>
        </div>
        <div class="row">
            <div class="col-md-4">Calculatie gestart</div>
            <div class="col-md-4"><?php echo date('d-m-Y', strtotime(DB::table('project')->select('created_at')->where('id','=',$project->id)->get()[0]->created_at)); ?></div>
            <div class="col-md-4"><i><?php echo date('d-m-Y', strtotime(DB::table('project')->select('updated_at')->where('id','=',$project->id)->get()[0]->updated_at)); ?></i></div>
        </div>
        <div class="row">
            <div class="col-md-4">Offerte opgesteld</div>
            <div class="col-md-4"><?php if ($offer_last) { echo date('d-m-Y', strtotime(DB::table('offer')->select('created_at')->where('id','=',$offer_last->id)->get()[0]->created_at)); } ?></div>
            <div class="col-md-4"><i><?php if ($offer_last) { echo ''.date('d-m-Y', strtotime(DB::table('offer')->select('updated_at')->where('id','=',$offer_last->id)->get()[0]->updated_at)); } ?></i></div>
        </div>
        <div class="row">
            <div class="col-md-4">Opdracht <a data-toggle="tooltip" data-placement="bottom" data-original-title="Vul hier de datum in wanneer je opdracht hebt gekregen op je offerte. De calculatie slaat dan definitief dicht." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></div>
            <div class="col-md-4">{{ $offer_last && $offer_last->offer_finish ? date('d-m-Y', strtotime($offer_last->offer_finish)) : '' }}</div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="row">
            <div class="col-md-4"><strong>Opdracht stadium</strong></div>
            <div class="col-md-4"><strong></strong></div>
            <div class="col-md-4"><i>Laatste wijziging</i></div>
        </div>
        <div class="row">
            <div class="col-md-4">Start uitvoering <a data-toggle="tooltip" data-placement="bottom" data-original-title="Vul hier de datum in dat je met uitvoering bent begonnen" href="#"><i class="fa fa-info-circle"></i></a></div>
            <div class="col-md-4"><?php if ($project->project_close) { echo $project->work_execution ? date('d-m-Y', strtotime($project->work_execution)) : ''; }else{ if ($project->work_execution){ echo date('d-m-Y', strtotime($project->work_execution)); }else{ ?><a href="#" id="wordexec">Bewerk</a><?php } } ?></div>
            <div class="col-md-4"></div>
        </div>
        <div class="row">
            <div class="col-md-4">Opleverdatum <a data-toggle="tooltip" data-placement="bottom" data-original-title="Vul hier de datum in dat je het moet/wilt/verwacht opleveren" href="#"><i class="fa fa-info-circle"></i></a></div>
            <div class="col-md-4"><?php if ($project->project_close) { echo $project->work_completion ? date('d-m-Y', strtotime($project->work_completion)) : ''; }else{ if ($project->work_completion){ echo date('d-m-Y', strtotime($project->work_completion)); }else{ ?><a href="#" id="wordcompl">Bewerk</a><?php } } ?></div>
            <div class="col-md-4"></div>
        </div>
        @if ($project->use_estim)
        <div class="row">
            <div class="col-md-4">Stelposten gesteld</div>
            <div class="col-md-4"><i>{{ $project->start_estimate ? date('d-m-Y', strtotime($project->start_estimate)) : '' }}</i></div>
            <div class="col-md-4"><i>{{ $project->update_estimate ? ''.date('d-m-Y', strtotime($project->update_estimate)) : '' }}</i></div>
        </div>
        @endif
        @if ($project->use_more)
        <div class="row">
            <div class="col-md-4">Meerwerk toegevoegd</div>
            <div class="col-md-4">{{ $project->start_more ? date('d-m-Y', strtotime($project->start_more)) : '' }}</div>
            <div class="col-md-4"><i>{{ $project->update_more ? ''.date('d-m-Y', strtotime($project->update_more)) : '' }}</i></div>
        </div>
        @endif
        @if ($project->use_less)
        <div class="row">
            <div class="col-md-4">Minderwerk verwerkt</div>
            <div class="col-md-4">{{ $project->start_less ? date('d-m-Y', strtotime($project->start_less)) : '' }}</div>
            <div class="col-md-4"><i>{{ $project->update_less ? ''.date('d-m-Y', strtotime($project->update_less)) : '' }}</i></div>
        </div>
        @endif
        <br>

        @if ($project->project_close)
        <div class="row">
            <div class="col-md-4"><strong>Project gesloten</strong></div>
            <div class="col-md-4">{{ date('d-m-Y', strtotime($project->project_close)) }}</a></div>
        </div>
        @endif
    </div>

    <div class="row">
        <div class="col-md-12" style="margin-top: 15px;">
            @if (!$project->project_close)
            <button class="btn btn-primary"><i class="fa fa-check"></i> Opslaan</button>
            @endif
        </div>
    </div>

</form>