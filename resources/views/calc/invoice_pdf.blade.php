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
use \Calctool\Calculus\EstimateEndresult;
use \Calctool\Calculus\MoreEndresult;
use \Calctool\Calculus\LessEndresult;
use \Calctool\Calculus\ResultEndresult;
use \Calctool\Calculus\CalculationOverview;
use \Calctool\Calculus\EstimateOverview;
use \Calctool\Calculus\LessOverview;
use \Calctool\Calculus\MoreOverview;
use \Calctool\Http\Controllers\OfferController;

//DELETE //
$total=Input::get("total");
$specification=Input::get("specification");
$description=Input::get("description");
$displaytax=Input::get("displaytax");
$endresult=Input::get("endresult");
$onlyactivity=Input::get("onlyactivity");
//DELETE //

$project = Project::find($invoice->project_id);
if (!$project || !$project->isOwner()) {
  exit();
}

$relation = Relation::find($project->client_id);
$relation_self = Relation::find(Auth::user()->self_id);
  if ($relation_self)
  $contact_self = Contact::where('relation_id','=',$relation_self->id);

$include_tax = $invoice->include_tax; //BTW bedragen weergeven
$only_totals = $invoice->only_totals; //Alleen het totale offertebedrag weergeven
$seperate_subcon = !$invoice->seperate_subcon; //Onderaanneming apart weergeven
$display_worktotals = $invoice->display_worktotals; //Kosten werkzaamheden weergeven
$display_specification = $invoice->display_specification; //Hoofdstukken en werkzaamheden weergeven
$display_description = $invoice->display_description;  //Omschrijving werkzaamheden weergeven


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

$term=0;
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

  
$masterheader (

  <?#--PAGE HEADER MASTER START--?>
  <header class="clearfix">
    <div id="logo">
    <?php if ($relation_self && $relation_self->logo_id) echo "<img src=\"".asset(Resource::find($relation_self->logo_id)->file_location)."\"/>"; ?>
    </div>
    <div id="company">
      <h3 class="name">{{ $relation_self->company_name }}</h3>
      <div>{{ $relation_self->address_street . ' ' . $relation_self->address_number }}</div>
      <div>{{ $relation_self->address_postal . ', ' . $relation_self->address_city }}</div>
      <div>{{ $relation_self->phone }}&nbsp;|&nbsp{{ $relation_self->email }}</div>
      <div>KVK:{{ $relation_self->kvk }}&nbsp;|&nbsp;BTW: {{ $relation_self->btw }}</div>
      <div>Rekeningnummer: {{ $relation_self->iban }}&nbsp;|&nbsp;tnv.: {{ $relation_self->iban_name }}</div>

  </header>
  <?#--PAGE HEADER MASTER END--?>

  <?#--ADRESSING START--?>
  <main>
    <div id="details" class="clearfix">
      <div id="client">
        <div>{{ $relation->company_name }}</div>
        <div>T.a.v. {{ Contact::find($invoice->to_contact_id)->getFormalName() }}</div>
        <div>{{ $relation->address_street . ' ' . $relation->address_number }}</div>
        <div>{{ $relation->address_postal . ', ' . $relation->address_city }}</div>
      </div>
      <div id="invoice">
        <h3 class="name">FACTUUR</h3>
        <div class="date">Projectnaam: {{ $project->project_name }}</div>
        <div class="date">Factuurnummer: {{ $invoice->invoice_code }}</div>
        <div class="date">Uw referentie: {{ $invoice->reference }}</div>
        <div class="date">Boekhoudkundignummer: {{ $invoice->book_code }}</div>
        <div class="date">Factuurdatum: {{ date("j M Y") }}</div>
      </div>
    </div>
    <?#--ADRESSING END--?>

<<<<<<< Updated upstream
    <div class="openingtext">Geachte {{ Contact::find($invoice->to_contact_id)->getFormalName() }},</div>
=======
  )












    <div class="openingtext">Geachte</div>
