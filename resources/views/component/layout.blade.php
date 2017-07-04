@extends('layout.master')

@section('content')
<div id="wrapper">
    <section class="container fix-footer-bottom">

        @include('wizard.index')

        @include('layout.message')

        @yield('component_buttons')

        <h2><strong>{{ __("components.$title") }}</strong></h2>

        @yield('component_content')

    </section>
</div>
@stop
