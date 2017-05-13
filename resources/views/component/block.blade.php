@extends('component.layout', ['title' => $page])

@section('component_content')
<div class="white-row">

    @include("component.{$page}.{$name}", ['section' => $name])

</div>
@stop
