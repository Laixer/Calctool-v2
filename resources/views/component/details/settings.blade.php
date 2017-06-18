<?php

use BynqIO\Dynq\Models\Relation;
use BynqIO\Dynq\Models\Contact;
use BynqIO\Dynq\Models\RelationKind;

?>

@inject('province', 'BynqIO\Dynq\Models\Province')
@inject('country', 'BynqIO\Dynq\Models\Country')

@if (0)
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
@endif

<form method="post" action="/project/update">
    {!! csrf_field() !!}

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Projectnaam <strong style="color:red;">*</strong></label>
                <input name="name" maxlength="50" id="name" type="text" {{ $project->project_close ? 'disabled' : '' }} value="{{ old('name') ? old('name') : $project->project_name }}" class="form-control" />
                <input type="hidden" name="id" id="id" value="{{ $project->id }}"/>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="contractor">Opdrachtgever <strong style="color:red;">*</strong></label>
                @if (!Relation::find($project->client_id)->isActive())
                <select name="contractor" id="contractor" {{ $project->project_close ? 'disabled' : '' }} class="form-control pointer">
                    @foreach (Relation::where('user_id','=', Auth::id())->get() as $relation)
                    <option {{ $project->client_id==$relation->id ? 'selected' : '' }} value="{{ $relation->id }}">{{ RelationKind::find($relation->kind_id)->kind_name == 'zakelijk' ? ucwords($relation->company_name) : (Contact::where('relation_id','=',$relation->id)->first()['firstname'].' '.Contact::where('relation_id','=',$relation->id)->first()['lastname']) }}</option>
                    @endforeach
                </select>
                @else
                <select name="contractor" id="contractor" {{ $project->project_close ? 'disabled' : '' }} class="form-control pointer">
                    @foreach (Relation::where('user_id','=', Auth::id())->where('active',true)->get() as $relation)
                    <option {{ $project->client_id==$relation->id ? 'selected' : '' }} value="{{ $relation->id }}">{{ RelationKind::find($relation->kind_id)->kind_name == 'zakelijk' ? ucwords($relation->company_name) : (Contact::where('relation_id','=',$relation->id)->first()['firstname'].' '.Contact::where('relation_id','=',$relation->id)->first()['lastname']) }}</option>
                    @endforeach
                </select>
                @endif
            </div>
        </div>
    </div>

    <h4>Adresgegevens</h4>
    <div class="row">

        <div class="col-md-4">
            <div class="form-group">
                <label for="street">Straat <strong style="color:red;">*</strong></label>
                <input name="street" id="street" maxlength="60" {{ $project->project_close ? 'disabled' : '' }} type="text" value="{{ old('street') ? old('street') : $project->address_street}}" class="form-control"/>
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group">
                <label for="address_number">Huis nr. <strong style="color:red;">*</strong></label>
                <input name="address_number" maxlength="5" {{ $project->project_close ? 'disabled' : '' }} id="address_number" type="text" value="{{ old('address_number') ? old('address_number') : $project->address_number }}" class="form-control"/>
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="zipcode">Postcode <strong style="color:red;">*</strong></label>
                <input name="zipcode" maxlength="6" {{ $project->project_close ? 'disabled' : '' }} id="zipcode" type="text" maxlength="6" value="{{ old('zipcode') ? old('zipcode') : $project->address_postal }}" class="form-control"/>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="city">Plaats <strong style="color:red;">*</strong></label>
                <input name="city" maxlength="35" {{ $project->project_close ? 'disabled' : '' }} id="city" type="text" value="{{ old('city') ? old('city'): $project->address_city }}" class="form-control"/>
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="province">Provincie <strong style="color:red;">*</strong></label>
                <select name="province" {{ $project->project_close ? 'disabled' : '' }} id="province" class="form-control pointer">
                    @foreach ($province::all() as $province)
                    <option {{ $project->province_id==$province->id ? 'selected' : '' }} value="{{ $province->id }}">{{ ucwords($province->province_name) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="country">Land <strong style="color:red;">*</strong></label>
                <select name="country" {{ $project->project_close ? 'disabled' : '' }} id="country" class="form-control pointer">
                    @foreach ($country::all() as $country)
                    <option {{ $project->country_id==$country->id ? 'selected' : '' }} value="{{ $country->id }}">{{ ucwords($country->country_name) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12" style="margin-top: 15px;">
            @if (!$project->project_close)
            <button class="btn btn-primary"><i class="fa fa-check"></i> Opslaan</button>
            @endif
        </div>
    </div>

</form>