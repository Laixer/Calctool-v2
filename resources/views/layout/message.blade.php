@inject('agent', 'Jenssegers\Agent\Agent')

@if (Session::has('success'))
<div class="alert alert-success">
    <i class="fa fa-check-circle"></i>
    <strong>{{ Session::get('success') }}</strong>
</div>
@endif

@if (count($errors) > 0)
<div class="alert alert-danger">
    <i class="fa fa-frown-o"></i>
    <strong>Fouten in de invoer</strong>
    <ul>
        @foreach ($errors->all() as $error)
        <li><h5 class="nomargin">{{ $error }}</h5></li>
        @endforeach
    </ul>
</div>
@endif

@if ($agent->isMobile())
<div class="alert alert-warning">
    <i class="fa fa-warning"></i>
    <strong>@lang('core.mobilewarning')</strong>
</div>
@endif
