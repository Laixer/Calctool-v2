<?php

use BynqIO\Dynq\Models\Relation;
use BynqIO\Dynq\Models\Contact;
use BynqIO\Dynq\Models\DeliverTime;
use BynqIO\Dynq\Models\Valid;

$relation = Relation::find($project->client_id);
$relation_self = Relation::find(Auth::user()->self_id);
?>
@inject('carbon', 'Carbon\Carbon')

@push('style')
<link media="all" type="text/css" rel="stylesheet" href="/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
<link media="all" type="text/css" rel="stylesheet" href="/plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css">
@endpush

@push('scripts')
<script src="/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<script src="/plugins/jquery.number.min.js"></script>
@endpush

@push('jsinline')
<script type="text/javascript">
$(document).ready(function() {
    $('[name=date]').datepicker({format: '{{ \BynqIO\Dynq\Services\FormatService::dateFormatJS() }}'});

    $("[type='checkbox'").bootstrapSwitch({onText: 'Ja', offText: 'Nee'});

    $("[name=amount]").number({!! \BynqIO\Dynq\Services\FormatService::monetaryJS('true') !!});

    /* Remove contents from modal on close */
    $(document).on('hidden.bs.modal', function (e) {
        $(e.target).removeData('bs.modal');
    });
});
</script>
@endpush

@section('component_buttons')
<div class="pull-right">
    <form action="/invoice/close" method="post">
        {!! csrf_field() !!}
        <input type="hidden" name="project" value="{{ $project->id }}" />
        @foreach(Input::all() as $input => $value)
        <input type="hidden" name="{{ $input }}" value="{{ $value }}" />
        @endforeach
        <button class="btn btn-primary" {{ Input::has('ts') ?: 'disabled' }}><i class="fa fa-check-square-o"></i>Factureren</button>
    </form>
</div>
@endsection

<div class="modal fade" id="asyncModal" tabindex="-1" role="dialog" aria-labelledby="asyncModal" aria-hidden="true">
    <div class="modal-dialog {{-- modal-lg --}} {{-- modal-sm --}}">
        <div class="modal-content"></div>
    </div>
</div>

