<?php

use \Calctool\Models\Project;
use \Calctool\Models\Relation;
use \Calctool\Models\Chapter;
use \Calctool\Models\Activity;
use \Calctool\Models\Part;
use \Calctool\Models\PartType;
use \Calctool\Models\Contact;
use \Calctool\Models\Invoice;
use \Calctool\Models\Offer;
use \Calctool\Models\Detail;
use \Calctool\Models\ProjectType;
use \Calctool\Models\Resource;
use \Calctool\Models\BlancRow;
use \Calctool\Models\Tax;
use \Calctool\Calculus\EstimateEndresult;
use \Calctool\Calculus\MoreEndresult;
use \Calctool\Calculus\LessEndresult;
use \Calctool\Calculus\ResultEndresult;
use \Calctool\Calculus\CalculationOverview;
use \Calctool\Calculus\EstimateOverview;
use \Calctool\Calculus\LessOverview;
use \Calctool\Calculus\MoreOverview;
use \Calctool\Calculus\BlancRowsEndresult;
use \Calctool\Http\Controllers\OfferController;

$_invoice = Invoice::find($invoice->invoice_id);
if (!$_invoice)
  exit();
$offer = Offer::find($_invoice->offer_id);
if (!$offer)
  exit();
$project = Project::find($offer->project_id);
if (!$project || !$project->isOwner()) {
  exit();
}

$relation = Relation::find($project->client_id);
$relation_self = Relation::find(Auth::user()->self_id);
  if ($relation_self)
  $contact_self = Contact::where('relation_id','=',$relation_self->id);

$include_tax = $invoice->include_tax; //BTW bedragen weergeven 1/6
$only_totals = $invoice->only_totals; //Alleen het totale offertebedrag weergeven 2/6
$seperate_subcon = !$invoice->seperate_subcon; //Onderaanneming apart weergeven 3/6
$display_worktotals = $invoice->display_worktotals; //Kosten werkzaamheden weergeven 4/6
$display_specification = $invoice->display_specification; //Onderdeel en werkzaamheden weergeven 5/6
$display_description = $invoice->display_description;  //Omschrijving werkzaamheden weergeven 6/6

print_r($invoice);


exit();


$term=0;

$type = ProjectType::find($project->type_id);
/*erm=0;
$cnt = Invoice::where('offer_id','=', $invoice->offer_id)->count();
if ($cnt>1)
  $term=1;
*/
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Factuur</title>
    <link rel="stylesheet" href="{{ asset('css/pdf.css') }}" media="all" />
  </head>





 <body style="background-image: url(http://localhost/images/concept.png);">
     <header class="clearfix">
        <div id="heading" class="clearfix">
        <table border="0" cellspacing="0" cellpadding="0">
          <tbody>
            <tr>
              <td style="width: 345px">
                <div id="logo">
                  <?php if ($relation_self && $relation_self->logo_id) echo "<img src=\"".asset(Resource::find($relation_self->logo_id)->file_location)."\"/>"; ?>
                </div>
              </td>

              <td style="width: 300px">
              
                <table border="0" cellspacing="0" cellpadding="0">
                  <tbody>
                    <tr>
                      <td style="width: 300 px">
                        <div class="name"><h2>{{ $relation_self->company_name }}</h2></div>

                      </td>
                    </tr>
                  </tbody>
                </table>
          
                <table border="0" cellspacing="0" cellpadding="0">
                  <tbody>
                    <tr>
                      <td style="width: 100px">
                        <div><strong>Adres:</strong></div>
                        <div><strong>&nbsp;</strong></div>
                        @if ($relation_self->phone)<div><strong>Telefoon:</strong></div>@endif
                        @if ($relation_self->email)<div><strong>E-mail:</strong></div>@endif  
                        @if ($relation_self->kvk)<div><strong>KVK:</strong></div>@endif 
                        <div><strong>Rekening:</strong></div>
                      </td>
                      <td style="width: 200px">
                        <div>{{ $relation_self->address_street . ' ' . $relation_self->address_number }}</div>  
                        <div>{{ $relation_self->address_postal . ', ' . $relation_self->address_city }}</div>
                        @if ($relation_self->phone)<div>{{ $relation_self->phone }} </div>@endif  
                        @if ($relation_self->email)<div>{{ $relation_self->email }}</div>@endif 
                        @if ($relation_self->kvk)<div>{{ $relation_self->kvk }}</div>@endif 
                        <div>{{ $relation_self->iban }}</div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </td>
            </tr>
          </tbody>
        </table>
        </div>
    </header>
    <main>
    <div id="heading" class="clearfix">
        <table border="0" cellspacing="0" cellpadding="0">
      <tbody>
        <tr>
          <td style="width: 345px">
          </td>
          <td style="width: 300px">
            <div><h2 class="type">FACTUUR</h2></div>
          </td>
        </tr>
      </tbody>
    </table>
    <table border="0" cellspacing="0" cellpadding="0">
      <tbody>
        <tr>
          <td style="width: 345px">
            <table border="0" cellspacing="0" cellpadding="0" class="to">
              <tbody>
                <tr>
                  <td>{{ $relation->company_name }}</td>
                </tr>
                <tr>
                  <td>T.a.v. {{ Contact::find($invoice->to_contact_id)->getFormalName() }}</td>
                </tr>
                <tr>
                  <td>{{ $relation->address_street . ' ' . $relation->address_number }}</td>
                </tr>
                <tr>
                  <td>{{ $relation->address_postal . ', ' . $relation->address_city }}</td>
                </tr>
              </tbody>
            </table>
          </td>
          <td style="width: 300px">
            <table border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td style="width: 100px">
                    <div><strong>Factuurnummer:</strong></div>
                    <div><strong>Projectnaam:</strong></div>
                    @if ($invoice->reference)<div><strong>Uw referentie:</strong></div>@endif
                    @if ($invoice->book_code)<div><strong>Boekhoudnummer:</strong></div>@endif
                    <div><strong>Factuurdatum:</strong></div>
                  </td>
                  <td style="width: 200px">
                    <div>{{ $invoice->invoice_code }}</div>
                    <div>{{ $project->project_name }}</div>
                    @if ($invoice->reference)<div>{{ $invoice->reference }}</div>@endif
                    @if ($invoice->book_code) <div>{{ $invoice->book_code }}</div>@endif
                    <div>{{ date("j M Y") }}</div>
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
      </tbody>
    </table>
  <br>
  <br>
  </div>
  <div id="spacing"></div>
  <div class="openingtext">Geachte {{ Contact::find($invoice->to_contact_id)->getFormalName() }},</div>
  <br>
  <div class="openingtext">{{ ($invoice ? $invoice->description : '') }}</div>
  <br>




















