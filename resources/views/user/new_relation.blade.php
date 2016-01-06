@extends('layout.master')

@section('content')

<?php
function getNewDebtorCode() {
	return mt_rand(1000000, 9999999);
}
?>

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

<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			<div>
				<ol class="breadcrumb">
				  <li><a href="/">Dashboard</a></li>
				  <li><a href="/relation">Relaties</a></li>
				  <li>Nieuw Contact</li>
				</ol>
			<div>
			<br>

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

			<h2><strong>Nieuwe</strong> relatie</h2>

			<div class="white-row">
				<form method="POST" action="/relation/new" accept-charset="UTF-8">
				{!! csrf_field() !!}
				<div class="row">

					<div class="col-md-2">
						<div class="form-group">
							<label for="relationkind">Relatiesoort*</label>
							<select name="relationkind" id="relationkind" class="form-control pointer">
							@foreach (Calctool\Models\RelationKind::all() as $kind)
								<option value="{{ $kind->id }}">{{ ucwords($kind->kind_name) }}</option>
							@endforeach
							</select>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="debtor">Debiteurennummer*</label>
							<input name="debtor" id="debtor" type="text" value="{{ Input::old('debtor') ? Input::old('debtor') : getNewDebtorCode() }}" class="form-control"/>
						</div>
					</div>

				</div>

				<h4 class="company">Bedrijfsgegevens</h4>
				<div class="row company">

					<div class="col-md-5">
						<div class="form-group">
							<label for="company_name">Bedrijfsnaam*</label>
							<input name="company_name" id="company_name" type="text" value="{{ Input::old('company_name') }}" class="form-control" />
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="company_type">Bedrijfstype*</label>
							<select name="company_type" id="company_type" class="form-control pointer">
							@foreach (Calctool\Models\RelationType::all() as $type)
								<option value="{{ $type->id }}">{{ ucwords($type->type_name) }}</option>
							@endforeach
							</select>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label for="website">Website</label>
							<input name="website" id="website" type="url" value="{{ Input::old('website') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="kvk">K.v.K nummer</label>&nbsp;<a data-toggle="tooltip" data-placement="bottom" data-original-title="Je KVK-nummer dient te bestaan uit 8 cijfers" href="javascript:void(0);"><i class="fa fa-info-circle"></i></a>
							<input name="kvk" id="kvk" type="text" maxlength="8" minlength="8" value="{{ Input::old('kvk') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="btw">BTW nummer</label>&nbsp;<a data-toggle="tooltip" data-placement="bottom" data-original-title="Je BTW-nummer bestaat uit een combinatie van 12 cijfers en/of letters. Veelal beginnen nederlandse BTW-nummers met 'NL' en eindigen op 'B01'." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a>
							<input name="btw" id="btw" type="text" maxlength="14" value="{{ Input::old('btw') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="telephone_comp">Telefoonnummer</label>
							<input name="telephone_comp" id="telephone_comp" type="text" minlength="12" maxlength="12" value="{{ Input::old('telephone_comp') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="email_comp">Email*</label>
							<input name="email_comp" id="email_comp" type="email" value="{{ Input::old('email_comp') }}" class="form-control"/>
						</div>
					</div>

				</div>

				<h4>Adresgegevens</h4>
				<div class="row">

					<div class="col-md-1">
						<div class="form-group">
							<label for="address_number">Huis nr.*</label>
							<input name="address_number" id="address_number" type="text" value="{{ Input::old('address_number') }}" class="form-control autoappend"/>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="zipcode">Postcode*</label>
							<input name="zipcode" id="zipcode" maxlength="6" type="text" value="{{ Input::old('zipcode') }}" class="form-control autoappend"/>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label for="street">Straat*</label>
							<input name="street" id="street" type="text" value="{{ Input::old('street') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="city">Plaats*</label>
							<input name="city" id="city" type="text" value="{{ Input::old('city') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="province">Provincie*</label>
							<select name="province" id="province" class="form-control pointer">
								@foreach (Calctool\Models\Province::all() as $province)
									<option value="{{ $province->id }}">{{ ucwords($province->province_name) }}</option>
								@endforeach
							</select>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label for="country">Land*</label>
							<select name="country" id="country" class="form-control pointer">
								@foreach (Calctool\Models\Country::all() as $country)
									<option {{ $country->country_name=='nederland' ? 'selected' : '' }} value="{{ $country->id }}">{{ ucwords($country->country_name) }}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>

				<h4>Contactpersoon</h4>
				<div class="row">

					<div class="col-md-2">
						<div class="form-group">
							<label for="contact_firstname">Voornaam*</label>
							<input name="contact_firstname" id="contact_firstname" type="text" value="{{ Input::old('contact_firstname') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="contact_name">Achternaam*</label>
							<input name="contact_name" id="contact_name" type="text" value="{{ Input::old('contact_name') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="mobile">Mobiel</label>
							<input name="mobile" id="mobile" type="text" maxlength="12" value="{{ Input::old('mobile') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="telephone">Telefoonnummer</label>
							<input name="telephone" id="telephone" type="text" maxlength="12" value="{{ Input::old('telephone') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="email">Email*</label>
							<input name="email" id="email" type="email" value="{{ Input::old('email') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-4 company">
						<div class="form-group">
							<label for="contactfunction">Functie*</label>
							<select name="contactfunction" id="contactfunction" class="form-control pointer">
							@foreach (Calctool\Models\ContactFunction::all() as $function)
								<option {{ $function->function_name=='directeur' ? 'selected' : '' }} value="{{ $function->id }}">{{ ucwords($function->function_name) }}</option>
							@endforeach
							</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="gender" style="display:block;">Geslacht</label>
							<select name="gender" id="gender" class="form-control pointer">
								<option value="-1">Selecteer</option>
								<option value="M">Man</option>
								<option value="V">Vrouw</option>
							</select>
						</div>
					</div>

				</div>

				<h4>Opmerkingen</h4>
				<div class="row">
					<div class="form-group">
						<div class="col-md-12">
							<textarea name="note" id="note" rows="10" class="form-control">{{ Input::old('note') }}</textarea>
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
