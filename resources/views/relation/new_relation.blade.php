<?php

use \BynqIO\Dynq\Models\RelationKind;
use \BynqIO\Dynq\Models\RelationType;

?>
@inject('province', 'BynqIO\Dynq\Models\Province')
@inject('country', 'BynqIO\Dynq\Models\Country')
@inject('contact_function', 'BynqIO\Dynq\Models\ContactFunction')

@extends('layout.master')

@section('title', 'Nieuwe relatie')

@push('scripts')
<script src="/plugins/summernote/summernote.min.js"></script>
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

    $('#website').blur(function(e){
        prefixURL($(this));
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

    $('#telephone_com').blur(function() {
        var telcompcheck = $(this).val();
        if (telcompcheck.length != 12) {
            $(this).addClass("error-input");
        }else {
            $(this).removeClass("error-input");
        }
    });

    $('#relationkind').change(function(e) {
        if ($(this).val() == 2)
            $('.company').hide('slow');
        else
            $('.company').show('slow');
    });

    @if (old('relationkind') && old('relationkind') == 2)
    $('.company').hide();
    @endif

    $('#street').blur(function() {
        var streetcheck = $(this).val();
        var regx = /^[A-Za-z0-9\s]*$/;
        if( streetcheck != "" && regx.test(streetcheck)) {
            $(this).removeClass("error-input");
        }else {
            $(this).addClass("error-input");
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

            $.post("/api/v1/postaladdress", {
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

<div id="wrapper">

    <section class="container">

        <div class="col-md-12">

            <div>
                <ol class="breadcrumb">
                  <li><a href="/">Dashboard</a></li>
                  <li><a href="/relation">Relaties</a></li>
                  <li>Nieuwe Relatie</li>
                </ol>
            <div>

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

            <h2 style="margin: 10px 0 20px 0;"><strong>Nieuwe</strong> relatie</h2>

            <div class="white-row">
                <form method="POST" action="/relation/new{{ Input::get('redirect') ? '?redirect='.Input::get('redirect') : '' }}" accept-charset="UTF-8">
                {!! csrf_field() !!}
                <div class="row">

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="relationkind">Relatiesoort <a style="text-decoration:none;cursor:default;">*</a></label>
                            <select name="relationkind" id="relationkind" class="form-control pointer">
                                @foreach (RelationKind::all() as $kind)
                                <option {{ old('relationkind') && old('relationkind') == $kind->id ? 'selected' : '' }} value="{{ $kind->id }}">{{ ucwords($kind->kind_name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="debtor">Debiteurennummer <a style="text-decoration:none;cursor:default;">*</a></label>&nbsp;<a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit nummer is gegenereerd door de CalculatieTool.com. Je kunt dit vervangen door je eigen boekhoudkundige nummering." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a>
                            <input name="debtor" maxlength="10" id="debtor" type="text" value="{{ old('debtor') ? old('debtor') : $debtor_code }}" class="form-control"/>
                        </div>
                    </div>

                </div>

                <h4 class="company">Bedrijfsgegevens</h4>
                <div class="row company">

                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="company_name">Bedrijfsnaam <a style="text-decoration:none;cursor:default;">*</a></label>
                            <input name="company_name" maxlength="50" id="company_name" type="text" value="{{ old('company_name') }}" class="form-control" />
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="company_type">Bedrijfstype <a style="text-decoration:none;cursor:default;">*</a></label>
                            <select name="company_type" id="company_type" class="form-control pointer">
                                @foreach (RelationType::all() as $type)
                                <option {{ (old('company_type') == $type->id ? 'selected' : '') }} value="{{ $type->id }}">{{ ucwords($type->type_name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="website">Website</label>
                            <input name="website" maxlength="180" id="website" type="url" value="{{ old('website') }}" class="form-control"/>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="kvk">K.v.K nummer</label>&nbsp;<a data-toggle="tooltip" data-placement="bottom" data-original-title="Je KVK-nummer dient te bestaan uit 8 cijfers" href="javascript:void(0);"><i class="fa fa-info-circle"></i></a>
                            <input name="kvk" id="kvk" type="text" maxlength="8" minlength="8" value="{{ old('kvk') }}" class="form-control"/>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="btw">BTW nummer</label>&nbsp;<a data-toggle="tooltip" data-placement="bottom" data-original-title="Je BTW-nummer bestaat uit een combinatie van 12 cijfers en/of letters. Veelal beginnen nederlandse BTW-nummers met 'NL' en eindigen op 'B01'." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a>
                            <input name="btw" id="btw" type="text" maxlength="14" value="{{ old('btw') }}" class="form-control"/>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="telephone_comp">Telefoonnummer</label>
                            <input name="telephone_comp" id="telephone_comp" type="text" minlength="12" maxlength="12" value="{{ old('telephone_comp') }}" class="form-control"/>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="email_comp">Email <a style="text-decoration:none;cursor:default;">*</a></label>
                            <input name="email_comp" maxlength="80" id="email_comp" type="email" value="{{ old('email_comp') }}" class="form-control"/>
                        </div>
                    </div>

                </div>

                <h4>Adresgegevens</h4>
                <div class="row">

                    <div class="col-md-1">
                        <div class="form-group">
                            <label for="address_number">Huis nr. <a style="text-decoration:none;cursor:default;">*</a></label>
                            <input name="address_number" maxlength="5" id="address_number" type="text" value="{{ old('address_number') }}" class="form-control autoappend"/>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="zipcode">Postcode <a style="text-decoration:none;cursor:default;">*</a></label>
                            <input name="zipcode" id="zipcode" maxlength="6" type="text" value="{{ old('zipcode') }}" class="form-control autoappend"/>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="street">Straat <a style="text-decoration:none;cursor:default;">*</a></label>
                            <input name="street" id="street" maxlength="50" type="text" value="{{ old('street') }}" class="form-control"/>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="city">Plaats <a style="text-decoration:none;cursor:default;">*</a></label>
                            <input name="city" id="city" maxlength="35" type="text" value="{{ old('city') }}" class="form-control"/>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="province">Provincie <a style="text-decoration:none;cursor:default;">*</a></label>
                            <select name="province" id="province" class="form-control pointer">
                                @foreach ($province::all() as $province)
                                <option  {{ (old('province') ? (old('province') == $province->id ? 'selected' : '') : $province->province_name=='overig'  ? 'selected' : '') }} value="{{ $province->id }}">{{ ucwords($province->province_name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="country">Land <a style="text-decoration:none;cursor:default;">*</a></label>
                            <select name="country" id="country" class="form-control pointer">
                                @foreach ($country::all() as $country)
                                <option {{ (old('country') ? (old('country') == $country->id ? 'selected' : '') : $country->country_name=='nederland' ? 'selected' : '') }} value="{{ $country->id }}">{{ ucwords($country->country_name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <h4>Contactpersoon</h4>
                <div class="row">

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="contact_salutation">Titel</label>
                            <input name="contact_salutation" maxlength="16" id="contact_salutation" type="text" value="{{ old('contact_salutation') }}" class="form-control"/>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="contact_name">Achternaam <a style="text-decoration:none;cursor:default;">*</a></label>
                            <input name="contact_name" maxlength="50" id="contact_name" type="text" value="{{ old('contact_name') }}" class="form-control"/>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="contact_firstname">Voornaam</label>
                            <input name="contact_firstname" maxlength="30" id="contact_firstname" type="text" value="{{ old('contact_firstname') }}" class="form-control"/>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="mobile">Mobiel</label>
                            <input name="mobile" id="mobile" type="text" maxlength="12" value="{{ old('mobile') }}" class="form-control"/>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="telephone">Telefoonnummer</label>
                            <input name="telephone" id="telephone" type="text" maxlength="12" value="{{ old('telephone') }}" class="form-control"/>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="email">Email <a style="text-decoration:none;cursor:default;">*</a></label>
                            <input name="email" id="email" maxlength="80" type="email" value="{{ old('email') }}" class="form-control"/>
                        </div>
                    </div>

                    <div class="col-md-4 company">
                        <div class="form-group">
                            <label for="contactfunction">Functie <a style="text-decoration:none;cursor:default;">*</a></label>
                            <select name="contactfunction" id="contactfunction" class="form-control pointer">
                                @foreach ($contact_function::all() as $function)
                                <option {{ (old('contactfunction') ? (old('contactfunction') == $function->id ? 'selected' : '') : $function->function_name=='directeur' ? 'selected' : '') }} value="{{ $function->id }}">{{ ucwords($function->function_name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="gender" style="display:block;">Geslacht</label>
                            <select name="gender" id="gender" class="form-control pointer">
                                <option value="-1">Selecteer</option>
                                <option {{ (old('gender') == 'M' ? 'selected' : '') }} value="M">Man</option>
                                <option {{ (old('gender') == 'V' ? 'selected' : '') }} value="V">Vrouw</option>
                            </select>
                        </div>
                    </div>

                </div>

                <h4>Opmerkingen</h4>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-12">
                            <textarea name="note" id="summernote" rows="10" class="form-control">{{ old('note') }}</textarea>
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

    </section>

</div>
@stop
