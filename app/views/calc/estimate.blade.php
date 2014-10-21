@extends('layout.master')

@section('content')
<?# -- WRAPPER -- ?>
<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			<div class="fuelux">
				<div id="calculation-wizard" class="wizard">
					<ul class="steps">
						<li data-target="#step0">Home<span class="chevron"></span></li>
						<li data-target="#step1" class="complete">Projectgegevens<span class="chevron"></span></li>
						<li data-target="#step2" class="complete">Calculatie<span class="chevron"></span></li>
						<li data-target="#step3" class="complete">Offerte<span class="chevron"></span></li>
						<li data-target="#step4" class="active">Stelpost<span class="chevron"></span></li>
						<li data-target="#step5">Minderwerk<span class="chevron"></span></li>
						<li data-target="#step6">Meerwerk<span class="chevron"></span></li>
						<li data-target="#step7">Factuur<span class="chevron"></span></li>
						<li data-target="#step8">Winst/Verlies<span class="chevron"></span></li>
					</ul>
				</div>
			</div>

			<hr />

			<h2><strong>Stelpost</strong> stellen</h2>

			<div class="tabs nomargin">

				<!-- tabs -->
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#calculate" data-toggle="tab">
							<i class="fa fa-list-ol"></i> Stellen
						</a>
					</li>
					<li>
						<a href="#summary" data-toggle="tab">
							<i class="fa fa-sort-amount-desc"></i> Uittrekstaat
						</a>
					</li>
				</ul>

				<!-- tabs content -->
				<div class="tab-content">
					<div id="calculate" class="tab-pane active">
						<div class="toogle">

							<div class="toggle">
								<label>Hoofdstuk 1</label>
								<div class="toggle-content">

									<div class="toogle">

										<div class="toggle">
											<label>Werkzaamheid 1</label>
											<div class="toggle-content">

												<h4>Arbeid</h4>
												<table class="table table-striped">
													<?# -- table head -- ?>
													<thead>
														<tr>
															<th class="col-md-2">Datum</th>
															<th class="col-md-1">Tarief</th>
															<th class="col-md-2">Eenheid</th>
															<th class="col-md-1">Arbeidsuren</th>
															<th class="col-md-1">Arbeidskosten</th>
															<th class="col-md-3">Opmerking</th>
															<th class="col-md-1">BTW</th>
															<th class="col-md-1">&nbsp;</th>
														</tr>
													</thead>

													<!-- table items -->
													<tbody>
														<tr><!-- item -->
															<td class="col-md-2"><input name="date" id="date" type="date" value="" class="form-control control-sm" /></td>
															<td class="col-md-1">$1</td>
															<td class="col-md-2">Per Uur</td>
															<td class="col-md-1"><input name="name" id="name" type="text" value="" class="form-control control-sm" /></td>
															<td class="col-md-1">$1</td>
															<td class="col-md-3"><input name="name" id="name" type="text" value="" class="form-control control-sm" /></td>
															<td class="col-md-1">
																<select name="type" id="type" class="form-control pointer control-sm">
																	<option value="" selected="selected"></option>
																</select>
															</td>
															<td class="col-md-1"><button class="btn btn-danger btn-xs fa fa-times"></button></td>
														</tr>
														<tr><!-- item -->
															<td class="col-md-2"><input name="date" id="date" type="date" value="" class="form-control control-sm" /></td>
															<td class="col-md-1">$1</td>
															<td class="col-md-2">Per Uur</td>
															<td class="col-md-1"><input name="name" id="name" type="text" value="" class="form-control control-sm" /></td>
															<td class="col-md-1">$1</td>
															<td class="col-md-3"><input name="name" id="name" type="text" value="" class="form-control control-sm" /></td>
															<td class="col-md-1">
																<select name="type" id="type" class="form-control pointer control-sm">
																	<option value="" selected="selected"></option>
																</select>
															</td>
															<td class="col-md-1"><button class="btn btn-warning btn-xs fa fa-undo"></button></td>
														</tr>
													</tbody>
												</table>

												<h4>Materiaal</h4>
												<table class="table table-striped">
													<?# -- table head -- ?>
													<thead>
														<tr>
															<th class="col-md-3">Materiaalsoort</th>
															<th class="col-md-2">Eenheid</th>
															<th class="col-md-1">Prijs/eenheid</th>
															<th class="col-md-1">Hoeveelheid</th>
															<th class="col-md-1">Totaalprijs</th>
															<th class="col-md-2">Incl. Winst</th>
															<th class="col-md-1">BTW</th>
															<th class="col-md-1">&nbsp;</th>
														</tr>
													</thead>

													<!-- table items -->
													<tbody>
														<tr>
															<td class="col-md-3"><input name="name" id="name" type="text" value="" class="form-control control-sm" /></td>
															<td class="col-md-2"><input name="name" id="name" type="text" value="" class="form-control control-sm" /></td>
															<td class="col-md-1"><input name="name" id="name" type="text" value="" class="form-control control-sm" /></td>
															<td class="col-md-1"><input name="name" id="name" type="number" min="0" value="" class="form-control control-sm" /></td>
															<td class="col-md-1 centering">$20.000,00</td>
															<td class="col-md-2">$40</td>
															<td class="col-md-1">
																<select name="type" id="type" class="form-control pointer control-sm">
																	<option value="" selected="selected">0</option>
																	<option value="">9</option>
																	<option value="">21</option>
																</select>
															</td>
															<td class="col-md-1"><button class="btn btn-danger btn-xs fa fa-times"></button></td>
														</tr>
														<tr>
															<td class="col-md-3"><input name="name" id="name" type="text" value="" class="form-control control-sm" /></td>
															<td class="col-md-2"><input name="name" id="name" type="text" value="" class="form-control control-sm" /></td>
															<td class="col-md-1"><input name="name" id="name" type="text" value="" class="form-control control-sm" /></td>
															<td class="col-md-1"><input name="name" id="name" type="number" min="0" value="" class="form-control control-sm" /></td>
															<td class="col-md-1">$20.000,00</td>
															<td class="col-md-2">$40</td>
															<td class="col-md-1">
																<select name="type" id="type" class="form-control pointer control-sm">
																	<option value="" selected="selected">0</option>
																	<option value="">9</option>
																	<option value="">21</option>
																</select>
															</td>
															<td class="col-md-1"><button class="btn btn-warning btn-xs fa fa-undo"></button></td>
														</tr>
													</tbody>
												</table>

												<h4>Materieel</h4>
												<table class="table table-striped">
													<?# -- table head -- ?>
													<thead>
														<tr>
															<th class="col-md-3">Materiaalsoort</th>
															<th class="col-md-2">Eenheid</th>
															<th class="col-md-1">Prijs/eenheid</th>
															<th class="col-md-1">Hoeveelheid</th>
															<th class="col-md-1">Totaalprijs</th>
															<th class="col-md-2">Incl. Winst</th>
															<th class="col-md-1">BTW</th>
															<th class="col-md-1">&nbsp;</th>
														</tr>
													</thead>

													<!-- table items -->
													<tbody>
														<tr>
															<td class="col-md-3"><input name="name" id="name" type="text" value="" class="form-control control-sm" /></td>
															<td class="col-md-2"><input name="name" id="name" type="text" value="" class="form-control control-sm" /></td>
															<td class="col-md-1"><input name="name" id="name" type="text" value="" class="form-control control-sm" /></td>
															<td class="col-md-1"><input name="name" id="name" type="number" min="0" value="" class="form-control control-sm" /></td>
															<td class="col-md-1">$20.000,00</td>
															<td class="col-md-2">$40</td>
															<td class="col-md-1">
																<select name="type" id="type" class="form-control pointer control-sm">
																	<option value="" selected="selected">0</option>
																	<option value="">9</option>
																	<option value="">21</option>
																</select>
															</td>
															<td class="col-md-1"><button class="btn btn-danger btn-xs fa fa-times"></button></td>
														</tr>
														<tr>
															<td class="col-md-3"><input name="name" id="name" type="text" value="" class="form-control control-sm" /></td>
															<td class="col-md-2"><input name="name" id="name" type="text" value="" class="form-control control-sm" /></td>
															<td class="col-md-1"><input name="name" id="name" type="text" value="" class="form-control control-sm" /></td>
															<td class="col-md-1"><input name="name" id="name" type="number" min="0" value="" class="form-control control-sm" /></td>
															<td class="col-md-1">$20.000,00</td>
															<td class="col-md-2">$40</td>
															<td class="col-md-1">
																<select name="type" id="type" class="form-control pointer control-sm">
																	<option value="" selected="selected">0</option>
																	<option value="">9</option>
																	<option value="">21</option>
																</select>
															</td>
															<td class="col-md-1"><button class="btn btn-warning btn-xs fa fa-undo"></button></td>
														</tr>
													</tbody>
												</table>

											</div>
										</div>

										<div class="toggle">
											<label>Werkzaamheid 2</label>
											<div class="toggle-content">
												<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas metus nulla, commodo a sodales sed, dignissim pretium nunc. Nam et lacus neque. Ut enim massa, sodales tempor convallis et, iaculis ac massa. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas metus nulla, commodo a sodales sed, dignissim pretium nunc. Nam et lacus neque. Ut enim massa, sodales tempor convallis et, iaculis ac massa.</p>
											</div>
										</div>

										<div class="toggle">
											<label>Werkzaamheid 3</label>
											<div class="toggle-content">
												<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas metus nulla, commodo a sodales sed, dignissim pretium nunc. Nam et lacus neque. Ut enim massa, sodales tempor convallis et, iaculis ac massa. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas metus nulla, commodo a sodales sed, dignissim pretium nunc. Nam et lacus neque. Ut enim massa, sodales tempor convallis et, iaculis ac massa.</p>
											</div>
										</div>

									</div>

								</div>
							</div>

							<div class="toggle">
								<label>Hoofdstuk 2</label>
								<div class="toggle-content">
									<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas metus nulla, commodo a sodales sed, dignissim pretium nunc. Nam et lacus neque. Ut enim massa, sodales tempor convallis et, iaculis ac massa. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas metus nulla, commodo a sodales sed, dignissim pretium nunc. Nam et lacus neque. Ut enim massa, sodales tempor convallis et, iaculis ac massa.</p>
								</div>
							</div>

							<div class="toggle">
								<label>Hoofdstuk 3</label>
								<div class="toggle-content">
									<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas metus nulla, commodo a sodales sed, dignissim pretium nunc. Nam et lacus neque. Ut enim massa, sodales tempor convallis et, iaculis ac massa. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas metus nulla, commodo a sodales sed, dignissim pretium nunc. Nam et lacus neque. Ut enim massa, sodales tempor convallis et, iaculis ac massa.</p>
								</div>
							</div>

						</div>
					</div>

					<div id="summary" class="tab-pane">
						<table class="table table-striped">
							<?# -- table head -- ?>
							<thead>
								<tr>
									<th class="col-md-2">&nbsp;</th>
									<th class="col-md-2">&nbsp;</th>
									<th class="col-md-1">Arbeidsuren</th>
									<th class="col-md-1">Arbeidskosten</th>
									<th class="col-md-1">Materiaalkosten</th>
									<th class="col-md-1">Materieelkosten</th>
									<th class="col-md-3">Totaal (excl. BTW)</th>
									<th class="col-md-1">Afwijking</th>
								</tr>
							</thead>

							<!-- table items -->
							<tbody>
								<tr><!-- item -->
									<td class="col-md-2"><strong>Hoofdstuk 1</strong></td>
									<td class="col-md-2">Werkzaamheid 1</td>
									<td class="col-md-1">6</td>
									<td class="col-md-1">$42</td>
									<td class="col-md-1">$83</td>
									<td class="col-md-1">$742</td>
									<td class="col-md-3">$742,28</td>
									<td class="col-md-1 text-danger">73.234%</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-2">Werkzaamheid 2</td>
									<td class="col-md-1">6</td>
									<td class="col-md-1">$42</td>
									<td class="col-md-1">$83</td>
									<td class="col-md-1">$742</td>
									<td class="col-md-3">$742,28</td>
									<td class="col-md-1 text-success">23.842%</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-2">Werkzaamheid 3</td>
									<td class="col-md-1">6</td>
									<td class="col-md-1">$42</td>
									<td class="col-md-1">$83</td>
									<td class="col-md-1">$742</td>
									<td class="col-md-3">$742,28</td>
									<td class="col-md-1 text-success">746.23%</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

			</div>


		</div>

	</section>

</div>
<!-- /WRAPPER -->
@stop
