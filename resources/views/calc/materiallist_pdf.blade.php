<?php

use \Calctool\Models\Activity;
use \Calctool\Models\Chapter;
use \Calctool\Models\Resource;
use \Calctool\Models\PartType;
use \Calctool\Models\CalculationMaterial;
use \Calctool\Models\CalculationEquipment;
use \Calctool\Models\EstimateMaterial;
use \Calctool\Models\EstimateEquipment;
use \Calctool\Models\MoreMaterial;
use \Calctool\Models\MoreEquipment;

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Pakbon</title>
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
                  <?php if ($relation_self && $relation_self->logo_id) echo "<img src=\"".asset(Resource::find($relation_self->logo_id)->file_location)."\"/>"; ?>
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
                        @if ($relation_self->phone)<div><strong>Telefoon:</strong></div>@endif
                        @if ($relation_self->email)<div><strong>E-mail:</strong></div>@endif  
                        @if ($relation_self->kvk)<div><strong>KVK:</strong></div>@endif
                        @if ($relation_self->btw)<div><strong>BTW:</strong></div>@endif
                        <div>&nbsp;</div>
                        <div><strong>Datum:</strong>
                        <div><strong>Pakbonnr:</strong>
                      </td>
                      <td style="width: 300px">
                        <div>@if ($relation_self->address_street) {{ $relation_self->address_street . ' ' . $relation_self->address_number }} @else 1 @endif</div>  
                        <div>@if ($relation_self->address_postal) {{ $relation_self->address_postal . ', ' . $relation_self->address_city }} @else 1 @endif</div>
                        @if ($relation_self->phone)<div>{{ $relation_self->phone }} </div>@endif  
                        @if ($relation_self->email)<div>{{ $relation_self->email }}</div>@endif
                        @if ($relation_self->kvk)<div>{{ $relation_self->kvk }}&nbsp;</div>@endif
                        @if ($relation_self->btw)<div>{{ $relation_self->btw }}&nbsp;</div>@endif
                        <div>&nbsp;</div>
                        <div>{{ date('d M Y') }}&nbsp;</div>
                        <div>{{ $list_id }}&nbsp;</div>
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
            <div><h2 class="type">PAKBON</h2></div>
          </td>
          <td style="width: 300px">
          </td>
        </tr>
      </tbody>
    </table>
  <br>
  <br>
  </div>

  @foreach (Chapter::where('project_id', $project_id)->orderBy('priority')->get() as $chapter)
  <h2 class="name">{{ $chapter->chapter_name }}</h2>
  <hr color="#000" size="1">
  <table border="0" cellspacing="0" cellpadding="0">
    <thead>
      <tr>
        <td style="width: 185px" class="qty"><strong>Werkzaamheid</strong></th>
        <td style="width: 160px" class="qty"><strong>Materiaal</strong></th>
        <td style="width: 70px" class="qty"><strong>&euro; / Eenh.</strong></th>
        <td style="width: 60px" class="qty"><strong>Aantal</strong></th>
        <td style="width: 60px" class="qty"><strong>Prijs</strong></th>
        <td style="width: 60px" class="qty"><strong>Stelpost</strong></th>
      </tr>
    </thead>
    <tbody>
  @foreach (Activity::where('chapter_id', $chapter->id)->orderBy('priority')->get() as $activity)
    <?php $i = true; ?>
    @foreach (CalculationMaterial::where('activity_id', $activity->id)->get() as $material)
      <tr>
        <td style="width: 185px" class="qty"><?php echo ($i ? $activity->activity_name : ''); $i = false; ?></td>
        <td style="width: 160px" class="qty">{{ $material->material_name }}</td>
        <td style="width: 70px" class="qty">{{ number_format($material->rate, 2,",",".") . '/' . $material->unit }}</td>
        <td style="width: 60px" class="qty">{{ number_format($material->amount, 2,",",".") }}</td>
        <td style="width: 60px" class="qty">{{ '&euro; '.number_format($material->rate*$material->amount, 2,",",".") }}</td>
        <td style="width: 60px" class="qty">{{ (PartType::find($activity->part_type_id)->type_name=='estimate') ? 'Ja' : '' }}</td>
      </tr>
     @endforeach
    @foreach (CalculationEquipment::where('activity_id', $activity->id)->get() as $material)
      <tr>
        <td style="width: 185px" class="qty"><?php echo ($i ? $activity->activity_name : ''); $i = false; ?></td>
        <td style="width: 160px" class="qty">{{ $material->equipment_name }}</td>
        <td style="width: 70px" class="qty">{{ number_format($material->rate, 2,",",".") . '/' . $material->unit }}</td>
        <td style="width: 60px" class="qty">{{ number_format($material->amount, 2,",",".") }}</td>
        <td style="width: 60px" class="qty">{{ '&euro; '.number_format($material->rate*$material->amount, 2,",",".") }}</td>
        <td style="width: 60px" class="qty">{{ (PartType::find($activity->part_type_id)->type_name=='estimate') ? 'Ja' : '' }}</td>
      </tr>
    @endforeach
    @foreach (EstimateMaterial::where('activity_id', $activity->id)->get() as $material)
      <tr>
        <td style="width: 185px" class="qty"><?php echo ($i ? $activity->activity_name : ''); $i = false; ?></td>
        <td style="width: 160px" class="qty">{{ $material->material_name }}</td>
        <td style="width: 70px" class="qty">{{ number_format($material->rate, 2,",",".") . '/' . $material->unit }}</td>
        <td style="width: 60px" class="qty">{{ number_format($material->amount, 2,",",".") }}</td>
        <td style="width: 60px" class="qty">{{ '&euro; '.number_format($material->rate*$material->amount, 2,",",".") }}</td>
        <td style="width: 60px" class="qty">{{ (PartType::find($activity->part_type_id)->type_name=='estimate') ? 'Ja' : '' }}</td>
      </tr>
    @endforeach
    @foreach (EstimateEquipment::where('activity_id', $activity->id)->get() as $material)
      <tr>
        <td style="width: 185px" class="qty"><?php echo ($i ? $activity->activity_name : ''); $i = false; ?></td>
        <td style="width: 160px" class="qty">{{ $material->equipment_name }}</td>
        <td style="width: 70px" class="qty">{{ number_format($material->rate, 2,",",".") . '/' . $material->unit }}</td>
        <td style="width: 60px" class="qty">{{ number_format($material->amount, 2,",",".") }}</td>
        <td style="width: 60px" class="qty">{{ '&euro; '.number_format($material->rate*$material->amount, 2,",",".") }}</td>
        <td style="width: 60px" class="qty">{{ (PartType::find($activity->part_type_id)->type_name=='estimate') ? 'Ja' : '' }}</td>
      </tr>
    @endforeach
    @foreach (MoreMaterial::where('activity_id', $activity->id)->get() as $material)
      <tr>
        <td style="width: 185px" class="qty">><?php echo ($i ? $activity->activity_name : ''); $i = false; ?></td>
        <td style="width: 160px" class="qty">{{ $material->material_name }}</td>
        <td style="width: 70px" class="qty">{{ number_format($material->rate, 2,",",".") . '/' . $material->unit }}</td>
        <td style="width: 60px" class="qty">{{ number_format($material->amount, 2,",",".") }}</td>
        <td style="width: 60px" class="qty">{{ '&euro; '.number_format($material->rate*$material->amount, 2,",",".") }}</td>
        <td style="width: 60px" class="qty">{{ (PartType::find($activity->part_type_id)->type_name=='estimate') ? 'Ja' : '' }}</td>
      </tr>
    @endforeach
    @foreach (MoreEquipment::where('activity_id', $activity->id)->get() as $material)
      <tr>
        <td style="width: 185px" class="qty"><?php echo ($i ? $activity->activity_name : ''); $i = false; ?></td>
        <td style="width: 160px" class="qty">{{ $material->equipment_name }}</td>
        <td style="width: 70px" class="qty">{{ number_format($material->rate, 2,",",".") . '/' . $material->unit }}</td>
        <td style="width: 60px" class="qty">{{ number_format($material->amount, 2,",",".") }}</td>
        <td style="width: 60px" class="qty">{{ '&euro; '.number_format($material->rate*$material->amount, 2,",",".") }}</td>
        <td style="width: 60px" class="qty">{{ (PartType::find($activity->part_type_id)->type_name=='estimate') ? 'Ja' : '' }}</td>
      </tr>
    @endforeach
    @endforeach
    </tbody>
  </table>
  @endforeach

    <h2 class="name">Opmerkingen</h2>
    <hr color="#000" size="1">
    <div class="terms">
      <li>Overzicht gebaseerd op huidige projectstatus.</li>
    </div>


  </main>

  </body>
</html>
