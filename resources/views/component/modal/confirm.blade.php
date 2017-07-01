@inject('offer', 'BynqIO\Dynq\Models\Offer')
@inject('carbon', 'Carbon\Carbon')

@extends('component.modal', ['form' => '/quotation/confirm'])

@section('modal_name', 'Opdracht bevestiging')

@section('modal_content')
<script src="/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('[name=date]').datepicker({format: '{{ \BynqIO\Dynq\Services\FormatService::dateFormatJS() }}'});
});
</script>

<div class="form-horizontal">
    <div class="form-group">
        <div class="col-md-12">
            <label>Bevestig {{ $offer::findOrFail(Input::get('id'))->offer_code }} op</label>
        </div>
        <div class="col-md-12">
            <input value="{{ Input::get('project') }}" type="hidden" name="project" />
            <input type="text" class="form-control" name="date" value="{{ $carbon::now()->toDateString() }}" autocomplete="off" required/>
        </div>
    </div>
</div>
@endsection
