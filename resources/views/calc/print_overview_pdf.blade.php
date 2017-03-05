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
use \Calctool\Models\Project;
use \Calctool\Models\Part;
use \Calctool\Models\Detail;
use \Calctool\Calculus\CalculationOverview;
use \Calctool\Calculus\MoreOverview;
use \Calctool\Calculus\LessOverview;

$project = Project::find($project_id);

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Calculatieoverzicht</title>
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
                        @if ($relation_self->phone)<div><strong>Telefoon:</strong></div>@endif
                        @if ($relation_self->email)<div><strong>E-mail:</strong></div>@endif  
                        @if ($relation_self->kvk)<div><strong>KVK:</strong></div>@endif
                        @if ($relation_self->btw)<div><strong>BTW:</strong></div>@endif
                        <div>&nbsp;</div>
                        <div><strong>Project:</strong>
                        <div><strong>Projectnr:</strong>
                        <div><strong>Datum:</strong>
                      </td>
                      <td style="width: 300px">
                        <div>@if ($relation_self->address_street) {{ $relation_self->address_street . ' ' . $relation_self->address_number }} @else 1 @endif</div>  
                        <div>@if ($relation_self->address_postal) {{ $relation_self->address_postal . ', ' . $relation_self->address_city }} @else 1 @endif</div>
                        @if ($relation_self->phone)<div>{{ $relation_self->phone }} </div>@endif  
                        @if ($relation_self->email)<div>{{ $relation_self->email }}</div>@endif
                        @if ($relation_self->kvk)<div>{{ $relation_self->kvk }}&nbsp;</div>@endif
                        @if ($relation_self->btw)<div>{{ $relation_self->btw }}&nbsp;</div>@endif
                        <div>&nbsp;</div>
                        <div>{{ $project->project_name }}&nbsp;</div>
                        <div>{{ $project->id }}&nbsp;</div>
                        <div>{{ date('d M Y') }}&nbsp;</div>
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
            <div><h2 class="type">Calculatieoverzicht</h2></div>
          </td>
          <td style="width: 300px">
          </td>
        </tr>
      </tbody>
    </table>
  <br>
  <br>
  </div>

    <h1>Aanneming</h1>

      <table class="table table-striped">

        <thead>
          <tr>
            <th class="col-md-3">Onderdeel</th>
            <th class="col-md-3">Werkzaamheden</th>
            <th class="col-md-1"><span class="pull-right">Arbeidsuren</th>
            <th class="col-md-1"><span class="pull-right">Arbeid</th>
            <th class="col-md-1"><span class="pull-right">Materiaal</th>
            <th class="col-md-1"><span class="pull-right">Overig</th>
            <th class="col-md-1"><span class="pull-right">Totaal</th>
            <th class="col-md-1"><span class="text-center">Type</th>
          </tr>
        </thead>

        <tbody>
          @foreach (Chapter::where('project_id','=', $project->id)->orderBy('priority')->get() as $chapter)
          <?php $i = 0; ?>
          @foreach (Activity::where('chapter_id','=', $chapter->id)->whereNull('detail_id')->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->orderBy('priority')->get() as $activity)
          <?php $i++; ?>
          <tr>
            <td class="col-md-3">{{ $i == 1 ? $chapter->chapter_name : ''  }}</td>
            <td class="col-md-3">{{ $activity->activity_name }}</td>
            <td class="col-md-1"><span class="pull-right">{{ number_format(CalculationOverview::laborTotal($activity), 2, ",",".") }}</td>
            <td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::laborActivity($project->hour_rate, $activity), 2, ",",".") }}</span></td>
            <td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::materialActivityProfit($activity, $project->profit_calc_contr_mat), 2, ",",".") }}</span></td>
            <td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::equipmentActivityProfit($activity, $project->profit_calc_contr_equip), 2, ",",".") }}</span></td>
            <td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::activityTotalProfit($project->hour_rate, $activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip), 2, ",",".") }} </td>
            <td class="col-md-1 text-center">{{ PartType::find($activity->part_type_id)->type_name=='estimate' ? 'Stelpost' : '' }}</td>
          </tr>
          @endforeach
          @endforeach
          <tr>
            <th class="col-md-3"><strong>Totaal Aanneming</strong></th>
            <th class="col-md-3">&nbsp;</th>
            <td class="col-md-1"><strong><span class="pull-right">{{ number_format(CalculationOverview::contrLaborTotalAmount($project), 2, ",",".") }}</span></strong></td>
            <td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::contrLaborTotal($project), 2, ",",".") }}</span></strong></td>
            <td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::contrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
            <td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::contrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
            <td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::contrTotal($project), 2, ",",".") }}</span></strong></td>
            <th class="col-md-1">&nbsp;</th>
          </tr>
        </tbody>

        <tbody>
          <tr>
            <th class="col-md-12">&nbsp;</th>
          </tr>
          <tr>
            <th class="col-md-12">&nbsp;</th>
          </tr>
        </tbody>

        <thead>
          <tr>
            <th class="col-md-3">Onderdeel</th>
            <th class="col-md-3">Werkzaamheden</th>
            <th class="col-md-1"><span class="pull-right">Arbeidsuren</th>
            <th class="col-md-1"><span class="pull-right">Arbeid</th>
            <th class="col-md-1"><span class="pull-right">Materiaal</th>
            <th class="col-md-1"><span class="pull-right">Overig</th>
            <th class="col-md-1"><span class="pull-right">Totaal</th>
            <th class="col-md-1"><span class="text-center">Type</th>
          </tr>
        </thead>

        <tbody>
          @foreach (Chapter::where('project_id','=', $project->id)->orderBy('priority')->get() as $chapter)
          <?php $i = 0; ?>
          @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->where('detail_id','=',Detail::where('detail_name','=','more')->first()->id)->orderBy('priority')->get() as $activity)
          <?php $i++; ?>
          <tr>
            <td class="col-md-3">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
            <td class="col-md-3">{{ $activity->activity_name }}</td>
            <td class="col-md-1"><span class="pull-right">{{ number_format(MoreOverview::laborTotal($activity), 2, ",",".") }}</td>
            <td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(MoreOverview::laborActivity($activity), 2, ",",".") }}</span></td>
            <td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(MoreOverview::materialActivityProfit($activity, $project->profit_more_contr_mat), 2, ",",".") }}</span></td>
            <td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::equipmentActivityProfit($activity, $project->profit_more_contr_equip), 2, ",",".") }}</span></td>
            <td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::activityTotalProfit($activity, $project->profit_more_contr_mat, $project->profit_more_contr_equip), 2, ",",".") }} </td>
            <td class="col-md-1 text-center">Meerwerk</td>
          </tr>
          @endforeach
          @endforeach
          <tr>
            <th class="col-md-3"><strong>Totaal Aanneming</strong></th>
            <th class="col-md-3">&nbsp;</th>
            <td class="col-md-1"><strong><span class="pull-right">{{ number_format(MoreOverview::contrLaborTotalAmount($project), 2, ",",".") }}</span></strong></td>
            <td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::contrLaborTotal($project), 2, ",",".") }}</span></strong></td>
            <td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::contrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
            <td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::contrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
            <td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::contrTotal($project), 2, ",",".") }}</span></strong></td>
            <th class="col-md-1">&nbsp;</th>
          </tr>
        </tbody>

        <tbody>
          <tr>
            <th class="col-md-12">&nbsp;</th>
          </tr>
          <tr>
            <th class="col-md-12">&nbsp;</th>
          </tr>
        </tbody>

        <thead>
          <tr>
            <th class="col-md-3">Onderdeel</th>
            <th class="col-md-3">Werkzaamheden</th>
            <th class="col-md-1"><span class="pull-right">Arbeidsuren</th>
            <th class="col-md-1"><span class="pull-right">Arbeid</th>
            <th class="col-md-1"><span class="pull-right">Materiaal</th>
            <th class="col-md-1"><span class="pull-right">Overig</th>
            <th class="col-md-1"><span class="pull-right">Totaal</th>
            <th class="col-md-1"><span class="text-center">Type</th>
          </tr>
        </thead>

        <tbody>
          @foreach (Chapter::where('project_id','=', $project->id)->orderBy('priority')->get() as $chapter)
          <?php $i = 0; ?>
          @foreach (Activity::where('chapter_id','=', $chapter->id)->whereNull('detail_id')->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->where('part_type_id','=',PartType::where('type_name','=','calculation')->first()->id)->orderBy('priority')->get() as $activity)
          <?php $i++; ?>
          <tr>
            <td class="col-md-3">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
            <td class="col-md-3">{{ $activity->activity_name }}</td>
            <td class="col-md-1"><span class="pull-right">{{ number_format(LessOverview::laborTotal($activity), 2, ",",".") }}</td>
            <td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(LessOverview::laborActivity($activity, $project), 2, ",",".") }}</span></td>
            <td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(LessOverview::materialActivityProfit($activity, $project->profit_calc_contr_mat), 2, ",",".") }}</span></td>
            <td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::equipmentActivityProfit($activity, $project->profit_calc_contr_equip), 2, ",",".") }}</span></td>
            <td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::activityTotalProfit($activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip, $project), 2, ",",".") }} </td>
            <td class="col-md-1 text-center">{{ PartType::find($activity->part_type_id)->type_name=='estimate' ? 'Stelpost' : '' }}</td>
          </tr>
          @endforeach
          @endforeach
          <tr>
            <th class="col-md-3"><strong>Totaal Aanneming</strong></th>
            <th class="col-md-3">&nbsp;</th>
            <td class="col-md-1"><strong><span class="pull-right">{{ number_format(LessOverview::contrLaborTotalAmount($project), 2, ",",".") }}</span></strong></td>
            <td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::contrLaborTotal($project), 2, ",",".") }}</span></strong></td>
            <td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::contrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
            <td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::contrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
            <td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::contrTotal($project), 2, ",",".") }}</span></strong></td>
            <th class="col-md-1">&nbsp;</th>
          </tr>
        </tbody>
      </table>


  @if ($project->use_subcontract)
    <h1>Onderaanneming</h1>

      <table class="table table-striped">

        <thead>
          <tr>
            <th class="col-md-3">Onderdeel</th>
            <th class="col-md-3">Werkzaamheden</th>
            <th class="col-md-1"><span class="pull-right">Arbeidsuren</th>
            <th class="col-md-1"><span class="pull-right">Arbeid</th>
            <th class="col-md-1"><span class="pull-right">Materiaal</th>
            <th class="col-md-1"><span class="pull-right">Overig</th>
            <th class="col-md-1"><span class="pull-right">Totaal</th>
            <th class="col-md-1"><span class="text-center">Type</th>
          </tr>
        </thead>

        <tbody>
          @foreach (Chapter::where('project_id','=', $project->id)->orderBy('priority')->get() as $chapter)
          <?php $i = 0; ?>
          @foreach (Activity::where('chapter_id','=', $chapter->id)->whereNull('detail_id')->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->orderBy('priority')->get() as $activity)
          <?php $i++; ?>
          <tr>
            <td class="col-md-3">{{ $i == 1 ? $chapter->chapter_name : '' }}</td>
            <td class="col-md-3">{{ $activity->activity_name }}</td>
            <td class="col-md-1"><span class="pull-right">{{ number_format(CalculationOverview::laborTotal($activity), 2, ",",".") }}</td>
            <td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::laborActivity($project->hour_rate, $activity), 2, ",",".") }}</span></td>
            <td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(CalculationOverview::materialActivityProfit($activity, $project->profit_calc_subcontr_mat), 2, ",",".") }}</span></td>
            <td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::equipmentActivityProfit($activity, $project->profit_calc_subcontr_equip), 2, ",",".") }}</span></td>
            <td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::activityTotalProfit($project->hour_rate, $activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip), 2, ",",".") }} </td>
            <td class="col-md-1 text-center">{{ PartType::find($activity->part_type_id)->type_name=='estimate' ? 'Stelpost' : '' }}</td>
          </tr>
          @endforeach
          @endforeach
          <tr>
            <th class="col-md-3"><strong>Totaal Onderaanneming</strong></th>
            <th class="col-md-3">&nbsp;</th>
            <td class="col-md-1"><strong><span class="pull-right">{{ number_format(CalculationOverview::subcontrLaborTotalAmount($project), 2, ",",".") }}</span></strong></td>
            <td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::subcontrLaborTotal($project), 2, ",",".") }}</span></strong></td>
            <td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::subcontrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
            <td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::subcontrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
            <td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(CalculationOverview::subcontrTotal($project), 2, ",",".") }}</span></strong></td>
            <th class="col-md-1">&nbsp;</th>
          </tr>
        </tbody>

        <tbody>
          <tr>
            <th class="col-md-12">&nbsp;</th>
          </tr>
          <tr>
            <th class="col-md-12">&nbsp;</th>
          </tr>
        </tbody>

        <thead>
          <tr>
            <th class="col-md-3">Onderdeel</th>
            <th class="col-md-3">Werkzaamheden</th>
            <th class="col-md-1"><span class="pull-right">Arbeidsuren</th>
            <th class="col-md-1"><span class="pull-right">Arbeid</th>
            <th class="col-md-1"><span class="pull-right">Materiaal</th>
            <th class="col-md-1"><span class="pull-right">Overig</th>
            <th class="col-md-1"><span class="pull-right">Totaal</th>
            <th class="col-md-1"><span class="text-center">Type</th>
          </tr>
        </thead>

        <tbody>
          @foreach (Chapter::where('project_id','=', $project->id)->orderBy('priority')->get() as $chapter)
          <?php $i = 0; ?>
          @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->where('detail_id','=',Detail::where('detail_name','=','more')->first()->id)->orderBy('priority')->get() as $activity)
          <?php $i++; ?>
          <tr>
            <td class="col-md-3">{{ $i==1 ? $chapter->chapter_name : ''}}</td>
            <td class="col-md-3">{{ $activity->activity_name }}</td>
            <td class="col-md-1"><span class="pull-right">{{ number_format(MoreOverview::laborTotal($activity), 2, ",",".") }}</td>
            <td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(MoreOverview::laborActivity($activity), 2, ",",".") }}</span></td>
            <td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(MoreOverview::materialActivityProfit($activity, $project->profit_more_subcontr_mat), 2, ",",".") }}</span></td>
            <td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::equipmentActivityProfit($activity, $project->profit_more_subcontr_equip), 2, ",",".") }}</span></td>
            <td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::activityTotalProfit($activity, $project->profit_more_subcontr_mat, $project->profit_more_subcontr_equip), 2, ",",".") }} </td>
            <td class="col-md-1 text-center">Meerwerk</td>
          </tr>
          @endforeach
          @endforeach
          <tr>
            <th class="col-md-3"><strong>Totaal Onderaanneming</strong></th>
            <th class="col-md-1">&nbsp;</th>
            <td class="col-md-1"><strong><span class="pull-right">{{ number_format(MoreOverview::subcontrLaborTotalAmount($project), 2, ",",".") }}</span></strong></td>
            <td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::subcontrLaborTotal($project), 2, ",",".") }}</span></strong></td>
            <td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::subcontrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
            <td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::subcontrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
            <td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::subcontrTotal($project), 2, ",",".") }}</span></strong></td>
            <th class="col-md-1">&nbsp;</th>
          </tr>
        </tbody>

        <tbody>
          <tr>
            <th class="col-md-12">&nbsp;</th>
          </tr>
          <tr>
            <th class="col-md-12">&nbsp;</th>
          </tr>
        </tbody>

        <thead>
          <tr>
            <th class="col-md-3">Onderdeel</th>
            <th class="col-md-3">Werkzaamheden</th>
            <th class="col-md-1"><span class="pull-right">Arbeidsuren</th>
            <th class="col-md-1"><span class="pull-right">Arbeid</th>
            <th class="col-md-1"><span class="pull-right">Materiaal</th>
            <th class="col-md-1"><span class="pull-right">Overig</th>
            <th class="col-md-1"><span class="pull-right">Totaal</th>
            <th class="col-md-1"><span class="text-center">Type</th>
          </tr>
        </thead>

        <tbody>
          @foreach (Chapter::where('project_id','=', $project->id)->orderBy('priority')->get() as $chapter)
          <?php $i = 0; ?>
          @foreach (Activity::where('chapter_id','=', $chapter->id)->whereNull('detail_id')->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->where('part_type_id','=',PartType::where('type_name','=','calculation')->first()->id)->orderBy('priority')->get() as $activity)
          <?php $i++ ?>
          <tr>
            <td class="col-md-3">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
            <td class="col-md-3">{{ $activity->activity_name }}</td>
            <td class="col-md-1"><span class="pull-right">{{ number_format(LessOverview::laborTotal($activity), 2, ",",".") }}</td>
            <td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(LessOverview::laborActivity($activity, $project), 2, ",",".") }}</span></td>
            <td class="col-md-1"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(LessOverview::materialActivityProfit($activity, $project->profit_calc_subcontr_mat), 2, ",",".") }}</span></td>
            <td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::equipmentActivityProfit($activity, $project->profit_calc_subcontr_equip), 2, ",",".") }}</span></td>
            <td class="col-md-1"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::activityTotalProfit($activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip, $project), 2, ",",".") }} </td>
            <td class="col-md-1 text-center">{{ PartType::find($activity->part_type_id)->type_name=='estimate' ? 'Stelpost' : '' }}</td>
          </tr>
          @endforeach
          @endforeach
          <tr>
            <th class="col-md-3"><strong>Totaal Onderaanneming</strong></th>
            <th class="col-md-3">&nbsp;</th>
            <td class="col-md-1"><strong><span class="pull-right">{{ number_format(LessOverview::subcontrLaborTotalAmount($project), 2, ",",".") }}</span></strong></td>
            <td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::subcontrLaborTotal($project), 2, ",",".") }}</span></strong></td>
            <td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::subcontrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
            <td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::subcontrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
            <td class="col-md-1"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::subcontrTotal($project), 2, ",",".") }}</span></strong></td>
            <th class="col-md-1">&nbsp;</th>
          </tr>
        </tbody>
      </table>

  @endif

  </main>

  </body>
</html>
