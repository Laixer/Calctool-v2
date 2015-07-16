<?php
$total=Input::get("total");
$specification=Input::get("specification");
$description=Input::get("description");
$c=false;

$project = Project::find(Route::Input('project_id'));
$relation = Relation::find($project->client_id);
$relation_self = Relation::find(Auth::user()->self_id);
if ($relation_self)

$contact_self = Contact::where('relation_id','=',$relation_self->id);
$offer_last = Offer::where('project_id','=',$project->id)->orderBy('created_at', 'desc')->first();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Example 2</title>
    <link rel="stylesheet" href="{{ asset('css/pdf.css') }}" media="all" />
  </head>
  <body>
    <header class="clearfix">
      <div id="logo">
      <?php if ($relation_self && $relation_self->logo_id) echo "<img src=\"".asset(Resource::find($relation_self->logo_id)->file_location)."\"/>"; ?>
      </div>
      <div id="company">
        <h3 class="name">{{ $relation_self->company_name }}</h3>
        <div>{{ $relation_self->address_street . ' ' . $relation_self->address_number }}</div>
        <div>{{ $relation_self->address_postal . ', ' . $relation_self->address_city }}</div>
        <div>Email:<a href="mailto:{{ $relation_self->email }}">{{ $relation_self->email }}</a></div>
        <div>KVK:{{ $relation_self->kvk }}</li>
    </header>
    <main>
      <div id="details" class="clearfix">
        <div id="client">
          <div>{{ $relation->company_name }}</div>
          <div>{{ Contact::find($offer_last->to_contact_id)->firstname ." ". Contact::find($offer_last->to_contact_id)->lastname }}</div>
          <div>{{ $relation->address_street . ' ' . $relation->address_number }}</div>
          <div>{{ $relation->address_postal . ', ' . $relation->address_city }}</div>
        </div>
        <div id="invoice">
          <h3 class="name">{{ OfferController::getOfferCode($project->id) }}</h3>
          <div class="date">{{ $project->project_name }}</div>
          <div class="date">{{ date("j M Y") }}</div>
        </div>
      </div>

      <div class="openingtext">Geachte</div>
      <div class="openingtext">{{ ($offer_last ? $offer_last->description : '') }}</div>

      <h1 class="name">Totaalkosten project</h1>
      @if ($total)
      <table border="0" cellspacing="0" cellpadding="0">
        <thead>
          <tr style="page-break-after: always;">
            <th class="no">&nbsp;</th>
            <th class="desc">Uren</th>
            <th class="unit">Bedrag (excl. BTW)</th>
            <th class="qty">BTW %</th>
            <th class="qty">BTW bedrag</th>
          </tr>
        </thead>
        <tbody>
          <tr style="page-break-after: always;">
            <td class="no"><strong>Arbeidskosten</strong></td>
            <td class="desc">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax1($project)+CalculationEndresult::subconCalcLaborActivityTax1($project), 2, ",",".") }}</td>
            <td class="unit">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax1Amount($project)+CalculationEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
            <td class="qty">21 %</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax1AmountTax($project)+CalculationEndresult::subconCalcLaborActivityTax1AmountTax($project), 2, ",",".") }}</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no">&nbsp;</td>
            <td class="desc">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax2($project)+CalculationEndresult::subconCalcLaborActivityTax2($project), 2, ",",".") }}</td>
            <td class="unit">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax2Amount($project)+CalculationEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
            <td class="qty">6 %</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax2AmountTax($project)+CalculationEndresult::subconCalcLaborActivityTax2AmountTax($project), 2, ",",".") }}</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no">&nbsp;</td>
            <td class="desc">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax3($project)+CalculationEndresult::subconCalcLaborActivityTax3($project), 2, ",",".") }}</td>
            <td class="unit">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax3Amount($project)+CalculationEndresult::conCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="qty">0 %</td>
            <td class="qty">&nbsp;</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no"><strong>Materiaalkosten</strong></td>
            <td class="desc">&nbsp;</td>
            <td class="unit">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax1Amount($project)+CalculationEndresult::conCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
            <td class="qty">21 %</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax1AmountTax($project)+CalculationEndresult::subconCalcMaterialActivityTax1AmountTax($project), 2, ",",".") }}</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no">&nbsp;</td>
            <td class="desc">&nbsp;</td>
            <td class="unit">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax2Amount($project)+CalculationEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
            <td class="qty">6 %</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax2AmountTax($project)+CalculationEndresult::subconCalcMaterialActivityTax2AmountTax($project), 2, ",",".") }}</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no">&nbsp;</td>
            <td class="desc">&nbsp;</td>
            <td class="unit">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax3Amount($project)+CalculationEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="qty">0 %</td>
            <td class="qty">&nbsp;</td>
          </tr>

          <tr style="page-break-after: always;">
            <td class="no"><strong>Materieelkosten</strong></td>
            <td class="desc">&nbsp;</td>
            <td class="unit">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax1Amount($project)+CalculationEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
            <td class="qty">21 %</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax1AmountTax($project)+CalculationEndresult::subconCalcEquipmentActivityTax1AmountTax($project), 2, ",",".") }}</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no">&nbsp;</td>
            <td class="desc">&nbsp;</td>
            <td class="unit">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax2Amount($project)+CalculationEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
            <td class="qty">6 %</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax2AmountTax($project)+CalculationEndresult::subconCalcEquipmentActivityTax2AmountTax($project), 2, ",",".") }}</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no">&nbsp;</td>
            <td class="desc">&nbsp;</td>
            <td class="unit">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax3Amount($project)+CalculationEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="qty">0 %</td>
            <td class="qty">&nbsp;</td>
          </tr>
        </tbody>
      </table>

      <h1 class="name">Cumulatieven offerte</h1>
      <table border="0" cellspacing="0" cellpadding="0">
        <thead>
          <tr style="page-break-after: always;">
            <th class="no">&nbsp;</th>
            <th class="unit">Bedrag (excl. BTW)</th>
            <th class="qty">BTW bedrag</th>
            <th class="total">Bedrag (incl. BTW)</th>
          </tr>
        </thead>
        <tbody>
          <tr style="page-break-after: always;">
            <td class="no"><strong>Calculatief te offereren (excl. BTW)</strong></td>
            <td class="unit">{{ '&euro; '.number_format(CalculationEndresult::totalProject($project), 2, ",",".") }}</td>
            <td class="qty">&nbsp;</td>
            <td class="total">&nbsp;</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no"><strong>BTW bedrag calculatie belast met 21%</strong>&nbsp;</td>
            <td class="unit">&nbsp;</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::totalContractingTax1($project)+CalculationEndresult::totalSubcontractingTax1($project), 2, ",",".") }}</td>
            <td class="total">&nbsp;</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no"><strong>BTW bedrag calculatie belast met 6%</strong></td>
            <td class="unit">&nbsp;</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::totalContractingTax2($project)+CalculationEndresult::totalSubcontractingTax2($project), 2, ",",".") }}</td>
            <td class="total">&nbsp;</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no"><strong>Te offereren BTW bedrag</strong></td>
            <td class="unit">&nbsp;</td>
            <td class="qty"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalProjectTax($project), 2, ",",".") }}</strong></td>
            <td class="total">&nbsp;</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no"><strong>Calculatief te offereren (Incl. BTW)</strong></td>
            <td class="unit">&nbsp;</td>
            <td class="qty">&nbsp;</td>
            <td class="total"><strong class="pull-right">{{ '&euro; '.number_format(CalculationEndresult::superTotalProject($project), 2, ",",".") }}</strong></td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no"><strong></strong></td>
            <td class="unit">&nbsp;</td>
            <td class="qty">&nbsp;</td>
            <td class="total"><strong class="pull-right">{{ '&euro; '.number_format(CalculationEndresult::superTotalProject($project), 2, ",",".") }}</strong></td>
          </tr>
        </tbody>
      </table>

      <div class="closingtext">{{ ($offer_last ? $offer_last->closure : '') }}</div>

      <h1 class="name">Bepalingen</h1>
      <div class="statements">
        <li>Indien opdracht gegund wordt, ontvangt u één eindfactuur.</li>
        <li>Wij kunnen de werkzaamheden starten binnen {{ DeliverTime::find($offer_last->deliver_id)->delivertime_name }} na uw opdrachtbevestiging.</li>
        <li>Deze offerte is geldig tot {{ Valid::find($offer_last->valid_id)->valid_name }} na dagtekening.</li>
      </div>
      <div class="signing">Met vriendelijke groet,</div>
      <div class="signing">{{ Contact::find($offer_last->from_contact_id)->firstname ." ". Contact::find($offer_last->from_contact_id)->lastname }}</div>
    </main>

    <footer>
      Deze offerte is op de computer gegenereerd en is geldig zonder handtekening.
    </footer>

