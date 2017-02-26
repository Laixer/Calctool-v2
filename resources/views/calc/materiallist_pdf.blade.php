<?php

use \Calctool\Models\Activity;
use \Calctool\Models\Chapter;
use \Calctool\Models\Resource;
use \Calctool\Models\PartType;
use \Calctool\Models\CalculationMaterial;
use \Calctool\Models\CalculationEquipment;
use \Calctool\Models\EstimateMaterial;
use \Calctool\Models\MoreMaterial;

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
        <th class="col-md-4">Onderdeel</th>
        <th class="col-md-1">Stelpost</th>
        <th class="col-md-4">Materiaal</th>
        <th class="col-md-1">&euro; / Eenh.</th>
        <th class="col-md-1">Aantal</th>
        <th class="col-md-1">Prijs</th>
      </tr>
    </thead>
    <tbody>
  @foreach (Activity::where('chapter_id', $chapter->id)->orderBy('priority')->get() as $activity)
    <?php $i = true; ?>
    @foreach (CalculationMaterial::where('activity_id', $activity->id)->get() as $material)
    @if ($i)
      <tr>
        <td class="col-md-4"><?php echo $activity->activity_name; $i = false; ?></td>
        <td class="col-md-1">{{ (PartType::find($activity->part_type_id)->type_name=='estimate') ? 'Ja' : '' }}</td>
        <td class="col-md-4"></td>
        <td class="col-md-1"></td>
        <td class="col-md-1"></td>
        <td class="col-md-1"></td>
      </tr>
    @else
      <tr>
        <td class="col-md-4"></td>
        <td class="col-md-1"></td>
        <td class="col-md-4">{{ $material->material_name }}</td>
        <td class="col-md-1">{{ number_format($material->rate, 2,",",".") . '/' . $material->unit }}</td>
        <td class="col-md-1">{{ number_format($material->amount, 2,",",".") }}</td>
        <td class="col-md-1">{{ '&euro; '.number_format($material->rate*$material->amount, 2,",",".") }}</td>
      </tr>
    @endif
    @endforeach
    @foreach (CalculationEquipment::where('activity_id', $activity->id)->get() as $material)
    @if ($i)
      <tr>
        <td class="col-md-4"><?php echo $activity->activity_name; $i = false; ?></td>
        <td class="col-md-1">{{ (PartType::find($activity->part_type_id)->type_name=='estimate') ? 'Ja' : '' }}</td>
        <td class="col-md-4"></td>
        <td class="col-md-1"></td>
        <td class="col-md-1"></td>
        <td class="col-md-1"></td>
      </tr>
    @else
      <tr>
        <td class="col-md-4"></td>
        <td class="col-md-1"></td>
        <td class="col-md-4">{{ $material->material_name }}</td>
        <td class="col-md-1">{{ number_format($material->rate, 2,",",".") . '/' . $material->unit }}</td>
        <td class="col-md-1">{{ number_format($material->amount, 2,",",".") }}</td>
        <td class="col-md-1">{{ '&euro; '.number_format($material->rate*$material->amount, 2,",",".") }}</td>
      </tr>
    @endif
    @endforeach
    @foreach (EstimateMaterial::where('activity_id', $activity->id)->get() as $material)
    @if ($i)
      <tr>
        <td class="col-md-4"><?php echo $activity->activity_name; $i = false; ?></td>
        <td class="col-md-1">{{ (PartType::find($activity->part_type_id)->type_name=='estimate') ? 'Ja' : '' }}</td>
        <td class="col-md-4"></td>
        <td class="col-md-1"></td>
        <td class="col-md-1"></td>
        <td class="col-md-1"></td>
      </tr>
    @else
      <tr>
        <td class="col-md-4"></td>
        <td class="col-md-1"></td>
        <td class="col-md-4">{{ $material->material_name }}</td>
        <td class="col-md-1">{{ number_format($material->rate, 2,",",".") . '/' . $material->unit }}</td>
        <td class="col-md-1">{{ number_format($material->amount, 2,",",".") }}</td>
        <td class="col-md-1">{{ '&euro; '.number_format($material->rate*$material->amount, 2,",",".") }}</td>
      </tr>
    @endif
    @endforeach
    @foreach (MoreMaterial::where('activity_id', $activity->id)->get() as $material)
    @if ($i)
      <tr>
        <td class="col-md-4"><?php echo $activity->activity_name; $i = false; ?></td>
        <td class="col-md-1">{{ (PartType::find($activity->part_type_id)->type_name=='estimate') ? 'Ja' : '' }}</td>
        <td class="col-md-4"></td>
        <td class="col-md-1"></td>
        <td class="col-md-1"></td>
        <td class="col-md-1"></td>
      </tr>
      @else
      <tr>
        <td class="col-md-4"><?php echo ($i ? $activity->activity_name : ''); $i = false; ?></td>
        <td class="col-md-1"></td>
        <td class="col-md-4">{{ $material->material_name }}</td>
        <td class="col-md-1">{{ number_format($material->rate, 2,",",".") . '/' . $material->unit }}</td>
        <td class="col-md-1">{{ number_format($material->amount, 2,",",".") }}</td>
        <td class="col-md-1">{{ '&euro; '.number_format($material->rate*$material->amount, 2,",",".") }}</td>
      </tr>
    @endif
    @endforeach
    @endforeach
    </tbody>
  </table>
  @endforeach

    <h2 class="name">Bepalingen</h2>
    <hr color="#000" size="1">
    <div class="terms">
      <li>Pakbon gebasseerd op huidig projectstatus.</li>
    </div>

    <div class="from">Met vriendelijke groet,</div>
    <div class="from">{{ $relation_self->name() }}</div>

  </main>

  </body>
</html>
