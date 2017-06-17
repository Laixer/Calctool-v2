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
            <td class="col-md-1">@money($calculus::conCalcLaborActivityTax1($project), false)</td>
            <td class="col-md-2">@money($calculus::conCalcLaborActivityTax1Amount($project))</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">21%</td>
            <td class="col-md-1">@money($calculus::conCalcLaborActivityTax1AmountTax($project))</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @if ($calculus::conCalcLaborActivityTax2Amount($project))
        <tr>
            <td class="col-md-4"><?php echo !$header ? "Arbeidskosten" : "" ?></td>
            <td class="col-md-1">@money($calculus::conCalcLaborActivityTax2($project), false)</td>
            <td class="col-md-2">@money($calculus::conCalcLaborActivityTax2Amount($project))</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">6%</td>
            <td class="col-md-1">@money($calculus::conCalcLaborActivityTax2AmountTax($project))</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @else
        @if ($calculus::conCalcLaborActivityTax3Amount($project))
        <tr>
            <td class="col-md-4">Arbeidskosten</td>
            <td class="col-md-1">@money($calculus::conCalcLaborActivityTax3($project), false)</td>
            <td class="col-md-2">@money($calculus::conCalcLaborActivityTax3Amount($project))</td>
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
            <td class="col-md-2">@money($calculus::conCalcMaterialActivityTax1Amount($project))</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">21%</td>
            <td class="col-md-1">@money($calculus::conCalcMaterialActivityTax1AmountTax($project))</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @if ($calculus::conCalcMaterialActivityTax2Amount($project))
        <tr>
            <td class="col-md-4"><?php echo !$header ? "Materiaalkosten" : "" ?></td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-2">@money($calculus::conCalcMaterialActivityTax2Amount($project))</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">6%</td>
            <td class="col-md-1">@money($calculus::conCalcMaterialActivityTax2AmountTax($project))</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @else
        @if ($calculus::conCalcMaterialActivityTax3Amount($project))
        <tr>
            <td class="col-md-4">Materiaalkosten</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-2">@money($calculus::conCalcMaterialActivityTax3Amount($project))</td>
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
            <td class="col-md-2">@money($calculus::conCalcEquipmentActivityTax1Amount($project))</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">21%</td>
            <td class="col-md-1">@money($calculus::conCalcEquipmentActivityTax1AmountTax($project))</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @if ($calculus::conCalcEquipmentActivityTax2Amount($project))
        <tr>
            <td class="col-md-4"><?php echo !$header ? "Overige kosten" : "" ?></td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-2">@money($calculus::conCalcEquipmentActivityTax2Amount($project))</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">6%</td>
            <td class="col-md-1">@money($calculus::conCalcEquipmentActivityTax2AmountTax($project))</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @else
        @if ($calculus::conCalcEquipmentActivityTax3Amount($project))
        <tr>
            <td class="col-md-4">Overige kosten</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-2">@money($calculus::conCalcEquipmentActivityTax3Amount($project))</td>
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
            <td class="col-md-2"><strong>@money($calculus::totalContracting($project))</strong></td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1"><strong>@money($calculus::totalContractingTax($project))</strong></td>
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
            <td class="col-md-1">@money($calculus::subconCalcLaborActivityTax1($project), false)</td>
            <td class="col-md-2">@money($calculus::subconCalcLaborActivityTax1Amount($project))</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">21%</td>
            <td class="col-md-1">@money($calculus::subconCalcLaborActivityTax1AmountTax($project))</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @if ($calculus::subconCalcLaborActivityTax2Amount($project))
        <tr>
            <td class="col-md-4"><?php echo !$header ? "Arbeidskosten" : "" ?></td>
            <td class="col-md-1">@money($calculus::subconCalcLaborActivityTax2($project), false)</td>
            <td class="col-md-2">@money($calculus::subconCalcLaborActivityTax2Amount($project))</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">6%</td>
            <td class="col-md-1">@money($calculus::subconCalcLaborActivityTax2AmountTax($project))</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @else
        @if ($calculus::subconCalcLaborActivityTax3($project))
        <tr>
            <td class="col-md-4">Arbeidskosten</td>
            <td class="col-md-1">@money($calculus::subconCalcLaborActivityTax3($project), false)</td>
            <td class="col-md-2">@money($calculus::subconCalcLaborActivityTax3Amount($project))</td>
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
            <td class="col-md-2">@money($calculus::subconCalcMaterialActivityTax1Amount($project))</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">21%</td>
            <td class="col-md-1">@money($calculus::subconCalcMaterialActivityTax1AmountTax($project))</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @if ($calculus::subconCalcMaterialActivityTax2Amount($project))
        <tr>
            <td class="col-md-4"><?php echo !$header ? "Materiaalkosten" : "" ?></td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-2">@money($calculus::subconCalcMaterialActivityTax2Amount($project))</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">6%</td>
            <td class="col-md-1">@money($calculus::subconCalcMaterialActivityTax2AmountTax($project))</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @else
        @if ($calculus::subconCalcMaterialActivityTax3Amount($project))
        <tr>
            <td class="col-md-4">Materiaalkosten</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-2">@money($calculus::subconCalcMaterialActivityTax3Amount($project))</td>
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
            <td class="col-md-2">@money($calculus::subconCalcEquipmentActivityTax1Amount($project))</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">21%</td>
            <td class="col-md-1">@money($calculus::subconCalcEquipmentActivityTax1AmountTax($project))</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @if ($calculus::subconCalcEquipmentActivityTax2Amount($project))
        <tr>
            <td class="col-md-4"><?php echo !$header ? "Overige kosten" : "" ?></td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-2">@money($calculus::subconCalcEquipmentActivityTax2Amount($project))</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">6%</td>
            <td class="col-md-1">@money($calculus::subconCalcEquipmentActivityTax2AmountTax($project))</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @else
        @if ($calculus::subconCalcEquipmentActivityTax3Amount($project))
        <tr>
            <td class="col-md-4">Overige kosten</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-2">@money($calculus::subconCalcEquipmentActivityTax3Amount($project))</span></td>
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
            <td class="col-md-2"><strong>@money($calculus::totalSubcontracting($project))</strong></td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1"><strong>@money($calculus::totalSubcontractingTax($project))</strong></td>
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
            <td class="col-md-2"><strong>@money($calculus::totalProject($project))</strong></td>
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
            <td class="col-md-1">@money($calculus::totalContractingTax1($project))</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @if ($calculus::totalContractingTax2($project))
        <tr>
            <td class="col-md-5">BTW bedrag aanneming 6%</td>
            <td class="col-md-2">&nbsp;</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">@money($calculus::totalContractingTax2($project))</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @if ($calculus::totalSubcontractingTax1($project))
        <tr>
            <td class="col-md-5">BTW bedrag onderaanneming 21%</td>
            <td class="col-md-2">&nbsp;</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">@money($calculus::totalSubcontractingTax1($project))</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @if ($calculus::totalSubcontractingTax2($project))
        <tr>
            <td class="col-md-5">BTW bedrag onderaanneming 6%</td>
            <td class="col-md-2">&nbsp;</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">@money($calculus::totalSubcontractingTax2($project))</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @endif
        <tr>
            <td class="col-md-5"><strong>Te offreren BTW bedrag</strong></td>
            <td class="col-md-2">&nbsp;</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1"><strong>@money($calculus::totalProjectTax($project))</strong></td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        <tr>
            <td class="col-md-5"><strong>Calculatief te offreren (Incl. BTW)</strong></td>
            <td class="col-md-2">&nbsp;</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-2"><strong class="pull-right">@money($calculus::superTotalProject($project))</strong></td>
        </tr>

    </tbody>

</table>
{{-- /Project totals --}}
