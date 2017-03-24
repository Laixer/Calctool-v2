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
use \Calctool\Models\ProjectType;
use \Calctool\Calculus\CalculationOverview;
use \Calctool\Calculus\MoreOverview;
use \Calctool\Calculus\EstimateOverview;
use \Calctool\Calculus\LessOverview;

$project = Project::find($project_id);

$image_height = 0;
if ($relation_self && $relation_self->logo_id) {
   $image_src = getcwd() . '/' . Resource::find($relation_self->logo_id)->file_location;
   $image = getimagesize($image_src);
   $image_height = round(($image[1] / $image[0]) * 300);

   $type = ProjectType::find($project->type_id);
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Projectoverzicht</title>
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
            <div><h1 class="type">PROJECTOVERZICHT</h1></div>
          </td>
          <td style="width: 300px">
          </td>
        </tr>
      </tbody>
    </table>
  <br>
  <br>
  </div>

  @if($type->type_name != 'regie')

  @if ($project->use_subcontract)
  <h1 class="type">Aanneming</h1>
  @endif

  <br>
  <h3 class="type">Calculatie</h3>
  <hr>

  <table class="table table-striped">
    <thead>
      <tr>
        <th style="width: 130px" class="qty-small">Onderdeel</th>
        <th style="width: 144px" class="qty-small">Werkzaamheden</th>
        <th style="width: 40px" class="qty-small"><span class="pull-right">Uren</th>
        <th style="width: 60px" class="qty-small"><span class="pull-right">Arbeid</th>
        <th style="width: 60px" class="qty-small"><span class="pull-right">Materiaal</th>
        <th style="width: 60px" class="qty-small"><span class="pull-right">Overig</th>
        <th style="width: 60px" class="qty-small"><span class="pull-right">Totaal</th>
        <th style="width: 40px" class="qty-small"><span class="text-center">Type</th>
      </tr>
    </thead>
    <tbody>
      <?php $j1 = 0; $j2 = 0; $j3 = 0; $j4 = 0; $j5 = 0; ?>
      @foreach (Chapter::where('project_id','=', $project->id)->orderBy('priority')->get() as $chapter)
      <?php $i = 0; ?>
      @foreach (Activity::where('chapter_id','=', $chapter->id)->whereNull('detail_id')->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->orderBy('priority')->get() as $activity)
      <?php $i++ ?>
      <tr>
        <td style="width: 130px" class="qty-small">{{ $i == 1 ? $chapter->chapter_name : ''  }}</td>
        <td style="width: 144px" class="qty-small">{{ $activity->activity_name }}</td>
        @if (PartType::find($activity->part_type_id)->type_name=='estimate')
        <td style="width: 40px" class="qty-small"><span class="pull-right"><?php echo number_format(EstimateOverview::laborTotal($activity), 2, ",","."); $j1 += EstimateOverview::laborTotal($activity) ?></td>
        <td style="width: 60px" class="qty-small"><span class="pull-right total-ex-tax"><?php echo '&euro; '.number_format(EstimateOverview::laborActivity($activity), 2, ",","."); $j2 += EstimateOverview::laborActivity($activity) ?></span></td>
        <td style="width: 60px" class="qty-small"><span class="pull-right total-ex-tax"><?php echo '&euro; '.number_format(EstimateOverview::materialActivityProfit($activity, $project->profit_calc_contr_mat), 2, ",","."); $j3 += EstimateOverview::materialActivityProfit($activity, $project->profit_calc_contr_mat) ?></span></td>
        <td style="width: 60px" class="qty-small"><span class="pull-right"><?php echo '&euro; '.number_format(EstimateOverview::equipmentActivityProfit($activity, $project->profit_calc_contr_equip), 2, ",","."); $j4 += EstimateOverview::equipmentActivityProfit($activity, $project->profit_calc_contr_equip) ?></span></td>
        <td style="width: 60px" class="qty-small"><span class="pull-right"><?php echo '&euro; '.number_format(EstimateOverview::activityTotalProfit($activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip), 2, ",","."); $j5 += EstimateOverview::activityTotalProfit($activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip) ?></td>
        @else
        <td style="width: 40px" class="qty-small"><span class="pull-right"><?php echo number_format(CalculationOverview::laborTotal($activity), 2, ",","."); $j1 += CalculationOverview::laborTotal($activity) ?></td>
        <td style="width: 60px" class="qty-small"><span class="pull-right total-ex-tax"><?php echo '&euro; '.number_format(CalculationOverview::laborActivity($project->hour_rate, $activity), 2, ",","."); $j2 += CalculationOverview::laborActivity($project->hour_rate, $activity) ?></span></td>
        <td style="width: 60px" class="qty-small"><span class="pull-right total-ex-tax"><?php echo '&euro; '.number_format(CalculationOverview::materialActivityProfit($activity, $project->profit_calc_contr_mat), 2, ",","."); $j3 += CalculationOverview::materialActivityProfit($activity, $project->profit_calc_contr_mat) ?></span></td>
        <td style="width: 60px" class="qty-small"><span class="pull-right"><?php echo '&euro; '.number_format(CalculationOverview::equipmentActivityProfit($activity, $project->profit_calc_contr_equip), 2, ",","."); $j4 += CalculationOverview::equipmentActivityProfit($activity, $project->profit_calc_contr_equip) ?></span></td>
        <td style="width: 60px" class="qty-small"><span class="pull-right"><?php echo '&euro; '.number_format(CalculationOverview::activityTotalProfit($project->hour_rate, $activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip), 2, ",","."); $j5 += CalculationOverview::activityTotalProfit($project->hour_rate, $activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip) ?></td>
        @endif
        <td style="width: 40px" class="qty-small" text-center">{{ PartType::find($activity->part_type_id)->type_name=='estimate' ? 'Stelpost' : '' }}</td>
      </tr>
      @endforeach
      @endforeach
      <tr>
        <th style="width: 130px" class="qty-small"><strong>Totaal</strong></th>
        <th style="width: 144px" class="qty-small">&nbsp;</th>
        <td style="width: 40px" class="qty-small"><strong><span class="pull-right">{{ number_format($j1, 2, ",",".") }}</span></strong></td>
        <td style="width: 60px" class="qty-small"><strong><span class="pull-right">{{ '&euro; '.number_format($j2, 2, ",",".") }}</span></strong></td>
        <td style="width: 60px" class="qty-small"><strong><span class="pull-right">{{ '&euro; '.number_format($j3, 2, ",",".") }}</span></strong></td>
        <td style="width: 60px" class="qty-small"><strong><span class="pull-right">{{ '&euro; '.number_format($j4, 2, ",",".") }}</span></strong></td>
        <td style="width: 60px" class="qty-small"><strong><span class="pull-right">{{ '&euro; '.number_format($j5, 2, ",",".") }}</span></strong></td>
        <th style="width: 40px" class="qty-small">&nbsp;</th>
      </tr>
    </tbody>
  </tbody>
</table>

@if ($project->use_less)
<br>   
<h3 class="type">Minderwerk</h3>
<hr>

<table class="table table-striped">
  <thead>
    <tr>
      <th style="width: 130px" class="qty-small">Onderdeel</th>
      <th style="width: 144px" class="qty-small">Werkzaamheden</th>
      <th style="width: 40px" class="qty-small"><span class="pull-right">Uren</th>
      <th style="width: 60px" class="qty-small"><span class="pull-right">Arbeid</th>
      <th style="width: 60px" class="qty-small"><span class="pull-right">Materiaal</th>
      <th style="width: 60px" class="qty-small"><span class="pull-right">Overig</th>
      <th style="width: 60px" class="qty-small"><span class="pull-right">Totaal</th>
      <th style="width: 40px" class="qty-small"><span class="text-center">&nbsp;<!-- Type --></th>
    </tr>
  </thead>
  <tbody>
    @foreach (Chapter::where('project_id','=', $project->id)->orderBy('priority')->get() as $chapter)
    <?php $i = 0; ?>
    @foreach (Activity::where('chapter_id','=', $chapter->id)->whereNull('detail_id')->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->where('part_type_id','=',PartType::where('type_name','=','calculation')->first()->id)->orderBy('priority')->get() as $activity)
    <?php $i++; ?>
    <tr>
      <td style="width: 130px" class="qty-small">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
      <td style="width: 144px" class="qty-small">{{ $activity->activity_name }}</td>
      <td style="width: 40px" class="qty-small"><span class="pull-right">{{ number_format(LessOverview::laborTotal($activity), 2, ",",".") }}</td>
      <td style="width: 60px" class="qty-small"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(LessOverview::laborActivity($activity, $project), 2, ",",".") }}</span></td>
      <td style="width: 60px" class="qty-small"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(LessOverview::materialActivityProfit($activity, $project->profit_calc_contr_mat), 2, ",",".") }}</span></td>
      <td style="width: 60px" class="qty-small"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::equipmentActivityProfit($activity, $project->profit_calc_contr_equip), 2, ",",".") }}</span></td>
      <td style="width: 60px" class="qty-small"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::activityTotalProfit($activity, $project->profit_calc_contr_mat, $project->profit_calc_contr_equip, $project), 2, ",",".") }} </td>
      <td style="width: 40px" class="qty-small" text-center">&nbsp;<!-- {{ PartType::find($activity->part_type_id)->type_name=='estimate' ? 'Stelpost' : '' }} --></td>
    </tr>
    @endforeach
    @endforeach
    <tr>
      <th style="width: 137px" class="qty-small"><strong>Totaal</strong></th>
      <th style="width: 144px" class="qty-small">&nbsp;</th>
      <td style="width: 40px" class="qty-small"><strong><span class="pull-right">{{ number_format(LessOverview::contrLaborTotalAmount($project), 2, ",",".") }}</span></strong></td>
      <td style="width: 60px" class="qty-small"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::contrLaborTotal($project), 2, ",",".") }}</span></strong></td>
      <td style="width: 60px" class="qty-small"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::contrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
      <td style="width: 60px" class="qty-small"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::contrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
      <td style="width: 60px" class="qty-small"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::contrTotal($project), 2, ",",".") }}</span></strong></td>
      <th style="width: 40px" class="qty-small">&nbsp;</th>
    </tr>
  </tbody>
