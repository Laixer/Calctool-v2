<?php

use \CalculatieTool\Models\Relation;
use \CalculatieTool\Models\RelationType;
use \CalculatieTool\Models\RelationKind;
use \CalculatieTool\Models\Contact;
use \CalculatieTool\Models\ContactFunction;
use \CalculatieTool\Models\Province;
use \CalculatieTool\Models\Country;
use \CalculatieTool\Models\Resource;
use \CalculatieTool\Models\BankAccount;

$relation = Relation::find(Auth::user()->self_id);
$user = Auth::user();
?>

@extends('layout.master')

@section('title', 'Bedrijfsgegevens')

@push('style')
<link media="all" type="text/css" rel="stylesheet" href="/plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css">
@endpush

@push('scripts')
<script src="/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<script type="text/javascript" src="/js/iban.js"></script>
@endpush

@section('content')
<script type="text/javascript">
$(document).ready(function() {
    function prefixURL(field) {
        var cur_val = $(field).val();
        if (!cur_val)
            return;
        var ini = cur_val.substring(0,4);
        if (ini == 'http')
            return;
        else {
            if (cur_val.indexOf("www") >=0) {
                $(field).val('http://' + cur_val);
            } else {
                $(field).val('http://www.' + cur_val);
            }
        }
    }
    $('#tab-company').click(function(e){
        sessionStorage.toggleTabMyComp{{Auth::id()}} = 'company';
    });
    $('#tab-payment').click(function(e){
        sessionStorage.toggleTabMyComp{{Auth::id()}} = 'payment';
    });
    $('#tab-contact').click(function(e){
        sessionStorage.toggleTabMyComp{{Auth::id()}} = 'contact';
    });
    $('#tab-logo').click(function(e){
        sessionStorage.toggleTabMyComp{{Auth::id()}} = 'logo';
    });
    $('#tab-prefs').click(function(e){
        sessionStorage.toggleTabMyComp{{Auth::id()}} = 'prefs';
    });

    if (sessionStorage.toggleTabMyComp{{Auth::id()}}){
        $toggleOpenTab = sessionStorage.toggleTabMyComp{{Auth::id()}};
        $('#tab-'+$toggleOpenTab).addClass('active');
        $('#'+$toggleOpenTab).addClass('active');
    } else {
        sessionStorage.toggleTabMyComp{{Auth::id()}} = 'company';
        $('#tab-company').addClass('active');
        $('#company').addClass('active');
    }
    $('#website').blur(function(e) {
        prefixURL($(this));
    });
    $('#iban').blur(function() {
        if (! IBAN.isValid($(this).val()) ) {
            $(this).parent().addClass('has-error');
        } else {
            $(this).parent().removeClass('has-error');
        }
    });
    $('#account').blur(function() {
        if (! IBAN.isValid($(this).val()) ) {
            $(this).parent().addClass('has-error');
        } else {
            $(this).parent().removeClass('has-error');
        }
    });

    $('#kvk').blur(function() {
        var kvkcheck = $(this).val();
        if (kvkcheck.length != 8) {
            $(this).parent().addClass('has-error');
        } else {
            $(this).parent().removeClass('has-error');
        }
    });

    $('#btw').blur(function() {
        var btwcheck = $(this).val();
        if (btwcheck.length != 14) {
            $(this).addClass("error-input");
        }else {
            $(this).removeClass("error-input");
        }
    });

    $('#street').blur(function() {
        var streetcheck = $(this).val();
        var regx = /^[A-Za-z0-9\s]*$/;
        if( streetcheck != "" && regx.test(streetcheck)) {
            $(this).removeClass("error-input");
        }else {
            $(this).addClass("error-input");
        }
    });

    $(document).on('change', '.btn-file :file', function() {
      var input = $(this),
          numFiles = input.get(0).files ? input.get(0).files.length : 1,
          label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
      input.trigger('fileselect', [numFiles, label]);
    });

    $("[name='pref_use_ct_numbering']").bootstrapSwitch({onText: 'Ja',offText: 'Nee'});

    $('.btn-file :file').on('fileselect', function(event, numFiles, label) {

        var input = $(this).parents('.input-group').find(':text'),
            log = numFiles > 1 ? numFiles + ' files selected' : label;

        if( input.length ) {
            input.val(log);
        } else {
            if( log ) alert(log);
        }

    });
    
    var zipcode = $('#zipcode').val();
    var number = $('#address_number').val();
    $('.autoappend').blur(function(e){
        if (number == $('#address_number').val() && zipcode == $('#zipcode').val())
            return;
        zipcode = $('#zipcode').val();
        number = $('#address_number').val();
        if (number && zipcode) {

            $.post("/mycompany/quickstart/address", {
                zipcode: zipcode,
                number: number,
            }, function(data) {
                if (data) {
                    var json = data;
                    $('#street').val(json.street);
                    $('#city').val(json.city);
                    $("#province").find('option:selected').removeAttr("selected");
                    $('#province option[value=' + json.province_id + ']').attr('selected','selected');
                }
            });
        }
    });
});
</script>
<style>
.datepicker{z-index:1151 !important;}
</style>

