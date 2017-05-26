@inject('result', 'BynqIO\Dynq\Calculus\ResultEndresult')

<table class="table table-striped">
    <thead>
        <tr>
            <th class="col-md-2">&nbsp;</th>
            <th class="col-md-2">Balans project <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit is het uiteindelijke factuurbedrag van het project, hierin zit ook het stellen van de stelposten en het meer- en minderwerk verrekend." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></th>
            <th class="col-md-3">Totaalkosten urenregistratie <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit zijn de totaalkosten van het project die voortvloeien uit de geboekte uren uit de urenregistratie vermenigvuldigd met het geldende uurtarief. Let op: dit geldt alleen voor aanneming. Van de onderaanneming is dit niet bekent omdat we daar geen urenregistratie van bij kunnen houden." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></th>
            <th class="col-md-3">Totaalkosten inkoopfacturen <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit zijn de totaalkosten van alle inkoopfacturen zoals die ingeboekt zijn bij de inkoop. Hier wordt aanneming en onderaanneming wel gescheiden." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></th>
            <th class="col-md-2">Winst / Verlies project <a data-toggle="tooltip" data-placement="left" data-original-title="Hier staat uiteindelijk of je winst of verlies gemaakt heb op je project. Om een reëel beeld te krijgen is het belangrijk dat je alle uren en inkoopfacturen hebt ingeboekt en het eventuele meer- en minderwerk hebt verwerkt." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="col-md-2"><strong>Aanneming</strong></td>
            <td class="col-md-2">{{ '&euro; ' . \BynqIO\Dynq\Services\FormatService::monetary($result::totalContracting($project)) }}</td>
            <td class="col-md-3">{{ '&euro; ' . \BynqIO\Dynq\Services\FormatService::monetary($result::totalTimesheet($project)) }}</td>
            <td class="col-md-3">{{ '&euro; ' . \BynqIO\Dynq\Services\FormatService::monetary($result::totalContractingPurchase($project)) }}</td>
            <td class="col-md-2">{{ '&euro; ' . \BynqIO\Dynq\Services\FormatService::monetary($result::totalContractingBudget($project)) }}</td>
        </tr>
        @if ($project->use_subcontract)
        <tr>
            <td class="col-md-2"><strong>Onderaanneming</strong></td>
            <td class="col-md-2">{{ '&euro; ' . \BynqIO\Dynq\Services\FormatService::monetary($result::totalSubcontracting($project)) }}</td>
            <td class="col-md-3">-</td>
            <td class="col-md-3">{{ '&euro; ' . \BynqIO\Dynq\Services\FormatService::monetary($result::totalSubcontractingPurchase($project)) }}</td>
            <td class="col-md-2">{{ '&euro; ' . \BynqIO\Dynq\Services\FormatService::monetary($result::totalSubcontractingBudget($project)) }}</td>
        </tr>
        @endif
    </tbody>
</table>
<h5><strong><i class="fa fa-info-circle" aria-hidden="true"></i> Weergegeven bedragen zijn exclusief BTW</strong></h5>