</table>
@endif

@if ($project->use_more)
<br>
<h3 class="type">Meerwerk</h3>
<hr>

<table class="table table-striped">
  <thead>
    <tr>
      <th style="width: 130px" class="qty-small">Onderdeel</th>
      <th style="width: 144px" class="qty-small">Werkzaamheden</th>
      <th style="width: 40px" class="qty-small"><span class="pull-right">Uren</th>
      <th style="width: 60px" class="qty-small"><span class="pull-right">Arbeid</th>
      <th style="width: 60px" class="qty-small"><span class="pull-right">Materiaal</th>
      <th style="width: 60px" class="qty-small"><span class="pull-right">Overig</th>
      <th style="width: 60px" class="qty-small"><span class="pull-right">Totaal</th>
      <th style="width: 40px" class="qty-small"><span class="text-center">&nbsp;<!-- Type --></th>
    </tr>
  </thead>

  <tbody>
    @foreach (Chapter::where('project_id','=', $project->id)->orderBy('priority')->get() as $chapter)
    <?php $i = 0; ?>
    @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->where('detail_id','=',Detail::where('detail_name','=','more')->first()->id)->orderBy('priority')->get() as $activity)
    <?php $i++; ?>
    <tr>
      <td style="width: 130px" class="qty-small">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
      <td style="width: 144px" class="qty-small">{{ $activity->activity_name }}</td>
      <td style="width: 40px" class="qty-small"><span class="pull-right">{{ number_format(MoreOverview::laborTotal($activity), 2, ",",".") }}</td>
      <td style="width: 60px" class="qty-small"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(MoreOverview::laborActivity($activity), 2, ",",".") }}</span></td>
      <td style="width: 60px" class="qty-small"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(MoreOverview::materialActivityProfit($activity, $project->profit_more_contr_mat), 2, ",",".") }}</span></td>
      <td style="width: 60px" class="qty-small"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::equipmentActivityProfit($activity, $project->profit_more_contr_equip), 2, ",",".") }}</span></td>
      <td style="width: 60px" class="qty-small"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::activityTotalProfit($activity, $project->profit_more_contr_mat, $project->profit_more_contr_equip), 2, ",",".") }} </td>
      <td style="width: 40px" class="qty-small" text-center">&nbsp;<!-- Meerwerk --></td>
    </tr>
    @endforeach
    @endforeach
    <tr>
      <th style="width: 130px" class="qty-small"><strong>Totaal</strong></th>
      <th style="width: 144px" class="qty-small">&nbsp;</th>
      <td style="width: 40px" class="qty-small"><strong><span class="pull-right">{{ number_format(MoreOverview::contrLaborTotalAmount($project), 2, ",",".") }}</span></strong></td>
      <td style="width: 60px" class="qty-small"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::contrLaborTotal($project), 2, ",",".") }}</span></strong></td>
      <td style="width: 60px" class="qty-small"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::contrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
      <td style="width: 60px" class="qty-small"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::contrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
      <td style="width: 60px" class="qty-small"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::contrTotal($project), 2, ",",".") }}</span></strong></td>
      <th style="width: 40px" class="qty-small">&nbsp;</th>
    </tr>
  </tbody>
 </table>
 @endif

 @if ($project->use_subcontract)
  <?#--PAGE HEADER SECOND START--?>
  <div style="page-break-after:always;"></div>
  <header class="clearfix">
 
  </header>
  <?#--PAGE HEADER SECOND END--?>


  <h1>Onderaanneming</h1>

    <br>
    <h3 class="type">Calculatie</h3>
    <hr>

    <table class="table table-striped">

      <thead>
        <tr>
          <th style="width: 130px" class="qty-small">Onderdeel</th>
          <th style="width: 144px" class="qty-small">Werkzaamheden</th>
          <th style="width: 40px" class="qty-small"><span class="pull-right">Uren</th>
          <th style="width: 60px" class="qty-small"><span class="pull-right">Arbeid</th>
          <th style="width: 60px" class="qty-small"><span class="pull-right">Materiaal</th>
          <th style="width: 60px" class="qty-small"><span class="pull-right">Overig</th>
          <th style="width: 60px" class="qty-small"><span class="pull-right">Totaal</th>
          <th style="width: 40px" class="qty-small"><span class="text-center">Type</th>
        </tr>
      </thead>

      <tbody>
        <?php $j1 = 0; $j2 = 0; $j3 = 0; $j4 = 0; $j5 = 0; ?>
        @foreach (Chapter::where('project_id','=', $project->id)->orderBy('priority')->get() as $chapter)
        <?php $i = 0; ?>
        @foreach (Activity::where('chapter_id','=', $chapter->id)->whereNull('detail_id')->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->orderBy('priority')->get() as $activity)
        <?php $i++; ?>
        <tr>
          <td style="width: 130px" class="qty-small">{{ $i == 1 ? $chapter->chapter_name : '' }}</td>
          <td style="width: 144px" class="qty-small">{{ $activity->activity_name }}</td>
          @if (PartType::find($activity->part_type_id)->type_name=='estimate')
          <td style="width: 40px" class="qty-small"><span class="pull-right"><?php echo number_format(EstimateOverview::laborTotal($activity), 2, ",","."); $j1 += EstimateOverview::laborTotal($activity) ?></td>
          <td style="width: 60px" class="qty-small"><span class="pull-right total-ex-tax"><?php echo '&euro; '.number_format(EstimateOverview::laborActivity($activity), 2, ",","."); $j2 += EstimateOverview::laborActivity($activity) ?></span></td>
          <td style="width: 60px" class="qty-small"><span class="pull-right total-ex-tax"><?php echo '&euro; '.number_format(EstimateOverview::materialActivityProfit($activity, $project->profit_calc_subcontr_mat), 2, ",","."); $j3 += EstimateOverview::materialActivityProfit($activity, $project->profit_calc_subcontr_mat) ?></span></td>
          <td style="width: 60px" class="qty-small"><span class="pull-right"><?php echo '&euro; '.number_format(EstimateOverview::equipmentActivityProfit($activity, $project->profit_calc_subcontr_equip), 2, ",","."); $j4 += EstimateOverview::equipmentActivityProfit($activity, $project->profit_calc_subcontr_equip) ?></span></td>
          <td style="width: 60px" class="qty-small"><span class="pull-right"><?php echo '&euro; '.number_format(EstimateOverview::activityTotalProfit($activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip), 2, ",","."); $j5 += EstimateOverview::activityTotalProfit($activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip) ?></td>
          @else
          <td style="width: 40px" class="qty-small"><span class="pull-right"><?php echo number_format(CalculationOverview::laborTotal($activity), 2, ",","."); $j1 += CalculationOverview::laborTotal($activity) ?></td>
          <td style="width: 60px" class="qty-small"><span class="pull-right total-ex-tax"><?php echo '&euro; '.number_format(CalculationOverview::laborActivity($project->hour_rate, $activity), 2, ",","."); $j2 += CalculationOverview::laborActivity($project->hour_rate, $activity) ?></span></td>
          <td style="width: 60px" class="qty-small"><span class="pull-right total-ex-tax"><?php echo '&euro; '.number_format(CalculationOverview::materialActivityProfit($activity, $project->profit_calc_subcontr_mat), 2, ",","."); $j3 += CalculationOverview::materialActivityProfit($activity, $project->profit_calc_subcontr_mat) ?></span></td>
          <td style="width: 60px" class="qty-small"><span class="pull-right"><?php echo '&euro; '.number_format(CalculationOverview::equipmentActivityProfit($activity, $project->profit_calc_subcontr_equip), 2, ",","."); $j4 += CalculationOverview::equipmentActivityProfit($activity, $project->profit_calc_subcontr_equip) ?></span></td>
          <td style="width: 60px" class="qty-small"><span class="pull-right"><?php echo '&euro; '.number_format(CalculationOverview::activityTotalProfit($project->hour_rate, $activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip), 2, ",","."); $j5 += CalculationOverview::activityTotalProfit($project->hour_rate, $activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip) ?></td>
          @endif
          <td style="width: 40px" class="qty-small" text-center">{{ PartType::find($activity->part_type_id)->type_name=='estimate' ? 'Stelpost' : '' }}</td>
        </tr>
        @endforeach
        @endforeach
        <tr>
          <th style="width: 130px" class="qty-small"><strong>Totaal Onderaanneming</strong></th>
          <th style="width: 144px" class="qty-small">&nbsp;</th>
          <td style="width: 40px" class="qty-small"><strong><span class="pull-right">{{ number_format($j1, 2, ",",".") }}</span></strong></td>
          <td style="width: 60px" class="qty-small"><strong><span class="pull-right">{{ '&euro; '.number_format($j2, 2, ",",".") }}</span></strong></td>
          <td style="width: 60px" class="qty-small"><strong><span class="pull-right">{{ '&euro; '.number_format($j3, 2, ",",".") }}</span></strong></td>
          <td style="width: 60px" class="qty-small"><strong><span class="pull-right">{{ '&euro; '.number_format($j4, 2, ",",".") }}</span></strong></td>
          <td style="width: 60px" class="qty-small"><strong><span class="pull-right">{{ '&euro; '.number_format($j5, 2, ",",".") }}</span></strong></td>
          <th style="width: 40px" class="qty-small">&nbsp;</th>
        </tr>
      </tbody>
    </table>
   
    @if ($project->use_more)
    <br>
    <h3 class="type">Meerwerk</h3>
    <hr>
    
    <table class="table table-striped">

      <thead>
        <tr>
          <th style="width: 130px" class="qty-small">Onderdeel</th>
          <th style="width: 144px" class="qty-small">Werkzaamheden</th>
          <th style="width: 40px" class="qty-small"><span class="pull-right">Uren</th>
          <th style="width: 60px" class="qty-small"><span class="pull-right">Arbeid</th>
          <th style="width: 60px" class="qty-small"><span class="pull-right">Materiaal</th>
          <th style="width: 60px" class="qty-small"><span class="pull-right">Overig</th>
          <th style="width: 60px" class="qty-small"><span class="pull-right">Totaal</th>
          <th style="width: 40px" class="qty-small"><span class="text-center"><!-- Type --></th>
        </tr>
      </thead>

      <tbody>
        @foreach (Chapter::where('project_id','=', $project->id)->orderBy('priority')->get() as $chapter)
        <?php $i = 0; ?>
        @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->where('detail_id','=',Detail::where('detail_name','=','more')->first()->id)->orderBy('priority')->get() as $activity)
        <?php $i++; ?>
        <tr>
          <td style="width: 130px" class="qty-small">{{ $i==1 ? $chapter->chapter_name : ''}}</td>
          <td style="width: 144px" class="qty-small">{{ $activity->activity_name }}</td>
          <td style="width: 60px" class="qty-small"><span class="pull-right">{{ number_format(MoreOverview::laborTotal($activity), 2, ",",".") }}</td>
          <td style="width: 60px" class="qty-small"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(MoreOverview::laborActivity($activity), 2, ",",".") }}</span></td>
          <td style="width: 60px" class="qty-small"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(MoreOverview::materialActivityProfit($activity, $project->profit_more_subcontr_mat), 2, ",",".") }}</span></td>
          <td style="width: 60px" class="qty-small"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::equipmentActivityProfit($activity, $project->profit_more_subcontr_equip), 2, ",",".") }}</span></td>
          <td style="width: 60px" class="qty-small"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::activityTotalProfit($activity, $project->profit_more_subcontr_mat, $project->profit_more_subcontr_equip), 2, ",",".") }} </td>
          <td style="width: 40px" class="qty-small" text-center">&nbsp;<!-- Meerwerk --></td>
        </tr>
        @endforeach
        @endforeach
        <tr>
          <th style="width: 130px" class="qty-small"><strong>Totaal Onderaanneming</strong></th>
          <th style="width: 144px" class="qty-small">&nbsp;</th>
          <td style="width: 40px" class="qty-small"><strong><span class="pull-right">{{ number_format(MoreOverview::subcontrLaborTotalAmount($project), 2, ",",".") }}</span></strong></td>
          <td style="width: 60px" class="qty-small"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::subcontrLaborTotal($project), 2, ",",".") }}</span></strong></td>
          <td style="width: 60px" class="qty-small"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::subcontrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
          <td style="width: 60px" class="qty-small"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::subcontrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
          <td style="width: 60px" class="qty-small"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::subcontrTotal($project), 2, ",",".") }}</span></strong></td>
          <th style="width: 40px" class="qty-small">&nbsp;</th>
        </tr>
      </tbody>
    </table>
    @endif

    @if ($project->use_less)
    <br>
    <h3 class="type">Minderwerk</h3>
    <hr>
    
    <table class="table table-striped">

      <thead>
        <tr>
          <th style="width: 130px" class="qty-small">Onderdeel</th>
          <th style="width: 144px" class="qty-small">Werkzaamheden</th>
          <th style="width: 40px" class="qty-small"><span class="pull-right">Uren</th>
          <th style="width: 60px" class="qty-small"><span class="pull-right">Arbeid</th>
          <th style="width: 60px" class="qty-small"><span class="pull-right">Materiaal</th>
          <th style="width: 60px" class="qty-small"><span class="pull-right">Overig</th>
          <th style="width: 60px" class="qty-small"><span class="pull-right">Totaal</th>
          <th style="width: 40px" class="qty-small"><span class="text-center">&nbsp;<!-- Type --></th>
        </tr>
      </thead>

      <tbody>
        @foreach (Chapter::where('project_id','=', $project->id)->orderBy('priority')->get() as $chapter)
        <?php $i = 0; ?>
        @foreach (Activity::where('chapter_id','=', $chapter->id)->whereNull('detail_id')->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->where('part_type_id','=',PartType::where('type_name','=','calculation')->first()->id)->orderBy('priority')->get() as $activity)
        <?php $i++ ?>
        <tr>
          <td style="width: 130px" class="qty-small">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
          <td style="width: 144px" class="qty-small">{{ $activity->activity_name }}</td>
          <td style="width: 40px" class="qty-small"><span class="pull-right">{{ number_format(LessOverview::laborTotal($activity), 2, ",",".") }}</td>
          <td style="width: 60px" class="qty-small"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(LessOverview::laborActivity($activity, $project), 2, ",",".") }}</span></td>
          <td style="width: 60px" class="qty-small"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(LessOverview::materialActivityProfit($activity, $project->profit_calc_subcontr_mat), 2, ",",".") }}</span></td>
          <td style="width: 60px" class="qty-small"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::equipmentActivityProfit($activity, $project->profit_calc_subcontr_equip), 2, ",",".") }}</span></td>
          <td style="width: 60px" class="qty-small"><span class="pull-right">{{ '&euro; '.number_format(LessOverview::activityTotalProfit($activity, $project->profit_calc_subcontr_mat, $project->profit_calc_subcontr_equip, $project), 2, ",",".") }} </td>
          <td style="width: 40px" class="qty-small" text-center">&nbsp;<!-- {{ PartType::find($activity->part_type_id)->type_name=='estimate' ? 'Stelpost' : '' }} --></td>
        </tr>
        @endforeach
        @endforeach
        <tr>
          <th style="width: 130px" class="qty-small"><strong>Totaal Onderaanneming</strong></th>
          <th style="width: 144px" class="qty-small">&nbsp;</th>
          <td style="width: 40px" class="qty-small"><strong><span class="pull-right">{{ number_format(LessOverview::subcontrLaborTotalAmount($project), 2, ",",".") }}</span></strong></td>
          <td style="width: 60px" class="qty-small"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::subcontrLaborTotal($project), 2, ",",".") }}</span></strong></td>
          <td style="width: 60px" class="qty-small"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::subcontrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
          <td style="width: 60px" class="qty-small"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::subcontrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
          <td style="width: 60px" class="qty-small"><strong><span class="pull-right">{{ '&euro; '.number_format(LessOverview::subcontrTotal($project), 2, ",",".") }}</span></strong></td>
          <th style="width: 40px" class="qty-small">&nbsp;</th>
        </tr>
      </tbody>
    </table>
    @endif

  @endif

