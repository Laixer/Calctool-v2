<!DOCTYPE html>

<html @yield('manifest','')>
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="x-ua-compatible" content="ie=edge" />
		<title>CalculatieTool.com - @yield('title', 'Online calculeren & offreren')</title>
		<meta name="application-name" content="CalculatieTool.com">

		<?php // -- favicon -- ?>
		<link rel="apple-touch-icon" sizes="57x57" href="/images/apple-touch-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="/images/apple-touch-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="/images/apple-touch-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="/images/apple-touch-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="/images/apple-touch-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="/images/apple-touch-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="/images/apple-touch-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="/images/apple-touch-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="/images/apple-touch-icon-180x180.png">
		<link rel="icon" type="image/png" href="/images/favicon-32x32.png" sizes="32x32">
		<link rel="icon" type="image/png" href="/images/android-chrome-192x192.png" sizes="192x192">
		<link rel="icon" type="image/png" href="/images/favicon-96x96.png" sizes="96x96">
		<link rel="icon" type="image/png" href="/images/favicon-16x16.png" sizes="16x16">
		<link rel="manifest" href="/manifest.json">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="msapplication-TileImage" content="/images/mstile-144x144.png">
		<meta name="theme-color" content="#4e4e4e">
		<meta name="csrf-token" content="{{ csrf_token() }}" />
		<meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0" />

		<?php // -- CORE CSS -- ?>
		<link media="all" type="text/css" rel="stylesheet" href="/css/opensans.css">
		<link media="all" type="text/css" rel="stylesheet" href="/components/bootstrap/dist/css/bootstrap.min.css">
		<link media="all" type="text/css" rel="stylesheet" href="/components/font-awesome/css/font-awesome.css">
		@stack('style')

		<?php // -- SHOP CSS -- ?>
		<link media="all" type="text/css" rel="stylesheet" href="/css/shop.css">

		<?php //-- THEME CSS -- ?>
		<link media="all" type="text/css" rel="stylesheet" href="/css/essentials.css">
		<link media="all" type="text/css" rel="stylesheet" href="/css/layout.css">
		<link media="all" type="text/css" rel="stylesheet" href="/css/layout-responsive.css">
		<link media="all" type="text/css" rel="stylesheet" href="/css/darkgreen.css">

		<?php // -- CUSTOM CSS -- ?>
		<link media="all" type="text/css" rel="stylesheet" href="/css/custom.css">

		<?php // -- JQuery -- ?>
		<script src="/components/jquery/dist/jquery.min.js"></script>
		@if (!app('request')->input('nofeedback'))
		<script type="text/javascript">
			$.ajaxSetup({headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
			if (localStorage._prescnt) localStorage._prescnt++; else localStorage._prescnt = 1;
			if (!localStorage.lastPageTag) localStorage.lastPageTag = '/';
			$(document).ready(function(){
				@if (Auth::check())
				function _lpolupdate() {
					$.post('/api/v1/update', {location:window.location.href,prescount:localStorage._prescnt}, function() {
						setTimeout(_lpolupdate, 30000);
					}).fail(function(e) {
						if (e.status == 503) location.reload();
					})
				}
				_lpolupdate();
				@endif
			});
		</script>
		@endif

	</head>
	<body>
		<?php // -- ONLY DEV -- ?>
		@if(App()->environment('dev'))
		<div class="hidden-xs" style="color:#fff;background-color:red;z-index:200;position:fixed;top:0px;left:45%;width: 150px;text-align: center;"><a href="https://bitbucket.org/calctool/calctool-v2/commits/{{ File::get('../.revision') }}" style="color: black;">{{ 'REV: ' . substr(File::get('../.revision'), 0, 7) }}</a></div>
		@elseif(App()->environment('local'))
		<div class="hidden-xs" style="color:#fff;background-color:green;z-index:200;position:fixed;top:0px;left:45%;width: 150px;text-align: center;">{{ exec('git describe --always') }}</div>
		@endif

		<?php // -- HEADER -- ?>
		@section('header')
			@include('layout.header')
		@show

		<?php // -- MAIN CONTENT -- ?>
		@yield('content')


		<?php // -- FOOTER -- ?>
		@section('footer')
			@include('layout.footer')
		@show

		<?php // -- JAVASCRIPT FILES -- ?>
		<script src="/plugins/masonry.js"></script>

		<script src="/components/bootstrap/dist/js/bootstrap.min.js"></script>
		@stack('scripts')

		<script src="/js/scripts.js"></script>
	</body>
</html>
