@inject('relationType', 'BynqIO\CalculatieTool\Models\RelationType')
@inject('province', 'BynqIO\CalculatieTool\Models\Province')
@inject('country', 'BynqIO\CalculatieTool\Models\Country')

@extends('company.layout')

@section('company_section_name', 'Nieuw Bedrijf')

@section('company_content')
<form action="{{ url()->current() }}" method="post">
{!! csrf_field() !!}

<div>
<div class="row">
    <div class="col-md-5">
        <div class="form-group">
            <label for="company_name">Bedrijfsnaam*</label>
            <input name="company_name" maxlength="50" id="company_name" type="text" value="{{ old('company_name') ? old('company_name') : '' }}" class="form-control" />
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="company_type">Bedrijfstype*</label>
            <select name="company_type" id="company_type" class="form-control pointer">
                @foreach ($relationType::all() as $type)
                <option {{ old('company_type') == $type->id ? 'selected' : '' }} value="{{ $type->id }}">{{ ucwords($type->type_name) }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="website">Website</label>
            <input name="website" maxlength="180" id="website" type="url" value="{{ old('website') ? old('website') : '' }}" class="form-control"/>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label for="kvk">K.v.K nummer</label>&nbsp;<a data-toggle="tooltip" data-placement="bottom" data-original-title="Je KVK-nummer dient te bestaan uit 8 cijfers" href="javascript:void(0);"><i class="fa fa-info-circle"></i></a>
            <input name="kvk" id="kvk" type="text" maxlength="8" minlength="8" value="{{ old('kvk') ? old('kvk') : '' }}" class="form-control"/>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="btw">BTW nummer</label>&nbsp;<a data-toggle="tooltip" data-placement="bottom" data-original-title="Je BTW-nummer bestaat uit een combinatie van 14 cijfers en/of letters. Veelal beginnen nederlandse BTW-nummers met 'NL' en eindigen op 'B01'." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a>
            <input name="btw" id="btw" type="text" maxlength="14" minlength="14" value="{{ old('btw') ? old('btw') : '' }}" class="form-control"/>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label for="telephone_comp">Telefoonnummer</label>
            <input name="telephone_comp" id="telephone_comp" type="text" maxlength="12" value="{{ old('telephone_comp') ? old('telephone_comp') : '' }}" class="form-control"/>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="email_comp">Email</label>
            <input name="email_comp" maxlength="80" id="email_comp" type="email" value="{{ old('email_comp') ? old('email_comp') : '' }}" class="form-control"/>
        </div>
    </div>
</div>

<h4>Adresgegevens</h4>
<div class="row">
    <div class="col-md-2">
        <div class="form-group">
            <label for="address_number">Huis nr.*</label>
            <input name="address_number" maxlength="5" id="address_number" type="text" value="{{ old('address_number') ? old('address_number') : '' }}" class="form-control autoappend"/>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label for="zipcode">Postcode*</label>
            <input name="zipcode" id="zipcode" maxlength="6" type="text" value="{{ old('zipcode') ? old('zipcode') : '' }}" class="form-control autoappend"/>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="street">Straat*</label>
            <input name="street" maxlength="50" id="street" type="text" value="{{ old('street') ? old('street') : '' }}" class="form-control"/>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="city">Plaats*</label>
            <input name="city" maxlength="35" id="city" type="text" value="{{ old('city') ? old('city') : '' }}" class="form-control"/>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="province">Provincie*</label>
            <select name="province" id="province" class="form-control pointer">
                @foreach ($province::all() as $province)
                    <option {{ old('province') == $province->id ? 'selected' : '' }} value="{{ $province->id }}">{{ ucwords($province->province_name) }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="country">Land*</label>
            <select name="country" id="country" class="form-control pointer">
                @foreach ($country::all() as $country)
                    <option {{ $country->country_name=='nederland' ? 'selected' : '' }} value="{{ $country->id }}">{{ ucwords($country->country_name) }}</option>
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
@endsection
