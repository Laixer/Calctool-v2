<?php

use BynqIO\Dynq\Models\Project;
use BynqIO\Dynq\Models\Relation;
use BynqIO\Dynq\Models\Tax;
use BynqIO\Dynq\Models\Offer;
use BynqIO\Dynq\Calculus\CalculationEndresult;
use BynqIO\Dynq\Models\Contact;
use BynqIO\Dynq\Models\ProjectType;
use BynqIO\Dynq\Models\DeliverTime;
use BynqIO\Dynq\Models\Valid;
use BynqIO\Dynq\Models\Chapter;
use BynqIO\Dynq\Models\BlancRow;
use BynqIO\Dynq\Models\Activity;
use BynqIO\Dynq\Models\Part;
use BynqIO\Dynq\Calculus\CalculationOverview;
use BynqIO\Dynq\Calculus\BlancRowsEndresult;
use BynqIO\Dynq\Models\Resource;
use BynqIO\Dynq\Http\Controllers\OfferController;

$relation = Relation::find($project->client_id);
$relation_self = Relation::find(Auth::user()->self_id);
if ($relation_self)
    $contact_self = Contact::where('relation_id','=',$relation_self->id);
$offer_last = Offer::where('project_id','=',$project->id)->orderBy('created_at', 'desc')->first();

$type = ProjectType::find($project->type_id);
?>

@extends('component.layout', ['title' => $page])

@push('style')
<link media="all" type="text/css" rel="stylesheet" href="/components/x-editable/dist/bootstrap3-editable/css/bootstrap-editable.css">
<link media="all" type="text/css" rel="stylesheet" href="/plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css">
@endpush

@push('scripts')
<script src="/components/x-editable/dist/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
<script src="/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
@endpush

