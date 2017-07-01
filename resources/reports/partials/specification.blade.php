@inject('calculus', BynqIO\Dynq\Calculus\CalculationEndresult)

<h3>Specificatie Offerte</h3>

@isset($separate_subcon)
<h4>Aanneming</h4>

<table border="0" cellspacing="0" cellpadding="0">
<thead>
    <tr>
        <th class="desc" style="background-color:#fff;"></th>
        <th class="unit">Bedrag Excl.</th>
        <th class="qty">BTW</th>
        <th class="unit">Bedrag BTW</th>
    </tr>
</thead>
<tbody>
    <tr>
        <td class="desc">Arbeidskosten</td>
        <td class="unit">@money($calculus::conCalcLaborActivityTax1Amount($project))</td>
        <td class="qty">21%</td>
        <td class="unit">@money($calculus::conCalcLaborActivityTax1AmountTax($project))</td>
    </tr>
    <tr>
        <td class="" style="background:white;"></td>
        <td class="unit">@money($calculus::conCalcLaborActivityTax2Amount($project))</td>
        <td class="qty">6%</td>
        <td class="unit">@money($calculus::conCalcLaborActivityTax2AmountTax($project))</td>
    </tr>
    <tr>
        <td class="desc">Materiaalkosten</td>
        <td class="unit">@money($calculus::conCalcMaterialActivityTax1Amount($project))</td>
        <td class="qty">21%</td>
        <td class="unit">@money($calculus::conCalcMaterialActivityTax1AmountTax($project))</td>
    </tr>
    <tr>
        <td class="" style="background:white;"></td>
        <td class="unit">@money($calculus::conCalcMaterialActivityTax2Amount($project))</td>
        <td class="qty">6%</td>
        <td class="unit">@money($calculus::conCalcMaterialActivityTax2AmountTax($project))</td>
    </tr>
    <tr>
        <td class="desc">Overige kosten</td>
        <td class="unit">@money($calculus::conCalcEquipmentActivityTax1Amount($project))</td>
        <td class="qty">21%</td>
        <td class="unit">@money($calculus::conCalcEquipmentActivityTax1AmountTax($project))</td>
    </tr>
    <tr>
        <td class="" style="background:white;"></td>
        <td class="unit">@money($calculus::conCalcEquipmentActivityTax2Amount($project))</td>
        <td class="qty">6%</td>
        <td class="unit">@money($calculus::conCalcEquipmentActivityTax2AmountTax($project))</td>
    </tr>
    <tr>
        <td class="desc">Totaal</td>
        <td class="unit"></td>
        <td class="qty"></td>
        <td class="unit">@money($calculus::totalContracting($project))</td>
    </tr>
</tbody>
</table>

<h4>Onderaanneming</h4>

<table border="0" cellspacing="0" cellpadding="0">
<thead>
    <tr>
        <th class="desc" style="background-color:#fff;"></th>
        <th class="unit">Bedrag Excl.</th>
        <th class="qty">BTW</th>
        <th class="unit">Bedrag BTW</th>
    </tr>
</thead>
<tbody>
    <tr>
        <td class="desc">Arbeidskosten</td>
        <td class="unit">@money($calculus::subconCalcLaborActivityTax1Amount($project))</td>
        <td class="qty">21%</td>
        <td class="unit">@money($calculus::subconCalcLaborActivityTax1AmountTax($project))</td>
    </tr>
    <tr>
        <td class="" style="background:white;"></td>
        <td class="unit">@money($calculus::subconCalcLaborActivityTax2Amount($project))</td>
        <td class="qty">6%</td>
        <td class="unit">@money($calculus::subconCalcLaborActivityTax2AmountTax($project))</td>
    </tr>
    <tr>
        <td class="desc">Materiaalkosten</td>
        <td class="unit">@money($calculus::subconCalcMaterialActivityTax1Amount($project))</td>
        <td class="qty">21%</td>
        <td class="unit">@money($calculus::subconCalcMaterialActivityTax1AmountTax($project))</td>
    </tr>
    <tr>
        <td class="" style="background:white;"></td>
        <td class="unit">@money($calculus::subconCalcMaterialActivityTax2Amount($project))</td>
        <td class="qty">6%</td>
        <td class="unit">@money($calculus::subconCalcMaterialActivityTax2AmountTax($project))</td>
    </tr>
    <tr>
        <td class="desc">Overige kosten</td>
        <td class="unit">@money($calculus::subconCalcEquipmentActivityTax1Amount($project))</td>
        <td class="qty">21%</td>
        <td class="unit">@money($calculus::subconCalcEquipmentActivityTax1AmountTax($project))</td>
    </tr>
    <tr>
        <td class="" style="background:white;"></td>
        <td class="unit">@money($calculus::subconCalcEquipmentActivityTax2Amount($project))</td>
        <td class="qty">6%</td>
        <td class="unit">@money($calculus::subconCalcEquipmentActivityTax2AmountTax($project))</td>
    </tr>
    <tr>
        <td class="desc">Totaal</td>
        <td class="unit"></td>
        <td class="qty"></td>
        <td class="unit">@money($calculus::totalSubcontracting($project))</td>
    </tr>
