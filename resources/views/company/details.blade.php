@inject('relationType', 'BynqIO\Dynq\Models\RelationType')
@inject('province', 'BynqIO\Dynq\Models\Province')
@inject('country', 'BynqIO\Dynq\Models\Country')

@extends('company.layout', ['page' => 'details'])

@section('company_section_name', 'Bedrijfsgegevens')

@section('company_content')
<form action="{{ url('company/update') }}" method="post">
{!! csrf_field() !!}

<div>
<input type="hidden" name="id" id="id" value="{{ $relation->id }}"/>

<div class="row">
    <div class="col-md-5">
        <div class="form-group">
            <label for="company_name">Bedrijfsnaam*</label>
            <input name="company_name" maxlength="50" id="company_name" type="text" value="{{ old('company_name') ? old('company_name') : $relation->company_name }}" class="form-control" required/>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="company_type">Bedrijfstype*</label>
            <select name="company_type" id="company_type" class="form-control pointer">
                @foreach ($relationType::all() as $type)
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

</div>
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label for="kvk">K.v.K nummer</label>&nbsp;<a data-toggle="tooltip" data-placement="bottom" data-original-title="Je KVK-nummer dient te bestaan uit 8 cijfers" href="javascript:void(0);"><i class="fa fa-info-circle"></i></a>
            <input name="kvk" id="kvk" type="text" maxlength="8" minlength="8" value="{{ old('kvk') ? old('kvk') : $relation->kvk }}" class="form-control"/>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="btw">BTW nummer</label>&nbsp;<a data-toggle="tooltip" data-placement="bottom" data-original-title="Je BTW-nummer bestaat uit een combinatie van 14 cijfers en/of letters. Veelal beginnen nederlandse BTW-nummers met 'NL' en eindigen op 'B01'." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a>
            <input name="btw" id="btw" type="text" maxlength="14" minlength="14" value="{{ old('btw') ? old('btw') : $relation->btw }}" class="form-control"/>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label for="telephone_comp">Telefoonnummer</label>
            <input name="telephone_comp" id="telephone_comp" type="text" maxlength="12" value="{{ old('telephone_comp') ? old('telephone_comp') : $relation->phone }}" class="form-control"/>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="email_comp">Email</label>
            <input name="email_comp" maxlength="80" id="email_comp" type="email" value="{{ old('email_comp') ? old('email_comp') : $relation->email }}" class="form-control"/>
        </div>
    </div>
</div>

<h4>Adresgegevens</h4>
<div class="row">
    <div class="col-md-2">
        <div class="form-group">
            <label for="address_number">Huis nr.*</label>
            <input name="address_number" maxlength="5" id="address_number" type="text" value="{{ old('address_number') ? old('address_number') : $relation->address_number }}" class="form-control autoappend"/>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label for="zipcode">Postcode*</label>
            <input name="zipcode" id="zipcode" maxlength="6" type="text" value="{{ old('zipcode') ? old('zipcode') : $relation->address_postal }}" class="form-control autoappend"/>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="street">Straat*</label>
            <input name="street" maxlength="50" id="street" type="text" value="{{ old('street') ? old('street') : $relation->address_street }}" class="form-control"/>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="city">Plaats*</label>
            <input name="city" maxlength="35" id="city" type="text" value="{{ old('city') ? old('city') : $relation->address_city }}" class="form-control"/>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="province">Provincie*</label>
            <select name="province" id="province" class="form-control pointer">
                @foreach ($province::all() as $province)
                    <option {{ $relation ? ($relation->province_id==$province->id ? 'selected' : '') : (old('province') == $province->id ? 'selected' : '') }} value="{{ $province->id }}">{{ ucwords($province->province_name) }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="country">Land*</label>
            <select name="country" id="country" class="form-control pointer">
                @foreach ($country::all() as $country)
                    <option {{ $relation ? ($relation->country_id==$country->id ? 'selected' : '') : ($country->country_name=='nederland' ? 'selected' : '')}} value="{{ $country->id }}">{{ ucwords($country->country_name) }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

    <div class="row">
        <div class="col-md-12">
            <button class="btn btn-primary"><i class="fa fa-check"></i> Opslaan</button>
        </div>
    </div>
</form>
</div>
@endsection
