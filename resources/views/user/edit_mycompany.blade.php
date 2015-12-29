<?php

use \Calctool\Models\Relation;
use \Calctool\Models\RelationType;
use \Calctool\Models\RelationKind;
use \Calctool\Models\Contact;
use \Calctool\Models\ContactFunction;
use \Calctool\Models\Province;
use \Calctool\Models\Country;
use \Calctool\Models\Resource;
use \Calctool\Models\BankAccount;
use \Calctool\Models\Cashbook;

$relation = Relation::find(Auth::user()->self_id);
?>

@extends('layout.master')

@section('content')

<script type="text/javascript" src="/js/iban.js"></script>
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
	$('#tab-cashbook').click(function(e){
		sessionStorage.toggleTabMyComp{{Auth::id()}} = 'cashbook';
	});
	$('#tab-logo').click(function(e){
		sessionStorage.toggleTabMyComp{{Auth::id()}} = 'logo';
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

    $('.btn-file :file').on('fileselect', function(event, numFiles, label) {

        var input = $(this).parents('.input-group').find(':text'),
            log = numFiles > 1 ? numFiles + ' files selected' : label;

        if( input.length ) {
            input.val(log);
        } else {
            if( log ) alert(log);
        }

    });
	$('#accountModal').on('hidden.bs.modal', function() {
		$.post("/mycompany/cashbook/account/new", {account: $('#account').val(), account_name: $('#account_name').val(), amount: $('#amount').val()}, function(data) {
			location.reload();
		});
	});
	$('#cashbookModal').on('hidden.bs.modal', function() {
		$.post("/mycompany/cashbook/new", {account: $('#account2').val(), amount: $('#amount2').val(), date: $('#date').val(), desc: $('#desc').val()}, function(data) {
			location.reload();
		});
	});
    $('#dateRangePicker').datepicker();

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
					var json = $.parseJSON(data);
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
<div id="wrapper">

	<div class="modal fade" id="accountModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel2">Nieuwe rekening</h4>
				</div>

				<div class="modal-body">
					<div class="form-horizontal">
						<div class="form-group">
							<div class="col-md-4">
								<label>Rekening</label>
							</div>
							<div class="col-md-8">
								<input name="account" id="account" type="text" class="form-control" />
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-4">
								<label>Naam</label>
							</div>
							<div class="col-md-8">
								<input name="account_name" id="account_name" type="text" class="form-control" />
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-4">
								<label>Startbedrag</label>
							</div>
							<div class="col-md-8">
								<input name="amount" id="amount" type="text" class="form-control" />
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-default" data-dismiss="modal">Opslaan</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="cashbookModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel2">Nieuwe regel</h4>
				</div>

				<div class="modal-body">
					<div class="form-horizontal">
						<div class="form-group">
							<div class="col-md-4">
								<label>Rekening</label>
							</div>
							<div class="col-md-8">
								<select name="account2" id="account2" class="form-control pointer">
								@foreach (BankAccount::where('user_id', Auth::id())->get() as $account)
									<option value="{{ $account->id }}">{{ $account->account }}</option>
								@endforeach
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-4">
								<label>Bedrag</label>
							</div>
							<div class="col-md-8">
								<input name="amount2" id="amount2" type="text" class="form-control" />
							</div>
						</div>
					    <div class="form-group">
					        <label class="col-md-4">Datum</label>
					        <div class="col-md-8 date">
					            <div class="input-group input-append date" id="dateRangePicker">
					                <input type="text" class="form-control" name="date" id="date" />
					                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
					            </div>
					        </div>
					    </div>
						<div class="form-group">
							<div class="col-md-4">
								<label>Omschrijving</label>
							</div>
							<div class="col-md-8">
								<input name="desc" id="desc" type="text" class="form-control" />
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-default" data-dismiss="modal">Opslaan</button>
				</div>
			</div>
		</div>
	</div>

	<section class="container">

		<div class="col-md-12">

			@if(Session::get('success'))
			<div class="alert alert-success">
				<i class="fa fa-check-circle"></i>
				<strong>Opgeslagen</strong>
			</div>
			@endif

			@if($errors->has())
			<div class="alert alert-danger">
				<i class="fa fa-frown-o"></i>
				<strong>Fout</strong>
				@foreach ($errors->all() as $error)
					{{ $error }}
				@endforeach
			</div>
			@endif

			@if ($relation && !Contact::where('relation_id','=', $relation->id)->count())
				<div class="alert alert-warning">
					<i class="fa fa-fa fa-info-circle"></i>
					Er moet minimaal 1 contactpersoon bestaan
				</div>
			@endif

			<h2><strong>Mijn</strong> bedrijf</h2>

				<div class="tabs nomargin-top">

					<ul class="nav nav-tabs">
						<li id="tab-company">
							<a href="#company" data-toggle="tab">Bedrijfsgegevens</a>
						</li>
						<li id="tab-contact">
							<a href="#contact" data-toggle="tab">Contacten</a>
						</li>
						<li id="tab-payment">
							<a href="#payment" data-toggle="tab">Betalingsgegevens</a>
						</li>
						<li id="tab-logo">
							<a href="#logo" data-toggle="tab">Logo</a>
						</li>
						<li id="tab-cashbook">
							<a href="#cashbook" data-toggle="tab">Kasboek</a>
						</li>
					</ul>

					<div class="tab-content">
						<div id="company" class="tab-pane">

							{!! $relation ? '<form action="relation/updatemycompany" method="post">' : '<form action="relation/newmycompany" method="post">' !!}
							{!! csrf_field() !!}

							<h4 class="company">Bedrijfsgegevens</h4>
							<input type="hidden" name="id" id="id" value="{{ $relation ? $relation->id : '' }}"/>
							<div class="row">
								<div class="col-md-5">
									<div class="form-group">
										<label for="company_name">Bedrijfsnaam*</label>
										<input name="company_name" id="company_name" type="text" value="{{ Input::old('company_name') ? Input::old('company_name') : ($relation ? $relation->company_name : '') }}" class="form-control" />
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="company_type">Bedrijfstype*</label>
										<select name="company_type" id="company_type" class="form-control pointer">
										@foreach (RelationType::all() as $type)
											<option {{ $relation ? ($relation->type_id==$type->id ? 'selected' : '') : '' }} value="{{ $type->id }}">{{ ucwords($type->type_name) }}</option>
										@endforeach
										</select>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="website">Website</label>
										<input name="website" id="website" type="url" value="{{ Input::old('website') ? Input::old('website') : ($relation ? $relation->website : '') }}" class="form-control"/>
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
										<input name="email_comp" id="email_comp" type="email" value="{{ Input::old('email_comp') ? Input::old('email_comp') : ($relation ? $relation->email : '') }}" class="form-control"/>
									</div>
								</div>
							</div>

							<h4>Adresgegevens</h4>
							<div class="row">
								<div class="col-md-1">
									<div class="form-group">
										<label for="address_number">Huis nr.*</label>
										<input name="address_number" id="address_number" type="text" value="{{ Input::old('address_number') ? Input::old('address_number') : ($relation ? $relation->address_number : '') }}" class="form-control autoappend"/>
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
										<input name="street" id="street" type="text" value="{{ Input::old('street') ? Input::old('street') : ($relation ? $relation->address_street : '') }}" class="form-control"/>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="city">Plaats*</label>
										<input name="city" id="city" type="text" value="{{ Input::old('city') ? Input::old('city') : ($relation ? $relation->address_city : '') }}" class="form-control"/>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="province">Provincie*</label>
										<select name="province" id="province" class="form-control pointer">
											@foreach (Province::all() as $province)
												<option {{ $relation ? ($relation->province_id==$province->id ? 'selected' : '') : '' }} value="{{ $province->id }}">{{ ucwords($province->province_name) }}</option>
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

							<h4>Opmerkingen</h4>
							<div class="row">
								<div class="form-group">
									<div class="col-md-12">
										<textarea name="note" id="note" rows="10" class="form-control">{{ Input::old('note') ? Input::old('note') : ($relation ? $relation->note : '') }}</textarea>
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
										<th class="col-md-2">Naam</th>
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
										<td class="col-md-2">{{ $contact->email }}</td>
									</tr>
									@endforeach
									<?php } ?>
								</tbody>
							</table>
							<div class="row">
								<div class="col-md-12">
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
										<input name="iban" id="iban" type="text" value="{{ Input::old('iban') ? Input::old('iban') : ($relation ? $relation->iban : '') }}" {{ $relation ? '' : 'disabled' }} class="form-control"/>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="btw">Naam rekeninghouder</label>
										<input name="iban_name" id="iban_name" type="text" {{ $relation ? '' : 'disabled' }} value="{{ Input::old('iban_name') ? Input::old('iban_name') : ($relation ? $relation->iban_name : '') }}" class="form-control"/>
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
							<h4>Logo</h4>
							<form action="relation/logo/save" method="post" enctype="multipart/form-data">
							{!! csrf_field() !!}
							<input type="hidden" name="id" id="id" value="{{ $relation ? $relation->id : '' }}"/>

							{!! ($relation && $relation->logo_id) ? "<div><h5>Huidige logo</h5><img src=\"/".Resource::find($relation->logo_id)->file_location."\"/></div>" : '' !!}

							<div class="form-group">
								<label for="image">Afbeelding Uploaden</label>
								<div class="input-group col-md-4">
					                <span class="input-group-btn">
					                    <span class="btn btn-primary btn-file {{ $relation ? '' : 'disabled' }}">
					                        Browse&hellip; <input {{ $relation ? '' : 'disabled' }} name="image" type="file" multiple>
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
						<div id="cashbook" class="tab-pane">
							<h4>Rekeningen</h4>
							<div class="row">
								<div class="col-md-3"><strong>Rekening</strong></div>
								<div class="col-md-2"><strong>Saldo</strong></div>
							</div>
							@foreach (BankAccount::where('user_id', Auth::id())->get() as $account)
							<div class="row">
								<div class="col-md-3">{{ $account->account }}</div>
								<div class="col-md-2">&euro;{{ number_format(Cashbook::where('account_id', $account->id)->sum('amount'), 2, ",",".") }}</div>
								<div class="col-md-3"></div>
							</div>
							@endforeach
							<br />
							<h4>Af en bij</h4>
							<table class="table table-striped">
								<thead>
									<tr>
										<th class="col-md-2">Rekening</th>
										<th class="col-md-2">Bedrag</th>
										<th class="col-md-2">Datum</th>
										<th class="col-md-6">Omschrijving</th>
									</tr>
								</thead>

								<tbody>
									@foreach (BankAccount::where('user_id', Auth::id())->get() as $account)
									@foreach (Cashbook::where('account_id', $account->id)->orderBy('payment_date','desc')->get() as $row)
									<tr>
										<td class="col-md-2">{{ $account->account }}</a></td>
										<td class="col-md-2">{{ ($row->amount > 0 ? '+' : '') . number_format($row->amount, 2, ",",".") }}</td>
										<td class="col-md-2">{{ date('d-m-Y', strtotime($row->payment_date)) }}</td>
										<td class="col-md-6">{{ $row->description }}</td>
									</tr>
									@endforeach
									@endforeach
								</tbody>
							</table>
							<div class="row">
								<div class="col-md-12">
									<a href="#" data-toggle="modal" data-target="#cashbookModal" id="newcash" class="btn btn-primary"><i class="fa fa-pencil"></i> Nieuwe regel</a>
									<a href="#" data-toggle="modal" data-target="#accountModal" id="newacc" class="btn btn-primary"><i class="fa fa-pencil"></i> Nieuwe rekening</a>
								</div>
							</div>
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
		console.log('check');
	});
});
</script>
@stop
