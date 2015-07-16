<?php
$total=Input::get("total");
$specification=Input::get("specification");
$description=Input::get("description");
$term=Input::get("term");
$c=false;




$project = Project::find(Route::Input('project_id'));
$relation = Relation::find($project->client_id);
$relation_self = Relation::find(Auth::user()->self_id);
  if ($relation_self)
$contact_self = Contact::where('relation_id','=',$relation_self->id);
$invoice = Invoice::find(Route::Input('invoice_id'));
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
          <div>t.a.v. XXXXX</div>
          <div>{{ $relation->address_street . ' ' . $relation->address_number }}</div>
          <div>{{ $relation->address_postal . ', ' . $relation->address_city }}</div>
        </div>
        <div id="invoice">
          <h3 class="name">{{ InvoiceController::getInvoiceCode($project->id) }}</h3>
          <div class="date">{{ $project->project_name }}</div>
          <div class="date">Factuurnummer: {{ $invoice->invoice_code }}</div>
          <div class="date">Uw referentie: {{ $invoice->reference }}</div>
          <div class="date">Boekhoudkundignummer: {{ $invoice->book_code }}</div>
          <div class="date">Factuurdatum: {{ date("j M Y") }}</div>
        </div>
      </div>

      <div class="openingtext">Geachte</div>
      <div class="openingtext">{{ ($invoice ? $invoice->description : '') }}</div>

      <h1 class="name">totalkosten project</h1>
      @if ($total)
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
          <tr style="page-break-after: always;">
            <td class="qty">&nbsp;</td>
            <td class="qty">{{ '&euro; '.number_format(EstimateEndresult::conCalcLaborActivityTax3Amount($project)+EstimateEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="qty">{{ '&euro; '.number_format(MoreEndresult::conCalcLaborActivityTax3Amount($project)+MoreEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="qty">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax3Amount($project)+LessEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax3($project)+ResultEndresult::subconLaborBalanceTax3($project), 2, ",",".") }}</td>
            <td class="qty">0%</td>
            <td class="qty">&nbsp;</td>
          </tr>
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
          <tr style="page-break-after: always;">
            <td class="qty">&nbsp;</td>
            <td class="qty">{{ '&euro; '.number_format(EstimateEndresult::conCalcMaterialActivityTax3Amount($project)+EstimateEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="qty">{{ '&euro; '.number_format(MoreEndresult::conCalcMaterialActivityTax3Amount($project)+MoreEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="qty">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax3Amount($project)+LessEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax3($project)+ResultEndresult::subconMaterialBalanceTax3($project), 2, ",",".") }}</td>
            <td class="qty">0%</td>
            <td class="qty">&nbsp;</td>
          </tr>
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
          <tr style="page-break-after: always;">
            <td class="qty">&nbsp;</td>
            <td class="qty">{{ '&euro; '.number_format(EstimateEndresult::conCalcEquipmentActivityTax3Amount($project)+EstimateEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="qty">{{ '&euro; '.number_format(MoreEndresult::conCalcEquipmentActivityTax3Amount($project)+MoreEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="qty">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax3Amount($project)+LessEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
            <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax3($project)+ResultEndresult::subconEquipmentBalanceTax3($project), 2, ",",".") }}</td>
            <td class="qty">0%</td>
            <td class="qty">&nbsp;</td>
          </tr>
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

      <h1 class="name">Cumulatieven factuur</h1>
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


         @if ($term)
          <?php
          $cnt = Invoice::where('offer_id','=', $invoice->offer_id)->count();
          if ($cnt>1) {
          ?>
          <h4>Reeds betaald</h4>
          <table class="table table-striped hide-btw2">
            <?# -- table head -- ?>
            <thead>
              <tr>
                <th class="qty">&nbsp;</th>
                <th class="qty">Bedrag (excl. BTW)</th>
                <th class="qty">BTW bedrag</th>
                <th class="qty">Bedrag (incl. BTW);</th>
              </tr>
            </thead>

            <!-- table items -->
            <tbody>
              <tr><!-- item -->
                <td class="qty">1e termijnbedrag van in totaal 3 betalingstermijnen (excl. BTW)</td>
                <td class="qty">{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('amount'), 2, ",",".") }}</td>
                <td class="qty">&nbsp;</td>
                <td class="qty">&nbsp;</td>
              </tr>

              <tr><!-- item -->
                <td class="qty">Factuurbedrag in 21% BTW cattegorie</td>
                <td class="qty">{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('rest_21'), 2, ",",".") }}</td>
                <td class="qty">&nbsp;</td>
                <td class="qty">&nbsp;</td>
              </tr>
              <tr><!-- item -->
                <td class="qty">Factuurbedrag in 6% BTW cattegorie</td>
                <td class="qty">{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('rest_6'), 2, ",",".") }}</td>
                <td class="qty">&nbsp;</td>
                <td class="qty">&nbsp;</td>
              </tr>
              <tr><!-- item -->
                <td class="qty">Factuurbedrag in 0% BTW cattegorie</td>
                <td class="qty">{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('rest_0'), 2, ",",".") }}</td>
                <td class="qty">&nbsp;</td>
                <td class="qty">&nbsp;</td>
              </tr>

              <tr><!-- item -->
                <td class="qty">BTW bedrag belast met 21%</td>
                <td class="qty">&nbsp;</td>
                <td class="qty">{{ '&euro; '.number_format((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('rest_21')/100)*21, 2, ",",".") }}</td>
                <td class="qty">&nbsp;</td>
              </tr>
              <tr><!-- item -->
                <td class="qty">BTW bedrag belast met 6%</td>
                <td class="qty">&nbsp;</td>
                <td class="qty">{{ '&euro; '.number_format((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('rest_6')/100)*6, 2, ",",".") }}</td>
                <td class="qty">&nbsp;</td>
              </tr>
              <tr><!-- item -->
                <td class="qty"><strong>Calculatief te factureren (Incl. BTW)</strong></td>
                <td class="qty">&nbsp;</td>
                <td class="qty">&nbsp;</td>
                <td class="qty"><strong>{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('amount')+((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('rest_21')/100)*21)+((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('rest_6')/100)*6), 2, ",",".") }}</strong></td>
              </tr>

            </tbody>

          </table>

          <div style="page-break-after:always;"></div>
           @if ($term)
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
           @else if
           @endif

          <h4>Resterend te betalen</h4>
          <table class="table table-striped hide-btw2">
            <?# -- table head -- ?>
            <thead>
              <tr>
                <th class="qty">&nbsp;</th>
                <th class="qty">Bedrag (excl. BTW)</th>
                <th class="qty">BTW bedrag</th>
                <th class="qty">Bedrag (incl. BTW);</th>
              </tr>
            </thead>

            <!-- table items -->
            <tbody>
              <tr><!-- item -->
                <td class="qty">1e termijnbedrag van in totaal 3 betalingstermijnen (excl. BTW)</td>
                <td class="qty">{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->amount, 2, ",",".") }}</td>
                <td class="qty">&nbsp;</td>
                <td class="qty">&nbsp;</td>
              </tr>

              <tr><!-- item -->
                <td class="qty">Factuurbedrag in 21% BTW cattegorie</td>
                <td class="qty">{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->rest_21, 2, ",",".") }}</td>
                <td class="qty">&nbsp;</td>
                <td class="qty">&nbsp;</td>
              </tr>
              <tr><!-- item -->
                <td class="qty">Factuurbedrag in 6% BTW cattegorie</td>
                <td class="qty">{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->rest_6, 2, ",",".") }}</td>
                <td class="qty">&nbsp;</td>
                <td class="qty">&nbsp;</td>
              </tr>
              <tr><!-- item -->
                <td class="qty">Factuurbedrag in 0% BTW cattegorie</td>
                <td class="qty">{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->rest_0, 2, ",",".") }}</td>
                <td class="qty">&nbsp;</td>
                <td class="qty">&nbsp;</td>
              </tr>

              <tr><!-- item -->
                <td class="qty">BTW bedrag belast met 21%</td>
                <td class="qty">&nbsp;</td>
                <td class="qty">{{ '&euro; '.number_format((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->rest_21/100)*21, 2, ",",".") }}</td>
                <td class="qty">&nbsp;</td>
              </tr>
              <tr><!-- item -->
                <td class="qty">BTW bedrag belast met 6%</td>
                <td class="qty">&nbsp;</td>
                <td class="qty">{{ '&euro; '.number_format((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->rest_6/100)*6, 2, ",",".") }}</td>
                <td class="qty">&nbsp;</td>
              </tr>
              <tr><!-- item -->
                <td class="qty"><strong>Calculatief te factureren (Incl. BTW)</strong></td>
                <td class="qty">&nbsp;</td>
                <td class="qty">&nbsp;</td>
                <td class="qty"><strong>{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->amount+((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->rest_21/100)*21)+((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->rest_6/100)*6), 2, ",",".") }}</strong></td>
              </tr>

            </tbody>

          </table>
         <?php } ?>
           @endif

      <div class="closingtext">{{ ($offer_last ? $offer_last->closure : '') }}</div>

      <h1 class="name">Bepalingen</h1>
      <div class="statements">
        <li>Deze factuur dient betaald te worden binnen {{ $invoice->payment_condition }} dagen na dagtekening.</li>
      </div>
      <div class="signing">Met vriendelijke groet,</div>
      <div class="signing">Mijn Naam</div>
    </main>

    <footer>
      Deze factuur is op de computer gegenereerd en is geldig zonder handtekening.
    </footer>