@if ($seperate_subcon)

<?#--TOTAL START--?>

    <h2 class="name">Specificatie factuur</h2>
    <hr color="#000" size="1">
    @if($type->type_name == 'snelle offerte en factuur')
    <table border="0" cellspacing="0" cellpadding="0">
    <thead>
      <tr style="page-break-after: always;">
      <th style="width: 147px" align="left" class="qty">Omschrijving</th>
      <th style="width: 60px" align="left" class="qty">€ / Eenh (excl. BTW)</th>
      <th style="width: 119px" align="left" class="qty">Aantal</th>
      <th style="width: 70px" align="left" class="qty">Totaal</th>
      <th style="width: 80px" align="left" class="qty">BTW</th>
      <th style="width: 119px" align="left" class="qty">BTW bedrag</th>
      </tr>
    </thead>
    <tbody>
      @foreach (BlancRow::where('project_id','=', $project->id)->get() as $row)
      <tr style="page-break-after: always;">
      <td class="qty">{{ $row->description }}</td>
      <td class="qty">{{ '&euro; '.number_format($row->rate, 2, ",",".") }}</td>
      <td class="qty">{{ '&euro; '.number_format($row->amount, 2, ",",".") }}</td>
      <td class="qty">{{ '&euro; '.number_format($row->rate * $row->amount, 2, ",",".") }}</td>
      <td class="qty">{{ Tax::find($row->tax_id)->tax_rate }}%</td>
      <td class="qty">{{ '&euro; '.number_format(($row->rate * $row->amount/100) * Tax::find($row->tax_id)->tax_rate, 2, ",",".") }}</td>
      </tr>
      @endforeach
    </tbody>
    </table>
    @else
    <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr style="page-break-after: always;">
          <th class="qty">&nbsp;</th>
          <th class="qty">Calculatie</th>
          <th class="qty">Meerwerk</th>
          <th class="qty">Minderwerk</th>
          <th class="qty">Balans</th>
          <th class="qty">BTW %</th>
          <th class="qty">@if ($include_tax) BTW bedrag @endif</th>
        </tr>
      </thead>
      <tbody>
        @if (!$project->tax_reverse)
        <tr style="page-break-after: always;">
          <td class="qty">Arbeidskosten</td>
          <td class="qty">{{ '&euro; '.number_format(EstimateEndresult::conCalcLaborActivityTax1Amount($project)+EstimateEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(MoreEndresult::conCalcLaborActivityTax1Amount($project)+MoreEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax1Amount($project)+LessEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax1($project)+ResultEndresult::subconLaborBalanceTax1($project), 2, ",",".") }}</td>
          <td class="qty">21%</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax1AmountTax($project)+ResultEndresult::subconLaborBalanceTax1AmountTax($project), 2, ",",".") }}</td>
        </tr>
        <tr style="page-break-after: always;">
          <td class="qty">&nbsp;</td>
          <td class="qty">{{ '&euro; '.number_format(EstimateEndresult::conCalcLaborActivityTax2Amount($project)+EstimateEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(MoreEndresult::conCalcLaborActivityTax2Amount($project)+MoreEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax2Amount($project)+LessEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax2($project)+ResultEndresult::subconLaborBalanceTax2($project), 2, ",",".") }}</td>
          <td class="qty">6%</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax2AmountTax($project)+ResultEndresult::subconLaborBalanceTax2AmountTax($project), 2, ",",".") }}</td>
        </tr>
        @else
        <tr style="page-break-after: always;">
          <td class="qty">Arbeidskosten</td>
          <td class="qty">{{ '&euro; '.number_format(EstimateEndresult::conCalcLaborActivityTax3Amount($project)+EstimateEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(MoreEndresult::conCalcLaborActivityTax3Amount($project)+MoreEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax3Amount($project)+LessEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax3($project)+ResultEndresult::subconLaborBalanceTax3($project), 2, ",",".") }}</td>
          <td class="qty">0%</td>
          <td class="qty">&nbsp;</td>
        </tr>
        @endif

        @if (!$project->tax_reverse)
        <tr style="page-break-after: always;">
          <td class="qty">Materiaalkosten</td>
          <td class="qty">{{ '&euro; '.number_format(EstimateEndresult::conCalcMaterialActivityTax1Amount($project)+EstimateEndresult::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(MoreEndresult::conCalcMaterialActivityTax1Amount($project)+MoreEndresult::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax1Amount($project)+LessEndresult::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax1($project)+ResultEndresult::subconMaterialBalanceTax1($project), 2, ",",".") }}</td>
          <td class="qty">21%</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax1AmountTax($project)+ResultEndresult::subconMaterialBalanceTax1AmountTax($project), 2, ",",".") }}</td>
        </tr>
        <tr style="page-break-after: always;">
          <td class="qty">&nbsp;</td>
          <td class="qty">{{ '&euro; '.number_format(EstimateEndresult::conCalcMaterialActivityTax2Amount($project)+EstimateEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(MoreEndresult::conCalcMaterialActivityTax2Amount($project)+MoreEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax2Amount($project)+LessEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax2($project)+ResultEndresult::subconMaterialBalanceTax2($project), 2, ",",".") }}</td>
          <td class="qty">6%</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax2AmountTax($project)+ResultEndresult::subconMaterialBalanceTax2AmountTax($project), 2, ",",".") }}</td>
        </tr>
        @else
        <tr style="page-break-after: always;">
          <td class="qty">Materiaalkosten</td>
          <td class="qty">{{ '&euro; '.number_format(EstimateEndresult::conCalcMaterialActivityTax3Amount($project)+EstimateEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(MoreEndresult::conCalcMaterialActivityTax3Amount($project)+MoreEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax3Amount($project)+LessEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax3($project)+ResultEndresult::subconMaterialBalanceTax3($project), 2, ",",".") }}</td>
          <td class="qty">0%</td>
          <td class="qty">&nbsp;</td>
        </tr>
        @endif

        @if (!$project->tax_reverse)
        <tr style="page-break-after: always;">
          <td class="qty">Overige kosten</td>
          <td class="qty">{{ '&euro; '.number_format(EstimateEndresult::conCalcEquipmentActivityTax1Amount($project)+EstimateEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(MoreEndresult::conCalcEquipmentActivityTax1Amount($project)+MoreEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax1Amount($project)+LessEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax1($project)+ResultEndresult::subconEquipmentBalanceTax1($project), 2, ",",".") }}</td>
          <td class="qty">21%</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax1AmountTax($project)+ResultEndresult::subconEquipmentBalanceTax1AmountTax($project), 2, ",",".") }}</td>
        </tr>
        <tr style="page-break-after: always;">
          <td class="qty">&nbsp;</td>
          <td class="qty">{{ '&euro; '.number_format(EstimateEndresult::conCalcEquipmentActivityTax2Amount($project)+EstimateEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(MoreEndresult::conCalcEquipmentActivityTax2Amount($project)+MoreEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax2Amount($project)+LessEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax2($project)+ResultEndresult::subconEquipmentBalanceTax2($project), 2, ",",".") }}</td>
          <td class="qty">6%</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax2AmountTax($project)+ResultEndresult::subconEquipmentBalanceTax2AmountTax($project), 2, ",",".") }}</td>
        </tr>
        @else
        <tr style="page-break-after: always;">
          <td class="qty">Overige kosten</td>
          <td class="qty">{{ '&euro; '.number_format(EstimateEndresult::conCalcEquipmentActivityTax3Amount($project)+EstimateEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(MoreEndresult::conCalcEquipmentActivityTax3Amount($project)+MoreEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax3Amount($project)+LessEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax3($project)+ResultEndresult::subconEquipmentBalanceTax3($project), 2, ",",".") }}</td>
          <td class="qty">0%</td>
          <td class="qty">&nbsp;</td>
        </tr>
        @endif
        <tr style="page-break-after: always;">
          <td class="qty"><strong>Totaal Aanneming </strong></td>
          <td class="qty"><strong>{{ '&euro; '.number_format(EstimateEndresult::totalContracting($project)+EstimateEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
          <td class="qty"><strong>{{ '&euro; '.number_format(MoreEndresult::totalContracting($project)+MoreEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
          <td class="qty"><strong>{{ '&euro; '.number_format(LessEndresult::totalContracting($project)+LessEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
          <td class="qty"><strong>{{ '&euro; '.number_format(ResultEndresult::totalContracting($project)+ResultEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
          <td class="qty">&nbsp;</td>
          <td class="qty"><strong>{{ '&euro; '.number_format(ResultEndresult::totalContractingTax($project)+ResultEndresult::totalSubcontractingTax($project), 2, ",",".") }}</strong></td>
        </tr>
      </tbody>
    </table>
    @endif

    <h2 class="name">Totalen Factuur</h2>
    <hr color="#000" size="1">

    <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr style="page-break-after: always;">
          <th class="qty">&nbsp;</th>
          <th class="qty">Bedrag (excl. BTW)</th>
          <th class="qty">BTW bedrag</th>
          <th class="qty">Bedrag (incl. BTW)</th>
        </tr>
      </thead>
      <tbody>
        <tr style="page-break-after: always;">
          <td class="qty">Calculatief te factureren (excl. BTW)</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::totalProject($project), 2, ",",".") }}</td>
          <td class="qty">&nbsp;</td>
          <td class="qty">&nbsp;</td>
        </tr>
        @if (!$project->tax_reverse)
        <tr style="page-break-after: always;">
          <td class="qty">BTW bedrag calculatie belast met 21%</td>
          <td class="qty">&nbsp;</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::totalContractingTax1($project)+ResultEndresult::totalSubcontractingTax1($project)+BlancRowsEndresult::rowTax1AmountTax($project), 2, ",",".") }}</td>
          <td class="qty">&nbsp;</td>
        </tr>
        <tr style="page-break-after: always;">
          <td class="qty">BTW bedrag calculatie belast met 6%</td>
          <td class="qty">&nbsp;</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::totalContractingTax2($project)+ResultEndresult::totalSubcontractingTax2($project)+BlancRowsEndresult::rowTax2AmountTax($project), 2, ",",".") }}</td>
          <td class="qty">&nbsp;</td>
        </tr>
        <tr style="page-break-after: always;">
          <td class="qty">BTW bedrag calculatie belast met 6%</td>
          <td class="qty">&nbsp;</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::totalContractingTax2($project)+ResultEndresult::totalSubcontractingTax2($project)+BlancRowsEndresult::rowTax1AmountTax($project)+BlancRowsEndresult::rowTax2AmountTax($project), 2, ",",".") }}</td>
          <td class="qty">&nbsp;</td>
        </tr>
        @endif
        <tr style="page-break-after: always;">
          <td class="qty">Te factureren BTW bedrag</td>
          <td class="qty">&nbsp;</td>
          <td class="qty">&nbsp;</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::totalProjectTax($project)+BlancRowsEndresult::rowTax1AmountTax($project)+BlancRowsEndresult::rowTax2AmountTax($project), 2, ",",".") }}</td>
        </tr>
        <tr style="page-break-after: always;">
          <td class="qty"><strong>Calculatief te factureren (Incl. BTW)</strong></td>
          <td class="qty">&nbsp;</td>
          <td class="qty">&nbsp;</td>
          <td class="qty"><strong>{{ '&euro; '.number_format(ResultEndresult::superTotalProject($project)+BlancRowsEndresult::rowTax1AmountTax($project)+BlancRowsEndresult::rowTax2AmountTax($project), 2, ",",".") }}</strong></td>
        </tr>
      </tbody>
    </table>

                            <?#--INCLUDE TERM START--?>

                                <?php
                                $cnt = Invoice::where('offer_id','=', $invoice->offer_id)->count();
                                if ($cnt>1) {
                                ?>

                                <h4>Reeds betaald middels termijnfacturen</h4>
                                <table class="table table-striped hide-btw2">
                                  <thead>
                                    <tr>
                                      <th class="qty">&nbsp;</th>
                                      <th class="qty">Bedrag (excl. BTW)</th>
                                      <th class="qty">BTW bedrag</th>
                                      <th class="qty">Bedrag (incl. BTW);</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td class="qty">Laatste van in totaal {{Invoice::where('offer_id','=', $invoice->offer_id)->count()}} termijnen</td>
                                      <td class="qty">{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('amount'), 2, ",",".") }}</td>
                                      <td class="qty">&nbsp;</td>
                                      <td class="qty">&nbsp;</td>
                                    </tr>
                                    @if (!$project->tax_reverse)
                                    <tr>
                                      <td class="qty">Factuurbedrag in 21% BTW tarief</td>
                                      <td class="qty">{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('rest_21'), 2, ",",".") }}</td>
                                      <td class="qty">&nbsp;</td>
                                      <td class="qty">&nbsp;</td>
                                    </tr>
                                    <tr>
                                      <td class="qty">Factuurbedrag belast met 6% BTW</td>
                                      <td class="qty">{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('rest_6'), 2, ",",".") }}</td>
                                      <td class="qty">&nbsp;</td>
                                      <td class="qty">&nbsp;</td>
                                    </tr>
                                    @else
                                    <tr>
                                      <td class="qty">Factuurbedrag belast met 0% BTW</td>
                                      <td class="qty">{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('rest_0'), 2, ",",".") }}</td>
                                      <td class="qty">&nbsp;</td>
                                      <td class="qty">&nbsp;</td>
                                    </tr>
                                    @endif
                                    @if (!$project->tax_reverse)
                                    <tr>
                                      <td class="qty">BTW bedrag belast met 21%</td>
                                      <td class="qty">&nbsp;</td>
                                      <td class="qty">{{ '&euro; '.number_format((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('rest_21')/100)*21, 2, ",",".") }}</td>
                                      <td class="qty">&nbsp;</td>
                                    </tr>
                                    <tr>
                                      <td class="qty">BTW bedrag belast met 6%</td>
                                      <td class="qty">&nbsp;</td>
                                      <td class="qty">{{ '&euro; '.number_format((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('rest_6')/100)*6, 2, ",",".") }}</td>
                                      <td class="qty">&nbsp;</td>
                                    </tr>
                                    @endif
                                    <tr>
                                      <td class="qty"><strong>Calculatief te factureren (Incl. BTW)</strong></td>
                                      <td class="qty">&nbsp;</td>
                                      <td class="qty">&nbsp;</td>
                                      <td class="qty"><strong>{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('amount')+((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('rest_21')/100)*21)+((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('rest_6')/100)*6), 2, ",",".") }}</strong></td>
                                    </tr>
                                  </tbody>
                                </table>


                                  <?#--PAGE HEADER SECOND START--?>
                                  <div style="page-break-after:always;"></div>
                                  <header class="clearfix">
                                  <div id="logo">
                                  <?php if ($relation_self && $relation_self->logo_id) echo "<img src=\"".asset(Resource::find($relation_self->logo_id)->file_location)."\"/>"; ?>
                                  </div>
                                    <div id="invoice">

                                    <span>{{ $invoice->invoice_code }}</span>
                                    <span>{{ $project->project_name }}</span>
                                    <span>{{ date("j M Y", strtotime($offer->offer_make)) }}</span>
                                  </div>
                                  </header>
                                  <?#--PAGE HEADER SECOND END--?>

                                  <h4>Resterend te betalen</h4>
                                  <table class="table table-striped hide-btw2">
                                    <thead>
                                      <tr>
                                        <th class="qty">&nbsp;</th>
                                        <th class="qty">Bedrag (excl. BTW)</th>
                                        <th class="qty">BTW bedrag</th>
                                        <th class="qty">Bedrag (incl. BTW);</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr>
                                        <td class="qty">Laatste van in totaal {{Invoice::where('offer_id','=', $invoice->offer_id)->count()}} termijnen</td>
                                        <td class="qty">{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->amount, 2, ",",".") }}</td>
                                        <td class="qty">&nbsp;</td>
                                        <td class="qty">&nbsp;</td>
                                      </tr>
                                      @if (!$project->tax_reverse)
                                      <tr>
                                        <td class="qty">Factuurbedrag belast met 21% BTW</td>
                                        <td class="qty">{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->rest_21, 2, ",",".") }}</td>
                                        <td class="qty">&nbsp;</td>
                                        <td class="qty">&nbsp;</td>
                                      </tr>
                                      <tr>
                                        <td class="qty">Factuurbedrag belast met 6% BTW</td>
                                        <td class="qty">{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->rest_6, 2, ",",".") }}</td>
                                        <td class="qty">&nbsp;</td>
                                        <td class="qty">&nbsp;</td>
                                      </tr>
                                      @else
                                      <tr>
                                        <td class="qty">Factuurbedrag belast met 0% BTW</td>
                                        <td class="qty">{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->rest_0, 2, ",",".") }}</td>
                                        <td class="qty">&nbsp;</td>
                                        <td class="qty">&nbsp;</td>
                                      </tr>
                                      @endif
                                      @if (!$project->tax_reverse)
                                      <tr>
                                        <td class="qty">BTW bedrag belast met 21%</td>
                                        <td class="qty">&nbsp;</td>
                                        <td class="qty">{{ '&euro; '.number_format((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->rest_21/100)*21, 2, ",",".") }}</td>
                                        <td class="qty">&nbsp;</td>
                                      </tr>
                                      <tr>
                                        <td class="qty">BTW bedrag belast met 6%</td>
                                        <td class="qty">&nbsp;</td>
                                        <td class="qty">{{ '&euro; '.number_format((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->rest_6/100)*6, 2, ",",".") }}</td>
                                        <td class="qty">&nbsp;</td>
                                      </tr>
                                      @endif
                                      <tr>
                                        <td class="qty"><strong>Calculatief te factureren (Incl. BTW)</strong></td>
                                        <td class="qty">&nbsp;</td>
                                        <td class="qty">&nbsp;</td>
                                        <td class="qty"><strong>{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->amount+((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->rest_21/100)*21)+((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->rest_6/100)*6), 2, ",",".") }}</strong></td>
                                      </tr>
                                    </tbody>
                                  </table>
                                  <?php } ?>
                                <?#--INCLUDE TERM END--?>


      <h2 class="name">Bepalingen</h2>
      <hr color="#000" size="1">

      <div class="terms">
        <li>Deze factuur dient betaald te worden binnen {{ $invoice->payment_condition }} dagen na dagtekening.</li>
      </div>

      <div class="closingtext">{{ ($invoice ? $invoice->closure : '') }}</div>

      <div class="signing">Met vriendelijke groet,</div>
      <div class="signing">{{ Contact::find($invoice->from_contact_id)->firstname ." ". Contact::find($invoice->from_contact_id)->lastname }}</div>
    </main>

