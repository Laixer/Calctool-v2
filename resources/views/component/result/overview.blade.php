<?php

use BynqIO\Dynq\Models\Project;
use BynqIO\Dynq\Models\Chapter;
use BynqIO\Dynq\Models\Activity;
use BynqIO\Dynq\Models\Part;
use BynqIO\Dynq\Models\Time;
use BynqIO\Dynq\Calculus\SetEstimateCalculationEndresult;
use BynqIO\Dynq\Calculus\MoreEndresult;
use BynqIO\Dynq\Calculus\LessEndresult;
use BynqIO\Dynq\Calculus\ResultEndresult;
use BynqIO\Dynq\Models\Timesheet;

?>

<h4>Aanneming</h4>
<table class="table table-striped">
    <thead>
        <tr>
            <th class="col-md-4">&nbsp;</th>
            <th class="col-md-1">Calculatie</th>
            <th class="col-md-1">Minderwerk</th>
            <th class="col-md-1">Meerwerk</th>
            <th class="col-md-1">Balans</th>
            <th class="col-md-1">BTW</th>
            <th class="col-md-1">BTW bedrag</th>
            <th class="col-md-2">&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <?php $header = false; ?>
        @if (!$project->tax_reverse)
        @if (ResultEndresult::conLaborBalanceTax1($project))
        <tr>
            <td class="col-md-4"><?php echo "Arbeidskosten"; $header = true; ?></td>
            <td class="col-md-1">@money(SetEstimateCalculationEndresult::conCalcLaborActivityTax1Amount($project))</td>
            <td class="col-md-1">@money(LessEndresult::conCalcLaborActivityTax1Amount($project))</td>
            <td class="col-md-1">@money(MoreEndresult::conCalcLaborActivityTax1Amount($project))</td>
            <td class="col-md-1">@money(ResultEndresult::conLaborBalanceTax1($project))</td>
            <td class="col-md-1">21%</td>
            <td class="col-md-1">@money(ResultEndresult::conLaborBalanceTax1AmountTax($project))</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @if (ResultEndresult::conLaborBalanceTax2($project))
        <tr>
            <td class="col-md-4"><?php echo !$header ? "Arbeidskosten" : "" ?></td>
            <td class="col-md-1">@money(SetEstimateCalculationEndresult::conCalcLaborActivityTax2Amount($project))</td>
            <td class="col-md-1">@money(LessEndresult::conCalcLaborActivityTax2Amount($project))</td>
            <td class="col-md-1">@money(MoreEndresult::conCalcLaborActivityTax2Amount($project))</td>
            <td class="col-md-1">@money(ResultEndresult::conLaborBalanceTax2($project))</td>
            <td class="col-md-1">6%</td>
            <td class="col-md-1">@money(ResultEndresult::conLaborBalanceTax2AmountTax($project))</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @else
        @if (ResultEndresult::conLaborBalanceTax3($project))
        <tr>
            <td class="col-md-4">Arbeidskosten</td>
            <td class="col-md-1">@money(SetEstimateCalculationEndresult::conCalcLaborActivityTax3Amount($project))</td>
            <td class="col-md-1">@money(LessEndresult::conCalcLaborActivityTax3Amount($project))</td>
            <td class="col-md-1">@money(MoreEndresult::conCalcLaborActivityTax3Amount($project))</td>
            <td class="col-md-1">@money(ResultEndresult::conLaborBalanceTax3($project))</td>
            <td class="col-md-1">0%</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @endif

        <?php $header = false; ?>
        @if (!$project->tax_reverse)
        @if (ResultEndresult::conMaterialBalanceTax1($project))
        <tr>
            <td class="col-md-4"><?php echo "Materiaalkosten"; $header = true; ?></td>
            <td class="col-md-1">@money(SetEstimateCalculationEndresult::conCalcMaterialActivityTax1Amount($project))</td>
            <td class="col-md-1">@money(LessEndresult::conCalcMaterialActivityTax1Amount($project))</td>
            <td class="col-md-1">@money(MoreEndresult::conCalcMaterialActivityTax1Amount($project))</td>
            <td class="col-md-1">@money(ResultEndresult::conMaterialBalanceTax1($project))</td>
            <td class="col-md-1">21%</td>
            <td class="col-md-1">@money(ResultEndresult::conMaterialBalanceTax1AmountTax($project))</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @if (ResultEndresult::conMaterialBalanceTax2($project))
        <tr>
            <td class="col-md-4"><?php echo !$header ? "Materiaalkosten" : "" ?></td>
            <td class="col-md-1">@money(SetEstimateCalculationEndresult::conCalcMaterialActivityTax2Amount($project))</td>
            <td class="col-md-1">@money(LessEndresult::conCalcMaterialActivityTax2Amount($project))</td>
            <td class="col-md-1">@money(MoreEndresult::conCalcMaterialActivityTax2Amount($project))</td>
            <td class="col-md-1">@money(ResultEndresult::conMaterialBalanceTax2($project))</td>
            <td class="col-md-1">6%</td>
            <td class="col-md-1">@money(ResultEndresult::conMaterialBalanceTax2AmountTax($project))</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @else
        @if (ResultEndresult::conMaterialBalanceTax3($project))
        <tr>
            <td class="col-md-4">Materiaalkosten</td>
            <td class="col-md-1">@money(SetEstimateCalculationEndresult::conCalcMaterialActivityTax3Amount($project))</td>
            <td class="col-md-1">@money(LessEndresult::conCalcMaterialActivityTax3Amount($project))</td>
            <td class="col-md-1">@money(MoreEndresult::conCalcMaterialActivityTax3Amount($project))</td>
            <td class="col-md-1">@money(ResultEndresult::conMaterialBalanceTax3($project))</td>
            <td class="col-md-1">0%</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @endif

        <?php $header = false; ?>
        @if (!$project->tax_reverse)
        @if (ResultEndresult::conEquipmentBalanceTax1($project))
        <tr>
            <td class="col-md-4"><?php echo "Overige kosten"; $header = true; ?></td>
            <td class="col-md-1">@money(SetEstimateCalculationEndresult::conCalcEquipmentActivityTax1Amount($project))</td>
            <td class="col-md-1">@money(LessEndresult::conCalcEquipmentActivityTax1Amount($project))</td>
            <td class="col-md-1">@money(MoreEndresult::conCalcEquipmentActivityTax1Amount($project))</td>
            <td class="col-md-1">@money(ResultEndresult::conEquipmentBalanceTax1($project))</td>
            <td class="col-md-1">21%</td>
            <td class="col-md-1">@money(ResultEndresult::conEquipmentBalanceTax1AmountTax($project))</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @if (ResultEndresult::conEquipmentBalanceTax2($project))
        <tr>
            <td class="col-md-4"><?php echo !$header ? "Overige kosten" : "" ?></td>
            <td class="col-md-1">@money(SetEstimateCalculationEndresult::conCalcEquipmentActivityTax2Amount($project))</td>
            <td class="col-md-1">@money(LessEndresult::conCalcEquipmentActivityTax2Amount($project))</td>
            <td class="col-md-1">@money(MoreEndresult::conCalcEquipmentActivityTax2Amount($project))</td>
            <td class="col-md-1">@money(ResultEndresult::conEquipmentBalanceTax2($project))</td>
            <td class="col-md-1">6%</td>
            <td class="col-md-1">@money(ResultEndresult::conEquipmentBalanceTax2AmountTax($project))</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @else
        @if (ResultEndresult::conEquipmentBalanceTax3($project))
        <tr>
            <td class="col-md-4">Overige kosten</td>
            <td class="col-md-1">@money(SetEstimateCalculationEndresult::conCalcEquipmentActivityTax3Amount($project))</td>
            <td class="col-md-1">@money(LessEndresult::conCalcEquipmentActivityTax3Amount($project))</td>
            <td class="col-md-1">@money(MoreEndresult::conCalcEquipmentActivityTax3Amount($project))</td>
            <td class="col-md-1">@money(ResultEndresult::conEquipmentBalanceTax3($project))</td>
            <td class="col-md-1">0%</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @endif

        <tr>
            <td class="col-md-4"><strong>Totaal Aanneming </strong></td>
            <td class="col-md-1"><strong>@money(SetEstimateCalculationEndresult::totalContracting($project))</strong></td>
            <td class="col-md-1"><strong>@money(LessEndresult::totalContracting($project))</strong></td>
            <td class="col-md-1"><strong>@money(MoreEndresult::totalContracting($project))</strong></td>
            <td class="col-md-1"><strong>@money(ResultEndresult::totalContracting($project))</strong></td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1"><strong>@money(ResultEndresult::totalContractingTax($project))</strong></td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
    </tbody>
