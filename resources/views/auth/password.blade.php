@extends('layout.master')

@section('title', 'Nieuw wachtwoord')

@section('content')
<div id="wrapper">

    <div id="shop">

        <section class="container">

            <div class="row">

                <div class="col-md-6">

                    <h2>Nieuwe <strong>Wachtwoord</strong></h2>

                    <form method="POST" action="" accept-charset="UTF-8" class="white-row">
                    {!! csrf_field() !!}

                        @if (Session::has('success'))
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

                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label for="secret">Wachtwoord</label>
                                    <input class="form-control" name="secret" type="password" id="secret" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label for="secret_confirmation">Herhaal wachtwoord</label>
                                    <input class="form-control" name="secret_confirmation" type="password" id="secret_confirmation" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="submit" value="Opslaan" class="btn btn-primary pull-right push-bottom">
                            </div>
                        </div>

                    </form>

                </div>

                <div class="col-md-6">

                    <h2>&nbsp;</h2>

                    <div class="white-row">

                        <h4>Registreren is snel, makkelijk en gratis</h4>

                        <p>Als je eenmaal geregistreerd bent, kun je:</p>
                        <ul class="list-icon check">
                            <li>Alle opties van het programma gebruiken.</li>
                            <li>Calculaties van A-Z opzetten.</li>
                            <li>In één handomdraai offertes en facturen genereren.</li>
                            <li>Een totale administratie voeren voor elk gewenst project.</li>
                        </ul>

                        <hr class="half-margins">

                        <p>
                            Heb je al een account?
                            <a href="/auth/signin">log dan hier in</a>
                        </p>
                    </div>

                    </div>

            </div>

            <div class="white-row">
                        <h4>Klantenservice</h4>
                        <p>
                            Als u op zoek bent naar hulp of gewoon een vraag wilt stellen, neem dan <a href="about">contact</a> met ons op.
                        </p>
                    </div>
        </section>

    </div>
</div>
@stop
