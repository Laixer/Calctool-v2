@extends('layout.master')

@section('title', 'Account aanmaken')

@push('style')
<link media="all" type="text/css" rel="stylesheet" href="/plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css">
@endpush

@push('scripts')
<script src="/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
@endpush

@section('content')
<script type="text/javascript">
$(function() {
    $(window).keydown(function(event){
        if(event.keyCode == 13 && !$('#tos').prop('checked')) {
            event.preventDefault();
            return false;
        }
    });
    $("[name='tos']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'}).on('switchChange.bootstrapSwitch', function(event, state) {
        if (state) {
            $('#btn-submit').removeClass('disabled');
            $('#btn-submit').prop('disabled', false);
        } else {
            $('#btn-submit').addClass('disabled');
            $('#btn-submit').prop('disabled', true);
        }
    });
    $("#tos").click(function(e) {
        if ($(this).prop('checked')) {
            $('#btn-submit').removeClass('disabled');
            $('#btn-submit').prop('disabled', false);
        } else {
            $('#btn-submit').addClass('disabled');
            $('#btn-submit').prop('disabled', true);
        }
    });
    $('#passtoggle').click(function(){
        if ($('#secret').attr('type') == 'password') {
            $('#secret').attr('type', 'text');
            $('#passtoggle i').remove();
            $('<i class="glyphicon icon-eye-close glyphicon-eye-close"></i>').appendTo('#passtoggle');
        } else {
            $('#secret').attr('type', 'password');
            $('#passtoggle i').remove();
            $('<i class="glyphicon icon-eye-open glyphicon-eye-open"></i>').appendTo('#passtoggle');
        }
    });
});
</script>
<div id="wrapper">

    <div id="shop">

        <section class="container">

            <div class="row">

                <div class="col-md-6">

                    <h2>Maak een <strong>Account</strong> aan</h2>

                    <form action="/auth/signup" method="post" class="white-row">
                        {!! csrf_field() !!}

                        @if (isset($client_referer))
                        <input name="client_referer" type="hidden" value="{{ $client_referer }}" />
                        @endif

                        @if (Session::has('success'))
                        <div class="alert alert-success">
                            <i class="fa fa-check-circle"></i>
                            <strong>{{ Session::get('success') }}</strong>
                        </div>
                        @endif

                        @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <i class="fa fa-frown-o"></i>
                            <strong>Fouten in aanmaak nieuw account</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li><h5 class="nomargin">{{ $error }}</h5></li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label for="contact_firstname">Voornaam</label>
                                    <input class="form-control" name="contact_firstname" type="text" id="contact_firstname" value="{{ old('contact_firstname') }}" required/>
                                </div>
                                <div class="col-md-6">
                                    <label for="contact_name">Achternaam</label>
                                    <input class="form-control" name="contact_name" type="text" id="contact_name" value="{{ old('contact_name') }}" required/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label for="company_name">Bedrijfsnaam (optioneel)</label>
                                    <input class="form-control" name="company_name" type="text" id="company_name" value="{{ old('company_name') }}" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label for="email">E-mail adres</label>
                                    <input class="form-control" name="email" type="email" id="email" value="{{ old('email') }}" required/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label for="secret">Wachtwoord</label>
                                    <div class="input-append input-group">
                                        <input id="secret" name="secret" class="form-control" type="password" autocomplete="off">
                                        <input type="text" class="form-control" placeholder="password" style="display: none;">
                                        <span tabindex="100" id="passtoggle" title="Klik om wachtwoord te tonen/verbergen" class="add-on input-group-addon" style="cursor: pointer;">
                                            <i class="icon-eye-open glyphicon glyphicon-eye-open"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="secret_confirmation">Herhaal wachtwoord</label>
                                    <input class="form-control" name="secret_confirmation" type="password" value="" id="secret_confirmation" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <span class="form-group">
                                    <input name="tos" type="checkbox" value="1" id="tos">
                                    <label for="tos" style="margin-left:10px;">Ik ga akkoord met de <a target="blank" href="/terms-and-conditions">algemene voorwaarden</a></label>
                                </span>
                            </div>
                            <div class="col-md-12">
                                <input type="submit" id="btn-submit" disabled value="Aanmelden" class="btn btn-primary pull-right push-bottom disabled item-full" data-loading-text="Loading...">
                            </div>
                        </div>

                    </form>

                </div>

                <div class="col-md-6 hidden-sm hidden-xs">

                    <h2>Waarom <strong> Registreren</strong>?</h2>

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

                        <h4>Support</h4>
                        <p>
                            Als u op zoek bent naar hulp of gewoon een vraag wilt stellen, neem dan <a href="/support">contact</a> met ons op.
                        </p>

                    </div>

                </div>

            </div>

            <div class="white-row hidden-sm hidden-xs">
                <h4>Direct inloggen?</h4>
                <p>
                    Heb je al een account?
                    <a href="/login">log dan hier in</a>
                </p>

            </section>

        </div>
    </div>
@stop