@else

  @if ($project->use_subcontract)
  <h1 class="type">Aanneming</h1>
  @endif

  <br>
  <h3 class="type">Werkzaamheden</h3>
  <hr>

  <table class="table table-striped">
    <thead>
      <tr>
        <th style="width: 130px" class="qty-small">Onderdeel</th>
        <th style="width: 144px" class="qty-small">Werkzaamheden</th>
        <th style="width: 40px" class="qty-small"><span class="pull-right">Uren</th>
        <th style="width: 60px" class="qty-small"><span class="pull-right">Arbeid</th>
        <th style="width: 60px" class="qty-small"><span class="pull-right">Materiaal</th>
        <th style="width: 60px" class="qty-small"><span class="pull-right">Overig</th>
        <th style="width: 60px" class="qty-small"><span class="pull-right">Totaal</th>
        <th style="width: 40px" class="qty-small"><span class="text-center">&nbsp;<!-- Type --></th>
      </tr>
    </thead>

    <tbody>
      @foreach (Chapter::where('project_id','=', $project->id)->orderBy('priority')->get() as $chapter)
      <?php $i = 0; ?>
      @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->where('detail_id','=',Detail::where('detail_name','=','more')->first()->id)->orderBy('priority')->get() as $activity)
      <?php $i++; ?>
      <tr>
        <td style="width: 130px" class="qty-small">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
        <td style="width: 144px" class="qty-small">{{ $activity->activity_name }}</td>
        <td style="width: 40px" class="qty-small"><span class="pull-right">{{ number_format(MoreOverview::laborTotal($activity), 2, ",",".") }}</td>
        <td style="width: 60px" class="qty-small"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(MoreOverview::laborActivity($activity), 2, ",",".") }}</span></td>
        <td style="width: 60px" class="qty-small"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(MoreOverview::materialActivityProfit($activity, $project->profit_more_contr_mat), 2, ",",".") }}</span></td>
        <td style="width: 60px" class="qty-small"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::equipmentActivityProfit($activity, $project->profit_more_contr_equip), 2, ",",".") }}</span></td>
        <td style="width: 60px" class="qty-small"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::activityTotalProfit($activity, $project->profit_more_contr_mat, $project->profit_more_contr_equip), 2, ",",".") }} </td>
        <td style="width: 40px" class="qty-small" text-center">&nbsp;<!-- Meerwerk --></td>
      </tr>
      @endforeach
      @endforeach
      <tr>
        <th style="width: 130px" class="qty-small"><strong>Totaal</strong></th>
        <th style="width: 144px" class="qty-small">&nbsp;</th>
        <td style="width: 40px" class="qty-small"><strong><span class="pull-right">{{ number_format(MoreOverview::contrLaborTotalAmount($project), 2, ",",".") }}</span></strong></td>
        <td style="width: 60px" class="qty-small"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::contrLaborTotal($project), 2, ",",".") }}</span></strong></td>
        <td style="width: 60px" class="qty-small"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::contrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
        <td style="width: 60px" class="qty-small"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::contrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
        <td style="width: 60px" class="qty-small"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::contrTotal($project), 2, ",",".") }}</span></strong></td>
        <th style="width: 40px" class="qty-small">&nbsp;</th>
      </tr>
    </tbody>
   </table>

   @if ($project->use_subcontract)
    <?#--PAGE HEADER SECOND START--?>
    <div style="page-break-after:always;"></div>
    <header class="clearfix">
   
    </header>
    <?#--PAGE HEADER SECOND END--?>


    <h1>Onderaanneming</h1>

      <br>
      <h3 class="type">Werkzaamheden</h3>
      <hr>
      
      <table class="table table-striped">

        <thead>
          <tr>
            <th style="width: 130px" class="qty-small">Onderdeel</th>
            <th style="width: 144px" class="qty-small">Werkzaamheden</th>
            <th style="width: 40px" class="qty-small"><span class="pull-right">Uren</th>
            <th style="width: 60px" class="qty-small"><span class="pull-right">Arbeid</th>
            <th style="width: 60px" class="qty-small"><span class="pull-right">Materiaal</th>
            <th style="width: 60px" class="qty-small"><span class="pull-right">Overig</th>
            <th style="width: 60px" class="qty-small"><span class="pull-right">Totaal</th>
            <th style="width: 40px" class="qty-small"><span class="text-center"><!-- Type --></th>
          </tr>
        </thead>

        <tbody>
          @foreach (Chapter::where('project_id','=', $project->id)->orderBy('priority')->get() as $chapter)
          <?php $i = 0; ?>
          @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_id','=',Part::where('part_name','=','subcontracting')->first()->id)->where('detail_id','=',Detail::where('detail_name','=','more')->first()->id)->orderBy('priority')->get() as $activity)
          <?php $i++; ?>
          <tr>
            <td style="width: 130px" class="qty-small">{{ $i==1 ? $chapter->chapter_name : ''}}</td>
            <td style="width: 144px" class="qty-small">{{ $activity->activity_name }}</td>
            <td style="width: 60px" class="qty-small"><span class="pull-right">{{ number_format(MoreOverview::laborTotal($activity), 2, ",",".") }}</td>
            <td style="width: 60px" class="qty-small"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(MoreOverview::laborActivity($activity), 2, ",",".") }}</span></td>
            <td style="width: 60px" class="qty-small"><span class="pull-right total-ex-tax">{{ '&euro; '.number_format(MoreOverview::materialActivityProfit($activity, $project->profit_more_subcontr_mat), 2, ",",".") }}</span></td>
            <td style="width: 60px" class="qty-small"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::equipmentActivityProfit($activity, $project->profit_more_subcontr_equip), 2, ",",".") }}</span></td>
            <td style="width: 60px" class="qty-small"><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::activityTotalProfit($activity, $project->profit_more_subcontr_mat, $project->profit_more_subcontr_equip), 2, ",",".") }} </td>
            <td style="width: 40px" class="qty-small" text-center">&nbsp;<!-- Meerwerk --></td>
          </tr>
          @endforeach
          @endforeach
          <tr>
            <th style="width: 130px" class="qty-small"><strong>Totaal Onderaanneming</strong></th>
            <th style="width: 144px" class="qty-small">&nbsp;</th>
            <td style="width: 40px" class="qty-small"><strong><span class="pull-right">{{ number_format(MoreOverview::subcontrLaborTotalAmount($project), 2, ",",".") }}</span></strong></td>
            <td style="width: 60px" class="qty-small"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::subcontrLaborTotal($project), 2, ",",".") }}</span></strong></td>
            <td style="width: 60px" class="qty-small"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::subcontrMaterialTotal($project), 2, ",",".") }}</span></strong></td>
            <td style="width: 60px" class="qty-small"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::subcontrEquipmentTotal($project), 2, ",",".") }}</span></strong></td>
            <td style="width: 60px" class="qty-small"><strong><span class="pull-right">{{ '&euro; '.number_format(MoreOverview::subcontrTotal($project), 2, ",",".") }}</span></strong></td>
            <th style="width: 40px" class="qty-small">&nbsp;</th>
          </tr>
        </tbody>
      </table>

    @endif

  @endif

  </main>

  </body>
</html>