@else
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
          <tr style="page-break-after: always;">
                  <td class="qty">&nbsp;</td>
                  <td class="qty">{{ '&euro; '.number_format(EstimateEndresult::conCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
                  <td class="qty">{{ '&euro; '.number_format(MoreEndresult::conCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
                  <td class="qty">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
                  <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax3($project), 2, ",",".") }}</td>
                  <td class="qty">0%</td>
                  <td class="qty">&nbsp;</td>
          </tr>
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
          <tr style="page-break-after: always;">
                  <td class="qty">&nbsp;</td>
                  <td class="qty">{{ '&euro; '.number_format(EstimateEndresult::conCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
                  <td class="qty">{{ '&euro; '.number_format(MoreEndresult::conCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
                  <td class="qty">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
                  <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax3($project), 2, ",",".") }}</td>
                  <td class="qty">0%</td>
                  <td class="qty">&nbsp;</td>
          </tr>
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
          <tr style="page-break-after: always;">
                  <td class="qty">&nbsp;</td>
                  <td class="qty">{{ '&euro; '.number_format(EstimateEndresult::conCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
                  <td class="qty">{{ '&euro; '.number_format(MoreEndresult::conCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
                  <td class="qty">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
                  <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax3($project), 2, ",",".") }}</td>
                  <td class="qty">0%</td>
                  <td class="qty">&nbsp;</td>
          </tr>
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
          <tr style="page-break-after: always;">
                  <td class="qty">&nbsp;</td>
                  <td class="qty">{{ '&euro; '.number_format(EstimateEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
                  <td class="qty">{{ '&euro; '.number_format(MoreEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
                  <td class="qty">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
                  <td class="qty">{{ '&euro; '.number_format(ResultEndresult::subconLaborBalanceTax3($project), 2, ",",".") }}</td>
                  <td class="qty">0%</td>
                  <td class="qty">&nbsp;</td>
          </tr>
          <tr style="page-break-after: always;">
                  <td class="qty">&nbsp;</td>
                  <td class="qty">{{ '&euro; '.number_format(EstimateEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
                  <td class="qty">{{ '&euro; '.number_format(MoreEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
                  <td class="qty">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
                  <td class="qty">{{ '&euro; '.number_format(ResultEndresult::subconLaborBalanceTax3($project), 2, ",",".") }}</td>
                  <td class="qty">0%</td>
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
          <tr style="page-break-after: always;">
                  <td class="qty">&nbsp;</td>
                  <td class="qty">{{ '&euro; '.number_format(EstimateEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
                  <td class="qty">{{ '&euro; '.number_format(MoreEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
                  <td class="qty">{{ '&euro; '.number_format(LessEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
                  <td class="qty">{{ '&euro; '.number_format(ResultEndresult::subconMaterialBalanceTax3($project), 2, ",",".") }}</td>
                  <td class="qty">0%</td>
                  <td class="qty">&nbsp;</td>
          </tr>
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
          <tr style="page-break-after: always;">
                  <td class="qty">&nbsp;</td>
                  <td class="qty">{{ '&euro; '.number_format(EstimateEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
                  <td class="qty">{{ '&euro; '.number_format(MoreEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
                  <td class="qty">{{ '&euro; '.number_format(LessEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
                  <td class="qty">{{ '&euro; '.number_format(ResultEndresult::subconEquipmentBalanceTax3($project), 2, ",",".") }}</td>
                  <td class="qty">0%</td>
                  <td class="qty">&nbsp;</td>
          </tr>
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

      <h1 class="name">Cumulatieven offerte</h1>
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
          <tr style="page-break-after: always;">
            <td class="qty">Te offereren BTW bedrag</td>
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

      <div style="page-break-after:always;"></div>
        <header class="clearfix">
        <div id="logo">
      <?php if ($relation_self && $relation_self->logo_id) echo "<img src=\"".asset(Resource::find($relation_self->logo_id)->file_location)."\"/>"; ?>
        </div>>
             <div id="invoice">
              <h3 class="name">{{ OfferController::getOfferCode($project->id) }}</h3>
              <div class="date">{{ $project->project_name }}</div>
              <div class="date">{{ date("j M Y") }}</div>
            </div>
        </header>




      @if ($term)
          <?php
          $cnt = Invoice::where('offer_id','=', $invoice->offer_id)->count();
          if ($cnt>1) {
          ?>
          <h4>Reeds betaald</h4>
          <table class="table table-striped hide-btw2">
            <?# -- table head -- ?>
            <thead>
              <tr>
                <th class="qty">&nbsp;</th>
                <th class="qty">Bedrag (excl. BTW)</th>
                <th class="qty">BTW bedrag</th>
                <th class="qty">Bedrag (incl. BTW);</th>
              </tr>
            </thead>

            <!-- table items -->
            <tbody>
              <tr><!-- item -->
                <td class="qty">1e termijnbedrag van in totaal 3 betalingstermijnen (excl. BTW)</td>
                <td class="qty">{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('amount'), 2, ",",".") }}</td>
                <td class="qty">&nbsp;</td>
                <td class="qty">&nbsp;</td>
              </tr>

              <tr><!-- item -->
                <td class="qty">Factuurbedrag in 21% BTW cattegorie</td>
                <td class="qty">{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('rest_21'), 2, ",",".") }}</td>
                <td class="qty">&nbsp;</td>
                <td class="qty">&nbsp;</td>
              </tr>
              <tr><!-- item -->
                <td class="qty">Factuurbedrag in 6% BTW cattegorie</td>
                <td class="qty">{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('rest_6'), 2, ",",".") }}</td>
                <td class="qty">&nbsp;</td>
                <td class="qty">&nbsp;</td>
              </tr>
              <tr><!-- item -->
                <td class="qty">Factuurbedrag in 0% BTW cattegorie</td>
                <td class="qty">{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('rest_0'), 2, ",",".") }}</td>
                <td class="qty">&nbsp;</td>
                <td class="qty">&nbsp;</td>
              </tr>

              <tr><!-- item -->
                <td class="qty">BTW bedrag belast met 21%</td>
                <td class="qty">&nbsp;</td>
                <td class="qty">{{ '&euro; '.number_format((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('rest_21')/100)*21, 2, ",",".") }}</td>
                <td class="qty">&nbsp;</td>
              </tr>
              <tr><!-- item -->
                <td class="qty">BTW bedrag belast met 6%</td>
                <td class="qty">&nbsp;</td>
                <td class="qty">{{ '&euro; '.number_format((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('rest_6')/100)*6, 2, ",",".") }}</td>
                <td class="qty">&nbsp;</td>
              </tr>
              <tr><!-- item -->
                <td class="qty"><strong>Calculatief te factureren (Incl. BTW)</strong></td>
                <td class="qty">&nbsp;</td>
                <td class="qty">&nbsp;</td>
                <td class="qty"><strong>{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('amount')+((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('rest_21')/100)*21)+((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('rest_6')/100)*6), 2, ",",".") }}</strong></td>
              </tr>

            </tbody>

          </table>

          <h4>Resterend te betalen</h4>
          <table class="table table-striped hide-btw2">
            <?# -- table head -- ?>
            <thead>
              <tr>
                <th class="qty">&nbsp;</th>
                <th class="qty">Bedrag (excl. BTW)</th>
                <th class="qty">BTW bedrag</th>
                <th class="qty">Bedrag (incl. BTW);</th>
              </tr>
            </thead>

            <!-- table items -->
            <tbody>
              <tr><!-- item -->
                <td class="qty">1e termijnbedrag van in totaal 3 betalingstermijnen (excl. BTW)</td>
                <td class="qty">{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->amount, 2, ",",".") }}</td>
                <td class="qty">&nbsp;</td>
                <td class="qty">&nbsp;</td>
              </tr>

              <tr><!-- item -->
                <td class="qty">Factuurbedrag in 21% BTW cattegorie</td>
                <td class="qty">{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->rest_21, 2, ",",".") }}</td>
                <td class="qty">&nbsp;</td>
                <td class="qty">&nbsp;</td>
              </tr>
              <tr><!-- item -->
                <td class="qty">Factuurbedrag in 6% BTW cattegorie</td>
                <td class="qty">{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->rest_6, 2, ",",".") }}</td>
                <td class="qty">&nbsp;</td>
                <td class="qty">&nbsp;</td>
              </tr>
              <tr><!-- item -->
                <td class="qty">Factuurbedrag in 0% BTW cattegorie</td>
                <td class="qty">{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->rest_0, 2, ",",".") }}</td>
                <td class="qty">&nbsp;</td>
                <td class="qty">&nbsp;</td>
              </tr>

              <tr><!-- item -->
                <td class="qty">BTW bedrag belast met 21%</td>
                <td class="qty">&nbsp;</td>
                <td class="qty">{{ '&euro; '.number_format((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->rest_21/100)*21, 2, ",",".") }}</td>
                <td class="qty">&nbsp;</td>
              </tr>
              <tr><!-- item -->
                <td class="qty">BTW bedrag belast met 6%</td>
                <td class="qty">&nbsp;</td>
                <td class="qty">{{ '&euro; '.number_format((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->rest_6/100)*6, 2, ",",".") }}</td>
                <td class="qty">&nbsp;</td>
              </tr>
              <tr><!-- item -->
                <td class="qty"><strong>Calculatief te factureren (Incl. BTW)</strong></td>
                <td class="qty">&nbsp;</td>
                <td class="qty">&nbsp;</td>
                <td class="qty"><strong>{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->amount+((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->rest_21/100)*21)+((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->rest_6/100)*6), 2, ",",".") }}</strong></td>
              </tr>

            </tbody>

          </table>
          <?php } ?>
          @endif

      <div class="closingtext">{{ ($offer_last ? $offer_last->closure : '') }}</div>

      <h1 class="name">Bepalingen</h1>
      <div class="statements">
        <li>Deze factuur dient betaald te worden binnen {{ $invoice->payment_condition }} dagen na dagtekening.</li>
      </div>
      <div class="signing">Met vriendelijke groet,</div>
      <div class="signing">Mijn Naam</div>
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

     <h1 class="name">totalkosten per werkzaamheid</h1>
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
