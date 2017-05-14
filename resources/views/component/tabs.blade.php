@extends('component.layout', ['title' => $page])

@push('jsinline')
<script type="text/javascript">
$(document).ready(function() {
    @foreach($tabs as $tab)
    $('#tab-{{ $tab['name'] }}').click(function(e){
        sessionStorage.toggleTab{{ $component }}{{ Auth::id() }} = '{{ $tab['name'] }}';
        @isset($tab['async'])
            $('#{{ $tab['name'] }}').load('{{ $tab['async'] }}');
        @endisset
    });
    @endforeach

    if (sessionStorage.toggleTab{{ $component }}{{ Auth::id() }}){
        $toggleOpenTab = sessionStorage.toggleTab{{ $component }}{{ Auth::id() }};
        $('#tab-' + $toggleOpenTab).addClass('active');
        $('#' + $toggleOpenTab).addClass('active');
        @isset($tab['async'])
            $('#tab-' + $toggleOpenTab).trigger("click");
        @endisset
    } else {
        sessionStorage.toggleTab{{ $component }}{{ Auth::id() }} = '{{ $tab['name'] }}';
        $('#tab-{{ $tab['name'] }}').addClass('active');
        $('#{{ $tab['name'] }}').addClass('active');
    }
});
</script>
@endpush

@section('component_content')
<div class="tabs nomargin-top">

    @includeIf("component.{$page}.scope")

    <ul class="nav nav-tabs">
        @foreach($tabs as $tab)
        <li id="tab-{{ $tab['name'] }}">
            <a href="#{{ $tab['name'] }}" data-toggle="tab"><i class="fa {{ isset($tab['icon']) ? $tab['icon'] : '' }}"></i> {{ $tab['title'] }}</a>
        </li>
        @endforeach
    </ul>

    <div class="tab-content">

        @foreach($tabs as $tab)
        <div id="{{ $tab['name'] }}" class="tab-pane">

            @isset($tab['include'])
            @include("component.{$page}.{$tab['include']}", ['section' => $tab['name']])
            @else
            @include("component.{$page}.{$tab['name']}", ['section' => $tab['name']])
            @endisset

        </div>
        @endforeach

    </div>

</div>
@stop
