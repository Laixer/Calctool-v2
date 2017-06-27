@extends('relation.layout', ['page' => 'details'])

@section('relation_section_name', 'Bedrijfsgegevens')

@section('relation_content')

<?php
use \BynqIO\Dynq\Models\RelationKind;
use \BynqIO\Dynq\Models\RelationType;
use \BynqIO\Dynq\Models\Province;
use \BynqIO\Dynq\Models\Country;
use \BynqIO\Dynq\Models\Relation;
$relation = Relation::find(Route::Input('relation_id'));
?>

<div class="white-row">

<div class="pull-right">
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Acties&nbsp;&nbsp;<span class="caret"></span></button>
        <ul class="dropdown-menu">
            <li><a href="/relation/convert?id={{ $relation->id }}&csrf={{ csrf_token() }}">Omzetten naar particulier</a></li>
            <li><a href="/relation/delete?id={{ $relation->id }}&csrf={{ csrf_token() }}" onclick="return confirm('Relatie verwijderen?')">Verwijderen</a>
        </li></ul>
    </div>
</div>

<form method="POST" action="/relation/update" accept-charset="UTF-8">
{!! csrf_field() !!}
<h4>{{ ucfirst(RelationKind::find($relation->kind_id)->kind_name) }}e relatie</h4>
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label for="debtor">Debiteurennummer</label>&nbsp;<a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit nummer is gegenereerd door de CalculatieTool.com. Je kunt dit vervangen door je eigen boekhoudkundige nummering." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a>
            <input name="debtor" maxlength="10" id="debtor" type="text" value="{{ old('debtor') ? old('debtor') : $relation->debtor_code }}" class="form-control"/>
            <input type="hidden" name="id" id="id" value="{{ $relation->id }}"/>
        </div>
    </div>

</div>

@if (RelationKind::find($relation->kind_id)->kind_name == 'zakelijk')
<h4 class="company">Bedrijfsgegevens</h4>
<div class="row company">

    <div class="col-md-5">
        <div class="form-group">
            <label for="company_name">Bedrijfsnaam <a style="text-decoration:none;cursor:default;">*</a></label>
            <input name="company_name" maxlength="50" id="company_name" type="text" value="{{ old('company_name') ? old('company_name') : $relation->company_name }}" class="form-control" />
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="company_type">Bedrijfstype <a style="text-decoration:none;cursor:default;">*</a></label>
            <select name="company_type" id="company_type" class="form-control pointer">
            @foreach (RelationType::all() as $type)
                <option {{ $relation->type_id==$type->id ? 'selected' : '' }} value="{{ $type->id }}">{{ ucwords($type->type_name) }}</option>
            @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="website">Website</label>
            <input name="website" maxlength="180" id="website" type="url" value="{{ old('website') ? old('website') : $relation->website }}" class="form-control"/>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="kvk">K.v.K nummer</label>&nbsp;<a data-toggle="tooltip" data-placement="bottom" data-original-title="Je KVK-nummer dient te bestaan uit 8 cijfers" href="javascript:void(0);"><i class="fa fa-info-circle"></i></a>
            <input name="kvk" id="kvk" type="text" maxlength="8" minlength="8" value="{{ old('kvk') ? old('kvk') : trim($relation->kvk) }}" class="form-control"/>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="btw">BTW nummer</label>&nbsp;<a data-toggle="tooltip" data-placement="bottom" data-original-title="Je BTW-nummer bestaat uit een combinatie van 12 cijfers en/of letters. Veelal beginnen nederlandse BTW-nummers met 'NL' en eindigen op 'B01'." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a>
            <input name="btw" id="btw" type="text" maxlength="14" value="{{ old('btw') ? old('btw') : $relation->btw }}" class="form-control"/>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label for="telephone_comp">Telefoonnummer</label>
            <input name="telephone_comp" id="telephone_comp" type="text" maxlength="12" value="{{ old('telephone_comp') ? old('telephone_comp') : $relation->phone }}" class="form-control"/>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="email_comp">Email <a style="text-decoration:none;cursor:default;">*</a></label>
            <input name="email_comp" maxlength="80" id="email_comp" type="email" value="{{ old('email_comp') ? old('email_comp') : $relation->email }}" class="form-control"/>
        </div>
    </div>

</div>
@endif

<h4>Adresgegevens</h4>
<div class="row">

    <div class="col-md-2">
        <div class="form-group">
            <label for="address_number">Huis nr. <a style="text-decoration:none;cursor:default;">*</a></label>
            <input name="address_number" maxlength="5" id="address_number" type="text" value="{{ old('address_number') ? old('address_number') : $relation->address_number }}" class="form-control autoappend"/>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label for="zipcode">Postcode <a style="text-decoration:none;cursor:default;">*</a></label>
            <input name="zipcode" id="zipcode" maxlength="6" type="text" value="{{ old('zipcode') ? old('zipcode') : $relation->address_postal }}" class="form-control autoappend"/>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="street">Straat <a style="text-decoration:none;cursor:default;">*</a></label>
            <input name="street" maxlength="50" id="street" type="text" value="{{ old('street') ? old('street') : $relation->address_street }}" class="form-control"/>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="city">Plaats <a style="text-decoration:none;cursor:default;">*</a></label>
            <input name="city" maxlength="35" id="city" type="text" value="{{ old('city') ? old('city') : $relation->address_city }}" class="form-control"/>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="province">Provincie <a style="text-decoration:none;cursor:default;">*</a></label>
            <select name="province" id="province" class="form-control pointer">
                @foreach (Province::all() as $province)
                    <option {{ $relation->province_id==$province->id ? 'selected' : '' }} value="{{ $province->id }}">{{ ucwords($province->province_name) }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="country">Land <a style="text-decoration:none;cursor:default;">*</a></label>
            <select name="country" id="country" class="form-control pointer">
                @foreach (Country::all() as $country)
                    <option {{ $relation->country_id==$country->id ? 'selected' : '' }} value="{{ $country->id }}">{{ ucwords($country->country_name) }}</option>
                @endforeach
            </select>
        </div>
    </div>

</div>
<!--
<h4>Opmerkingen</h4>
<div class="row">
    <div class="form-group">
        <div class="col-md-12">
            <textarea name="note" id="summernote" rows="10" class="form-control">{{ old('note') ? old('note') : $relation->note }}</textarea>
        </div>
    </div>
</div>-->
<div class="row">
    <div class="col-md-12">
        <button class="btn btn-primary"><i class="fa fa-check"></i> Opslaan</button>
    </div>
</div>
</form>
</div>
@endsection
