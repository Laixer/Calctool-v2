@extends('layout.master')

@section('content')
<?# -- WRAPPER -- ?>
<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			<h2><strong>Nieuwe</strong> relatie</h2>

			<form action="#" method="post">
				<div class="row">

					<div class="col-md-4">
						<div class="form-group">
							<label for="relationkind">Relatiesoort</label>
							<select name="relationkind" id="relationkind" class="form-control pointer">
								<option value="" selected="selected"></option>
							</select>
						</div>
					</div>

				</div>

				<h4>Algemeen</h4>
				<div class="row">

					<div class="col-md-8">
						<div class="form-group">
							<label for="company_name">Bedrijfsnaam</label>
							<input name="company_name" id="company_name" type="text" value="" class="form-control" />
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="company_type">Bedrijfstype</label>
							<select name="company_type" id="company_type" class="form-control pointer">
								<option value="" selected="selected"></option>
							</select>
						</div>
					</div>

				</div>

				<h4>Adresgegevens</h4>
				<div class="row">

					<div class="col-md-4">
						<div class="form-group">
							<label for="street">Straat</label>
							<input name="street" id="street" type="text" value="" class="form-control"/>
						</div>
					</div>

					<div class="col-md-1">
						<div class="form-group">
							<label for="address_number">Huisnr</label>
							<input name="address_number" id="address_number" type="text" value="" class="form-control"/>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="zipcode">Postcode</label>
							<input name="zipcode" id="zipcode" type="text" value="" class="form-control"/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="city">Plaats</label>
							<input name="city" id="city" type="text" value="" class="form-control"/>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="province">Provincie</label>
							<select name="province" id="province" class="form-control pointer">
								<option value="" selected="selected"></option>
							</select>
						</div>
					</div>

				</div>

				<h4>Bedrijfsgegevens</h4>
				<div class="row">

					<div class="col-md-3">
						<div class="form-group">
							<label for="kvk">KVK nr</label>
							<input name="kvk" id="kvk" type="number" value="" class="form-control"/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="btw">BTW nr</label>
							<input name="btw" id="btw" type="text" value="" class="form-control"/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="iban">IBAN</label>
							<input name="iban" id="iban" type="text" value="" class="form-control"/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="debtor">Debiteurnr</label>
							<input name="debtor" id="debtor" type="text" value="" class="form-control"/>
						</div>
					</div>

				</div>

				<h4>Contactgegevens</h4>
				<div class="row">

					<div class="col-md-3">
						<div class="form-group">
							<label for="contact_name">Naam</label>
							<input name="contact_name" id="contact_name" type="text" value="" class="form-control"/>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="contact_firstname">Voornaam</label>
							<input name="contact_firstname" id="contact_firstname" type="text" value="" class="form-control"/>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="mobile">Mobiel</label>
							<input name="mobile" id="mobile" type="text" value="" class="form-control"/>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="telephone">Vast</label>
							<input name="telephone" id="telephone" type="text" value="" class="form-control"/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="email">Email</label>
							<input name="email" id="email" type="email" value="" class="form-control"/>
						</div>
					</div>

				</div>

				<h4>Opmerkingen</h4>
				<div class="row">
					<div class="form-group">
						<div class="col-md-12">
							<textarea name="note" id="note" rows="10" class="form-control"></textarea>
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

	</section>

</div>
<!-- /WRAPPER -->
@stop