</table>

@if ($project->use_subcontract)
<h4>Onderaanneming</h4>
<table class="table table-striped">
    <thead>
        <tr>
            <th class="col-md-4">&nbsp;</th>
            <th class="col-md-1">Calculatie</th>
            <th class="col-md-1">Minderwerk</th>
            <th class="col-md-1">Meerwerk</th>
            <th class="col-md-1">Balans</th>
            <th class="col-md-1">BTW</th>
            <th class="col-md-1">BTW bedrag</th>
            <th class="col-md-2">&nbsp;</th>
        </tr>
    </thead>

    <tbody>
        <?php $header = false; ?>
        @if (!$project->tax_reverse)
        @if (ResultEndresult::subconLaborBalanceTax1($project))
        <tr>
            <td class="col-md-4"><?php echo "Arbeidskosten"; $header = true; ?></td>
            <td class="col-md-1">@money(SetEstimateCalculationEndresult::subconCalcLaborActivityTax1Amount($project))</td>
            <td class="col-md-1">@money(LessEndresult::subconCalcLaborActivityTax1Amount($project))</td>
            <td class="col-md-1">@money(MoreEndresult::subconCalcLaborActivityTax1Amount($project))</td>
            <td class="col-md-1">@money(ResultEndresult::subconLaborBalanceTax1($project))</td>
            <td class="col-md-1">21%</td>
            <td class="col-md-1">@money(ResultEndresult::subconLaborBalanceTax1AmountTax($project))</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @if (ResultEndresult::subconLaborBalanceTax2($project))
        <tr>
            <td class="col-md-4"><?php echo !$header ? "Arbeidskosten" : "" ?></td>
            <td class="col-md-1">@money(SetEstimateCalculationEndresult::subconCalcLaborActivityTax2Amount($project))</td>
            <td class="col-md-1">@money(LessEndresult::subconCalcLaborActivityTax2Amount($project))</td>
            <td class="col-md-1">@money(MoreEndresult::subconCalcLaborActivityTax2Amount($project))</td>
            <td class="col-md-1">@money(ResultEndresult::subconLaborBalanceTax2($project))</td>
            <td class="col-md-1">6%</td>
            <td class="col-md-1">@money(ResultEndresult::subconLaborBalanceTax2AmountTax($project))</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @else
        @if (ResultEndresult::subconLaborBalanceTax3($project))
        <tr>
            <td class="col-md-4">Arbeidskosten</td>
            <td class="col-md-1">@money(SetEstimateCalculationEndresult::subconCalcLaborActivityTax3Amount($project))</td>
            <td class="col-md-1">@money(LessEndresult::subconCalcLaborActivityTax3Amount($project))</td>
            <td class="col-md-1">@money(MoreEndresult::subconCalcLaborActivityTax3Amount($project))</td>
            <td class="col-md-1">@money(ResultEndresult::subconLaborBalanceTax3($project))</td>
            <td class="col-md-1">0%</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @endif

        <?php $header = false; ?>
        @if (!$project->tax_reverse)
        @if (ResultEndresult::subconMaterialBalanceTax1($project))
        <tr>
            <td class="col-md-4"><?php echo "Materiaalkosten"; $header = true; ?></td>
            <td class="col-md-1">@money(SetEstimateCalculationEndresult::subconCalcMaterialActivityTax1Amount($project))</td>
            <td class="col-md-1">@money(LessEndresult::subconCalcMaterialActivityTax1Amount($project))</td>
            <td class="col-md-1">@money(MoreEndresult::subconCalcMaterialActivityTax1Amount($project))</td>
            <td class="col-md-1">@money(ResultEndresult::subconMaterialBalanceTax1($project))</td>
            <td class="col-md-1">21%</td>
            <td class="col-md-1">@money(ResultEndresult::subconMaterialBalanceTax1AmountTax($project))</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @if (ResultEndresult::subconMaterialBalanceTax2($project))
        <tr>
            <td class="col-md-4"><?php echo !$header ? "Materiaalkosten" : "" ?></td>
            <td class="col-md-1">@money(SetEstimateCalculationEndresult::subconCalcMaterialActivityTax2Amount($project))</td>
            <td class="col-md-1">@money(LessEndresult::subconCalcMaterialActivityTax2Amount($project))</td>
            <td class="col-md-1">@money(MoreEndresult::subconCalcMaterialActivityTax2Amount($project))</td>
            <td class="col-md-1">@money(ResultEndresult::subconMaterialBalanceTax2($project))</td>
            <td class="col-md-1">6%</td>
            <td class="col-md-1">@money(ResultEndresult::subconMaterialBalanceTax2AmountTax($project))</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @else
        @if (ResultEndresult::subconMaterialBalanceTax3($project))
        <tr>
            <td class="col-md-4">Materiaalkosten</td>
            <td class="col-md-1">@money(SetEstimateCalculationEndresult::subconCalcMaterialActivityTax3Amount($project))</td>
            <td class="col-md-1">@money(LessEndresult::subconCalcMaterialActivityTax3Amount($project))</td>
            <td class="col-md-1">@money(MoreEndresult::subconCalcMaterialActivityTax3Amount($project))</td>
            <td class="col-md-1">@money(ResultEndresult::subconMaterialBalanceTax3($project))</td>
            <td class="col-md-1">0%</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @endif

        <?php $header = false; ?>
        @if (!$project->tax_reverse)
        @if (ResultEndresult::subconEquipmentBalanceTax1($project))
        <tr>
            <td class="col-md-4"><?php echo "Overige kosten"; $header = true; ?></td>
            <td class="col-md-1">@money(SetEstimateCalculationEndresult::subconCalcEquipmentActivityTax1Amount($project))</td>
            <td class="col-md-1">@money(LessEndresult::subconCalcEquipmentActivityTax1Amount($project))</td>
            <td class="col-md-1">@money(MoreEndresult::subconCalcEquipmentActivityTax1Amount($project))</td>
            <td class="col-md-1">@money(ResultEndresult::subconEquipmentBalanceTax1($project))</td>
            <td class="col-md-1">21%</td>
            <td class="col-md-1">@money(ResultEndresult::subconEquipmentBalanceTax1AmountTax($project))</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @if (ResultEndresult::subconEquipmentBalanceTax2($project))
        <tr>
            <td class="col-md-4"><?php echo !$header ? "Overige kosten" : "" ?></td>
            <td class="col-md-1">@money(SetEstimateCalculationEndresult::subconCalcEquipmentActivityTax2Amount($project))</td>
            <td class="col-md-1">@money(LessEndresult::subconCalcEquipmentActivityTax2Amount($project))</td>
            <td class="col-md-1">@money(MoreEndresult::subconCalcEquipmentActivityTax2Amount($project))</td>
            <td class="col-md-1">@money(ResultEndresult::subconEquipmentBalanceTax2($project))</td>
            <td class="col-md-1">6%</td>
            <td class="col-md-1">@money(ResultEndresult::subconEquipmentBalanceTax2AmountTax($project))</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @else
        @if (ResultEndresult::subconEquipmentBalanceTax3($project))
        <tr>
            <td class="col-md-4">Overige kosten</td>
            <td class="col-md-1">@money(SetEstimateCalculationEndresult::subconCalcEquipmentActivityTax3Amount($project))</td>
            <td class="col-md-1">@money(LessEndresult::subconCalcEquipmentActivityTax3Amount($project))</td>
            <td class="col-md-1">@money(MoreEndresult::subconCalcEquipmentActivityTax3Amount($project))</td>
            <td class="col-md-1">@money(ResultEndresult::subconEquipmentBalanceTax3($project))</td>
            <td class="col-md-1">0%</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @endif	

        <tr>
            <td class="col-md-4"><strong>Totaal Onderaanneming</strong></td>
            <td class="col-md-1"><strong>@money(SetEstimateCalculationEndresult::totalSubcontracting($project))</strong></td>
            <td class="col-md-1"><strong>@money(LessEndresult::totalSubcontracting($project))</strong></td>
            <td class="col-md-1"><strong>@money(MoreEndresult::totalSubcontracting($project))</strong></td>
            <td class="col-md-1"><strong>@money(ResultEndresult::totalSubcontracting($project))</strong></td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1"><strong>@money(ResultEndresult::totalSubcontractingTax($project))</strong></td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
    </tbody>
