<?php

use \BynqIO\CalculatieTool\Models\Project;
use \BynqIO\CalculatieTool\Models\User;
use \BynqIO\CalculatieTool\Models\Relation;
use \BynqIO\CalculatieTool\Models\Contact;
use \BynqIO\CalculatieTool\Models\ContactFunction;
use \BynqIO\CalculatieTool\Models\RelationKind;
use \BynqIO\CalculatieTool\Models\RelationType;
use \BynqIO\CalculatieTool\Models\Province;
use \BynqIO\CalculatieTool\Models\Country;
use \BynqIO\CalculatieTool\Models\Invoice;
use \BynqIO\CalculatieTool\Models\Offer;

$common_access_error = false;
$relation = Relation::find(Route::Input('relation_id'));
if (!$relation || !$relation->isOwner() || !$relation->isActive()) {
    $common_access_error = true;
} else {
    $contact = Contact::where('relation_id','=',$relation->id)->first();
}
?>

@extends('layout.master')

@section('title', 'Relatiedetails')

@push('scripts')
<script src="/plugins/summernote/summernote.min.js"></script>
<script type="text/javascript" src="/js/iban.js"></script>
@endpush

<?php if($common_access_error){ ?>
@section('content')
<div id="wrapper">
    <section class="container">
        <div class="alert alert-danger">
            <i class="fa fa-frown-o"></i>
            <strong>Fout</strong>
            Deze relatie bestaat niet
        </div>
    </section>
</div>
@stop
<?php }else{ ?>

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
        sessionStorage.toggleTabRel{{Auth::id()}} = 'company';
    });
    $('#tab-payment').click(function(e){
        sessionStorage.toggleTabRel{{Auth::id()}} = 'payment';
    });
    $('#tab-contact').click(function(e){
        sessionStorage.toggleTabRel{{Auth::id()}} = 'contact';
    });
    $('#tab-calc').click(function(e){
        sessionStorage.toggleTabRel{{Auth::id()}} = 'calc';
    });
    $('#tab-invoices').click(function(e){
        sessionStorage.toggleTabRel{{Auth::id()}} = 'invoices';
    });
    if (sessionStorage.toggleTabRel{{Auth::id()}}){
        $toggleOpenTab = sessionStorage.toggleTabRel{{Auth::id()}};
        $('#tab-'+$toggleOpenTab).addClass('active');
        $('#'+$toggleOpenTab).addClass('active');
    } else {
        sessionStorage.toggleTabRel{{Auth::id()}} = 'company';
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

    $('#btw').blur(function() {
        var btwcheck = $(this).val().trim();
        if (btwcheck.length != 14) {
            $(this).addClass("error-input");
        }else {
            $(this).removeClass("error-input");
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

    $('#acc-deactive').on('click', function(e){
        if (confirm('Relatie verwijderen?')){
            window.location.href = '/relation-{{ $relation->id }}/delete';
        }
    });

    $('#summernote').summernote({
        height: $(this).attr("data-height") || 200,
        toolbar: [
            ["style", ["bold", "italic", "underline", "strikethrough", "clear"]],
            ["para", ["ul", "ol", "paragraph"]],
            ["table", ["table"]],
            ["media", ["link", "picture", "video"]],
        ]
    })
          
});
</script>

<div id="wrapper">

    <section class="container">

        <div class="col-md-12">

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

            <div>
            <ol class="breadcrumb">
              <li><a href="/">Dashboard</a></li>
              <li><a href="/relation">Relaties</a></li>
             <li>{{ $relation->company_name ? $relation->company_name : $contact->firstname . ' ' . $contact->lastname }}</li>
            </ol>
            <div>
            <br>

            <h2><strong>Relatie</strong> {{ $relation->company_name ? $relation->company_name : $contact->firstname . ' ' . $contact->lastname }}</h2>

                <div class="tabs nomargin-top">

                    <ul class="nav nav-tabs">
                        <li id="tab-company">
                            <a href="#company" data-toggle="tab"><i class="fa fa-info"></i> {{ ucfirst( RelationKind::find($relation->kind_id)->kind_name) }}e gegevens</a>
                        </li>
                        <li id="tab-contact">
                            <a href="#contact" data-toggle="tab"><i class="fa fa-users"></i> Contacten</a>
                        </li>
                        <li id="tab-payment">
                            <a href="#payment" data-toggle="tab"><i class="fa fa-university"></i> Betalingsgegevens</a>
                        </li>
                        <li id="tab-calc">
                            <a href="#calc" data-toggle="tab"><i class="fa fa-sliders"></i> Tarieven</a>
                        </li>
                        <li id="tab-invoices">
                            <a href="#invoices" data-toggle="tab"><i class="fa fa-file-pdf-o"></i> Facturen</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div id="company" class="tab-pane">

                            <div class="pull-right">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Acties&nbsp;&nbsp;<span class="caret"></span></button>
                                <ul class="dropdown-menu">
                                    <li><a href="/project/new">Nieuw project</a></i>
                                    @if (RelationKind::find($relation->kind_id)->kind_name == 'zakelijk')
                                    <li><a href="/relation-{{ $relation->id }}/convert">Omzetten naar particulier</a></i>
                                    @else
                                    <li><a href="/relation-{{ $relation->id }}/convert">Omzetten naar zakelijk</a></i>
                                    @endif
                                    <li><a href="#" id="acc-deactive">Verwijderen</a></i>
                                </ul>
                            </div>
                            </div>

                            <form method="POST" action="/relation/update" accept-charset="UTF-8">
                            {!! csrf_field() !!}
                            <h4>{{ ucfirst(RelationKind::find($relation->kind_id)->kind_name) }}e relatie</h4>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="debtor">Debiteurennummer</label>&nbsp;<a data-toggle="tooltip" data-placement="bottom" data-original-title="Dit nummer is gegenereerd door de CalculatieTool.com. Je kunt dit vervangen door je eigen boekhoudkundige nummering." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a>
                                        <input name="debtor" maxlength="10" id="debtor" type="text" value="{{ old('debtor') ? old('debtor') : $relation->debtor_code }}" class="form-control"/>
                                        <input type="hidden" name="id" id="id" value="{{ $relation->id }}"/>
                                    </div>
                                </div>

                            </div>

                            @if (RelationKind::find($relation->kind_id)->kind_name == 'zakelijk')
                            <h4 class="company">Bedrijfsgegevens</h4>
                            <div class="row company">

                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="company_name">Bedrijfsnaam*</label>
                                        <input name="company_name" maxlength="50" id="company_name" type="text" value="{{ old('company_name') ? old('company_name') : $relation->company_name }}" class="form-control" />
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="company_type">Bedrijfstype*</label>
                                        <select name="company_type" id="company_type" class="form-control pointer">
                                        @foreach (RelationType::all() as $type)
                                            <option {{ $relation->type_id==$type->id ? 'selected' : '' }} value="{{ $type->id }}">{{ ucwords($type->type_name) }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="website">Website</label>
                                        <input name="website" maxlength="180" id="website" type="url" value="{{ old('website') ? old('website') : $relation->website }}" class="form-control"/>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="kvk">K.v.K nummer</label>&nbsp;<a data-toggle="tooltip" data-placement="bottom" data-original-title="Je KVK-nummer dient te bestaan uit 8 cijfers" href="javascript:void(0);"><i class="fa fa-info-circle"></i></a>
                                        <input name="kvk" id="kvk" type="text" maxlength="8" minlength="8" value="{{ old('kvk') ? old('kvk') : trim($relation->kvk) }}" class="form-control"/>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="btw">BTW nummer</label>&nbsp;<a data-toggle="tooltip" data-placement="bottom" data-original-title="Je BTW-nummer bestaat uit een combinatie van 12 cijfers en/of letters. Veelal beginnen nederlandse BTW-nummers met 'NL' en eindigen op 'B01'." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a>
                                        <input name="btw" id="btw" type="text" maxlength="14" value="{{ old('btw') ? old('btw') : $relation->btw }}" class="form-control"/>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="telephone_comp">Telefoonnummer</label>
                                        <input name="telephone_comp" id="telephone_comp" type="text" maxlength="12" value="{{ old('telephone_comp') ? old('telephone_comp') : $relation->phone }}" class="form-control"/>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="email_comp">Email*</label>
                                        <input name="email_comp" maxlength="80" id="email_comp" type="email" value="{{ old('email_comp') ? old('email_comp') : $relation->email }}" class="form-control"/>
                                    </div>
                                </div>

                            </div>
                            @endif

                            <h4>Adresgegevens</h4>
                            <div class="row">

                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label for="address_number">Huis nr.*</label>
                                        <input name="address_number" maxlength="5" id="address_number" type="text" value="{{ old('address_number') ? old('address_number') : $relation->address_number }}" class="form-control autoappend"/>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="zipcode">Postcode*</label>
                                        <input name="zipcode" id="zipcode" maxlength="6" type="text" value="{{ old('zipcode') ? old('zipcode') : $relation->address_postal }}" class="form-control autoappend"/>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="street">Straat*</label>
                                        <input name="street" maxlength="50" id="street" type="text" value="{{ old('street') ? old('street') : $relation->address_street }}" class="form-control"/>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="city">Plaats*</label>
                                        <input name="city" maxlength="35" id="city" type="text" value="{{ old('city') ? old('city') : $relation->address_city }}" class="form-control"/>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="province">Provincie*</label>
                                        <select name="province" id="province" class="form-control pointer">
                                            @foreach (Province::all() as $province)
                                                <option {{ $relation->province_id==$province->id ? 'selected' : '' }} value="{{ $province->id }}">{{ ucwords($province->province_name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="country">Land*</label>
                                        <select name="country" id="country" class="form-control pointer">
                                            @foreach (Country::all() as $country)
                                                <option {{ $relation->country_id==$country->id ? 'selected' : '' }} value="{{ $country->id }}">{{ ucwords($country->country_name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>

                            <h4>Opmerkingen</h4>
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <textarea name="note" id="summernote" rows="10" class="form-control">{{ old('note') ? old('note') : $relation->note }}</textarea>
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
                            <h4>Contactpersonen {{ $relation->company_name ? $relation->company_name : $contact->firstname . ' ' . $contact->lastname }}</h4>
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
                                    @foreach (Contact::where('relation_id','=', $relation->id)->get() as $contact)
                                    <tr>
                                        <td class="col-md-2"><a href="/relation-{{ $relation->id }}/contact-{{ $contact->id }}/edit">{{ $contact->lastname }}</a></td>
                                        <td class="col-md-2">{{ $contact->firstname }}</a></td>
                                        <td class="col-md-2">{{ ucfirst(ContactFunction::find($contact->function_id)->function_name) }}</td>
                                        <td class="col-md-2">{{ $contact->phone }}</td>
                                        <td class="col-md-2">{{ $contact->mobile }}</td>
                                        <td class="col-md-2"><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-md-12">
                                    <a href="/relation-{{ $relation->id }}/contact/new" class="btn btn-primary"><i class="fa fa-pencil"></i> Nieuw contact</a>
                                </div>
                            </div>
                        </div>
                        <div id="payment" class="tab-pane">
                            <h4>Betalingsgegevens {{ $relation->company_name ? $relation->company_name : $contact->firstname . ' ' . $contact->lastname }}</h4>
                            <form method="POST" action="/relation/iban/update" accept-charset="UTF-8">
                            {!! csrf_field() !!}
                            <input type="hidden" name="id" id="id" value="{{ $relation->id }}"/>
                            <div class="row">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="iban">IBAN rekeningnummer</label>
                                        <input name="iban" maxlength="25" id="iban" type="text" value="{{ old('iban') ? old('iban') : $relation->iban }}" class="form-control"/>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="btw">Naam rekeninghouder</label>
                                        <input name="iban_name" maxlength="50" id="iban_name" type="text" value="{{ old('iban_name') ? old('iban_name') : $relation->iban_name }}" class="form-control"/>
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

                        <div id="calc" class="tab-pane">
                            <h4>Voorkeurstarief</h4>
                            <form method="post" action="/relation/updatecalc">
                                {!! csrf_field() !!}
                                <input type="hidden" name="id" id="id" value="{{ $relation->id }}"/>
                                <div class="row">
                                    <div class="col-md-3"><h5><strong>Eigen uurtarief <a data-toggle="tooltip" data-placement="bottom" data-original-title="Geef hier uw uurtarief op wat door heel de calculatie gebruikt wordt voor dit project. Of stel deze in bij Voorkeuren om bij elk project te kunnen gebruiken." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></strong></h5></div>
                                    <div class="col-md-1"></div>
                                    <div class="col-md-2"><h5><strong>Calculatie</strong></h5></div>
                                    <div class="col-md-2"><h5><strong>Meerwerk</strong></h5></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3"><label for="hour_rate">Uurtarief excl. BTW</label></div>
                                    <div class="col-md-1"><div class="pull-right">&euro;</div></div>
                                    <div class="col-md-2">
                                        <input name="hour_rate" type="text" value="{{ old('hour_rate') ? old('hour_rate') : number_format($relation->hour_rate, 2,",",".") }}" class="form-control form-control-sm-number"/>
                                    </div>
                                    <div class="col-md-2">
                                        <input name="more_hour_rate" id="more_hour_rate" type="text" value="{{ old('more_hour_rate') ? old('more_hour_rate') : number_format($relation->hour_rate_more, 2,",",".") }}" class="form-control form-control-sm-number"/>
                                    </div>
                                </div>

                                <h5><strong>Aanneming <a data-toggle="tooltip" data-placement="bottom" data-original-title="Geef hier uw winstpercentage op wat u over uw materiaal en overig wilt gaan rekenen. Of stel deze in bij Voorkeuren om bij elk project te kunnen gebruiken." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></strong></h5></strong></h5>
                                <div class="row">
                                    <div class="col-md-3"><label for="profit_material_1">Winstpercentage materiaal</label></div>
                                    <div class="col-md-1"><div class="pull-right">%</div></div>
                                    <div class="col-md-2">
                                        <input name="profit_material_1" id="profit_material_1" type="number" min="0" max="200" value="{{ old('profit_material_1') ? old('profit_material_1') : $relation->profit_calc_contr_mat }}" class="form-control form-control-sm-number"/>
                                    </div>
                                    <div class="col-md-2">
                                        <input name="more_profit_material_1" id="more_profit_material_1" type="number" min="0" max="200" value="{{ old('more_profit_material_1') ? old('more_profit_material_1') : $relation->profit_more_contr_mat }}" class="form-control form-control-sm-number"/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3"><label for="profit_equipment_1">Winstpercentage overig</label></div>
                                    <div class="col-md-1"><div class="pull-right">%</div></div>
                                    <div class="col-md-2">
                                        <input name="profit_equipment_1" id="profit_equipment_1" type="number" min="0" max="200" value="{{ old('profit_equipment_1') ? old('profit_equipment_1') : $relation->profit_calc_contr_equip }}" class="form-control form-control-sm-number"/>
                                    </div>
                                    <div class="col-md-2">
                                        <input name="more_profit_equipment_1" id="more_profit_equipment_1" type="number" min="0" max="200" value="{{ old('more_profit_equipment_1') ? old('more_profit_equipment_1') : $relation->profit_more_contr_equip }}" class="form-control form-control-sm-number"/>
                                    </div>
                                </div>

                                <h5><strong>Onderaanneming <a data-toggle="tooltip" data-placement="bottom" data-original-title="Onderaanneming: Geef hier uw winstpercentage op wat u over het materiaal en overig van uw onderaanneming wilt gaan rekenen. Of stel deze in bij Voorkeuren om bij elk project te kunnen gebruiken." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a></strong></h5></strong></h5>
                                <div class="row">
                                    <div class="col-md-3"><label for="profit_material_2">Winstpercentage materiaal</label></div>
                                    <div class="col-md-1"><div class="pull-right">%</div></div>
                                    <div class="col-md-2">
                                        <input name="profit_material_2" id="profit_material_2" type="number" min="0" max="200" value="{{ old('profit_material_2') ? old('profit_material_2') : $relation->profit_calc_subcontr_mat }}" class="form-control form-control-sm-number"/>
                                    </div>
                                    <div class="col-md-2">
                                        <input name="more_profit_material_2" id="more_profit_material_2" type="number" min="0" max="200" value="{{ old('more_profit_material_2') ? old('more_profit_material_2') : $relation->profit_more_subcontr_mat }}" class="form-control form-control-sm-number"/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3"><label for="profit_equipment_2">Winstpercentage overig</label></div>
                                    <div class="col-md-1"><div class="pull-right">%</div></div>
                                    <div class="col-md-2">
                                        <input name="profit_equipment_2" id="profit_equipment_2" type="number" min="0" max="200" value="{{ old('profit_equipment_2') ? old('profit_equipment_2') : $relation->profit_calc_subcontr_equip }}" class="form-control form-control-sm-number"/>
                                    </div>
                                    <div class="col-md-2">
                                        <input name="more_profit_equipment_2" id="more_profit_equipment_2" type="number" min="0" max="200" value="{{ old('more_profit_equipment_2') ? old('more_profit_equipment_2') : $relation->profit_more_subcontr_equip }}" class="form-control form-control-sm-number"/>
                                    </div>
                                </div>
                                <br/>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-primary"><i class="fa fa-check"></i> Opslaan</button>
                                    </div>
                                </div>
                            </form>
                        </div>


                        <div id="invoices" class="tab-pane">
                            <h4>Facturen bij relatie</h4>
                            
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="col-md-2">Factuur</th>
                                        <th class="col-md-2">Project</th>
                                        <th class="col-md-2">Bedrag</th>
                                        <th class="col-md-2">Datum</th>
                                        <th class="col-md-2"></th>
                                        <th class="col-md-2">Status</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php $i = 0; ?>
                                    @foreach (Project::where('user_id','=', Auth::id())->where('client_id',$relation->id)->orderBy('created_at','desc')->get() as $project)
                                    @foreach (Offer::where('project_id','=', $project->id)->orderBy('created_at','desc')->get() as $offer)
                                    @foreach (Invoice::where('offer_id','=', $offer->id)->whereNotNUll('bill_date')->orderBy('created_at','desc')->get() as $invoice)
                                    <?php $i++; ?>
                                    <tr>
                                        <td class="col-md-2"><a href="/invoice/project-{{ $project->id }}/pdf-invoice-{{ $invoice->id }}">{{ $invoice->invoice_code }}</a></td>
                                        <td class="col-md-2">{{ $project->project_name }}</td>
                                        <td class="col-md-2">{!! '&euro;&nbsp;'.number_format($invoice->amount, 2, ",",".") !!}</td>
                                        <td class="col-md-2">{{ date('d-m-Y', strtotime(DB::table('invoice')->select('created_at')->where('id','=',$invoice->id)->get()[0]->created_at)) }}</td>
                                        <td class="col-md-2">{{--  --}}</td>
                                        <td class="col-md-2">{{ $invoice->payment_date ? 'Betaald' : 'Gefactureerd' }}</td>
                                    </tr>
                                    @endforeach
                                    @endforeach
                                    @endforeach

                                    @if (!$i)
                                    <tr>
                                        <td colspan="6"><center>Geen facturen bij relatie</center></td>
                                    </td>
                                    @endif
                                </tbody>
                            </table>							
                        </div>
                    </div>
                </div>

        </div>

    </section>

</div>
<script type="text/javascript">
$(document).ready(function() {
    <?php $response = RelationKind::where('id','=',old('relationkind'))->first(); ?>
    if('{{ ($response ? $response->kind_name : 'zakelijk') }}'=='particulier'){
        $('.company').hide();
        $('#relationkind option[value="{{ old('relationkind') }}"]').attr("selected",true);
    }
    $('#relationkind').change(function() {
        $('.company').toggle('slow');
    });
});
</script>
@stop

<?php } ?>
