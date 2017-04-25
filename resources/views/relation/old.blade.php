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
                        <div id="calc" class="tab-pane">
                            <h4>Voorkeurstarief</h4>

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
