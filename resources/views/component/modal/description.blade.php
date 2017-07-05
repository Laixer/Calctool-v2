@inject('activity', 'BynqIO\Dynq\Models\Activity')

@extends('component.modal', ['form' => "$level_endpoint/description"])

@section('modal_name', 'Omschrijving aanpassen')

@section('modal_content')
<div class="form-horizontal">
    <div class="form-group">
        <div class="col-md-4">
            <label>Omschrijving</label>
        </div>
        <div class="col-md-12">
            <textarea name="description" rows="5" class="form-control">{{ $activity::findOrFail(Input::get('id'))->note }}</textarea>
            <input value="{{ Input::get('id') }}" type="hidden" name="id" />
        </div>
    </div>
</div>
@endsection
