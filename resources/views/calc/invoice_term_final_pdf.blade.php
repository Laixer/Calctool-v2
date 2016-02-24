<?php

use \Calctool\Models\Project;
use \Calctool\Models\Relation;
use \Calctool\Models\Contact;
use \Calctool\Models\Invoice;
use \Calctool\Models\Offer;
use \Calctool\Models\ProjectType;
use \Calctool\Models\Resource;

$offer = Offer::find($invoice->offer_id);
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

$include_tax = $invoice->include_tax; //BTW bedragen weergeven

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Termijnfactuur</title>
    <link rel="stylesheet" href="{{ asset('css/pdf.css') }}" media="all" />
  </head>
  <body>

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
        <h3 class="name">TERMIJNFACTUUR</h3>
        <div class="date">Projectnaam: {{ $project->project_name }}</div>
        <div class="date">Factuurnummer: {{ $invoice->invoice_code }}</div>
        <div class="date">Uw referentie: {{ $invoice->reference }}</div>
        <div class="date">Boekhoudkundignummer: {{ $invoice->book_code }}</div>
        <div class="date">Factuurdatum: {{ date("j M Y") }}</div>
      </div>
    </div>
    <?#--ADRESSING END--?>

    <div class="openingtext">Geachte {{ Contact::find($invoice->to_contact_id)->getFormalName() }},</div>
    <div class="openingtext">{{ ($invoice ? $invoice->description : '') }}</div>

    <h1 class="name">Specificatie termijnfactuur</h1>
    <table class="table table-striped hide-btw2">
      <thead>
        <tr>
          <th class="qty">&nbsp;</th>
          <th class="qty">Bedrag (excl. BTW)</th>
          <th class="qty">@if ($include_tax)BTW bedrag @endif</th>
          <th class="qty">@if ($include_tax)Bedrag (incl. BTW) @endif</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="qty">{{Invoice::where('offer_id','=', $invoice->offer_id)->where('priority','<',$invoice->priority)->count()}}e van in totaal {{Invoice::where('offer_id','=', $invoice->offer_id)->count()}} betalingstermijnen.</td>
          <td class="qty">{{ '&euro; '.number_format($invoice->amount, 2, ",",".") }}</td>
          <td class="qty">&nbsp;</td>
          <td class="qty">&nbsp;</td>
        </tr>
        @if ($include_tax)
        @if (!$project->tax_reverse)
        <tr>
          <td class="qty">&nbsp;<i>Aandeel termijnfactuur in 21% BTW categorie</i></td>
          <td class="qty">{{ '&euro; '.number_format($invoice->rest_21, 2, ",",".") }}</td>
          <td class="qty">&nbsp;</td>
          <td class="qty">&nbsp;</td>
        </tr>
        <tr>
          <td class="qty">&nbsp;<i>Aandeel termijnfactuur in 6% BTW categorie</i></td>
          <td class="qty">{{ '&euro; '.number_format($invoice->rest_6, 2, ",",".") }}</td>
          <td class="qty">&nbsp;</td>
          <td class="qty">&nbsp;</td>
        </tr>
        @else
        <tr>
          <td class="qty">&nbsp;<i>Aandeel termijnfactuur in 0% BTW categorie</i></td>
          <td class="qty">{{ '&euro; '.number_format($invoice->rest_0, 2, ",",".") }}</td>
          <td class="qty">&nbsp;</td>
          <td class="qty">&nbsp;</td>
        </tr>
        @endif

        @if (!$project->tax_reverse)
        <tr>
          <td class="qty">BTW bedrag 21%</td>
          <td class="qty">&nbsp;</td>
          <td class="qty">{{ '&euro; '.number_format(($invoice->rest_21/100)*21, 2, ",",".") }}</td>
          <td class="qty">&nbsp;</td>
        </tr>
        <tr>
          <td class="qty">BTW bedrag 6%</td>
          <td class="qty">&nbsp;</td>
          <td class="qty">{{ '&euro; '.number_format(($invoice->rest_6/100)*6, 2, ",",".") }}</td>
          <td class="qty">&nbsp;</td>
        </tr>
        @endif

        <tr>
          <td class="qty"><strong>Calculatief te factureren (Incl. BTW)</strong></td>
          <td class="qty">&nbsp;</td>
          <td class="qty">&nbsp;</td>
          <td class="qty"><strong>{{ '&euro; '.number_format($invoice->amount+(($invoice->rest_21/100)*21)+(($invoice->rest_6/100)*6), 2, ",",".") }}</strong></td>
        </tr>
      </tbody>
      @endif
    </table>

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

  </body>
</html>