<form action="" method="get" class="white-row">
    <input type="hidden" name="id" value="{{ $invoice->id }}" />
    <input type="hidden" name="ts" value="{{ time() }}" />
    <input type="hidden" name="endinvoice" value="{{ Input::get('endinvoice') }}" />
    <input type="hidden" name="conditions" value="{{ Input::has('conditions') ? Input::get('conditions') : '' }}" />
    <input type="hidden" name="pretext" value="{{ Input::has('pretext') ? Input::get('pretext') : '' }}" />
    <input type="hidden" name="posttext" value="{{ Input::has('posttext') ? Input::get('posttext') : '' }}" />

    <h3 class="page-header nomargin-top">Instellingen</h3>

    {{-- Invoice options --}}
    <div class="row">
        <div class="form-group">

            <div class="col-md-12">
                <label>Factuurnummer</label>
                <input type="text" class="form-control" disabled name="invoice_code" value="{{ $invoice->invoice_code }}" />
            </div>

            @if (!$invoice->isclose)
            <div class="col-md-12">
                <label>Bedrag</label>
                <input type="text" class="form-control" name="amount" value="{{ $invoice->amount ? $invoice->amount : '0' }}" />
            </div>
            @endif

            <div class="col-md-6">
                <label>Klantreferentie</label>
                <input type="text" class="form-control" name="client_reference" value="{{ Input::has('client_reference') ? Input::get('client_reference') : '' }}" />
            </div>

            <div class="col-md-6">
                <label>Uw referentie</label>
                <input type="text" class="form-control" name="our_reference" placeholder="REF-{{ mt_rand(1000000, 9000000) }}" value="{{ Input::has('our_reference') ? Input::get('our_reference') : '' }}" />
            </div>

            <div class="col-md-12 col-sm-6">
                <label>Contactpersoon</label>
                <select class="form-control" name="contact_to">
                    <option value="">Selecteer</option>
                    @foreach (Contact::where('relation_id',$relation->id)->get() as $contact)
                    <option {{ Input::has('contact_to') ? (Input::get('contact_to') == $contact->id ? 'selected' : '') : '' }} value="{{ $contact->id }}">{{ Contact::find($contact->id)->getFormalName() }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-12 col-sm-6">
                <label>Namens</label>
                <select class="form-control" name="contact_from">
                    <option value="">Selecteer</option>
                    @foreach (Contact::where('relation_id',$relation_self->id)->get() as $contact)
                    <option {{ Input::has('contact_from') ? (Input::get('contact_from') == $contact->id ? 'selected' : '') : '' }} value="{{ $contact->id }}">{{ $contact->firstname . ' ' . $contact->lastname }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 col-sm-6">
                <label>Betalingstermijn</label>
                <select class="form-control" name="condition">
                    <option value="">Selecteer</option>
                    <option {{ Input::has('condition') ? (Input::get('condition') == 1 ? 'selected' : '') : '' }} value="1">1 dag</option>
                    <option {{ Input::has('condition') ? (Input::get('condition') == 5 ? 'selected' : '') : '' }} value="5">5 dagen</option>
                    <option {{ Input::has('condition') ? (Input::get('condition') == 7 ? 'selected' : '') : '' }} value="7">7 dagen</option>
                    <option {{ Input::has('condition') ? (Input::get('condition') == 14 ? 'selected' : '') : '' }} value="14">14 dagen</option>
                    <option {{ Input::has('condition') ? (Input::get('condition') == 21 ? 'selected' : '') : '' }} value="21">21 dagen</option>
                    <option {{ Input::has('condition') ? (Input::get('condition') == 30 ? 'selected' : '') : '' }} value="30">30 dagen</option>
                    <option {{ Input::has('condition') ? (Input::get('condition') == 60 ? 'selected' : '') : '' }} value="60">60 dagen</option>
                </select>
            </div>
            <div class="col-md-6 col-sm-6">
                <label>Factuurdatum</label>
                <input type="text" class="form-control" name="date" value="{{ $carbon::now()->toDateString() }}" />
            </div>
            <div class="col-sm-offset-0 col-sm-12">
                <div class="checkbox">
                    <input name="separate_subcon" type="checkbox" {{ Input::has('separate_subcon') ? 'checked' : '' }}><span style="margin-left:10px;">Onderaanneming specificeren</span>
                </div>
            </div>
        </div>
    </div>
    {{-- /Invoice options --}}

    {{-- Proposal texts --}}
    <h3 class="page-header nomargin-top">Teksten</h3>
    <div class="row">
        <div class="col-sm-offset-0 col-sm-12" style="margin-bottom: 10px;">
            <a href="/inline/inline_edit?selector=pretext&title=Aanheftekst&package=component.modal" style="width:100px" data-toggle="modal" data-target="#asyncModal" class="btn btn-sm btn-default">Aanheftekst</a><span style="margin-left:10px;">Tekst na de aanhef</span>
        </div>
        <div class="col-sm-offset-0 col-sm-12" style="margin-bottom: 10px;">
            <a href="/inline/inline_edit?selector=conditions&title=Bepalingen&package=component.modal" style="width:100px" data-toggle="modal" data-target="#asyncModal" class="btn btn-sm btn-default">Bepalingen</a><span style="margin-left:10px;">Voeg extra bepalingen toe</span>
        </div>
        <div class="col-sm-offset-0 col-sm-12" style="margin-bottom: 10px;">
            <a href="/inline/inline_edit?selector=posttext&title=Sluittekst&package=component.modal" style="width:100px" data-toggle="modal" data-target="#asyncModal" class="btn btn-sm btn-default">Sluittekst</a><span style="margin-left:10px;">Geef sluittekst op</span>
        </div>
    </div>
    {{-- /Proposal texts --}}

    @if ($invoice->isclose)
    <h3 class="page-header nomargin-top">Bijlages</h3>

    {{-- Invoice pages --}}
    <div class="row">
        <div class="form-group">

            <div class="col-sm-offset-0 col-sm-12">
                <div class="checkbox">
                    <input name="display_specification" type="checkbox" {{ Input::has('display_specification') ? 'checked' : '' }}><span style="margin-left:10px;">Specificeren op onderdelen</span>
                </div>
            </div>
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

        </div>
    </div>
    {{-- /Invoice pages --}}
    @endif

    <div class="row">
        <div class="col-md-12">
            <label>&nbsp;</label>
            <button class="btn btn-primary fullwidth"><i class="fa fa-refresh" aria-hidden="true"></i>Bijwerken</button>
        </div>
    </div>

</form>