</tbody>
</table>
@else
<table border="0" cellspacing="0" cellpadding="0">
<thead>
    <tr>
        <th class="desc" style="background-color:#fff;"></th>
        <th class="unit">Bedrag Excl.</th>
        <th class="qty">BTW</th>
        <th class="unit">Bedrag BTW</th>
    </tr>
</thead>
<tbody>
    <tr>
        <td class="desc">Arbeidskosten</td>
        <td class="unit">@money($calculus::conCalcLaborActivityTax1Amount($project)+$calculus::subconCalcLaborActivityTax1Amount($project))</td>
        <td class="qty">21%</td>
        <td class="unit">@money($calculus::conCalcLaborActivityTax1AmountTax($project)+$calculus::subconCalcLaborActivityTax1AmountTax($project))</td>
    </tr>
    <tr>
        <td class="" style="background:white;"></td>
        <td class="unit">@money($calculus::conCalcLaborActivityTax2Amount($project)+$calculus::subconCalcLaborActivityTax2Amount($project))</td>
        <td class="qty">6%</td>
        <td class="unit">@money($calculus::conCalcLaborActivityTax2AmountTax($project)+$calculus::subconCalcLaborActivityTax2AmountTax($project))</td>
    </tr>
    <tr>
        <td class="desc">Materiaalkosten</td>
        <td class="unit">@money($calculus::conCalcMaterialActivityTax1Amount($project)+$calculus::subconCalcMaterialActivityTax1Amount($project))</td>
        <td class="qty">21%</td>
        <td class="unit">@money($calculus::conCalcMaterialActivityTax1Amount($project)+$calculus::subconCalcMaterialActivityTax1Amount($project))</td>
    </tr>
    <tr>
        <td class="" style="background:white;"></td>
        <td class="unit">@money($calculus::conCalcMaterialActivityTax2Amount($project)+$calculus::subconCalcMaterialActivityTax2Amount($project))</td>
        <td class="qty">6%</td>
        <td class="unit">@money($calculus::conCalcMaterialActivityTax2AmountTax($project)+$calculus::subconCalcMaterialActivityTax2AmountTax($project))</td>
    </tr>
    <tr>
        <td class="desc">Overige kosten</td>
        <td class="unit">@money($calculus::conCalcEquipmentActivityTax1Amount($project)+$calculus::subconCalcEquipmentActivityTax1Amount($project))</td>
        <td class="qty">21%</td>
        <td class="unit">@money($calculus::conCalcEquipmentActivityTax1AmountTax($project)+$calculus::subconCalcEquipmentActivityTax1AmountTax($project))</td>
    </tr>
    <tr>
        <td class="" style="background:white;"></td>
        <td class="unit">@money($calculus::conCalcEquipmentActivityTax2Amount($project)+$calculus::subconCalcEquipmentActivityTax2Amount($project))</td>
        <td class="qty">6%</td>
        <td class="unit">@money($calculus::conCalcEquipmentActivityTax2AmountTax($project)+$calculus::subconCalcEquipmentActivityTax2AmountTax($project))</td>
    </tr>
    <tr>
        <td class="desc">Totaal</td>
        <td class="unit"></td>
        <td class="qty"></td>
        <td class="unit">@money($calculus::totalContracting($project)+$calculus::totalSubcontracting($project))</td>
    </tr>
</tbody>
</table>
@endif
