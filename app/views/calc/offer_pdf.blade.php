<?php
$total=Input::get("total");
$specification=Input::get("specification");
$description=Input::get("description");
$onlyactivity=Input::get("onlyactivity");
$displaytax=Input::get("displaytax");
$endresult=Input::get("endresult");
$c=false;

$project = Project::find(Route::Input('project_id'));
if (!$project || !$project->isOwner()) {
  exit();
}
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
    <title>Offerte</title>
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
        <div>Telefoon: </i>:{{ $relation_self->phone }}</div>
        <div>E-mail: {{ $relation_self->email }}</div>
        <div>KVK: {{ $relation_self->kvk }}</li>
    </header>
    <main>
      <div id="details" class="clearfix">
        <div id="client">
          <div>{{ $relation->company_name }}</div>
          <div>T.a.v.
              {{ Contact::find($offer_last->to_contact_id)->firstname ." ". Contact::find($offer_last->to_contact_id)->lastname }}</div>
          <div>{{ $relation->address_street . ' ' . $relation->address_number }}</div>
          <div>{{ $relation->address_postal . ', ' . $relation->address_city }}</div>
        </div>
        <div id="invoice">
          <h3 class="name">{{ OfferController::getOfferCode($project->id) }}</h3>
          <div class="date">{{ $project->project_name }}</div>
          <div class="date">{{ date("j M Y") }}</div>
        </div>
      </div>

      <div class="openingtext">Geachte {{ Contact::find($offer_last->to_contact_id)->firstname ." ". Contact::find($offer_last->to_contact_id)->lastname }},</div>
      <div class="openingtext">{{ ($offer_last ? $offer_last->description : '') }}</div>

      <h1 class="name">Specificatie offerte</h1>
      @if ($total)
      <table border="0" cellspacing="0" cellpadding="0">
        <thead>
          <tr style="page-break-after: always;">
            <th style="width: 147px" align="left" class="qty">&nbsp;</th>
            <th style="width: 60px" align="left" class="qty">Uren</th>
            <th style="width: 119px" align="left" class="qty">Bedrag (excl. BTW)</th>
            <th style="width: 70px" align="left" class="qty">BTW %</th>
            <th style="width: 80px" align="left" class="qty">BTW bedrag</th>
            <th style="width: 119px" align="left" class="qty">&nbsp;</th>
          </tr>
        </thead>
        <tbody>
        @if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
          <tr style="page-break-after: always;">
            <td class="qty"><strong>Arbeidskosten</strong></td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax1($project)+CalculationEndresult::subconCalcLaborActivityTax1($project), 2, ",",".") }}</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax1Amount($project)+CalculationEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
            <td class="qty">21 %</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax1AmountTax($project)+CalculationEndresult::subconCalcLaborActivityTax1AmountTax($project), 2, ",",".") }}</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="qty">&nbsp;</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax2($project)+CalculationEndresult::subconCalcLaborActivityTax2($project), 2, ",",".") }}</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax2Amount($project)+CalculationEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
            <td class="qty">6 %</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax2AmountTax($project)+CalculationEndresult::subconCalcLaborActivityTax2AmountTax($project), 2, ",",".") }}</td>
          </tr>
          @else
          <tr style="page-break-after: always;">
            <td class="qty"><strong>Arbeidskosten</strong></td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax3($project)+CalculationEndresult::subconCalcLaborActivityTax3($project), 2, ",",".") }}</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax3Amount($project)+CalculationEndresult::conCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="qty">0 %</td>
            <td class="qty">&nbsp;</td>
          </tr>
          @endif

          @if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
          <tr style="page-break-after: always;">
            <td class="qty"><strong>Materiaalkosten</strong></td>
            <td class="qty">&nbsp;</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax1Amount($project)+CalculationEndresult::conCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
            <td class="qty">21 %</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax1AmountTax($project)+CalculationEndresult::subconCalcMaterialActivityTax1AmountTax($project), 2, ",",".") }}</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="qty">&nbsp;</td>
            <td class="qty">&nbsp;</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax2Amount($project)+CalculationEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
            <td class="qty">6 %</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax2AmountTax($project)+CalculationEndresult::subconCalcMaterialActivityTax2AmountTax($project), 2, ",",".") }}</td>
          </tr>
          @else
          <tr style="page-break-after: always;">
            <td class="qty"><strong>Materiaalkosten</strong></td>
            <td class="qty">&nbsp;</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax3Amount($project)+CalculationEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="qty">0 %</td>
            <td class="qty">&nbsp;</td>
          </tr>
          @endif

          @if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
          <tr style="page-break-after: always;">
            <td class="no"><strong>Materieelkosten</strong></td>
            <td class="desc">&nbsp;</td>
            <td class="unit">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax1Amount($project)+CalculationEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
            <td class="qty">21 %</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax1AmountTax($project)+CalculationEndresult::subconCalcEquipmentActivityTax1AmountTax($project), 2, ",",".") }}</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="qty">&nbsp;</td>
            <td class="qty">&nbsp;</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax2Amount($project)+CalculationEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
            <td class="qty">6 %</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax2AmountTax($project)+CalculationEndresult::subconCalcEquipmentActivityTax2AmountTax($project), 2, ",",".") }}</td>
          </tr>
          @else
          <tr style="page-break-after: always;">
            <td class="qty">strong>Materieelkosten</strong></td>
            <td class="qty">&nbsp;</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax3Amount($project)+CalculationEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="qty">0 %</td>
            <td class="qty">&nbsp;</td>
          </tr>
          @endif
        </tbody>
      </table>

      <h1 class="name">Totalen offerte</h1>
      <table border="0" cellspacing="0" cellpadding="0">
        <thead>
          <tr style="page-break-after: always;">
            <th style="width: 207px" align="left" class="qty">&nbsp;</th>
            <th style="width: 119px" align="left" class="qty">Bedrag (excl. BTW)</th>
            <th style="width: 70px" align="left" class="qty">&nbsp;</th>
            <th style="width: 80px" align="left" class="qty">BTW bedrag</th>
            <th style="width: 119px" align="left" class="qty">Bedrag (incl. BTW)</th>
          </tr>
        </thead>
        <tbody>
          <tr style="page-break-after: always;">
            <td class="qty"><strong>Calculatief te offreren (excl. BTW)</strong></td>
            <td class="qty"><strong class="pull-right">{{ '&euro; '.number_format(CalculationEndresult::totalProject($project), 2, ",",".") }}</strong></td>
            <td class="qty">&nbsp;</td>
            <td class="qty">&nbsp;</td>
            <td class="qty">&nbsp;</td>
          </tr>
          @if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
          <tr style="page-break-after: always;">
            <td class="qty">BTW bedrag 21%</td>
            <td class="qty">&nbsp;</td>
            <td class="qty">&nbsp;</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::totalContractingTax1($project)+CalculationEndresult::totalSubcontractingTax1($project), 2, ",",".") }}</td>
            <td class="qty">&nbsp;</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="qty">BTW bedrag 6%</td>
            <td class="qty">&nbsp;</td>
            <td class="qty">&nbsp;</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::totalContractingTax2($project)+CalculationEndresult::totalSubcontractingTax2($project), 2, ",",".") }}</td>
            <td class="qty">&nbsp;</td>
          </tr>
          @endif
          <tr style="page-break-after: always;">
            <td class="qty"><strong>Te offreren BTW bedrag</strong></td>
            <td class="qty">&nbsp;</td>
            <td class="qty">&nbsp;</td>
            <td class="qty"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalProjectTax($project), 2, ",",".") }}</strong></td>
            <td class="qty">&nbsp;</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="qty"><strong>Calculatief te offreren (Incl. BTW)</strong></td>
            <td class="qty">&nbsp;</td>
            <td class="qty">&nbsp;</td>
            <td class="qty">&nbsp;</td>
            <td class="qty"><strong class="pull-right">{{ '&euro; '.number_format(CalculationEndresult::superTotalProject($project), 2, ",",".") }}</strong></td>
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
      <br>
      <div class="signing">{{ Contact::find($offer_last->from_contact_id)->firstname ." ". Contact::find($offer_last->from_contact_id)->lastname }}</div>
    </main>

    <footer >
      Deze offerte is op de computer gegenereerd en is geldig zonder handtekening.
    </footer>

