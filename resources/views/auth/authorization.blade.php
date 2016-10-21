@extends('layout.master')

@section('title', 'Applicatie toegang')

@section('header')

<header id="topNav" class="topHead">
    <div class="container">
        <a class="logo" href="/">
            <img src="/images/logo2.png" width="229px" alt="Calctool" />
        </a>
    </div>
</header>
@endsection

@section('content')

<div id="wrapper">

    <div id="shop">

        <section class="container">

            <div class="row">

                <div class="col-md-12">

                    <h2>Applicatie <strong>toegang</strong></h2>

                    <form method="POST" action="" accept-charset="UTF-8" class="white-row">
                        {!! csrf_field() !!}
                        <input type="hidden" name="client_id" value="{{ $params['client_id'] }}">
                        <input type="hidden" name="redirect_uri" value="{{ $params['redirect_uri'] }}">
                        <input type="hidden" name="response_type" value="{{ $params['response_type'] }}">
                        <input type="hidden" name="state" value="{{ $params['state'] }}">

                        @if(Session::get('success'))
                        <div class="alert alert-success">
                            <i class="fa fa-check-circle"></i>
                            <strong>{{ Session::get('success') }}</strong>
                        </div>
                        @endif

                        @if($errors->any())
                        <div class="alert alert-danger">
                            <i class="fa fa-frown-o"></i>
                            @foreach ($errors->all() as $error)
                            {{ $error }}
                            @endforeach
                        </div>
                        @endif

                        <div class="white-row nopadding-bottom">

                            <h4>Applicatie {{ $client->getName() }}</h4>

                            <p>Deze applicatie vraagt toegang tot:</p>
                            <ul class="list-icon check">
                                <li>Account gegevens</li>
                                <li>Bedrijfsgegevens</li>
                                <li>Offertes en factren</li>
                                <li>Relaties en persoonlijke instellingen</li>
                            </ul>

                            <hr class="half-margins">

                            <p>
                                Onvoldoende informatie?
                                <a href="/support">Vraag hulp</a>
                            </p>
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="submit" name="approve" value="Toestaan" class="btn btn-primary">
                                    <input type="submit" name="deny" value="Afwijzen" class="btn btn-danger">
                                </div>
                            </div>
                        </div>

                    </form>

                </div>

            </section>

        </div>
    </div>
@stop

