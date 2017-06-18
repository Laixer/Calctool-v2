@extends('relation.layout', ['page' => 'financial'])

@section('relation_section_name', 'Financieel')

@section('relation_content')

<?php
use \BynqIO\Dynq\Models\Relation;
$relation = Relation::find(Route::Input('relation_id'));
?>

<div class="white-row">
<form method="POST" action="/relation/iban/update" accept-charset="UTF-8">
{!! csrf_field() !!}
<input type="hidden" name="id" id="id" value="{{ $relation->id }}"/>
<div class="row">

    <div class="col-md-6">
        <div class="form-group">
            <label for="iban">IBAN rekeningnummer</label>
            <input name="iban" maxlength="25" id="iban" type="text" value="{{ old('iban') ? old('iban') : $relation->iban }}" class="form-control"/>
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
</div>
@endsection
