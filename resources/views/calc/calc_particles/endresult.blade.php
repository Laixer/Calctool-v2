<?php
use \Calctool\Calculus\CalculationEndresult;

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
			<th class="col-md-1">BTW bedrag</th>
			<th class="col-md-2">&nbsp;</th>
		</tr>
	</thead>


	<tbody>
		<?php $header = false; ?>
		@if (!$project->tax_reverse)
		@if (CalculationEndresult::conCalcLaborActivityTax1Amount($project))
		<tr>
			<td class="col-md-4"><?php echo "Arbeidskosten"; $header = true; ?></td>
			<td class="col-md-1">{{ number_format(CalculationEndresult::conCalcLaborActivityTax1($project), 2, ",",".") }}</td>
			<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">21%</td>
			<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax1AmountTax($project), 2, ",",".") }}</td>
			<td class="col-md-2">&nbsp;</td>
		</tr>
		@endif
		@if (CalculationEndresult::conCalcLaborActivityTax2Amount($project))
		<tr>
			<td class="col-md-4"><?php echo !$header ? "Arbeidskosten" : "" ?></td>
			<td class="col-md-1">{{ number_format(CalculationEndresult::conCalcLaborActivityTax2($project), 2, ",",".") }}</td>
			<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">6%</td>
			<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax2AmountTax($project), 2, ",",".") }}</td>
			<td class="col-md-2">&nbsp;</td>
		</tr>
		@endif
		@else
		@if (CalculationEndresult::conCalcLaborActivityTax3Amount($project))
		<tr>
			<td class="col-md-4">Arbeidskosten</td>
			<td class="col-md-1">{{ number_format(CalculationEndresult::conCalcLaborActivityTax3($project), 2, ",",".") }}</td>
			<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">0%</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">&nbsp;</td>
		</tr>
		@endif
		@endif

		<?php $header = false; ?>
		@if (!$project->tax_reverse)
		@if (CalculationEndresult::conCalcMaterialActivityTax1Amount($project))
		<tr>
			<td class="col-md-4"><?php echo "Materiaalkosten"; $header = true; ?></td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">21%</td>
			<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax1AmountTax($project), 2, ",",".") }}</td>
			<td class="col-md-2">&nbsp;</td>
		</tr>
		@endif
		@if (CalculationEndresult::conCalcMaterialActivityTax2Amount($project))
		<tr>
			<td class="col-md-4"><?php echo !$header ? "Materiaalkosten" : "" ?></td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">6%</td>
			<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax2AmountTax($project), 2, ",",".") }}</td>
			<td class="col-md-2">&nbsp;</td>
		</tr>
		@endif
		@else
		@if (CalculationEndresult::conCalcMaterialActivityTax3Amount($project))
		<tr>
			<td class="col-md-4">Materiaalkosten</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">0%</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">&nbsp;</td>
		</tr>
		@endif
		@endif

		<?php $header = false; ?>
		@if (!$project->tax_reverse)
		@if (CalculationEndresult::conCalcEquipmentActivityTax1Amount($project))
		<tr>
			<td class="col-md-4"><?php echo "Overige kosten"; $header = true; ?></td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">21%</td>
			<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax1AmountTax($project), 2, ",",".") }}</td>
			<td class="col-md-2">&nbsp;</td>
		</tr>
		@endif
		@if (CalculationEndresult::conCalcEquipmentActivityTax2Amount($project))
		<tr>
			<td class="col-md-4"><?php echo !$header ? "Overige kosten" : "" ?></td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">6%</td>
			<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax2AmountTax($project), 2, ",",".") }}</td>
			<td class="col-md-2">&nbsp;</td>
		</tr>
		@endif
		@else
		@if (CalculationEndresult::conCalcEquipmentActivityTax3Amount($project))
		<tr>
			<td class="col-md-4">Overige kosten</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">0%</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">&nbsp;</td>
		</tr>
		@endif
		@endif

		<tr>
			<td class="col-md-4"><strong>Totaal Aanneming</strong></td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalContracting($project), 2, ",",".") }}</strong></td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalContractingTax($project), 2, ",",".") }}</strong></td>
			<td class="col-md-2">&nbsp;</td>
		</tr>
	</tbody>
