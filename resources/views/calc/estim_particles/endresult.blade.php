<?php
use \Calctool\Calculus\SetEstimateEndresult;
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
		@if (!$project->tax_reverse)
		<tr>
			<td class="col-md-4">Arbeidskosten</td>
			<td class="col-md-1">{{ number_format(SetEstimateEndresult::conCalcLaborActivityTax1($project), 2, ",",".") }}</td>
			<td class="col-md-2">{{ '&euro; '.number_format(SetEstimateEndresult::conCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">21%</td>
			<td class="col-md-2">{{ '&euro; '.number_format(SetEstimateEndresult::conCalcLaborActivityTax1AmountTax($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
		</tr>
		<tr>
			<td class="col-md-4">&nbsp;</td>
			<td class="col-md-1">{{ number_format(SetEstimateEndresult::conCalcLaborActivityTax2($project), 2, ",",".") }}</td>
			<td class="col-md-2">{{ '&euro; '.number_format(SetEstimateEndresult::conCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">6%</td>
			<td class="col-md-2">{{ '&euro; '.number_format(SetEstimateEndresult::conCalcLaborActivityTax2AmountTax($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
		</tr>
		@else
		<tr>
			<td class="col-md-4">Arbeidskosten</td>
			<td class="col-md-1">{{ number_format(SetEstimateEndresult::conCalcLaborActivityTax3($project), 2, ",",".") }}</td>
			<td class="col-md-2">{{ '&euro; '.number_format(SetEstimateEndresult::conCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">0%</td>
			<td class="col-md-2">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
		</tr>
		@endif

		@if (!$project->tax_reverse)
		<tr>
			<td class="col-md-4">Materiaalkosten</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">{{ '&euro; '.number_format(SetEstimateEndresult::conCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">21%</td>
			<td class="col-md-2">{{ '&euro; '.number_format(SetEstimateEndresult::conCalcMaterialActivityTax1AmountTax($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
		</tr>
		<tr>
			<td class="col-md-4">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">{{ '&euro; '.number_format(SetEstimateEndresult::conCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">6%</td>
			<td class="col-md-2">{{ '&euro; '.number_format(SetEstimateEndresult::conCalcMaterialActivityTax2AmountTax($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
		</tr>
		@else
		<tr>
			<td class="col-md-4">Materiaalkosten</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">{{ '&euro; '.number_format(SetEstimateEndresult::conCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">0%</td>
			<td class="col-md-2">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
		</tr>
		@endif

		@if (!$project->tax_reverse)
		<tr>
			<td class="col-md-4">Materieelkosten</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">{{ '&euro; '.number_format(SetEstimateEndresult::conCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">21%</td>
			<td class="col-md-2">{{ '&euro; '.number_format(SetEstimateEndresult::conCalcEquipmentActivityTax1AmountTax($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
		</tr>
		<tr>
			<td class="col-md-4">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">{{ '&euro; '.number_format(SetEstimateEndresult::conCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">6%</td>
			<td class="col-md-2">{{ '&euro; '.number_format(SetEstimateEndresult::conCalcEquipmentActivityTax2AmountTax($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
		</tr>
		@else
		<tr>
			<td class="col-md-4">Materieelkosten</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">{{ '&euro; '.number_format(SetEstimateEndresult::conCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">0%</td>
			<td class="col-md-2">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
		</tr>
		@endif

		<tr>
			<td class="col-md-4"><strong>Totaal Aanneming </strong></td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2"><strong>{{ '&euro; '.number_format(SetEstimateEndresult::totalContracting($project), 2, ",",".") }}</strong></td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2"><strong>{{ '&euro; '.number_format(SetEstimateEndresult::totalContractingTax($project), 2, ",",".") }}</strong></td>
			<td class="col-md-1">&nbsp;</td>
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
			<th class="col-md-2">BTW bedrag</th>
			<th class="col-md-1">&nbsp;</th>
		</tr>
	</thead>


	<tbody>
		@if (!$project->tax_reverse)
		<tr>
			<td class="col-md-4">Arbeidskosten</td>
			<td class="col-md-1">{{ number_format(SetEstimateEndresult::subconCalcLaborActivityTax1($project), 2, ",",".") }}</td>
			<td class="col-md-2">{{ '&euro; '.number_format(SetEstimateEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">21%</td>
			<td class="col-md-2">{{ '&euro; '.number_format(SetEstimateEndresult::subconCalcLaborActivityTax1AmountTax($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
		</tr>
		<tr>
			<td class="col-md-4">&nbsp;</td>
			<td class="col-md-1">{{ number_format(SetEstimateEndresult::subconCalcLaborActivityTax2($project), 2, ",",".") }}</td>
			<td class="col-md-2">{{ '&euro; '.number_format(SetEstimateEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">6%</td>
			<td class="col-md-2">{{ '&euro; '.number_format(SetEstimateEndresult::subconCalcLaborActivityTax2AmountTax($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
		</tr>
		@else
		<tr>
			<td class="col-md-4">Arbeidskosten</td>
			<td class="col-md-1">{{ number_format(SetEstimateEndresult::subconCalcLaborActivityTax3($project), 2, ",",".") }}</td>
			<td class="col-md-2">{{ '&euro; '.number_format(SetEstimateEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">0%</td>
			<td class="col-md-2">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
		</tr>
		@endif

		@if (!$project->tax_reverse)
		<tr>
			<td class="col-md-4">Materiaalkosten</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">{{ '&euro; '.number_format(SetEstimateEndresult::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">21%</td>
			<td class="col-md-2">{{ '&euro; '.number_format(SetEstimateEndresult::subconCalcMaterialActivityTax1AmountTax($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
		</tr>
		<tr>
			<td class="col-md-4">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">{{ '&euro; '.number_format(SetEstimateEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">6%</td>
			<td class="col-md-2">{{ '&euro; '.number_format(SetEstimateEndresult::subconCalcMaterialActivityTax2AmountTax($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
		</tr>
		@else
		<tr>
			<td class="col-md-4">Materiaalkosten</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">{{ '&euro; '.number_format(SetEstimateEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">0%</td>
			<td class="col-md-2">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
		</tr>
		@endif

		@if (!$project->tax_reverse)
		<tr>
			<td class="col-md-4">Materieelkosten</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">{{ '&euro; '.number_format(SetEstimateEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">21%</td>
			<td class="col-md-2">{{ '&euro; '.number_format(SetEstimateEndresult::subconCalcEquipmentActivityTax1AmountTax($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
		</tr>
		<tr>
			<td class="col-md-4">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">{{ '&euro; '.number_format(SetEstimateEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">6%</td>
			<td class="col-md-2">{{ '&euro; '.number_format(SetEstimateEndresult::subconCalcEquipmentActivityTax2AmountTax($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
		</tr>
		@else
		<tr>
			<td class="col-md-4">Materieelkosten</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">{{ '&euro; '.number_format(SetEstimateEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">0%</td>
			<td class="col-md-2">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
		</tr>
		@endif

		<tr>
			<td class="col-md-4"><strong>Totaal Onderaanneming </strong></td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2"><strong>{{ '&euro; '.number_format(SetEstimateEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2"><strong>{{ '&euro; '.number_format(SetEstimateEndresult::totalSubcontractingTax($project), 2, ",",".") }}</strong></td>
			<td class="col-md-2">&nbsp;</td>
		</tr>
	</tbody>
</table>

<h4>Totalen Stelpost</h4>
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
			<td class="col-md-5"><strong>Calculatief te factureren (excl. BTW)<strong></td>
			<td class="col-md-2"><strong>{{ '&euro; '.number_format(SetEstimateEndresult::totalProject($project), 2, ",",".") }}</strong></td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">&nbsp;</td>
		</tr>
		@if (!$project->tax_reverse)
		<tr>
			<td class="col-md-5">BTW bedrag aanneming 21%</td>
			<td class="col-md-2">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">{{ '&euro; '.number_format(SetEstimateEndresult::totalContractingTax1($project), 2, ",",".") }}</td>
			<td class="col-md-2">&nbsp;</td>
		</tr>
		<tr>
			<td class="col-md-5">BTW bedrag aanneming 6%</td>
			<td class="col-md-2">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">{{ '&euro; '.number_format(SetEstimateEndresult::totalContractingTax2($project), 2, ",",".") }}</td>
			<td class="col-md-2">&nbsp;</td>
		</tr>
		<tr>
			<td class="col-md-5">BTW bedrag onderaanneming 21%</td>
			<td class="col-md-2">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">{{ '&euro; '.number_format(SetEstimateEndresult::totalSubcontractingTax1($project), 2, ",",".") }}</td>
			<td class="col-md-2">&nbsp;</td>
		</tr>
		<tr>
			<td class="col-md-5">BTW bedrag onderaanneming 6%</td>
			<td class="col-md-2">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">{{ '&euro; '.number_format(SetEstimateEndresult::totalSubcontractingTax2($project), 2, ",",".") }}</td>
			<td class="col-md-2">&nbsp;</td>
		</tr>
		@endif
		<tr>
			<td class="col-md-5"><strong>Te factureren BTW bedrag</strong></td>
			<td class="col-md-2">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1"><strong>{{ '&euro; '.number_format(SetEstimateEndresult::totalProjectTax($project), 2, ",",".") }}</strong></td>
			<td class="col-md-2"></td>
		</tr>
		<tr>
			<td class="col-md-5"><strong>Calculatief te factureren (Incl. BTW)</strong></td>
			<td class="col-md-2">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2"><strong class="pull-right">{{ '&euro; '.number_format(SetEstimateEndresult::superTotalProject($project), 2, ",",".") }}</strong></td>
		</tr>

	</tbody>


</table>