<?php
$total=Input::get("total");
$specification=Input::get("specification");
$description=Input::get("description");
$term=Input::get("term");
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

  <!--PAGE HEADER MASTER START-->
  <header class="clearfix">
    <div id="logo">
    <?php if ($relation_self && $relation_self->logo_id) echo "<img src=\"".asset(Resource::find($relation_self->logo_id)->file_location)."\"/>"; ?>
    </div>
    <div id="company">
      <h3 class="name">{{ $relation_self->company_name }}</h3>
      <div>{{ $relation_self->address_street . ' ' . $relation_self->address_number }}</div>
      <div>{{ $relation_self->address_postal . ', ' . $relation_self->address_city }}</div>
      <div>Email:{{ $relation_self->email }}</div>
      <div>KVK:{{ $relation_self->kvk }}</li>
  </header>
  <!--PAGE HEADER MASTER END-->

  <!--ADRESSING START-->
  <main>
    <div id="details" class="clearfix">
      <div id="client">
        <div>{{ $relation->company_name }}</div>
        <div>t.a.v. XXXXX</div>
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
    <!--ADRESSING END-->

    <div class="openingtext">Geachte</div>
    <div class="openingtext">{{ ($invoice ? $invoice->description : '') }}</div>

    <h1 class="name">Cumulatieven termijnfactuur</h1>
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
          <td class="qty">{{Invoice::where('offer_id','=', $invoice->offer_id)->where('priority','<',$invoice->priority)->count()+1}} factuur van in totaal {{Invoice::where('offer_id','=', $invoice->offer_id)->count()}} betalingstermijnen.</td>
          <td class="qty">{{ '&euro; '.number_format($invoice->amount, 2, ",",".") }}</td>
          <td class="qty">&nbsp;</td>
          <td class="qty">&nbsp;</td>
        </tr>
        <tr>
          <td class="qty">Factuurbedrag in 21% BTW cattegorie</td>
          <td class="qty">{{ '&euro; '.number_format($invoice->rest_21, 2, ",",".") }}</td>
          <td class="qty">&nbsp;</td>
          <td class="qty">&nbsp;</td>
        </tr>
        <tr>
          <td class="qty">Factuurbedrag in 6% BTW cattegorie</td>
          <td class="qty">{{ '&euro; '.number_format($invoice->rest_6, 2, ",",".") }}</td>
          <td class="qty">&nbsp;</td>
          <td class="qty">&nbsp;</td>
        </tr>
        <tr>
          <td class="qty">Factuurbedrag in 0% BTW cattegorie</td>
          <td class="qty">{{ '&euro; '.number_format($invoice->rest_0, 2, ",",".") }}</td>
          <td class="qty">&nbsp;</td>
          <td class="qty">&nbsp;</td>
        </tr>
        <tr>
          <td class="qty">BTW bedrag belast met 21%</td>
          <td class="qty">&nbsp;</td>
          <td class="qty">{{ '&euro; '.number_format(($invoice->rest_21/100)*21, 2, ",",".") }}</td>
          <td class="qty">&nbsp;</td>
        </tr>
        <tr>
          <td class="qty">BTW bedrag belast met 6%</td>
          <td class="qty">&nbsp;</td>
          <td class="qty">{{ '&euro; '.number_format(($invoice->rest_6/100)*6, 2, ",",".") }}</td>
          <td class="qty">&nbsp;</td>
        </tr>
        <tr>
          <td class="qty"><strong>Calculatief te factureren (Incl. BTW)</strong></td>
          <td class="qty">&nbsp;</td>
          <td class="qty">&nbsp;</td>
          <td class="qty"><strong>{{ '&euro; '.number_format($invoice->amount+(($invoice->rest_21/100)*21)+(($invoice->rest_6/100)*6), 2, ",",".") }}</strong></td>
        </tr>
      </tbody>
    </table>

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

  </body>
</html>