@else
 <h2 class="name">Aanneming</h2>
     <table border="0" cellspacing="0" cellpadding="0">
        <thead>
          <tr style="page-break-after: always;">
            <th class="no">&nbsp;</th>
            <th class="desc">Uren</th>
            <th class="unit">Bedrag (excl. BTW)</th>
            <th class="qty">BTW %</th>
            <th class="qty">BTW bedrag</th>
          </tr>
        </thead>
        <tbody>
          <tr style="page-break-after: always;">
            <td class="no"><strong>Arbeidskosten</strong></td>
            <td class="desc">{{ ''.number_format(CalculationEndresult::conCalcLaborActivityTax1($project), 2, ",",".") }}</td>
            <td class="unit">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
            <td class="qty">21 %</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax1AmountTax($project), 2, ",",".") }}</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no">&nbsp;</td>
            <td class="desc">{{ ''.number_format(CalculationEndresult::conCalcLaborActivityTax2($project), 2, ",",".") }}</td>
            <td class="unit">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
            <td class="qty">6 %</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax1AmountTax($project), 2, ",",".") }}</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no">&nbsp;</td>
            <td class="desc">{{ ''.number_format(CalculationEndresult::conCalcLaborActivityTax3($project), 2, ",",".") }}</td>
            <td class="unit">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="qty">0 %</td>
            <td class="qty">&nbsp;</td>
          </tr>

          <tr style="page-break-after: always;">
            <td class="no"><strong>Materiaalkosten</strong></td>
            <td class="desc">&nbsp;</td>
            <td class="unit">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
            <td class="qty">21 %</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax1AmountTax($project), 2, ",",".") }}</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no">&nbsp;</td>
            <td class="desc">&nbsp;</td>
            <td class="unit">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
            <td class="qty">6 %</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax2AmountTax($project), 2, ",",".") }}</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no">&nbsp;</td>
            <td class="desc">&nbsp;</td>
            <td class="unit">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="qty">0 %</td>
            <td class="qty">&nbsp;</td>
          </tr>

          <tr style="page-break-after: always;">
            <td class="no"><strong>Materieelkosten</strong></td>
            <td class="desc">&nbsp;</td>
            <td class="unit">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
            <td class="qty">21 %</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax1AmountTax($project), 2, ",",".") }}</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no">&nbsp;</td>
            <td class="desc">&nbsp;</td>
            <td class="unit">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
            <td class="qty">6 %</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax2AmountTax($project), 2, ",",".") }}</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no">&nbsp;</td>
            <td class="desc">&nbsp;</td>
            <td class="unit">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="qty">0 %</td>
            <td class="qty">&nbsp;</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no"><strong>Totaal Aanneming </strong></td>
            <td class="desc">&nbsp;</td>
            <td class="unit"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalContracting($project), 2, ",",".") }}</strong></td>
            <td class="qty">&nbsp;</td>
            <td class="qty"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalContractingTax($project), 2, ",",".") }}</strong></td>
          </tr>
     </table>

     <h2 class="name">Onderaanneming</h2>
     <table border="0" cellspacing="0" cellpadding="0">
        <thead>
          <tr style="page-break-after: always;">
            <th class="no">&nbsp;</th>
            <th class="desc">Uren</th>
            <th class="unit">Bedrag (excl. BTW)</th>
            <th class="qty">BTW %</th>
            <th class="qty">BTW bedrag</th>
          </tr>
        </thead>
        <tbody>
          <tr style="page-break-after: always;">
            <td class="no"><strong>Arbeidskosten</strong></td>
            <td class="desc">{{ ' '.number_format(CalculationEndresult::subconCalcLaborActivityTax1($project), 2, ",",".") }}</td>
            <td class="unit">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
            <td class="qty">21 %</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax1AmountTax($project), 2, ",",".") }}</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no">&nbsp;</td>
            <td class="desc">{{ ' '.number_format(CalculationEndresult::subconCalcLaborActivityTax2($project), 2, ",",".") }}</td>
            <td class="unit">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
            <td class="qty">6 %</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax2AmountTax($project), 2, ",",".") }}</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no">&nbsp;</td>
            <td class="desc">{{ ''.number_format(CalculationEndresult::subconCalcLaborActivityTax3($project), 2, ",",".") }}</td>
            <td class="unit">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="qty">0 %</td>
            <td class="qty">&nbsp;</td>
          </tr>

          <tr style="page-break-after: always;">
            <td class="no"><strong>Materiaalkosten</strong></td>
            <td class="desc">&nbsp;</td>
            <td class="unit">{{ '&euro; '.number_format(CalculationEndresult::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
            <td class="qty">21 %</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::subconCalcMaterialActivityTax1AmountTax($project), 2, ",",".") }}</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no">&nbsp;</td>
            <td class="desc">&nbsp;</td>
            <td class="unit">{{ '&euro; '.number_format(CalculationEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
            <td class="qty">6 %</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::subconCalcMaterialActivityTax2AmountTax($project), 2, ",",".") }}</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no">&nbsp;</td>
            <td class="desc">&nbsp;</td>
            <td class="unit">{{ '&euro; '.number_format(CalculationEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="qty">0 %</td>
            <td class="qty">&nbsp;</td>
          </tr>

          <tr style="page-break-after: always;">
            <td class="no"><strong>Materieelkosten</strong></td>
            <td class="desc">&nbsp;</td>
            <td class="unit">{{ '&euro; '.number_format(CalculationEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
            <td class="qty">21 %</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::subconCalcEquipmentActivityTax1AmountTax($project), 2, ",",".") }}</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no">&nbsp;</td>
            <td class="desc">&nbsp;</td>
            <td class="unit">{{ '&euro; '.number_format(CalculationEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
            <td class="qty">6 %</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::subconCalcEquipmentActivityTax2AmountTax($project), 2, ",",".") }}</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no">&nbsp;</td>
            <td class="desc">&nbsp;</td>
            <td class="unit">{{ '&euro; '.number_format(CalculationEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="qty">0 %</td>
            <td class="qty">&nbsp;</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no"><strong>total Onderaanneming </strong></td>
            <td class="desc">&nbsp;</td>
            <td class="unit"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
            <td class="qty">&nbsp;</td>
            <td class="qty"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalSubcontractingTax($project), 2, ",",".") }}</strong></td>
          </tr>
       </table>

      <h1 class="name">Cumulatieven offerte</h1>
      <table border="0" cellspacing="0" cellpadding="0">
        <thead>
          <tr style="page-break-after: always;">
            <th class="no">&nbsp;</th>
            <th class="unit">Bedrag (excl. BTW)</th>
            <th class="qty">BTW bedrag</th>
            <th class="total">Bedrag (incl. BTW)</th>
          </tr>
        </thead>
        <tbody>
          <tr style="page-break-after: always;">
            <td class="no"><strong>Calculatief te offereren (excl. BTW)</strong></td>
            <td class="unit">{{ '&euro; '.number_format(CalculationEndresult::totalProject($project), 2, ",",".") }}</td>
            <td class="qty">&nbsp;</td>
            <td class="total">&nbsp;</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no"><strong>BTW bedrag calculatie aanneming belast met 21%</strong>&nbsp;</td>
            <td class="unit">&nbsp;</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::totalContractingTax1($project), 2, ",",".") }}</td>
            <td class="total">&nbsp;</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no"><strong>BTW bedrag calculatie aanneming belast met 6%</strong></td>
            <td class="unit">&nbsp;</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::totalContractingTax2($project), 2, ",",".") }}</td>
            <td class="total">&nbsp;</td>
          </tr>
           <tr style="page-break-after: always;">
            <td class="no"><strong>BTW bedrag calculatie onderaanneming belast met 21%</strong>&nbsp;</td>
            <td class="unit">&nbsp;</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::totalSubcontractingTax1($project), 2, ",",".") }}</td>
            <td class="total">&nbsp;</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no"><strong>BTW bedrag calculatie onderaanneming belast met 6%</strong></td>
            <td class="unit">&nbsp;</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::totalSubcontractingTax2($project), 2, ",",".") }}</td>
            <td class="total">&nbsp;</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no"><strong>Te offereren BTW bedrag</strong></td>
            <td class="unit">&nbsp;</td>
            <td class="qty">&nbsp;</td>
            <td class="total">{{ '&euro; '.number_format(CalculationEndresult::totalProjectTax($project), 2, ",",".") }}</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no"><strong>Calculatief te offereren (Incl. BTW)</strong></td>
            <td class="unit">&nbsp;</td>
            <td class="qty">&nbsp;</td>
            <td class="total">{{ '&euro; '.number_format(CalculationEndresult::superTotalProject($project), 2, ",",".") }}</td>
          </tr>
        </tbody>
      </table>

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


      <div class="closingtext">{{ ($offer_last ? $offer_last->closure : '') }}</div>

      <h1 class="name">Bepalingen</h1>
      <div class="statements">
        <li>Indien opdracht gegund wordt, ontvangt u één eindfactuur.</li>
        <li>Wij kunnen de werkzaamheden starten binnen {{ DeliverTime::find($offer_last->deliver_id)->delivertime_name }} na uw opdrachtbevestiging.</li>
        <li>Deze offerte is geldig tot {{ Valid::find($offer_last->valid_id)->valid_name }} na dagtekening.</li>
      </div>
      <div class="signing">Met vriendelijke groet,</div>
      <div class="signing">{{ Contact::find($offer_last->from_contact_id)->firstname ." ". Contact::find($offer_last->from_contact_id)->lastname }}</div>
    </main>

    <footer>
      Deze offerte is op de computer gegenereerd en is geldig zonder handtekening.
    </footer>
 @endif

    @if ($specification)
    @if ($total)
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

     <h1 class="name">Totaalkosten per werkzaamheid</h1>
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
            <th class="total">Stelpost</th>
          </tr>
        </thead>
        <tbody>
          @foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
          @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->get() as $activity)
          <tr><!-- item -->
            <td class="no"><strong>{{ $chapter->chapter_name }}</strong></td>
            <td class="desc">{{ $activity->activity_name }}</td>
            <td class="no"><span class="pull-right">{{ number_format(CalculationOverview::laborTotal($activity), 2, ",",".") }}</td>
            <td class="desc"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::laborActivity($project->hour_rate, $activity), 2, ",",".") }}</span></td>
            <td class="unit"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::materialActivityProfit($activity, $project->profit_calc_contr_mat), 2, ",",".") }}</span></td>
            <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::equipmentActivityProfit($activity, $project->profit_calc_contr_equip), 2, ",",".") }}</span></td>
            <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::activityTotalProfit($project->hour_rate, $activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip), 2, ",",".") }} </td>
            <td class="total text-center ($activity)"></td>
          </tr>
          @endforeach
          @endforeach
          @foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
          @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->get() as $activity)
          <tr><!-- item -->
            <td class="no"><strong>{{ $chapter->chapter_name }}</strong></td>
            <td class="desc">{{ $activity->activity_name }}</td>
            <td class="no"><span class="pull-right">{{ number_format(CalculationOverview::laborTotal($activity), 2, ",",".") }}</td>
            <td class="desc"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::laborActivity($project->hour_rate, $activity), 2, ",",".") }}</span></td>
            <td class="unit"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::materialActivityProfit($activity, $project->profit_calc_subcontr_mat), 2, ",",".") }}</span></td>
            <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::equipmentActivityProfit($activity, $project->profit_calc_subcontr_equip), 2, ",",".") }}</span></td>
            <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::activityTotalProfit($project->hour_rate, $activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip), 2, ",",".") }} </td>
            <td class="total text-center">
            <?php
              if (PartType::find($activity->part_type_id)->type_name=='estimate') {
                echo "<strong>Ja</strong>";
              }
            ?>
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
          <td class="no">&nbsp;</td>
          <td class="desc">&nbsp;</td>
          <td class="no"><span class="pull-right">{{ CalculationOverview::laborSuperTotalAmount($project) }}</span></td>
          <td class="desc"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::laborSuperTotal($project), 2, ",",".") }}</span></td>
          <td class="unit"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::materialSuperTotal($project), 2, ",",".") }}</span></td>
          <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::equipmentSuperTotal($project), 2, ",",".") }}</span></td>
          <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::superTotal($project), 2, ",",".") }}</span></td>
        </tbody>
      </table>
      @else
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

     <h1 class="name">Totalen per project</h1>
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
            <th class="total">Stelpost</th>
          </tr>
        </thead>
        <tbody>
            @foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
            @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->get() as $activity)
            <tr style="page-break-after: always;">
              <td class="no"><strong>{{ $chapter->chapter_name }}</strong></td>
              <td class="desc">{{ $activity->activity_name }}</td>
              <td class="no"><span class="pull-right">{{ number_format(CalculationOverview::laborTotal($activity), 2, ",",".") }}</td>
              <td class="desc"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::laborActivity($project->hour_rate, $activity), 2, ",",".") }}</span></td>
              <td class="unit"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::materialActivityProfit($activity, $project->profit_calc_contr_mat), 2, ",",".") }}</span></td>
              <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::equipmentActivityProfit($activity, $project->profit_calc_contr_equip), 2, ",",".") }}</span></td>
              <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::activityTotalProfit($project->hour_rate, $activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip), 2, ",",".") }} </td>
              <td class="total text-center">
            <?php
              if (PartType::find($activity->part_type_id)->type_name=='estimate') {
                echo "<strong>Ja</strong>";
              }
            ?>
              </td>
            </tr>
            @endforeach
            @endforeach
          <tr style="page-break-after: always;">
                <td class="no"><strong>total aanneming</strong></td>
                <td class="desc">&nbsp;</td>
                <td class="no"><strong><span class="pull-right">{{ CalculationOverview::contrLaborTotalAmount($project) }}</span></strong></td>
                <td class="desc"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::contrLaborTotal($project), 2, ",",".") }}</span></strong></td>
                <td class="unit"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::contrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
                <td class="qty"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::contrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
                <td class="qty"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::contrTotal($project), 2, ",",".") }}</span></strong></td>
                <td class="total">&nbsp;</td>
              </tr>
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
            <th class="total">Stelpost</th>
          </tr>
        </thead>
        <tbody>
          @foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
          @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->get() as $activity)
          <tr style="page-break-after: always;">
            <td class="no"><strong>{{ $chapter->chapter_name }}</strong></td>
            <td class="desc">{{ $activity->activity_name }}</td>
            <td class="no"><span class="pull-right">{{ number_format(CalculationOverview::laborTotal($activity), 2, ",",".") }}</td>
            <td class="desc"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::laborActivity($project->hour_rate, $activity), 2, ",",".") }}</span></td>
            <td class="unit"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::materialActivityProfit($activity, $project->profit_calc_subcontr_mat), 2, ",",".") }}</span></td>
            <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::equipmentActivityProfit($activity, $project->profit_calc_subcontr_equip), 2, ",",".") }}</span></td>
            <td class="qty"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::activityTotalProfit($project->hour_rate, $activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip), 2, ",",".") }} </td>
            <td class="total text-center">
            <?php
              if (PartType::find($activity->part_type_id)->type_name=='estimate') {
                echo "<strong>Ja</strong>";
              }
            ?>
            </td>
          </tr>
          @endforeach
          @endforeach
          <tr style="page-break-after: always;">
            <td class="no"><strong>total onderaanneming</strong></td>
            <td class="desc">&nbsp;</td>
            <td class="no"><strong><span class="pull-right">{{ CalculationOverview::subcontrLaborTotalAmount($project) }}</span></strong></td>
            <td class="desc"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::subcontrLaborTotal($project), 2, ",",".") }}</span></strong></td>
            <td class="unit"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::subcontrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
            <td class="qty"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::subcontrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
            <td class="qty"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::subcontrTotal($project), 2, ",",".") }}</span></strong></td>
            <td class="total">&nbsp;</td>
          </tr>
     </table>
     <h1 class="name">Totalen per project</h1>
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
            <th class="total">&nbsp;</th>
          </tr>
        </thead>
        <tbody>
          <tr style="page-break-after: always;">
            <td class="no">&nbsp;</td>
            <td class="desc">&nbsp;</td>
            <td class="no"><strong><span class="pull-right">{{ CalculationOverview::laborSuperTotalAmount($project) }}</span></strong></td>
            <td class="desc"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::laborSuperTotal($project), 2, ",",".") }}</span></strong></td>
            <td class="unit"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::materialSuperTotal($project), 2, ",",".") }}</span></strong></td>
            <td class="qty"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::equipmentSuperTotal($project), 2, ",",".") }}</span></strong></td>
            <td class="qty"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::superTotal($project), 2, ",",".") }}</span></strong></td>
            <td class="total">&nbsp;</td>
          </tr>
      </table>
      @endif
      @endif

    @if ($description)
    @if ($total)
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
    <h1 class="name">description werkzaamheden</h1>
    <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr style="page-break-after: always;">
          <th class="no">Hoofdstuk</th>
          <th class="desc">Werkzaamheid</th>
          <th class="no">description</th>
        </tr>
      </thead>
      <tbody>
        @foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
        @foreach (Activity::where('chapter_id','=', $chapter->id)->get() as $activity)
        <tr><!-- item -->
          <td class="no"><strong>{{ $chapter->chapter_name }}</strong></td>
          <td class="desc">{{ $activity->activity_name }}</td>
          <td class="no"><span>{{ $activity->note }}</td>
        </tr>
        @endforeach
        @endforeach
      </tbody>
    </table>
    @else
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
    <h1 class="name">description werkzaamheden</h1>
    <h2 class="name">Aanneming</h2>
    <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr style="page-break-after: always;">
          <th class="no">Hoofdstuk</th>
          <th class="desc">Werkzaamheid</th>
          <th class="no">description</th>
        </tr>
      </thead>
      <tbody>
        @foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
        @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->get() as $activity)
        <tr><!-- item -->
          <td class="no"><strong>{{ $chapter->chapter_name }}</strong></td>
          <td class="desc">{{ $activity->activity_name }}</td>
          <td class="no"><span>{{ $activity->note }}</td>
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
          <th class="no">description</th>
        </tr>
      </thead>
      <tbody>
        @foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
        @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->get() as $activity)
        <tr><!-- item -->
          <td class="no"><strong>{{ $chapter->chapter_name }}</strong></td>
          <td class="desc">{{ $activity->activity_name }}</td>
          <td class="no"><span>{{ $activity->note }}</td>
        </tr>
        @endforeach
        @endforeach
      </tbody>
    </table>
    @endif
    @endif
  </body>
</html>
