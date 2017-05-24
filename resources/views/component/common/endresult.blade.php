{{--
 * Copyright (C) 2017 Bynq.io B.V.
 * All Rights Reserved
 *
 * This file is part of the Dynq project.
 *
 * Content can not be copied and/or distributed without the express
 * permission of the author.
 *
 * @package  Dynq
 * @author   Yorick de Wid <y.dewid@calculatietool.com>
--}}

{{-- Contracting --}}
<h4>Aanneming</h4>
<table class="table table-striped">
    <thead>
        <tr>
            <th class="col-md-4">&nbsp;</th>
            <th class="col-md-1">Uren</th>
            <th class="col-md-2">Bedrag (excl. BTW)</th>
            <th class="col-md-1">&nbsp;</th>
            <th class="col-md-1">BTW</th>
            <th class="col-md-1">BTW bedrag</th>
            <th class="col-md-2">&nbsp;</th>
        </tr>
    </thead>

    <tbody>
        <?php $header = false; ?>
        @if (!$project->tax_reverse)
        @if ($calculus::conCalcLaborActivityTax1Amount($project))
        <tr>
            <td class="col-md-4"><?php echo "Arbeidskosten"; $header = true; ?></td>
            <td class="col-md-1">{{ number_format($calculus::conCalcLaborActivityTax1($project), 2, ",",".") }}</td>
            <td class="col-md-2">{{ '&euro; '.number_format($calculus::conCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">21%</td>
            <td class="col-md-1">{{ '&euro; '.number_format($calculus::conCalcLaborActivityTax1AmountTax($project), 2, ",",".") }}</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @if ($calculus::conCalcLaborActivityTax2Amount($project))
        <tr>
            <td class="col-md-4"><?php echo !$header ? "Arbeidskosten" : "" ?></td>
            <td class="col-md-1">{{ number_format($calculus::conCalcLaborActivityTax2($project), 2, ",",".") }}</td>
            <td class="col-md-2">{{ '&euro; '.number_format($calculus::conCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">6%</td>
            <td class="col-md-1">{{ '&euro; '.number_format($calculus::conCalcLaborActivityTax2AmountTax($project), 2, ",",".") }}</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @else
        @if ($calculus::conCalcLaborActivityTax3Amount($project))
        <tr>
            <td class="col-md-4">Arbeidskosten</td>
            <td class="col-md-1">{{ number_format($calculus::conCalcLaborActivityTax3($project), 2, ",",".") }}</td>
            <td class="col-md-2">{{ '&euro; '.number_format($calculus::conCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">0%</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @endif

        <?php $header = false; ?>
        @if (!$project->tax_reverse)
        @if ($calculus::conCalcMaterialActivityTax1Amount($project))
        <tr>
            <td class="col-md-4"><?php echo "Materiaalkosten"; $header = true; ?></td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-2">{{ '&euro; '.number_format($calculus::conCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">21%</td>
            <td class="col-md-1">{{ '&euro; '.number_format($calculus::conCalcMaterialActivityTax1AmountTax($project), 2, ",",".") }}</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @if ($calculus::conCalcMaterialActivityTax2Amount($project))
        <tr>
            <td class="col-md-4"><?php echo !$header ? "Materiaalkosten" : "" ?></td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-2">{{ '&euro; '.number_format($calculus::conCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">6%</td>
            <td class="col-md-1">{{ '&euro; '.number_format($calculus::conCalcMaterialActivityTax2AmountTax($project), 2, ",",".") }}</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @else
        @if ($calculus::conCalcMaterialActivityTax3Amount($project))
        <tr>
            <td class="col-md-4">Materiaalkosten</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-2">{{ '&euro; '.number_format($calculus::conCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">0%</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @endif

        @if ($project->use_equipment)
        <?php $header = false; ?>
        @if (!$project->tax_reverse)
        @if ($calculus::conCalcEquipmentActivityTax1Amount($project))
        <tr>
            <td class="col-md-4"><?php echo "Overige kosten"; $header = true; ?></td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-2">{{ '&euro; '.number_format($calculus::conCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">21%</td>
            <td class="col-md-1">{{ '&euro; '.number_format($calculus::conCalcEquipmentActivityTax1AmountTax($project), 2, ",",".") }}</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @if ($calculus::conCalcEquipmentActivityTax2Amount($project))
        <tr>
            <td class="col-md-4"><?php echo !$header ? "Overige kosten" : "" ?></td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-2">{{ '&euro; '.number_format($calculus::conCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">6%</td>
            <td class="col-md-1">{{ '&euro; '.number_format($calculus::conCalcEquipmentActivityTax2AmountTax($project), 2, ",",".") }}</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @else
        @if ($calculus::conCalcEquipmentActivityTax3Amount($project))
        <tr>
            <td class="col-md-4">Overige kosten</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-2">{{ '&euro; '.number_format($calculus::conCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">0%</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @endif
        @endif

        <tr>
            <td class="col-md-4"><strong>Totaal Aanneming</strong></td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-2"><strong>{{ '&euro; '.number_format($calculus::totalContracting($project), 2, ",",".") }}</strong></td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1"><strong>{{ '&euro; '.number_format($calculus::totalContractingTax($project), 2, ",",".") }}</strong></td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
    </tbody>
</table>
{{-- /Contracting --}}

{{-- Subcontracting --}}
@if ($project->use_subcontract)
<h4>Onderaanneming</h4>
<table class="table table-striped">
    <thead>
        <tr>
            <th class="col-md-4">&nbsp;</th>
            <th class="col-md-1">Uren</th>
            <th class="col-md-2">Bedrag (excl. BTW)</th>
            <th class="col-md-1">&nbsp;</th>
            <th class="col-md-1">BTW</th>
            <th class="col-md-1">BTW bedrag</th>
            <th class="col-md-2">&nbsp;</th>
        </tr>
    </thead>

    <tbody>
        <?php $header = false; ?>
        @if (!$project->tax_reverse)
        @if ($calculus::subconCalcLaborActivityTax1Amount($project))
        <tr>
            <td class="col-md-4"><?php echo "Arbeidskosten"; $header = true; ?></td>
            <td class="col-md-1">{{ number_format($calculus::subconCalcLaborActivityTax1($project), 2, ",",".") }}</td>
            <td class="col-md-2">{{ '&euro; '.number_format($calculus::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">21%</td>
            <td class="col-md-1">{{ '&euro; '.number_format($calculus::subconCalcLaborActivityTax1AmountTax($project), 2, ",",".") }}</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @if ($calculus::subconCalcLaborActivityTax2Amount($project))
        <tr>
            <td class="col-md-4"><?php echo !$header ? "Arbeidskosten" : "" ?></td>
            <td class="col-md-1">{{ number_format($calculus::subconCalcLaborActivityTax2($project), 2, ",",".") }}</td>
            <td class="col-md-2">{{ '&euro; '.number_format($calculus::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">6%</td>
            <td class="col-md-1">{{ '&euro; '.number_format($calculus::subconCalcLaborActivityTax2AmountTax($project), 2, ",",".") }}</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @else
        @if ($calculus::subconCalcLaborActivityTax3($project))
        <tr>
            <td class="col-md-4">Arbeidskosten</td>
            <td class="col-md-1">{{ number_format($calculus::subconCalcLaborActivityTax3($project), 2, ",",".") }}</td>
            <td class="col-md-2">{{ '&euro; '.number_format($calculus::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">0%</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @endif

        <?php $header = false; ?>
        @if (!$project->tax_reverse)
        @if ($calculus::subconCalcMaterialActivityTax1Amount($project))
        <tr>
            <td class="col-md-4"><?php echo "Materiaalkosten"; $header = true; ?></td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-2">{{ '&euro; '.number_format($calculus::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">21%</td>
            <td class="col-md-1">{{ '&euro; '.number_format($calculus::subconCalcMaterialActivityTax1AmountTax($project), 2, ",",".") }}</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @if ($calculus::subconCalcMaterialActivityTax2Amount($project))
        <tr>
            <td class="col-md-4"><?php echo !$header ? "Materiaalkosten" : "" ?></td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-2">{{ '&euro; '.number_format($calculus::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">6%</td>
            <td class="col-md-1">{{ '&euro; '.number_format($calculus::subconCalcMaterialActivityTax2AmountTax($project), 2, ",",".") }}</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @else
        @if ($calculus::subconCalcMaterialActivityTax3Amount($project))
        <tr>
            <td class="col-md-4">Materiaalkosten</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-2">{{ '&euro; '.number_format($calculus::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">0%</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @endif

        @if ($project->use_equipment)
        <?php $header = false; ?>
        @if (!$project->tax_reverse)
        @if ($calculus::subconCalcEquipmentActivityTax1Amount($project))
        <tr>
            <td class="col-md-4"><?php echo "Overige kosten"; $header = true; ?></td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-2">{{ '&euro; '.number_format($calculus::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">21%</td>
            <td class="col-md-1">{{ '&euro; '.number_format($calculus::subconCalcEquipmentActivityTax1AmountTax($project), 2, ",",".") }}</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @if ($calculus::subconCalcEquipmentActivityTax2Amount($project))
        <tr>
            <td class="col-md-4"><?php echo !$header ? "Overige kosten" : "" ?></td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-2">{{ '&euro; '.number_format($calculus::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">6%</td>
            <td class="col-md-1">{{ '&euro; '.number_format($calculus::subconCalcEquipmentActivityTax2AmountTax($project), 2, ",",".") }}</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @else
        @if ($calculus::subconCalcEquipmentActivityTax3Amount($project))
        <tr>
            <td class="col-md-4">Overige kosten</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-2">{{ '&euro; '.number_format($calculus::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</span></td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">0%</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @endif
        @endif

        <tr>
            <td class="col-md-4"><strong>Totaal Onderaanneming </strong></td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-2"><strong>{{ '&euro; '.number_format($calculus::totalSubcontracting($project), 2, ",",".") }}</strong></td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1"><strong>{{ '&euro; '.number_format($calculus::totalSubcontractingTax($project), 2, ",",".") }}</strong></td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
    </tbody>
</table>
@endif
{{-- /Subcontracting --}}

{{-- Project totals --}}
<h4>Totalen project</h4>
<table class="table table-striped">
    <thead>
        <tr>
            <th class="col-md-5">&nbsp;</th>
            <th class="col-md-2">Bedrag (excl. BTW)</th>
            <th class="col-md-1">&nbsp;</th>
            <th class="col-md-1">&nbsp;</th>
            <th class="col-md-1">BTW bedrag</th>
            <th class="col-md-2"><span class="pull-right">Bedrag (incl. BTW)</span></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="col-md-5"><strong>Calculatief te offreren (excl. BTW)<strong></td>
            <td class="col-md-2"><strong>{{ '&euro; '.number_format($calculus::totalProject($project), 2, ",",".") }}</strong></td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @if (!$project->tax_reverse)
        @if ($calculus::totalContractingTax1($project))
        <tr>
            <td class="col-md-5">BTW bedrag aanneming 21%</td>
            <td class="col-md-2">&nbsp;</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">{{ '&euro; '.number_format($calculus::totalContractingTax1($project), 2, ",",".") }}</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @if ($calculus::totalContractingTax2($project))
        <tr>
            <td class="col-md-5">BTW bedrag aanneming 6%</td>
            <td class="col-md-2">&nbsp;</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">{{ '&euro; '.number_format($calculus::totalContractingTax2($project), 2, ",",".") }}</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @if ($calculus::totalSubcontractingTax1($project))
        <tr>
            <td class="col-md-5">BTW bedrag onderaanneming 21%</td>
            <td class="col-md-2">&nbsp;</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">{{ '&euro; '.number_format($calculus::totalSubcontractingTax1($project), 2, ",",".") }}</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @if ($calculus::totalSubcontractingTax2($project))
        <tr>
            <td class="col-md-5">BTW bedrag onderaanneming 6%</td>
            <td class="col-md-2">&nbsp;</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">{{ '&euro; '.number_format($calculus::totalSubcontractingTax2($project), 2, ",",".") }}</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @endif
        <tr>
            <td class="col-md-5"><strong>Te offreren BTW bedrag</strong></td>
            <td class="col-md-2">&nbsp;</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1"><strong>{{ '&euro; '.number_format($calculus::totalProjectTax($project), 2, ",",".") }}</strong></td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        <tr>
            <td class="col-md-5"><strong>Calculatief te offreren (Incl. BTW)</strong></td>
            <td class="col-md-2">&nbsp;</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-2"><strong class="pull-right">{{ '&euro; '.number_format($calculus::superTotalProject($project), 2, ",",".") }}</strong></td>
        </tr>

    </tbody>

</table>
{{-- /Project totals --}}
