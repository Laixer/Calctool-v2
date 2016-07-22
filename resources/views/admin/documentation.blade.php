@extends('layout.master')

@section('title', 'Documentation')

@section('content')

<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			@if (count($errors) > 0)
			<div class="alert alert-danger">
				<i class="fa fa-frown-o"></i>
				<strong>Fout</strong>
				@foreach ($errors->all() as $error)
					{{ $error }}
				@endforeach
			</div>
			@endif

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