</table>

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
		@if (CalculationEndresult::subconCalcLaborActivityTax1Amount($project))
		<tr>
			<td class="col-md-4"><?php echo "Arbeidskosten"; $header = true; ?></td>
			<td class="col-md-1">{{ number_format(CalculationEndresult::subconCalcLaborActivityTax1($project), 2, ",",".") }}</td>
			<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">21%</td>
			<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax1AmountTax($project), 2, ",",".") }}</td>
			<td class="col-md-2">&nbsp;</td>
		</tr>
		@endif
		@if (CalculationEndresult::subconCalcLaborActivityTax2Amount($project))
		<tr>
			<td class="col-md-4"><?php echo !$header ? "Arbeidskosten" : "" ?></td>
			<td class="col-md-1">{{ number_format(CalculationEndresult::subconCalcLaborActivityTax2($project), 2, ",",".") }}</td>
			<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">6%</td>
			<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax2AmountTax($project), 2, ",",".") }}</td>
			<td class="col-md-2">&nbsp;</td>
		</tr>
		@endif
		@else
		@if (CalculationEndresult::subconCalcLaborActivityTax3($project))
		<tr>
			<td class="col-md-4">Arbeidskosten</td>
			<td class="col-md-1">{{ number_format(CalculationEndresult::subconCalcLaborActivityTax3($project), 2, ",",".") }}</td>
			<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">0%</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">&nbsp;</td>
		</tr>
		@endif
		@endif

		<?php $header = false; ?>
		@if (!$project->tax_reverse)
		@if (CalculationEndresult::subconCalcMaterialActivityTax1Amount($project))
		<tr>
			<td class="col-md-4"><?php echo "Materiaalkosten"; $header = true; ?></td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">21%</td>
			<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::subconCalcMaterialActivityTax1AmountTax($project), 2, ",",".") }}</td>
			<td class="col-md-2">&nbsp;</td>
		</tr>
		@endif
		@if (CalculationEndresult::subconCalcMaterialActivityTax2Amount($project))
		<tr>
			<td class="col-md-4"><?php echo !$header ? "Materiaalkosten" : "" ?></td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">6%</td>
			<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::subconCalcMaterialActivityTax2AmountTax($project), 2, ",",".") }}</td>
			<td class="col-md-2">&nbsp;</td>
		</tr>
		@endif
		@else
		@if (CalculationEndresult::subconCalcMaterialActivityTax3Amount($project))
		<tr>
			<td class="col-md-4">Materiaalkosten</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">0%</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">&nbsp;</td>
		</tr>
		@endif
		@endif

		<?php $header = false; ?>
		@if (!$project->tax_reverse)
		@if (CalculationEndresult::subconCalcEquipmentActivityTax1Amount($project))
		<tr>
			<td class="col-md-4"><?php echo "Overige kosten"; $header = true; ?></td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">21%</td>
			<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::subconCalcEquipmentActivityTax1AmountTax($project), 2, ",",".") }}</td>
			<td class="col-md-2">&nbsp;</td>
		</tr>
		@endif
		@if (CalculationEndresult::subconCalcEquipmentActivityTax2Amount($project))
		<tr>
			<td class="col-md-4"><?php echo !$header ? "Overige kosten" : "" ?></td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">6%</td>
			<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::subconCalcEquipmentActivityTax2AmountTax($project), 2, ",",".") }}</td>
			<td class="col-md-2">&nbsp;</td>
		</tr>
		@endif
		@else
		@if (CalculationEndresult::subconCalcEquipmentActivityTax3Amount($project))
		<tr>
			<td class="col-md-4">Overige kosten</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">{{ '&euro; '.number_format(CalculationEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</span></td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">0%</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">&nbsp;</td>
		</tr>
		@endif
		@endif

		<tr>
			<td class="col-md-4"><strong>Totaal Onderaanneming </strong></td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalSubcontractingTax($project), 2, ",",".") }}</strong></td>
			<td class="col-md-2">&nbsp;</td>
		</tr>
	</tbody>
</table>

<h4>Totalen Offerte</h4>
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
			<td class="col-md-2"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalProject($project), 2, ",",".") }}</strong></td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">&nbsp;</td>
		</tr>
		@if (!$project->tax_reverse)
		@if (CalculationEndresult::totalContractingTax1($project))
		<tr>
			<td class="col-md-5">BTW bedrag aanneming 21%</td>
			<td class="col-md-2">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::totalContractingTax1($project), 2, ",",".") }}</td>
			<td class="col-md-2">&nbsp;</td>
		</tr>
		@endif
		@if (CalculationEndresult::totalContractingTax2($project))
		<tr>
			<td class="col-md-5">BTW bedrag aanneming 6%</td>
			<td class="col-md-2">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::totalContractingTax2($project), 2, ",",".") }}</td>
			<td class="col-md-2">&nbsp;</td>
		</tr>
		@endif
		@if (CalculationEndresult::totalSubcontractingTax1($project))
		<tr>
			<td class="col-md-5">BTW bedrag onderaanneming 21%</td>
			<td class="col-md-2">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::totalSubcontractingTax1($project), 2, ",",".") }}</td>
			<td class="col-md-2">&nbsp;</td>
		</tr>
		@endif
		@if (CalculationEndresult::totalSubcontractingTax2($project))
		<tr>
			<td class="col-md-5">BTW bedrag onderaanneming 6%</td>
			<td class="col-md-2">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">{{ '&euro; '.number_format(CalculationEndresult::totalSubcontractingTax2($project), 2, ",",".") }}</td>
			<td class="col-md-2">&nbsp;</td>
		</tr>
		@endif
		@endif
		<tr>
			<td class="col-md-5"><strong>Te offreren BTW bedrag</strong></td>
			<td class="col-md-2">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalProjectTax($project), 2, ",",".") }}</strong></td>
			<td class="col-md-2">&nbsp;</td>
		</tr>
		<tr>
			<td class="col-md-5"><strong>Calculatief te offreren (Incl. BTW)</strong></td>
			<td class="col-md-2">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2"><strong class="pull-right">{{ '&euro; '.number_format(CalculationEndresult::superTotalProject($project), 2, ",",".") }}</strong></td>
		</tr>

	</tbody>

</table>