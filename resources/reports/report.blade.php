<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'Report')</title>
    <style>@include('style')</style>
</head>

<body>

    @foreach($pages as $page)

    <div style="height: 35cm;position:relative;">

    @isset($overlay)
    <div id="overlay">{{ strtoupper($overlay) }}</div>
    @endisset

    <header class="clearfix">
        <div id="logo">
            <img src="{{ $logo }}" />
        </div>
        <div id="company">
            <h2 class="name">{{ $company }}</h2>
            <div>{{ $address }}</div>
            <div>{{ $phone }}</div>
            <div><a href="mailto:{{ $email }}">{{ $email }}</a></div>
        </div>
    </header>

    <main>@yield("body_$page")</main>

    <footer>
        <span>@yield('footer')</span>
    </footer>

    </div>

    @endforeach

</body>
</html>
