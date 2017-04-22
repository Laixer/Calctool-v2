@extends('company.layout')

@section('company_section_name', 'Financieel')

@section('company_content')
<form action="{{ url('company/updatefinacial') }}" method="post">
{!! csrf_field() !!}
<div class="row">
<input type="hidden" name="id" id="id" value="{{ $relation->id }}"/>

    <div class="col-md-6">
        <div class="form-group">
            <label for="iban">IBAN rekeningnummer</label>
            <input name="iban" id="iban" maxlength="25" type="text" value="{{ old('iban') ? old('iban') : $relation->iban }}" class="form-control"/>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="btw">Naam rekeninghouder</label>
            <input name="iban_name" maxlength="50" id="iban_name" type="text" value="{{ old('iban_name') ? old('iban_name') : $relation->iban_name }}" class="form-control"/>
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