>>>>>>> Stashed changes
    <div class="openingtext">{{ ($invoice ? $invoice->description : '') }}</div>


    @if ($total)
    <?#--TOTAL START--?>

  echo ($masterheader)
    echo ($masterheader)
      echo ($masterheader)
        echo ($masterheader)
          echo ($masterheader)

    <h1 class="name">Specificatie factuur</h1>
    <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr style="page-break-after: always;">
          <th class="qty">&nbsp;</th>
          <th class="qty">Calculatie</th>
          <th class="qty">Meerwerk</th>
          <th class="qty">Minderwerk</th>
          <th class="qty">Balans</th>
          <th class="qty">BTW %</th>
          <th class="qty">@if ($displaytax) BTW bedrag @endif</th>
        </tr>
      </thead>
      <tbody>
        @if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
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

        @if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
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

        @if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
        <tr style="page-break-after: always;">
          <td class="qty">Materieelkosten</td>
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
          <td class="qty">Materieelkosten</td>
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

    <h1 class="name">Totalen Factuur</h1>
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
        @if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
        <tr style="page-break-after: always;">
          <td class="qty">BTW bedrag calculatie belast met 21%</td>
          <td class="qty">&nbsp;</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::totalContractingTax1($project)+ResultEndresult::totalSubcontractingTax1($project), 2, ",",".") }}</td>
          <td class="qty">&nbsp;</td>
        </tr>
        <tr style="page-break-after: always;">
          <td class="qty">BTW bedrag calculatie belast met 6%</td>
          <td class="qty">&nbsp;</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::totalContractingTax2($project)+ResultEndresult::totalSubcontractingTax2($project), 2, ",",".") }}</td>
          <td class="qty">&nbsp;</td>
        </tr>
        <tr style="page-break-after: always;">
          <td class="qty">BTW bedrag calculatie belast met 6%</td>
          <td class="qty">&nbsp;</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::totalContractingTax2($project)+ResultEndresult::totalSubcontractingTax2($project), 2, ",",".") }}</td>
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
          @if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
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
          @if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
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
          <h3 class="name">{{ OfferController::getOfferCode($project->id) }}</h3>
          <div class="date">{{ $project->project_name }}</div>
          <div class="date">{{ date("j M Y") }}</div>
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
          @if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
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
          @if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
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


      <?#--INCLUDE TERM START--?>

      <div class="closingtext">{{ ($invoice ? $invoice->closure : '') }}</div>

      <h1 class="name">Bepalingen</h1>
      <div class="statements">
        <li>Deze factuur dient betaald te worden binnen {{ $invoice->payment_condition }} dagen na dagtekening.</li>
      </div>
      <div class="signing">Met vriendelijke groet,</div>
      <div class="signing">{{ Contact::find($invoice->from_contact_id)->firstname ." ". Contact::find($invoice->from_contact_id)->lastname }}</div>
    </main>

    <footer>
      Deze factuur is op de computer gegenereerd en is geldig zonder handtekening.
    </footer>

    <?#--TOTAL END--?>
    @else
    <?#--CONT & SUBCONTR START--?>

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
       @if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
        <tr style="page-break-after: always;">
          <td class="qty">Arbeidskosten</td>
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

        @if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
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

        @if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
        <tr style="page-break-after: always;">
          <td class="qty">Materieelkosten</td>
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
          <td class="qty">Materieelkosten</td>
          <td class="qty">{{ '&euro; '.number_format(EstimateEndresult::conCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(MoreEndresult::conCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax3($project), 2, ",",".") }}</td>
          <td class="qty">0%</td>
          <td class="qty">&nbsp;</td>
        </tr>
        @endif
        <tr style="page-break-after: always;">
          <td class="qty"><strong>Totaal Aanneming </strong></td>
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
       @if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
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

        @if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
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

        @if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
        <tr style="page-break-after: always;">
          <td class="qty">Materieelkosten</td>
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
          <td class="qty">Materieelkosten</td>
          <td class="qty">{{ '&euro; '.number_format(EstimateEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(MoreEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(LessEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::subconEquipmentBalanceTax3($project), 2, ",",".") }}</td>
          <td class="qty">0%</td>
          <td class="qty">&nbsp;</td>
        </tr>
        @endif
        <tr style="page-break-after: always;">
          <td class="qty"><strong>Totaal Onderaanneming </strong></td>
          <td class="qty"><strong>{{ '&euro; '.number_format(EstimateEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
          <td class="qty"><strong>{{ '&euro; '.number_format(MoreEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
          <td class="qty"><strong>{{ '&euro; '.number_format(LessEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
          <td class="qty"><strong>{{ '&euro; '.number_format(ResultEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
          <td class="qty">&nbsp;</td>
          <td class="qty"><strong>{{ '&euro; '.number_format(ResultEndresult::totalSubcontractingTax($project), 2, ",",".") }}</strong></td>
        </tr>
      </tbody>
    </table>
    <h1 class="name">Totalen Factuur</h1>
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
        @if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
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

    <?#--PAGE HEADER SECOND START--?>
    <div style="page-break-after:always;"></div>
    <header class="clearfix">
      <div id="logo">
      <?php if ($relation_self && $relation_self->logo_id) echo "<img src=\"".asset(Resource::find($relation_self->logo_id)->file_location)."\"/>"; ?>
      </div>
        <div id="invoice">
        <h3 class="name">{{ OfferController::getOfferCode($project->id) }}</h3>
        <div class="date">{{ $project->project_name }}</div>
        <div class="date">{{ date("j M Y") }}</div>
      </div>
    </header>
    <?#--PAGE HEADER SECOND END--?>

    @if ($term)
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
        @if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
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

        @if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
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
        @if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
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

        @if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
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

    @endif
     <?#--INCLUDE TERM END--?>

    <div class="closingtext">{{ ($invoice ? $invoice->closure : '') }}</div>

    <h1 class="name">Bepalingen</h1>
    <div class="statements">
      <li>Deze factuur dient betaald te worden binnen {{ $invoice->payment_condition }} dagen na dagtekening.</li>
    </div>
    <div class="signing">Met vriendelijke groet,</div>
    <div class="signing">{{ Contact::find($invoice->from_contact_id)->firstname ." ". Contact::find($invoice->from_contact_id)->lastname }}</div>
  </main>

  <footer>
    Deze factuur is op de computer gegenereerd en is geldig zonder handtekening.
  </footer>

  @endif
  <?#--CON & SUBCONTR END--?>
  @if ($specification)
  <?#--SPECIFICATION START--?>
  @if ($total)
  <?#--TOTAL START--?>

  <?#--PAGE HEADER SECOND START--?>
  <div style="page-break-after:always;"></div>
  <header class="clearfix">
    <div id="logo">
    <?php if ($relation_self && $relation_self->logo_id) echo "<img src=\"".asset(Resource::find($relation_self->logo_id)->file_location)."\"/>"; ?>
    </div>
      <div id="invoice">
      <h3 class="name">{{ OfferController::getOfferCode($project->id) }}</h3>
      <div class="date">{{ $project->project_name }}</div>
      <div class="date">{{ date("j M Y") }}</div>
    </div>
  </header>
  <?#--PAGE HEADER SECOND END--?>

  <?#--CALCULATION TOTAL START --?>
  <h1 class="name">Calculatie per werkzaamheid</h1>
  <table border="0" cellspacing="0" cellpadding="0">
    <thead>
      <tr style="page-break-after: always;">
        <th class="no">Hoofdstuk</th>
        <th class="desc">Werkzaamheid</th>
        <th class="no">Arbeidsuren</th>
        <th class="desc">Arbeid</th>
        <th class="unit">Materiaal</th>
        <th class="qty">Materieel</th>
        <th class="qty">total</th>
      </tr>
    </thead>
    <tbody>
      @foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
      @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->whereNull('detail_id')->get() as $activity)
      <tr><?#-- item --?>
        <td class="no"><strong>{{ $chapter->chapter_name }}</strong></td>
        <td class="desc">{{ $activity->activity_name }}</td>
        <td class="no"><span class="pull-right">{{ number_format(CalculationOverview::laborTotal($activity), 2, ",",".") }}</td>
        <td class="desc"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::laborActivity($project->hour_rate, $activity), 2, ",",".") }}</span></td>
        <td class="unit"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::materialActivityProfit($activity, $project->profit_calc_contr_mat), 2, ",",".") }}</span></td>
        <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::equipmentActivityProfit($activity, $project->profit_calc_contr_equip), 2, ",",".") }}</span></td>
        <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::activityTotalProfit($project->hour_rate, $activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip), 2, ",",".") }} </td>
      </tr>
      @endforeach
      @endforeach
      @foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
      @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->whereNull('detail_id')->get() as $activity)
      <tr>
        <td class="no"><strong>{{ $chapter->chapter_name }}</strong></td>
        <td class="desc">{{ $activity->activity_name }}</td>
        <td class="no"><span class="pull-right">{{ number_format(CalculationOverview::laborTotal($activity), 2, ",",".") }}</td>
        <td class="desc"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::laborActivity($project->hour_rate, $activity), 2, ",",".") }}</span></td>
        <td class="unit"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::materialActivityProfit($activity, $project->profit_calc_subcontr_mat), 2, ",",".") }}</span></td>
        <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::equipmentActivityProfit($activity, $project->profit_calc_subcontr_equip), 2, ",",".") }}</span></td>
        <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::activityTotalProfit($project->hour_rate, $activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip), 2, ",",".") }} </td>
        </td>
      </tr>
      @endforeach
      @endforeach
    </tbody>
  </table>
  <h1 class="name">Totalen per project</h1>
  <table border="0" cellspacing="0" cellpadding="0">
    <thead>
      <tr style="page-break-after: always;">
        <th class="qty">&nbsp;</th>
        <th class="qty">&nbsp;</th>
        <th class="qty">Arbeidsuren</th>
        <th class="qty">Arbeid</th>
        <th class="qty">Materiaal</th>
        <th class="qty">Materieel</th>
        <th class="qty">total</th>
      </tr>
    </thead>
    <tbody>
      <td class="qty">&nbsp;</td>
      <td class="qty">&nbsp;</td>
      <td class="qty"><span class="pull-right">{{ CalculationOverview::laborSuperTotalAmount($project) }}</span></td>
      <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::laborSuperTotal($project), 2, ",",".") }}</span></td>
      <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::materialSuperTotal($project), 2, ",",".") }}</span></td>
      <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::equipmentSuperTotal($project), 2, ",",".") }}</span></td>
      <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::superTotal($project), 2, ",",".") }}</span></td>
    </tbody>
  </table>
  <?#--CALCULATION TOTAL END --?>

  <?#--PAGE HEADER SECOND START--?>
  <div style="page-break-after:always;"></div>
  <header class="clearfix">
    <div id="logo">
    <?php if ($relation_self && $relation_self->logo_id) echo "<img src=\"".asset(Resource::find($relation_self->logo_id)->file_location)."\"/>"; ?>
    </div>
      <div id="invoice">
      <h3 class="name">{{ OfferController::getOfferCode($project->id) }}</h3>
      <div class="date">{{ $project->project_name }}</div>
      <div class="date">{{ date("j M Y") }}</div>
    </div>
  </header>
  <?#--PAGE HEADER SECOND END--?>

  <?#--ESTIMATE TOTAL START --?>
   <h1 class="name">Stelposten per werkzaamheid</h1>
   <table border="0" cellspacing="0" cellpadding="0">
    <thead>
      <tr style="page-break-after: always;">
        <th class="qty">Hoofdstuk</th>
        <th class="qty">Werkzaamheid</th>
        <th class="qty">Arbeidsuren</th>
        <th class="qty">Arbeid</th>
        <th class="qty">Materiaal</th>
        <th class="qty">Materieel</th>
        <th class="qty">total</th>
      </tr>
    </thead>
    <tbody>
      @foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
      @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->get() as $activity)
      <?php
        if (!EstimateOverview::activityTotalProfit($activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip))
          continue;
      ?>
      <tr>
        <td class="qty"><strong>{{ $chapter->chapter_name }}</strong></td>
        <td class="qty">{{ $activity->activity_name }}</td>
        <td class="qty"><span class="pull-right">{{ number_format(EstimateOverview::laborTotal($activity), 2, ",",".") }}</td>
        <td class="qty"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(EstimateOverview::laborActivity($activity), 2, ",",".") }}</span></td>
        <td class="qty"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(EstimateOverview::materialActivityProfit($activity, $project->profit_calc_contr_mat), 2, ",",".") }}</span></td>
        <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::equipmentActivityProfit($activity, $project->profit_calc_contr_equip), 2, ",",".") }}</span></td>
        <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::activityTotalProfit($activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip), 2, ",",".") }} </td>
      </tr>
      @endforeach
      @endforeach
      @foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
      @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->get() as $activity)
      <?php
        if (!EstimateOverview::activityTotalProfit($activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip))
          continue;
      ?>
      <tr>
        <td class="qty"><strong>{{ $chapter->chapter_name }}</strong></td>
        <td class="qty">{{ $activity->activity_name }}</td>
        <td class="qty"><span class="pull-right">{{ number_format(EstimateOverview::laborTotal($activity), 2, ",",".") }}</td>
        <td class="qty"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(EstimateOverview::laborActivity($activity), 2, ",",".") }}</span></td>
        <td class="qty"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(EstimateOverview::materialActivityProfit($activity, $project->profit_calc_subcontr_mat), 2, ",",".") }}</span></td>
        <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::equipmentActivityProfit($activity, $project->profit_calc_subcontr_equip), 2, ",",".") }}</span></td>
        <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::activityTotalProfit($activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip), 2, ",",".") }} </td>
      </tr>
      @endforeach
      @endforeach
    </tbody>
   </table>
   <h1 class="name">Totalen stelposten</h1>
   <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr style="page-break-after: always;">
          <th class="qty">&nbsp;</th>
          <th class="qty">&nbsp;</th>
          <th class="qty">Arbeidsuren</th>
          <th class="qty">Arbeid</th>
          <th class="qty">Materiaal</th>
          <th class="qty">Materieel</th>
          <th class="qty">total</th>
        </tr>
      </thead>
      <tbody>
          <td class="qty">&nbsp;</td>
          <td class="qty">&nbsp;</td>
          <td class="qty"><span class="pull-right">{{ EstimateOverview::laborSuperTotalAmount($project) }}</span></td>
          <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::laborSuperTotal($project), 2, ",",".") }}</span></td>
          <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::materialSuperTotal($project), 2, ",",".") }}</span></td>
          <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::equipmentSuperTotal($project), 2, ",",".") }}</span></td>
          <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::superTotal($project), 2, ",",".") }}</span></td>
      </tbody>
    </table>
  <?#--ESTIMATE TOTAL END--?>

  <?#--PAGE HEADER SECOND START--?>
  <div style="page-break-after:always;"></div>
  <header class="clearfix">
    <div id="logo">
    <?php if ($relation_self && $relation_self->logo_id) echo "<img src=\"".asset(Resource::find($relation_self->logo_id)->file_location)."\"/>"; ?>
    </div>
      <div id="invoice">
      <h3 class="name">{{ OfferController::getOfferCode($project->id) }}</h3>
      <div class="date">{{ $project->project_name }}</div>
      <div class="date">{{ date("j M Y") }}</div>
    </div>
  </header>
  <?#--PAGE HEADER SECOND END--?>

  <?#--LESS TOTAL START--?>
  <h1 class="name">Minderwerk per werkzaamheid</h1>
  <table border="0" cellspacing="0" cellpadding="0">
    <thead>
      <tr style="page-break-after: always;">
        <th class="no">Hoofdstuk</th>
        <th class="desc">Werkzaamheid</th>
        <th class="no">Arbeidsuren</th>
        <th class="desc">Arbeid</th>
        <th class="unit">Materiaal</th>
        <th class="qty">Materieel</th>
        <th class="qty">total</th>
      </tr>
    </thead>
    <tbody>
      @foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
      @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->get() as $activity)
      <?php
        if (!LessOverview::activityTotalProfit($activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip))
          continue;
      ?>
      <tr>
        <td class="qty"><strong>{{ $chapter->chapter_name }}</strong></td>
        <td class="qty">{{ $activity->activity_name }}</td>
        <td class="qty"><span class="pull-right">{{ number_format(LessOverview::laborTotal($activity), 2, ",",".") }}</td>
        <td class="qty"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(LessOverview::laborActivity($activity), 2, ",",".") }}</span></td>
        <td class="qty"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(LessOverview::materialActivityProfit($activity, $project->profit_calc_contr_mat), 2, ",",".") }}</span></td>
        <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::equipmentActivityProfit($activity, $project->profit_calc_contr_equip), 2, ",",".") }}</span></td>
        <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::activityTotalProfit($activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip), 2, ",",".") }} </td>
      </tr>
      @endforeach
      @endforeach
      @foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
      @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->get() as $activity)
      <?php
        if (!LessOverview::activityTotalProfit($activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip))
          continue;
      ?>
      <tr>
        <td class="qty"><strong>{{ $chapter->chapter_name }}</strong></td>
        <td class="qty">{{ $activity->activity_name }}</td>
        <td class="qty"><span class="pull-right">{{ number_format(LessOverview::laborTotal($activity), 2, ",",".") }}</td>
        <td class="qty"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(LessOverview::laborActivity($activity), 2, ",",".") }}</span></td>
        <td class="qty"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(LessOverview::materialActivityProfit($activity, $project->profit_calc_subcontr_mat), 2, ",",".") }}</span></td>
        <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::equipmentActivityProfit($activity, $project->profit_calc_subcontr_equip), 2, ",",".") }}</span></td>
        <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::activityTotalProfit($activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip), 2, ",",".") }} </td>
      </tr>
      @endforeach
      @endforeach
    </tbody>
   </table>
   <h1 class="name">Totalen minderwerk</h1>
   <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr style="page-break-after: always;">
          <th class="no">&nbsp;</th>
          <th class="desc">&nbsp;</th>
          <th class="no">Arbeidsuren</th>
          <th class="desc">Arbeid</th>
          <th class="unit">Materiaal</th>
          <th class="qty">Materieel</th>
          <th class="qty">total</th>
        </tr>
      </thead>
      <tbody>
          <td class="qty">&nbsp;</td>
          <td class="qty">&nbsp;</td>
          <td class="qty"><span class="pull-right">{{ LessOverview::laborSuperTotalAmount($project) }}</span></td>
          <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::laborSuperTotal($project), 2, ",",".") }}</span></td>
          <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::materialSuperTotal($project), 2, ",",".") }}</span></td>
          <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::equipmentSuperTotal($project), 2, ",",".") }}</span></td>
          <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::superTotal($project), 2, ",",".") }}</span></td>
      </tbody>
    </table>
  <?#--LESS TOTAL END--?>

  <?#--PAGE HEADER SECOND START--?>
  <div style="page-break-after:always;"></div>
  <header class="clearfix">
    <div id="logo">
    <?php if ($relation_self && $relation_self->logo_id) echo "<img src=\"".asset(Resource::find($relation_self->logo_id)->file_location)."\"/>"; ?>
    </div>
      <div id="invoice">
      <h3 class="name">{{ OfferController::getOfferCode($project->id) }}</h3>
      <div class="date">{{ $project->project_name }}</div>
      <div class="date">{{ date("j M Y") }}</div>
    </div>
  </header>
  <?#--PAGE HEADER SECOND END--?>

  <?#--MORE TOTAL START--?>
  <h1 class="name">Meerwerk per werkzaamheid</h1>
  <table border="0" cellspacing="0" cellpadding="0">
    <thead>
      <tr style="page-break-after: always;">
        <th class="no">Hoofdstuk</th>
        <th class="desc">Werkzaamheid</th>
        <th class="no">Arbeidsuren</th>
        <th class="desc">Arbeid</th>
        <th class="unit">Materiaal</th>
        <th class="qty">Materieel</th>
        <th class="qty">Totaal</th>
      </tr>
    </thead>
    <tbody>
      @foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
      @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->where('detail_id','=',Detail::where('detail_name','=','more')->first()->id)->get() as $activity)
      <tr>
        <td class="qty"><strong>{{ $chapter->chapter_name }}</strong></td>
        <td class="qty">{{ $activity->activity_name }}</td>
        <td class="qty"><span class="pull-right">{{ number_format(MoreOverview::laborTotal($activity), 2, ",",".") }}</td>
        <td class="qty"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(MoreOverview::laborActivity($activity), 2, ",",".") }}</span></td>
        <td class="qty"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(MoreOverview::materialActivityProfit($activity, $project->profit_more_contr_mat), 2, ",",".") }}</span></td>
        <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::equipmentActivityProfit($activity, $project->profit_more_contr_equip), 2, ",",".") }}</span></td>
        <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::activityTotalProfit($activity, $project->profit_more_contr_mat, $project->profit_more_contr_equip), 2, ",",".") }} </td>
      </tr>
      @endforeach
      @endforeach
      @foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
      @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->where('detail_id','=',Detail::where('detail_name','=','more')->first()->id)->get() as $activity)
      <tr>
        <td class="qty"><strong>{{ $chapter->chapter_name }}</strong></td>
        <td class="qty">{{ $activity->activity_name }}</td>
        <td class="qty"><span class="pull-right">{{ number_format(MoreOverview::laborTotal($activity), 2, ",",".") }}</td>
        <td class="qty"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(MoreOverview::laborActivity($activity), 2, ",",".") }}</span></td>
        <td class="qty"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(MoreOverview::materialActivityProfit($activity, $project->profit_more_subcontr_mat), 2, ",",".") }}</span></td>
        <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::equipmentActivityProfit($activity, $project->profit_more_subcontr_equip), 2, ",",".") }}</span></td>
        <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::activityTotalProfit($activity, $project->profit_more_subcontr_mat, $project->profit_more_subcontr_equip), 2, ",",".") }} </td>
      </tr>
      @endforeach
      @endforeach
    </tbody>
   </table>
   <h1 class="name">Totalen meerwerk</h1>
   <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr style="page-break-after: always;">
          <th class="no">&nbsp;</th>
          <th class="desc">&nbsp;</th>
          <th class="no">Arbeidsuren</th>
          <th class="desc">Arbeid</th>
          <th class="unit">Materiaal</th>
          <th class="qty">Materieel</th>
          <th class="qty">total</th>
        </tr>
      </thead>
      <tbody>
          <td class="qty">&nbsp;</td>
          <td class="qty">&nbsp;</td>
          <td class="qty"><span class="pull-right">{{ MoreOverview::laborSuperTotalAmount($project) }}</span></td>
          <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::laborSuperTotal($project), 2, ",",".") }}</span></td>
          <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::materialSuperTotal($project), 2, ",",".") }}</span></td>
          <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::equipmentSuperTotal($project), 2, ",",".") }}</span></td>
          <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::superTotal($project), 2, ",",".") }}</span></td>
      </tbody>
    </table>
  <?#--MORE TOTAL END--?>

  <?#--TOTAL END--?>
  @else
  <?#--CONT & SUBCONT START--?>

  <?#--PAGE HEADER SECOND START--?>
  <div style="page-break-after:always;"></div>
  <header class="clearfix">
    <div id="logo">
    <?php if ($relation_self && $relation_self->logo_id) echo "<img src=\"".asset(Resource::find($relation_self->logo_id)->file_location)."\"/>"; ?>
    </div>
      <div id="invoice">
      <h3 class="name">{{ OfferController::getOfferCode($project->id) }}</h3>
      <div class="date">{{ $project->project_name }}</div>
      <div class="date">{{ date("j M Y") }}</div>
    </div>
  </header>
  <?#--PAGE HEADER SECOND END--?>

  <?#--CALCULATION CONT & SUBCONT START--?>
  <h1 class="name">Totalen voor calculatie</h1>
  <h2 class="name">Aanneming</h2>
  <table border="0" cellspacing="0" cellpadding="0">
    <thead>
      <tr style="page-break-after: always;">
          <th style="width: 181px" class="qty">Hoofdstuk</th>
          <th style="width: 170px" class="qty">Werkzaamheid</th>
          <th style="width: 40px" class="qty">@if (!$onlyactivity) Arbeidsuren @endif</th>
          <th style="width: 51px" class="qty">@if (!$onlyactivity) Arbeid @endif</th>
          <th style="width: 51px" class="qty">@if (!$onlyactivity) Materiaal @endif</th>
          <th style="width: 51px" class="qty">@if (!$onlyactivity) Materieel @endif</th>
          <th style="width: 51px" class="qty">Totaal</th>
      </tr>
    </thead>
    <tbody>
      @foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
      @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->where('part_type_id','=',PartType::where('type_name','=','calculation')->first()->id)->whereNull('detail_id')->get() as $activity)
      <tr>
        <td class="qty"><strong>{{ $chapter->chapter_name }}</strong></td>
        <td class="qty">{{ $activity->activity_name }}</td>
        <td class="qty"><span class="pull-right">@if (!$onlyactivity)  {{ number_format(CalculationOverview::laborTotal($activity), 2, ",",".") }} @endif</td>
        <td class="qty"><span class="pull-right total-ex-tax">@if (!$onlyactivity)  {{ '&euro; '.number_format(CalculationOverview::laborActivity($project->hour_rate, $activity), 2, ",",".") }} @endif</span></td>
        <td class="qty"><span class="pull-right total-ex-tax">@if (!$onlyactivity)  {{ '&euro; '.number_format(CalculationOverview::materialActivityProfit($activity, $project->profit_calc_contr_mat), 2, ",",".") }} @endif</span></td>
        <td class="qty"><span class="pull-right">@if (!$onlyactivity) {{ '&euro; '.number_format(CalculationOverview::equipmentActivityProfit($activity, $project->profit_calc_contr_equip), 2, ",",".") }} @endif</span></td>
        <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::activityTotalProfit($project->hour_rate, $activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip), 2, ",",".") }}</td>
      </tr>
      @endforeach
      @endforeach
      <tr style="page-break-after: always;">
        <th class="qty"><strong>Totaal aanneming</strong></th>
        <th class="qty">&nbsp;</th>
        <td class="qty"><strong><span class="pull-right">@if (!$onlyactivity) {{ CalculationOverview::contrLaborTotalAmount($project) }} @endif</span></strong></td>
        <td class="qty"><strong><span class="pull-right">@if (!$onlyactivity) {{ '&euro; '.number_format(CalculationOverview::contrLaborTotal($project), 2, ",",".") }} @endif</span></strong></td>
        <td class="qty"><strong><span class="pull-right">@if (!$onlyactivity) {{ '&euro; '.number_format(CalculationOverview::contrMaterialTotal($project), 2, ",",".") }} @endif</span></strong></td>
        <td class="qty"><strong><span class="pull-right">@if (!$onlyactivity) {{ '&euro; '.number_format(CalculationOverview::contrEquipmentTotal($project), 2, ",",".") }} @endif</span></strong></td>
        <td class="qty"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::contrTotal($project), 2, ",",".") }}</span></strong></td>
      </tr>
    </table>
   <h2 class="name">Onderaanneming</h2>
   <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr style="page-break-after: always;">
          <th style="width: 181px" class="qty">Hoofdstuk</th>
          <th style="width: 170px" class="qty">Werkzaamheid</th>
          <th style="width: 40px" class="qty">@if (!$onlyactivity) Arbeidsuren @endif</th>
          <th style="width: 51px" class="qty">@if (!$onlyactivity) Arbeid @endif</th>
          <th style="width: 51px" class="qty">@if (!$onlyactivity) Materiaal @endif</th>
          <th style="width: 51px" class="qty">@if (!$onlyactivity) Materieel @endif</th>
          <th style="width: 51px" class="qty">Totaal</th>
        </tr>
      </thead>
      <tbody>
        @foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
        @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->where('part_type_id','=',PartType::where('type_name','=','calculation')->first()->id)->whereNull('detail_id')->get() as $activity)
        <tr>
          <td class="qty"><strong>{{ $chapter->chapter_name }}</strong></td>
          <td class="qty">{{ $activity->activity_name }}</td>
          <td class="qty"><span class="pull-right">@if (!$onlyactivity) {{ number_format(CalculationOverview::laborTotal($activity), 2, ",",".") }} @endif</td>
          <td class="qty"><span class="pull-right total-ex-tax">@if (!$onlyactivity) {{ '&euro; '.number_format(CalculationOverview::laborActivity($project->hour_rate, $activity), 2, ",",".") }} @endif</span></td>
          <td class="qty"><span class="pull-right total-ex-tax">@if (!$onlyactivity) {{ '&euro; '.number_format(CalculationOverview::materialActivityProfit($activity, $project->profit_calc_subcontr_mat), 2, ",",".") }} @endif</span></td>
          <td class="qty"><span class="pull-right">@if (!$onlyactivity) {{ '&euro; '.number_format(CalculationOverview::equipmentActivityProfit($activity, $project->profit_calc_subcontr_equip), 2, ",",".") }} @endif</span></td>
          <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::activityTotalProfit($project->hour_rate, $activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip), 2, ",",".") }} </td>
        </tr>
        @endforeach
        @endforeach
         <tr style="page-break-after: always;">
          <th class="qty"><strong>Totaal onderaanneming</strong></th>
          <th class="qty">&nbsp;</th>
          <td class="qty"><strong><span class="pull-right">@if (!$onlyactivity) {{ CalculationOverview::subcontrLaborTotalAmount($project) }} @endif</span></strong></td>
          <td class="qty"><strong><span class="pull-right">@if (!$onlyactivity) {{ '&euro; '.number_format(CalculationOverview::subcontrLaborTotal($project), 2, ",",".") }} @endif</span></strong></td>
          <td class="qty"><strong><span class="pull-right">@if (!$onlyactivity) {{ '&euro; '.number_format(CalculationOverview::subcontrMaterialTotal($project), 2, ",",".") }} @endif</span></strong></td>
          <td class="qty"><strong><span class="pull-right">@if (!$onlyactivity) {{ '&euro; '.number_format(CalculationOverview::subcontrEquipmentTotal($project), 2, ",",".") }} @endif</span></strong></td>
          <td class="qty"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::subcontrTotal($project), 2, ",",".") }}</span></strong></td>
        </tr>
      </tbody>
   </table>
   <h1 class="name">Totalen voor calculatie</h1>
   <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr style="page-break-after: always;">
          <th style="width: 181px" class="qty">&nbsp;</th>
          <th style="width: 170px" class="qty">&nbsp;</th>
          <th style="width: 40px" class="qty">@if (!$onlyactivity) Arbeidsuren @endif</th>
          <th style="width: 51px" class="qty">@if (!$onlyactivity) Arbeid @endif</th>
          <th style="width: 51px" class="qty">@if (!$onlyactivity) Materiaal @endif</th>
          <th style="width: 51px" class="qty">@if (!$onlyactivity) Materieel @endif</th>
          <th style="width: 51px" class="qty">Totaal</th>
        </tr>
      </thead>
      <tbody>
        <tr style="page-break-after: always;">
          <td class="qty">&nbsp;</td>
          <td class="qty">&nbsp;</td>
          <td class="qty"><span class="pull-right">@if (!$onlyactivity) {{ CalculationOverview::laborSuperTotalAmount($project) }} @endif</span></td>
          <td class="qty"><span class="pull-right">@if (!$onlyactivity) {{ '&euro; '.number_format(CalculationOverview::laborSuperTotal($project), 2, ",",".") }} @endif</span></td>
          <td class="qty"><span class="pull-right">@if (!$onlyactivity) {{ '&euro; '.number_format(CalculationOverview::materialSuperTotal($project), 2, ",",".") }} @endif</span></td>
          <td class="qty"><span class="pull-right">@if (!$onlyactivity) {{ '&euro; '.number_format(CalculationOverview::equipmentSuperTotal($project), 2, ",",".") }} @endif</span></td>
          <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::superTotal($project), 2, ",",".") }}</span></td>
        </tr>
    </table>
    <?#--CALCULATION CONT & SUBCONT END--?>

    <?#--PAGE HEADER SECOND START--?>
    <div style="page-break-after:always;"></div>
    <header class="clearfix">
      <div id="logo">
      <?php if ($relation_self && $relation_self->logo_id) echo "<img src=\"".asset(Resource::find($relation_self->logo_id)->file_location)."\"/>"; ?>
      </div>
        <div id="invoice">
        <h3 class="name">{{ OfferController::getOfferCode($project->id) }}</h3>
        <div class="date">{{ $project->project_name }}</div>
        <div class="date">{{ date("j M Y") }}</div>
      </div>
    </header>
    <?#--PAGE HEADER SECOND END--?>

    <?#--ESTIMATE CONT & SUBCOINT START--?>
    <h1 class="name">Totalen voor stelposten</h1>
    <h2 class="name">Aanneming</h2>
    <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr style="page-break-after: always;">
          <th class="no">Hoofdstuk</th>
          <th class="desc">Werkzaamheid</th>
          <th class="no">Arbeidsuren</th>
          <th class="desc">Arbeid</th>
          <th class="unit">Materiaal</th>
          <th class="qty">Materieel</th>
          <th class="qty">total</th>
        </tr>
      </thead>
      <tbody>
        @foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
        @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->get() as $activity)
        <?php
          if (!EstimateOverview::activityTotalProfit($activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip))
            continue;
        ?>
        <tr>
          <td class="qty"><strong>{{ $chapter->chapter_name }}</strong></td>
          <td class="qty">{{ $activity->activity_name }}</td>
          <td class="qty"><span class="pull-right">{{ number_format(EstimateOverview::laborTotal($activity), 2, ",",".") }}</td>
          <td class="qty"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(EstimateOverview::laborActivity($activity), 2, ",",".") }}</span></td>
          <td class="qty"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(EstimateOverview::materialActivityProfit($activity, $project->profit_calc_contr_mat), 2, ",",".") }}</span></td>
          <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::equipmentActivityProfit($activity, $project->profit_calc_contr_equip), 2, ",",".") }}</span></td>
          <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::activityTotalProfit($activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip), 2, ",",".") }} </td>
        </tr>
        @endforeach
        @endforeach
        <tr style="page-break-after: always;">
          <th class="qty"><strong>Totaal aanneming</strong></th>
          <th class="qty">&nbsp;</th>
          <td class="qty"><strong><span class="pull-right">{{ EstimateOverview::contrLaborTotalAmount($project) }}</span></strong></td>
          <td class="qty"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::contrLaborTotal($project), 2, ",",".") }}</span></strong></td>
          <td class="qty"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::contrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
          <td class="qty"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::contrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
          <td class="qty"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::contrTotal($project), 2, ",",".") }}</span></strong></td>
        </tr>
      </tbody>
    </table>
   <h2 class="name">Onderaanneming</h2>
   <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr style="page-break-after: always;">
          <th class="no">Hoofdstuk</th>
          <th class="desc">Werkzaamheid</th>
          <th class="no">Arbeidsuren</th>
          <th class="desc">Arbeid</th>
          <th class="unit">Materiaal</th>
          <th class="qty">Materieel</th>
          <th class="qty">total</th>
        </tr>
      </thead>
      <tbody>
        @foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
        @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->get() as $activity)
        <?php
          if (!EstimateOverview::activityTotalProfit($activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip))
            continue;
        ?>
        <tr>
          <td class="qty"><strong>{{ $chapter->chapter_name }}</strong></td>
          <td class="qty">{{ $activity->activity_name }}</td>
          <td class="qty"><span class="pull-right">{{ number_format(EstimateOverview::laborTotal($activity), 2, ",",".") }}</td>
          <td class="qty"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(EstimateOverview::laborActivity($activity), 2, ",",".") }}</span></td>
          <td class="qty"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(EstimateOverview::materialActivityProfit($activity, $project->profit_calc_subcontr_mat), 2, ",",".") }}</span></td>
          <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::equipmentActivityProfit($activity, $project->profit_calc_subcontr_equip), 2, ",",".") }}</span></td>
          <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::activityTotalProfit($activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip), 2, ",",".") }} </td>
        </tr>
        @endforeach
        @endforeach
          <th class="qty"><strong>Totaal onderaanneming</strong></th>
          <th class="qty">&nbsp;</th>
          <td class="qty"><strong><span class="pull-right">{{ EstimateOverview::subcontrLaborTotalAmount($project) }}</span></strong></td>
          <td class="qty"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::subcontrLaborTotal($project), 2, ",",".") }}</span></strong></td>
          <td class="qty"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::subcontrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
          <td class="qty"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::subcontrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
          <td class="qty"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::subcontrTotal($project), 2, ",",".") }}</span></strong></td>
        </tr>
      </tbody>
   </table>
   <h1 class="name">Totalen voor stelposten</h1>
   <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr style="page-break-after: always;">
          <th class="no">&nbsp;</th>
          <th class="desc">&nbsp;</th>
          <th class="no">Arbeidsuren</th>
          <th class="desc">Arbeid</th>
          <th class="unit">Materiaal</th>
          <th class="qty">Materieel</th>
          <th class="qty">total</th>
        </tr>
      </thead>
      <tbody>
        <tr style="page-break-after: always;">
          <td class="qty">&nbsp;</td>
          <td class="qty">&nbsp;</td>
          <td class="qty"><span class="pull-right">{{ EstimateOverview::laborSuperTotalAmount($project) }}</span></td>
          <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::laborSuperTotal($project), 2, ",",".") }}</span></td>
          <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::materialSuperTotal($project), 2, ",",".") }}</span></td>
          <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::equipmentSuperTotal($project), 2, ",",".") }}</span></td>
          <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::superTotal($project), 2, ",",".") }}</span></td>
        </tr>
    </table>
    <?#--ESTIMATE CONT & SUBCOINT END--?>

    <?#--PAGE HEADER SECOND START--?>
    <div style="page-break-after:always;"></div>
    <header class="clearfix">
      <div id="logo">
      <?php if ($relation_self && $relation_self->logo_id) echo "<img src=\"".asset(Resource::find($relation_self->logo_id)->file_location)."\"/>"; ?>
      </div>
        <div id="invoice">
        <h3 class="name">{{ OfferController::getOfferCode($project->id) }}</h3>
        <div class="date">{{ $project->project_name }}</div>
        <div class="date">{{ date("j M Y") }}</div>
      </div>
    </header>
    <?#--PAGE HEADER SECOND END--?>

    <?#--LESS CONT & SUBCOINT START--?>
    <h1 class="name">Totalen minderwerk</h1>
    <h2 class="name">Aanneming</h2>
    <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr style="page-break-after: always;">
          <th class="no">Hoofdstuk</th>
          <th class="desc">Werkzaamheid</th>
          <th class="no">Arbeidsuren</th>
          <th class="desc">Arbeid</th>
          <th class="unit">Materiaal</th>
          <th class="qty">Materieel</th>
          <th class="qty">total</th>
        </tr>
      </thead>
      <tbody>
        @foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
        @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->get() as $activity)
        <?php
          if (!LessOverview::activityTotalProfit($activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip))
            continue;
        ?>
        <tr>
          <td class="qty"><strong>{{ $chapter->chapter_name }}</strong></td>
          <td class="qty">{{ $activity->activity_name }}</td>
          <td class="qty"><span class="pull-right">{{ number_format(LessOverview::laborTotal($activity), 2, ",",".") }}</td>
          <td class="qty"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(LessOverview::laborActivity($activity), 2, ",",".") }}</span></td>
          <td class="qty"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(LessOverview::materialActivityProfit($activity, $project->profit_calc_contr_mat), 2, ",",".") }}</span></td>
          <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::equipmentActivityProfit($activity, $project->profit_calc_contr_equip), 2, ",",".") }}</span></td>
          <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::activityTotalProfit($activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip), 2, ",",".") }} </td>
        </tr>
        @endforeach
        @endforeach
        <tr style="page-break-after: always;">
          <td class="qty"><strong>Totaal aanneming</strong></td>
          <td class="qty">&nbsp;</td>
          <td class="qty"><strong><span class="pull-right">{{ LessOverview::contrLaborTotalAmount($project) }}</span></strong></td>
          <td class="qty"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::contrLaborTotal($project), 2, ",",".") }}</span></strong></td>
          <td class="qty"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::contrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
          <td class="qty"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::contrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
          <td class="qty"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::contrTotal($project), 2, ",",".") }}</span></strong></td>
        </tr>
      </tbody>
   </table>
   <h2 class="name">Onderaanneming</h2>
   <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr style="page-break-after: always;">
          <th class="no">Hoofdstuk</th>
          <th class="desc">Werkzaamheid</th>
          <th class="no">Arbeidsuren</th>
          <th class="desc">Arbeid</th>
          <th class="unit">Materiaal</th>
          <th class="qty">Materieel</th>
          <th class="qty">total</th>
        </tr>
      </thead>
      <tbody>
        @foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
        @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->get() as $activity)
        <?php
          if (!LessOverview::activityTotalProfit($activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip))
            continue;
        ?>
        <tr>
          <td class="qty"><strong>{{ $chapter->chapter_name }}</strong></td>
          <td class="qty">{{ $activity->activity_name }}</td>
          <td class="qty"><span class="pull-right">{{ number_format(LessOverview::laborTotal($activity), 2, ",",".") }}</td>
          <td class="qty"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(LessOverview::laborActivity($activity), 2, ",",".") }}</span></td>
          <td class="qty"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(LessOverview::materialActivityProfit($activity, $project->profit_calc_subcontr_mat), 2, ",",".") }}</span></td>
          <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::equipmentActivityProfit($activity, $project->profit_calc_subcontr_equip), 2, ",",".") }}</span></td>
          <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::activityTotalProfit($activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip), 2, ",",".") }} </td>
        </tr>
        @endforeach
        @endforeach
          <td class="qty"><strong>Totaal onderaanneming</strong></td>
          <td class="qty">&nbsp;</td>
          <td class="qty"><strong><span class="pull-right">{{ LessOverview::subcontrLaborTotalAmount($project) }}</span></strong></td>
          <td class="qty"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::subcontrLaborTotal($project), 2, ",",".") }}</span></strong></td>
          <td class="qty"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::subcontrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
          <td class="qty"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::subcontrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
          <td class="qty"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::subcontrTotal($project), 2, ",",".") }}</span></strong></td>
        </tr>
      </tbody>
   </table>
   <h1 class="name">Totalen voor minderwerk</h1>
   <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr style="page-break-after: always;">
          <th class="no">&nbsp;</th>
          <th class="desc">&nbsp;</th>
          <th class="no">Arbeidsuren</th>
          <th class="desc">Arbeid</th>
          <th class="unit">Materiaal</th>
          <th class="qty">Materieel</th>
          <th class="qty">total</th>
        </tr>
      </thead>
      <tbody>
        <tr style="page-break-after: always;">
          <td class="qty">&nbsp;</td>
          <td class="qty">&nbsp;</td>
          <td class="qty"><span class="pull-right">{{ LessOverview::laborSuperTotalAmount($project) }}</span></td>
          <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::laborSuperTotal($project), 2, ",",".") }}</span></td>
          <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::materialSuperTotal($project), 2, ",",".") }}</span></td>
          <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::equipmentSuperTotal($project), 2, ",",".") }}</span></td>
          <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::superTotal($project), 2, ",",".") }}</span></td>
        </tr>
    </table>
    <?#--LESS CONT & SUBCOINT END--?>

    <?#--PAGE HEADER SECOND START--?>
    <div style="page-break-after:always;"></div>
    <header class="clearfix">
      <div id="logo">
      <?php if ($relation_self && $relation_self->logo_id) echo "<img src=\"".asset(Resource::find($relation_self->logo_id)->file_location)."\"/>"; ?>
      </div>
        <div id="invoice">
        <h3 class="name">{{ OfferController::getOfferCode($project->id) }}</h3>
        <div class="date">{{ $project->project_name }}</div>
        <div class="date">{{ date("j M Y") }}</div>
      </div>
    </header>
    <?#--PAGE HEADER SECOND END--?>

    <?#--MORE CONT & SUBCOINT START--?>
    <h1 class="name">Totalen meerwerk</h1>
    <h2 class="name">Aanneming</h2>
    <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr style="page-break-after: always;">
          <th class="no">Hoofdstuk</th>
          <th class="desc">Werkzaamheid</th>
          <th class="no">Arbeidsuren</th>
          <th class="desc">Arbeid</th>
          <th class="unit">Materiaal</th>
          <th class="qty">Materieel</th>
          <th class="qty">total</th>
        </tr>
      </thead>
      <tbody>
        @foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
        @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->where('detail_id','=',Detail::where('detail_name','=','more')->first()->id)->get() as $activity)
        <tr>
          <td class="qty"><strong>{{ $chapter->chapter_name }}</strong></td>
          <td class="qty">{{ $activity->activity_name }}</td>
          <td class="qty"><span class="pull-right">{{ number_format(MoreOverview::laborTotal($activity), 2, ",",".") }}</td>
          <td class="qty"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(MoreOverview::laborActivity($activity), 2, ",",".") }}</span></td>
          <td class="qty"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(MoreOverview::materialActivityProfit($activity, $project->profit_more_contr_mat), 2, ",",".") }}</span></td>
          <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::equipmentActivityProfit($activity, $project->profit_more_contr_equip), 2, ",",".") }}</span></td>
          <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::activityTotalProfit($activity, $project->profit_more_contr_mat, $project->profit_more_contr_equip), 2, ",",".") }} </td>
        </tr>
        @endforeach
        @endforeach
        <tr style="page-break-after: always;">
          <th class="qty"><strong>Totaal aanneming</strong></th>
          <th class="qty">&nbsp;</th>
          <td class="qty"><strong><span class="pull-right">{{ MoreOverview::contrLaborTotalAmount($project) }}</span></strong></td>
          <td class="qty"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::contrLaborTotal($project), 2, ",",".") }}</span></strong></td>
          <td class="qty"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::contrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
          <td class="qty"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::contrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
          <td class="qty"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::contrTotal($project), 2, ",",".") }}</span></strong></td>
        </tr>
      </tbody>
   </table>
   <h2 class="name">Onderaanneming</h2>
   <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr style="page-break-after: always;">
          <th class="no">Hoofdstuk</th>
          <th class="desc">Werkzaamheid</th>
          <th class="no">Arbeidsuren</th>
          <th class="desc">Arbeid</th>
          <th class="unit">Materiaal</th>
          <th class="qty">Materieel</th>
          <th class="qty">total</th>
        </tr>
      </thead>
      <tbody>
        @foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
        @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->where('detail_id','=',Detail::where('detail_name','=','more')->first()->id)->get() as $activity)
        <tr>
          <td class="qty"><strong>{{ $chapter->chapter_name }}</strong></td>
          <td class="qty">{{ $activity->activity_name }}</td>
          <td class="qty"><span class="pull-right">{{ number_format(MoreOverview::laborTotal($activity), 2, ",",".") }}</td>
          <td class="qty"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(MoreOverview::laborActivity($activity), 2, ",",".") }}</span></td>
          <td class="qty"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(MoreOverview::materialActivityProfit($activity, $project->profit_more_subcontr_mat), 2, ",",".") }}</span></td>
          <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::equipmentActivityProfit($activity, $project->profit_more_subcontr_equip), 2, ",",".") }}</span></td>
          <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::activityTotalProfit($activity, $project->profit_more_subcontr_mat, $project->profit_more_subcontr_equip), 2, ",",".") }} </td>
        </tr>
        @endforeach
        @endforeach
          <td class="qty"><strong>Totaal onderaanneming</strong></td>
          <td class="qty">&nbsp;</td>
          <td class="qty"><strong><span class="pull-right">{{ MoreOverview::subcontrLaborTotalAmount($project) }}</span></strong></td>
          <td class="qty"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::subcontrLaborTotal($project), 2, ",",".") }}</span></strong></td>
          <td class="qty"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::subcontrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
          <td class="qty"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::subcontrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
          <td class="qty"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::subcontrTotal($project), 2, ",",".") }}</span></strong></td>
        </tr>
      </tbody>
   </table>
   <h1 class="name">Totalen voor meerwerk</h1>
   <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr style="page-break-after: always;">
          <th class="no">&nbsp;</th>
          <th class="desc">&nbsp;</th>
          <th class="no">Arbeidsuren</th>
          <th class="desc">Arbeid</th>
          <th class="unit">Materiaal</th>
          <th class="qty">Materieel</th>
          <th class="qty">total</th>
        </tr>
      </thead>
      <tbody>
        <tr style="page-break-after: always;">
          <td class="qty">&nbsp;</td>
          <td class="qty">&nbsp;</td>
          <td class="qty"><span class="pull-right">{{ MoreOverview::laborSuperTotalAmount($project) }}</span></td>
          <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::laborSuperTotal($project), 2, ",",".") }}</span></td>
          <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::materialSuperTotal($project), 2, ",",".") }}</span></td>
          <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::equipmentSuperTotal($project), 2, ",",".") }}</span></td>
          <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::superTotal($project), 2, ",",".") }}</span></td>
        </tr>
    </table>
    <?#--LESS CONT & SUBCOINT END--?>
    @endif
    @endif
    <?#--TOTAL END--?>
    <?#--SPECIFICATION END--?>

    @if ($description)
    <?#--DESCRIPTION START--?>
    @if ($total)
    <?#--TOTAL START--?>

    <?#--PAGE HEADER SECOND START--?>
    <div style="page-break-after:always;"></div>
    <header class="clearfix">
      <div id="logo">
      <?php if ($relation_self && $relation_self->logo_id) echo "<img src=\"".asset(Resource::find($relation_self->logo_id)->file_location)."\"/>"; ?>
      </div>
        <div id="invoice">
        <h3 class="name">{{ OfferController::getOfferCode($project->id) }}</h3>
        <div class="date">{{ $project->project_name }}</div>
        <div class="date">{{ date("j M Y") }}</div>
      </div>
    </header>
    <?#--PAGE HEADER SECOND END--?>

    <h1 class="name">Omschrijving werkzaamheden</h1>
    <table border="0" cellspacing="0" cellpadding="0">
    <thead>
      <tr style="page-break-after: always;">
        <th class="no">Hoofdstuk</th>
        <th class="desc">Werkzaamheid</th>
        <th class="no">Omschrijving</th>
      </tr>
    </thead>
    <tbody>
      @foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
      @foreach (Activity::where('chapter_id','=', $chapter->id)->get() as $activity)
      <tr>
        <td class="col-md-2"><strong>{{ $chapter->chapter_name }}</strong></td>
        <td class="col-md-3">{{ $activity->activity_name }}</td>
        <td class="col-md-7"><span>{{ $activity->note }}</td>
      </tr>
      @endforeach
      @endforeach
    </tbody>
  </table>
  <?#--TOTAL END--?>

  @else

  <?#--PAGE HEADER SECOND START--?>
  <div style="page-break-after:always;"></div>
  <header class="clearfix">
    <div id="logo">
    <?php if ($relation_self && $relation_self->logo_id) echo "<img src=\"".asset(Resource::find($relation_self->logo_id)->file_location)."\"/>"; ?>
    </div>
      <div id="invoice">
      <h3 class="name">{{ OfferController::getOfferCode($project->id) }}</h3>
      <div class="date">{{ $project->project_name }}</div>
      <div class="date">{{ date("j M Y") }}</div>
    </div>
  </header>
  <?#--PAGE HEADER SECOND END--?>

  <?#--CONT & SUBCOINT START--?>
  <h1 class="name">Omschrijving werkzaamheden</h1>
  <h2 class="name">Aanneming</h2>
  <table border="0" cellspacing="0" cellpadding="0">
    <thead>
      <tr style="page-break-after: always;">
        <th class="no">Hoofdstuk</th>
        <th class="desc">Werkzaamheid</th>
        <th class="no">Omschrijving</th>
      </tr>
    </thead>
    <tbody>
      @foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
      @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->get() as $activity)
      <tr>
        <td class="col-md-2"><strong>{{ $chapter->chapter_name }}</strong></td>
        <td class="col-md-3">{{ $activity->activity_name }}</td>
        <td class="col-md-7"><span>{{ $activity->note }}</td>
      </tr>
      @endforeach
      @endforeach
    </tbody>
  </table>
   <h2 class="name">Onderaanneming</h2>
  <table border="0" cellspacing="0" cellpadding="0">
    <thead>
      <tr style="page-break-after: always;">
        <th class="no">Hoofdstuk</th>
        <th class="desc">Werkzaamheid</th>
        <th class="no">Omschrijving</th>
      </tr>
    </thead>
    <tbody>
      @foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
      @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->get() as $activity)
      <tr>
        <td class="col-md-2"><strong>{{ $chapter->chapter_name }}</strong></td>
        <td class="col-md-3">{{ $activity->activity_name }}</td>
        <td class="col-md-7"><span>{{ $activity->note }}</td>
      </tr>
      @endforeach
      @endforeach
    </tbody>
  </table>
  @endif
  <?#--CONT & SUBCOINT END--?>

  @endif
  <?#--DESCRIPTION END--?>

  </body>
</html>
