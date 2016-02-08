@extends('layout.master')

@section('header')
<header id="topNav" class="topHead">
    <div class="container">

        <button class="btn btn-mobile" data-toggle="collapse" data-target=".nav-main-collapse">
            <i class="fa fa-bars"></i>
        </button>

        <a class="logo" href="/">
            <img src="/images/logo2.png" width="229px" alt="Calctool" />
        </a>

    </div>
</header>
@endsection

@section('content')
<div id="wrapper">

    <section class="container">

        <div class="row">

            <div class="col-md-12 text-center">
                <div class="e404"><i class="fa fa-refresh"></i></div>
                <h2>
                    De applicatie wordt geüpdatet...
                    <span class="subtitle">Ons excuus, probeer het in een paar minuten nogmaals</span>
                </h2>
            </div>

        </div>

    </section>

</div>
@stop
