@inject('activity', 'BynqIO\Dynq\Models\Activity')

@extends('component.modal', ['button_id' => 'inline-save'])

@section('modal_name', Input::get('title'))

@section('modal_content')
<script type="text/javascript">
$(document).ready(function() {
    $("#inline-text").val($("[name={{ Input::get('selector') }}]").val());
    $("#inline-save").click(function (e){
        $("[name={{ Input::get('selector') }}]").val($("#inline-text").val());
        $('#asyncModal').modal('toggle');
    });
});
</script>

<div class="form-horizontal">
    <div class="form-group">
        <div class="col-md-4">
            <label>Omschrijving</label>
        </div>
        <div class="col-md-12">
            <textarea name="inline-text" id="inline-text" rows="5" class="form-control"></textarea>
            <input value="{{ Input::get('id') }}" type="hidden" name="id" />
        </div>
    </div>
</div>
@endsection
