@extends('layout.master')

@section('content')
<?# -- WRAPPER -- ?>
<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			<h2><strong>Nieuw</strong> project</h2>

			<form action="#" method="post">
				<h4>Projectgegevens</h4>
				<div class="row">

					<div class="col-md-6">
						<div class="form-group">
							<label for="name">Projectnaam</label>
							<input name="name" id="name" type="text" value="" class="form-control" />
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="contractor">Opdrachtgever</label>
							<select name="contractor" id="contractor" class="form-control pointer">
								<option value="" selected="selected"></option>
							</select>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<label for="type">Type</label>
							<select name="type" id="type" class="form-control pointer">
								<option value="" selected="selected"></option>
							</select>
						</div>
					</div>

				</div>

				<h4>Project adresgegevens</h4>
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

				<h4>Financieel</h4>
				<div class="tabs nomargin-top">

					<?# -- tabs -- ?>
					<ul class="nav nav-tabs">
						<li class="active">
							<a href="#calc" data-toggle="tab">Calculatie</a>
						</li>
						<li>
							<a href="#more" data-toggle="tab">Meerwerk</a>
						</li>
					</ul>

					<?# -- tabs content -- ?>
					<div class="tab-content">
						<div id="calc" class="tab-pane active">
							<h5><strong>Eigen uurtarief</strong></h5>
							<div class="row">
								<div class="col-md-3"><label for="hour_rate">Uurtarief excl. BTW</label></div>
								<div class="col-md-1"><div class="pull-right">&euro;</div></div>
								<div class="col-md-2">
									<input name="hour_rate" id="hour_rate" type="number" min="0" max="1000" value="" class="form-control"/>
								</div>
							</div>

							<h5><strong>Aanneming</strong></h5>
							<div class="row">
								<div class="col-md-3"><label for="profit_material_1">Winst materiaal</label></div>
								<div class="col-md-1"><div class="pull-right">%</div></div>
								<div class="col-md-2">
									<input name="profit_material_1" id="profit_material_1" type="number" min="0" max="200" value="" class="form-control"/>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3"><label for="profit_equipment_1">Winst materieel</label></div>
								<div class="col-md-1"><div class="pull-right">%</div></div>
								<div class="col-md-2">
									<input name="profit_equipment_1" id="profit_equipment_1" type="number" min="0" max="200" value="" class="form-control"/>
								</div>
							</div>

							<h5><strong>Onderaanneming</strong></h5>
							<div class="row">
								<div class="col-md-3"><label for="profit_material_2">Winst materiaal</label></div>
								<div class="col-md-1"><div class="pull-right">%</div></div>
								<div class="col-md-2">
									<input name="profit_material_2" id="profit_material_2" type="number" min="0" max="200" value="" class="form-control"/>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3"><label for="profit_equipment_2">Winst materieel</label></div>
								<div class="col-md-1"><div class="pull-right">%</div></div>
								<div class="col-md-2">
									<input name="profit_equipment_2" id="profit_equipment_2" type="number" min="0" max="200" value="" class="form-control"/>
								</div>
							</div>

							<h5><strong>Stelpost</strong></h5>
							<div class="row">
								<div class="col-md-3"><label for="profit_material_3">Winst materiaal</label></div>
								<div class="col-md-1"><div class="pull-right">%</div></div>
								<div class="col-md-2">
									<input name="profit_material_3" id="profit_material_3" type="number" min="0" max="200" value="" class="form-control"/>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3"><label for="profit_equipment_3">Winst materieel</label></div>
								<div class="col-md-1"><div class="pull-right">%</div></div>
								<div class="col-md-2">
									<input name="profit_equipment_3" id="profit_equipment_3" type="number" min="0" max="200" value="" class="form-control"/>
								</div>
							</div>

						</div>

						<div id="more" class="tab-pane">
							<h5><strong>Eigen uurtarief</strong></h5>
							<div class="row">
								<div class="col-md-3"><label for="more_hour_rate">Uurtarief excl. BTW</label></div>
								<div class="col-md-1"><div class="pull-right">&euro;</div></div>
								<div class="col-md-2">
									<input name="more_hour_rate" id="more_hour_rate" type="number" min="0" max="1000" value="" class="form-control"/>
								</div>
							</div>

							<h5><strong>Aanneming</strong></h5>
							<div class="row">
								<div class="col-md-3"><label for="more_profit_material_1">Winst materiaal</label></div>
								<div class="col-md-1"><div class="pull-right">%</div></div>
								<div class="col-md-2">
									<input name="more_profit_material_1" id="more_profit_material_1" type="number" min="0" max="200" value="" class="form-control"/>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3"><label for="more_profit_equipment_1">Winst materieel</label></div>
								<div class="col-md-1"><div class="pull-right">%</div></div>
								<div class="col-md-2">
									<input name="more_profit_equipment_1" id="more_profit_equipment_1" type="number" min="0" max="200" value="" class="form-control"/>
								</div>
							</div>

							<h5><strong>Onderaanneming</strong></h5>
							<div class="row">
								<div class="col-md-3"><label for="more_profit_material_2">Winst materiaal</label></div>
								<div class="col-md-1"><div class="pull-right">%</div></div>
								<div class="col-md-2">
									<input name="more_profit_material_2" id="more_profit_material_2" type="number" min="0" max="200" value="" class="form-control"/>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3"><label for="more_profit_equipment_2">Winst materieel</label></div>
								<div class="col-md-1"><div class="pull-right">%</div></div>
								<div class="col-md-2">
									<input name="more_profit_equipment_2" id="more_profit_equipment_2" type="number" min="0" max="200" value="" class="form-control"/>
								</div>
							</div>

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