@push('jsinline')
<script type="text/javascript">
$(document).ready(function() {
    $('.only-end-total tr').each(function() {
        $(this).find("td").eq(2).hide();
        $(this).find("th").eq(2).hide();
        $(this).find("td").eq(3).hide();
        $(this).find("th").eq(3).hide();
        $(this).find("td").eq(4).hide();
        $(this).find("th").eq(4).hide();
        $(this).find("td").eq(5).hide();
        $(this).find("th").eq(5).hide();
    });

    $("[name='include-tax']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'}).on('switchChange.bootstrapSwitch', function(event, state) {
        if (state) {
            $('.hide-btw1 tr').each(function() {
                $(this).find("td").eq(4).show();
                $(this).find("th").eq(4).show();
                $(this).find("td").eq(5).show();
                $(this).find("th").eq(5).show();
                $(this).find("td").eq(6).show();
                $(this).find("th").eq(6).show();
            });
            $('.hide-btw2 tr').each(function() {
                $(this).find("td").eq(2).show();
                $(this).find("th").eq(2).show();
                $(this).find("td").eq(3).show();
                $(this).find("th").eq(3).show();
                $(this).find("td").eq(4).show();
                $(this).find("th").eq(4).show();
                $(this).find("td").eq(5).show();
                $(this).find("th").eq(5).show();
                $(this).find("td").eq(6).show();
                $(this).find("th").eq(6).show();
            });
            $('.hide-btw2').each(function() {
                $(this).find("tr").eq(2).show();
                $(this).find("tr").eq(3).show();
                $(this).find("tr").eq(4).show();
                $(this).find("tr").eq(5).show();
                $(this).find("tr").eq(6).show();
                $(this).find("tr").eq(7).show();
            });
        } else {
            $('.hide-btw1 tr').each(function() {
                $(this).find("td").eq(4).hide();
                $(this).find("th").eq(4).hide();
                $(this).find("td").eq(5).hide();
                $(this).find("th").eq(5).hide();
                $(this).find("td").eq(6).hide();
                $(this).find("th").eq(6).hide();
            });
            $('.hide-btw2 tr').each(function() {
                $(this).find("td").eq(2).hide();
                $(this).find("th").eq(2).hide();
                $(this).find("td").eq(3).hide();
                $(this).find("th").eq(3).hide();
                $(this).find("td").eq(4).hide();
                $(this).find("th").eq(4).hide();
                $(this).find("td").eq(5).hide();
                $(this).find("th").eq(5).hide();
                $(this).find("td").eq(6).hide();
                $(this).find("th").eq(6).hide();
            });
            $('.hide-btw2').each(function() {
                $(this).find("tr").eq(2).hide();
                $(this).find("tr").eq(3).hide();
                $(this).find("tr").eq(4).hide();
                $(this).find("tr").eq(5).hide();
                $(this).find("tr").eq(6).hide();
                $(this).find("tr").eq(7).hide();
            });
        }
    });
    $("[name='display-worktotals']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'}).on('switchChange.bootstrapSwitch', function(event, state) {
        if (state) {
            $("[name='display-specification']").bootstrapSwitch('disabled', false);
            console.log('ik ga nu aan')
            $('.show-activity').show();
        } else {
            $("[name='display-specification']").bootstrapSwitch('disabled', true);
        $('.show-activity').hide();
        }
    });

    $("[name='only-totals']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'}).on('switchChange.bootstrapSwitch', function(event, state) {
        if (state) {
            // $('.show-activity').show();
            // $('#ss').toggle();
            $("[name='seperate-subcon']").bootstrapSwitch('disabled', true);
        } else {
            $("[name='seperate-subcon']").bootstrapSwitch('disabled', false);
        // $('#ss').toggle();
        // $('.show-activity').hide();
        }
    });

    $("[name='seperate-subcon']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'}).on('switchChange.bootstrapSwitch', function(event, state) {
        if (state) {
            $('.show-all').show();
            $('.show-totals').hide();
        } else {
            $('.show-all').hide();
            $('.show-totals').show();
        }
    });
    $("[name='display-description']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'}).on('switchChange.bootstrapSwitch', function(event, state) {
        if (state) {
            $('.show-note').show();
        } else {
        $('.show-note').hide();
        }
    });
    $("[name='only-totals']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'}).on('switchChange.bootstrapSwitch', function(event, state) {
        if (state) {
            $('.only-total').hide();
            $('.hide-btw1').hide();
        $('.hide-btw2 tr').each(function() {
            $(this).find("td").eq(2).hide();
            $(this).find("th").eq(2).hide();
            $(this).find("td").eq(3).hide();
            $(this).find("th").eq(3).hide();

        });
        $('.hide-btw2').each(function() {
            $(this).find("tr").eq(2).hide();
            $(this).find("tr").eq(3).hide();
            $(this).find("tr").eq(4).hide();
            $(this).find("tr").eq(5).hide();
            $(this).find("tr").eq(6).hide();
            $(this).find("tr").eq(7).hide();
        });
        } else {
            $('.only-total').show();
        $('.hide-btw1').show();
        $('.hide-btw2 tr').each(function() {
            $(this).find("td").eq(2).show();
            $(this).find("th").eq(2).show();
            $(this).find("td").eq(3).show();
            $(this).find("th").eq(3).show();

        });
        $('.hide-btw2').each(function() {
            $(this).find("tr").eq(2).show();
            $(this).find("tr").eq(3).show();
            $(this).find("tr").eq(4).show();
            $(this).find("tr").eq(5).show();
            $(this).find("tr").eq(6).show();
            $(this).find("tr").eq(7).show();
        });
        }
    });
    $("[name='display-specification']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'}).on('switchChange.bootstrapSwitch', function(event, state) {
        if (state) {
        $('.only-end-total tr').each(function() {
            $(this).find("td").eq(2).show();
            $(this).find("th").eq(2).show();
            $(this).find("td").eq(3).show();
            $(this).find("th").eq(3).show();
            $(this).find("td").eq(4).show();
            $(this).find("th").eq(4).show();
            $(this).find("td").eq(5).show();
            $(this).find("th").eq(5).show();

        });
        } else {
        $('.only-end-total tr').each(function() {
            $(this).find("td").eq(2).hide();
            $(this).find("th").eq(2).hide();
            $(this).find("td").eq(3).hide();
            $(this).find("th").eq(3).hide();
            $(this).find("td").eq(4).hide();
            $(this).find("th").eq(4).hide();
            $(this).find("td").eq(5).hide();
            $(this).find("th").eq(5).hide();

        });
        }
    });
    $tpayment = false;
    $("[name='toggle-payment']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'}).on('switchChange.bootstrapSwitch', function(event, state) {
        if (state) {
            $("#amount").prop('disabled', false);
            $(".show-downpayment").show();
            $tpayment = true;
        } else {
            $("#amount").prop('disabled', true);
            $(".show-downpayment").hide();
            $tpayment = false;
        }

    });
    $('#terms').change(function(e){
        var q = $('#terms').val();
        if($.isNumeric(q)&&(q>1)) {
            $('.noterms').show('slow');
        } else {
            $('.noterms').hide('slow');
        }

    });
    $('#termModal').on('hidden.bs.modal', function() {
        var q = $('#terms').val();
        if($.isNumeric(q)&&(q>1)&&(q<=50)) {
            if($('input[name="toggle-payment"]').prop('checked'))
                $('#condition-text').html('Indien opdracht gegund wordt, ontvangt u '+q+' termijnen waarvan de eerste termijn een aanbetaling betreft á &euro; '+$('#amount').val()+'.');
            else
                $('#condition-text').html('Indien opdracht gegund wordt, ontvangt u '+q+' termijnen waarvan de laatste een eindfactuur.');
        } else {
            $('#condition-text').text('Indien opdracht gegund wordt, ontvangt u één eindfactuur.');
        }

    });

    $('.osave').click(function(e){
        e.preventDefault();
        $('#frm-offer').submit();
    });

    $('#adressing').text($('#to_contact option:selected').text());
    $('#to_contact').change(function(e){
        $('#adressing').text($('#to_contact option:selected').text());
    });
    $('.offdate').datepicker().on('changeDate', function(e){
        $('.offdate').datepicker('hide');
        $('#offdateval').val(e.date.toISOString());
        $('.offdate').text(e.date.getDate() + "-" + (e.date.getMonth() + 1)  + "-" + e.date.getFullYear());
    });
    @if ($offer_last && $offer_last->offer_make)
    $('.offdate').text("{{ date('d-m-Y', strtotime($offer_last->offer_make)) }}");

        @if (!$offer_last->include_tax)
            $("[name='include-tax']").bootstrapSwitch('toggleState');
        @endif

        @if ($offer_last->only_totals)
            $("[name='only-totals']").bootstrapSwitch('toggleState');
        @endif

        @if ($offer_last->seperate_subcon)
            $("[name='seperate-subcon']").bootstrapSwitch('toggleState');
        @endif

        @if ($offer_last->display_worktotals)
            $("[name='display-worktotals']").bootstrapSwitch('toggleState');
        @endif

        @if ($offer_last->display_specification)
            $("[name='display-specification']").bootstrapSwitch('toggleState');
        @endif

        @if ($offer_last->display_description)
            $("[name='display-description']").bootstrapSwitch('toggleState');
        @endif

    @endif
});
</script>
@endpush

