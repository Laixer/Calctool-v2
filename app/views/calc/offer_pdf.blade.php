<?php
$totaal=Input::get("totaal");
$specificatie=Input::get("specificatie");
$omschrijving=Input::get("omschrijving");
$c=false;
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
        <img src="{{ asset('images/logo2.png') }}">
      </div>
      <div id="company">
        <h3 class="name">Bedrijfsnaam</h3>
        <div>adres:</div>
        <div>adres:</div>
        <div>Telefoon:</div>
        <div>Email:<a href="mailto:company@example.com">company@example.com</a></div>
      </div>
      </div>
    </header>
    <main>
      <div id="details" class="clearfix">
        <div id="client">
          <div>Relatienaam</div>
          <div>t.a.v. XXXX</div>
          <div>adres 1</div>
          <div>adres 2</div>
        </div>
        <div id="invoice">
          <h3 class="name">Offertenummer</h3>
          <div class="date">Projectnaam</div>
          <div class="date">Datum offerte</div>
        </div>
      </div>

      <div class="openingtext">Geachte</div>
      <div class="openingtext">
        Omschrijving voor op de offerte mag hier komen te staan.  Dez emag zo groot zijn als je maar wilt maar natuurlijk zitten er grenzen aan.
        Omschrijving voor op de offerte mag hier komen te staan.  Dez emag zo groot zijn als je maar wilt maar natuurlijk zitten er grenzen aan.
        Omschrijving voor op de offerte mag hier komen te staan.  Dez emag zo groot zijn als je maar wilt maar natuurlijk zitten er grenzen aan.
      </div>

      <h1 class="name">Totaalkosten project</h1>
      @if ($totaal)
      <table border="0" cellspacing="0" cellpadding="0">
        <thead>
          <tr style="page-break-after: always;">
            <th class="no">&nbsp;</th>
            <th class="desc">Uren</th>
            <th class="unit">Bedrag (excl. BTW)</th>
            <th class="qty">BTW %</th>
            <th class="qty">BTW bedrag</th>
            <th class="total">Bedrag (incl. BTW)</th>
          </tr>
        </thead>
        <tbody>
          <tr style="page-break-after: always;">
            <td class="no"><strong>Arbeidskosten</strong></td>
            <td class="desc">Uren</td>
            <td class="unit">Bedrag (excl. BTW)</td>
            <td class="qty">BTW %</td>
            <td class="qty">BTW bedrag</td>
            <td class="total">Bedrag (incl. BTW)</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no">&nbsp;</td>
            <td class="desc">Uren</td>
            <td class="unit">Bedrag (excl. BTW)</td>
            <td class="qty">BTW %</td>
            <td class="qty">BTW bedrag</td>
            <td class="total">Bedrag (incl. BTW)</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no">&nbsp;</td>
            <td class="desc">Uren</td>
            <td class="unit">Bedrag (excl. BTW)</td>
            <td class="qty">BTW %</td>
            <td class="qty">BTW bedrag</td>
            <td class="total">Bedrag (incl. BTW)</td>
          </tr>

          <tr style="page-break-after: always;">
            <td class="no"><strong>Materiaalkosten</strong></td>
            <td class="desc">&nbsp;</td>
            <td class="unit">Bedrag (excl. BTW)</td>
            <td class="qty">BTW %</td>
            <td class="qty">BTW bedrag</td>
            <td class="total">Bedrag (incl. BTW)</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no">&nbsp;</td>
            <td class="desc">&nbsp;</td>
            <td class="unit">Bedrag (excl. BTW)</td>
            <td class="qty">BTW %</td>
            <td class="qty">BTW bedrag</td>
            <td class="total">Bedrag (incl. BTW)</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no">&nbsp;</td>
            <td class="desc">&nbsp;</td>
            <td class="unit">Bedrag (excl. BTW)</td>
            <td class="qty">BTW %</td>
            <td class="qty">BTW bedrag</td>
            <td class="total">Bedrag (incl. BTW)</td>
          </tr>

          <tr style="page-break-after: always;">
            <td class="no"><strong>Materieelkosten</strong></td>
            <td class="desc">&nbsp;</td>
            <td class="unit">Bedrag (excl. BTW)</td>
            <td class="qty">BTW %</td>
            <td class="qty">BTW bedrag</td>
            <td class="total">Bedrag (incl. BTW)</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no">&nbsp;</td>
            <td class="desc">&nbsp;</td>
            <td class="unit">Bedrag (excl. BTW)</td>
            <td class="qty">BTW %</td>
            <td class="qty">BTW bedrag</td>
            <td class="total">Bedrag (incl. BTW)</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no">&nbsp;</td>
            <td class="desc">&nbsp;</td>
            <td class="unit">Bedrag (excl. BTW)</td>
            <td class="qty">BTW %</td>
            <td class="qty">BTW bedrag</td>
            <td class="total">Bedrag (incl. BTW)</td>
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
            <td class="unit">Bedrag (excl. BTW)</td>
            <td class="qty">BTW bedrag</td>
            <td class="total">Bedrag (incl. BTW)</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no"><strong>BTW bedrag calculatie belast met 21%</strong>&nbsp;</td>
            <td class="unit">Bedrag (excl. BTW)</td>
            <td class="qty">BTW bedrag</td>
            <td class="total">Bedrag (incl. BTW)</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no"><strong>BTW bedrag calculatie belast met 6%</strong></td>
            <td class="unit">Bedrag (excl. BTW)</td>
            <td class="qty">BTW bedrag</td>
            <td class="total">Bedrag (incl. BTW)</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no"><strong>Te offereren BTW bedrag</strong></td>
            <td class="unit">Bedrag (excl. BTW)</td>
            <td class="qty">BTW bedrag</td>
            <td class="total">Bedrag (incl. BTW)</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no"><strong>Calculatief te offereren (Incl. BTW)</strong></td>
            <td class="unit">Bedrag (excl. BTW)</td>
            <td class="qty">BTW bedrag</td>
            <td class="total">Bedrag (incl. BTW)</td>
          </tr>
        </tbody>
      </table>
      <div class="closingtext">
        Omschrijving voor op de offerte mag hier komen te staan.  Dez emag zo groot zijn als je maar wilt maar natuurlijk zitten er grenzen aan.
        Omschrijving voor op de offerte mag hier komen te staan.  Dez emag zo groot zijn als je maar wilt maar natuurlijk zitten er grenzen aan.
        Omschrijving voor op de offerte mag hier komen te staan.  Dez emag zo groot zijn als je maar wilt maar natuurlijk zitten er grenzen aan.
      </div>

      <h1 class="name">Bepalingen</h1>
      <div class="statements">
        <li>Indien opdracht gegund wordt, ontvangt u één eindfactuur.</li>
        <li>Wij kunnen de werkzaamheden starten binnen   na uw opdrachtbevestiging.</li>
        <li>Deze offerte is geldig tot   na dagtekening.</li>
      </div>
      <div class="signing">Met vriendelijke groet,</div>
      <div class="signing">Mijn Naam</div>
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
            <th class="total">Bedrag (incl. BTW)</th>
          </tr>
        </thead>
        <tbody>
          <tr style="page-break-after: always;">
            <td class="no"><strong>Arbeidskosten</strong></td>
            <td class="desc">Uren</td>
            <td class="unit">Bedrag (excl. BTW)</td>
            <td class="qty">BTW %</td>
            <td class="qty">BTW bedrag</td>
            <td class="total">Bedrag (incl. BTW)</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no">&nbsp;</td>
            <td class="desc">Uren</td>
            <td class="unit">Bedrag (excl. BTW)</td>
            <td class="qty">BTW %</td>
            <td class="qty">BTW bedrag</td>
            <td class="total">Bedrag (incl. BTW)</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no">&nbsp;</td>
            <td class="desc">Uren</td>
            <td class="unit">Bedrag (excl. BTW)</td>
            <td class="qty">BTW %</td>
            <td class="qty">BTW bedrag</td>
            <td class="total">Bedrag (incl. BTW)</td>
          </tr>

          <tr style="page-break-after: always;">
            <td class="no"><strong>Materiaalkosten</strong></td>
            <td class="desc">&nbsp;</td>
            <td class="unit">Bedrag (excl. BTW)</td>
            <td class="qty">BTW %</td>
            <td class="qty">BTW bedrag</td>
            <td class="total">Bedrag (incl. BTW)</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no">&nbsp;</td>
            <td class="desc">&nbsp;</td>
            <td class="unit">Bedrag (excl. BTW)</td>
            <td class="qty">BTW %</td>
            <td class="qty">BTW bedrag</td>
            <td class="total">Bedrag (incl. BTW)</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no">&nbsp;</td>
            <td class="desc">&nbsp;</td>
            <td class="unit">Bedrag (excl. BTW)</td>
            <td class="qty">BTW %</td>
            <td class="qty">BTW bedrag</td>
            <td class="total">Bedrag (incl. BTW)</td>
          </tr>

          <tr style="page-break-after: always;">
            <td class="no"><strong>Materieelkosten</strong></td>
            <td class="desc">&nbsp;</td>
            <td class="unit">Bedrag (excl. BTW)</td>
            <td class="qty">BTW %</td>
            <td class="qty">BTW bedrag</td>
            <td class="total">Bedrag (incl. BTW)</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no">&nbsp;</td>
            <td class="desc">&nbsp;</td>
            <td class="unit">Bedrag (excl. BTW)</td>
            <td class="qty">BTW %</td>
            <td class="qty">BTW bedrag</td>
            <td class="total">Bedrag (incl. BTW)</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no">&nbsp;</td>
            <td class="desc">&nbsp;</td>
            <td class="unit">Bedrag (excl. BTW)</td>
            <td class="qty">BTW %</td>
            <td class="qty">BTW bedrag</td>
            <td class="total">Bedrag (incl. BTW)</td>
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
            <th class="total">Bedrag (incl. BTW)</th>
          </tr>
        </thead>
        <tbody>
          <tr style="page-break-after: always;">
            <td class="no"><strong>Arbeidskosten</strong></td>
            <td class="desc">Uren</td>
            <td class="unit">Bedrag (excl. BTW)</td>
            <td class="qty">BTW %</td>
            <td class="qty">BTW bedrag</td>
            <td class="total">Bedrag (incl. BTW)</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no">&nbsp;</td>
            <td class="desc">Uren</td>
            <td class="unit">Bedrag (excl. BTW)</td>
            <td class="qty">BTW %</td>
            <td class="qty">BTW bedrag</td>
            <td class="total">Bedrag (incl. BTW)</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no">&nbsp;</td>
            <td class="desc">Uren</td>
            <td class="unit">Bedrag (excl. BTW)</td>
            <td class="qty">BTW %</td>
            <td class="qty">BTW bedrag</td>
            <td class="total">Bedrag (incl. BTW)</td>
          </tr>

          <tr style="page-break-after: always;">
            <td class="no"><strong>Materiaalkosten</strong></td>
            <td class="desc">&nbsp;</td>
            <td class="unit">Bedrag (excl. BTW)</td>
            <td class="qty">BTW %</td>
            <td class="qty">BTW bedrag</td>
            <td class="total">Bedrag (incl. BTW)</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no">&nbsp;</td>
            <td class="desc">&nbsp;</td>
            <td class="unit">Bedrag (excl. BTW)</td>
            <td class="qty">BTW %</td>
            <td class="qty">BTW bedrag</td>
            <td class="total">Bedrag (incl. BTW)</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no">&nbsp;</td>
            <td class="desc">&nbsp;</td>
            <td class="unit">Bedrag (excl. BTW)</td>
            <td class="qty">BTW %</td>
            <td class="qty">BTW bedrag</td>
            <td class="total">Bedrag (incl. BTW)</td>
          </tr>

          <tr style="page-break-after: always;">
            <td class="no"><strong>Materieelkosten</strong></td>
            <td class="desc">&nbsp;</td>
            <td class="unit">Bedrag (excl. BTW)</td>
            <td class="qty">BTW %</td>
            <td class="qty">BTW bedrag</td>
            <td class="total">Bedrag (incl. BTW)</td>
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
            <td class="unit">Bedrag (excl. BTW)</td>
            <td class="qty">BTW bedrag</td>
            <td class="total">Bedrag (incl. BTW)</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no"><strong>BTW bedrag calculatie belast met 21%</strong>&nbsp;</td>
            <td class="unit">Bedrag (excl. BTW)</td>
            <td class="qty">BTW bedrag</td>
            <td class="total">Bedrag (incl. BTW)</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no"><strong>BTW bedrag calculatie belast met 6%</strong></td>
            <td class="unit">Bedrag (excl. BTW)</td>
            <td class="qty">BTW bedrag</td>
            <td class="total">Bedrag (incl. BTW)</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no"><strong>Te offereren BTW bedrag</strong></td>
            <td class="unit">Bedrag (excl. BTW)</td>
            <td class="qty">BTW bedrag</td>
            <td class="total">Bedrag (incl. BTW)</td>
          </tr>
          <tr style="page-break-after: always;">
            <td class="no"><strong>Calculatief te offereren (Incl. BTW)</strong></td>
            <td class="unit">Bedrag (excl. BTW)</td>
            <td class="qty">BTW bedrag</td>
            <td class="total">Bedrag (incl. BTW)</td>
          </tr>
        </tbody>
      </table>

    <div style="page-break-after:always;"></div>
    <header class="clearfix">
      <div id="logo">
        <img src="{{ asset('images/logo2.png') }}">
      </div>
         <div id="invoice">
          <h3 class="name">Offertenummer</h3>
          <div class="date">Projectnaam</div>
          <div class="date">Datum offerte</div>
        </div>
      </div>
      </div>
    </header>


     <div class="closingtext">
        Omschrijving voor op de offerte mag hier komen te staan.  Dez emag zo groot zijn als je maar wilt maar natuurlijk zitten er grenzen aan.
        Omschrijving voor op de offerte mag hier komen te staan.  Dez emag zo groot zijn als je maar wilt maar natuurlijk zitten er grenzen aan.
        Omschrijving voor op de offerte mag hier komen te staan.  Dez emag zo groot zijn als je maar wilt maar natuurlijk zitten er grenzen aan.
      </div>

     <h1 class="name">Bepalingen</h1>
      <div class="statements">
        <li>Indien opdracht gegund wordt, ontvangt u één eindfactuur.</li>
        <li>Wij kunnen de werkzaamheden starten binnen   na uw opdrachtbevestiging.</li>
        <li>Deze offerte is geldig tot   na dagtekening.</li>
      </div>
      <div class="signing">Met vriendelijke groet,</div>
      <div class="signing">Mijn Naam</div>
    </main>

    <footer>
      Deze offerte is op de computer gegenereerd en is geldig zonder handtekening.
    </footer>
     @endif













    @if ($specificatie)
    @if ($totaal)
    <div style="page-break-after:always;"></div>
    <header class="clearfix">
      <div id="logo">
        <img src="{{ asset('images/logo2.png') }}">
      </div>
         <div id="invoice">
          <h3 class="name">Offertenummer</h3>
          <div class="date">Projectnaam</div>
          <div class="date">Datum offerte</div>
        </div>
      </div>
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
            <th class="qty">Totaal</th>
            <th class="total">Stelpost</th>
          </tr>
        </thead>
        <tbody>
          <tr style="page-break-after: always;">
            <td class="no">Arbeidskosten</td>
            <td class="desc">Uren</td>
            <td class="no">Arbeidskosten</td>
            <td class="desc">Uren</td>
            <td class="unit">Bedrag (excl. BTW)</td>
            <td class="qty">BTW %</td>
            <td class="qty">BTW bedrag</td>
            <td class="total">Bedrag (incl. BTW)</td>
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
            <th class="qty">Totaal</th>
            <th class="total">Stelpost</th>
          </tr>
        </thead>
        <tbody>
          <tr style="page-break-after: always;">
            <td class="no">&nbsp;</td>
            <td class="desc">&nbsp;</td>
            <td class="no">Arbeidskosten</td>
            <td class="desc">Uren</td>
            <td class="unit">Bedrag (excl. BTW)</td>
            <td class="qty">BTW %</td>
            <td class="qty">BTW bedrag</td>
            <td class="total">Bedrag (incl. BTW)</td>
          </tr>
      </table>
      @else
      <div style="page-break-after:always;"></div>
      <header class="clearfix">
      <div id="logo">
        <img src="{{ asset('images/logo2.png') }}">
      </div>
         <div id="invoice">
          <h3 class="name">Offertenummer</h3>
          <div class="date">Projectnaam</div>
          <div class="date">Datum offerte</div>
        </div>
      </div>
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
            <th class="qty">Totaal</th>
            <th class="total">Stelpost</th>
          </tr>
        </thead>
        <tbody>
          <tr style="page-break-after: always;">
            <td class="no">Arbeidskosten</td>
            <td class="desc">Uren</td>
            <td class="no">Arbeidskosten</td>
            <td class="desc">Uren</td>
            <td class="unit">Bedrag (excl. BTW)</td>
            <td class="qty">BTW %</td>
            <td class="qty">BTW bedrag</td>
            <td class="total">Bedrag (incl. BTW)</td>
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
            <th class="qty">Totaal</th>
            <th class="total">Stelpost</th>
          </tr>
        </thead>
        <tbody>
          <tr style="page-break-after: always;">
            <td class="no">Arbeidskosten</td>
            <td class="desc">Uren</td>
            <td class="no">Arbeidskosten</td>
            <td class="desc">Uren</td>
            <td class="unit">Bedrag (excl. BTW)</td>
            <td class="qty">BTW %</td>
            <td class="qty">BTW bedrag</td>
            <td class="total">Bedrag (incl. BTW)</td>
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
            <th class="qty">Totaal</th>
            <th class="total">Stelpost</th>
          </tr>
        </thead>
        <tbody>
          <tr style="page-break-after: always;">
            <td class="no">&nbsp;</td>
            <td class="desc">&nbsp;</td>
            <td class="no">Arbeidskosten</td>
            <td class="desc">Uren</td>
            <td class="unit">Bedrag (excl. BTW)</td>
            <td class="qty">BTW %</td>
            <td class="qty">BTW bedrag</td>
            <td class="total">Bedrag (incl. BTW)</td>
          </tr>
      </table>
      @endif
      @endif











    @if ($omschrijving)
    @if ($totaal)
      <div style="page-break-after:always;"></div>
      <header class="clearfix">
      <div id="logo">
        <img src="{{ asset('images/logo2.png') }}">
      </div>
         <div id="invoice">
          <h3 class="name">Offertenummer</h3>
          <div class="date">Projectnaam</div>
          <div class="date">Datum offerte</div>
        </div>
      </div>
      </div>
    </header>
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
        <tr style="page-break-after: always;">
          <td class="no">Hoofdstuk</td>
          <td class="desc">Werkzaamheid</td>
          <td class="no">Omschrijving</td>
         </tr>
      </tbody>
    </table>
    @else
    <div style="page-break-after:always;"></div>
    <header class="clearfix">
      <div id="logo">
        <img src="{{ asset('images/logo2.png') }}">
      </div>
         <div id="invoice">
          <h3 class="name">Offertenummer</h3>
          <div class="date">Projectnaam</div>
          <div class="date">Datum offerte</div>
        </div>
      </div>
      </div>
    </header>
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
        <tr style="page-break-after: always;">
          <td class="no">Hoofdstuk</td>
          <td class="desc">Werkzaamheid</td>
          <td class="no">Omschrijving</td>
         </tr>
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
        <tr style="page-break-after: always;">
          <td class="no">Hoofdstuk</td>
          <td class="desc">Werkzaamheid</td>
          <td class="no">Omschrijving</td>
         </tr>
      </tbody>
    </table>
    @endif
    @endif
















  </body>
</html>