</table>
@endif

<h4>Totalen</h4>
<table class="table table-striped">
    <thead>
        <tr>
            <th class="col-md-4">&nbsp;</th>
            <th class="col-md-3">&nbsp;</th>
            <th class="col-md-2">Bedrag (excl. BTW)</th>
            <th class="col-md-1">BTW bedrag</th>
            <th class="col-md-2"><span class="pull-right">Bedrag (incl. BTW)</span></th>
        </tr>
    </thead>

    <tbody>
        <tr>
            <td class="col-md-4"><strong>Cumulatief project (excl. BTW)</strong></td>
            <td class="col-md-3">&nbsp;</td>
            <td class="col-md-2"><strong>@money(ResultEndresult::totalProject($project))</strong></td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @if (!$project->tax_reverse)
        @if (ResultEndresult::totalContractingTax1($project))
        <tr>
            <td class="col-md-4">BTW bedrag aanneming belast met 21%</td>
            <th class="col-md-3">&nbsp;</th>
            <td class="col-md-2">&nbsp;</td>
            <td class="col-md-1">@money(ResultEndresult::totalContractingTax1($project))</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @if (ResultEndresult::totalContractingTax2($project))
        <tr>
            <td class="col-md-4">BTW bedrag aanneming belast met 6%</td>
            <th class="col-md-3">&nbsp;</th>
            <td class="col-md-2">&nbsp;</td>
            <td class="col-md-1">@money(ResultEndresult::totalContractingTax2($project))</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @if (ResultEndresult::totalSubcontractingTax1($project))
        <tr>
            <td class="col-md-4">BTW bedrag onderaanneming belast met 21%</td>
            <th class="col-md-3">&nbsp;</th>
            <td class="col-md-2">&nbsp;</td>
            <td class="col-md-1">@money(ResultEndresult::totalSubcontractingTax1($project))</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @if (ResultEndresult::totalSubcontractingTax2($project))
        <tr>
            <td class="col-md-4">BTW bedrag onderaanneming belast met 6%</td>
            <th class="col-md-3">&nbsp;</th>
            <td class="col-md-2">&nbsp;</td>
            <td class="col-md-1">@money(ResultEndresult::totalSubcontractingTax2($project))</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @endif
        @if (ResultEndresult::totalProjectTax($project))
        <tr>
            <td class="col-md-4">Cumulatief BTW bedrag</td>
            <th class="col-md-3">&nbsp;</th>
            <td class="col-md-2">&nbsp;</td>
            <td class="col-md-1"><strong>@money(ResultEndresult::totalProjectTax($project))</strong></td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        <tr>
            <td class="col-md-4"><strong>Cumulatief project (Incl. BTW)</strong></td>
            <th class="col-md-3">&nbsp;</th>
            <td class="col-md-2">&nbsp;</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-2"><strong><span class="pull-right">@money(ResultEndresult::superTotalProject($project))</strong></span></td>
        </tr>
    </tbody>
</table>
