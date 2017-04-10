<?php

ini_set('memory_limit', '2048M');

use \CalculatieTool\Models\Project;
use \CalculatieTool\Models\Relation;
use \CalculatieTool\Models\Chapter;
use \CalculatieTool\Models\Activity;
use \CalculatieTool\Models\Part;
use \CalculatieTool\Models\PartType;
use \CalculatieTool\Models\Contact;
use \CalculatieTool\Models\Invoice;
use \CalculatieTool\Models\Offer;
use \CalculatieTool\Models\Detail;
use \CalculatieTool\Models\ProjectType;
use \CalculatieTool\Models\Resource;
use \CalculatieTool\Models\BlancRow;
use \CalculatieTool\Models\Tax;
use \CalculatieTool\Calculus\EstimateEndresult;
use \CalculatieTool\Calculus\MoreEndresult;
use \CalculatieTool\Calculus\LessEndresult;
use \CalculatieTool\Calculus\ResultEndresult;
use \CalculatieTool\Calculus\CalculationOverview;
use \CalculatieTool\Calculus\EstimateOverview;
use \CalculatieTool\Calculus\LessOverview;
use \CalculatieTool\Calculus\MoreOverview;
use \CalculatieTool\Calculus\BlancRowsEndresult;
use \CalculatieTool\Http\Controllers\OfferController;
use \CalculatieTool\Calculus\SetEstimateCalculationEndresult;

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

$include_tax = $invoice->include_tax; //BTW bedragen weergeven 1/6
$only_totals = $invoice->only_totals; //Alleen het totale offertebedrag weergeven 2/6
$seperate_subcon = !$invoice->seperate_subcon; //Onderaanneming apart weergeven 3/6
$display_worktotals = $invoice->display_worktotals; //Kosten werkzaamheden weergeven 4/6
$display_specification = $invoice->display_specification; //Onderdeel en werkzaamheden weergeven 5/6
$display_description = $invoice->display_description;  //Omschrijving werkzaamheden weergeven 6/6

$type = ProjectType::find($project->type_id);

