@inject('agent', 'Jenssegers\Agent\Agent')

@if ($agent->isMobile())
<div class="alert alert-warning">
    <i class="fa fa-warning"></i>
    <strong>@lang('core.mobilewarning')</strong>
</div>
@endif
