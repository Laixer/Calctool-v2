<h3>Specificatie termijnfactuur</h3>

<table border="0" cellspacing="0" cellpadding="0">
<thead>
    <tr>
        <th class="desc" style="background-color:#fff;"></th>
        <th class="unit">Bedrag Excl.</th>
        <th class="qty">BTW</th>
        <th class="total">Bedrag Incl.</th>
    </tr>
</thead>
<tbody>
    <tr>
        <td class="desc">Xe van in totaal X betalingstermijnen</td>
        <td class="unit">@money(\BynqIO\Dynq\Calculus\CalculationEndresult::totalProject($project))</td>
        <td class="qty"></td>
        <td class="total"></td>
    </tr>
    <tr>
        <td class="desc">Aandeel termijnfactuur in 21% BTW categorie</td>
        <td class="unit">@money(\BynqIO\Dynq\Calculus\CalculationEndresult::totalContractingTax1($project)+\BynqIO\Dynq\Calculus\CalculationEndresult::totalSubcontractingTax1($project)+\BynqIO\Dynq\Calculus\BlancRowsEndresult::rowTax1AmountTax($project))</td>
        <td class="qty"></td>
        <td class="total"></td>
    </tr>
    <tr>
        <td class="desc">Aandeel termijnfactuur in 6% BTW categorie</td>
        <td class="unit">@money(\BynqIO\Dynq\Calculus\CalculationEndresult::totalContractingTax2($project)+\BynqIO\Dynq\Calculus\CalculationEndresult::totalSubcontractingTax2($project)+\BynqIO\Dynq\Calculus\BlancRowsEndresult::rowTax2AmountTax($project))</td>
        <td class="qty"></td>
        <td class="total"></td>
    </tr>
    <tr>
        <td class="desc">BTW bedrag 21%</td>
        <td class="unit"></td>
        <td class="qty">@money(\BynqIO\Dynq\Calculus\CalculationEndresult::totalContractingTax1($project)+\BynqIO\Dynq\Calculus\CalculationEndresult::totalSubcontractingTax1($project)+\BynqIO\Dynq\Calculus\BlancRowsEndresult::rowTax1AmountTax($project))</td>
        <td class="total"></td>
    </tr>
    <tr>
        <td class="desc">BTW bedrag 6%</td>
        <td class="unit"></td>
        <td class="qty">@money(\BynqIO\Dynq\Calculus\CalculationEndresult::totalContractingTax2($project)+\BynqIO\Dynq\Calculus\CalculationEndresult::totalSubcontractingTax2($project)+\BynqIO\Dynq\Calculus\BlancRowsEndresult::rowTax2AmountTax($project))</td>
        <td class="total"></td>
    </tr>
    <tr>
        <td class="desc">Calculatief te betalen</td>
        <td class="unit"></td>
        <td class="qty"></td>
        <td class="total">@money(\BynqIO\Dynq\Calculus\CalculationEndresult::superTotalProject($project)+\BynqIO\Dynq\Calculus\BlancRowsEndresult::rowTax1AmountTax($project)+\BynqIO\Dynq\Calculus\BlancRowsEndresult::rowTax2AmountTax($project))</td>
    </tr>
</tbody>
</table>
