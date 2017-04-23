@if (empty($project))
    @include('wizard.empty')
@elseif ($project->isQuickInvoice())
    @include('wizard.quickinvoice')
@elseif ($project->isDirectWork())
    @include('wizard.directwork')
@elseif ($project->isCalculation())
    @include('wizard.calculation')
@endif
