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

    $('#website').blur(function(e) {
        prefixURL($(this));
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
                  <li class="active">Bedrijfsgegevens</li>
                </ol>
            </div>

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

            <aside class="col-md-3 nav-align">

                <ul class="nav nav-list">
                    @if (Auth::user()->hasOwnCompany())
                    <li class="{{ $page == 'details' ? 'nav-active' : '' }}"><a href="/company/details"><i class="fa fa-id-card"></i> Bedrijfsgegevens</a></li>
                    <li class="{{ $page == 'contacts' ? 'nav-active' : '' }}"><a href="/company/contacts"><i class="fa fa-users"></i> Contacten</a></li>
                    <li class="{{ $page == 'financial' ? 'nav-active' : '' }}"><a href="/company/financial"><i class="fa fa-university"></i> Financieel</a></li>
                    <li class="{{ $page == 'logo' ? 'nav-active' : '' }}"><a href="/company/logo"><i class="fa fa-file-image-o"></i> Logo & Voorwaarden</a></li>
                    <li class="{{ $page == 'preferences' ? 'nav-active' : '' }}"><a href="/company/preferences"><i class="fa fa-cogs"></i> Voorkeuren</a></li>
                    @else
                    <li class="nav-active"><a href="/company/setupcompany"><i class="fa fa-id-card"></i> Nieuw Bedrijf</a></li>
                    @endif
                </ul>

            </aside>

            <div class="col-md-9">
                <h4>@yield('company_section_name')</h4>

                <div class="white-row">
                    @yield('company_content')
                </div>
            </div>

        </div>

    </section>

</div>
@stop
