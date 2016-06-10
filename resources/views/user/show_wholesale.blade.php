<?php

use \Calctool\Models\Wholesale;
use \Calctool\Models\WholesaleType;
use \Calctool\Models\Province;
use \Calctool\Models\Country;

$common_access_error = false;
$wholesale = Wholesale::find(Route::Input('wholesale_id'));
if (!$wholesale || $wholesale->user_id) {
	$common_access_error = true;
}
?>

@extends('layout.master')

@section('title', 'Leverancierdetails')

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
<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			<div>
			<ol class="breadcrumb">
			  <li><a href="/">Dashboard</a></li>
			  <li><a href="/purchase">Inkoopfacturen</a></li>
			  <li><a href="/wholesale">Leveranciers</a></li>
			 <li>{{ $wholesale->company_name }}</li>
			</ol>
			<div>
			<br>

			<h2><strong>Leverancier</strong> {{ $wholesale->company_name }}</h2>


			<div class="white-row">

				<form method="POST" action="/wholesale/update" accept-charset="UTF-8">
                {!! csrf_field() !!}
                <input type="hidden" name="id" id="id" value="{{ $wholesale->id }}"/>
				<h4 class="company">Bedrijfsgegevens</h4>
				<div class="row company">

					<div class="col-md-5">
						<div class="form-group">
							<label for="company_name">Bedrijfsnaam</label>
							<input name="company_name" id="company_name" disabled="disabled" type="text" value="{{ $wholesale->company_name }}" class="form-control" />
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="company_type">Bedrijfstype</label>
							<input name="company_type" id="company_type" disabled="disabled" type="text" value="{{ ucwords(WholesaleType::find($wholesale->type_id)->type_name) }}" class="form-control" />
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label for="website">Website</label>
							<input name="website" id="website" type="url" disabled="disabled" value="{{ $wholesale->website }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="telephone_comp">Telefoonnummer</label>
							<input name="telephone_comp" id="telephone_comp" disabled="disabled" type="text" maxlength="12" value="{{ $wholesale->phone }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="email_comp">Email</label>
							<input name="email_comp" id="email_comp" type="email" disabled="disabled" value="{{ $wholesale->email }}" class="form-control"/>
						</div>
					</div>

				</div>

				<h4>Adresgegevens</h4>
				<div class="row">

					<div class="col-md-4">
						<div class="form-group">
							<label for="street">Straat</label>
							<input name="street" id="street" type="text" disabled="disabled" value="{{ $wholesale->address_street }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-1">
						<div class="form-group">
							<label for="address_number">Huis nr.</label>
							<input name="address_number" id="address_number" disabled="disabled" type="text" value="{{ $wholesale->address_number }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="zipcode">Postcode</label>
							<input name="zipcode" id="zipcode" maxlength="6" disabled="disabled" type="text" value="{{ $wholesale->address_postal }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="city">Plaats</label>
							<input name="city" id="city" type="text" disabled="disabled" value="{{ $wholesale->address_city }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="province">Provincie</label>
							<input name="province" id="province" type="text" disabled="disabled" value="{{ ucwords(Province::find($wholesale->province_id)->province_name) }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label for="country">Land</label>
							<input name="country" id="country" type="text" disabled="disabled" value="{{ ucwords(Country::find($wholesale->country_id)->country_name) }}" class="form-control"/>
						</div>
					</div>

				</div>
			</form>

			</div>

		</div>

	</section>

</div>
@stop

<?php } ?>
