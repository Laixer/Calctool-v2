<?php

use \BynqIO\Dynq\Calculus\LessEndresult;
?>
<h4>Aanneming</h4>
<table class="table table-striped">
	<thead>
		<tr>
			<th class="col-md-4">&nbsp;</th>
			<th class="col-md-1">Uren</th>
			<th class="col-md-2">Bedrag (excl. BTW)</th>
			<th class="col-md-1">&nbsp;</th>
			<th class="col-md-1">BTW</th>
			<th class="col-md-2">BTW bedrag</th>
			<th class="col-md-1">&nbsp;</th>
		</tr>
	</thead>

	<tbody>
		<?php $header = false; ?>
		@if (!$project->tax_reverse)
		@if (LessEndresult::conCalcLaborActivityTax1Amount($project))
		<tr>
			<td class="col-md-4"><?php echo "Arbeidskosten"; $header = true; ?></td>
			<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax1($project), 2, ",",".") }}</td>
			<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">21%</td>
			<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax1AmountTax($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
		</tr>
		@endif
		@if (LessEndresult::conCalcLaborActivityTax2Amount($project))
		<tr>
			<td class="col-md-4"><?php echo !$header ? "Arbeidskosten" : "" ?></td>
			<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax2($project), 2, ",",".") }}</td>
			<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">6%</td>
			<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax2AmountTax($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
		</tr>
		@endif
		@else
		@if (LessEndresult::conCalcLaborActivityTax3Amount($project))
		<tr>
			<td class="col-md-4">Arbeidskosten</td>
			<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax3($project), 2, ",",".") }}</td>
			<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">0%</td>
			<td class="col-md-2">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
		</tr>
		@endif
		@endif

		<?php $header = false; ?>
		@if (!$project->tax_reverse)
		@if (LessEndresult::conCalcMaterialActivityTax1Amount($project))
		<tr>
			<td class="col-md-4"><?php echo "Materiaalkosten"; $header = true; ?></td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">21%</td>
			<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax1AmountTax($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
		</tr>
		@endif
		@if (LessEndresult::conCalcMaterialActivityTax2Amount($project))
		<tr>
			<td class="col-md-4"><?php echo !$header ? "Materiaalkosten" : "" ?></td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">6%</td>
			<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax2AmountTax($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
		</tr>
		@endif
		@else
		@if (LessEndresult::conCalcMaterialActivityTax3Amount($project))
		<tr>
			<td class="col-md-4">Materiaalkosten</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">0%</td>
			<td class="col-md-2">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
		</tr>
		@endif
		@endif

		@if ($project->use_equipment)
		<?php $header = false; ?>
		@if (!$project->tax_reverse)
		@if (LessEndresult::conCalcEquipmentActivityTax1Amount($project))
		<tr>
			<td class="col-md-4"><?php echo "Overige kosten"; $header = true; ?></td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">21%</td>
			<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax1AmountTax($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
		</tr>
		@endif
		@if (LessEndresult::conCalcEquipmentActivityTax2Amount($project))
		<tr>
			<td class="col-md-4"><?php echo !$header ? "Overige kosten" : "" ?></td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">6%</td>
			<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax2AmountTax($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
		</tr>
		@endif
		@else
		@if (LessEndresult::conCalcEquipmentActivityTax3Amount($project))
		<tr>
			<td class="col-md-4">Overige kosten</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">0%</td>
			<td class="col-md-2">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
		</tr>
		@endif
		@endif
		@endif

		<tr>
			<td class="col-md-4"><strong>Totaal Aanneming </strong></td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2"><strong>{{ '&euro; '.number_format(LessEndresult::totalContracting($project), 2, ",",".") }}</strong></td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2"><strong>{{ '&euro; '.number_format(LessEndresult::totalContractingTax($project), 2, ",",".") }}</strong></td>
			<td class="col-md-1">&nbsp;</td>
		</tr>
	</tbody>
</table>

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
			<th class="col-md-2">BTW bedrag</th>
			<th class="col-md-1">&nbsp;</th>
		</tr>
	</thead>

	<tbody>
		<?php $header = false; ?>
		@if (!$project->tax_reverse)
		@if (LessEndresult::subconCalcLaborActivityTax1Amount($project))
		<tr>
			<td class="col-md-4"><?php echo "Arbeidskosten"; $header = true; ?></td>
			<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax1($project), 2, ",",".") }}</td>
			<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">21%</td>
			<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax1AmountTax($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
		</tr>
		@endif
		@if (LessEndresult::subconCalcLaborActivityTax2Amount($project))
		<tr>
			<td class="col-md-4"><?php echo !$header ? "Arbeidskosten" : "" ?></td>
			<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax2($project), 2, ",",".") }}</td>
			<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">6%</td>
			<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax2AmountTax($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
		</tr>
		@endif
		@else
		@if (LessEndresult::subconCalcLaborActivityTax3Amount($project))
		<tr>
			<td class="col-md-4">Arbeidskosten</td>
			<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax3($project), 2, ",",".") }}</td>
			<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">0%</td>
			<td class="col-md-2">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
		</tr>
		@endif
		@endif

		<?php $header = false; ?>
		@if (!$project->tax_reverse)
		@if (LessEndresult::subconCalcMaterialActivityTax1Amount($project))
		<tr>
			<td class="col-md-4"><?php echo "Materiaalkosten"; $header = true; ?></td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">21%</td>
			<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::subconCalcMaterialActivityTax1AmountTax($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
		</tr>
		@endif
		@if (LessEndresult::subconCalcMaterialActivityTax2Amount($project))
		<tr>
			<td class="col-md-4"><?php echo !$header ? "Materiaalkosten" : "" ?></td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">6%</td>
			<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::subconCalcMaterialActivityTax2AmountTax($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
		</tr>
		@endif
		@else
		@if (LessEndresult::subconCalcMaterialActivityTax3Amount($project))
		<tr>
			<td class="col-md-4">Materiaalkosten</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">0%</td>
			<td class="col-md-2">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
		</tr>
		@endif
		@endif

		@if ($project->use_equipment)
		<?php $header = false; ?>
		@if (!$project->tax_reverse)
		@if (LessEndresult::subconCalcEquipmentActivityTax1Amount($project))
		<tr>
			<td class="col-md-4"><?php echo "Overige kosten"; $header = true; ?></td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">21%</td>
			<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::subconCalcEquipmentActivityTax1AmountTax($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
		</tr>
		@endif
		@if (LessEndresult::subconCalcEquipmentActivityTax2Amount($project))
		<tr>
			<td class="col-md-4"><?php echo !$header ? "Overige kosten" : "" ?></td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">6%</td>
			<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::subconCalcEquipmentActivityTax2AmountTax($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
		</tr>
		@endif
		@else
		@if (LessEndresult::subconCalcEquipmentActivityTax3Amount($project))
		<tr>
			<td class="col-md-4">Overige kosten</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">{{ '&euro; '.number_format(LessEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">0%</td>
			<td class="col-md-2">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
		</tr>
		@endif
		@endif
		@endif

		<tr>
			<td class="col-md-4"><strong>Totaal Onderaanneming </strong></td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2"><strong>{{ '&euro; '.number_format(LessEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2"><strong>{{ '&euro; '.number_format(LessEndresult::totalSubcontractingTax($project), 2, ",",".") }}</strong></td>
			<td class="col-md-2">&nbsp;</td>
		</tr>
	</tbody>
</table>
@endif

<h4>Totalen Minderwerk</h4>
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
			<td class="col-md-5"><strong>Calculatief in mindering te brengen (excl. BTW)</strong></td>
			<td class="col-md-2"><strong>{{ '&euro; '.number_format(LessEndresult::totalProject($project), 2, ",",".") }}</strong></td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">&nbsp;</td>
		</tr>
		@if (!$project->tax_reverse)
		@if (LessEndresult::totalContractingTax1($project))
		<tr>
			<td class="col-md-5">BTW bedrag aanneming 21%</td>
			<td class="col-md-2">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::totalContractingTax1($project), 2, ",",".") }}</td>
			<td class="col-md-2">&nbsp;</td>
		</tr>
		@endif
		@if (LessEndresult::totalContractingTax2($project))
		<tr>
			<td class="col-md-5">BTW bedrag aanneming 6%</td>
			<td class="col-md-2">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::totalContractingTax2($project), 2, ",",".") }}</td>
			<td class="col-md-2">&nbsp;</td>
		</tr>
		@endif
		@if (LessEndresult::totalSubcontractingTax1($project))
		<tr>
			<td class="col-md-5">BTW bedrag onderaanneming 21%</td>
			<td class="col-md-2">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::totalSubcontractingTax1($project), 2, ",",".") }}</td>
			<td class="col-md-2">&nbsp;</td>
		</tr>
		@endif
		@if (LessEndresult::totalSubcontractingTax2($project))
		<tr>
			<td class="col-md-5">BTW bedrag onderaanneming 6%</td>
			<td class="col-md-2">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::totalSubcontractingTax2($project), 2, ",",".") }}</td>
			<td class="col-md-2">&nbsp;</td>
		</tr>
		@endif
		@endif
		@if (LessEndresult::totalProjectTax($project))
		<tr>
			<td class="col-md-5">In mindering te brengen BTW bedrag</td>
			<td class="col-md-2">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::totalProjectTax($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
		</tr>
		@endif
		<tr>
			<td class="col-md-5"><strong>Calculatief in mindering te brengen (Incl. BTW)</strong></td>
			<td class="col-md-2">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2"><strong class="pull-right">{{ '&euro; '.number_format(LessEndresult::superTotalProject($project), 2, ",",".") }}</strong></td>
		</tr>
	</tbody>
</table>