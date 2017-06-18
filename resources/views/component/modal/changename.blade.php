@extends('component.modal', ['form' => '/project/level/rename'])

@section('modal_name', 'Naam aanpassen')

@section('modal_content')
<div class="form-horizontal">
    <div class="form-group">
        <div class="col-md-4">
            <label>Naam</label>
        </div>
        <div class="col-md-12">
            <input value="{{ Input::get('id') }}" name="id" type="hidden" class="form-control" />
            <input value="{{ Input::get('level') }}" name="level" type="hidden" class="form-control" />
            <input value="{{ Input::get('name') }}" maxlength="100" name="name" class="form-control" />
        </div>
    </div>
</div>
@endsection
