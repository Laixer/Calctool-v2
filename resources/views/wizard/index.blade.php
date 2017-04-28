@if (empty($project))
    @include('wizard.empty')
@else
    @include("wizard.{$wizard}")
@endif
