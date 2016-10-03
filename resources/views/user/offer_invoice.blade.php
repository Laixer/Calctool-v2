<?php

use \Calctool\Models\Relation;
use \Calctool\Models\Contact;
use \Calctool\Models\Invoice;
use \Calctool\Models\Resource;
use \Calctool\Models\Project;
use \Calctool\Models\Offer;

$relation = Relation::find(Auth::user()->self_id);
$user = Auth::user();
?>

@extends('layout.master')

@section('title', 'Mijn bedrijf')

@push('style')
<link media="all" type="text/css" rel="stylesheet" href="/components/intro.js/introjs.css">
<link media="all" type="text/css" rel="stylesheet" href="/plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css">
@endpush

@push('scripts')
<script src="/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<script src="/components/intro.js/intro.js"></script>
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

<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			<div>
				<ol class="breadcrumb">
				  <li><a href="/">Home</a></li>
				  <li class="active">Overzichten</li>
				</ol>
			<div>
			<br>

			@if(Session::get('success'))
			<div class="alert alert-success">
				<i class="fa fa-check-circle"></i>
				<strong>Opgeslagen</strong>
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

			<h2 style="margin: 10px 0 20px 0;"><strong>Overzichten</strong></h2>

				<div class="tabs nomargin-top">

					<ul class="nav nav-tabs">
						<li id="tab-contact">
							<a href="#contact" data-toggle="tab" data-step="3" data-position="botom" data-intro="Je bedrijf heeft een contactpersoon nodig. Klik op het tabblad 'Contacten' en klik daarna op volgende.">Offertes</a>
						</li>
						<li id="tab-payment">
							<a href="#payment" data-toggle="tab">Facturen</a>
						</li>
					</ul>

					<div class="tab-content">
						<div id="contact" class="tab-pane">
							<table class="table table-striped">
								<thead>
									<tr>
										<th class="col-md-2">Offertenummer</th>
										<th class="col-md-2">Datum</th>
										<th class="col-md-3">Aanbetalingsbedrag</th>
										<th class="col-md-3">Offertebedrag (excl. BTW)</th>
										<th class="col-md-3">Acties</th>
									</tr>
								</thead>
								<tbody>
									<?php $i = 0; ?>
									@foreach(Project::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get() as $project)
									@foreach(Offer::where('project_id', $project->id)->whereNotNull('offer_finish')->orderBy('created_at')->get() as $offer)
									<tr>
										<td class="col-md-2"><a href="/offer/project-{{ $project->id }}/offer-{{ $offer->id }}">{{ $offer->offer_code }}</a></td>
										<td class="col-md-2"><?php echo date('d-m-Y', strtotime($offer->offer_make)); ?></td>
										<td class="col-md-3">{{ $offer->downpayment_amount ? number_format($offer->downpayment_amount, 2,",",".") : '-' }}</td>
										<td class="col-md-3">{{ '&euro; '.number_format($offer->offer_total, 2, ",",".") }}</td>
										<td class="col-md-3"><a href="/res-{{ ($offer->resource_id) }}/download" class="btn btn-primary btn-xs"><i class="fa fa-cloud-download fa-fw"></i> Downloaden</a></td>
									</tr>
									@endforeach
									@endforeach
								</tbody>
							</table>
						</div>
						<div id="payment" class="tab-pane">
							<table class="table table-striped">
								<thead>
									<tr>
										<th class="col-md-2">Factuurnummer</th>
										<th class="col-md-2">Bedrag (Excl. BTW)</th>
										<th class="col-md-1">Referentie</th>
										<th class="col-md-2">Aangemaakt op</th>
										<th class="col-md-2">Betaald op</th>
										<th class="col-md-1">Conditie</th>
										<th class="col-md-2">Status</th>
										<th class="col-md-1"></th>
									</tr>
								</thead>

								<tbody>
									@foreach(Project::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get() as $project)
									<?php $offer_last = Offer::where('project_id','=',$project->id)->orderBy('created_at', 'desc')->first(); ?>
									@foreach(Invoice::where('offer_id','=', $offer_last->id)->where('isclose', true)->where('invoice_close', true)->orderBy('priority')->get() as $invoice)
									<tr>
										<td class="col-md-2"><a href="/invoice/project-{{ $project->id }}/pdf-invoice-{{ $invoice->id }}">{{ Auth::user()->pref_use_ct_numbering ? $invoice->invoice_code : $invoice->book_code }}</a></td>
										<td class="col-md-2">{{ number_format($invoice->amount, 2,",",".") }}</td>
										<td class="col-md-2">{{ $invoice->reference ? $invoice->reference : '-' }}</td>
										<td class="col-md-2">{{ $invoice->invoice_make ? date("d-m-Y", strtotime($invoice->invoice_make)) : '-' }}</td>
										<td class="col-md-2">{{ $invoice->payment_date ? date("d-m-Y", strtotime($invoice->payment_date)) : '-' }}</td>
										<td class="col-md-1">{{ $invoice->payment_condition }} dagen</td>
										<td class="col-md-1"><a href="/res-{{ ($invoice->resource_id) }}/download" class="btn btn-primary btn-xs"><i class="fa fa-cloud-download fa-fw"></i> Downloaden</a></td>
										<td class="col-md-1"></td>
									</tr>
									@endforeach
									@endforeach
								</tbody>
							</table>
						</div>
						
					</div>
				</div>

		</div>

	</section>

</div>
@stop
