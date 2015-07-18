<!DOCTYPE html>
<!--[if IE 8]><html class="ie ie8"><![endif]-->
<!--[if IE 9]><html class="ie ie9"><![endif]-->
<html>
	<head>
		<meta charset="utf-8" />
		<title>Calctool - Online calculeren & offereren</title>
		<meta name="keywords" content="Calculeren" />
		<meta name="description" content="" />
		<meta name="Author" content="CalcTool.nl" />

		<?# -- favicon -- ?>
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

		<?# -- mobile settings -- ?>
		<meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0" />

		<?# -- WEB FONTS -- ?>
		{{ HTML::style('https://fonts.googleapis.com/css?family=Open+Sans:300,400,700,800') }}

		<?# -- CORE CSS -- ?>
		{{ HTML::style('plugins/bootstrap/css/bootstrap.min.css') }}
		{{ HTML::style('css/font-awesome.css') }}
		{{ HTML::style('plugins/owl-carousel/owl.carousel.css') }}
		{{ HTML::style('plugins/owl-carousel/owl.theme.css') }}
		{{ HTML::style('plugins/owl-carousel/owl.transitions.css') }}
		{{ HTML::style('plugins/x-editable/css/bootstrap-editable.css') }}
		{{ HTML::style('plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css') }}

		<?# -- SHOP CSS -- ?>
		{{ HTML::style('css/shop.css') }}

		<?#-- THEME CSS -- ?>
		{{ HTML::style('css/essentials.css') }}
		{{ HTML::style('css/layout.css') }}
		{{ HTML::style('css/layout-responsive.css') }}
		{{ HTML::style('css/darkgreen.css') }}

		<?# -- CUSTOM CSS -- ?>
		{{ HTML::style('css/custom.css') }}

		<?# -- Morenizr -- ?>
		{{ HTML::script('plugins/modernizr.min.js') }}

		<?# -- JQuery -- ?>
		{{ HTML::script('plugins/jquery-2.1.4.min.js') }}
	</head>
	<body>
		<?# -- ONLY DEV -- ?>
		@if(App::environment('dev'))
		<div style="background-color:red;z-index:200;position:fixed;top:0px;left:45%;width: 100px;text-align: center;"><a href="https://bitbucket.org/calctool/calctool-v2/commits/{{ File::get('../.revision') }}" style="color: black;">{{ 'REV: ' . substr(File::get('../.revision'), 0, 7) }}</a></div>
		@elseif(App::environment('local'))
		<div style="background-color:green;z-index:200;position:fixed;top:0px;left:45%;width: 100px;text-align: center;">local</div>
		@endif

		<?# -- HEADER -- ?>
		@section('header')
			@include('layout.header')
		@show

		<?# -- MAIN CONTENT -- ?>
		@yield('content')


		<?# -- FOOTER -- ?>
		@section('footer')
			@include('layout.footer')
		@show

		<?# -- JAVASCRIPT FILES -- ?>
		{{ HTML::script('plugins/jquery.easing.1.3.js') }}
		{{ HTML::script('plugins/jquery.cookie.js') }}
		{{ HTML::script('plugins/jquery.appear.js') }}
		{{ HTML::script('plugins/jquery.isotope.js') }}
		{{ HTML::script('plugins/jquery.number.min.js') }}
		{{ HTML::script('plugins/masonry.js') }}

		{{ HTML::script('plugins/bootstrap/js/bootstrap.min.js') }}
		{{ HTML::script('plugins/owl-carousel/owl.carousel.min.js') }}
		{{ HTML::script('plugins/x-editable/js/bootstrap-editable.min.js') }}
		{{ HTML::script('plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}

		{{ HTML::script('js/scripts.js') }}

	</body>
</html>