<?#--TOTAL END--?>

@else



<?#--CONT & SUBCONTR START--?>

    <h2 class="name">Specificatie factuur</h2>
    <hr color="#000" size="1">

    <h2 class="name">Aanneming</h2>
    <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr style="page-break-after: always;">
          <th class="qty">&nbsp;</th>
          <th class="qty">Calculatie</th>
          <th class="qty">Meerwerk</th>
          <th class="qty">Minderwerk</th>
          <th class="qty">Balans</th>
          <th class="qty">BTW %</th>
          <th class="qty">BTW bedrag</th>
        </tr>
      </thead>
      <tbody>
       @if (!$project->tax_reverse)
        <tr style="page-break-after: always;">
          <td style="width: 140px" class="qty">Arbeidskosten</td>
          <td class="qty">{{ '&euro; '.number_format(EstimateEndresult::conCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(MoreEndresult::conCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax1($project), 2, ",",".") }}</td>
          <td class="qty">21%</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax1AmountTax($project), 2, ",",".") }}</td>
        </tr>
        <tr style="page-break-after: always;">
          <td class="qty">&nbsp;</td>
          <td class="qty">{{ '&euro; '.number_format(EstimateEndresult::conCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(MoreEndresult::conCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax2($project), 2, ",",".") }}</td>
          <td class="qty">6%</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax2AmountTax($project), 2, ",",".") }}</td>
        </tr>
        @else
        <tr style="page-break-after: always;">
          <td class="qty">Arbeidskosten</td>
          <td class="qty">{{ '&euro; '.number_format(EstimateEndresult::conCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(MoreEndresult::conCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax3($project), 2, ",",".") }}</td>
          <td class="qty">0%</td>
          <td class="qty">&nbsp;</td>
        </tr>
        @endif

        @if (!$project->tax_reverse)
        <tr style="page-break-after: always;">
          <td class="qty">Materiaalkosten</td>
          <td class="qty">{{ '&euro; '.number_format(EstimateEndresult::conCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(MoreEndresult::conCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax1($project), 2, ",",".") }}</td>
          <td class="qty">21%</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax1AmountTax($project), 2, ",",".") }}</td>
        </tr>
        <tr style="page-break-after: always;">
          <td class="qty">&nbsp;</td>
          <td class="qty">{{ '&euro; '.number_format(EstimateEndresult::conCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(MoreEndresult::conCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax2($project), 2, ",",".") }}</td>
          <td class="qty">6%</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax2AmountTax($project), 2, ",",".") }}</td>
        </tr>
        @else
        <tr style="page-break-after: always;">
          <td class="qty">Materiaalkosten</td>
          <td class="qty">{{ '&euro; '.number_format(EstimateEndresult::conCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(MoreEndresult::conCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax3($project), 2, ",",".") }}</td>
          <td class="qty">0%</td>
          <td class="qty">&nbsp;</td>
        </tr>
        @endif

        @if (!$project->tax_reverse)
        <tr style="page-break-after: always;">
          <td class="qty">Overige kosten</td>
          <td class="qty">{{ '&euro; '.number_format(EstimateEndresult::conCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(MoreEndresult::conCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax1($project), 2, ",",".") }}</td>
          <td class="qty">21%</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax1AmountTax($project), 2, ",",".") }}</td>
        </tr>
        <tr style="page-break-after: always;">
          <td class="qty">&nbsp;</td>
          <td class="qty">{{ '&euro; '.number_format(EstimateEndresult::conCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(MoreEndresult::conCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax2($project), 2, ",",".") }}</td>
          <td class="qty">6%</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax2AmountTax($project), 2, ",",".") }}</td>
        </tr>
        @else
        <tr style="page-break-after: always;">
          <td class="qty">Overige kosten</td>
          <td class="qty">{{ '&euro; '.number_format(EstimateEndresult::conCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(MoreEndresult::conCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax3($project), 2, ",",".") }}</td>
          <td class="qty">0%</td>
          <td class="qty">&nbsp;</td>
        </tr>
        @endif
        <tr style="page-break-after: always;">
          <td class="qty"><strong>Totaal </strong></td>
          <td class="qty"><strong>{{ '&euro; '.number_format(EstimateEndresult::totalContracting($project), 2, ",",".") }}</strong></td>
          <td class="qty"><strong>{{ '&euro; '.number_format(MoreEndresult::totalContracting($project), 2, ",",".") }}</strong></td>
          <td class="qty"><strong>{{ '&euro; '.number_format(LessEndresult::totalContracting($project), 2, ",",".") }}</strong></td>
          <td class="qty"><strong>{{ '&euro; '.number_format(ResultEndresult::totalContracting($project), 2, ",",".") }}</strong></td>
          <td class="qty">&nbsp;</td>
          <td class="qty"><strong>{{ '&euro; '.number_format(ResultEndresult::totalContractingTax($project), 2, ",",".") }}</strong></td>
        </tr>
      </tbody>
    </table>

    <h2 class="name">Onderaanneming</h2>
    <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr style="page-break-after: always;">
          <th class="qty">&nbsp;</th>
          <th class="qty">Calculatie</th>
          <th class="qty">Meerwerk</th>
          <th class="qty">Minderwerk</th>
          <th class="qty">Balans</th>
          <th class="qty">BTW %</th>
          <th class="qty">BTW bedrag</th>
        </tr>
      </thead>
      <tbody>
       @if (!$project->tax_reverse)
        <tr style="page-break-after: always;">
          <td class="qty">Arbeidskosten</td>
          <td class="qty">{{ '&euro; '.number_format(EstimateEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(MoreEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::subconLaborBalanceTax1($project), 2, ",",".") }}</td>
          <td class="qty">21%</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::subconLaborBalanceTax1AmountTax($project), 2, ",",".") }}</td>
        </tr>
        <tr style="page-break-after: always;">
          <td class="qty">&nbsp;</td>
          <td class="qty">{{ '&euro; '.number_format(EstimateEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(MoreEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::subconLaborBalanceTax2($project), 2, ",",".") }}</td>
          <td class="qty">6%</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::subconLaborBalanceTax2AmountTax($project), 2, ",",".") }}</td>
        </tr>
        @else
        <tr style="page-break-after: always;">
          <td class="qty">Arbeidskosten</td>
          <td class="qty">{{ '&euro; '.number_format(EstimateEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(MoreEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::subconLaborBalanceTax3($project), 2, ",",".") }}</td>
          <td class="qty">0%</td>
          <td class="qty">&nbsp;</td>
        </tr>
        @endif

        @if (!$project->tax_reverse)
        <tr style="page-break-after: always;">
          <td class="qty">Materiaalkosten</td>
          <td class="qty">{{ '&euro; '.number_format(EstimateEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(MoreEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::subconLaborBalanceTax3($project), 2, ",",".") }}</td>
          <td class="qty">21%</td>
          <td class="qty">&nbsp;</td>
        </tr>
        <tr style="page-break-after: always;">
          <td class="qty">&nbsp;</td>
          <td class="qty">{{ '&euro; '.number_format(EstimateEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(MoreEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(LessEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::subconMaterialBalanceTax2($project), 2, ",",".") }}</td>
          <td class="qty">6%</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::subconMaterialBalanceTax2AmountTax($project), 2, ",",".") }}</td>
        </tr>
        @else
        <tr style="page-break-after: always;">
          <td class="qty">Materiaalkosten</td>
          <td class="qty">{{ '&euro; '.number_format(EstimateEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(MoreEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(LessEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::subconMaterialBalanceTax3($project), 2, ",",".") }}</td>
          <td class="qty">0%</td>
          <td class="qty">&nbsp;</td>
        </tr>
        @endif

        @if (!$project->tax_reverse)
        <tr style="page-break-after: always;">
          <td class="qty">Overige kosten</td>
          <td class="qty">{{ '&euro; '.number_format(EstimateEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(MoreEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(LessEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::subconEquipmentBalanceTax1($project), 2, ",",".") }}</td>
          <td class="qty">21%</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::subconEquipmentBalanceTax1AmountTax($project), 2, ",",".") }}</td>
        </tr>
        <tr style="page-break-after: always;">
          <td class="qty">&nbsp;</td>
          <td class="qty">{{ '&euro; '.number_format(EstimateEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(MoreEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(LessEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::subconEquipmentBalanceTax2($project), 2, ",",".") }}</td>
          <td class="qty">6%</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::subconEquipmentBalanceTax2AmountTax($project), 2, ",",".") }}</td>
        </tr>
        @else
        <tr style="page-break-after: always;">
          <td class="qty">Overige kosten</td>
          <td class="qty">{{ '&euro; '.number_format(EstimateEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(MoreEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(LessEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::subconEquipmentBalanceTax3($project), 2, ",",".") }}</td>
          <td class="qty">0%</td>
          <td class="qty">&nbsp;</td>
        </tr>
        @endif
        <tr style="page-break-after: always;">
          <td style="width: 140px" class="qty"><strong>Totaal</strong></td>
          <td class="qty"><strong>{{ '&euro; '.number_format(EstimateEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
          <td class="qty"><strong>{{ '&euro; '.number_format(MoreEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
          <td class="qty"><strong>{{ '&euro; '.number_format(LessEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
          <td class="qty"><strong>{{ '&euro; '.number_format(ResultEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
          <td class="qty">&nbsp;</td>
          <td class="qty"><strong>{{ '&euro; '.number_format(ResultEndresult::totalSubcontractingTax($project), 2, ",",".") }}</strong></td>
        </tr>
      </tbody>
    </table>

    <h2 class="name">Totalen Factuur</h2>
    <hr color="#000" size="1">

    <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr style="page-break-after: always;">
          <th class="qty">&nbsp;</th>
          <th class="qty">Bedrag (excl. BTW)</th>
          <th class="qty">BTW bedrag</th>
          <th class="qty">Bedrag (incl. BTW)</th>
        </tr>
      </thead>
      <tbody>
        <tr style="page-break-after: always;">
          <td class="qty">Calculatief te factureren (excl. BTW)</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::totalProject($project), 2, ",",".") }}</td>
          <td class="qty">&nbsp;</td>
          <td class="qty">&nbsp;</td>
        </tr>
        @if (!$project->tax_reverse)
        <tr style="page-break-after: always;">
          <td class="qty">BTW bedrag aanneming belast met 21%</td>
          <td class="qty">&nbsp;</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::totalContractingTax1($project), 2, ",",".") }}</td>
          <td class="qty">&nbsp;</td>
        </tr>
        <tr style="page-break-after: always;">
          <td class="qty">BTW bedrag aanneming belast met 6%</td>
          <td class="qty">&nbsp;</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::totalContractingTax2($project), 2, ",",".") }}</td>
          <td class="qty">&nbsp;</td>
        </tr>
        <tr style="page-break-after: always;">
          <td class="qty">BTW bedrag onderaanneming belast met 21%</td>
          <td class="qty">&nbsp;</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::totalSubcontractingTax1($project), 2, ",",".") }}</td>
          <td class="qty">&nbsp;</td>
        </tr>
        <tr style="page-break-after: always;">
          <td class="qty">BTW bedrag onderaanneming belast met 6%</td>
          <td class="qty">&nbsp;</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::totalSubcontractingTax2($project), 2, ",",".") }}</td>
          <td class="qty">&nbsp;</td>
        </tr>
        @endif
        <tr style="page-break-after: always;">
          <td class="qty">Te factureren BTW bedrag</td>
          <td class="qty">&nbsp;</td>
          <td class="qty">&nbsp;</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::totalProjectTax($project), 2, ",",".") }}</td>
        </tr>
        <tr style="page-break-after: always;">
          <td class="qty"><strong>Calculatief te factureren (Incl. BTW)</strong></td>
          <td class="qty">&nbsp;</td>
          <td class="qty">&nbsp;</td>
          <td class="qty"><strong>{{ '&euro; '.number_format(ResultEndresult::superTotalProject($project), 2, ",",".") }}</strong></td>
        </tr>
      </tbody>
    </table>
                   
                              <?#--INCLUDE TERM START--?>
                              
                                    <?php
                                      $cnt = Invoice::where('offer_id','=', $invoice->offer_id)->count();
                                      if ($cnt>1) {
                                    ?>

                                    <h4>Reeds betaald</h4>
                                    <table class="table table-striped hide-btw2">
                                      <thead>
                                        <tr>
                                          <th class="qty">&nbsp;</th>
                                          <th class="qty">Bedrag (excl. BTW)</th>
                                          <th class="qty">BTW bedrag</th>
                                          <th class="qty">Bedrag (incl. BTW);</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <tr>
                                          <td class="qty">Laatste van in totaal {{Invoice::where('offer_id','=', $invoice->offer_id)->count()}} termijnen</td>
                                          <td class="qty">{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('amount'), 2, ",",".") }}</td>
                                          <td class="qty">&nbsp;</td>
                                          <td class="qty">&nbsp;</td>
                                        </tr>
                                        @if (!$project->tax_reverse)
                                        <tr>
                                          <td class="qty">Factuurbedrag belast met 21% BTW</td>
                                          <td class="qty">{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('rest_21'), 2, ",",".") }}</td>
                                          <td class="qty">&nbsp;</td>
                                          <td class="qty">&nbsp;</td>
                                        </tr>
                                        <tr>
                                          <td class="qty">Factuurbedrag belast met 6% BTW</td>
                                          <td class="qty">{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('rest_6'), 2, ",",".") }}</td>
                                          <td class="qty">&nbsp;</td>
                                          <td class="qty">&nbsp;</td>
                                        </tr>
                                        @else
                                        <tr>
                                          <td class="qty">Factuurbedrag belast met 0% BTW</td>
                                          <td class="qty">{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('rest_0'), 2, ",",".") }}</td>
                                          <td class="qty">&nbsp;</td>
                                          <td class="qty">&nbsp;</td>
                                        </tr>
                                        @endif

                                        @if (!$project->tax_reverse)
                                        <tr>
                                          <td class="qty">BTW bedrag belast met 21%</td>
                                          <td class="qty">&nbsp;</td>
                                          <td class="qty">{{ '&euro; '.number_format((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('rest_21')/100)*21, 2, ",",".") }}</td>
                                          <td class="qty">&nbsp;</td>
                                        </tr>
                                        <tr>
                                          <td class="qty">BTW bedrag belast met 6%</td>
                                          <td class="qty">&nbsp;</td>
                                          <td class="qty">{{ '&euro; '.number_format((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('rest_6')/100)*6, 2, ",",".") }}</td>
                                          <td class="qty">&nbsp;</td>
                                        </tr>
                                        @endif

                                        <tr>
                                          <td class="qty"><strong>Calculatief te factureren (Incl. BTW)</strong></td>
                                          <td class="qty">&nbsp;</td>
                                          <td class="qty">&nbsp;</td>
                                          <td class="qty"><strong>{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('amount')+((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('rest_21')/100)*21)+((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('rest_6')/100)*6), 2, ",",".") }}</strong></td>
                                        </tr>
                                      </tbody>
                                    </table>

                                    <h4>Resterend te betalen</h4>
                                    <table class="table table-striped hide-btw2">
                                      <thead>
                                        <tr>
                                          <th class="qty">&nbsp;</th>
                                          <th class="qty">Bedrag (excl. BTW)</th>
                                          <th class="qty">BTW bedrag</th>
                                          <th class="qty">Bedrag (incl. BTW);</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <tr>
                                          <td class="qty">Laatste van in totaal {{Invoice::where('offer_id','=', $invoice->offer_id)->count()}} termijnen</td>
                                          <td class="qty">{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->amount, 2, ",",".") }}</td>
                                          <td class="qty">&nbsp;</td>
                                          <td class="qty">&nbsp;</td>
                                        </tr>
                                        @if (!$project->tax_reverse)
                                        <tr>
                                          <td class="qty">Factuurbedrag belast met 21% BTW</td>
                                          <td class="qty">{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->rest_21, 2, ",",".") }}</td>
                                          <td class="qty">&nbsp;</td>
                                          <td class="qty">&nbsp;</td>
                                        </tr>
                                        <tr>
                                          <td class="qty">Factuurbedrag belast met 6% BTW</td>
                                          <td class="qty">{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->rest_6, 2, ",",".") }}</td>
                                          <td class="qty">&nbsp;</td>
                                          <td class="qty">&nbsp;</td>
                                        </tr>
                                        @else
                                        <tr>
                                          <td class="qty">Factuurbedrag belast met 0% BTW</td>
                                          <td class="qty">{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->rest_0, 2, ",",".") }}</td>
                                          <td class="qty">&nbsp;</td>
                                          <td class="qty">&nbsp;</td>
                                        </tr>
                                        @endif

                                        @if (!$project->tax_reverse)
                                        <tr>
                                          <td class="qty">BTW bedrag belast met 21%</td>
                                          <td class="qty">&nbsp;</td>
                                          <td class="qty">{{ '&euro; '.number_format((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->rest_21/100)*21, 2, ",",".") }}</td>
                                          <td class="qty">&nbsp;</td>
                                        </tr>
                                        <tr>
                                          <td class="qty">BTW bedrag belast met 6%</td>
                                          <td class="qty">&nbsp;</td>
                                          <td class="qty">{{ '&euro; '.number_format((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->rest_6/100)*6, 2, ",",".") }}</td>
                                          <td class="qty">&nbsp;</td>
                                        </tr>
                                        @endif
                                        <tr>
                                          <td class="qty"><strong>Calculatief te factureren (Incl. BTW)</strong></td>
                                          <td class="qty">&nbsp;</td>
                                          <td class="qty">&nbsp;</td>
                                          <td class="qty"><strong>{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->amount+((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->rest_21/100)*21)+((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->rest_6/100)*6), 2, ",",".") }}</strong></td>
                                        </tr>
                                      </tbody>
                                    </table>
                                    <?php } ?>
                                <?#--INCLUDE TERM END--?>

      <h2 class="name">Bepalingen</h2>
        <hr color="#000" size="1">

        <div class="terms">
          <li>Deze factuur dient betaald te worden binnen {{ $invoice->payment_condition }} dagen na dagtekening.</li>
        </div>

        <div class="closingtext">{{ ($invoice ? $invoice->closure : '') }}</div>

        <div class="signing">Met vriendelijke groet,</div>
        <div class="signing">{{ Contact::find($invoice->from_contact_id)->firstname ." ". Contact::find($invoice->from_contact_id)->lastname }}</div>
      </main>

  @endif
  <?#--CON & SUBCONTR END--?>




  </body>
</html>
