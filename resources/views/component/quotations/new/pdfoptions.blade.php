<?php

use BynqIO\Dynq\Models\Relation;
use BynqIO\Dynq\Models\Contact;
use BynqIO\Dynq\Models\DeliverTime;
use BynqIO\Dynq\Models\Valid;

$relation = Relation::find($project->client_id);
$relation_self = Relation::find(Auth::user()->self_id);
?>

@push('style')
<link media="all" type="text/css" rel="stylesheet" href="/plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css">
@endpush

@push('scripts')
<script src="/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
@endpush

@push('jsinline')
<script type="text/javascript">
$(document).ready(function() {
    $("[type='checkbox'").bootstrapSwitch({onText: 'Ja', offText: 'Nee'});
});
</script>
@endpush

@section('component_buttons')
<div class="pull-right">
    <form action="/proposal/something" method="post">
        {!! csrf_field() !!}
        <button class="btn btn-primary"><i class="fa fa-check-square-o"></i>Offereren</button>
        @foreach(Input::all() as $input => $value)
        <input type="hidden" name="{{ $input }}" value="{{ $value }}" />
        @endforeach
    </form>
</div>
@endsection

<form action="" method="get" class="white-row">

    <h3 class="page-header nomargin-top">Instellingen</h3>

    {{-- Proposal options --}}
    <div class="row">
        <div class="form-group">

            <div class="col-md-5">
                <label>Termijnen</label>
                <input type="number" class="form-control" name="terms" min="1" value="{{ Input::has('terms') ? Input::get('terms') : '1' }}">
            </div>

            <div class="col-md-7">
                <label>Aanbetalingsbedrag</label>
                <input type="text" class="form-control" name="amount" value="{{ Input::has('amount') ? Input::get('amount') : '0' }}">
            </div>

            <div class="col-md-12 col-sm-6">
                <label>Contactpersoon</label>
                <select class="form-control" name="to_contact">
                    @foreach (Contact::where('relation_id',$relation->id)->get() as $contact)
                    <option {{ Input::has('to_contact') ? (Input::get('to_contact') == $contact->id ? 'selected' : '') : '' }} value="{{ $contact->id }}">{{ Contact::find($contact->id)->getFormalName() }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-12 col-sm-6">
                <label>Namens</label>
                <select class="form-control" name="from_contact">
                    @foreach (Contact::where('relation_id','=',$relation_self->id)->get() as $contact)
                    <option {{ Input::has('from_contact') ? (Input::get('from_contact') == $contact->id ? 'selected' : '') : '' }} value="{{ $contact->id }}">{{ $contact->firstname . ' ' . $contact->lastname }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 col-sm-6">
                <label>Oplevering</label>
                <select class="form-control" name="deliver">
                    @foreach (DeliverTime::all() as $deliver)
                    <option {{ Input::has('deliver') ? (Input::get('deliver') == $deliver->id ? 'selected' : '') : '' }} value="{{ $deliver->id }}">{{ $deliver->delivertime_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 col-sm-6">
                <label>Offerte geldig</label>
                <select class="form-control" name="valid">
                    @foreach (Valid::all() as $valid)
                    <option {{ Input::has('valid') ? (Input::get('valid') == $valid->id ? 'selected' : '') : '' }} value="{{ $valid->id }}">{{ $valid->valid_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-offset-0 col-sm-12">
                <div class="checkbox">
                    <input name="only_totals" type="checkbox" {{ Input::has('only_totals') ? 'checked' : '' }}><span style="margin-left:10px;">Alleen totaalkosten weergeven</span>
                </div>
            </div>
            <div class="col-sm-offset-0 col-sm-12">
                <div class="checkbox">
                    <input name="separate_subcon" type="checkbox" {{ Input::has('separate_subcon') ? 'checked' : '' }}><span style="margin-left:10px;">Onderaanneming specificeren</span>
                </div>
            </div>
        </div>
    </div>
    {{-- /Proposal options --}}

    <h3 class="page-header nomargin-top">Extra pagina's</h3>

    {{-- Proposal pages --}}
    <div class="row">
        <div class="form-group">

            <div class="col-sm-offset-0 col-sm-12">
                <div class="checkbox">
                    <input name="display_worktotals" type="checkbox" {{ Input::has('display_worktotals') ? 'checked' : '' }}><span style="margin-left:10px;">Totaalkosten per werkzaamheid</span>
                </div>
            </div>
            <div class="col-sm-offset-0 col-sm-12">
                <div class="checkbox">
                    <input name="display_description" type="checkbox" {{ Input::has('display_description') ? 'checked' : '' }}><span style="margin-left:10px;">Omschrijving per werkzaamheid</span>
                </div>
            </div>
            <div class="col-sm-offset-0 col-sm-12">
                <div class="checkbox">
                    <input name="display_specification" type="checkbox" {{ Input::has('display_specification') ? 'checked' : '' }}><span style="margin-left:10px;">Werkzaamheden specificeren</span>
                </div>
            </div>

            <div class="col-md-12">
                <label>&nbsp;</label>
                <button class="btn btn-primary fullwidth"><i class="fa fa-refresh" aria-hidden="true"></i>Bijwerken</button>
            </div>
        </div>
    </div>
    {{-- /Proposal pages --}}

</form>
