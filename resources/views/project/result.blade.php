<?php

use BynqIO\CalculatieTool\Models\Project;
use BynqIO\CalculatieTool\Models\Chapter;
use BynqIO\CalculatieTool\Models\Activity;
use BynqIO\CalculatieTool\Models\PartType;
use BynqIO\CalculatieTool\Models\Part;
use BynqIO\CalculatieTool\Models\Time;
use BynqIO\CalculatieTool\Models\Detail;
use BynqIO\CalculatieTool\Models\TimesheetKind;
use BynqIO\CalculatieTool\Models\MoreLabor;
use BynqIO\CalculatieTool\Models\ProjectType;
use BynqIO\CalculatieTool\Calculus\SetEstimateCalculationEndresult;
use BynqIO\CalculatieTool\Calculus\MoreEndresult;
use BynqIO\CalculatieTool\Calculus\LessEndresult;
use BynqIO\CalculatieTool\Calculus\ResultEndresult;
use BynqIO\CalculatieTool\Calculus\TimesheetOverview;
use BynqIO\CalculatieTool\Models\Timesheet;
use BynqIO\CalculatieTool\Calculus\CalculationLabor;

$common_access_error = false;
$project = Project::find(Route::Input('project_id'));
if (!$project || !$project->isOwner() || $project->is_dilapidated)
    $common_access_error = true;
?>

@extends('layout.master')

@section('title', 'Projectresultaat')