@section('component_buttons')
<div class="pull-right">
    @if (!$project->project_close)

    @if ($offer_last)

    @if (!$offer_last->offer_finish)
    <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#myModal"><i class="fa fa-sliders">&nbsp;</i>Opties</a>
    <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#termModal"><i class="fa fa-pie-chart">&nbsp;</i>Termijnen</a>
    <button class="btn btn-primary osave"><i class="fa fa-file-pdf-o">&nbsp;</i>Voorbeeld</button>
    @endif

    @else {{-- project_close --}}

    <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#myModal"><i class="fa fa-sliders">&nbsp;</i>Opties</a>
    <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#termModal"><i class="fa fa-pie-chart">&nbsp;</i>Termijnen</a>
    @if (CalculationEndresult::totalProject($project))
    <button class="btn btn-primary osave"><i class="fa fa-file-pdf-o">&nbsp;</i>Voorbeeld</button>
    @endif

    @endif {{-- offer_last --}}

    @endif {{-- project_close --}}
</div>
@endsection

@section('component_content')
<div class="white-row">

    {{-- Content, CON & SUBCON --}}
    <div class="show-all">
        <h4 class="only-total">Specificatie offerte</h4>
        <!-- <div class="only-total"><strong><u>AANNEMING</u></strong></div> -->
        <table class="table table-striped hide-btw1">
            <thead>
                <tr>
                    <th class="col-md-4">AANNEMING</th>
                    <th class="col-md-1">&nbsp;</th>
                    <th class="col-md-2">Bedrag (excl. BTW)</th>
                    <th class="col-md-1">&nbsp;</th>
                    @if (!$project->tax_reverse)<th class="col-md-1">BTW</th>@endif
                    @if (!$project->tax_reverse)<th class="col-md-2">BTW bedrag</th>@endif
                    @if (!$project->tax_reverse)<th class="col-md-1">&nbsp;</th>@endif
                </tr>
            </thead>
            <tbody>
                @if (!$project->tax_reverse)
                <tr>
                    <td class="col-md-4">Arbeidskosten</td>
                    <td class="col-md-1">@if(0) {{ ''.number_format(CalculationEndresult::conCalcLaborActivityTax1($project), 2, ",",".") }} @endif &nbsp;</td>
                    <td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
                    <td class="col-md-1">&nbsp;</td>
                    <td class="col-md-1">21%</td>
                    <td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax1AmountTax($project), 2, ",",".") }}</td>
                    <td class="col-md-1">&nbsp;</td>
                </tr>
                <tr>
                    <td class="col-md-4">&nbsp;</td>
                    <td class="col-md-1">@if(0) {{ ''.number_format(CalculationEndresult::conCalcLaborActivityTax2($project), 2, ",",".") }} @endif &nbsp;</td>
                    <td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
                    <td class="col-md-1">&nbsp;</td>
                    <td class="col-md-1">6%</td>
                    <td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax2AmountTax($project), 2, ",",".") }}</td>
                    <td class="col-md-1">&nbsp;</td>
                </tr>
                @else
                <tr>
                    <td class="col-md-4">Arbeidskosten</td>
                    <td class="col-md-1">@if(0) {{ ''.number_format(CalculationEndresult::conCalcLaborActivityTax3($project), 2, ",",".") }} @endif &nbsp;</td>
                    <td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
                    <td class="col-md-1">&nbsp;</td>
                    @if (!$project->tax_reverse)<td class="col-md-1">0%</td>@endif
                    @if (!$project->tax_reverse)<td class="col-md-2">&nbsp;</td>@endif
                    @if (!$project->tax_reverse)<td class="col-md-1">&nbsp;</td>@endif
                </tr>
                @endif

                @if (!$project->tax_reverse)
                <tr>
                    <td class="col-md-4">Materiaalkosten</td>
                    <td class="col-md-1">&nbsp;</td>
                    <td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
                    <td class="col-md-1">&nbsp;</td>
                    <td class="col-md-1">21%</td>
                    <td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax1AmountTax($project), 2, ",",".") }}</td>
                    <td class="col-md-1">&nbsp;</td>
                </tr>
                <tr>
                    <td class="col-md-4">&nbsp;</td>
                    <td class="col-md-1">&nbsp;</td>
                    <td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
                    <td class="col-md-1">&nbsp;</td>
                    <td class="col-md-1">6%</td>
                    <td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax2AmountTax($project), 2, ",",".") }}</td>
                    <td class="col-md-1">&nbsp;</td>
                </tr>
                @else
                <tr>
                    <td class="col-md-4">Materiaalkosten</td>
                    <td class="col-md-1">&nbsp;</td>
                    <td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
                    <td class="col-md-1">&nbsp;</td>
                    @if (!$project->tax_reverse)<td class="col-md-1">0%</td>@endif
                    @if (!$project->tax_reverse)<td class="col-md-2">&nbsp;</td>@endif
                    @if (!$project->tax_reverse)<td class="col-md-1">&nbsp;</td>@endif
                </tr>
                @endif

                @if ($project->use_equipment)
                @if (!$project->tax_reverse)
                <tr>
                    <td class="col-md-4">Overige kosten</td>
                    <td class="col-md-1">&nbsp;</td>
                    <td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
                    <td class="col-md-1">&nbsp;</td>
                    <td class="col-md-1">21%</td>
                    <td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax1AmountTax($project), 2, ",",".") }}</td>
                    <td class="col-md-1">&nbsp;</td>
                </tr>
                <tr>
                    <td class="col-md-4">&nbsp;</td>
                    <td class="col-md-1">&nbsp;</td>
                    <td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
                    <td class="col-md-1">&nbsp;</td>
                    <td class="col-md-1">6%</td>
                    <td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax2AmountTax($project), 2, ",",".") }}</td>
                    <td class="col-md-1">&nbsp;</td>
                </tr>
                @else
                <tr>
                    <td class="col-md-4">Overige kosten</td>
                    <td class="col-md-1">&nbsp;</td>
                    <td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
                    <td class="col-md-1">&nbsp;</td>
                    @if (!$project->tax_reverse)<td class="col-md-1">0%</td>@endif
                    @if (!$project->tax_reverse)<td class="col-md-2">&nbsp;</td>@endif
                    @if (!$project->tax_reverse)<td class="col-md-1">&nbsp;</td>@endif
                </tr>
                @endif
                @endif

                <tr>
                    <td class="col-md-4"><strong>Totaal</strong></td>
                    <td class="col-md-1">&nbsp;</td>
                    <td class="col-md-2"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalContracting($project), 2, ",",".") }}</strong></td>
                    <td class="col-md-1">&nbsp;</td>
                    @if (!$project->tax_reverse)<td class="col-md-1">&nbsp;</td>@endif
                    @if (!$project->tax_reverse)<td class="col-md-2"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalContractingTax($project), 2, ",",".") }}</strong></td>@endif
                    @if (!$project->tax_reverse)<td class="col-md-1">&nbsp;</td>@endif
                </tr>
            </tbody>
        </table>

        <hr>

        <table class="table table-striped hide-btw1">
            <thead>
                <tr>
                    <th class="col-md-4">ONDERAANNEMING</th>
                    <th class="col-md-1">&nbsp;</th>
                    <th class="col-md-2">Bedrag (excl. BTW)</th>
                    <th class="col-md-1">&nbsp;</th>
                    @if (!$project->tax_reverse)<th class="col-md-1">BTW</th>@endif
                    @if (!$project->tax_reverse)<th class="col-md-2">BTW bedrag</th>@endif
                    @if (!$project->tax_reverse)<th class="col-md-1">&nbsp;</th>@endif
                </tr>
            </thead>
            <tbody>
                @if (!$project->tax_reverse)
                <tr>
                    <td class="col-md-4">Arbeidskosten</td>
                    <td class="col-md-1">@if(0) {{ ' '.number_format(CalculationEndresult::subconCalcLaborActivityTax1($project), 2, ",",".") }} @endif &nbsp;</td>
                    <td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
                    <td class="col-md-1">&nbsp;</td>
                    <td class="col-md-1">21%</td>
                    <td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax1AmountTax($project), 2, ",",".") }}</td>
                    <td class="col-md-1">&nbsp;</td>
                </tr>
                <tr>
                    <td class="col-md-4">&nbsp;</td>
                    <td class="col-md-1">@if(0) {{ ' '.number_format(CalculationEndresult::subconCalcLaborActivityTax2($project), 2, ",",".") }} @endif &nbsp;</td>
                    <td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
                    <td class="col-md-1">&nbsp;</td>
                    <td class="col-md-1">6%</td>
                    <td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax2AmountTax($project), 2, ",",".") }}</td>
                    <td class="col-md-1">&nbsp;</td>
                </tr>
                @else
                <tr>
                    <td class="col-md-4">Arbeidskosten</td>
                    <td class="col-md-1">@if(0) {{ ''.number_format(CalculationEndresult::subconCalcLaborActivityTax3($project), 2, ",",".") }} @endif &nbsp;</td>
                    <td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
                    <td class="col-md-1">&nbsp;</td>
                    @if (!$project->tax_reverse)<td class="col-md-1">0%</td>@endif
                    @if (!$project->tax_reverse)<td class="col-md-2">&nbsp;</td>@endif
                    @if (!$project->tax_reverse)<td class="col-md-1">&nbsp;</td>@endif
                </tr>
                @endif

                @if (!$project->tax_reverse)
                <tr>
                    <td class="col-md-4">Materiaalkosten</td>
                    <td class="col-md-1">&nbsp;</td>
                    <td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
                    <td class="col-md-1">&nbsp;</td>
                    <td class="col-md-1">21%</td>
                    <td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcMaterialActivityTax1AmountTax($project), 2, ",",".") }}</td>
                    <td class="col-md-1">&nbsp;</td>
                </tr>
                <tr>
                    <td class="col-md-4">&nbsp;</td>
                    <td class="col-md-1">&nbsp;</td>
                    <td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
                    <td class="col-md-1">&nbsp;</td>
                    <td class="col-md-1">6%</td>
                    <td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcMaterialActivityTax2AmountTax($project), 2, ",",".") }}</td>
                    <td class="col-md-1">&nbsp;</td>
                </tr>
                @else
                <tr>
                    <td class="col-md-4">Materiaalkosten</td>
                    <td class="col-md-1">&nbsp;</td>
                    <td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
                    <td class="col-md-1">&nbsp;</td>
                    @if (!$project->tax_reverse)<td class="col-md-1">0%</td>@endif
                    @if (!$project->tax_reverse)<td class="col-md-2">&nbsp;</td>@endif
                    @if (!$project->tax_reverse)<td class="col-md-1">&nbsp;</td>@endif
                </tr>
                @endif

                @if ($project->use_equipment)
                @if (!$project->tax_reverse)
                <tr>
                    <td class="col-md-4">Overige kosten</td>
                    <td class="col-md-1">&nbsp;</td>
                    <td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
                    <td class="col-md-1">&nbsp;</td>
                    <td class="col-md-1">21%</td>
                    <td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcEquipmentActivityTax1AmountTax($project), 2, ",",".") }}</td>
                    <td class="col-md-1">&nbsp;</td>
                </tr>
                <tr>
                    <td class="col-md-4">&nbsp;</td>
                    <td class="col-md-1">&nbsp;</td>
                    <td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
                    <td class="col-md-1">&nbsp;</td>
                    <td class="col-md-1">6%</td>
                    <td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcEquipmentActivityTax2AmountTax($project), 2, ",",".") }}</td>
                    <td class="col-md-1">&nbsp;</td>
                </tr>
                @else
                <tr>
                    <td class="col-md-4">Overige kosten</td>
                    <td class="col-md-1">&nbsp;</td>
                    <td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
                    <td class="col-md-1">&nbsp;</td>
                    @if (!$project->tax_reverse)<td class="col-md-1">0%</td>@endif
                    @if (!$project->tax_reverse)<td class="col-md-2">&nbsp;</td>@endif
                    @if (!$project->tax_reverse)<td class="col-md-1">&nbsp;</td>@endif
                </tr>
                @endif
                @endif

                <tr>
                    <td class="col-md-4"><strong>Totaal</strong></td>
                    <td class="col-md-1">&nbsp;</td>
                    <td class="col-md-2"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
                    <td class="col-md-1">&nbsp;</td>
                    @if (!$project->tax_reverse)<td class="col-md-1">&nbsp;</td>@endif
                    @if (!$project->tax_reverse)<td class="col-md-2"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalSubcontractingTax($project), 2, ",",".") }}</strong></td>@endif
                    @if (!$project->tax_reverse)<td class="col-md-1">&nbsp;</td>@endif
                </tr>
            </tbody>
        </table>
    </div>
    {{-- /Content, CON & SUBCON --}}

    {{-- Content total --}}
    <div class="show-totals">
        <h4 class="only-total">Specificatie offerte</h4>

        @if($type->type_name != 'snelle offerte en factuur')
        <table class="table table-striped hide-btw1">
            <thead>
                <tr>
                    <th class="col-md-4">&nbsp;</th>
                    <th class="col-md-1">&nbsp;</th>
                    <th class="col-md-2">Bedrag (excl. BTW)</th>
                    <th class="col-md-1">&nbsp;</th>
                    @if (!$project->tax_reverse)<th class="col-md-1">BTW</th>@endif
                    @if (!$project->tax_reverse)<th class="col-md-1">BTW bedrag</th>@endif
                    @if (!$project->tax_reverse)<th class="col-md-2">&nbsp;</th>@endif
                </tr>
            </thead>
            <tbody>
                @if (!$project->tax_reverse)
                <tr>
                    <td class="col-md-4">Arbeidskosten</td>
                    <td class="col-md-1">@if(0) {{ ''.number_format(CalculationEndresult::conCalcLaborActivityTax1($project)+CalculationEndresult::subconCalcLaborActivityTax1($project), 2, ",",".") }} @endif &nbsp;</td>
                    <td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax1Amount($project)+CalculationEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
                    <td class="col-md-1">&nbsp;</td>
                    <td class="col-md-1">21%</td>
                    <td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax1AmountTax($project)+CalculationEndresult::subconCalcLaborActivityTax1AmountTax($project), 2, ",",".") }}</td>
                    <td class="col-md-2">&nbsp;</td>
                </tr>
                <tr>
                    <td class="col-md-4">&nbsp;</td>
                    <td class="col-md-1">@if(0) {{ ''.number_format(CalculationEndresult::conCalcLaborActivityTax2($project)+CalculationEndresult::subconCalcLaborActivityTax2($project), 2, ",",".") }} @endif &nbsp;</td>
                    <td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax2Amount($project)+CalculationEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
                    <td class="col-md-1">&nbsp;</td>
                    <td class="col-md-1">6%</td>
                    <td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax2AmountTax($project)+CalculationEndresult::subconCalcLaborActivityTax2AmountTax($project), 2, ",",".") }}</td>
                    <td class="col-md-2">&nbsp;</td>
                </tr>
                @else
                <tr>
                    <td class="col-md-4">Arbeidskosten</td>
                    <td class="col-md-1">@if(0) {{ ''.number_format(CalculationEndresult::conCalcLaborActivityTax3($project)+CalculationEndresult::subconCalcLaborActivityTax3($project), 2, ",",".") }} @endif &nbsp;</td>
                    <td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax3Amount($project)+CalculationEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
                    <td class="col-md-1">&nbsp;</td>
                    @if (!$project->tax_reverse)<td class="col-md-1">0%</td>@endif
                    @if (!$project->tax_reverse)<td class="col-md-1">&nbsp;</td>@endif
                    @if (!$project->tax_reverse)<td class="col-md-2">&nbsp;</td>@endif
                </tr>
                @endif

                @if (!$project->tax_reverse)
                <tr>
                    <td class="col-md-4">Materiaalkosten</td>
                    <td class="col-md-1">&nbsp;</td>
                    <td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax1Amount($project)+CalculationEndresult::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
                    <td class="col-md-1">&nbsp;</td>
                    <td class="col-md-1">21%</td>
                    <td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax1AmountTax($project)+CalculationEndresult::subconCalcMaterialActivityTax1AmountTax($project), 2, ",",".") }}</td>
                    <td class="col-md-2">&nbsp;</td>
                </tr>
                <tr>
                    <td class="col-md-4">&nbsp;</td>
                    <td class="col-md-1">&nbsp;</td>
                    <td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax2Amount($project)+CalculationEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
                    <td class="col-md-1">&nbsp;</td>
                    <td class="col-md-1">6%</td>
                    <td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax2AmountTax($project)+CalculationEndresult::subconCalcMaterialActivityTax2AmountTax($project), 2, ",",".") }}</td>
                    <td class="col-md-2">&nbsp;</td>
                </tr>
                @else
                <tr>
                    <td class="col-md-4">Materiaalkosten</td>
                    <td class="col-md-1">&nbsp;</td>
                    <td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax3Amount($project)+CalculationEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
                    <td class="col-md-1">&nbsp;</td>
                    @if (!$project->tax_reverse)<td class="col-md-1">0%</td>@endif
                    @if (!$project->tax_reverse)<td class="col-md-1">&nbsp;</td>@endif
                    @if (!$project->tax_reverse)<td class="col-md-2">&nbsp;</td>@endif
                </tr>
                @endif

                @if ($project->use_equipment)
                @if (!$project->tax_reverse)
                <tr>
                    <td class="col-md-4">Overige kosten</td>
                    <td class="col-md-1">&nbsp;</td>
                    <td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax1Amount($project)+CalculationEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
                    <td class="col-md-1">&nbsp;</td>
                    <td class="col-md-1">21%</td>
                    <td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax1AmountTax($project)+CalculationEndresult::subconCalcEquipmentActivityTax1AmountTax($project), 2, ",",".") }}</td>
                    <td class="col-md-2">&nbsp;</td>
                </tr>
                <tr>
                    <td class="col-md-4">&nbsp;</td>
                    <td class="col-md-1">&nbsp;</td>
                    <td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax2Amount($project)+CalculationEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
                    <td class="col-md-1">&nbsp;</td>
                    <td class="col-md-1">6%</td>
                    <td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax2AmountTax($project)+CalculationEndresult::subconCalcEquipmentActivityTax2AmountTax($project), 2, ",",".") }}</td>
                    <td class="col-md-2">&nbsp;</td>
                </tr>
                @else
                <tr>
                    <td class="col-md-4">Overige kosten</td>
                    <td class="col-md-1">&nbsp;</td>
                    <td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax3Amount($project)+CalculationEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
                    <td class="col-md-1">&nbsp;</td>
                    @if (!$project->tax_reverse)<td class="col-md-1">0%</td>@endif
                    @if (!$project->tax_reverse)<td class="col-md-1">&nbsp;</td>@endif
                    @if (!$project->tax_reverse)<td class="col-md-2">&nbsp;</td>@endif
                </tr>
                @endif
                @endif

                <tr>
                    <td class="col-md-4"><strong>Totaal</strong></td>
                    <td class="col-md-1">&nbsp;</td>
                    <td class="col-md-2"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalContracting($project)+CalculationEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
                    <td class="col-md-1">&nbsp;</td>
                    @if (!$project->tax_reverse)<td class="col-md-1">&nbsp;</td>@endif
                    @if (!$project->tax_reverse)<td class="col-md-1"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalContractingTax($project)+CalculationEndresult::totalSubcontractingTax($project), 2, ",",".") }}</strong></td>@endif
                    @if (!$project->tax_reverse)<td class="col-md-2">&nbsp;</td>@endif
                </tr>
            </tbody>
        </table>
        @else
        <table class="table table-striped hide-btw1">
            <thead>
                <tr>
                    <th class="col-md-4">Omschrijving</th>
                    <th class="col-md-2">€ / Eenh (excl. BTW)</th>
                    <th class="col-md-1">Aantal</th>
                    <th class="col-md-1">Totaal</th>
                    <th class="col-md-1">BTW</th>
                    <th class="col-md-1">BTW bedrag</th>
                </tr>
            </thead>
            <tbody>
                @foreach (BlancRow::where('project_id','=', $project->id)->get() as $row)
                <tr>
                    <td class="col-md-4">{{ $row->description }}</td>
                    <td class="col-md-2">{{ '&euro; '.number_format($row->rate, 2, ",",".") }}</td>
                    <td class="col-md-1">{{ '&euro; '.number_format($row->amount, 2, ",",".") }}</td>
                    <td class="col-md-1">{{ '&euro; '.number_format($row->rate * $row->amount, 2, ",",".") }}</td>
                    <td class="col-md-1">{{ Tax::find($row->tax_id)->tax_rate }}%</td>
                    <td class="col-md-1">{{ '&euro; '.number_format(($row->rate * $row->amount/100) * Tax::find($row->tax_id)->tax_rate, 2, ",",".") }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
    {{-- /Content total --}}


</div>
@stop
