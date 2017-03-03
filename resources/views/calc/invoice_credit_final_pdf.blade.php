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
    <link rel="stylesheet" href="{{ getcwd() }}/css/pdf.css" media="all" />
  </head>
   <body>
     <header class="clearfix">
        <div id="heading" class="clearfix">
        <table border="0" cellspacing="0" cellpadding="0">
          <tbody>
            <tr>
              <td style="width: 345px">
                <div id="logo">
                <?php
                  if ($relation_self && $relation_self->logo_id)
                    echo "<img src=\"".getcwd().'/'.Resource::find($relation_self->logo_id)->file_location."\"/>";
                ?>
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
                        @if ($relation_self->phone)<div><strong>Telefoon: </strong></div>@endif
                        @if ($relation_self->email)<div><strong>E-mail: </strong></div>@endif  
                        @if ($relation_self->kvk)<div><strong>KVK: </strong></div>@endif 
                        @if ($relation_self->btw)<div><strong>BTW: </strong></div>@endif 
                        @if ($relation_self->iban)<div><strong>IBAN: </strong></div>@endif 
                        @if ($relation_self->iban_name)<div><strong>T.n.v.: </strong></div>@endif 
                      </td>
                      <td style="width: 200px">
                        <div>{{ $relation_self->address_street . ' ' . $relation_self->address_number }}</div>  
                        <div>{{ $relation_self->address_postal . ', ' . $relation_self->address_city }}</div>
                        @if ($relation_self->phone)<div>{{ $relation_self->phone }} </div>@endif  
                        @if ($relation_self->email)<div>{{ $relation_self->email }}</div>@endif 
                        @if ($relation_self->kvk)<div>{{ $relation_self->kvk }}&nbsp;</div>@endif 
                        @if ($relation_self->btw)<div>{{ $relation_self->btw }}</div>@endif 
                        @if ($relation_self->iban)<div>{{ $relation_self->iban }}&nbsp;</div>@endif 
                        @if ($relation_self->iban_name) <div>{{ $relation_self->iban_name }}&nbsp;</div>@endif 
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
            <div><h2 class="type">CREDITFACTUUR</h2></div>
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
                  <td style="width: 90px">
                    <div><strong>Factuurnummer: </strong></div>
                    <div><strong>Projectnaam: </strong></div>
                    @if ($invoice->reference)<div><strong>Uw referentie: </strong></div>@endif
                    <div><strong>Factuurdatum: </strong></div>
                  </td>
                  <td style="width: 210px">
                    <div><?php if (Auth::user()->pref_use_ct_numbering) { echo $invoice->invoice_code; } else { echo $invoice->book_code; } ?></div>
                    <div>{{ $project->project_name }}</div>
                    @if ($invoice->reference)<div>{{ $invoice->reference }}</div>@endif
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
  <div class="openingtext">Hierbij doe ik de creditfactuur toekomen van {{ $project->project_name }}, factuurnummer {{ $oldinvoice }}.</div>
  <br>

    <h2 class="name">Specificatie creditfactuur</h2>
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
          <td style="width: 385px" class="qty"><strong>Te crediteren</strong> @if(!$project->tax_reverse)<i>(Excl. BTW)</i> @endif</td>
          <td class="qty"><strong>{{ '&euro; '.number_format($invoice->amount, 2, ",",".") }}</strong></td>
        </tr>
          
        @if (!$project->tax_reverse)
        <tr>
          <td style="width: 270px" class="qty">&nbsp;</td>
          <td style="width: 385px" class="qty">BTW bedrag 21%</td>
          <td class="qty">{{ '&euro; '.number_format(($invoice->rest_21/100)*21, 2, ",",".") }}</td>
        </tr>
        <tr>
          <td style="width: 270px" class="qty">&nbsp;</td>
          <td style="width: 385px" class="qty">BTW bedrag 6%</td>
          <td class="qty">{{ '&euro; '.number_format(($invoice->rest_6/100)*6, 2, ",",".") }}</td>
        </tr> 
        @endif   
        <tr>
          <td style="width: 270px" class="qty">&nbsp;</td>
          <td style="width: 385px" class="qty"><strong>Calculatief te crediteren</strong> @if(!$project->tax_reverse)<i>(Incl. BTW)</i> @endif</td>
          <td class="qty"><strong>{{ '&euro; '.number_format($invoice->amount+(($invoice->rest_21/100)*21)+(($invoice->rest_6/100)*6), 2, ",",".") }}</strong></td>
        </tr>
      </tbody>
    </table>

      @if($project->tax_reverse)<h2 class="name">Deze creditfactuur is <strong>BTW Verlegd</strong></h1>@endif

    <h2 class="name">Bepalingen</h2>
    <hr color="#000" size="1">
    <div class="terms">
      <li>Betreft de creditnota van factuur {{ $oldinvoice }}.</li>
    </div>

    <div class="closingtext">{{ ($invoice ? $invoice->closure : '') }}</div>

    <div class="signing">Met vriendelijke groet,</div>
    <div class="signing">{{ Contact::find($invoice->from_contact_id)->firstname ." ". Contact::find($invoice->from_contact_id)->lastname }}</div>

  </main>




  </body>
</html>
