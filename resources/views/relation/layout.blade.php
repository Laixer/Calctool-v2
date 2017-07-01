@extends('layout.master')

@section('title', 'Bedrijfsgegevens')

@push('style')
<link media="all" type="text/css" rel="stylesheet" href="/plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css">
@endpush

@push('scripts')
<script src="/plugins/summernote/summernote.min.js"></script>
<script src="/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
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

});
</script>
<div id="wrapper">

    <section class="container">

        <div class="col-md-12">

            <div>
                <ol class="breadcrumb">
                    <li><a href="/">Dashboard</a></li>
                    <li><a href="/relation">Relaties</a></li>
                    <li>{{ $relation->name() }}</li>
                </ol>
            </div>

            @include('layout.message')

            <aside class="col-md-3 nav-align">
                <div class="white-row" style="padding:10px;">
                    <ul class="nav nav-list">
                        <li class="{{ $page == 'details' ? 'nav-active' : '' }}"><a href="/relation/{{ $relation->id }}-{{ $relation->slug() }}/details"><i class="fa fa-id-card"></i> Bedrijfsgegevens</a></li>
                        <li class="{{ $page == 'contacts' ? 'nav-active' : '' }}"><a href="/relation/{{ $relation->id }}-{{ $relation->slug() }}/contacts"><i class="fa fa-users"></i> Contacten</a></li>
                        <li class="{{ $page == 'notes' ? 'nav-active' : '' }}"><a href="/relation/{{ $relation->id }}-{{ $relation->slug() }}/notes"><i class="fa fa-cogs"></i> Notities</a></li>
                        <li class="{{ $page == 'financial' ? 'nav-active' : '' }}"><a href="/relation/{{ $relation->id }}-{{ $relation->slug() }}/financial"><i class="fa fa-university"></i> Financieel</a></li>
                        <li class="{{ $page == 'invoices' ? 'nav-active' : '' }}"><a href="/relation/{{ $relation->id }}-{{ $relation->slug() }}/invoices"><i class="fa fa-file-pdf-o"></i> Facturen</a></li>
                        <li class="{{ $page == 'preferences' ? 'nav-active' : '' }}"><a href="/relation/{{ $relation->id }}-{{ $relation->slug() }}/preferences"><i class="fa fa-sliders"></i> Voorkeuren</a></li>
                    </ul>
                </div>
            </aside>

            <div class="col-md-9">
                <h4>@yield('relation_section_name')</h4>

                @yield('relation_content')
            </div>

        </div>

    </section>

</div>
@stop
