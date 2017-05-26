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
            <td class="col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax1($project), 2, ",",".") }}</td>
            <td class="col-md-1">21%</td>
            <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax1AmountTax($project), 2, ",",".") }}</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @if (ResultEndresult::conLaborBalanceTax2($project))
        <tr>
            <td class="col-md-4"><?php echo !$header ? "Arbeidskosten" : "" ?></td>
            <td class="col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax2($project), 2, ",",".") }}</td>
            <td class="col-md-1">6%</td>
            <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax2AmountTax($project), 2, ",",".") }}</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @else
        @if (ResultEndresult::conLaborBalanceTax3($project))
        <tr>
            <td class="col-md-4">Arbeidskosten</td>
            <td class="col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax3($project), 2, ",",".") }}</td>
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
            <td class="col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax1($project), 2, ",",".") }}</td>
            <td class="col-md-1">21%</td>
            <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax1AmountTax($project), 2, ",",".") }}</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @if (ResultEndresult::conMaterialBalanceTax2($project))
        <tr>
            <td class="col-md-4"><?php echo !$header ? "Materiaalkosten" : "" ?></td>
            <td class="col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax2($project), 2, ",",".") }}</td>
            <td class="col-md-1">6%</td>
            <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax2AmountTax($project), 2, ",",".") }}</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @else
        @if (ResultEndresult::conMaterialBalanceTax3($project))
        <tr>
            <td class="col-md-4">Materiaalkosten</td>
            <td class="col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax3($project), 2, ",",".") }}</td>
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
            <td class="col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax1($project), 2, ",",".") }}</td>
            <td class="col-md-1">21%</td>
            <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax1AmountTax($project), 2, ",",".") }}</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @if (ResultEndresult::conEquipmentBalanceTax2($project))
        <tr>
            <td class="col-md-4"><?php echo !$header ? "Overige kosten" : "" ?></td>
            <td class="col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax2($project), 2, ",",".") }}</td>
            <td class="col-md-1">6%</td>
            <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax2AmountTax($project), 2, ",",".") }}</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @else
        @if (ResultEndresult::conEquipmentBalanceTax3($project))
        <tr>
            <td class="col-md-4">Overige kosten</td>
            <td class="col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax3($project), 2, ",",".") }}</td>
            <td class="col-md-1">0%</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @endif

        <tr>
            <td class="col-md-4"><strong>Totaal Aanneming </strong></td>
            <td class="col-md-1"><strong>{{ '&euro; '.number_format(SetEstimateCalculationEndresult::totalContracting($project), 2, ",",".") }}</strong></td>
            <td class="col-md-1"><strong>{{ '&euro; '.number_format(LessEndresult::totalContracting($project), 2, ",",".") }}</strong></td>
            <td class="col-md-1"><strong>{{ '&euro; '.number_format(MoreEndresult::totalContracting($project), 2, ",",".") }}</strong></td>
            <td class="col-md-1"><strong>{{ '&euro; '.number_format(ResultEndresult::totalContracting($project), 2, ",",".") }}</strong></td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1"><strong>{{ '&euro; '.number_format(ResultEndresult::totalContractingTax($project), 2, ",",".") }}</strong></td>
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
            <td class="col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconLaborBalanceTax1($project), 2, ",",".") }}</td>
            <td class="col-md-1">21%</td>
            <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconLaborBalanceTax1AmountTax($project), 2, ",",".") }}</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @if (ResultEndresult::subconLaborBalanceTax2($project))
        <tr>
            <td class="col-md-4"><?php echo !$header ? "Arbeidskosten" : "" ?></td>
            <td class="col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconLaborBalanceTax2($project), 2, ",",".") }}</td>
            <td class="col-md-1">6%</td>
            <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconLaborBalanceTax2AmountTax($project), 2, ",",".") }}</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @else
        @if (ResultEndresult::subconLaborBalanceTax3($project))
        <tr>
            <td class="col-md-4">Arbeidskosten</td>
            <td class="col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconLaborBalanceTax3($project), 2, ",",".") }}</td>
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
            <td class="col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconMaterialBalanceTax1($project), 2, ",",".") }}</td>
            <td class="col-md-1">21%</td>
            <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconMaterialBalanceTax1AmountTax($project), 2, ",",".") }}</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @if (ResultEndresult::subconMaterialBalanceTax2($project))
        <tr>
            <td class="col-md-4"><?php echo !$header ? "Materiaalkosten" : "" ?></td>
            <td class="col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconMaterialBalanceTax2($project), 2, ",",".") }}</td>
            <td class="col-md-1">6%</td>
            <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconMaterialBalanceTax2AmountTax($project), 2, ",",".") }}</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @else
        @if (ResultEndresult::subconMaterialBalanceTax3($project))
        <tr>
            <td class="col-md-4">Materiaalkosten</td>
            <td class="col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconMaterialBalanceTax3($project), 2, ",",".") }}</td>
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
            <td class="col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconEquipmentBalanceTax1($project), 2, ",",".") }}</td>
            <td class="col-md-1">21%</td>
            <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconEquipmentBalanceTax1AmountTax($project), 2, ",",".") }}</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @if (ResultEndresult::subconEquipmentBalanceTax2($project))
        <tr>
            <td class="col-md-4"><?php echo !$header ? "Overige kosten" : "" ?></td>
            <td class="col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconEquipmentBalanceTax2($project), 2, ",",".") }}</td>
            <td class="col-md-1">6%</td>
            <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconEquipmentBalanceTax2AmountTax($project), 2, ",",".") }}</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @else
        @if (ResultEndresult::subconEquipmentBalanceTax3($project))
        <tr>
            <td class="col-md-4">Overige kosten</td>
            <td class="col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconEquipmentBalanceTax3($project), 2, ",",".") }}</td>
            <td class="col-md-1">0%</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @endif	

        <tr>
            <td class="col-md-4"><strong>Totaal Onderaanneming</strong></td>
            <td class="col-md-1"><strong>{{ '&euro; '.number_format(SetEstimateCalculationEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
            <td class="col-md-1"><strong>{{ '&euro; '.number_format(LessEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
            <td class="col-md-1"><strong>{{ '&euro; '.number_format(MoreEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
            <td class="col-md-1"><strong>{{ '&euro; '.number_format(ResultEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-1"><strong>{{ '&euro; '.number_format(ResultEndresult::totalSubcontractingTax($project), 2, ",",".") }}</strong></td>
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
            <td class="col-md-2"><strong>{{ '&euro; '.number_format(ResultEndresult::totalProject($project), 2, ",",".") }}</strong></td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @if (!$project->tax_reverse)
        @if (ResultEndresult::totalContractingTax1($project))
        <tr>
            <td class="col-md-4">BTW bedrag aanneming belast met 21%</td>
            <th class="col-md-3">&nbsp;</th>
            <td class="col-md-2">&nbsp;</td>
            <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::totalContractingTax1($project), 2, ",",".") }}</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @if (ResultEndresult::totalContractingTax2($project))
        <tr>
            <td class="col-md-4">BTW bedrag aanneming belast met 6%</td>
            <th class="col-md-3">&nbsp;</th>
            <td class="col-md-2">&nbsp;</td>
            <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::totalContractingTax2($project), 2, ",",".") }}</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @if (ResultEndresult::totalSubcontractingTax1($project))
        <tr>
            <td class="col-md-4">BTW bedrag onderaanneming belast met 21%</td>
            <th class="col-md-3">&nbsp;</th>
            <td class="col-md-2">&nbsp;</td>
            <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::totalSubcontractingTax1($project), 2, ",",".") }}</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @if (ResultEndresult::totalSubcontractingTax2($project))
        <tr>
            <td class="col-md-4">BTW bedrag onderaanneming belast met 6%</td>
            <th class="col-md-3">&nbsp;</th>
            <td class="col-md-2">&nbsp;</td>
            <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::totalSubcontractingTax2($project), 2, ",",".") }}</td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        @endif
        @if (ResultEndresult::totalProjectTax($project))
        <tr>
            <td class="col-md-4">Cumulatief BTW bedrag</td>
            <th class="col-md-3">&nbsp;</th>
            <td class="col-md-2">&nbsp;</td>
            <td class="col-md-1"><strong>{{ '&euro; '.number_format(ResultEndresult::totalProjectTax($project), 2, ",",".") }}</strong></td>
            <td class="col-md-2">&nbsp;</td>
        </tr>
        @endif
        <tr>
            <td class="col-md-4"><strong>Cumulatief project (Incl. BTW)</strong></td>
            <th class="col-md-3">&nbsp;</th>
            <td class="col-md-2">&nbsp;</td>
            <td class="col-md-1">&nbsp;</td>
            <td class="col-md-2"><strong><span class="pull-right">{{ '&euro; '.number_format(ResultEndresult::superTotalProject($project), 2, ",",".") }}</strong></span></td>
        </tr>
    </tbody>
</table>