@else
     <table border="0" cellspacing="0" cellpadding="0">
        <thead>
          <h4 class="name">AANNEMING</h4>
          <tr style="page-break-after: always;">
            <th style="width: 147px" align="left" class="qty">&nbsp;</th>
            <th style="width: 60px" align="left" class="qty">Uren</th>
            <th style="width: 119px" align="left" class="qty">Bedrag (excl. BTW)</th>
            <th style="width: 70px" align="left" class="qty">BTW %</th>
            <th style="width: 80px" align="left" class="qty">BTW bedrag</th>
            <th style="width: 119px" align="left" class="qty">&nbsp;</th>
          </tr>
        </thead>
        <tbody>
        @if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
          <tr style="page-break-after: always;">
            <td class="qty"><strong>Arbeidskosten</strong></td>
            <td class="qty">{{ ''.number_format(CalculationEndresult::conCalcLaborActivityTax1($project), 2, ",",".") }}</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
            <td class="qty">21 %</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax1AmountTax($project), 2, ",",".") }}</td>
            <td class="qty">&nbsp;</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="qty">&nbsp;</td>
            <td class="qty">{{ ''.number_format(CalculationEndresult::conCalcLaborActivityTax2($project), 2, ",",".") }}</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
            <td class="qty">6 %</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax1AmountTax($project), 2, ",",".") }}</td>
            <td class="qty">&nbsp;</td>
          </tr>
          @else
          <tr style="page-break-after: always;">
            <td class="qty"><strong>Arbeidskosten</strong></td>
            <td class="qty">{{ ''.number_format(CalculationEndresult::conCalcLaborActivityTax3($project), 2, ",",".") }}</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::conCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="qty">0 %</td>
            <td class="qty">&nbsp;</td>
            <td class="qty">&nbsp;</td>
          </tr>
          @endif

          @if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
          <tr style="page-break-after: always;">
            <td class="qty"><strong>Materiaalkosten</strong></td>
            <td class="qty">&nbsp;</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
            <td class="qty">21 %</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax1AmountTax($project), 2, ",",".") }}</td>
            <td class="qty">&nbsp;</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="qty">&nbsp;</td>
            <td class="qty">&nbsp;</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
            <td class="qty">6 %</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax2AmountTax($project), 2, ",",".") }}</td>
            <td class="qty">&nbsp;</td>
          </tr>
          @else
          <tr style="page-break-after: always;">
            <td class="no"><strong>Materiaalkosten</strong></td>
            <td class="desc">&nbsp;</td>
            <td class="unit">{{ '&euro; '.number_format(CalculationEndresult::conCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="qty">0 %</td>
            <td class="qty">&nbsp;</td>
            <td class="qty">&nbsp;</td>
          </tr>
          @endif

          @if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
          <tr style="page-break-after: always;">
            <td class="qty"><strong>Materieelkosten</strong></td>
            <td class="qty">&nbsp;</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
            <td class="qty">21 %</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax1AmountTax($project), 2, ",",".") }}</td>
            <td class="qty">&nbsp;</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no">&nbsp;</td>
            <td class="desc">&nbsp;</td>
            <td class="unit">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
            <td class="qty">6 %</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax2AmountTax($project), 2, ",",".") }}</td>
            <td class="qty">&nbsp;</td>
          </tr>
          @else
          <tr style="page-break-after: always;">
            <td class="qty"><strong>Materieelkosten</strong></td>
            <td class="qty">&nbsp;</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::conCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="qty">0 %</td>
            <td class="qty">&nbsp;</td>
            <td class="qty">&nbsp;</td>
          </tr>
          @endif
          <tr style="page-break-after: always;">
            <td class="qty"><strong>Totaal Aanneming </strong></td>
            <td class="qty">&nbsp;</td>
            <td class="qty"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalContracting($project), 2, ",",".") }}</strong></td>
            <td class="qty">&nbsp;</td>
            <td class="qty"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalContractingTax($project), 2, ",",".") }}</strong></td>
            <td class="qty">&nbsp;</td>
          </tr>
     </table>
     <br>
     <table border="0" cellspacing="0" cellpadding="0">
        <thead>
          <h4 class="name">ONDERAANNEMING</h4>
          <tr style="page-break-after: always;">
            <th style="width: 147px" align="left" class="qty">&nbsp;</th>
            <th style="width: 60px" align="left" class="qty">Uren</th>
            <th style="width: 120px" align="left" class="qty">Bedrag (excl. BTW)</th>
            <th style="width: 70px" align="left" class="qty">BTW %</th>
            <th style="width: 80px" align="left" class="qty">BTW bedrag</th>
            <th style="width: 119px" align="left" class="qty">&nbsp;</th>
          </tr>
        </thead>
        <tbody>
        @if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
          <tr style="page-break-after: always;">
            <td class="qty"><strong>Arbeidskosten</strong></td>
            <td class="qty">{{ ' '.number_format(CalculationEndresult::subconCalcLaborActivityTax1($project), 2, ",",".") }}</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
            <td class="qty">21 %</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax1AmountTax($project), 2, ",",".") }}</td>
            <td class="qty">&nbsp;</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="qty">&nbsp;</td>
            <td class="qty">{{ ' '.number_format(CalculationEndresult::subconCalcLaborActivityTax2($project), 2, ",",".") }}</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
            <td class="qty">6 %</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax2AmountTax($project), 2, ",",".") }}</td>
            <td class="qty">&nbsp;</td>
          </tr>
          @else
          <tr style="page-break-after: always;">
            <td class="qty"><strong>Arbeidskosten</strong></td>
            <td class="qty">{{ ''.number_format(CalculationEndresult::subconCalcLaborActivityTax3($project), 2, ",",".") }}</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="qty">0 %</td>
            <td class="qty">&nbsp;</td>
            <td class="qty">&nbsp;</td>
          </tr>
          @endif

          @if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
          <tr style="page-break-after: always;">
            <td class="qty"><strong>Materiaalkosten</strong></td>
            <td class="qty">&nbsp;</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
            <td class="qty">21 %</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::subconCalcMaterialActivityTax1AmountTax($project), 2, ",",".") }}</td>
            <td class="qty">&nbsp;</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="qty">&nbsp;</td>
            <td class="qty">&nbsp;</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
            <td class="qty">6 %</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::subconCalcMaterialActivityTax2AmountTax($project), 2, ",",".") }}</td>
            <td class="qty">&nbsp;</td>
          </tr>
          @else
          <tr style="page-break-after: always;">
            <td class="qty"><strong>Materiaalkosten</strong></td>
            <td class="qty">&nbsp;</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="qty">0 %</td>
            <td class="qty">&nbsp;</td>
            <td class="qty">&nbsp;</td>
          </tr>
          @endif

          @if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
          <tr style="page-break-after: always;">
            <td class="qty"><strong>Materieelkosten</strong></td>
            <td class="qty">&nbsp;</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
            <td class="qty">21 %</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::subconCalcEquipmentActivityTax1AmountTax($project), 2, ",",".") }}</td>
            <td class="qty">&nbsp;</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="qty">&nbsp;</td>
            <td class="qty">&nbsp;</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
            <td class="qty">6 %</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::subconCalcEquipmentActivityTax2AmountTax($project), 2, ",",".") }}</td>
            <td class="qty">&nbsp;</td>
          </tr>
          @else
          <tr style="page-break-after: always;">
            <td class="qty"><strong>Materieelkosten</strong></td>
            <td class="qty">&nbsp;</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="qty">0 %</td>
            <td class="qty">&nbsp;</td>
            <td class="qty">&nbsp;</td>
          </tr>
          @endif
          <tr style="page-break-after: always;">
            <td class="qty"><strong>Totaal Onderaanneming </strong></td>
            <td class="qty">&nbsp;</td>
            <td class="qty"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
            <td class="qty">&nbsp;</td>
            <td class="qty"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalSubcontractingTax($project), 2, ",",".") }}</strong></td>
            <td class="qty">&nbsp;</td>
          </tr>
        </tbody>
      </table>

      <h1 class="name">Totalen offerte</h1>
      <table border="0" cellspacing="0" cellpadding="0">
        <thead>
          <tr style="page-break-after: always;">
            <th style="width: 207px" align="left" class="qty">&nbsp;</th>
            <th style="width: 119px" align="left" class="qty">Bedrag (excl. BTW)</th>
            <th style="width: 70px" align="left" class="qty">&nbsp;</th>
            <th style="width: 80px" align="left" class="qty">BTW bedrag</th>
            <th style="width: 119px" align="left" class="qty">Bedrag (incl. BTW)</th>
          </tr>
        </thead>
        <tbody>
          <tr style="page-break-after: always;">
            <td class="qty"><strong>Calculatief te offreren (excl. BTW)</strong></td>
            <td class="qty"><strong class="pull-right">{{ '&euro; '.number_format(CalculationEndresult::totalProject($project), 2, ",",".") }}</strong></td>
            <td class="qty">&nbsp;</td>
            <td class="qty">&nbsp;</td>
            <td class="qty">&nbsp;</td>
          </tr>
          @if (ProjectType::find($project->type_id)->type_name != 'BTW verlegd')
          <tr style="page-break-after: always;">
            <td class="qty">BTW bedrag 21%</td>
            <td class="qty">&nbsp;</td>
            <td class="qty">&nbsp;</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::totalContractingTax1($project)+CalculationEndresult::totalSubcontractingTax1($project), 2, ",",".") }}</td>
            <td class="qty">&nbsp;</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="qty">BTW bedrag 6%</td>
            <td class="qty">&nbsp;</td>
            <td class="qty">&nbsp;</td>
            <td class="qty">{{ '&euro; '.number_format(CalculationEndresult::totalContractingTax2($project)+CalculationEndresult::totalSubcontractingTax2($project), 2, ",",".") }}</td>
            <td class="qty">&nbsp;</td>
          </tr>
          @endif
          <tr style="page-break-after: always;">
            <td class="qty"><strong>Te offreren BTW bedrag</strong></td>
            <td class="qty">&nbsp;</td>
            <td class="qty">&nbsp;</td>
            <td class="qty"><strong>{{ '&euro; '.number_format(CalculationEndresult::totalProjectTax($project), 2, ",",".") }}</strong></td>
            <td class="qty">&nbsp;</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="qty"><strong>Calculatief te offreren (Incl. BTW)</strong></td>
            <td class="qty">&nbsp;</td>
            <td class="qty">&nbsp;</td>
            <td class="qty">&nbsp;</td>
            <td class="qty"><strong class="pull-right">{{ '&euro; '.number_format(CalculationEndresult::superTotalProject($project), 2, ",",".") }}</strong></td>
          </tr>
        </tbody>
      </table>

      <!--PAGE HEADER SECOND START-->
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
      <!--PAGE HEADER SECOND END-->

      <div class="closingtext">{{ ($offer_last ? $offer_last->closure : '') }}</div>

      <h1 class="name">Bepalingen</h1>
      <div class="statements">
        <li>Indien opdracht gegund wordt, ontvangt u één eindfactuur.</li>
        <li>Wij kunnen de werkzaamheden starten binnen {{ DeliverTime::find($offer_last->deliver_id)->delivertime_name }} na uw opdrachtbevestiging.</li>
        <li>Deze offerte is geldig tot {{ Valid::find($offer_last->valid_id)->valid_name }} na dagtekening.</li>
      </div>
      <div class="signing">Met vriendelijke groet,</div>
      <br>
      <div class="signing">{{ Contact::find($offer_last->from_contact_id)->firstname ." ". Contact::find($offer_last->from_contact_id)->lastname }}</div>
    </main>

    <footer>
      Deze offerte is op de computer gegenereerd en is geldig zonder handtekening.
    </footer>
 @endif

    @if ($specification)
    @if ($total)

      <!--PAGE HEADER SECOND START-->
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
      <!--PAGE HEADER SECOND END-->

     <h1 class="name">Totaalkosten per werkzaamheid</h1>
     <table border="0" cellspacing="0" cellpadding="0">
        <thead>
          <tr style="page-break-after: always;">
            <th style="width: 130px" class="qty">Hoofdstuk</th>
            <th style="width: 170px" class="qty">Werkzaamheid</th>
            <th style="width: 40px" class="qty">Uren</th>
            <th style="width: 51px" class="qty">Arbeid</th>
            <th style="width: 51px" class="qty">Materiaal</th>
            <th style="width: 51px" class="qty">Materieel</th>
            <th style="width: 51px" class="qty">Totaal</th>
            <th style="width: 51px" class="qty">Stelpost</th>
          </tr>
        </thead>
        <tbody>
          @foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
          @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->get() as $activity)
          <tr><!-- item -->
            <td class="qty"><strong>{{ $chapter->chapter_name }}</strong></td>
            <td class="qty">{{ $activity->activity_name }}</td>
            <td class="qty"><span>{{ number_format(CalculationOverview::laborTotal($activity), 2, ",",".") }}</td>
            <td class="qty"><span class="total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::laborActivity($project->hour_rate, $activity), 2, ",",".") }}</span></td>
            <td class="qty"><span class="total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::materialActivityProfit($activity, $project->profit_calc_contr_mat), 2, ",",".") }}</span></td>
            <td class="qty"><span>{{ '&euro; '.number_format(CalculationOverview::equipmentActivityProfit($activity, $project->profit_calc_contr_equip), 2, ",",".") }}</span></td>
            <td class="qty"><span>{{ '&euro; '.number_format(CalculationOverview::activityTotalProfit($project->hour_rate, $activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip), 2, ",",".") }} </td>
            <td class="qty text-center">
            <?php
              if (PartType::find($activity->part_type_id)->type_name=='estimate') {
                echo "<strong>Ja</strong>";
              }
            ?>
            </td>
          </tr>
          @endforeach
          @endforeach
          @foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
          @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->get() as $activity)
          <tr><!-- item -->
            <td class="qty"><strong>{{ $chapter->chapter_name }}</strong></td>
            <td class="qty">{{ $activity->activity_name }}</td>
            <td class="qty"><span>{{ number_format(CalculationOverview::laborTotal($activity), 2, ",",".") }}</td>
            <td class="qty"><span class="total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::laborActivity($project->hour_rate, $activity), 2, ",",".") }}</span></td>
            <td class="qty"><span class="total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::materialActivityProfit($activity, $project->profit_calc_subcontr_mat), 2, ",",".") }}</span></td>
            <td class="qty"><span>{{ '&euro; '.number_format(CalculationOverview::equipmentActivityProfit($activity, $project->profit_calc_subcontr_equip), 2, ",",".") }}</span></td>
            <td class="qty"><span>{{ '&euro; '.number_format(CalculationOverview::activityTotalProfit($project->hour_rate, $activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip), 2, ",",".") }} </td>
            <td class="qty text-center">
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
            <th style="width: 300px" class="qty">&nbsp;</th>
            <th style="width: 40px" class="qty">Uren</th>
            <th style="width: 51px" class="qty">Arbeid</th>
            <th style="width: 51px" class="qty">Materiaal</th>
            <th style="width: 51px" class="qty">Materieel</th>
            <th style="width: 51px" class="qty">Totaal</th>
            <th style="width: 51px" class="qty">&nbsp;</th>
          </tr>
        </thead>
        <tbody>
          <td class="qty">&nbsp;</td>
          <td class="qty"><span>{{ CalculationOverview::laborSuperTotalAmount($project) }}</span></td>
          <td class="qty"><span>{{ '&euro; '.number_format(CalculationOverview::laborSuperTotal($project), 2, ",",".") }}</span></td>
          <td class="qty"><span>{{ '&euro; '.number_format(CalculationOverview::materialSuperTotal($project), 2, ",",".") }}</span></td>
          <td class="qty"><span>{{ '&euro; '.number_format(CalculationOverview::equipmentSuperTotal($project), 2, ",",".") }}</span></td>
          <td class="qty"><span>{{ '&euro; '.number_format(CalculationOverview::superTotal($project), 2, ",",".") }}</span></td>
          <td class="qty">&nbsp;</td>
        </tbody>
      </table>
      @else

      <!--PAGE HEADER SECOND START-->
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
      <!--PAGE HEADER SECOND END-->

    <h1 class="name">Totalen per project</h1>
    <h4 class="name">AANNEMING</h4>
    <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr style="page-break-after: always;">
          <th style="width: 130px" class="qty">Hoofdstuk</th>
          <th style="width: 170px" class="qty">Werkzaamheid</th>
          <th style="width: 40px" class="qty">@if (!$onlyactivity) Uren @endif</th>
          <th style="width: 51px" class="qty">@if (!$onlyactivity) Arbeid @endif</th>
          <th style="width: 51px" class="qty">@if (!$onlyactivity) Materiaal @endif</th>
          <th style="width: 51px" class="qty">@if (!$onlyactivity) Materieel @endif</th>
          <th style="width: 51px" class="qty">Totaal</th>
          <th style="width: 51px" class="qty">Stelpost</th>
         </tr>
      </thead>
      <tbody>
      @foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
      @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->get() as $activity)
        <tr style="page-break-after: always;">
          <td class="qty">{{ $chapter->chapter_name }}</td>
          <td class="qty">{{ $activity->activity_name }}</td>
          <td class="qty">@if (!$onlyactivity)<span>{{ number_format(CalculationOverview::laborTotal($activity), 2, ",",".") }}@endif</td>
          <td class="qty">@if (!$onlyactivity)<span class="total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::laborActivity($project->hour_rate, $activity), 2, ",",".") }}</span>@endif</td>
          <td class="qty">@if (!$onlyactivity)<span class="total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::materialActivityProfit($activity, $project->profit_calc_contr_mat), 2, ",",".") }}</span>@endif</td>
          <td class="qty">@if (!$onlyactivity)<span>{{ '&euro; '.number_format(CalculationOverview::equipmentActivityProfit($activity, $project->profit_calc_contr_equip), 2, ",",".") }}</span>@endif</td>
          <td class="qty"><span>{{ '&euro; '.number_format(CalculationOverview::activityTotalProfit($project->hour_rate, $activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip), 2, ",",".") }}</td>
          <td class="qty text-center">
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
          <td class="qty"><strong>Totaal</strong></td>
          <td class="qty">&nbsp;</td>
          <td class="qty">@if (!$onlyactivity)<strong><span>{{ CalculationOverview::contrLaborTotalAmount($project) }}</span></strong>@endif</td>
          <td class="qty">@if (!$onlyactivity)<strong><span>{{ '&euro; '.number_format(CalculationOverview::contrLaborTotal($project), 2, ",",".") }}</span></strong>@endif</td>
          <td class="qty">@if (!$onlyactivity)<strong><span>{{ '&euro; '.number_format(CalculationOverview::contrMaterialTotal($project), 2, ",",".") }}</span></strong>@endif</td>
          <td class="qty">@if (!$onlyactivity)<strong><span>{{ '&euro; '.number_format(CalculationOverview::contrEquipmentTotal($project), 2, ",",".") }}</span></strong>@endif</td>
          <td class="qty"><strong><span>{{ '&euro; '.number_format(CalculationOverview::contrTotal($project), 2, ",",".") }}</span></strong></td>
          <td class="qty">&nbsp;</td>
        </tr>
      </tbody>
    </table>

    <h4 class="name">ONDERAANNEMING</h4>
    <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr style="page-break-after: always;">
          <th style="width: 130px" class="qty">Hoofdstuk</th>
          <th style="width: 170px" class="qty">Werkzaamheid</th>
          <th style="width: 40px" class="qty">@if (!$onlyactivity) Uren @endif</th>
          <th style="width: 51px" class="qty">@if (!$onlyactivity) Arbeid @endif</th>
          <th style="width: 51px" class="qty">@if (!$onlyactivity) Materiaal @endif</th>
          <th style="width: 51px" class="qty">@if (!$onlyactivity) Materieel @endif</th>
          <th style="width: 51px" class="qty">Totaal</th>
          <th style="width: 51px" class="qty">Stelpost</th>
         </tr>
      </thead>
      <tbody>
        @foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
        @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->get() as $activity)
        <tr style="page-break-after: always;">
          <td class="qty">{{ $chapter->chapter_name }}</td>
          <td class="qty">{{ $activity->activity_name }}</td>
          <td class="qty">@if (!$onlyactivity)<span>{{ number_format(CalculationOverview::laborTotal($activity), 2, ",",".") }}@endif</td>
          <td class="qty">@if (!$onlyactivity)<span class="total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::laborActivity($project->hour_rate, $activity), 2, ",",".") }}</span>@endif</td>
          <td class="qty">@if (!$onlyactivity)<span class="total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::materialActivityProfit($activity, $project->profit_calc_subcontr_mat), 2, ",",".") }}</span>@endif</td>
          <td class="qty">@if (!$onlyactivity)<span>{{ '&euro; '.number_format(CalculationOverview::equipmentActivityProfit($activity, $project->profit_calc_subcontr_equip), 2, ",",".") }}</span>@endif</td>
          <td class="qty"><span>{{ '&euro; '.number_format(CalculationOverview::activityTotalProfit($project->hour_rate, $activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip), 2, ",",".") }} </td>
          <td class="qty text-center">
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
          <td class="qty"><strong>Totaal</strong></td>
          <td class="qty">&nbsp;</td>
          <td class="qty">@if (!$onlyactivity)<strong><span>{{ CalculationOverview::subcontrLaborTotalAmount($project) }}</span></strong>@endif</td>
          <td class="qty">@if (!$onlyactivity)<strong><span>{{ '&euro; '.number_format(CalculationOverview::subcontrLaborTotal($project), 2, ",",".") }}</span></strong>@endif</td>
          <td class="qty">@if (!$onlyactivity)<strong><span>{{ '&euro; '.number_format(CalculationOverview::subcontrMaterialTotal($project), 2, ",",".") }}</span></strong>@endif</td>
          <td class="qty">@if (!$onlyactivity)<strong><span>{{ '&euro; '.number_format(CalculationOverview::subcontrEquipmentTotal($project), 2, ",",".") }}</span></strong>@endif</td>
          <td class="qty"><strong><span>{{ '&euro; '.number_format(CalculationOverview::subcontrTotal($project), 2, ",",".") }}</span></strong></td>
          <td class="qty">&nbsp;</td>
        </tr>
      </tbody>
    </table>

     <h1 class="name">Totalen per project</h1>
     <table border="0" cellspacing="0" cellnpadding="0">
        <thead>
          <tr style="page-break-after: always;">
            <th style="width: 130px" class="qty"class="qty">&nbsp;</th>
            <th style="width: 170px" class="qty"class="qty">&nbsp;</th>
            <th style="width: 40px" class="qty"class="qty">@if (!$onlyactivity) Uren @endif</th>
            <th style="width: 51px" class="qty"class="qty">@if (!$onlyactivity) Arbeid @endif</th>
            <th style="width: 51px" class="qty"class="qty">@if (!$onlyactivity) Materiaal @endif</th>
            <th style="width: 51px" class="qty"class="qty">@if (!$onlyactivity) Materieel @endif</th>
            <th style="width: 51px" class="qty"class="qty">Totaal</th>
            <th style="width: 51px" class="qty"class="qty">&nbsp;</th>
          </tr>
        </thead>
        <tbody>
          <tr style="page-break-after: always;">
            <td class="qty">&nbsp;</td>
            <td class="qty">&nbsp;</td>
            <td class="qty">@if (!$onlyactivity)<span>{{ CalculationOverview::laborSuperTotalAmount($project) }}</span>@endif</td>
            <td class="qty">@if (!$onlyactivity)<span>{{ '&euro; '.number_format(CalculationOverview::laborSuperTotal($project), 2, ",",".") }}</span>@endif</td>
            <td class="qty">@if (!$onlyactivity)<span>{{ '&euro; '.number_format(CalculationOverview::materialSuperTotal($project), 2, ",",".") }}</span>@endif</td>
            <td class="qty">@if (!$onlyactivity)<span>{{ '&euro; '.number_format(CalculationOverview::equipmentSuperTotal($project), 2, ",",".") }}</span>@endif</td>
            <td class="qty"><span>{{ '&euro; '.number_format(CalculationOverview::superTotal($project), 2, ",",".") }}</span></td>
            <td class="qty">&nbsp;</td>
          </tr>
      </table>
      @endif
      @endif

    @if ($description)
    @if ($total)

      <!--PAGE HEADER SECOND START-->
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
      <!--PAGE HEADER SECOND END-->

    <h1 class="name">Omschrijving werkzaamheden</h1>
    <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr>
          <th style="width: 130px" class="qty">Hoofdstuk</th>
          <th style="width: 170px" class="qty">Werkzaamheid</th>
          <th class="qty">Omschrijving</th>
        </tr>
      </thead>
      <tbody>
        @foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
        @foreach (Activity::where('chapter_id','=', $chapter->id)->get() as $activity)
        <tr><!-- item -->
          <td class="qty"><strong>{{ $chapter->chapter_name }}</strong></td>
          <td class="qty">{{ $activity->activity_name }}</td>
          <td class="qty"><br><span>{{ $activity->note }}</td>
        </tr>
        @endforeach
        @endforeach
      </tbody>
    </table>
    @else

      <!--PAGE HEADER SECOND START-->
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
      <!--PAGE HEADER SECOND END-->

    <h1 class="name">Omschrijving werkzaamheden</h1>
    <h4 class="name">AANNEMING</h4>
    <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr>
          <th style="width: 130px"class="qty">Hoofdstuk</th>
          <th style="width: 170px"class="qty">Werkzaamheid</th>
          <th class="qty">Omschrijving</th>
        </tr>
      </thead>
      <tbody>
        @foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
        @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->get() as $activity)
        <tr>
          <td class="qty"><strong>{{ $chapter->chapter_name }}</strong></td>
          <td class="qty">{{ $activity->activity_name }}</td>
          <td class="qty"><br><span>{{ $activity->note }}</td>
        </tr>
        @endforeach
        @endforeach
      </tbody>
    </table>
     <h4 class="name">ONDERAANNEMING</h4>
    <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr>
          <th style="width: 130px" class="qty">Hoofdstuk</th>
          <th style="width: 170px" class="qty">Werkzaamheid</th>
          <th class="qty">Omschrijving</th>
        </tr>
      </thead>
      <tbody>
        @foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
        @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->get() as $activity)
        <tr><!-- item -->
          <td class="qty"><strong>{{ $chapter->chapter_name }}</strong></td>
          <td class="qty">{{ $activity->activity_name }}</td>
          <td class="qty"><br><span>{{ $activity->note }}</td>
        </tr>
        @endforeach
        @endforeach
      </tbody>
    </table>
    @endif
    @endif
  </body>
</html>
