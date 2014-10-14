<!DOCTYPE html>
<!--[if IE 8]><html class="ie ie8"><![endif]-->
<!--[if IE 9]><html class="ie ie9"><![endif]-->
<html>
	<head>
		<meta charset="utf-8" />
		<title>{{{ $title or 'Calctool' }}}</title>
		<meta name="keywords" content="HTML5,CSS3,Template" />
		<meta name="description" content="" />
		<meta name="Author" content="Dorin Grigoras [www.stepofweb.com]" />

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
		{{ HTML::style('plugins/magnific-popup/magnific-popup.css') }}
		{{ HTML::style('css/animate.css') }}
		{{ HTML::style('css/superslides.css') }}

		<?# -- SHOP CSS -- ?>
		{{ HTML::style('css/shop.css') }}

		<!-- THEME CSS -->
		{{ HTML::style('css/essentials.css') }}
		{{ HTML::style('css/layout.css') }}
		{{ HTML::style('css/layout-responsive.css') }}
		{{ HTML::style('css/darkgreen.css') }}

		<?# -- Morenizr -- ?>
		{{ HTML::script('plugins/modernizr.min.js') }}
	</head>
	<body>

		@section('header')
			@include('layout.header')
		@show

		@yield('content')

		@section('footer')
			@include('layout.footer')
		@show

		<?# -- JAVASCRIPT FILES -- ?>
		{{ HTML::script('plugins/jquery-2.0.3.min.js') }}
		{{ HTML::script('plugins/jquery.easing.1.3.js') }}
		{{ HTML::script('plugins/jquery.cookie.js') }}
		{{ HTML::script('plugins/jquery.appear.js') }}
		{{ HTML::script('plugins/jquery.isotope.js') }}
		{{ HTML::script('plugins/masonry.js') }}

		{{ HTML::script('plugins/bootstrap/js/bootstrap.min.js') }}
		{{ HTML::script('plugins/magnific-popup/jquery.magnific-popup.min.js') }}
		{{ HTML::script('plugins/owl-carousel/owl.carousel.min.js') }}
		{{ HTML::script('plugins/stellar/jquery.stellar.min.js') }}
		{{ HTML::script('plugins/knob/js/jquery.knob.js') }}
		{{ HTML::script('plugins/jquery.backstretch.min.js') }}
		{{ HTML::script('plugins/superslides/dist/jquery.superslides.min.js') }}

		{{ HTML::script('js/scripts.js') }}

	</body>
</html>
