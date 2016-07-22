@extends('layout.master')

@section('title', 'Documentation')

@section('content')

<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			<div>
			<ol class="breadcrumb">
			  <li><a href="/">Home</a></li>
			  <li><a href="/admin">Admin CP</a></li>
			  <li><a href="/admin/documentation">Documentation</a></li>
			  @if ($dir)
			  <li><a href="/admin/documentation/{{ $dir }}">{{ ucfirst($dir) }}</a></li>
			  @endif
			  <li class="active">{{ ucfirst($page) }}</li>
			</ol>
			<div>
			<br />

			<div class="white-row">{!! $content !!}</div>

		</div>

	</section>

</div>
@stop