$image_height = 0;
if ($relation_self && $relation_self->logo_id) {
   $image_src = getcwd() . '/' . Resource::find($relation_self->logo_id)->file_location;
   $image = getimagesize($image_src);
   $image_height = round(($image[1] / $image[0]) * 300);
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Factuur</title>
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
                  if ($image_height > 0)
                    echo "<img style=\"width:300px;height:" . $image_height . "px;\" src=\"" . $image_src . "\"/>";
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
  <div class="openingtext">{{ ($invoice ? $invoice->description : '') }}</div>
  <br>

@if ($seperate_subcon)

<?#--TOTAL START--?>

  @if ($only_totals)
    <h2 class="name">Specificatie factuur</h2>
    <hr color="#000" size="1">

  <!-- 'snelle offerte en factuur')
    <table border="0" cellspacing="0" cellpadding="0">
    <thead>
      <tr style="page-break-after: always;">
      <th style="width: 147px" align="left" class="qty">Omschrijving</th>
      <th style="width: 60px" align="left" class="qty">€ / Eenh (excl. BTW)</th>
      <th style="width: 119px" align="left" class="qty">Aantal</th>
      <th style="width: 70px" align="left" class="qty">Totaal</th>
      <th style="width: 80px" align="left" class="qty">BTW</th>
      <th style="width: 119px" align="left" class="qty">@if(!$project->tax_reverse) BTW bedrag @endif</th>
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
      <td class="qty">@if(!$project->tax_reverse) {{ '&euro; '.number_format(($row->rate * $row->amount/100) * Tax::find($row->tax_id)->tax_rate, 2, ",",".") }} @endif</td>
      </tr>
      @endforeach
    </tbody>
    </table>
     -->

    <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr style="page-break-after: always;">
          <th class="qty">&nbsp;</th>
          <th class="qty">Calculatie</th>
          @if ($project->use_more)
          <th class="qty">Meerwerk</th>
          @endif
          @if ($project->use_less)
          <th class="qty">Minderwerk</th>
          @endif
          @if ($project->use_more || $project->use_less)
          <th class="qty">Balans</th>
          @endif
          <th class="qty">@if ($display_specification) BTW @endif</th>
          <th class="qty">@if(!$project->tax_reverse) BTW bedrag @endif</th>
        </tr>
      </thead>
      <tbody>

    @if ($display_specification)
      @if (!$project->tax_reverse)
      <tr style="page-break-after: always;">
        <td class="qty">Arbeidskosten</td>
        <td class="qty">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcLaborActivityTax1Amount($project)+SetEstimateCalculationEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
        @if ($project->use_more)
        <td class="qty">{{ '&euro; '.number_format(MoreEndresult::conCalcLaborActivityTax1Amount($project)+MoreEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
        @endif
        @if ($project->use_less)
        <td class="qty">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax1Amount($project)+LessEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
        @endif
        @if ($project->use_more || $project->use_less)
        <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax1($project)+ResultEndresult::subconLaborBalanceTax1($project), 2, ",",".") }}</td>
        @endif
        <td class="qty">21%</td>
        <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax1AmountTax($project)+ResultEndresult::subconLaborBalanceTax1AmountTax($project), 2, ",",".") }}</td>
      </tr>
      <tr style="page-break-after: always;">
        <td class="qty">&nbsp;</td>
        <td class="qty">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcLaborActivityTax2Amount($project)+SetEstimateCalculationEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
        @if ($project->use_more)
        <td class="qty">{{ '&euro; '.number_format(MoreEndresult::conCalcLaborActivityTax2Amount($project)+MoreEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
        @endif
        @if ($project->use_less)
        <td class="qty">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax2Amount($project)+LessEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
        @endif
        @if ($project->use_more || $project->use_less)
        <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax2($project)+ResultEndresult::subconLaborBalanceTax2($project), 2, ",",".") }}</td>
        @endif
        <td class="qty">6%</td>
        <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax2AmountTax($project)+ResultEndresult::subconLaborBalanceTax2AmountTax($project), 2, ",",".") }}</td>
      </tr>
      @else
        <tr style="page-break-after: always;">
        <td class="qty">Arbeidskosten</td>
        <td class="qty">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcLaborActivityTax3Amount($project)+SetEstimateCalculationEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
        @if ($project->use_more)
        <td class="qty">{{ '&euro; '.number_format(MoreEndresult::conCalcLaborActivityTax3Amount($project)+MoreEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
        @endif
        @if ($project->use_less)
        <td class="qty">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax3Amount($project)+LessEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
        @endif
        @if ($project->use_more || $project->use_less)
        <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax3($project)+ResultEndresult::subconLaborBalanceTax3($project), 2, ",",".") }}</td>
        @endif
        <td class="qty">0%</td>
        <td class="qty">&nbsp;</td>
      </tr>
      @endif
      @if (!$project->tax_reverse)
      <tr style="page-break-after: always;">
        <td class="qty">Materiaalkosten</td>
        <td class="qty">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcMaterialActivityTax1Amount($project)+SetEstimateCalculationEndresult::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
        @if ($project->use_more)
        <td class="qty">{{ '&euro; '.number_format(MoreEndresult::conCalcMaterialActivityTax1Amount($project)+MoreEndresult::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
        @endif
        @if ($project->use_less)
        <td class="qty">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax1Amount($project)+LessEndresult::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
        @endif
        @if ($project->use_more || $project->use_less)
        <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax1($project)+ResultEndresult::subconMaterialBalanceTax1($project), 2, ",",".") }}</td>
        @endif
        <td class="qty">21%</td>
        <td class="qty">@if(!$project->tax_reverse) {{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax1AmountTax($project)+ResultEndresult::subconMaterialBalanceTax1AmountTax($project), 2, ",",".") }}@endif</td>
      </tr>
      <tr style="page-break-after: always;">
        <td class="qty">&nbsp;</td>
        <td class="qty">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcMaterialActivityTax2Amount($project)+SetEstimateCalculationEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
        @if ($project->use_more)
        <td class="qty">{{ '&euro; '.number_format(MoreEndresult::conCalcMaterialActivityTax2Amount($project)+MoreEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
        @endif
        @if ($project->use_less)
        <td class="qty">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax2Amount($project)+LessEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
        @endif
        @if ($project->use_more || $project->use_less)
        <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax2($project)+ResultEndresult::subconMaterialBalanceTax2($project), 2, ",",".") }}</td>
        @endif
        <td class="qty">6%</td>
        <td class="qty">@if(!$project->tax_reverse) {{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax2AmountTax($project)+ResultEndresult::subconMaterialBalanceTax2AmountTax($project), 2, ",",".") }}@endif</td>
      </tr>
      @else
      <tr style="page-break-after: always;">
        <td class="qty">Materiaalkosten</td>
        <td class="qty">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcMaterialActivityTax3Amount($project)+SetEstimateCalculationEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
        @if ($project->use_more)
        <td class="qty">{{ '&euro; '.number_format(MoreEndresult::conCalcMaterialActivityTax3Amount($project)+MoreEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
        @endif
        @if ($project->use_less)
        <td class="qty">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax3Amount($project)+LessEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
        @endif
        @if ($project->use_more || $project->use_less)
        <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax3($project)+ResultEndresult::subconMaterialBalanceTax3($project), 2, ",",".") }}</td>
        @endif
        <td class="qty">0%</td>
        <td class="qty">&nbsp;</td>
      </tr>
      @endif
      
      @if ($project->use_equipment)
      @if (!$project->tax_reverse)
      <tr style="page-break-after: always;">
        <td class="qty">Overige kosten</td>
        <td class="qty">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcEquipmentActivityTax1Amount($project)+SetEstimateCalculationEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
        @if ($project->use_more)
        <td class="qty">{{ '&euro; '.number_format(MoreEndresult::conCalcEquipmentActivityTax1Amount($project)+MoreEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
        @endif
        @if ($project->use_less)
        <td class="qty">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax1Amount($project)+LessEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
        @endif
        @if ($project->use_more || $project->use_less)
        <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax1($project)+ResultEndresult::subconEquipmentBalanceTax1($project), 2, ",",".") }}</td>
        @endif
        <td class="qty">21%</td>
        <td class="qty">@if(!$project->tax_reverse) {{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax1AmountTax($project)+ResultEndresult::subconEquipmentBalanceTax1AmountTax($project), 2, ",",".") }}@endif</td>
      </tr>
      <tr style="page-break-after: always;">
        <td class="qty">&nbsp;</td>
        <td class="qty">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcEquipmentActivityTax2Amount($project)+SetEstimateCalculationEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
        @if ($project->use_more)
        <td class="qty">{{ '&euro; '.number_format(MoreEndresult::conCalcEquipmentActivityTax2Amount($project)+MoreEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
        @endif
        @if ($project->use_less)
        <td class="qty">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax2Amount($project)+LessEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
        @endif
        @if ($project->use_more || $project->use_less)
        <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax2($project)+ResultEndresult::subconEquipmentBalanceTax2($project), 2, ",",".") }}</td>
        @endif
        <td class="qty">6%</td>
        <td class="qty">@if(!$project->tax_reverse) {{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax2AmountTax($project)+ResultEndresult::subconEquipmentBalanceTax2AmountTax($project), 2, ",",".") }}@endif</td>
      </tr>
      @else
      <tr style="page-break-after: always;">
        <td class="qty">Overige kosten</td>
        <td class="qty">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcEquipmentActivityTax3Amount($project)+SetEstimateCalculationEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
        @if ($project->use_more)
        <td class="qty">{{ '&euro; '.number_format(MoreEndresult::conCalcEquipmentActivityTax3Amount($project)+MoreEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
        @endif
        @if ($project->use_less)
        <td class="qty">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax3Amount($project)+LessEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
        @endif
        @if ($project->use_more || $project->use_less)
        <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax3($project)+ResultEndresult::subconEquipmentBalanceTax3($project), 2, ",",".") }}</td>
        @endif
        <td class="qty">0%</td>
        <td class="qty">&nbsp;</td>
      </tr>
      @endif
      @endif


    @endif
      <tr style="page-break-after: always;">
        <td class="qty"><strong>Totaal</strong></td>
        <td class="qty"><strong>{{ '&euro; '.number_format(SetEstimateCalculationEndresult::totalSubcontracting($project)+SetEstimateCalculationEndresult::totalContracting($project), 2, ",",".") }}</strong></td>
        @if ($project->use_more)
        <td class="qty"><strong>{{ '&euro; '.number_format(MoreEndresult::totalContracting($project)+MoreEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
        @endif
        @if ($project->use_less)
        <td class="qty"><strong>{{ '&euro; '.number_format(LessEndresult::totalContracting($project)+LessEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
        @endif
        @if ($project->use_more || $project->use_less)
        <td class="qty"><strong>{{ '&euro; '.number_format(ResultEndresult::totalContracting($project)+ResultEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
        @endif
        <td class="qty">&nbsp;</td>
        <td class="qty"><strong>@if(!$project->tax_reverse) {{ '&euro; '.number_format(ResultEndresult::totalContractingTax($project)+ResultEndresult::totalSubcontractingTax($project), 2, ",",".") }}</strong>@endif</td>
      </tr>
    </tbody>
  </table>
  @endif

    <h2 class="name">Totalen Factuur</h2>
    <hr color="#000" size="1">
    @if(!$project->tax_reverse)
    <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr style="page-break-after: always;">
          <th class="qty">&nbsp;</th>
        </tr>
      </thead>
      <tbody>
        <tr style="page-break-after: always;">
          <td style="width: 270px"class="qty">&nbsp;</td>
          <td style="width: 385px" class="qty">Calculatief te betalen @if(!$project->tax_reverse)<i>(Excl. BTW)</i> @endif</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::totalProject($project), 2, ",",".") }}</td>
        </tr>
        <tr style="page-break-after: always;">
          <td style="width: 270px"class="qty">&nbsp;</td>
          <td style="width: 385px" class="qty">BTW bedrag 21%</td>
          <td class="qty">@if(!$project->tax_reverse) {{ '&euro; '.number_format(ResultEndresult::totalContractingTax1($project)+ResultEndresult::totalSubcontractingTax1($project)+BlancRowsEndresult::rowTax1AmountTax($project), 2, ",",".") }} @endif</td>
        </tr>
        <tr style="page-break-after: always;">
          <td style="width: 270px"class="qty">&nbsp;</td>
          <td style="width: 385px" class="qty">BTW bedrag 6%</td>
          <td class="qty">@if(!$project->tax_reverse) {{ '&euro; '.number_format(ResultEndresult::totalContractingTax2($project)+ResultEndresult::totalSubcontractingTax2($project)+BlancRowsEndresult::rowTax2AmountTax($project), 2, ",",".") }} @endif</td>
        </tr>
        <tr style="page-break-after: always;">
          <td style="width: 270px" class="qty">&nbsp;</td>
          <td style="width: 385px" class="qty">Calculatief te betalen @if(!$project->tax_reverse) <i>(Incl. BTW)</i> @endif</td>
          <td class="qty">@if(!$project->tax_reverse) {{ '&euro; '.number_format(ResultEndresult::superTotalProject($project)+BlancRowsEndresult::rowTax1AmountTax($project)+BlancRowsEndresult::rowTax2AmountTax($project), 2, ",",".") }}@endif</td>
        </tr>
      </tbody>
    </table>
    @else
    <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr style="page-break-after: always;">
          <th class="qty">&nbsp;</th>
        </tr>
      </thead>
      <tbody>
        <tr style="page-break-after: always;">
          <td style="width: 270px" class="qty">&nbsp;</td>
          <td style="width: 385px" class="qty"><strong>Calculatief te betalen<strong></td>
          <td lass="qty">{{ '&euro; '.number_format(ResultEndresult::totalProject($project), 2, ",",".") }}</td>
        </tr>
      </tbody>
    </table>
    @endif



                            <?#--INCLUDE TERM START--?>

                                <?php
                                $cnt = Invoice::where('offer_id','=', $invoice->offer_id)->count();
                                if ($cnt>1) {
                                ?>
                                  <br>
                                 <table class="table table-striped hide-btw2">
                                  <tbody>
                                    <tr>
                                      <td style="width: 270px" class="qty">&nbsp;</td>
                                      <td style="width: 385px" class="qty">Totaal betaald over {{Invoice::where('offer_id','=', $invoice->offer_id)->count() -1}} termijn(en) @if(!$project->tax_reverse)<i>(Excl. BTW)</i> @endif</td>
                                      <td class="qty">{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('amount'), 2, ",",".") }}</td>
                                    </tr>
                                    @if (!$project->tax_reverse)
                                    <tr>
                                      <th style="width: 270px" class="qty">&nbsp;</th>
                                      <td style="width: 385px" class="qty">BTW bedrag 21%</td>
                                      <td class="qty">{{ '&euro; '.number_format((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('rest_21')/100)*21, 2, ",",".") }}</td>
                                    </tr>
                                    <tr>
                                      <th style="width: 270px" class="qty">&nbsp;</th>
                                      <td style="width: 385px" class="qty">BTW bedrag 6%</td>
                                      <td class="qty">{{ '&euro; '.number_format((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('rest_6')/100)*6, 2, ",",".") }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                      <th style="width: 270px" class="qty">&nbsp;</th>
                                      <td style="width: 385px" class="qty">Totaal reeds betaald @if(!$project->tax_reverse) <i>(Incl. BTW)</i> @endif</strong></td>
                                      <td class="qty">{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('amount')+((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('rest_21')/100)*21)+((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',false)->sum('rest_6')/100)*6), 2, ",",".") }}</strong></td>
                                    </tr>
                                  </tbody>
                                </table>
                                <br>
                                  <table class="table table-striped hide-btw2">
                                    <tbody>
                                      <tr>
                                        <td style="width: 270px" class="qty">&nbsp;</td>
                                        <td style="width: 385px"class="qty">Laatste van in totaal {{Invoice::where('offer_id','=', $invoice->offer_id)->count()}} termijnen @if(!$project->tax_reverse) <i>(Excl. BTW)</i> @endif</td>
                                        <td class="qty">{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->amount, 2, ",",".") }}</td>
                                      </tr>
                                      @if (!$project->tax_reverse)
                                      <tr>
                                        <td style="width: 270px" class="qty">&nbsp;</td>
                                        <td style="width: 385px"class="qty">BTW bedrag 21%</td>
                                        <td class="qty">{{ '&euro; '.number_format((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->rest_21/100)*21, 2, ",",".") }}</td>
                                      </tr>
                                      <tr>
                                        <td style="width: 270px" class="qty">&nbsp;</td>
                                        <td style="width: 385px"class="qty">BTW bedrag 6%</td>
                                        <td class="qty">{{ '&euro; '.number_format((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->rest_6/100)*6, 2, ",",".") }}</td>
                                      </tr>
                                      @endif
                                      <tr>
                                        <td style="width: 270px" class="qty">&nbsp;</td>
                                        <td style="width: 385px" class="qty"><strong>Resterend te betalen @if(!$project->tax_reverse)<i>(Incl. BTW)</i> @endif</strong></td>
                                        <td class="qty"><strong>{{ '&euro; '.number_format(Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->amount+((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->rest_21/100)*21)+((Invoice::where('offer_id','=',$invoice->offer_id)->where('isclose','=',true)->first()->rest_6/100)*6), 2, ",",".") }}</strong></td>
                                      </tr>
                                    </tbody>
                                  </table>
                                  <?php } ?>
                                <?#--INCLUDE TERM END--?>


    @if ($project->tax_reverse)
    @if ($relation->btw)
    <h2 class="name">Deze offerte is <strong>BTW Verlegd</strong> naar {{ $relation->btw }}</h1>
    @else
    <h2 class="name">Deze offerte is <strong>BTW Verlegd</strong></h1>
    @endif
    @endif

      <h2 class="name">Bepalingen</h2>
      <hr color="#000" size="1">

      <div class="terms">
        <li>Deze factuur dient betaald te worden binnen {{ $invoice->payment_condition }} dagen na dagtekening.</li>
        <li>Gaarne bij betaling factuurnummer vermelden.</li>
      </div>
      <br>
      <div class="signing">{{ ($invoice ? $invoice->closure : '') }}</div>
      <br>
      <div class="signing">Met vriendelijke groet,</div>
      <br>
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
          <th style="width: 181px" class="qty">&nbsp;</th>
          <th class="qty">Calculatie</th>
          <th class="qty">Meerwerk</th>
          <th class="qty">Minderwerk</th>
          <th class="qty">Balans</th>
          <th class="qty">@if ($display_specification) BTW @endif</th>
          <th class="qty">@if(!$project->tax_reverse) BTW bedrag @endif</th>
        </tr>
      </thead>
      <tbody>
    @if ($display_specification)
        @if (!$project->tax_reverse)
        <tr style="page-break-after: always;">
          <td class="qty">Arbeidskosten</td>
          <td class="qty">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(MoreEndresult::conCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax1($project), 2, ",",".") }}</td>
          <td class="qty">21%</td>
          <td class="qty">@if(!$project->tax_reverse) {{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax1AmountTax($project), 2, ",",".") }} @endif</td>
        </tr>
        <tr style="page-break-after: always;">
          <td class="qty">&nbsp;</td>
          <td class="qty">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(MoreEndresult::conCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax2($project), 2, ",",".") }}</td>
          <td class="qty">6%</td>
          <td class="qty">@if(!$project->tax_reverse) {{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax2AmountTax($project), 2, ",",".") }} @endif</td>
        </tr>
        @else
        <tr style="page-break-after: always;">
          <td class="qty">Arbeidskosten</td>
          <td class="qty">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
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
          <td class="qty">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(MoreEndresult::conCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax1($project), 2, ",",".") }}</td>
          <td class="qty">21%</td>
          <td class="qty">@if(!$project->tax_reverse) {{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax1AmountTax($project), 2, ",",".") }} @endif</td>
        </tr>
        <tr style="page-break-after: always;">
          <td class="qty">&nbsp;</td>
          <td class="qty">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(MoreEndresult::conCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax2($project), 2, ",",".") }}</td>
          <td class="qty">6%</td>
          <td class="qty">@if(!$project->tax_reverse) {{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax2AmountTax($project), 2, ",",".") }} @endif</td>
        </tr>
        @else
        <tr style="page-break-after: always;">
          <td class="qty">Materiaalkosten</td>
          <td class="qty">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(MoreEndresult::conCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax3($project), 2, ",",".") }}</td>
          <td class="qty">0%</td>
          <td class="qty">&nbsp;</td>
        </tr>
        @endif
        @if ($project->use_equipment)
        @if (!$project->tax_reverse)
        <tr style="page-break-after: always;">
          <td class="qty">Overige kosten</td>
          <td class="qty">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(MoreEndresult::conCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax1($project), 2, ",",".") }}</td>
          <td class="qty">21%</td>
          <td class="qty">@if(!$project->tax_reverse) {{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax1AmountTax($project), 2, ",",".") }} @endif</td>
        </tr>
        <tr style="page-break-after: always;">
          <td class="qty">&nbsp;</td>
          <td class="qty">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(MoreEndresult::conCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax2($project), 2, ",",".") }}</td>
          <td class="qty">6%</td>
          <td class="qty">@if(!$project->tax_reverse) {{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax2AmountTax($project), 2, ",",".") }} @endif</td>
        </tr>
        @else
        <tr style="page-break-after: always;">
          <td class="qty">Overige kosten</td>
          <td class="qty">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(MoreEndresult::conCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax3($project), 2, ",",".") }}</td>
          <td class="qty">0%</td>
          <td class="qty">&nbsp;</td>
        </tr>
        @endif
        @endif
    @endif
        <tr style="page-break-after: always;">
          <td class="qty"><strong>Totaal</strong></td>
          <td class="qty"><strong>{{ '&euro; '.number_format(SetEstimateCalculationEndresult::totalContracting($project), 2, ",",".") }}</strong></td>
          <td class="qty"><strong>{{ '&euro; '.number_format(MoreEndresult::totalContracting($project), 2, ",",".") }}</strong></td>
          <td class="qty"><strong>{{ '&euro; '.number_format(LessEndresult::totalContracting($project), 2, ",",".") }}</strong></td>
          <td class="qty"><strong>{{ '&euro; '.number_format(ResultEndresult::totalContracting($project), 2, ",",".") }}</strong></td>
          <td class="qty">&nbsp;</td>
          <td class="qty">@if(!$project->tax_reverse) <strong>{{ '&euro; '.number_format(ResultEndresult::totalContractingTax($project), 2, ",",".") }}</strong>@endif</td>
        </tr>
      </tbody>
    </table>

    <h2 class="name">Onderaanneming</h2>
    <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr style="page-break-after: always;">
          <th style="width: 181px" class="qty">&nbsp;</th>
          <th class="qty">Calculatie</th>
          <th class="qty">Meerwerk</th>
          <th class="qty">Minderwerk</th>
          <th class="qty">Balans</th>
          <th class="qty">@if ($display_specification) BTW @endif</th>
          <th class="qty">@if(!$project->tax_reverse) BTW bedrag @endif </th>
        </tr>
      </thead>
      <tbody>
    @if ($display_specification)
       @if (!$project->tax_reverse)
        <tr style="page-break-after: always;">
          <td class="qty">Arbeidskosten</td>
          <td class="qty">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(MoreEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::subconLaborBalanceTax1($project), 2, ",",".") }}</td>
          <td class="qty">21%</td>
          <td class="qty">@if(!$project->tax_reverse) {{ '&euro; '.number_format(ResultEndresult::subconLaborBalanceTax1AmountTax($project), 2, ",",".") }} @endif</td>
        </tr>
        <tr style="page-break-after: always;">
          <td class="qty">&nbsp;</td>
          <td class="qty">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(MoreEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::subconLaborBalanceTax2($project), 2, ",",".") }}</td>
          <td class="qty">6%</td>
          <td class="qty">@if(!$project->tax_reverse) {{ '&euro; '.number_format(ResultEndresult::subconLaborBalanceTax2AmountTax($project), 2, ",",".") }} @endif</td>
        </tr>
        @else
        <tr style="page-break-after: always;">
          <td class="qty">Arbeidskosten</td>
          <td class="qty">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
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
          <td class="qty">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(MoreEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::subconLaborBalanceTax3($project), 2, ",",".") }}</td>
          <td class="qty">21%</td>
          <td class="qty">&nbsp;</td>
        </tr>
        <tr style="page-break-after: always;">
          <td class="qty">&nbsp;</td>
          <td class="qty">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(MoreEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(LessEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::subconMaterialBalanceTax2($project), 2, ",",".") }}</td>
          <td class="qty">6%</td>
          <td class="qty">@if(!$project->tax_reverse){{ '&euro; '.number_format(ResultEndresult::subconMaterialBalanceTax2AmountTax($project), 2, ",",".") }} @endif</td>
        </tr> 
        @else
        <tr style="page-break-after: always;">
          <td class="qty">Materiaalkosten</td>
          <td class="qty">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(MoreEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(LessEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::subconMaterialBalanceTax3($project), 2, ",",".") }}</td>
          <td class="qty">0%</td>
          <td class="qty">&nbsp;</td>
        </tr>
        @endif
        @if ($project->use_equipment)
        @if (!$project->tax_reverse)
        <tr style="page-break-after: always;">
          <td class="qty">Overige kosten</td>
          <td class="qty">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(MoreEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(LessEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::subconEquipmentBalanceTax1($project), 2, ",",".") }}</td>
          <td class="qty">21%</td>
          <td class="qty">@if(!$project->tax_reverse) {{ '&euro; '.number_format(ResultEndresult::subconEquipmentBalanceTax1AmountTax($project), 2, ",",".") }} @endif</td>
        </tr>
        <tr style="page-break-after: always;">
          <td class="qty">&nbsp;</td>
          <td class="qty">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(MoreEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(LessEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::subconEquipmentBalanceTax2($project), 2, ",",".") }}</td>
          <td class="qty">6%</td>
          <td class="qty">@if(!$project->tax_reverse) {{ '&euro; '.number_format(ResultEndresult::subconEquipmentBalanceTax2AmountTax($project), 2, ",",".") }} @endif</td>
        </tr>
        @else
        <tr style="page-break-after: always;">
          <td class="qty">Overige kosten</td>
          <td class="qty">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(MoreEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(LessEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::subconEquipmentBalanceTax3($project), 2, ",",".") }}</td>
          <td class="qty">0%</td>
          <td class="qty">&nbsp;</td>
        </tr>
        @endif
        @endif
    @endif
        <tr style="page-break-after: always;">
          <td class="qty"><strong>Totaal</strong></td>
          <td class="qty"><strong>{{ '&euro; '.number_format(SetEstimateCalculationEndresult::totalSubcontracting($project), 2, ",",".") }} </strong></td>
          <td class="qty"><strong>{{ '&euro; '.number_format(MoreEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
          <td class="qty"><strong>{{ '&euro; '.number_format(LessEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
          <td class="qty"><strong>{{ '&euro; '.number_format(ResultEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
          <td class="qty">&nbsp;</td>
          <td class="qty"><strong>@if(!$project->tax_reverse) {{ '&euro; '.number_format(ResultEndresult::totalSubcontractingTax($project), 2, ",",".") }} @endif</strong></td>
        </tr>
      </tbody>
    </table>


    @if(!$project->tax_reverse)
    <h2 class="name">Totalen Factuur</h2>
    <hr color="#000" size="1">

    <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr style="page-break-after: always;">
          <th class="qty">&nbsp;</th>
        </tr>
      </thead>
      <tbody>
        <tr style="page-break-after: always;">
          <td style="width: 270px" class="qty">&nbsp;</td>
          <td style="width: 385px" class="qty">Calculatief te betalen @if(!$project->tax_reverse) <i>(Excl. BTW)</i> @endif</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::totalProject($project), 2, ",",".") }}</td>
        </tr>
        @if (!$project->tax_reverse)
        <tr style="page-break-after: always;">
          <td style="width: 270px" class="qty">&nbsp;</td>
          <td style="width: 385px"class="qty">BTW bedrag 21%</td>
          <td class="qty">{{ '&euro; '.number_format((ResultEndresult::totalContractingTax1($project) + ResultEndresult::totalSubcontractingTax1($project)), 2, ",",".") }}</td>
        </tr>
        <tr style="page-break-after: always;">
          <td style="width: 270px" class="qty">&nbsp;</td>
          <td style="width: 385px"class="qty">BTW bedrag 6%</td>
          <td class="qty">{{ '&euro; '.number_format((ResultEndresult::totalContractingTax2($project) + ResultEndresult::totalSubcontractingTax2($project)), 2, ",",".") }}</td>
        </tr>
        @endif
        <tr style="page-break-after: always;">
          <td style="width: 270px" class="qty">&nbsp;</td>
          <td style="width: 385px"class="qty">Calculatief te betalen @if(!$project->tax_reverse)<i>(Incl. BTW)</i> @endif</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::superTotalProject($project), 2, ",",".") }}</td>
        </tr>
      </tbody>
    </table>
    @else
    <h2 class="name">Totalen Factuur</h2>
    <hr color="#000" size="1">
    <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr style="page-break-after: always;">
          <th class="qty">&nbsp;</th>
        </tr>
      </thead>
      <tbody>
        <tr style="page-break-after: always;">
          <td style="width: 270px" class="qty">&nbsp;</td>
          <td style="width: 385px" class="qty">Calculatief te betalen</td>
          <td class="qty">{{ '&euro; '.number_format(ResultEndresult::totalProject($project), 2, ",",".") }}</td>
        </tr>
      </tbody>
    </table>
    @endif
                   
                              <?#--INCLUDE TERM START--?>
                              
                                    <?php
                                      $cnt = Invoice::where('offer_id','=', $_invoice->offer_id)->count();
                                      if ($cnt>1) {
                                    ?>
                                      <br>
                                      <table class="table table-striped hide-btw2">
                                      <tbody>
                                        <tr>
                                          <td style="width: 270px" class="qty">&nbsp;</td>
                                          <td style="width: 385px"class="qty">Totaal betaald over {{Invoice::where('offer_id','=', $_invoice->offer_id)->count()}} termijn(en) @if(!$project->tax_reverse) <i>(Excl. BTW)</i> @endif</td>
                                          <td class="qty">{{ '&euro; '.number_format(Invoice::where('offer_id','=',$_invoice->offer_id)->where('isclose','=',false)->sum('amount'), 2, ",",".") }}</td>
                                        </tr>
                                        @if (!$project->tax_reverse)
                                        <tr>
                                          <td style="width: 270px" class="qty">&nbsp;</td>
                                          <td style="width: 385px"class="qty">BTW bedrag 21%</td>
                                          <td class="qty">{{ '&euro; '.number_format((Invoice::where('offer_id','=',$_invoice->offer_id)->where('isclose','=',false)->sum('rest_21')/100)*21, 2, ",",".") }}</td>
                                        </tr>
                                        <tr>
                                          <td style="width: 270px" class="qty">&nbsp;</td>
                                          <td style="width: 385px"class="qty">BTW bedrag 6%</td>
                                          <td class="qty">{{ '&euro; '.number_format((Invoice::where('offer_id','=',$_invoice->offer_id)->where('isclose','=',false)->sum('rest_6')/100)*6, 2, ",",".") }}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                          <td style="width: 270px" class="qty">&nbsp;</td>
                                          <td style="width: 385px"class="qty">Totaal reeds betaald @if(!$project->tax_reverse)<i>(Incl. BTW)</i> @endif</td>
                                          <td class="qty">{{ '&euro; '.number_format(Invoice::where('offer_id','=',$_invoice->offer_id)->where('isclose','=',false)->sum('amount')+((Invoice::where('offer_id','=',$_invoice->offer_id)->where('isclose','=',false)->sum('rest_21')/100)*21)+((Invoice::where('offer_id','=',$_invoice->offer_id)->where('isclose','=',false)->sum('rest_6')/100)*6), 2, ",",".") }}</td>
                                        </tr>
                                      </tbody>
                                    </table>
                                      <br>
                                      <table class="table table-striped hide-btw2">
                                      <tbody>
                                        <tr>
                                          <td style="width: 270px" class="qty">&nbsp;</td>
                                          <td style="width: 385px"class="qty">Laatste van in totaal {{Invoice::where('offer_id','=', $_invoice->offer_id)->count()}} termijnen @if(!$project->tax_reverse)<i>(Excl. BTW)</i> @endif</td>
                                          <td class="qty">{{ '&euro; '.number_format(Invoice::where('offer_id','=',$_invoice->offer_id)->where('isclose','=',true)->first()->amount, 2, ",",".") }}</td>
                                        </tr>
                                        @if (!$project->tax_reverse)
                                        <tr>
                                          <td style="width: 270px" class="qty">&nbsp;</td>
                                          <td style="width: 385px"class="qty">BTW bedrag 21%</td>
                                          <td class="qty">{{ '&euro; '.number_format((Invoice::where('offer_id','=',$_invoice->offer_id)->where('isclose','=',true)->first()->rest_21/100)*21, 2, ",",".") }}</td>
                                          <td class="qty">&nbsp;</td>
                                        </tr>
                                        <tr>
                                          <td style="width: 270px" class="qty">&nbsp;</td>
                                          <td style="width: 385px"class="qty">BTW bedrag 6%</td>
                                          <td class="qty">{{ '&euro; '.number_format((Invoice::where('offer_id','=',$_invoice->offer_id)->where('isclose','=',true)->first()->rest_6/100)*6, 2, ",",".") }}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                          <td style="width: 270px" class="qty">&nbsp;</td>
                                          <td style="width: 385px" class="qty"><strong>Resterend te betalen @if(!$project->tax_reverse)<i>(Incl. BTW)</i> @endif</strong></td>
                                          <td class="qty"><strong>{{ '&euro; '.number_format(Invoice::where('offer_id','=',$_invoice->offer_id)->where('isclose','=',true)->first()->amount+((Invoice::where('offer_id','=',$_invoice->offer_id)->where('isclose','=',true)->first()->rest_21/100)*21)+((Invoice::where('offer_id','=',$_invoice->offer_id)->where('isclose','=',true)->first()->rest_6/100)*6), 2, ",",".") }}</strong></td>
                                        </tr>
                                      </tbody>
                                    </table>
                                    

                                    <?#--PAGE HEADER SECOND START--?>
                                    <div style="page-break-after:always;"></div>
                                    <header class="clearfix">
                                    <div id="logo">
                                    <?php
                                      if ($image_height > 0)
                                        echo "<img style=\"width:300px;height:" . $image_height . "px;\" src=\"" . $image_src . "\"/>";
                                    ?>
                                    </div>
                                      <div id="invoice">
                                        <div><?php if (Auth::user()->pref_use_ct_numbering) { echo $invoice->invoice_code; } else { echo $invoice->book_code; } ?></div>
                                        <div>{{ $project->project_name }}</div>
                                        <div>{{ date("j M Y") }}</div>
                                    </div>
                                    </header>
                                    <?#--PAGE HEADER SECOND END--?>
                                <?#--INCLUDE TERM END--?>

                                <?php } ?>


    @if ($project->tax_reverse)
    @if ($relation->btw)
    <h2 class="name">Deze offerte is <strong>BTW Verlegd</strong> naar {{ $relation->btw }}</h1>
    @else
    <h2 class="name">Deze offerte is <strong>BTW Verlegd</strong></h1>
    @endif
    @endif

        <h2 class="name">Bepalingen</h2>
        <hr color="#000" size="1">

        <div class="terms">
          <li>Deze factuur dient betaald te worden binnen {{ $invoice->payment_condition }} dagen na dagtekening.</li>
          <li>Gaarne bij betaling factuurnummer vermelden.</li>
        </div>
        <br>
        <div class="signing">{{ ($invoice ? $invoice->closure : '') }}</div>
        <br>
        <div class="signing">Met vriendelijke groet,</div>
        <br>
        <div class="signing">{{ Contact::find($invoice->from_contact_id)->firstname ." ". Contact::find($invoice->from_contact_id)->lastname }}</div>
      </main>


  <?#--CON & SUBCONTR END--?>
  @endif
  @if ($display_worktotals)
  <?#--$display_worktotals START--?>
  @if ($seperate_subcon)
  <?#--TOTAL START--?>

    <?#--PAGE HEADER SECOND START--?>
      <div style="page-break-after:always;"></div>
      <header class="clearfix">
      <div id="logo">
      <?php
        if ($image_height > 0)
          echo "<img style=\"width:300px;height:" . $image_height . "px;\" src=\"" . $image_src . "\"/>";
      ?>
      </div>
        <div id="invoice">
          <div><?php if (Auth::user()->pref_use_ct_numbering) { echo $invoice->invoice_code; } else { echo $invoice->book_code; } ?></div>
          <div>{{ $project->project_name }}</div>
          <div>{{ date("j M Y") }}</div>
      </div>
      </header>

      <?#--PAGE HEADER SECOND END--?>

    <?#--PAGE HEADER SECOND START--?>

  <?#--CALCULATION TOTAL START --?>

  <h2 class="name">Calculatie per werkzaamheid</h2>
  <hr color="#000" size="1">

  <table border="0" cellspacing="0" cellpadding="0">
    <thead>
      <tr style="page-break-after: always;">
        <th style="width: 181px" class="qty-small">Onderdeel</th>
        <th style="width: 170px" class="qty-small">Werkzaamheid</th>
        <th style="width: 40px" class="qty-small">@if ($display_specification) @if(0) Arbeidsuren @endif &nbsp; @endif</th>
        <th style="width: 51px" class="qty-small">@if ($display_specification) Arbeid @endif</th>
        <th style="width: 51px" class="qty-small">@if ($display_specification) Materiaal @endif</th>
        <th style="width: 51px" class="qty-small">@if ($display_specification) @if ($project->use_equipment) Overig @endif &nbsp; @endif</th>
        <th style="width: 51px"class="qty-small">Totaal</th>
      </tr>
    </thead>
    <tbody>
      @foreach (Chapter::where('project_id','=', $project->id)->orderBy('priority')->get() as $chapter)
      @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->whereNull('detail_id')->orderBy('priority')->get() as $activity)
      <tr><?#-- item --?>
        <td style="width: 181px" class="qty-small">{{ $chapter->chapter_name }}</td>
        <td style="width: 170px" class="qty-small">{{ $activity->activity_name }}</td>
        <td style="width: 40px" class="qty-small"><span class="pull-right">@if ($display_specification) @if(0) {{ number_format(CalculationOverview::laborTotal($activity), 2, ",",".") }} @endif &nbsp; @endif</td>
        <td style="width: 51px" class="qty-small"><span class="pull-right total-ex-tax">@if ($display_specification) {{ '&euro; '.number_format(CalculationOverview::laborActivity($project->hour_rate, $activity), 2, ",",".") }} @endif</span></td>
        <td style="width: 51px" class="qty-small"><span class="pull-right total-ex-tax">@if ($display_specification) {{ '&euro; '.number_format(CalculationOverview::materialActivityProfit($activity, $project->profit_calc_contr_mat), 2, ",",".") }} @endif</span></td>
        <td style="width: 51px" class="qty-small"><span class="pull-right">@if ($display_specification) @if ($project->use_equipment) {{ '&euro; '.number_format(CalculationOverview::equipmentActivityProfit($activity, $project->profit_calc_contr_equip), 2, ",",".") }} @endif &nbsp; @endif</span></td>
        <td style="width: 51px" class="qty-small"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::activityTotalProfit($project->hour_rate, $activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip), 2, ",",".") }} </td>
      </tr>
      @endforeach
      @endforeach
      @foreach (Chapter::where('project_id','=', $project->id)->orderBy('priority')->get() as $chapter)
      @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->whereNull('detail_id')->orderBy('priority')->get() as $activity)
      <tr>
        <td style="width: 181px" class="qty-small"><strong>{{ $chapter->chapter_name }}</strong></td>
        <td style="width: 170px" class="qty-small">{{ $activity->activity_name }}</td>
        <td style="width: 40px" class="qty-small"><span class="pull-right">@if ($display_specification) {{ number_format(CalculationOverview::laborTotal($activity), 2, ",",".") }} @endif</td>
        <td style="width: 51px" class="qty-small"><span class="pull-right total-ex-tax">@if ($display_specification) {{ '&euro; '.number_format(CalculationOverview::laborActivity($project->hour_rate, $activity), 2, ",",".") }} @endif</span></td>
        <td style="width: 51px" class="qty-small"><span class="pull-right total-ex-tax">@if ($display_specification) {{ '&euro; '.number_format(CalculationOverview::materialActivityProfit($activity, $project->profit_calc_subcontr_mat), 2, ",",".") }} @endif</span></td>
        <td style="width: 51px" class="qty-small"><span class="pull-right">@if ($display_specification) {{ '&euro; '.number_format(CalculationOverview::equipmentActivityProfit($activity, $project->profit_calc_subcontr_equip), 2, ",",".") }} @endif</span></td>
        <td style="width: 51px" class="qty-small"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::activityTotalProfit($project->hour_rate, $activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip), 2, ",",".") }} </td>
        </td>
      </tr>
      @endforeach
      @endforeach
    </tbody>
  </table>

  <h2 class="name">Totalen per project</h2>
  <hr color="#000" size="1">

  <table border="0" cellspacing="0" cellpadding="0">
    <thead>
      <tr style="page-break-after: always;">
        <th style="width: 181px" class="qty-small">&nbsp;</th>
        <th style="width: 170px" class="qty-small">&nbsp;</th>
        <th style="width: 40px" class="qty-small">@if ($display_specification) @if(0) Arbeidsuren @endif &nbsp;  @endif</th>
        <th style="width: 51px" class="qty-small">@if ($display_specification) Arbeid @endif</th>
        <th style="width: 51px" class="qty-small">@if ($display_specification) Materiaal @endif</th>
        <th style="width: 51px" class="qty-small">@if ($display_specification) @if ($project->use_equipment) Overig @endif &nbsp; @endif</th>
        <th style="width: 51px" class="qty-small">Totaal</th>
      </tr>
    </thead>
    <tbody>
      <td style="width: 181px" class="qty-small">&nbsp;</td>
      <td style="width: 170px" class="qty-small">&nbsp;</td>
      <td style="width: 40px" class="qty-small"><span class="pull-right">@if ($display_specification) @if(0) {{ CalculationOverview::laborSuperTotalAmount($project) }} @endif &nbsp; @endif</span></td>
      <td style="width: 51px" class="qty-small"><span class="pull-right">@if ($display_specification) {{ '&euro; '.number_format(CalculationOverview::laborSuperTotal($project), 2, ",",".") }} @endif</span></td>
      <td style="width: 51px" class="qty-small"><span class="pull-right">@if ($display_specification) {{ '&euro; '.number_format(CalculationOverview::materialSuperTotal($project), 2, ",",".") }} @endif</span></td>
      <td style="width: 51px" class="qty-small"><span class="pull-right">@if ($display_specification) @if ($project->use_equipment) {{ '&euro; '.number_format(CalculationOverview::equipmentSuperTotal($project), 2, ",",".") }} @endif &nbsp; @endif</span></td>
      <td style="width: 51px" class="qty-small"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::superTotal($project), 2, ",",".") }}</span></td>
    </tbody>
  </table>
  <?#--CALCULATION TOTAL END --?>
  
   @if ($project->use_estim)
    <?#--PAGE HEADER SECOND START--?>
      <div style="page-break-after:always;"></div>
      <header class="clearfix">
      <div id="logo">
      <?php
        if ($image_height > 0)
          echo "<img style=\"width:300px;height:" . $image_height . "px;\" src=\"" . $image_src . "\"/>";
      ?>
      </div>
        <div id="invoice">
          <div><?php if (Auth::user()->pref_use_ct_numbering) { echo $invoice->invoice_code; } else { echo $invoice->book_code; } ?></div>
          <div>{{ $project->project_name }}</div>
          <div>{{ date("j M Y") }}</div>
      </div>
      </header>
      <?#--PAGE HEADER SECOND END--?>

  <?#--ESTIMATE TOTAL START --?>

   <h2 class="name">Stelposten per werkzaamheid</h2>
   <hr color="#000" size="1">

   <table border="0" cellspacing="0" cellpadding="0">
    <thead>
      <tr style="page-break-after: always;">
        <th style="width: 181px" class="qty-small">Onderdeel</th>
        <th style="width: 170px" class="qty-small">Werkzaamheid</th>
        <th style="width: 40px" class="qty-small">@if ($display_specification) @if(0) Arbeidsuren @endif &nbsp;  @endif</th>
        <th style="width: 51px" class="qty-small">@if ($display_specification) Arbeid @endif</th>
        <th style="width: 51px" class="qty-small">@if ($display_specification) Materiaal @endif</th>
        <th style="width: 51px" class="qty-small">@if ($display_specification) @if ($project->use_equipment) Overig @endif &nbsp; @endif</th>
        <th style="width: 51px" class="qty-small">Totaal</th>
      </tr>
    </thead>
    <tbody>
      @foreach (Chapter::where('project_id','=', $project->id)->orderBy('priority')->get() as $chapter)
      @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->orderBy('priority')->get() as $activity)
      <?php
        if (!EstimateOverview::activityTotalProfit($activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip))
          continue;
      ?>
      <tr>
        <td style="width: 181px" class="qty-small">{{ $chapter->chapter_name }}</td>
        <td style="width: 170px" class="qty-small">{{ $activity->activity_name }}</td>
        <td style="width: 40px" class="qty-small"><span class="pull-right">@if ($display_specification) @if(0)  {{ number_format(EstimateOverview::laborTotal($activity), 2, ",",".") }} @endif &nbsp; @endif</td>
        <td style="width: 51px" class="qty-small"><span class="pull-right total-ex-tax">@if ($display_specification) {{ '&euro; '.number_format(EstimateOverview::laborActivity($activity), 2, ",",".") }} @endif</span></td>
        <td style="width: 51px" class="qty-small"><span class="pull-right total-ex-tax">@if ($display_specification) {{ '&euro; '.number_format(EstimateOverview::materialActivityProfit($activity, $project->profit_calc_contr_mat), 2, ",",".") }} @endif</span></td>
        <td style="width: 51px" class="qty-small"><span class="pull-right">@if ($display_specification) @if ($project->use_equipment) {{ '&euro; '.number_format(EstimateOverview::equipmentActivityProfit($activity, $project->profit_calc_contr_equip), 2, ",",".") }} @endif &nbsp; @endif</span></td>
        <td style="width: 51px" class="qty-small"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::activityTotalProfit($activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip), 2, ",",".") }} </td>
      </tr>
      @endforeach
      @endforeach
      @foreach (Chapter::where('project_id','=', $project->id)->orderBy('priority')->get() as $chapter)
      @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->orderBy('priority')->get() as $activity)
      <?php
        if (!EstimateOverview::activityTotalProfit($activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip))
          continue;
      ?>
      <tr>
        <td style="width: 181px" class="qty-small"><strong>{{ $chapter->chapter_name }}</strong></td>
        <td style="width: 170px" class="qty-small">{{ $activity->activity_name }}</td>
        <td style="width: 40px" class="qty-small"><span class="pull-right">@if ($display_specification) {{ number_format(EstimateOverview::laborTotal($activity), 2, ",",".") }} @endif</td>
        <td style="width: 51px" class="qty-small"><span class="pull-right total-ex-tax">@if ($display_specification) {{ '&euro; '.number_format(EstimateOverview::laborActivity($activity), 2, ",",".") }} @endif</span></td>
        <td style="width: 51px" class="qty-small"><span class="pull-right total-ex-tax">@if ($display_specification) {{ '&euro; '.number_format(EstimateOverview::materialActivityProfit($activity, $project->profit_calc_subcontr_mat), 2, ",",".") }} @endif</span></td>
        <td style="width: 51px" class="qty-small"><span class="pull-right">@if ($display_specification) {{ '&euro; '.number_format(EstimateOverview::equipmentActivityProfit($activity, $project->profit_calc_subcontr_equip), 2, ",",".") }} @endif</span></td>
        <td style="width: 51px" class="qty-small"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::activityTotalProfit($activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip), 2, ",",".") }} </td>
      </tr>
      @endforeach
      @endforeach
    </tbody>
   </table>

   <h2 class="name">Totalen stelposten</h2>
   <hr color="#000" size="1">

   <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr style="page-break-after: always;">
          <th style="width: 181px" class="qty-small">&nbsp;</th>
          <th style="width: 170px" class="qty-small">&nbsp;</th>
          <th style="width: 40px" class="qty-small">@if ($display_specification) @if(0) Arbeidsuren @endif &nbsp;  @endif</th>
          <th style="width: 51px" class="qty-small">@if ($display_specification) Arbeid @endif</th>
          <th style="width: 51px" class="qty-small">@if ($display_specification) Materiaal @endif</th>
          <th style="width: 51px" class="qty-small">@if ($display_specification) @if ($project->use_equipment) Overig @endif &nbsp; @endif</th>
          <th style="width: 51px" class="qty-small">Totaal</th>
        </tr>
      </thead>
      <tbody>
          <td style="width: 181px" class="qty-small">&nbsp;</td>
          <td style="width: 170px" class="qty-small">&nbsp;</td>
          <td style="width: 40px" class="qty-small"><span class="pull-right">@if ($display_specification) @if(0) {{ EstimateOverview::laborSuperTotalAmount($project) }} @endif &nbsp; @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right">@if ($display_specification) {{ '&euro; '.number_format(EstimateOverview::laborSuperTotal($project), 2, ",",".") }} @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right">@if ($display_specification) {{ '&euro; '.number_format(EstimateOverview::materialSuperTotal($project), 2, ",",".") }} @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right">@if ($display_specification) @if ($project->use_equipment) {{ '&euro; '.number_format(EstimateOverview::equipmentSuperTotal($project), 2, ",",".") }} @endif &nbsp; @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::superTotal($project), 2, ",",".") }}</span></td>
      </tbody>
    </table>
  <?#--ESTIMATE TOTAL END--?>
  @endif

  @if ($project->use_less)
  <?#--PAGE HEADER SECOND START--?>
      <div style="page-break-after:always;"></div>
      <header class="clearfix">
      <div id="logo">
      <?php
        if ($image_height > 0)
          echo "<img style=\"width:300px;height:" . $image_height . "px;\" src=\"" . $image_src . "\"/>";
      ?>
      </div>
        <div id="invoice">
          <div><?php if (Auth::user()->pref_use_ct_numbering) { echo $invoice->invoice_code; } else { echo $invoice->book_code; } ?></div>
          <div>{{ $project->project_name }}</div>
          <div>{{ date("j M Y") }}</div>
      </div>
      </header>
      <?#--PAGE HEADER SECOND END--?>

    <?#--LESS TOTAL START--?>

   <h2 class="name">Minderwerk per werkzaamheid</h2>
   <hr color="#000" size="1">

  <table border="0" cellspacing="0" cellpadding="0">
    <thead>
      <tr style="page-break-after: always;">
        <th style="width: 181px" class="qty-small">Onderdeel</th>
        <th style="width: 170px" class="qty-small">Werkzaamheid</th>
        <th style="width: 40px" class="qty-small">@if ($display_specification) @if(0) Arbeidsuren @endif &nbsp;  @endif</th>
        <th style="width: 51px" class="qty-small">@if ($display_specification) Arbeid @endif</th>
        <th style="width: 51px" class="qty-small">@if ($display_specification) Materiaal @endif</th>
        <th style="width: 51px" class="qty-small">@if ($display_specification) @if ($project->use_equipment) Overig @endif &nbsp; @endif</th>
        <th style="width: 51px" class="qty-small">Totaal</th>
      </tr>
    </thead>
    <tbody>
      @foreach (Chapter::where('project_id','=', $project->id)->orderBy('priority')->get() as $chapter)
      @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->orderBy('priority')->get() as $activity)
      <?php
        if (!LessOverview::activityTotalProfit($activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip, $project))
          continue;
      ?>
      <tr>
        <td style="width: 181px" class="qty-small">{{ $chapter->chapter_name }}</td>
        <td style="width: 170px" class="qty-small">{{ $activity->activity_name }}</td>
        <td style="width: 40px" class="qty-small"><span class="pull-right">@if ($display_specification) @if(0) {{ number_format(LessOverview::laborTotal($activity), 2, ",",".") }} @endif &nbsp; @endif</td>
        <td style="width: 51px" class="qty-small"><span class="pull-right total-ex-tax">@if ($display_specification) {{ '&euro; '.number_format(LessOverview::laborActivity($activity, $project), 2, ",",".") }} @endif</span></td>
        <td style="width: 51px" class="qty-small"><span class="pull-right total-ex-tax">@if ($display_specification) {{ '&euro; '.number_format(LessOverview::materialActivityProfit($activity, $project->profit_calc_contr_mat), 2, ",",".") }} @endif</span></td>
        <td style="width: 51px" class="qty-small"><span class="pull-right">@if ($display_specification) @if ($project->use_equipment) {{ '&euro; '.number_format(LessOverview::equipmentActivityProfit($activity, $project->profit_calc_contr_equip), 2, ",",".") }} @endif &nbsp; @endif</span></td>
        <td style="width: 51px" class="qty-small"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::activityTotalProfit($activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip, $project), 2, ",",".") }} </td>
      </tr>
      @endforeach
      @endforeach
      @foreach (Chapter::where('project_id','=', $project->id)->orderBy('priority')->get() as $chapter)
      @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->orderBy('priority')->get() as $activity)
      <?php
        if (!LessOverview::activityTotalProfit($activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip, $project))
          continue;
      ?>
      <tr>
        <td style="width: 181px" class="qty-small"><strong>{{ $chapter->chapter_name }}</strong></td>
        <td style="width: 170px" class="qty-small">{{ $activity->activity_name }}</td>
        <td style="width: 40px" class="qty-small"><span class="pull-right">@if ($display_specification) {{ number_format(LessOverview::laborTotal($activity), 2, ",",".") }} @endif</td>
        <td style="width: 51px" class="qty-small"><span class="pull-right total-ex-tax">@if ($display_specification) {{ '&euro; '.number_format(LessOverview::laborActivity($activity, $project), 2, ",",".") }} @endif</span></td>
        <td style="width: 51px" class="qty-small"><span class="pull-right total-ex-tax">@if ($display_specification) {{ '&euro; '.number_format(LessOverview::materialActivityProfit($activity, $project->profit_calc_subcontr_mat), 2, ",",".") }} @endif</span></td>
        <td style="width: 51px" class="qty-small"><span class="pull-right">@if ($display_specification) {{ '&euro; '.number_format(LessOverview::equipmentActivityProfit($activity, $project->profit_calc_subcontr_equip), 2, ",",".") }} @endif</span></td>
        <td style="width: 51px" class="qty-small"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::activityTotalProfit($activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip, $project), 2, ",",".") }} </td>
      </tr>
      @endforeach
      @endforeach
    </tbody>
   </table>

   <h2 class="name">Totalen minderwerk</h2>
   <hr color="#000" size="1">

   <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr style="page-break-after: always;">
          <th style="width: 181px" class="qty-small">&nbsp;</th>
          <th style="width: 170px" class="qty-small">&nbsp;</th>
          <th style="width: 40px" class="qty-small">@if ($display_specification) @if(0) Arbeidsuren @endif &nbsp;  @endif</th>
          <th style="width: 51px" class="qty-small">@if ($display_specification) Arbeid @endif</th>
          <th style="width: 51px" class="qty-small">@if ($display_specification) Materiaa @endif</th>
          <th style="width: 51px" class="qty-small">@if ($display_specification) @if ($project->use_equipment) Overig @endif &nbsp; @endif</th>
          <th style="width: 51px" class="qty-small">Totaal</th>
        </tr>
      </thead>
      <tbody>
          <td style="width: 181px" class="qty-small">&nbsp;</td>
          <td style="width: 170px" class="qty-small">&nbsp;</td>
          <td style="width: 40px" class="qty-small"><span class="pull-right">@if ($display_specification) @if(0) {{ LessOverview::laborSuperTotalAmount($project) }} @endif &nbsp; @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right">@if ($display_specification) {{ '&euro; '.number_format(LessOverview::laborSuperTotal($project), 2, ",",".") }} @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right">@if ($display_specification) {{ '&euro; '.number_format(LessOverview::materialSuperTotal($project), 2, ",",".") }} @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right">@if ($display_specification) @if ($project->use_equipment) {{ '&euro; '.number_format(LessOverview::equipmentSuperTotal($project), 2, ",",".") }} @endif &nbsp; @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::superTotal($project), 2, ",",".") }}</span></td>
      </tbody>
    </table>
  <?#--LESS TOTAL END--?>
  @endif

  @if ($project->use_more)
  <?#--PAGE HEADER SECOND START--?>
      <div style="page-break-after:always;"></div>
      <header class="clearfix">
      <div id="logo">
      <?php
        if ($image_height > 0)
          echo "<img style=\"width:300px;height:" . $image_height . "px;\" src=\"" . $image_src . "\"/>";
      ?>
      </div>
        <div id="invoice">
          <div><?php if (Auth::user()->pref_use_ct_numbering) { echo $invoice->invoice_code; } else { echo $invoice->book_code; } ?></div>
          <div>{{ $project->project_name }}</div>
          <div>{{ date("j M Y") }}</div>
      </div>
      </header>
      <?#--PAGE HEADER SECOND END--?>

  <?#--MORE TOTAL START--?>

  <h2 class="name">Meerwerk per werkzaamheid</h2>
  <hr color="#000" size="1">

  <table border="0" cellspacing="0" cellpadding="0">
    <thead>
      <tr style="page-break-after: always;">
        <th style="width: 181px" class="qty-small">Onderdeel</th>
        <th style="width: 170px" class="qty-small">Werkzaamheid</th>
        <th style="width: 40px" class="qty-small">@if ($display_specification) @if(0) Arbeidsuren @endif &nbsp;  @endif</th>
        <th style="width: 51px" class="qty-small">@if ($display_specification) Arbeid @endif</th>
        <th style="width: 51px" class="qty-small">@if ($display_specification) Materiaal @endif</th>
        <th style="width: 51px" class="qty-small">@if ($display_specification) @if ($project->use_equipment) Overig @endif &nbsp; @endif</th>
        <th style="width: 51px" class="qty-small">Totaal</th>
      </tr>
    </thead>
    <tbody>
      @foreach (Chapter::where('project_id','=', $project->id)->orderBy('priority')->get() as $chapter)
      @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->where('detail_id','=',Detail::where('detail_name','=','more')->first()->id)->orderBy('priority')->get() as $activity)
      <tr>
        <td style="width: 181px" class="qty-small">{{ $chapter->chapter_name }}</td>
        <td style="width: 170px" class="qty-small">{{ $activity->activity_name }}</td>
        <td style="width: 40px" class="qty-small"><span class="pull-right">@if ($display_specification) @if(0) {{ number_format(MoreOverview::laborTotal($activity), 2, ",",".") }} @endif &nbsp; @endif</td>
        <td style="width: 51px" class="qty-small"><span class="pull-right total-ex-tax">@if ($display_specification) {{ '&euro; '.number_format(MoreOverview::laborActivity($activity), 2, ",",".") }} @endif</span></td>
        <td style="width: 51px" class="qty-small"><span class="pull-right total-ex-tax">@if ($display_specification) {{ '&euro; '.number_format(MoreOverview::materialActivityProfit($activity, $project->profit_more_contr_mat), 2, ",",".") }} @endif</span></td>
        <td style="width: 51px" class="qty-small"><span class="pull-right">@if ($display_specification) @if ($project->use_equipment) {{ '&euro; '.number_format(MoreOverview::equipmentActivityProfit($activity, $project->profit_more_contr_equip), 2, ",",".") }} @endif &nbsp; @endif</span></td>
        <td style="width: 51px" class="qty-small"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::activityTotalProfit($activity, $project->profit_more_contr_mat, $project->profit_more_contr_equip), 2, ",",".") }} </td>
      </tr>
      @endforeach
      @endforeach
      @foreach (Chapter::where('project_id','=', $project->id)->orderBy('priority')->get() as $chapter)
      @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->where('detail_id','=',Detail::where('detail_name','=','more')->first()->id)->orderBy('priority')->get() as $activity)
      <tr>
        <td style="width: 181px" class="qty-small"><strong>{{ $chapter->chapter_name }}</strong></td>
        <td style="width: 170px" class="qty-small">{{ $activity->activity_name }}</td>
        <td style="width: 40px" class="qty-small"><span class="pull-right">@if ($display_specification) {{ number_format(MoreOverview::laborTotal($activity), 2, ",",".") }} @endif</td>
        <td style="width: 51px" class="qty-small"><span class="pull-right total-ex-tax">@if ($display_specification) {{ '&euro; '.number_format(MoreOverview::laborActivity($activity), 2, ",",".") }} @endif</span></td>
        <td style="width: 51px" class="qty-small"><span class="pull-right total-ex-tax">@if ($display_specification) {{ '&euro; '.number_format(MoreOverview::materialActivityProfit($activity, $project->profit_more_subcontr_mat), 2, ",",".") }} @endif</span></td>
        <td style="width: 51px" class="qty-small"><span class="pull-right">@if ($display_specification) @if ($project->use_equipment) {{ '&euro; '.number_format(MoreOverview::equipmentActivityProfit($activity, $project->profit_more_subcontr_equip), 2, ",",".") }} @endif &nbsp;  @endif</span></td>
        <td style="width: 51px" class="qty-small"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::activityTotalProfit($activity, $project->profit_more_subcontr_mat, $project->profit_more_subcontr_equip), 2, ",",".") }} </td>
      </tr>
      @endforeach
      @endforeach
    </tbody>
   </table>

   <h2 class="name">Totalen meerwerk</h2>
   <hr color="#000" size="1">

   <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr style="page-break-after: always;">
          <th style="width: 181px" class="qty-small">&nbsp;</th>
          <th style="width: 170px" class="qty-small">&nbsp;</th>
          <th style="width: 40px" class="qty-small">@if ($display_specification) @if(0) Arbeidsuren @endif &nbsp;  @endif</th>
          <th style="width: 51px" class="qty-small">@if ($display_specification) Arbeid @endif</th>
          <th style="width: 51px" class="qty-small">@if ($display_specification) Materiaal @endif</th>
          <th style="width: 51px" class="qty-small">@if ($display_specification) @if ($project->use_equipment) Overig @endif &nbsp; @endif</th>
          <th style="width: 51px" class="qty-small">Totaal</th>
        </tr>
      </thead>
      <tbody>
          <td style="width: 181px" class="qty-small">&nbsp;</td>
          <td style="width: 170px" class="qty-small">&nbsp;</td>
          <td style="width: 40px" class="qty-small"><span class="pull-right">@if ($display_specification) @if(0) {{ MoreOverview::laborSuperTotalAmount($project) }} @endif &nbsp; @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right">@if ($display_specification) {{ '&euro; '.number_format(MoreOverview::laborSuperTotal($project), 2, ",",".") }} @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right">@if ($display_specification) {{ '&euro; '.number_format(MoreOverview::materialSuperTotal($project), 2, ",",".") }} @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right">@if ($display_specification) @if ($project->use_equipment) {{ '&euro; '.number_format(MoreOverview::equipmentSuperTotal($project), 2, ",",".") }} @endif &nbsp; @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::superTotal($project), 2, ",",".") }}</span></td>
      </tbody>
    </table>
  <?#--MORE TOTAL END--?>
  @endif

 


  <?#--TOTAL END--?>
  @else
  <?#--CONT & SUBCONT START--?>
   

    <?#--PAGE HEADER SECOND START--?>
      <div style="page-break-after:always;"></div>
      <header class="clearfix">
      <div id="logo">
      <?php
        if ($image_height > 0)
          echo "<img style=\"width:300px;height:" . $image_height . "px;\" src=\"" . $image_src . "\"/>";
      ?>
      </div>
        <div id="invoice">
          <div><?php if (Auth::user()->pref_use_ct_numbering) { echo $invoice->invoice_code; } else { echo $invoice->book_code; } ?></div>
          <div>{{ $project->project_name }}</div>
          <div>{{ date("j M Y") }}</div>
      </div>
      </header>
      <?#--PAGE HEADER SECOND END--?>

  <?#--CALCULATION CONT & SUBCONT START--?>

  <h2 class="name">Totalen voor calculatie</h2>
  <hr color="#000" size="1">

  <h2 class="name">Aanneming</h2>
  <table border="0" cellspacing="0" cellpadding="0">
    <thead>
      <tr style="page-break-after: always;">
          <th style="width: 181px" class="qty-small">Onderdeel</th>
          <th style="width: 170px" class="qty-small">Werkzaamheid</th>
          <th style="width: 40px" class="qty-small">@if ($display_specification) @if(0) Arbeidsuren @endif &nbsp;  @endif</th>
          <th style="width: 51px" class="qty-small">@if ($display_specification) Arbeid @endif</th>
          <th style="width: 51px" class="qty-small">@if ($display_specification) Materiaal @endif</th>
          <th style="width: 51px" class="qty-small">@if ($display_specification) @if ($project->use_equipment) Overig @endif &nbsp; @endif</th>
          <th style="width: 51px" class="qty-small">Totaal</th>
      </tr>
    </thead>
    <tbody>
      @foreach (Chapter::where('project_id','=', $project->id)->orderBy('priority')->get() as $chapter)
      @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->where('part_type_id','=',PartType::where('type_name','=','calculation')->first()->id)->whereNull('detail_id')->orderBy('priority')->get() as $activity)
      <tr>
        <td style="width: 181px" class="qty-small">{{ $chapter->chapter_name }}</td>
        <td style="width: 170px" class="qty-small">{{ $activity->activity_name }}</td>
        <td style="width: 40px" class="qty-small"><span class="pull-right">@if ($display_specification) @if(0) {{ number_format(CalculationOverview::laborTotal($activity), 2, ",",".") }} @endif &nbsp; @endif</td>
        <td style="width: 51px" class="qty-small"><span class="pull-right total-ex-tax">@if ($display_specification)  {{ '&euro; '.number_format(CalculationOverview::laborActivity($project->hour_rate, $activity), 2, ",",".") }} @endif</span></td>
        <td style="width: 51px" class="qty-small"><span class="pull-right total-ex-tax">@if ($display_specification)  {{ '&euro; '.number_format(CalculationOverview::materialActivityProfit($activity, $project->profit_calc_contr_mat), 2, ",",".") }} @endif</span></td>
        <td style="width: 51px" class="qty-small"><span class="pull-right">@if ($display_specification) @if ($project->use_equipment) {{ '&euro; '.number_format(CalculationOverview::equipmentActivityProfit($activity, $project->profit_calc_contr_equip), 2, ",",".") }} @endif &nbsp; @endif</span></td>
        <td style="width: 51px" class="qty-small"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::activityTotalProfit($project->hour_rate, $activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip), 2, ",",".") }}</td>
      </tr>
      @endforeach
      @endforeach
      <tr style="page-break-after: always;">
        <th style="width: 181px" class="qty-small"><strong>Totaal</strong></th>
        <th style="width: 170px" class="qty-small">&nbsp;</th>
        <td style="width: 40px" class="qty-small"><strong><span class="pull-right">@if ($display_specification) @if(0) {{ CalculationOverview::contrLaborTotalAmount($project) }} @endif &nbsp; @endif</span></strong></td>
        <td style="width: 51px" class="qty-small"><strong><span class="pull-right">@if ($display_specification) {{ '&euro; '.number_format(CalculationOverview::contrLaborTotal($project), 2, ",",".") }} @endif</span></strong></td>
        <td style="width: 51px" class="qty-small"><strong><span class="pull-right">@if ($display_specification) {{ '&euro; '.number_format(CalculationOverview::contrMaterialTotal($project), 2, ",",".") }} @endif</span></strong></td>
        <td style="width: 51px" class="qty-small"><strong><span class="pull-right">@if ($display_specification) @if ($project->use_equipment) {{ '&euro; '.number_format(CalculationOverview::contrEquipmentTotal($project), 2, ",",".") }} @endif &nbsp; @endif</span></strong></td>
        <td style="width: 51px" class="qty-small"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::contrTotal($project), 2, ",",".") }}</span></strong></td>
      </tr>
    </table>
   <h2 class="name">Onderaanneming</h2>
   <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr style="page-break-after: always;">
          <th style="width: 181px" class="qty-small">Onderdeel</th>
          <th style="width: 170px" class="qty-small">Werkzaamheid</th>
          <th style="width: 40px" class="qty-small">@if ($display_specification) @if(0) Arbeidsuren @endif &nbsp;  @endif</th>
          <th style="width: 51px" class="qty-small">@if ($display_specification) Arbeid @endif</th>
          <th style="width: 51px" class="qty-small">@if ($display_specification) Materiaal @endif</th>
          <th style="width: 51px" class="qty-small">@if ($display_specification) @if ($project->use_equipment) Overig @endif &nbsp; @endif</th>
          <th style="width: 51px" class="qty-small">Totaal</th>
        </tr>
      </thead>
      <tbody>
        @foreach (Chapter::where('project_id','=', $project->id)->orderBy('priority')->get() as $chapter)
        @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->where('part_type_id','=',PartType::where('type_name','=','calculation')->first()->id)->whereNull('detail_id')->orderBy('priority')->get() as $activity)
        <tr>
          <td style="width: 181px" class="qty-small">{{ $chapter->chapter_name }}</td>
          <td style="width: 170px" class="qty-small">{{ $activity->activity_name }}</td>
          <td style="width: 40px" class="qty-small"><span class="pull-right">@if ($display_specification) @if(0) {{ number_format(CalculationOverview::laborTotal($activity), 2, ",",".") }} @endif &nbsp; @endif</td>
          <td style="width: 51px" class="qty-small"><span class="pull-right total-ex-tax">@if ($display_specification) {{ '&euro; '.number_format(CalculationOverview::laborActivity($project->hour_rate, $activity), 2, ",",".") }} @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right total-ex-tax">@if ($display_specification) {{ '&euro; '.number_format(CalculationOverview::materialActivityProfit($activity, $project->profit_calc_subcontr_mat), 2, ",",".") }} @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right">@if ($display_specification) @if ($project->use_equipment) {{ '&euro; '.number_format(CalculationOverview::equipmentActivityProfit($activity, $project->profit_calc_subcontr_equip), 2, ",",".") }} @endif &nbsp; @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::activityTotalProfit($project->hour_rate, $activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip), 2, ",",".") }} </td>
        </tr>
        @endforeach
        @endforeach
         <tr style="page-break-after: always;">
          <th style="width: 181px" class="qty-small"><strong>Totaal</strong></th>
          <th style="width: 170px" class="qty-small">&nbsp;</th>
          <td style="width: 40px" class="qty-small"><strong><span class="pull-right">@if ($display_specification) @if(0) {{ CalculationOverview::subcontrLaborTotalAmount($project) }} @endif &nbsp; @endif</span></strong></td>
          <td style="width: 51px" class="qty-small"><strong><span class="pull-right">@if ($display_specification) {{ '&euro; '.number_format(CalculationOverview::subcontrLaborTotal($project), 2, ",",".") }} @endif</span></strong></td>
          <td style="width: 51px" class="qty-small"><strong><span class="pull-right">@if ($display_specification) {{ '&euro; '.number_format(CalculationOverview::subcontrMaterialTotal($project), 2, ",",".") }} @endif</span></strong></td>
          <td style="width: 51px" class="qty-small"><strong><span class="pull-right">@if ($display_specification) @if ($project->use_equipment) {{ '&euro; '.number_format(CalculationOverview::subcontrEquipmentTotal($project), 2, ",",".") }} @endif &nbsp; @endif</span></strong></td>
          <td style="width: 51px" class="qty-small"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::subcontrTotal($project), 2, ",",".") }}</span></strong></td>
        </tr>
      </tbody>
   </table>

   <h2 class="name">Totalen voor calculatie</h2>
   <hr color="#000" size="1">

   <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr style="page-break-after: always;">
          <th style="width: 181px" class="qty-small">&nbsp;</th>
          <th style="width: 170px" class="qty-small">&nbsp;</th>
          <th style="width: 40px" class="qty-small">@if ($display_specification) @if(0) Arbeidsuren @endif &nbsp;  @endif</th>
          <th style="width: 51px" class="qty-small">@if ($display_specification) Arbeid @endif</th>
          <th style="width: 51px" class="qty-small">@if ($display_specification) Materiaal @endif</th>
          <th style="width: 51px" class="qty-small">@if ($display_specification) @if ($project->use_equipment) Overig @endif &nbsp; @endif</th>
          <th style="width: 51px" class="qty-small">Totaal</th>
        </tr>
      </thead>
      <tbody>
        <tr style="page-break-after: always;">
          <td style="width: 181px" class="qty-small">&nbsp;</td>
          <td style="width: 170px" class="qty-small">&nbsp;</td>
          <td style="width: 40px" class="qty-small"><span class="pull-right">@if ($display_specification) @if(0) {{ CalculationOverview::laborSuperTotalAmount($project) }} @endif &nbsp; @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right">@if ($display_specification) {{ '&euro; '.number_format(CalculationOverview::laborSuperTotal($project), 2, ",",".") }} @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right">@if ($display_specification) {{ '&euro; '.number_format(CalculationOverview::materialSuperTotal($project), 2, ",",".") }} @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right">@if ($display_specification) @if ($project->use_equipment) {{ '&euro; '.number_format(CalculationOverview::equipmentSuperTotal($project), 2, ",",".") }} @endif &nbsp; @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::superTotal($project), 2, ",",".") }}</span></td>
        </tr>
    </table>
    <?#--CALCULATION CONT & SUBCONT END--?>


   @if ($project->use_estim)
    <?#--PAGE HEADER SECOND START--?>
      <div style="page-break-after:always;"></div>
      <header class="clearfix">
      <div id="logo">
      <?php
        if ($image_height > 0)
          echo "<img style=\"width:300px;height:" . $image_height . "px;\" src=\"" . $image_src . "\"/>";
      ?>
      </div>
        <div id="invoice">
          <div><?php if (Auth::user()->pref_use_ct_numbering) { echo $invoice->invoice_code; } else { echo $invoice->book_code; } ?></div>
          <div>{{ $project->project_name }}</div>
          <div>{{ date("j M Y") }}</div>
      </div>
      </header>
      <?#--PAGE HEADER SECOND END--?>

    <?#--ESTIMATE CONT & SUBCOINT START--?>

    <h2 class="name">Totalen voor stelposten</h2>
    <hr color="#000" size="1">

    <h2 class="name">Aanneming</h2>
    <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr style="page-break-after: always;">
          <th style="width: 181px" class="qty-small">Onderdeel</th>
          <th style="width: 170px" class="qty-small">Werkzaamheid</th>
          <th style="width: 40px" class="qty-small">@if ($display_specification) @if(0) Arbeidsuren @endif &nbsp;  @endif</th>
          <th style="width: 51px" class="qty-small">@if ($display_specification) Arbeid @endif</th>
          <th style="width: 51px" class="qty-small">@if ($display_specification) Materiaal @endif</th>
          <th style="width: 51px" class="qty-small">@if ($display_specification) @if ($project->use_equipment) Overig @endif &nbsp; @endif</th>
          <th style="width: 51px" class="qty-small">Totaal</th>
        </tr>
      </thead>
      <tbody>
        @foreach (Chapter::where('project_id','=', $project->id)->orderBy('priority')->get() as $chapter)
        @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->orderBy('priority')->get() as $activity)
        <?php
          if (!EstimateOverview::activityTotalProfit($activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip))
            continue;
        ?>
        <tr>
          <td style="width: 181px" class="qty-small">{{ $chapter->chapter_name }}</td>
          <td style="width: 170px" class="qty-small">{{ $activity->activity_name }}</td>
          <td style="width: 40px" class="qty-small"><span class="pull-right">@if ($display_specification)  @if(0) {{ number_format(EstimateOverview::laborTotal($activity), 2, ",",".") }} @endif &nbsp; @endif</td>
          <td style="width: 51px" class="qty-small"><span class="pull-right total-ex-tax">@if ($display_specification)  {{ '&euro; '.number_format(EstimateOverview::laborActivity($activity), 2, ",",".") }} @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right total-ex-tax">@if ($display_specification)  {{ '&euro; '.number_format(EstimateOverview::materialActivityProfit($activity, $project->profit_calc_contr_mat), 2, ",",".") }} @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right">@if ($display_specification) @if ($project->use_equipment) {{ '&euro; '.number_format(EstimateOverview::equipmentActivityProfit($activity, $project->profit_calc_contr_equip), 2, ",",".") }} @endif &nbsp; @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::activityTotalProfit($activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip), 2, ",",".") }} </td>
        </tr>
        @endforeach
        @endforeach
        <tr style="page-break-after: always;">
          <th style="width: 181px" class="qty-small"><strong>Totaal</strong></th>
          <th style="width: 170px" class="qty-small">&nbsp;</th>
          <td style="width: 40px" class="qty-small"><strong><span class="pull-right">@if ($display_specification)  @if(0) {{ EstimateOverview::contrLaborTotalAmount($project) }} @endif &nbsp; @endif</span></strong></td>
          <td style="width: 51px" class="qty-small"><strong><span class="pull-right">@if ($display_specification)  {{ '&euro; '.number_format(EstimateOverview::contrLaborTotal($project), 2, ",",".") }} @endif</span></strong></td>
          <td style="width: 51px" class="qty-small"><strong><span class="pull-right">@if ($display_specification)  {{ '&euro; '.number_format(EstimateOverview::contrMaterialTotal($project), 2, ",",".") }} @endif</span></strong></td>
          <td style="width: 51px" class="qty-small"><strong><span class="pull-right">@if ($display_specification)  @if ($project->use_equipment) {{ '&euro; '.number_format(EstimateOverview::contrEquipmentTotal($project), 2, ",",".") }} @endif &nbsp; @endif</span></strong></td>
          <td style="width: 51px" class="qty-small"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::contrTotal($project), 2, ",",".") }}</span></strong></td>
        </tr>
      </tbody>
    </table>
   <h2 class="name">Onderaanneming</h2>
   <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr style="page-break-after: always;">
          <th style="width: 181px" class="qty-small">Onderdeel</th>
          <th style="width: 170px" class="qty-small">Werkzaamheid</th>
          <th style="width: 40px" class="qty-small">@if ($display_specification) @if(0) Arbeidsuren @endif &nbsp;  @endif</th>
          <th style="width: 51px" class="qty-small">@if ($display_specification) Arbeid @endif</th>
          <th style="width: 51px" class="qty-small">@if ($display_specification) Materiaal @endif</th>
          <th style="width: 51px" class="qty-small">@if ($display_specification) @if ($project->use_equipment) Overig @endif &nbsp; @endif</th>
          <th style="width: 51px" class="qty-small">Totaal</th>
        </tr>
      </thead>
      <tbody>
        @foreach (Chapter::where('project_id','=', $project->id)->orderBy('priority')->get() as $chapter)
        @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->orderBy('priority')->get() as $activity)
        <?php
          if (!EstimateOverview::activityTotalProfit($activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip))
            continue;
        ?>
        <tr>
          <td style="width: 181px" class="qty-small">{{ $chapter->chapter_name }}</td>
          <td style="width: 170px" class="qty-small">{{ $activity->activity_name }}</td>
          <td style="width: 40px" class="qty-small"><span class="pull-right">@if ($display_specification) @if(0) {{ number_format(EstimateOverview::laborTotal($activity), 2, ",",".") }} @endif &nbsp; @endif</td>
          <td style="width: 51px" class="qty-small"><span class="pull-right total-ex-tax">@if ($display_specification) {{ '&euro; '.number_format(EstimateOverview::laborActivity($activity), 2, ",",".") }} @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right total-ex-tax">@if ($display_specification) {{ '&euro; '.number_format(EstimateOverview::materialActivityProfit($activity, $project->profit_calc_subcontr_mat), 2, ",",".") }} @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right">@if ($display_specification) @if ($project->use_equipment) {{ '&euro; '.number_format(EstimateOverview::equipmentActivityProfit($activity, $project->profit_calc_subcontr_equip), 2, ",",".") }} @endif &nbsp; @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::activityTotalProfit($activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip), 2, ",",".") }} </td>
        </tr>
        @endforeach
        @endforeach
          <th style="width: 181px" class="qty-small"><strong>Totaal</strong></th>
          <th style="width: 170px" class="qty-small">&nbsp;</th>
          <td style="width: 40px" class="qty-small"><strong><span class="pull-right">@if ($display_specification) @if(0) {{ EstimateOverview::subcontrLaborTotalAmount($project) }} @endif &nbsp; @endif</span></strong></td>
          <td style="width: 51px" class="qty-small"><strong><span class="pull-right">@if ($display_specification) {{ '&euro; '.number_format(EstimateOverview::subcontrLaborTotal($project), 2, ",",".") }} @endif</span></strong></td>
          <td style="width: 51px" class="qty-small"><strong><span class="pull-right">@if ($display_specification) {{ '&euro; '.number_format(EstimateOverview::subcontrMaterialTotal($project), 2, ",",".") }} @endif</span></strong></td>
          <td style="width: 51px" class="qty-small"><strong><span class="pull-right">@if ($display_specification) @if ($project->use_equipment)  {{ '&euro; '.number_format(EstimateOverview::subcontrEquipmentTotal($project), 2, ",",".") }} @endif &nbsp; @endif</span></strong></td>
          <td style="width: 51px" class="qty-small"><strong><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::subcontrTotal($project), 2, ",",".") }}</span></strong></td>
        </tr>
      </tbody>
   </table>

   <h2 class="name">Totalen voor stelposten</h2>
   <hr color="#000" size="1">

   <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr style="page-break-after: always;">
          <th style="width: 181px" class="qty-small">&nbsp;</th>
          <th style="width: 170px" class="qty-small">&nbsp;</th>
          <th style="width: 40px" class="qty-small">@if ($display_specification) @if(0) Arbeidsuren @endif &nbsp;  @endif</th>
          <th style="width: 51px" class="qty-small">@if ($display_specification) Arbeid @endif</th>
          <th style="width: 51px" class="qty-small">@if ($display_specification) Materiaal @endif</th>
          <th style="width: 51px" class="qty-small">@if ($display_specification) @if ($project->use_equipment) Overig @endif &nbsp; @endif</th>
          <th style="width: 51px" class="qty-small">Totaal</th>
        </tr>
      </thead>
      <tbody>
        <tr style="page-break-after: always;">
          <td style="width: 181px" class="qty-small">&nbsp;</td>
          <td style="width: 170px" class="qty-small">&nbsp;</td>
          <td style="width: 40px" class="qty-small"><span class="pull-right">@if ($display_specification) @if(0) {{ EstimateOverview::laborSuperTotalAmount($project) }} @endif &nbsp; @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right">@if ($display_specification) {{ '&euro; '.number_format(EstimateOverview::laborSuperTotal($project), 2, ",",".") }} @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right">@if ($display_specification) {{ '&euro; '.number_format(EstimateOverview::materialSuperTotal($project), 2, ",",".") }} @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right">@if ($display_specification) @if ($project->use_equipment) {{ '&euro; '.number_format(EstimateOverview::equipmentSuperTotal($project), 2, ",",".") }} @endif &nbsp; @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right">{{ '&euro; '.number_format(EstimateOverview::superTotal($project), 2, ",",".") }}</span></td>
        </tr>
    </table>
    <?#--ESTIMATE CONT & SUBCOINT END--?>
    @endif

    @if ($project->use_less)
    <?#--PAGE HEADER SECOND START--?>
      <div style="page-break-after:always;"></div>
      <header class="clearfix">
      <div id="logo">
      <?php
        if ($image_height > 0)
          echo "<img style=\"width:300px;height:" . $image_height . "px;\" src=\"" . $image_src . "\"/>";
      ?>
      </div>
        <div id="invoice">
          <div><?php if (Auth::user()->pref_use_ct_numbering) { echo $invoice->invoice_code; } else { echo $invoice->book_code; } ?></div>
          <div>{{ $project->project_name }}</div>
          <div>{{ date("j M Y") }}</div>
      </div>
      </header>
      <?#--PAGE HEADER SECOND END--?>

    
    <?#--LESS CONT & SUBCOINT START--?>

    <h2 class="name">Totalen minderwerk</h2>
    <hr color="#000" size="1">

    <h2 class="name">Aanneming</h2>
    <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr style="page-break-after: always;">
          <th style="width: 181px" class="qty-small">Onderdeel</th>
          <th style="width: 170px" class="qty-small">Werkzaamheid</th>
          <th style="width: 40px" class="qty-small">@if ($display_specification) @if(0) Arbeidsuren @endif &nbsp;  @endif</th>
          <th style="width: 51px" class="qty-small">@if ($display_specification) Arbeid @endif</th>
          <th style="width: 51px" class="qty-small">@if ($display_specification) Materiaal @endif</th>
          <th style="width: 51px" class="qty-small">@if ($display_specification) @if ($project->use_equipment) Overig @endif &nbsp; @endif</th>
          <th style="width: 51px" class="qty-small">Totaal</th>
        </tr>
      </thead>
      <tbody>
        @foreach (Chapter::where('project_id','=', $project->id)->orderBy('priority')->get() as $chapter)
        @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->orderBy('priority')->get() as $activity)
        <?php
          if (!LessOverview::activityTotalProfit($activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip, $project))
            continue;
        ?>
        <tr>
          <td style="width: 181px" class="qty-small">{{ $chapter->chapter_name }}</td>
          <td style="width: 170px" class="qty-small">{{ $activity->activity_name }}</td>
          <td style="width: 40px" class="qty-small"><span class="pull-right">@if ($display_specification) @if(0) {{ number_format(LessOverview::laborTotal($activity), 2, ",",".") }} @endif &nbsp; @endif</td>
          <td style="width: 51px" class="qty-small"><span class="pull-right total-ex-tax">@if ($display_specification) {{ '&euro; '.number_format(LessOverview::laborActivity($activity, $project), 2, ",",".") }} @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right total-ex-tax">@if ($display_specification) {{ '&euro; '.number_format(LessOverview::materialActivityProfit($activity, $project->profit_calc_contr_mat), 2, ",",".") }} @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right">@if ($display_specification) @if ($project->use_equipment) {{ '&euro; '.number_format(LessOverview::equipmentActivityProfit($activity, $project->profit_calc_contr_equip), 2, ",",".") }} @endif &nbsp; @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::activityTotalProfit($activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip, $project), 2, ",",".") }} </td>
        </tr>
        @endforeach
        @endforeach
        <tr style="page-break-after: always;">
          <td style="width: 181px" class="qty-small"><strong>Totaal</strong></td>
          <td style="width: 170px" class="qty-small">&nbsp;</td>
          <td style="width: 40px" class="qty-small"><strong><span class="pull-right">@if ($display_specification) @if(0) {{ LessOverview::contrLaborTotalAmount($project) }} @endif &nbsp; @endif</span></strong></td>
          <td style="width: 51px" class="qty-small"><strong><span class="pull-right">@if ($display_specification) {{ '&euro; '.number_format(LessOverview::contrLaborTotal($project), 2, ",",".") }} @endif</span></strong></td>
          <td style="width: 51px" class="qty-small"><strong><span class="pull-right">@if ($display_specification) {{ '&euro; '.number_format(LessOverview::contrMaterialTotal($project), 2, ",",".") }} @endif</span></strong></td>
          <td style="width: 51px" class="qty-small"><strong><span class="pull-right">@if ($display_specification) @if ($project->use_equipment) {{ '&euro; '.number_format(LessOverview::contrEquipmentTotal($project), 2, ",",".") }} @endif &nbsp; @endif</span></strong></td>
          <td style="width: 51px" class="qty-small"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::contrTotal($project), 2, ",",".") }}</span></strong></td>
        </tr>
      </tbody>
   </table>
   <h2 class="name">Onderaanneming</h2>
   <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr style="page-break-after: always;">
          <th style="width: 181px" class="qty-small">Onderdeel</th>
          <th style="width: 170px" class="qty-small">Werkzaamheid</th>
          <th style="width: 40px" class="qty-small">@if ($display_specification) @if(0) Arbeidsuren @endif &nbsp;  @endif</th>
          <th style="width: 51px" class="qty-small">@if ($display_specification) Arbeid @endif</th>
          <th style="width: 51px" class="qty-small">@if ($display_specification) Materiaal @endif</th>
          <th style="width: 51px" class="qty-small">@if ($display_specification) @if ($project->use_equipment) Overig @endif &nbsp; @endif</th>
          <th style="width: 51px" class="qty-small">Totaal</th>
        </tr>
      </thead>
      <tbody>
        @foreach (Chapter::where('project_id','=', $project->id)->orderBy('priority')->get() as $chapter)
        @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->orderBy('priority')->get() as $activity)
        <?php
          if (!LessOverview::activityTotalProfit($activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip, $project))
            continue;
        ?>
        <tr>
          <td style="width: 181px" class="qty-small">{{ $chapter->chapter_name }}</td>
          <td style="width: 170px" class="qty-small">{{ $activity->activity_name }}</td>
          <td style="width: 40px" class="qty-small"><span class="pull-right">@if ($display_specification) @if(0) {{ number_format(LessOverview::laborTotal($activity), 2, ",",".") }} @endif &nbsp; @endif</td>
          <td style="width: 51px" class="qty-small"><span class="pull-right total-ex-tax">@if ($display_specification){{ '&euro; '.number_format(LessOverview::laborActivity($activity, $project), 2, ",",".") }} @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right total-ex-tax">@if ($display_specification){{ '&euro; '.number_format(LessOverview::materialActivityProfit($activity, $project->profit_calc_subcontr_mat), 2, ",",".") }} @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right">@if ($display_specification) @if ($project->use_equipment) {{ '&euro; '.number_format(LessOverview::equipmentActivityProfit($activity, $project->profit_calc_subcontr_equip), 2, ",",".") }} @endif &nbsp; @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::activityTotalProfit($activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip, $project), 2, ",",".") }} </td>
        </tr>
        @endforeach
        @endforeach
          <td style="width: 181px" class="qty-small"><strong>Totaal</strong></td>
          <td style="width: 170px" class="qty-small">&nbsp;</td>
          <td style="width: 40px" class="qty-small"><strong><span class="pull-right">@if ($display_specification) @if(0) {{ LessOverview::subcontrLaborTotalAmount($project) }} @endif &nbsp; @endif</span></strong></td>
          <td style="width: 51px" class="qty-small"><strong><span class="pull-right">@if ($display_specification) {{ '&euro; '.number_format(LessOverview::subcontrLaborTotal($project), 2, ",",".") }} @endif</span></strong></td>
          <td style="width: 51px" class="qty-small"><strong><span class="pull-right">@if ($display_specification) {{ '&euro; '.number_format(LessOverview::subcontrMaterialTotal($project), 2, ",",".") }} @endif</span></strong></td>
          <td style="width: 51px" class="qty-small"><strong><span class="pull-right">@if ($display_specification) @if ($project->use_equipment){{ '&euro; '.number_format(LessOverview::subcontrEquipmentTotal($project), 2, ",",".") }} @endif &nbsp; @endif</span></strong></td>
          <td style="width: 51px" class="qty-small"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::subcontrTotal($project), 2, ",",".") }}</span></strong></td>
        </tr>
      </tbody>
   </table>
   <h2 class="name">Totalen voor minderwerk</h2>
   <hr color="#000" size="1">

   <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr style="page-break-after: always;">
          <th style="width: 181px" class="qty-small">&nbsp;</th>
          <th style="width: 170px" class="qty-small">&nbsp;</th>
          <th style="width: 40px" class="qty-small">@if ($display_specification) @if(0) Arbeidsuren @endif &nbsp;  @endif</th>
          <th style="width: 51px" class="qty-small">@if ($display_specification) Arbeid @endif</th>
          <th style="width: 51px" class="qty-small">@if ($display_specification) Materiaal @endif</th>
          <th style="width: 51px" class="qty-small">@if ($display_specification) @if ($project->use_equipment)Overig @endif &nbsp; @endif</th>
          <th style="width: 51px" class="qty-small">Totaal</th>
        </tr>
      </thead>
      <tbody>
        <tr style="page-break-after: always;">
          <td style="width: 181px" class="qty-small">&nbsp;</td>
          <td style="width: 170px" class="qty-small">&nbsp;</td>
          <td style="width: 40px" class="qty-small"><span class="pull-right">@if ($display_specification) @if(0) {{ LessOverview::laborSuperTotalAmount($project) }} @endif &nbsp; @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right">@if ($display_specification) {{ '&euro; '.number_format(LessOverview::laborSuperTotal($project), 2, ",",".") }} @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right">@if ($display_specification) {{ '&euro; '.number_format(LessOverview::materialSuperTotal($project), 2, ",",".") }} @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right">@if ($display_specification) @if ($project->use_equipment) {{ '&euro; '.number_format(LessOverview::equipmentSuperTotal($project), 2, ",",".") }} @endif &nbsp; @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::superTotal($project), 2, ",",".") }}</span></td>
        </tr>
    </table>
    <?#--LESS CONT & SUBCOINT END--?>
    @endif

    @if ($project->use_more)
    <?#--PAGE HEADER SECOND START--?>
      <div style="page-break-after:always;"></div>
      <header class="clearfix">
      <div id="logo">
      <?php
        if ($image_height > 0)
          echo "<img style=\"width:300px;height:" . $image_height . "px;\" src=\"" . $image_src . "\"/>";
      ?>
      </div>
        <div id="invoice">
          <div><?php if (Auth::user()->pref_use_ct_numbering) { echo $invoice->invoice_code; } else { echo $invoice->book_code; } ?></div>
          <div>{{ $project->project_name }}</div>
          <div>{{ date("j M Y") }}</div>
      </div>
      </header>
      <?#--PAGE HEADER SECOND END--?>

    <?#--MORE CONT & SUBCOINT START--?>

    <h2 class="name">Totalen meerwerk</h2>
    <hr color="#000" size="1">

    <h2 class="name">Aanneming</h2>
    <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr style="page-break-after: always;">
          <th style="width: 181px" class="qty-small">Onderdeel</th>
          <th style="width: 170px" class="qty-small">Werkzaamheid</th>
          <th style="width: 40px" class="qty-small">@if ($display_specification) @if(0) Arbeidsuren @endif &nbsp;  @endif</th>
          <th style="width: 51px" class="qty-small">@if ($display_specification) Arbeid @endif</th>
          <th style="width: 51px" class="qty-small">@if ($display_specification) Materiaal @endif</th>
          <th style="width: 51px" class="qty-small">@if ($display_specification) @if ($project->use_equipment) Overig @endif &nbsp; @endif</th>
          <th style="width: 51px" class="qty-small">Totaal</th>
        </tr>
      </thead>
      <tbody>
        @foreach (Chapter::where('project_id','=', $project->id)->orderBy('priority')->get() as $chapter)
        @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->where('detail_id','=',Detail::where('detail_name','=','more')->first()->id)->orderBy('priority')->get() as $activity)
        <tr>
          <td style="width: 181px" class="qty-small">{{ $chapter->chapter_name }}</td>
          <td style="width: 170px" class="qty-small">{{ $activity->activity_name }}</td>
          <td style="width: 40px" class="qty-small"><span class="pull-right">@if ($display_specification) @if(0) {{ number_format(MoreOverview::laborTotal($activity), 2, ",",".") }} @endif &nbsp; @endif</td>
          <td style="width: 51px" class="qty-small"><span class="pull-right total-ex-tax">@if ($display_specification) {{ '&euro; '.number_format(MoreOverview::laborActivity($activity), 2, ",",".") }} @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right total-ex-tax">@if ($display_specification) {{ '&euro; '.number_format(MoreOverview::materialActivityProfit($activity, $project->profit_more_contr_mat), 2, ",",".") }} @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right">@if ($display_specification) @if ($project->use_equipment) {{ '&euro; '.number_format(MoreOverview::equipmentActivityProfit($activity, $project->profit_more_contr_equip), 2, ",",".") }} @endif &nbsp; @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::activityTotalProfit($activity, $project->profit_more_contr_mat, $project->profit_more_contr_equip), 2, ",",".") }} </td>
        </tr>
        @endforeach
        @endforeach
        <tr style="page-break-after: always;">
          <th style="width: 181px" class="qty-small"><strong>Totaal</strong></th>
          <th style="width: 170px" class="qty-small">&nbsp;</th>
          <td style="width: 40px" class="qty-small"><strong><span class="pull-right">@if ($display_specification) @if(0) {{ MoreOverview::contrLaborTotalAmount($project) }} @endif &nbsp; @endif</span></strong></td>
          <td style="width: 51px" class="qty-small"><strong><span class="pull-right">@if ($display_specification) {{ '&euro; '.number_format(MoreOverview::contrLaborTotal($project), 2, ",",".") }} @endif</span></strong></td>
          <td style="width: 51px" class="qty-small"><strong><span class="pull-right">@if ($display_specification) {{ '&euro; '.number_format(MoreOverview::contrMaterialTotal($project), 2, ",",".") }} @endif</span></strong></td>
          <td style="width: 51px" class="qty-small"><strong><span class="pull-right">@if ($display_specification) @if ($project->use_equipment) {{ '&euro; '.number_format(MoreOverview::contrEquipmentTotal($project), 2, ",",".") }} @endif &nbsp; @endif</span></strong></td>
          <td style="width: 51px" class="qty-small"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::contrTotal($project), 2, ",",".") }}</span></strong></td>
        </tr>
      </tbody>
   </table>
   <h2 class="name">Onderaanneming</h2>
   <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr style="page-break-after: always;">
          <th style="width: 181px" class="qty-small">Onderdeel</th>
          <th style="width: 170px" class="qty-small">Werkzaamheid</th>
          <th style="width: 40px" class="qty-small">@if ($display_specification) @if(0) Arbeidsuren @endif &nbsp;  @endif</th>
          <th style="width: 51px" class="qty-small">@if ($display_specification) Arbeid @endif</th>
          <th style="width: 51px" class="qty-small">@if ($display_specification) Materiaal @endif</th>
          <th style="width: 51px" class="qty-small">@if ($display_specification) @if ($project->use_equipment) Overig @endif &nbsp; @endif</th>
          <th style="width: 51px" class="qty-small">Totaal</th>
        </tr>
      </thead>
      <tbody>
        @foreach (Chapter::where('project_id','=', $project->id)->orderBy('priority')->get() as $chapter)
        @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->where('detail_id','=',Detail::where('detail_name','=','more')->first()->id)->orderBy('priority')->get() as $activity)
        <tr>
          <td style="width: 181px" class="qty-small">{{ $chapter->chapter_name }}</td>
          <td style="width: 170px" class="qty-small">{{ $activity->activity_name }}</td>
          <td style="width: 40px" class="qty-small"><span class="pull-right">@if ($display_specification) @if(0) {{ number_format(MoreOverview::laborTotal($activity), 2, ",",".") }} @endif &nbsp; @endif</td>
          <td style="width: 51px" class="qty-small"><span class="pull-right total-ex-tax">@if ($display_specification) {{ '&euro; '.number_format(MoreOverview::laborActivity($activity), 2, ",",".") }} @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right total-ex-tax">@if ($display_specification) {{ '&euro; '.number_format(MoreOverview::materialActivityProfit($activity, $project->profit_more_subcontr_mat), 2, ",",".") }} @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right">@if ($display_specification) @if ($project->use_equipment) {{ '&euro; '.number_format(MoreOverview::equipmentActivityProfit($activity, $project->profit_more_subcontr_equip), 2, ",",".") }} @endif &nbsp; @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::activityTotalProfit($activity, $project->profit_more_subcontr_mat, $project->profit_more_subcontr_equip), 2, ",",".") }} </td>
        </tr>
        @endforeach
        @endforeach
          <td style="width: 181px" class="qty-small"><strong>Totaal</strong></td>
          <td style="width: 170px" class="qty-small">&nbsp;</td>
          <td style="width: 40px" class="qty-small"><strong><span class="pull-right">@if ($display_specification) @if(0) {{ MoreOverview::subcontrLaborTotalAmount($project) }} @endif &nbsp; @endif</span></strong></td>
          <td style="width: 51px" class="qty-small"><strong><span class="pull-right">@if ($display_specification) {{ '&euro; '.number_format(MoreOverview::subcontrLaborTotal($project), 2, ",",".") }} @endif</span></strong></td>
          <td style="width: 51px" class="qty-small"><strong><span class="pull-right">@if ($display_specification) {{ '&euro; '.number_format(MoreOverview::subcontrMaterialTotal($project), 2, ",",".") }} @endif</span></strong></td>
          <td style="width: 51px" class="qty-small"><strong><span class="pull-right">@if ($display_specification) @if ($project->use_equipment) {{ '&euro; '.number_format(MoreOverview::subcontrEquipmentTotal($project), 2, ",",".") }} @endif &nbsp; @endif</span></strong></td>
          <td style="width: 51px" class="qty-small"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::subcontrTotal($project), 2, ",",".") }}</span></strong></td>
        </tr>
      </tbody>
   </table>

   <h2 class="name">Totalen voor meerwerk</h2>
   <hr color="#000" size="1">

   <table border="0" cellspacing="0" cellpadding="0">
      <thead>
        <tr style="page-break-after: always;">
          <th style="width: 181px" class="qty-small">&nbsp;</th>
          <th style="width: 170px" class="qty-small">&nbsp;</th>
          <th style="width: 40px" class="qty-small">@if ($display_specification) @if(0) Arbeidsuren @endif &nbsp; @endif</th>
          <th style="width: 51px" class="qty-small">@if ($display_specification) Arbeid @endif</th>
          <th style="width: 51px" class="qty-small">@if ($display_specification) Materiaal @endif</th>
          <th style="width: 51px" class="qty-small">@if ($display_specification) @if ($project->use_equipment) Overig @endif &nbsp; @endif</th>
          <th style="width: 51px" class="qty-small">Totaal</th>
        </tr>
      </thead>
      <tbody>
        <tr style="page-break-after: always;">
          <td style="width: 181px" class="qty-small">&nbsp;</td>
          <td style="width: 170px" class="qty-small">&nbsp;</td>
          <td style="width: 40px" class="qty-small"><span class="pull-right">@if ($display_specification) @if(0) {{ MoreOverview::laborSuperTotalAmount($project) }} @endif &nbsp; @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right">@if ($display_specification) {{ '&euro; '.number_format(MoreOverview::laborSuperTotal($project), 2, ",",".") }} @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right">@if ($display_specification) {{ '&euro; '.number_format(MoreOverview::materialSuperTotal($project), 2, ",",".") }} @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right">@if ($display_specification) @if ($project->use_equipment) {{ '&euro; '.number_format(MoreOverview::equipmentSuperTotal($project), 2, ",",".") }} @endif &nbsp; @endif</span></td>
          <td style="width: 51px" class="qty-small"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::superTotal($project), 2, ",",".") }}</span></td>
        </tr>
    </table>
    <?#--LESS CONT & SUBCOINT END--?>
    @endif
    @endif
    @endif
    <?#--TOTAL END--?>
    <?#--SPECIFICATION END--?>

    @if ($display_description)
    <?#--DESCRIPTION START--?>
    @if ($seperate_subcon)
    <?#--TOTAL START--?>


    <?#--PAGE HEADER SECOND START--?>
      <div style="page-break-after:always;"></div>
      <header class="clearfix">
      <div id="logo">
      <?php
        if ($image_height > 0)
          echo "<img style=\"width:300px;height:" . $image_height . "px;\" src=\"" . $image_src . "\"/>";
      ?>
      </div>
        <div id="invoice">
          <div><?php if (Auth::user()->pref_use_ct_numbering) { echo $invoice->invoice_code; } else { echo $invoice->book_code; } ?></div>
          <div>{{ $project->project_name }}</div>
          <div>{{ date("j M Y") }}</div>
      </div>
      </header>
      <?#--PAGE HEADER SECOND END--?>


    <h2 class="name">Omschrijving werkzaamheden</h2>
    <hr color="#000" size="1">

    <table border="0" cellspacing="0" cellpadding="0">
    <thead>
      <tr style="page-break-after: always;">
        <th class="qty-small" style="width: 200px">Onderdeel</th>
        <th class="qty-small" style="width: 220px">Werkzaamheid</th>
        <th class="qty-small">Omschrijving</th>
      </tr>
    </thead>
    <tbody>
      @foreach (Chapter::where('project_id','=', $project->id)->orderBy('priority')->get() as $chapter)
      <?php $i = true; ?>
      @foreach (Activity::where('chapter_id','=', $chapter->id)->orderBy('priority')->get() as $activity)
      <tr>
        <td class="qty-small" style="width: 200px" valign="top"><br/><?php echo ($i ? $chapter->chapter_name : ''); $i = false; ?></td>
        <td class="qty-small" style="width: 220px" valign="top"><br/>{{ $activity->activity_name }}</td>
        <td class="qty-small" valign="top"><br/><span>{!! $activity->note !!}</td>
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
      <?php
        if ($image_height > 0)
          echo "<img style=\"width:300px;height:" . $image_height . "px;\" src=\"" . $image_src . "\"/>";
      ?>
      </div>
        <div id="invoice">
          <div><?php if (Auth::user()->pref_use_ct_numbering) { echo $invoice->invoice_code; } else { echo $invoice->book_code; } ?></div>
          <div>{{ $project->project_name }}</div>
          <div>{{ date("j M Y") }}</div>
      </div>
    </header>
  <?#--PAGE HEADER SECOND END--?>


  <?#--CONT & SUBCOINT START--?>

  <h2 class="name">Omschrijving werkzaamheden</h2>
  <hr color="#000" size="1">

  <h2 class="name">Aanneming</h2>
  <table border="0" cellspacing="0" cellpadding="0">
    <thead>
      <tr style="page-break-after: always;">
        <th style="width: 200px">Onderdeel</th>
        <th style="width: 220px">Werkzaamheid</th>
        <th class="qty-small">Omschrijving</th>
      </tr>
    </thead>
    <tbody>
      @foreach (Chapter::where('project_id','=', $project->id)->orderBy('priority')->get() as $chapter)
      <?php $i = true; ?>
      @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->orderBy('priority')->get() as $activity)
      <tr>
        <td style="width: 200px" valign="top"><br/><?php echo ($i ? $chapter->chapter_name : ''); $i = false; ?></td>
        <td style="width: 220px" valign="top"><br/>{{ $activity->activity_name }}</td>
        <td class="qty-small" valign="top"><br/><span>{!! $activity->note !!}</td>
      </tr>
      @endforeach
      @endforeach
    </tbody>
  </table>
   <h2 class="name">Onderaanneming</h2>
  <table border="0" cellspacing="0" cellpadding="0">
    <thead>
      <tr style="page-break-after: always;">
        <th style="width: 200px" class="qty-small">Onderdeel</th>
        <th style="width: 220px" class="qty-small">Werkzaamheid</th>
        <th class="qty-small">Omschrijving</th>
      </tr>
    </thead>
    <tbody>
      @foreach (Chapter::where('project_id','=', $project->id)->orderBy('priority')->get() as $chapter)
      <?php $i = true; ?>
      @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->orderBy('priority')->get() as $activity)
      <tr>
        <td style="width: 200px" class="qty-small" valign="top"><br/><?php echo ($i ? $chapter->chapter_name : ''); $i = false; ?></td>
        <td style="width: 220px" class="qty-small" valign="top"><br/>{{ $activity->activity_name }}</td>
        <td class="qty-small" valign="top"><br/><span>{!! $activity->note !!}</td>
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



