@extends('relation.layout', ['page' => 'options'])

@section('relation_section_name', 'Notities')

@section('relation_content')

<?php
use \BynqIO\CalculatieTool\Models\RelationKind;
use \BynqIO\CalculatieTool\Models\RelationType;
use \BynqIO\CalculatieTool\Models\Province;
use \BynqIO\CalculatieTool\Models\Country;
use \BynqIO\CalculatieTool\Models\Relation;
$relation = Relation::find(Route::Input('relation_id'));
?>

<div class="white-row">
<form method="POST" action="/relation/update" accept-charset="UTF-8">
<!--<h4>Opmerkingen</h4>-->
<div class="row">
    <div class="form-group">
        <div class="col-md-12">
            <textarea name="note" rows="10" class="form-control">{{ old('note') ? old('note') : $relation->note }}</textarea>
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
