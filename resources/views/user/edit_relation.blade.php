<?php
$common_access_error = false;
$relation = \Calctool\Models\Relation::find(Route::Input('relation_id'));
if (!$relation || !$relation->isOwner()) {
	$common_access_error = true;
} else {
	$iban = \Calctool\Models\Iban::where('relation_id','=',$relation->id)->first();
	$contact = \Calctool\Models\Contact::where('relation_id','=',$relation->id)->first();
}
?>

@extends('layout.master')

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
		var regx = /^[A-Za-z]+$/;
		if( streetcheck != "" && regx.test(streetcheck)) {
			$(this).removeClass("error-input");
		}else {
			$(this).addClass("error-input");
		}
	});




});
</script>

<div id="wrapper">

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

			<div>
			<ol class="breadcrumb">
			  <li><a href="/">Home</a></li>
			  <li><a href="/relation">Relaties</a></li>
			 <li>{{ $relation->company_name ? $relation->company_name : $contact->firstname . ' ' . $contact->lastname }}</li>
			</ol>
			<div>
			<br>

			<h2><strong>Relatie</strong> {{ $relation->company_name ? $relation->company_name : $contact->firstname . ' ' . $contact->lastname }}</h2>

				<div class="tabs nomargin-top">

					<?# -- tabs -- ?>
					<ul class="nav nav-tabs">
						<li class="active">
							<a href="#company" data-toggle="tab">{{ ucfirst( \Calctool\Models\RelationKind::find($relation->kind_id)->kind_name) }}e gegevens</a>
						</li>
						<li>
							<a href="#payment" data-toggle="tab">Betalingsgegevens</a>
						</li>
						<li>
							<a href="#contact" data-toggle="tab">Contacten</a>
						</li>
					</ul>

					<?# -- tabs content -- ?>
					<div class="tab-content">
						<div id="company" class="tab-pane active">

							<form method="POST" action="/relation/update" accept-charset="UTF-8">
			                                {!! csrf_field() !!}
							<h4>{{ ucfirst(\Calctool\Models\RelationKind::find($relation->kind_id)->kind_name) }}e relatie</h4>
							<div class="row">
								<div class="col-md-2">
									<div class="form-group">
										<label for="debtor">Debiteurennummer</label>
										<input name="debtor" id="debtor" type="text" value="{{ Input::old('debtor') ? Input::old('debtor') : $relation->debtor_code }}" class="form-control"/>
										<input type="hidden" name="id" id="id" value="{{ $relation->id }}"/>
									</div>
								</div>

							</div>

							@if (\Calctool\Models\RelationKind::find($relation->kind_id)->kind_name == 'zakelijk')
							<h4 class="company">Bedrijfsgegevens</h4>
							<div class="row company">

								<div class="col-md-5">
									<div class="form-group">
										<label for="company_name">Bedrijfsnaam*</label>
										<input name="company_name" id="company_name" type="text" value="{{ Input::old('company_name') ? Input::old('company_name') : $relation->company_name }}" class="form-control" />
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="company_type">Bedrijfstype*</label>
										<select name="company_type" id="company_type" class="form-control pointer">
										@foreach (\Calctool\Models\RelationType::all() as $type)
											<option {{ $relation->type_id==$type->id ? 'selected' : '' }} value="{{ $type->id }}">{{ ucwords($type->type_name) }}</option>
										@endforeach
										</select>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="website">Website</label>
										<input name="website" id="website" type="url" value="{{ Input::old('website') ? Input::old('website') : $relation->website }}" class="form-control"/>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="kvk">K.v.K nummer</label>&nbsp;<a data-toggle="tooltip" data-placement="bottom" data-original-title="Je KVK-nummer dient te bestaan uit 8 cijfers" href="javascript:void(0);"><i class="fa fa-info-circle"></i></a>
										<input name="kvk" id="kvk" type="text" maxlength="8" minlength="8" value="{{ Input::old('kvk') ? Input::old('kvk') : trim($relation->kvk) }}" class="form-control"/>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="btw">BTW nummer</label>&nbsp;<a data-toggle="tooltip" data-placement="bottom" data-original-title="Je BTW-nummer bestaat uit een combinatie van 12 cijfers en/of letters. Veelal beginnen nederlandse BTW-nummers met 'NL' en eindigen op 'B01'." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a>
										<input name="btw" id="btw" type="text" maxlength="14" value="{{ Input::old('btw') ? Input::old('btw') : $relation->btw }}" class="form-control"/>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="telephone_comp">Telefoonnummer</label>
										<input name="telephone_comp" id="telephone_comp" type="text" maxlength="12" value="{{ Input::old('telephone_comp') ? Input::old('telephone_comp') : $relation->phone }}" class="form-control"/>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="email_comp">Email*</label>
										<input name="email_comp" id="email_comp" type="email" value="{{ Input::old('email_comp') ? Input::old('email_comp') : $relation->email }}" class="form-control"/>
									</div>
								</div>

							</div>
							@endif

							<h4>Adresgegevens</h4>
							<div class="row">

								<div class="col-md-4">
									<div class="form-group">
										<label for="street">Straat*</label>
										<input name="street" id="street" type="text" value="{{ Input::old('street') ? Input::old('street') : $relation->address_street }}" class="form-control"/>
									</div>
								</div>

								<div class="col-md-1">
									<div class="form-group">
										<label for="address_number">Huis nr.*</label>
										<input name="address_number" id="address_number" type="text" value="{{ Input::old('address_number') ? Input::old('address_number') : $relation->address_number }}" class="form-control"/>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="zipcode">Postcode*</label>
										<input name="zipcode" id="zipcode" maxlength="6" type="text" value="{{ Input::old('zipcode') ? Input::old('zipcode') : $relation->address_postal }}" class="form-control"/>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="city">Plaats*</label>
										<input name="city" id="city" type="text" value="{{ Input::old('city') ? Input::old('city') : $relation->address_city }}" class="form-control"/>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="province">Provincie*</label>
										<select name="province" id="province" class="form-control pointer">
											@foreach (\Calctool\Models\Province::all() as $province)
												<option {{ $relation->province_id==$province->id ? 'selected' : '' }} value="{{ $province->id }}">{{ ucwords($province->province_name) }}</option>
											@endforeach
										</select>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="country">Land*</label>
										<select name="country" id="country" class="form-control pointer">
											@foreach (\Calctool\Models\Country::all() as $country)
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
										<textarea name="note" id="note" rows="10" class="form-control">{{ Input::old('note') ? Input::old('note') : $relation->note }}</textarea>
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
						<div id="payment" class="tab-pane">
							<h4>Betalingsgegevens</h4>
							<form method="POST" action="/relation/iban/update" accept-charset="UTF-8">
                            {!! csrf_field() !!}
							<div class="row">

								<div class="col-md-3">
									<div class="form-group">
										<label for="iban">IBAN rekeningnummer</label>
										<input name="iban" id="iban" type="text" value="{{ Input::old('iban') ? Input::old('iban') : $iban->iban }}" class="form-control"/>
										<input type="hidden" name="id" id="id" value="{{ $iban->id }}"/>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="btw">Naam rekeninghouder</label>
										<input name="iban_name" id="iban_name" type="text" value="{{ Input::old('iban_name') ? Input::old('iban_name') : $iban->iban_name }}" class="form-control"/>
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
								<?# -- table head -- ?>
								<thead>
									<tr>
										<th class="col-md-2">Voornaam</th>
										<th class="col-md-2">Achternaam</th>
										<th class="col-md-2">Functie</th>
										<th class="col-md-2">Telefoon</th>
										<th class="col-md-2">Mobiel</th>
										<th class="col-md-2">Email</th>
									</tr>
								</thead>

								<!-- table items -->
								<tbody>
									@foreach (\Calctool\Models\Contact::where('relation_id','=', $relation->id)->get() as $contact)
									<tr><!-- item -->
										<td class="col-md-2"><a href="/relation-{{ $relation->id }}/contact-{{ $contact->id }}/edit">{{ $contact->firstname }}</a></td>
										<td class="col-md-2">{{ $contact->lastname }}</td>
										<td class="col-md-2">{{ ucfirst(\Calctool\Models\ContactFunction::find($contact->function_id)->function_name) }}</td>
										<td class="col-md-2">{{ $contact->phone }}</td>
										<td class="col-md-2">{{ $contact->mobile }}</td>
										<td class="col-md-2">{{ $contact->email }}</td>
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
					</div>
				</div>

		</div>

	</section>

</div>
<?#-- /WRAPPER --?>
<script type="text/javascript">
$(document).ready(function() {
	<?php $response = \Calctool\Models\RelationKind::where('id','=',Input::old('relationkind'))->first(); ?>
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

<?php } ?>
