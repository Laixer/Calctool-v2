<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8" />
		<title>CalculatieTool.com - Online calculeren & offreren</title>
		<meta name="keywords" content="Calculeren" />
		<meta name="description" content="" />
		<meta name="Author" content="CalculatieTool.com" />

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

		<?php // -- mobile settings -- ?>
		<meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0" />

		<?php // -- WEB FONTS -- ?>
		<link media="all" type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700,800">

		<?php // -- CORE CSS -- ?>
		<link media="all" type="text/css" rel="stylesheet" href="/plugins/bootstrap/css/bootstrap.min.css">
		<link media="all" type="text/css" rel="stylesheet" href="/css/font-awesome.css">
		<link media="all" type="text/css" rel="stylesheet" href="/plugins/owl-carousel/owl.carousel.css">
		<link media="all" type="text/css" rel="stylesheet" href="/plugins/owl-carousel/owl.theme.css">
		<link media="all" type="text/css" rel="stylesheet" href="/plugins/owl-carousel/owl.transitions.css">
		<link media="all" type="text/css" rel="stylesheet" href="/plugins/x-editable/css/bootstrap-editable.css">
		<link media="all" type="text/css" rel="stylesheet" href="/plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css">
		<link type="text/css" rel="stylesheet" href="/plugins/videojs/videojs-sublime-skin.css">

		<?php // -- SHOP CSS -- ?>
		<link media="all" type="text/css" rel="stylesheet" href="/css/shop.css">

		<?php //-- THEME CSS -- ?>
		<link media="all" type="text/css" rel="stylesheet" href="/css/essentials.css">
		<link media="all" type="text/css" rel="stylesheet" href="/css/layout.css">
		<link media="all" type="text/css" rel="stylesheet" href="/css/layout-responsive.css">
		<link media="all" type="text/css" rel="stylesheet" href="/css/darkgreen.css">

		<?php // -- CUSTOM CSS -- ?>
		<link media="all" type="text/css" rel="stylesheet" href="/css/custom.css">
		<link media="all" type="text/css" rel="stylesheet" href="/plugins/feedback/css/jquery.feedback_me.css">

		<?php // -- Morenizr -- ?>
		<script src="/plugins/modernizr.min.js"></script>

		<?php // -- JQuery -- ?>
		<script src="/plugins/jquery-2.1.4.min.js"></script>
		<script type="text/javascript">
			$.ajaxSetup({headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
			if (localStorage._prescnt) localStorage._prescnt++; else localStorage._prescnt = 1;
			if (!localStorage.lastPageTag) localStorage.lastPageTag = '/';

			$(document).ready(function(){
			    fm_options = {
			        position: "right-top",
			        name_required: true,
			        message_placeholder: "Opmerkingen, vragen en suggesties zijn welkom",
			        message_required: true,
					name_label: "Naam",
					message_label: "Bericht",
			        show_asterisk_for_required: true,
			        feedback_url: "/feedback",
			        delayed_options: {
			            send_fail : "Sending failed :(.",
			            send_success : "Bedankt voor de feedback!"
			        }
			    };
			    fm.init(fm_options);
			});

		</script>
	</head>
	<body>
		<?php // -- ONLY DEV -- ?>
		@if(App::environment('dev'))
		<div style="color:#fff;background-color:red;z-index:200;position:fixed;top:0px;left:45%;width: 150px;text-align: center;"><a href="https://bitbucket.org/calctool/calctool-v2/commits/{{ File::get('../.revision') }}" style="color: black;">{{ 'REV: ' . substr(File::get('../.revision'), 0, 7) }}</a></div>
		@elseif(App::environment('local'))
		<div style="color:#fff;background-color:green;z-index:200;position:fixed;top:0px;left:45%;width: 150px;text-align: center;">{{ exec('git describe --always') }}</div>
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
		<script src="/plugins/jquery.easing.1.3.js"></script>
		<script src="/plugins/jquery.cookie.js"></script>
		<script src="/plugins/jquery.appear.js"></script>
		<script src="/plugins/jquery.isotope.js"></script>
		<script src="/plugins/jquery.number.min.js"></script>
		<script src="/plugins/masonry.js"></script>

		<script src="/plugins/bootstrap/js/bootstrap.min.js"></script>
		<script src="/plugins/owl-carousel/owl.carousel.min.js"></script>
		<script src="/plugins/x-editable/js/bootstrap-editable.min.js"></script>
		<script src="/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
		<script src="/plugins/summernote/summernote.min.js"></script>
		<script src="/plugins/videojs/video.min.js"></script>
		<script src="/plugins/feedback/js/jquery.feedback_me.js"></script>

		<script src="/js/scripts.js"></script>
	</body>
</html>
