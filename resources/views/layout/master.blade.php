{{--
 * Copyright (C) 2017 Bynq.io B.V.
 * All Rights Reserved
 *
 * This file is part of the Dynq project.
 *
 * Content can not be copied and/or distributed without the express
 * permission of the author.
 *
 * @package  Dynq
 * @author   Yorick de Wid <y.dewid@calculatietool.com>
--}}

<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge,chrome=1" />
        <title>{{ config('app.name') }} @yield('title')</title>
        <meta name="application-name" content="{{ config('app.name') }}">

        {{-- Favicon --}}
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

        {{-- Global CSS --}}
        <link media="all" type="text/css" rel="stylesheet" href="/css/opensans.css">
        <link media="all" type="text/css" rel="stylesheet" href="/components/bootstrap/dist/css/bootstrap.min.css">
        <link media="all" type="text/css" rel="stylesheet" href="/components/font-awesome/css/font-awesome.css">

        @stack('style')

        {{-- Theme CSS --}}
        <link media="all" type="text/css" rel="stylesheet" href="/css/shop.css">
        <link media="all" type="text/css" rel="stylesheet" href="/css/essentials.css">
        <link media="all" type="text/css" rel="stylesheet" href="/css/layout.css">
        <link media="all" type="text/css" rel="stylesheet" href="/css/layout-responsive.css">
        <link media="all" type="text/css" rel="stylesheet" href="{{ APP_STYLESHEET }}">
        <!--<link media="all" type="text/css" rel="stylesheet" href="/css/darkblue.css">-->
        <!--<link media="all" type="text/css" rel="stylesheet" href="/css/layout-dark.css">-->

        {{-- Custom CSS --}}
        <link media="all" type="text/css" rel="stylesheet" href="/css/custom.css">

        {{-- jQuery --}}
        <script src="/components/jquery/dist/jquery.min.js"></script>

        @include('layout.script')

    </head>
    <body>
        @section('header')
            @include('layout.header')
        @show

        @yield('content')

        @section('footer')
            @include('layout.footer')
        @show

        {{-- Javascript --}}
        <script src="/components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="/plugins/masonry.js" defer></script>
        <script src="/js/scripts.js" defer></script>

        @stack('scripts')

        @stack('jsinline')

    </body>
</html>
