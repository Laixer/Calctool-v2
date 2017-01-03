<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Termijnfactuur</title>
    <link rel="stylesheet" href="{{ asset('css/pdf.css') }}" media="all" />
  </head>
   <body>
     <header class="clearfix">
        <div id="heading" class="clearfix">
        <table border="0" cellspacing="0" cellpadding="0">
          <tbody>
            <tr>
              <td style="width: 345px">
                <div id="logo">
                  <img src="{{ asset('images/logo2.png') }}"/>
                </div>
              </td>

              <td style="width: 300px">
              
                <table border="0" cellspacing="0" cellpadding="0">
                  <tbody>
                    <tr>
                      <td style="width: 300 px">
                        <div class="name"><h2>CalculatieTool.com</h2></div>

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
                        <div><strong>Telefoon: </strong></div>
                        <div><strong>&nbsp;</strong></div>
                        <div><strong>E-mail: </strong></div>  
                        <div><strong>KVK: </strong></div> 
                        <div><strong>BTW: </strong></div> 
                        <div><strong>IBAN: </strong></div>
                        <div><strong>T.n.v.: </strong></div>
                      </td>
                      <td style="width: 200px">
                        <div>Odinholm 25</div>  
                        <div>3124SC Schiedam</div>
                        <div>+31 (0)643587470</div>
                        <div>+31 (0)643587430</div>
                        <div>administratie@calculatietool.com</div> 
                        <div>54565243&nbsp;</div> 
                        <div>851353423B01</div>
                        <div>NL29INGB0006863509&nbsp;</div> 
                        <div>CalculatieTool&nbsp;</div> 
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
                  <td>CalculatieTool.com</td>
                </tr>
                <tr>
                  <td>T.a.v. {{ $username }}</td>
                </tr>
              </tbody>
            </table>
          </td>
          <td style="width: 300px">
            <table border="0" cellspacing="0" cellpadding="0">
              <tbody>
                <tr>
                  <td style="width: 90px">
                    <div><strong>Factuurnummer: </strong></div>
                    <div><strong>Projectnaam: </strong></div>
                    <div><strong>Uw referentie: </strong></div>
                    <div><strong>Kenmerk: </strong></div>
                    <div><strong>Factuurdatum: </strong></div>
                  </td>
                  <td style="width: 210px">
                    <div>{{ $invoice_id }}</div>
                    <div>Betaling CalculatieTool</div>
                    <div>{{ $reference }}</div>
                    <div>{{ $payment_id }}</div>
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
  <div class="openingtext">{{ $name }},</div>
  <br>
  <div class="openingtext">Bijgaand treft u de factuur van uw recente betaling aan de CalculatieTool.com. Indien u vragen heeft over deze factuur zijn wij bereikbaar via ons support-forum.</div>
  <br>

    <h2 class="name">Specificatie factuur</h2>
    <hr color="#000" size="1">

    <table class="table table-striped hide-btw2">
      <thead>
        <tr>
          <th class="qty">&nbsp;</th>
        </tr>
      </thead>
           <tbody>
        <tr>
          <td style="width: 270px" class="qty">&nbsp;</td>
          <td style="width: 385px" class="qty"><strong>Account activatie tot {{ $date }}</strong> <i>(excl. BTW)</i></td>
          <td class="qty"><strong>{{ '&euro; '.number_format($amount, 2, ",",".") }}</strong></td>
        </tr>
        
        <tr>
          <td style="width: 270px" class="qty">&nbsp;</td>
          <td style="width: 385px" class="qty">&nbsp;</td>
          <td class="qty"></td>
        </tr>
        
        <tr>
          <td style="width: 270px" class="qty">&nbsp;</td>
          <td style="width: 385px" class="qty">BTW bedrag 21%</td>
          <td class="qty">{{ '&euro; '.number_format(($amount/100) * 21, 2, ",",".") }}</td>
        </tr>
        <tr>
          <td style="width: 270px" class="qty">&nbsp;</td>
          <td style="width: 385px" class="qty"><strong>Betaald</strong> <i>(incl. BTW)</i></td>
          <td class="qty"><strong>{{ '&euro; '.number_format($amount + (($amount/100) * 21), 2, ",",".") }}</strong></td>
        </tr>
      </tbody>
    </table>

    <h2 class="name">Bepalingen</h2>
    <hr color="#000" size="1">
    <div class="terms">
      <li>Deze factuur is reeds betaald.</li>
      <li>Factuur betaald onder account {{ $user_id }}.</li>
    </div>

    <div class="closingtext">Bedankt voor uw vertrouwen in de CalculatieTool.com.</div>

    <div class="signing">Met vriendelijke groet,</div>
    <div class="signing">Het team van de CalculatieTool.com</div>

  </main>

  </body>
</html>
