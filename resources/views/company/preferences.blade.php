@extends('company.layout', ['page' => 'preferences'])

@section('company_section_name', 'Voorkeuren')

@push('scripts')
<script src="/plugins/jquery.number.min.js"></script>
@endpush

@push('jsinline')
<script type="text/javascript">
$(document).ready(function() {
    $("[name=pref_hourrate_calc]").number({!! \BynqIO\Dynq\Services\FormatService::monetaryJS('true') !!});
    $("[name=pref_hourrate_more]").number({!! \BynqIO\Dynq\Services\FormatService::monetaryJS('true') !!});
});
</script>
@endpush

@section('company_content')
<form method="POST" action="/account/preferences/update" accept-charset="UTF-8">
{!! csrf_field() !!}

<div class="toogle">

    {{-- Hour preferences --}}
    <div class="toggle">
        <label>Uurtarief en Winstpercentages</label>
        <div class="toggle-content" style="padding: 5px 10px;">

            <div class="row">
                <div class="col-md-5"><h5><strong>Eigen uurtarief*</strong></h5></div>
                <div class="col-md-1"></div>
                <div class="col-md-2"><h5><strong>Calculatie</strong></h5></div>
                <div class="col-md-2"><h5><strong>Meerwerk</strong></h5></div>
            </div>
            <div class="row">
                <div class="col-md-5"><label for="hour_rate">Uurtarief excl. BTW</label></div>
                <div class="col-md-1"><div class="pull-right">&euro;</div></div>
                <div class="col-md-2">
                    <input name="pref_hourrate_calc" id="pref_hourrate_calc" type="text" class="form-control form-control-sm-number" value="@money($user->pref_hourrate_calc, false)" />
                </div>
                <div class="col-md-2">
                    <input name="pref_hourrate_more" id="pref_hourrate_more" type="text" class="form-control form-control-sm-number" value="@money($user->pref_hourrate_more, false)" />
                </div>
            </div>

            <h5><strong>Aanneming</strong></h5>
            <div class="row">
                <div class="col-md-5"><label for="profit_material_1">Winstpercentage materiaal</label></div>
                <div class="col-md-1"><div class="pull-right">%</div></div>
                <div class="col-md-2">
                        <input name="pref_profit_calc_contr_mat" type="number" min="0" max="200" id="pref_profit_calc_contr_mat" type="text" class="form-control form-control-sm-number" value="{{ $user->pref_profit_calc_contr_mat }}" />
                </div>
                <div class="col-md-2">
                        <input name="pref_profit_more_contr_mat" type="number" min="0" max="200" id="pref_profit_more_contr_mat" type="text" class="form-control form-control-sm-number" value="{{ $user->pref_profit_more_contr_mat }}" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-5"><label for="profit_equipment_1">Winstpercentage overig</label></div>
                <div class="col-md-1"><div class="pull-right">%</div></div>
                <div class="col-md-2">
                        <input name="pref_profit_calc_contr_equip" type="number" min="0" max="200" id="pref_profit_calc_contr_equip" type="text" class="form-control form-control-sm-number" value="{{ $user->pref_profit_calc_contr_equip }}" />
                </div>
                <div class="col-md-2">
                        <input name="pref_profit_more_contr_equip" type="number" min="0" max="200" id="pref_profit_more_contr_equip" type="text" class="form-control form-control-sm-number" value="{{ $user->pref_profit_more_contr_equip }}" />
                </div>
            </div>

            <h5><strong>Onderaanneming</strong></h5>
            <div class="row">
                <div class="col-md-5"><label for="profit_material_2">Winstpercentage materiaal</label></div>
                <div class="col-md-1"><div class="pull-right">%</div></div>
                <div class="col-md-2">
                        <input name="pref_profit_calc_subcontr_mat" type="number" min="0" max="200" id="pref_profit_calc_subcontr_mat" type="text" class="form-control form-control-sm-number" value="{{ $user->pref_profit_calc_subcontr_mat }}" />
                </div>
                <div class="col-md-2">
                        <input name="pref_profit_more_subcontr_mat" type="number" min="0" max="200" id="pref_profit_more_subcontr_mat" type="text" class="form-control form-control-sm-number" value="{{ $user->pref_profit_more_subcontr_mat }}" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-5"><label for="profit_equipment_2">Winstpercentage overig</label></div>
                <div class="col-md-1"><div class="pull-right">%</div></div>
                <div class="col-md-2">
                        <input name="pref_profit_calc_subcontr_equip" type="number" min="0" max="200" id="pref_profit_calc_subcontr_equip" type="text" class="form-control form-control-sm-number" value="{{ $user->pref_profit_calc_subcontr_equip }}" />
                </div>
                <div class="col-md-2">
                        <input name="pref_profit_more_subcontr_equip" type="number" min="0" max="200" id="pref_profit_more_subcontr_equip" type="text" class="form-control form-control-sm-number" value="{{ $user->pref_profit_more_subcontr_equip }}" />
                </div>
            </div>

        </div>
    </div>
    {{-- /Hour preferences --}}

    {{-- Report texts --}}
    <div class="toggle">
        <label>Omschrijvingen voor op offerte en factuur</label>
        <div class="toggle-content" style="padding: 5px 10px;">

            <h4>Offerte</h4>
            <h5><strong>Omschrijving voor op de offerte</strong></h5>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-12">
                        <textarea name="pref_offer_description" id="pref_offer_description" rows="5" class="form-control">{{ $user->pref_offer_description }}</textarea>
                    </div>
                </div>
            </div>
            <h5><strong>Aanvullende bepalingen voor op de offerte</strong></h5>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-12">
                        <textarea name="pref_extracondition_offer" id="pref_extracondition_offer" rows="5" class="form-control">{{ $user->pref_extracondition_offer }}</textarea>
                    </div>
                </div>
            </div>
            <h5><strong>Sluitingstekst voor op de offerte</strong></h5>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-12">
                        <textarea name="pref_closure_offer" id="pref_closure_offer" rows="5" class="form-control">{{ $user->pref_closure_offer }}</textarea>
                    </div>
                </div>
            </div>
            <h4>Factuur</h4>
            <h5><strong>Omschrijving voor op de factuur</strong></h5>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-12">
                        <textarea name="pref_invoice_description" id="pref_invoice_description" rows="5" class="form-control">{{ $user->pref_invoice_description }}</textarea>
                    </div>
                </div>
            </div>
            <h5><strong>Sluitingstekst voor op de factuur</strong></h5>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-12">
                        <textarea name="pref_invoice_closure" id="pref_invoice_closure" rows="5" class="form-control">{{ $user->pref_invoice_closure }}</textarea>
                    </div>
                </div>
            </div>

        </div>
    </div>
    {{-- /Report texts --}}

    {{-- Mail texts --}}
    <div class="toggle">
        <label>Omschrijvingen voor in de emails</label>
        <div class="toggle-content" style="padding: 5px 10px;">

            <h4>Offerte</h4>
            <h5><strong>Beschrijving voor in de email bij verzending van de offerte</strong></h5>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-12">
                        <textarea name="pref_email_offer" id="pref_email_offer" rows="5" class="form-control">{{ $user->pref_email_offer }}</textarea>
                    </div>
                </div>
            </div>
            <h4>Factuur</h4>
            <h5><strong>Beschrijving voor in de email bij verzending van de factuur</strong></h5>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-12">
                        <textarea name="pref_email_invoice" id="pref_email_invoice" rows="5" class="form-control">{{ $user->pref_email_invoice }}</textarea>
                    </div>
                </div>
            </div>
            @if (0)
            <h5><strong>1e betalingsherinnering van de factuur (direct na verstrijken van de ingestelde betalingsconditie van de factuur)</strong></h5>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-12">
                        <textarea name="pref_email_invoice_first_reminder" id="pref_email_invoice_first_reminder" rows="5" class="form-control">{{ $user->pref_email_invoice_first_reminder }}</textarea>
                    </div>
                </div>
            </div>
            <h5><strong>Laatste betalingsherinnering van de factuur (14 dagen na de 1e betalingsherinnering)</strong></h5>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-12">
                        <textarea name="pref_email_invoice_demand" id="pref_email_invoice_last_reminder" rows="5" class="form-control">{{ $user->pref_email_invoice_last_reminder }}</textarea>
                    </div>
                </div>
            </div>
            <h5><strong>Vorderingswaaeschuwing van de factuur (7 dagen na de laatste (2e) betalingsherinnering)</strong></h5>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-12">
                        <textarea name="pref_email_invoice_demand" id="pref_email_invoice_demand" rows="5" class="form-control">{{ $user->pref_email_invoice_demand }}</textarea>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
    {{-- /Mail texts --}}

    {{-- Report numbering --}}
    <div class="toggle">
        <label>Offerte en factuurnummering</label>
        <div class="toggle-content" style="padding: 5px 10px;">

            <h4>Offerte</h5>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="offernumber_prefix"><strong>Tekst voor offertenummer</strong></label>
                        <input name="offernumber_prefix" id="offernumber_prefix" maxlength="10" type="text" class="form-control" value="{{ $user->offernumber_prefix }}" />
                    </div>
                </div>
            </div>

            <h4>Factuur</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="pref_use_ct_numbering" style="display:block;"><strong>Gebruik CalculatieTool nummering voor factuurnummer</strong></label>
                        <input name="pref_use_ct_numbering" type="checkbox" {{ $user->pref_use_ct_numbering ? 'checked' : '' }}>
                    </div>
                </div>
                <div class="col-md-6" >
                    <div class="form-group">
                        <label for="invoicenumber_prefix"><strong>Tekst voor factuurnummer</strong></label>
                        <input name="invoicenumber_prefix" id="invoicenumber_prefix" type="text" maxlength="10" class="form-control" value="{{ $user->invoicenumber_prefix }}" />
                    </div>
                </div>
            </div>

        </div>
    </div>
    {{-- /Report numbering --}}

</div>
<div class="row">
        <div class="col-md-12">
            <button class="btn btn-primary"><i class="fa fa-check"></i> Opslaan</button>
        </div>
    </div>
</form>
@endsection
