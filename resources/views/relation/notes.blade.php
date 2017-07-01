@extends('relation.layout', ['page' => 'notes'])

@section('relation_section_name', 'Notities')

@section('relation_content')

<?php
use \BynqIO\Dynq\Models\Relation;
$relation = Relation::find(Route::Input('relation_id'));
?>

<div class="white-row">
    <form method="post" action="/relation/note/update" accept-charset="UTF-8">
        {!! csrf_field() !!}
        <input type="hidden" name="id" id="id" value="{{ $relation->id }}" />

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