<?php if($common_access_error){ ?>
@section('content')
<div id="wrapper">
    <section class="container">
        <div class="alert alert-danger">
            <i class="fa fa-frown-o"></i>
            <strong>Fout</strong>
            Dit project bestaat niet
        </div>
    </section>
</div>
@stop
<?php }else{ ?>

@section('content')
<script type="text/javascript">
$(document).ready(function() {

    $('#tab-result').click(function(e){
        sessionStorage.toggleTabRes{{Auth::user()->id}} = 'result'
    });
    $('#tab-budget').click(function(e){
        sessionStorage.toggleTabRes{{Auth::user()->id}} = 'budget'
    });
    $('#tab-hour_overview').click(function(e){
        sessionStorage.toggleTabRes{{Auth::user()->id}} = 'hour_overview'
    });

    if (sessionStorage.toggleTabRes{{Auth::user()->id}}){
        $toggleOpenTab = sessionStorage.toggleTabRes{{Auth::user()->id}}
        $('#tab-'+$toggleOpenTab).addClass('active');
        $('#'+$toggleOpenTab).addClass('active');
    } else {
        sessionStorage.toggleTabRes{{Auth::user()->id}} = 'result'
        $('#tab-result').addClass('active');
        $('#result').addClass('active');
    }

});
</script>
<style type="text/css">
.tooltip {
  top: 0;
  left: 50%;
  margin-left: -5px;
  border-bottom-color: #000000; /* black */
  border-width: 0 5px 5px;
}
</style>
<div id="wrapper">

    <section class="container fix-footer-bottom">

        @include('wizard.index', array('page' => 'result'))

            <h2><strong>Projectresultaat </strong> <sup><a data-toggle="tooltip" data-placement="bottom" data-original-title="Hier staan alle ingevoerde getallen samengevat in een projectresultaat en een urenresultaat waaruit een winst en verlies berekening voortkomt." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></sup></h2>

            <div class="tabs nomargin">

                <ul class="nav nav-tabs">
                    <li id="tab-result">
                        <a href="#result" data-toggle="tab">
                            <i class="fa fa-list-ol"></i> Projectresultaat
                        </a>
                    </li>
                    <li id="tab-hour_overview">
                        <a href="#hour_overview" data-toggle="tab">
                            <i class="fa fa-sort-amount-desc"></i> Resultaat urenregistratie
                        </a>
                    </li>
                    <li id="tab-budget">
                        <a href="#budget" data-toggle="tab">
                            <i class="fa fa-sort-amount-desc"></i> Winst / Verlies
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div id="result" class="tab-pane">

                        <h4>Aanneming</h4>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="col-md-4">&nbsp;</th>
                                    <th class="col-md-1">Calculatie</th>
                                    <th class="col-md-1">Minderwerk</th>
                                    <th class="col-md-1">Meerwerk</th>
                                    <th class="col-md-1">Balans</th>
                                    <th class="col-md-1">BTW</th>
                                    <th class="col-md-1">BTW bedrag</th>
                                    <th class="col-md-2">&nbsp;</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $header = false; ?>
                                @if (!$project->tax_reverse)
                                @if (ResultEndresult::conLaborBalanceTax1($project))
                                <tr>
                                    <td class="col-md-4"><?php echo "Arbeidskosten"; $header = true; ?></td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax1($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">21%</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax1AmountTax($project), 2, ",",".") }}</td>
                                    <td class="col-md-2">&nbsp;</td>
                                </tr>
                                @endif
                                @if (ResultEndresult::conLaborBalanceTax2($project))
                                <tr>
                                    <td class="col-md-4"><?php echo !$header ? "Arbeidskosten" : "" ?></td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax2($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">6%</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax2AmountTax($project), 2, ",",".") }}</td>
                                    <td class="col-md-2">&nbsp;</td>
                                </tr>
                                @endif
                                @else
                                @if (ResultEndresult::conLaborBalanceTax3($project))
                                <tr>
                                    <td class="col-md-4">Arbeidskosten</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conLaborBalanceTax3($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">0%</td>
                                    <td class="col-md-1">&nbsp;</td>
                                    <td class="col-md-2">&nbsp;</td>
                                </tr>
                                @endif
                                @endif

                                <?php $header = false; ?>
                                @if (!$project->tax_reverse)
                                @if (ResultEndresult::conMaterialBalanceTax1($project))
                                <tr>
                                    <td class="col-md-4"><?php echo "Materiaalkosten"; $header = true; ?></td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax1($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">21%</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax1AmountTax($project), 2, ",",".") }}</td>
                                    <td class="col-md-2">&nbsp;</td>
                                </tr>
                                @endif
                                @if (ResultEndresult::conMaterialBalanceTax2($project))
                                <tr>
                                    <td class="col-md-4"><?php echo !$header ? "Materiaalkosten" : "" ?></td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax2($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">6%</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax2AmountTax($project), 2, ",",".") }}</td>
                                    <td class="col-md-2">&nbsp;</td>
                                </tr>
                                @endif
                                @else
                                @if (ResultEndresult::conMaterialBalanceTax3($project))
                                <tr>
                                    <td class="col-md-4">Materiaalkosten</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conMaterialBalanceTax3($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">0%</td>
                                    <td class="col-md-1">&nbsp;</td>
                                    <td class="col-md-2">&nbsp;</td>
                                </tr>
                                @endif
                                @endif

                                <?php $header = false; ?>
                                @if (!$project->tax_reverse)
                                @if (ResultEndresult::conEquipmentBalanceTax1($project))
                                <tr>
                                    <td class="col-md-4"><?php echo "Overige kosten"; $header = true; ?></td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax1($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">21%</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax1AmountTax($project), 2, ",",".") }}</td>
                                    <td class="col-md-2">&nbsp;</td>
                                </tr>
                                @endif
                                @if (ResultEndresult::conEquipmentBalanceTax2($project))
                                <tr>
                                    <td class="col-md-4"><?php echo !$header ? "Overige kosten" : "" ?></td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax2($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">6%</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax2AmountTax($project), 2, ",",".") }}</td>
                                    <td class="col-md-2">&nbsp;</td>
                                </tr>
                                @endif
                                @else
                                @if (ResultEndresult::conEquipmentBalanceTax3($project))
                                <tr>
                                    <td class="col-md-4">Overige kosten</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::conCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::conCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::conCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::conEquipmentBalanceTax3($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">0%</td>
                                    <td class="col-md-1">&nbsp;</td>
                                    <td class="col-md-2">&nbsp;</td>
                                </tr>
                                @endif
                                @endif

                                <tr>
                                    <td class="col-md-4"><strong>Totaal Aanneming </strong></td>
                                    <td class="col-md-1"><strong>{{ '&euro; '.number_format(SetEstimateCalculationEndresult::totalContracting($project), 2, ",",".") }}</strong></td>
                                    <td class="col-md-1"><strong>{{ '&euro; '.number_format(LessEndresult::totalContracting($project), 2, ",",".") }}</strong></td>
                                    <td class="col-md-1"><strong>{{ '&euro; '.number_format(MoreEndresult::totalContracting($project), 2, ",",".") }}</strong></td>
                                    <td class="col-md-1"><strong>{{ '&euro; '.number_format(ResultEndresult::totalContracting($project), 2, ",",".") }}</strong></td>
                                    <td class="col-md-1">&nbsp;</td>
                                    <td class="col-md-1"><strong>{{ '&euro; '.number_format(ResultEndresult::totalContractingTax($project), 2, ",",".") }}</strong></td>
                                    <td class="col-md-2">&nbsp;</td>
                                </tr>
                            </tbody>
                        </table>

                        @if ($project->use_subcontract)
                        <h4>Onderaanneming</h4>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="col-md-4">&nbsp;</th>
                                    <th class="col-md-1">Calculatie</th>
                                    <th class="col-md-1">Minderwerk</th>
                                    <th class="col-md-1">Meerwerk</th>
                                    <th class="col-md-1">Balans</th>
                                    <th class="col-md-1">BTW</th>
                                    <th class="col-md-1">BTW bedrag</th>
                                    <th class="col-md-2">&nbsp;</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $header = false; ?>
                                @if (!$project->tax_reverse)
                                @if (ResultEndresult::subconLaborBalanceTax1($project))
                                <tr>
                                    <td class="col-md-4"><?php echo "Arbeidskosten"; $header = true; ?></td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::subconCalcLaborActivityTax1Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconLaborBalanceTax1($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">21%</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconLaborBalanceTax1AmountTax($project), 2, ",",".") }}</td>
                                    <td class="col-md-2">&nbsp;</td>
                                </tr>
                                @endif
                                @if (ResultEndresult::subconLaborBalanceTax2($project))
                                <tr>
                                    <td class="col-md-4"><?php echo !$header ? "Arbeidskosten" : "" ?></td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::subconCalcLaborActivityTax2Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconLaborBalanceTax2($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">6%</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconLaborBalanceTax2AmountTax($project), 2, ",",".") }}</td>
                                    <td class="col-md-2">&nbsp;</td>
                                </tr>
                                @endif
                                @else
                                @if (ResultEndresult::subconLaborBalanceTax3($project))
                                <tr>
                                    <td class="col-md-4">Arbeidskosten</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::subconCalcLaborActivityTax3Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconLaborBalanceTax3($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">0%</td>
                                    <td class="col-md-1">&nbsp;</td>
                                    <td class="col-md-2">&nbsp;</td>
                                </tr>
                                @endif
                                @endif

                                <?php $header = false; ?>
                                @if (!$project->tax_reverse)
                                @if (ResultEndresult::subconMaterialBalanceTax1($project))
                                <tr>
                                    <td class="col-md-4"><?php echo "Materiaalkosten"; $header = true; ?></td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::subconCalcMaterialActivityTax1Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconMaterialBalanceTax1($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">21%</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconMaterialBalanceTax1AmountTax($project), 2, ",",".") }}</td>
                                    <td class="col-md-2">&nbsp;</td>
                                </tr>
                                @endif
                                @if (ResultEndresult::subconMaterialBalanceTax2($project))
                                <tr>
                                    <td class="col-md-4"><?php echo !$header ? "Materiaalkosten" : "" ?></td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::subconCalcMaterialActivityTax2Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconMaterialBalanceTax2($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">6%</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconMaterialBalanceTax2AmountTax($project), 2, ",",".") }}</td>
                                    <td class="col-md-2">&nbsp;</td>
                                </tr>
                                @endif
                                @else
                                @if (ResultEndresult::subconMaterialBalanceTax3($project))
                                <tr>
                                    <td class="col-md-4">Materiaalkosten</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::subconCalcMaterialActivityTax3Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconMaterialBalanceTax3($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">0%</td>
                                    <td class="col-md-1">&nbsp;</td>
                                    <td class="col-md-2">&nbsp;</td>
                                </tr>
                                @endif
                                @endif

                                <?php $header = false; ?>
                                @if (!$project->tax_reverse)
                                @if (ResultEndresult::subconEquipmentBalanceTax1($project))
                                <tr>
                                    <td class="col-md-4"><?php echo "Overige kosten"; $header = true; ?></td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::subconCalcEquipmentActivityTax1Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconEquipmentBalanceTax1($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">21%</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconEquipmentBalanceTax1AmountTax($project), 2, ",",".") }}</td>
                                    <td class="col-md-2">&nbsp;</td>
                                </tr>
                                @endif
                                @if (ResultEndresult::subconEquipmentBalanceTax2($project))
                                <tr>
                                    <td class="col-md-4"><?php echo !$header ? "Overige kosten" : "" ?></td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::subconCalcEquipmentActivityTax2Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconEquipmentBalanceTax2($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">6%</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconEquipmentBalanceTax2AmountTax($project), 2, ",",".") }}</td>
                                    <td class="col-md-2">&nbsp;</td>
                                </tr>
                                @endif
                                @else
                                @if (ResultEndresult::subconEquipmentBalanceTax3($project))
                                <tr>
                                    <td class="col-md-4">Overige kosten</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(SetEstimateCalculationEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(LessEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(MoreEndresult::subconCalcEquipmentActivityTax3Amount($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::subconEquipmentBalanceTax3($project), 2, ",",".") }}</td>
                                    <td class="col-md-1">0%</td>
                                    <td class="col-md-1">&nbsp;</td>
                                    <td class="col-md-2">&nbsp;</td>
                                </tr>
                                @endif
                                @endif	

                                <tr>
                                    <td class="col-md-4"><strong>Totaal Onderaanneming</strong></td>
                                    <td class="col-md-1"><strong>{{ '&euro; '.number_format(SetEstimateCalculationEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
                                    <td class="col-md-1"><strong>{{ '&euro; '.number_format(LessEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
                                    <td class="col-md-1"><strong>{{ '&euro; '.number_format(MoreEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
                                    <td class="col-md-1"><strong>{{ '&euro; '.number_format(ResultEndresult::totalSubcontracting($project), 2, ",",".") }}</strong></td>
                                    <td class="col-md-1">&nbsp;</td>
                                    <td class="col-md-1"><strong>{{ '&euro; '.number_format(ResultEndresult::totalSubcontractingTax($project), 2, ",",".") }}</strong></td>
                                    <td class="col-md-2">&nbsp;</td>
                                </tr>
                            </tbody>
                        </table>
                        @endif

                        <h4>Totalen</h4>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="col-md-4">&nbsp;</th>
                                    <th class="col-md-3">&nbsp;</th>
                                    <th class="col-md-2">Bedrag (excl. BTW)</th>
                                    <th class="col-md-1">BTW bedrag</th>
                                    <th class="col-md-2"><span class="pull-right">Bedrag (incl. BTW)</span></th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td class="col-md-4"><strong>Cumulatief project (excl. BTW)</strong></td>
                                    <td class="col-md-3">&nbsp;</td>
                                    <td class="col-md-2"><strong>{{ '&euro; '.number_format(ResultEndresult::totalProject($project), 2, ",",".") }}</strong></td>
                                    <td class="col-md-1">&nbsp;</td>
                                    <td class="col-md-2">&nbsp;</td>
                                </tr>
                                @if (!$project->tax_reverse)
                                @if (ResultEndresult::totalContractingTax1($project))
                                <tr>
                                    <td class="col-md-4">BTW bedrag aanneming belast met 21%</td>
                                    <th class="col-md-3">&nbsp;</th>
                                    <td class="col-md-2">&nbsp;</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::totalContractingTax1($project), 2, ",",".") }}</td>
                                    <td class="col-md-2">&nbsp;</td>
                                </tr>
                                @endif
                                @if (ResultEndresult::totalContractingTax2($project))
                                <tr>
                                    <td class="col-md-4">BTW bedrag aanneming belast met 6%</td>
                                    <th class="col-md-3">&nbsp;</th>
                                    <td class="col-md-2">&nbsp;</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::totalContractingTax2($project), 2, ",",".") }}</td>
                                    <td class="col-md-2">&nbsp;</td>
                                </tr>
                                @endif
                                @if (ResultEndresult::totalSubcontractingTax1($project))
                                <tr>
                                    <td class="col-md-4">BTW bedrag onderaanneming belast met 21%</td>
                                    <th class="col-md-3">&nbsp;</th>
                                    <td class="col-md-2">&nbsp;</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::totalSubcontractingTax1($project), 2, ",",".") }}</td>
                                    <td class="col-md-2">&nbsp;</td>
                                </tr>
                                @endif
                                @if (ResultEndresult::totalSubcontractingTax2($project))
                                <tr>
                                    <td class="col-md-4">BTW bedrag onderaanneming belast met 6%</td>
                                    <th class="col-md-3">&nbsp;</th>
                                    <td class="col-md-2">&nbsp;</td>
                                    <td class="col-md-1">{{ '&euro; '.number_format(ResultEndresult::totalSubcontractingTax2($project), 2, ",",".") }}</td>
                                    <td class="col-md-2">&nbsp;</td>
                                </tr>
                                @endif
                                @endif
                                @if (ResultEndresult::totalProjectTax($project))
                                <tr>
                                    <td class="col-md-4">Cumulatief BTW bedrag</td>
                                    <th class="col-md-3">&nbsp;</th>
                                    <td class="col-md-2">&nbsp;</td>
                                    <td class="col-md-1"><strong>{{ '&euro; '.number_format(ResultEndresult::totalProjectTax($project), 2, ",",".") }}</strong></td>
                                    <td class="col-md-2">&nbsp;</td>
                                </tr>
                                @endif
                                <tr>
                                    <td class="col-md-4"><strong>Cumulatief project (Incl. BTW)</strong></td>
                                    <th class="col-md-3">&nbsp;</th>
                                    <td class="col-md-2">&nbsp;</td>
                                    <td class="col-md-1">&nbsp;</td>
                                    <td class="col-md-2"><strong><span class="pull-right">{{ '&euro; '.number_format(ResultEndresult::superTotalProject($project), 2, ",",".") }}</strong></span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                        <div id="hour_overview" class="tab-pane">
                            <div class="toogle">
                                @if(0)<div class="toggle active">@endif
                                    <label><h4>Calculatie</h4></label>
                                    <div class="toggle-content">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th class="col-md-3">Onderdeel</th>
                                                <th class="col-md-3">Werkzaamheden</th>
                                                <th class="col-md-2"><span class="pull-right">Gecalculeerd <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit zijn de gecalculeerde uren uit de offerte." href="javascript:void(0);"><i class="fa fa-info-circle"></i> </a></span></th>
                                                <th class="col-md-1"><span class="pull-right">Minderw. <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit is het bedrag dat voorkomt uit 'Calculeren Minderwerk'" href="#"><i class="fa fa-info-circle"></i></a></span></th>
                                                <th class="col-md-1"><span class="pull-right">Geboekt <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit zijn de geboekte uren uit de urenregistratie" href="#"><i class="fa fa-info-circle"></i></a></span></th>
                                                <th class="col-md-1"><span class="pull-right">Verschil <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit is het verschil tussen de gecalculeerde uren (minus de minderwerkuren) en de geboekte uren" href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></span></th>
                                                <th class="col-md-1"><span class="pull-right">Win./Ver. <a data-toggle="tooltip" data-placement="left" data-original-title="Dit is het verschil vertaald naar kosten op basis van het standaard uurtarief" href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></span></th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php $rs_1 = 0; $rs_2 = 0; $rs_3 = 0; $rs_4 = 0; $rs_5 = 0; ?>
                                            @foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
                                            <?php $i = 0; ?>
                                            @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_type_id','=',PartType::where('type_name','=','calculation')->first()->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->whereNull('detail_id')->orderBy('priority')->get() as $activity)
                                            <?php $i++; ?>
                                            <?php

                                            $total_hours = TimesheetOverview::calcTotalAmount($activity->id);
                                            $total_hours_original = TimesheetOverview::calcOrigTotalAmount($activity->id);
                                            $total_hours_less = TimesheetOverview::calcLessTotalAmount($activity->id);
                                            $total_registered_hours = Timesheet::where('activity_id','=',$activity->id)->sum('register_hour');

                                            // col 2
                                            $less_hours = 0;
                                            if ($total_hours_less) {
                                                $less_hours = $total_hours_less - $total_hours_original;
                                            }

                                            // col 3
                                            $registerd_hours = $total_registered_hours;

                                            // col 4
                                            $difference = $total_hours-$total_registered_hours;

                                            // col 5
                                            $gain_loss = ($total_hours-$total_registered_hours)*$project->hour_rate;

                                            $rs_1 += $total_hours_original;
                                            $rs_2 += $less_hours;
                                            $rs_3 += $registerd_hours;
                                            $rs_4 += $difference;
                                            $rs_5 += $gain_loss;

                                            ?>
                                            <tr>
                                                <td class="col-md-3">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
                                                <td class="col-md-3">{{ $activity->activity_name }}</td>
                                                <td class="col-md-2"><span class="pull-right">{{ number_format($total_hours_original, 2,",",".") }}</span></td>
                                                <td class="col-md-1"><span class="pull-right">{{ number_format($less_hours, 2,",",".") }}</span></td>
                                                <td class="col-md-1"><span class="pull-right">{{ number_format($registerd_hours, 2,",",".") }}</span></td>
                                                <td class="col-md-1"><span class="pull-right">{{ number_format($difference, 2,",",".") }}</span></td>
                                                <td class="col-md-1"><span class="pull-right">{{ number_format($gain_loss, 2,",",".") }}</span></td>
                                            </tr>
                                            @endforeach
                                            @endforeach
                                            <tr>
                                                <th class="col-md-3"><strong>Totaal Calculatie</strong></th>
                                                <th class="col-md-3">&nbsp;</th>
                                                <td class="col-md-2"><strong><span class="pull-right">{{ number_format($rs_1, 2, ",",".") }}</span></strong></td>
                                                <td class="col-md-1"><strong><span class="pull-right">{{ number_format($rs_2, 2, ",",".") }}</span></strong></td>
                                                <td class="col-md-1"><strong><span class="pull-right">{{ number_format($rs_3, 2, ",",".") }}</span></strong></td>
                                                <td class="col-md-1"><strong><span class="pull-right">{{ number_format($rs_4, 2, ",",".") }}</span></strong></td>
                                                <td class="col-md-1"><strong><span class="pull-right">{{ number_format($rs_5, 2, ",",".") }}</span></strong></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    </div>
                                @if(0)</div>@endif

                                @if ($project->use_estimate)
                                @if(0)<div class="toggle active">@endif
                                    <label><h4>Stelposten</h4></label>
                                    <div class="toggle-content">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th class="col-md-3">Onderdeel</th>
                                                <th class="col-md-3">Werkzaamheden</th>
                                                <th class="col-md-2"><span class="pull-right">Gecalculeerd <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit zijn de gecalculeerde uren uit de calculatie." href="javascript:void(0);"><i class="fa fa-info-circle"></i> </a></span></th>
                                                <th class="col-md-1"><span class="pull-right">Gesteld <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit zijn de gestelde uren vanuit 'Stelposten Stellen'" href="#"><i class="fa fa-info-circle"></i></a></span></th>
                                                <th class="col-md-1"><span class="pull-right">Geboekt <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit zijn de geboekte uren uit de urenregistratie" href="#"><i class="fa fa-info-circle"></i></a></span></th>
                                                <th class="col-md-1"><span class="pull-right">Verschil <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit is het verschil tussen de gecalculeerde OF de gestelde uren minus de geboekte uren." href="#"><i class="fa fa-info-circle"></i></a></span></th>
                                                <th class="col-md-1"><span class="pull-right">Win./Ver. <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit is het verschil vertaald naar kosten op basis van het standaard uurtarief" href="#"><i class="fa fa-info-circle"></i></a></span></th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php $rs_1 = 0; $rs_2 = 0; $rs_3 = 0; $rs_4 = 0; $rs_5 = 0; ?>
                                            @foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
                                            <?php $i = 0; ?>
                                            @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_type_id','=',PartType::where('type_name','=','estimate')->first()->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->orderBy('priority')->get() as $activity)
                                            <?php $i++; ?>
                                            <tr>
                                                <td class="col-md-3">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
                                                <td class="col-md-3">{{ $activity->activity_name }}</td>
                                                <td class="col-md-2"><span class="pull-right"><?php $rs_set = TimesheetOverview::estimOrigTotalAmount($activity->id); $rs_1 += $rs_set; echo $rs_set ? number_format($rs_set, 2,",",".") : '-'; ?></span></td></span></td>
                                                <td class="col-md-1"><span class="pull-right"><?php $rs_set = TimesheetOverview::estimSetTotalAmount($activity->id); $rs_set2 = TimesheetOverview::estimTimesheetTotalAmount($activity->id); $rs_2 = ($activity->use_timesheet ? $rs_set2 : $rs_set); echo ($activity->use_timesheet ? number_format($rs_set2, 2,",",".") : ($rs_set ? number_format($rs_set, 2,",",".") : '-')); ?></span></td></span></td>
                                                <td class="col-md-1"><span class="pull-right"><?php $rs_set = TimesheetOverview::estimTimesheetTotalAmount($activity->id); $rs_3 += $rs_set; echo $rs_set ? number_format($rs_set, 2,",",".") : '-'; ?></span></td></span></td>
                                                <td class="col-md-1"><span class="pull-right"><?php $rs_set = TimesheetOverview::estimSetTotalAmount($activity->id); $rs_set2 = TimesheetOverview::estimTimesheetTotalAmount($activity->id); $rs_2 = ($activity->use_timesheet ? $rs_set2 : $rs_set); $Z = Timesheet::where('activity_id','=',$activity->id)->sum('register_hour'); $rs_4 += ($rs_2-$Z); echo number_format($rs_2-$Z, 2,",","."); ?></span></td>
                                                <td class="col-md-1"><span class="pull-right"><?php $rs_set = TimesheetOverview::estimSetTotalAmount($activity->id); $rs_set2 = TimesheetOverview::estimTimesheetTotalAmount($activity->id); $rs_2 = ($activity->use_timesheet ? $rs_set2 : $rs_set); $Z = Timesheet::where('activity_id','=',$activity->id)->sum('register_hour'); $rs_5 += (($rs_2-$Z)*$project->hour_rate); echo number_format(($rs_2-$Z)*$project->hour_rate, 2,",","."); ?></span></td>
                                            </tr>
                                            @endforeach
                                            @endforeach
                                            <tr>
                                                <th class="col-md-3"><strong>Totaal Stelposten</strong></th>
                                                <th class="col-md-3">&nbsp;</th>
                                                <td class="col-md-2"><strong><span class="pull-right">{{ number_format($rs_1, 2, ",",".") }}</span></strong></td>
                                                <td class="col-md-1"><strong><span class="pull-right">{{ number_format($rs_2, 2, ",",".") }}</span></strong></td>
                                                <td class="col-md-1"><strong><span class="pull-right">{{ number_format($rs_3, 2, ",",".") }}</span></strong></td>
                                                <td class="col-md-1"><strong><span class="pull-right">{{ number_format($rs_4, 2, ",",".") }}</span></strong></td>
                                                <td class="col-md-1"><strong><span class="pull-right">{{ number_format($rs_5, 2, ",",".") }}</span></strong></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    </div>
                                @if(0)</div>@endif
                                @endif

                                @if ($project->use_more)
                                @if(0)<div class="toggle active">@endif
                                    <label><h4>Meerwerk</h4></label>
                                    <div class="toggle-content">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th class="col-md-3">Onderdeel</th>
                                                <th class="col-md-3">Werkzaamheden</th>
                                                <th class="col-md-2"><span class="pull-right">Gecalculeerd <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit zijn de (mondeling) opgegeven uren bij de tab 'Calculeren Meerwerk' die als prijsopgaaf kunnen dienen naar de klant. Wordt de urenregistratie bijgehouden dan is die bindend." href="#"><i class="fa fa-info-circle"></i></a></span></th>
                                                <th class="col-md-1"><span class="pull-right">&nbsp;</span></th>
                                                <th class="col-md-1"><span class="pull-right">Geboekt <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit zijn de geboekte uren uit de urenregistratie" href="#"><i class="fa fa-info-circle"></i></a></span></th>
                                                <th class="col-md-1"><span class="pull-right">Verschil <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit is het verschil tussen de gecalculeerde uren minus de geboekte uren." href="#"><i class="fa fa-info-circle"></i></a></span></th>
                                                <th class="col-md-1"><span class="pull-right">Win./Ver. <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit is het verschil vertaald naar kosten op basis van het standaard uurtarief" href="#"><i class="fa fa-info-circle"></i></a></span></th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php $rs_1 = 0; $rs_2 = 0; $rs_3 = 0; $rs_4 = 0; ?>
                                            @foreach (Chapter::where('project_id','=', $project->id)->get() as $chapter)
                                            <?php $i = 0; ?>
                                            @foreach (Activity::where('chapter_id','=', $chapter->id)->where('part_type_id','=',PartType::where('type_name','=','calculation')->first()->id)->where('part_id','=',Part::where('part_name','=','contracting')->first()->id)->where('detail_id','=',Detail::where('detail_name','=','more')->first()->id)->get() as $activity)
                                            <?php $i++; ?>
                                            <tr>
                                                <td class="col-md-3">{{ $i==1 ? $chapter->chapter_name : '' }}</td>
                                                <td class="col-md-3">{{ $activity->activity_name }}</td>
                                                <td class="col-md-2"><span class="pull-right">
                                                <?php
                                                    $rs_set = Timesheet::where('activity_id','=',$activity->id)->where('timesheet_kind_id','=',TimesheetKind::where('kind_name','=','meerwerk')->first()->id)->sum('register_hour');
                                                    $x = ($activity->use_timesheet ? $rs_set : MoreLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->sum('amount'));
                                                    $rs_1 += $x;
                                                    echo number_format($activity->use_timesheet ? $rs_set : MoreLabor::where('activity_id','=',$activity->id)->whereNull('hour_id')->sum('amount'), 2,",",".")
                                                ?>													
                                                </span></td>
                                                <td class="col-md-1"><span class="pull-right">&nbsp;</span></td>
                                                <td class="col-md-1"><span class="pull-right">
                                                <?php
                                                    $rs_set = Timesheet::where('activity_id','=',$activity->id)->where('timesheet_kind_id','=',TimesheetKind::where('kind_name','=','meerwerk')->first()->id)->sum('register_hour');
                                                    $y = $rs_set;
                                                    $rs_2 += $y;
                                                    echo number_format($rs_set, 2,",",".")
                                                ?></span></td>
                                                <td class="col-md-1"><span class="pull-right"><?php $rs_3 += ($x-$y); echo number_format($x-$y, 2,",",".") ?></span></td>
                                                <td class="col-md-1"><span class="pull-right"><?php $rs_4 += ($x-$y)*$project->hour_rate_more; echo number_format(($x-$y)*$project->hour_rate_more, 2,",",".") ?></span></td>
                                            </tr>
                                            @endforeach
                                            @endforeach
                                            <tr>
                                                <td class="col-md-3"><strong>Totaal Meerwerk</strong></td>
                                                <td class="col-md-3">&nbsp;</td>
                                                <td class="col-md-2"><strong><span class="pull-right">{{ number_format($rs_1, 2, ",",".") }}</span></strong></td>
                                                <td class="col-md-1"><strong><span class="pull-right">&nbsp;</span></strong></td>
                                                <td class="col-md-1"><strong><span class="pull-right">{{ number_format($rs_2, 2, ",",".") }}</span></strong></td>
                                                <td class="col-md-1"><strong><span class="pull-right">{{ number_format($rs_3, 2, ",",".") }}</span></strong></td>
                                                <td class="col-md-1"><strong><span class="pull-right">{{ number_format($rs_4, 2, ",",".") }}</span></strong></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    </div>
                                @if(0)</div>@endif
                                @endif

                                <span><strong>Het is voor onderaanneming niet mogelijk een urenregistratie bij te houden.</strong></span>

                            </div>
                        </div>

                        <div id="budget" class="tab-pane">

                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="col-md-2">&nbsp;</th>
                                        <th class="col-md-2">Balans project <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit is het uiteindelijke factuurbedrag van het project, hierin zit ook het stellen van de stelposten en het meer- en minderwerk verrekend." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></th>
                                        <th class="col-md-3">Totaalkosten urenregistratie <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit zijn de totaalkosten van het project die voortvloeien uit de geboekte uren uit de urenregistratie vermenigvuldigd met het geldende uurtarief. Let op: dit geldt alleen voor aanneming. Van de onderaanneming is dit niet bekent omdat we daar geen urenregistratie van bij kunnen houden." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></th>
                                        <th class="col-md-3">Totaalkosten inkoopfacturen <a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit zijn de totaalkosten van alle inkoopfacturen zoals die ingeboekt zijn bij de inkoop. Hier wordt aanneming en onderaanneming wel gescheiden." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></th>
                                        <th class="col-md-2">Winst / Verlies project <a data-toggle="tooltip" data-placement="left" data-original-title="Hier staat uiteindelijk of je winst of verlies gemaakt heb op je project. Om een reëel beeld te krijgen is het belangrijk dat je alle uren en inkoopfacturen hebt ingeboekt en het eventuele meer- en minderwerk hebt verwerkt." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr>
                                        <td class="col-md-2"><strong>Aanneming</strong></td>
                                        <td class="col-md-2">{{ '&euro; '.number_format(ResultEndresult::totalContracting($project), 2, ",",".") }}</td>
                                        <td class="col-md-3">{{ '&euro; '.number_format(ResultEndresult::totalTimesheet($project), 2, ",",".") }}</td>
                                        <td class="col-md-3">{{ '&euro; '.number_format(ResultEndresult::totalContractingPurchase($project), 2, ",",".") }}</td>
                                        <td class="col-md-2">{{ '&euro; '.number_format(ResultEndresult::totalContractingBudget($project), 2, ",",".") }}</td>
                                    </tr>
                                    @if ($project->use_subcontract)
                                    <tr>
                                        <td class="col-md-2"><strong>Onderaanneming</strong></td>
                                        <td class="col-md-2">{{ '&euro; '.number_format(ResultEndresult::totalSubcontracting($project), 2, ",",".") }}</td>
                                        <td class="col-md-3">-</td>
                                        <td class="col-md-3">{{ '&euro; '.number_format(ResultEndresult::totalSubcontractingPurchase($project), 2, ",",".") }}</td>
                                        <td class="col-md-2">{{ '&euro; '.number_format(ResultEndresult::totalSubcontractingBudget($project), 2, ",",".") }}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                                <strong>Weergegeven bedragen zijn exclusief BTW</strong>
                        </div>

                </div>

            </div>


        </div>

    </section>

</div>

@stop

<?php } ?>