<div class="modal fade" id="myYouTube" tabindex="-1" role="dialog" aria-labelledby="mYouTubeLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <iframe width="1280" height="720" src="https://www.youtube.com/embed/v0QnQpoXt-4" frameborder="0" allowfullscreen></iframe>

        </div>
    </div>
</div>

<div id="wrapper">
    
    <section class="container">

        <div class="col-md-12">

            <div>
                <ol class="breadcrumb">
                  <li><a href="/">Home</a></li>
                  <li class="active">Bedrijfsgegevens</li>
                </ol>
            <div>
            <br>

            @if (Session::has('success'))
            <div class="alert alert-success">
                <i class="fa fa-check-circle"></i>
                <strong>{{ Session::get('success') }}</strong>
            </div>
            @endif

            @if (count($errors) > 0)
            <div class="alert alert-danger">
                <i class="fa fa-frown-o"></i>
                <strong>Fouten in de invoer</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li><h5 class="nomargin">{{ $error }}</h5></li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if ($relation && !Contact::where('relation_id','=', $relation->id)->count())
                <div class="alert alert-warning">
                    <i class="fa fa-fa fa-info-circle"></i>
                    Er moet minimaal 1 contactpersoon bestaan
                </div>
            @endif

            @if(0)
            <div class="pull-right">
                <a href="/offer_invoice" class="btn btn-primary">Overzichten</a>
            </div>
            @endif

            <h2 style="margin: 10px 0 20px 0;"><strong>Bedrijfsgegevens</strong> &nbsp;&nbsp;<a class="fa fa-youtube-play yt-vid" href="javascript:void(0);" data-toggle="modal" data-target="#myYouTube"></a></h2>

                <div class="tabs nomargin-top">

                    <ul class="nav nav-tabs">
                        <li id="tab-company">
                            <a href="#company" data-toggle="tab"><i class="fa fa-info" aria-hidden="true"></i>Bedrijfsgegevens</a>
                        </li>
                        <li id="tab-contact">
                            <a href="#contact" data-toggle="tab"><i class="fa fa-users" aria-hidden="true"></i>Contacten</a>
                        </li>
                        <li id="tab-payment">
                            <a href="#payment" data-toggle="tab"><i class="fa fa-university" aria-hidden="true"></i>Betalingsgegevens</a>
                        </li>
                        <li id="tab-logo">
                            <a href="#logo" data-toggle="tab"><i class="fa fa-file-image-o" aria-hidden="true"></i>Logo & Voorwaarden</a>
                        </li>
                        <li id="tab-prefs">
                            <a href="#prefs" data-toggle="tab"><i class="fa fa-cogs" aria-hidden="true"></i>Voorkeuren</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div id="company" class="tab-pane">

                            {!! $relation ? '<form action="relation/updatemycompany?multipage=true" method="post">' : '<form action="relation/newmycompany" method="post">' !!}
                            {!! csrf_field() !!}

                            <div>
                            <h4 class="company" >Bedrijfsgegevens</h4>
                            <input type="hidden" name="id" id="id" value="{{ $relation ? $relation->id : '' }}"/>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="company_name">Bedrijfsnaam*</label>
                                        <input name="company_name" maxlength="50" id="company_name" type="text" value="{{ Input::old('company_name') ? Input::old('company_name') : ($relation ? $relation->company_name : '') }}" class="form-control" />
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="company_type">Bedrijfstype*</label>
                                        <select name="company_type" id="company_type" class="form-control pointer">
                                            @foreach (RelationType::all() as $type)
                                            <option {{ $relation ? ($relation->type_id==$type->id ? 'selected' : '') : (old('company_type') == $type->id ? 'selected' : '') }} value="{{ $type->id }}">{{ ucwords($type->type_name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="website">Website</label>
                                        <input name="website" maxlength="180" id="website" type="url" value="{{ Input::old('website') ? Input::old('website') : ($relation ? $relation->website : '') }}" class="form-control"/>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="kvk">K.v.K nummer</label>&nbsp;<a data-toggle="tooltip" data-placement="bottom" data-original-title="Je KVK-nummer dient te bestaan uit 8 cijfers" href="javascript:void(0);"><i class="fa fa-info-circle"></i></a>
                                        <input name="kvk" id="kvk" type="text" maxlength="8" minlength="8" value="{{ Input::old('kvk') ? Input::old('kvk') : ($relation ? $relation->kvk : '') }}" class="form-control"/>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="btw">BTW nummer</label>&nbsp;<a data-toggle="tooltip" data-placement="bottom" data-original-title="Je BTW-nummer bestaat uit een combinatie van 14 cijfers en/of letters. Veelal beginnen nederlandse BTW-nummers met 'NL' en eindigen op 'B01'." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a>
                                        <input name="btw" id="btw" type="text" maxlength="14" minlength="14" value="{{ Input::old('btw') ? Input::old('btw') : ($relation ? $relation->btw : '') }}" class="form-control"/>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="telephone_comp">Telefoonnummer</label>
                                        <input name="telephone_comp" id="telephone_comp" type="text" maxlength="12" value="{{ Input::old('telephone_comp') ? Input::old('telephone_comp') : ($relation ? $relation->phone : '') }}" class="form-control"/>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="email_comp">Email</label>
                                        <input name="email_comp" maxlength="80" id="email_comp" type="email" value="{{ Input::old('email_comp') ? Input::old('email_comp') : ($relation ? $relation->email : '') }}" class="form-control"/>
                                    </div>
                                </div>
                            </div>

                            <h4>Adresgegevens</h4>
                            <div class="row">
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="address_number">Huis nr.*</label>
                                        <input name="address_number" maxlength="5" id="address_number" type="text" value="{{ Input::old('address_number') ? Input::old('address_number') : ($relation ? $relation->address_number : '') }}" class="form-control autoappend"/>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="zipcode">Postcode*</label>
                                        <input name="zipcode" id="zipcode" maxlength="6" type="text" value="{{ Input::old('zipcode') ? Input::old('zipcode') : ($relation ? $relation->address_postal : '') }}" class="form-control autoappend"/>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="street">Straat*</label>
                                        <input name="street" maxlength="50" id="street" type="text" value="{{ Input::old('street') ? Input::old('street') : ($relation ? $relation->address_street : '') }}" class="form-control"/>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="city">Plaats*</label>
                                        <input name="city" maxlength="35" id="city" type="text" value="{{ Input::old('city') ? Input::old('city') : ($relation ? $relation->address_city : '') }}" class="form-control"/>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="province">Provincie*</label>
                                        <select name="province" id="province" class="form-control pointer">
                                            @foreach (Province::all() as $province)
                                                <option {{ $relation ? ($relation->province_id==$province->id ? 'selected' : '') : (old('province') == $province->id ? 'selected' : '') }} value="{{ $province->id }}">{{ ucwords($province->province_name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="country">Land*</label>
                                        <select name="country" id="country" class="form-control pointer">
                                            @foreach (Country::all() as $country)
                                                <option {{ $relation ? ($relation->country_id==$country->id ? 'selected' : '') : ($country->country_name=='nederland' ? 'selected' : '')}} value="{{ $country->id }}">{{ ucwords($country->country_name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-primary"><i class="fa fa-check"></i> Opslaan</button>
                                </div>
                            </div>
                        </form>
                        </div>
                        <div id="contact" class="tab-pane">
                            <h4>Contactpersonen</h4>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="col-md-2">Achternaam</th>
                                        <th class="col-md-2">Voornaam</th>
                                        <th class="col-md-2">Functie</th>
                                        <th class="col-md-2">Telefoon</th>
                                        <th class="col-md-2">Mobiel</th>
                                        <th class="col-md-2">Email</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php if ($relation) { ?>
                                    @foreach (Contact::where('relation_id','=', $relation->id)->get() as $contact)
                                    <tr>
                                        <td class="col-md-2"><a href="/relation-{{ $relation->id }}/contact-{{ $contact->id }}/edit">{{ $contact->lastname }}</a></td>
                                        <td class="col-md-2">{{ $contact->firstname }}</td>
                                        <td class="col-md-2">{{ ContactFunction::find($contact->function_id)->function_name }}</td>
                                        <td class="col-md-2">{{ $contact->phone }}</td>
                                        <td class="col-md-2">{{ $contact->mobile }}</td>
                                        <td class="col-md-2"><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></td>
                                    </tr>
                                    @endforeach
                                    <?php } ?>
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-md-2">
                                    <a href="/mycompany/contact/new" {{ $relation ? '' : 'disabled' }} class="btn btn-primary"><i class="fa fa-pencil"></i> Nieuw contact</a>
                                </div>
                            </div>
                        </div>
                        <div id="payment" class="tab-pane">
                            <h4>Betalingsgegevens</h4>
                            <form action="mycompany/iban/update" method="post">
                            {!! csrf_field() !!}
                            <div class="row">
                            <input type="hidden" name="id" id="id" value="{{ $relation ? $relation->id : '' }}"/>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="iban">IBAN rekeningnummer</label>
                                        <input name="iban" id="iban" maxlength="25" type="text" value="{{ Input::old('iban') ? Input::old('iban') : ($relation ? $relation->iban : '') }}" {{ $relation ? '' : 'disabled' }} class="form-control"/>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="btw">Naam rekeninghouder</label>
                                        <input name="iban_name" maxlength="50" id="iban_name" type="text" {{ $relation ? '' : 'disabled' }} value="{{ Input::old('iban_name') ? Input::old('iban_name') : ($relation ? $relation->iban_name : '') }}" class="form-control"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-primary {{ $relation ? '' : 'disabled' }}"><i class="fa fa-check"></i> Opslaan</button>
                                </div>
                            </div>
                            </form>
                        </div>
                        <div id="logo" class="tab-pane">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Logo</h4>
                                    <form action="relation/logo/save" method="post" enctype="multipart/form-data">
                                    {!! csrf_field() !!}
                                    <input type="hidden" name="id" id="id" value="{{ $relation ? $relation->id : '' }}"/>

                                    @if ($relation && $relation->logo_id)
                                    <div>
                                    <h5>Huidige logo</h5><img src="/res-{{ $relation->logo_id }}/view"/></div>
                                    @endif

                                    <br />
                                    <div class="form-group">
                                        <label for="image">Afbeelding Uploaden</label>
                                        <div class="input-group col-md-4">
                                            <span class="input-group-btn">
                                                <span class="btn btn-primary btn-file {{ $relation ? '' : 'disabled' }}">
                                                    <i class="fa fa-file-o" aria-hidden="true"></i> Bestand&hellip; <input {{ $relation ? '' : 'disabled' }} name="image" type="file" multiple>
                                                </span>
                                            </span>
                                            <input type="text" class="form-control" readonly>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <button class="btn btn-primary {{ $relation ? '' : 'disabled' }}"><i class="fa fa-check"></i> Opslaan</button>
                                        </div>
                                    </div>

                                    </form>
                                </div>
                                <div class="col-md-6">
                                    <h4>Voorwaarden</h4>
                                    <form action="relation/agreement/save" method="post" enctype="multipart/form-data">
                                    {!! csrf_field() !!}
                                    <input type="hidden" name="id" id="id" value="{{ $relation ? $relation->id : '' }}"/>

                                    @if ($relation && $relation->agreement_id)
                                    <div class="item">
                                        <span class="cart_img" style="width:45px;"><a href="/res-{{ $relation->agreement_id }}/download"><i class="fa fa-file-pdf-o fsize60"></i></a></span>
                                        <a href="/res-{{ $relation->agreement_id }}/download" class="product_name">{{ Resource::find($relation->agreement_id)->resource_name }}</a>
                                    </div>
                                    @endif

                                    <br />
                                    <div class="form-group">
                                        <label for="image">PDF Uploaden</label>
                                        <div class="input-group col-md-4">
                                            <span class="input-group-btn">
                                                <span class="btn btn-primary btn-file {{ $relation ? '' : 'disabled' }}">
                                                    <i class="fa fa-file-o" aria-hidden="true"></i> Bestand&hellip; <input {{ $relation ? '' : 'disabled' }} name="doc" type="file" multiple>
                                                </span>
                                            </span>
                                            <input type="text" class="form-control" readonly>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <button class="btn btn-primary {{ $relation ? '' : 'disabled' }}"><i class="fa fa-check"></i> Opslaan</button>
                                        </div>
                                    </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div id="prefs" class="tab-pane">

                            <form method="POST" action="/account/preferences/update" accept-charset="UTF-8">
                            {!! csrf_field() !!}

                            <h4 class="company">Voorkeuren</h4>

                            <div class="panel-group" id="accordion">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#acordion1">
                                                <i class="fa fa-check"></i>
                                                Uurtarief en Winstpercentages
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="acordion1" class="collapse in">
                                        <div class="panel-body">

                                            <div class="row">
                                                <div class="col-md-3"><h5><strong>Eigen uurtarief*</strong></h5></div>
                                                <div class="col-md-1"></div>
                                                <div class="col-md-2"><h5><strong>Calculatie</strong></h5></div>
                                                <div class="col-md-2"><h5><strong>Meerwerk</strong></h5></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3"><label for="hour_rate">Uurtarief excl. BTW</label></div>
                                                <div class="col-md-1"><div class="pull-right">&euro;</div></div>
                                                <div class="col-md-2">
                                                    <input name="pref_hourrate_calc" id="pref_hourrate_calc" type="text" class="form-control form-control-sm-number" value="{{ number_format($user->pref_hourrate_calc, 2, ",",".") }}" />
                                                </div>
                                                <div class="col-md-2">
                                                    <input name="pref_hourrate_more" id="pref_hourrate_more" type="text" class="form-control form-control-sm-number" value="{{ number_format($user->pref_hourrate_more, 2, ",",".") }}" />
                                                </div>
                                            </div>

                                            <h5><strong>Aanneming</strong></h5>
                                            <div class="row">
                                                <div class="col-md-3"><label for="profit_material_1">Winstpercentage materiaal</label></div>
                                                <div class="col-md-1"><div class="pull-right">%</div></div>
                                                <div class="col-md-2">
                                                        <input name="pref_profit_calc_contr_mat" type="number" min="0" max="200" id="pref_profit_calc_contr_mat" type="text" class="form-control form-control-sm-number" value="{{ $user->pref_profit_calc_contr_mat }}" />
                                                </div>
                                                <div class="col-md-2">
                                                        <input name="pref_profit_more_contr_mat" type="number" min="0" max="200" id="pref_profit_more_contr_mat" type="text" class="form-control form-control-sm-number" value="{{ $user->pref_profit_more_contr_mat }}" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3"><label for="profit_equipment_1">Winstpercentage overig</label></div>
                                                <div class="col-md-1"><div class="pull-right">%</div></div>
                                                <div class="col-md-2">
                                                        <input name="pref_profit_calc_contr_equip" type="number" min="0" max="200" id="pref_profit_calc_contr_equip" type="text" class="form-control form-control-sm-number" value="{{ $user->pref_profit_calc_contr_equip }}" />
                                                </div>
                                                <div class="col-md-2">
                                                        <input name="pref_profit_more_contr_equip" type="number" min="0" max="200" id="pref_profit_more_contr_equip" type="text" class="form-control form-control-sm-number" value="{{ $user->pref_profit_more_contr_equip }}" />
                                                </div>
                                            </div>

                                            <h5><strong>Onderaanneming</strong></h5>
                                            <div class="row">
                                                <div class="col-md-3"><label for="profit_material_2">Winstpercentage materiaal</label></div>
                                                <div class="col-md-1"><div class="pull-right">%</div></div>
                                                <div class="col-md-2">
                                                        <input name="pref_profit_calc_subcontr_mat" type="number" min="0" max="200" id="pref_profit_calc_subcontr_mat" type="text" class="form-control form-control-sm-number" value="{{ $user->pref_profit_calc_subcontr_mat }}" />
                                                </div>
                                                <div class="col-md-2">
                                                        <input name="pref_profit_more_subcontr_mat" type="number" min="0" max="200" id="pref_profit_more_subcontr_mat" type="text" class="form-control form-control-sm-number" value="{{ $user->pref_profit_more_subcontr_mat }}" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3"><label for="profit_equipment_2">Winstpercentage overig</label></div>
                                                <div class="col-md-1"><div class="pull-right">%</div></div>
                                                <div class="col-md-2">
                                                        <input name="pref_profit_calc_subcontr_equip" type="number" min="0" max="200" id="pref_profit_calc_subcontr_equip" type="text" class="form-control form-control-sm-number" value="{{ $user->pref_profit_calc_subcontr_equip }}" />
                                                </div>
                                                <div class="col-md-2">
                                                        <input name="pref_profit_more_subcontr_equip" type="number" min="0" max="200" id="pref_profit_more_subcontr_equip" type="text" class="form-control form-control-sm-number" value="{{ $user->pref_profit_more_subcontr_equip }}" />
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#acordion2">
                                                <i class="fa fa-check"></i>
                                                Omschrijvingen voor op offerte en factuur
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="acordion2" class="collapse">
                                        <div class="panel-body">
                                            <h4>Offerte</h4>
                                            <h5><strong>Omschrijving voor op de offerte</strong></h5>
                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <textarea name="pref_offer_description" id="pref_offer_description" rows="5" class="form-control">{{ $user->pref_offer_description }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <h5><strong>Aanvullende bepalingen voor op de offerte</strong></h5>
                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <textarea name="pref_extracondition_offer" id="pref_extracondition_offer" rows="5" class="form-control">{{ $user->pref_extracondition_offer }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <h5><strong>Sluitingstekst voor op de offerte</strong></h5>
                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <textarea name="pref_closure_offer" id="pref_closure_offer" rows="5" class="form-control">{{ $user->pref_closure_offer }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <h4>Factuur</h4>
                                            <h5><strong>Omschrijving voor op de factuur</strong></h5>
                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <textarea name="pref_invoice_description" id="pref_invoice_description" rows="5" class="form-control">{{ $user->pref_invoice_description }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <h5><strong>Sluitingstekst voor op de factuur</strong></h5>
                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <textarea name="pref_invoice_closure" id="pref_invoice_closure" rows="5" class="form-control">{{ $user->pref_invoice_closure }}</textarea>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#acordion3">
                                                <i class="fa fa-check"></i>
                                                Omschrijvingen voor in de emails
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="acordion3" class="collapse">
                                        <div class="panel-body">

                                            <h4>Offerte</h4>
                                            <h5><strong>Beschrijving voor in de email bij verzending van de offerte</strong></h5>
                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <textarea name="pref_email_offer" id="pref_email_offer" rows="5" class="form-control">{{ $user->pref_email_offer }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <h4>Factuur</h4>
                                            <h5><strong>Beschrijving voor in de email bij verzending van de factuur</strong></h5>
                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <textarea name="pref_email_invoice" id="pref_email_invoice" rows="5" class="form-control">{{ $user->pref_email_invoice }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            @if (0)
                                            <h5><strong>1e betalingsherinnering van de factuur (direct na verstrijken van de ingestelde betalingsconditie van de factuur)</strong></h5>
                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <textarea name="pref_email_invoice_first_reminder" id="pref_email_invoice_first_reminder" rows="5" class="form-control">{{ $user->pref_email_invoice_first_reminder }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <h5><strong>Laatste betalingsherinnering van de factuur (14 dagen na de 1e betalingsherinnering)</strong></h5>
                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <textarea name="pref_email_invoice_demand" id="pref_email_invoice_last_reminder" rows="5" class="form-control">{{ $user->pref_email_invoice_last_reminder }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <h5><strong>Vorderingswaaeschuwing van de factuur (7 dagen na de laatste (2e) betalingsherinnering)</strong></h5>
                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <textarea name="pref_email_invoice_demand" id="pref_email_invoice_demand" rows="5" class="form-control">{{ $user->pref_email_invoice_demand }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#acordion4">
                                                <i class="fa fa-check"></i>
                                                Offerte en factuurnummering
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="acordion4" class="collapse">
                                        <div class="panel-body">

                                            <h4>Offerte</h5>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="offernumber_prefix"><strong>Tekst voor offertenummer</strong></label>
                                                        <input name="offernumber_prefix" id="offernumber_prefix" maxlength="10" type="text" class="form-control" value="{{ $user->offernumber_prefix }}" />
                                                    </div>
                                                </div>
                                            </div>

                                            <h4>Factuur</h5>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="pref_use_ct_numbering" style="display:block;"><strong>Gebruik CalculatieTool nummering voor factuurnummer</strong></label>
                                                        <input name="pref_use_ct_numbering" type="checkbox" {{ $user->pref_use_ct_numbering ? 'checked' : '' }}>
                                                    </div>
                                                </div>
                                                <div class="col-md-6" >
                                                    <div class="form-group">
                                                        <label for="invoicenumber_prefix"><strong>Tekst voor factuurnummer</strong></label>
                                                        <input name="invoicenumber_prefix" id="invoicenumber_prefix" type="text" maxlength="10" class="form-control" value="{{ $user->invoicenumber_prefix }}" />
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-primary"><i class="fa fa-check"></i> Opslaan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

        </div>

    </section>

</div>
<script type="text/javascript">
$(document).ready(function() {
    <?php $response = RelationKind::where('id','=',Input::old('relationkind'))->first(); ?>
    if('{{ ($response ? $response->kind_name : 'zakelijk') }}'=='particulier'){
        $('.company').hide();
        $('#relationkind option[value="{{ Input::old('relationkind') }}"]').attr("selected",true);
    }
    $('#relationkind').change(function() {
        $('.company').toggle('slow');
    });
});
</script>
@stop
