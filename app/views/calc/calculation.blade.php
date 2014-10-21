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
						<li data-target="#step2" class="active">Calculatie<span class="chevron"></span></li>
						<li data-target="#step3">Offerte<span class="chevron"></span></li>
						<li data-target="#step4">Stelpost<span class="chevron"></span></li>
						<li data-target="#step5">Minderwerk<span class="chevron"></span></li>
						<li data-target="#step6">Meerwerk<span class="chevron"></span></li>
						<li data-target="#step7">Factuur<span class="chevron"></span></li>
						<li data-target="#step8">Winst/Verlies<span class="chevron"></span></li>
					</ul>
				</div>
			</div>

			<hr />

			<h2><strong>Calculeren</strong></h2>

			<div class="tabs nomargin">

				<!-- tabs -->
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#calculate" data-toggle="tab">
							<i class="fa fa-list-ol"></i> Calculeren
						</a>
					</li>
					<li>
						<a href="#summary" data-toggle="tab">
							<i class="fa fa-sort-amount-desc"></i> Uittrekstaat
						</a>
					</li>
					<li>
						<a href="#endresult" data-toggle="tab">
							<i class="fa fa-check-circle-o"></i> Eindresultaat
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

												<div class="col-md-12">

													<div class="col-md-10">
														<span class="pull-right">
															<div class="form-group">
																<label class="radio-inline"><input id="type" name="type" value="1" type="radio">Aanneming</label>
	    														<label class="radio-inline"><input id="type" name="type" value="2" type="radio">Onderaanneming</label>
															</div>
														</span>
													</div>
													<div class="col-md-2">
														<span class="pull-right">
															<label>Stelpost</label>
															<input type="checkbox" name="estimate" value="1" class="form-control">
														</span>
													</div>

												</div>

												<h4>Arbeid</h4>
												<table class="table table-striped">
													<?# -- table head -- ?>
													<thead>
														<tr>
															<th class="col-md-2">Eenheid</th>
															<th class="col-md-1">Tarief</th>
															<th class="col-md-1">Arbeidsuren</th>
															<th class="col-md-1">Arbeidskosten</th>
															<th class="col-md-2">&nbsp;</th>
															<th class="col-md-1">&nbsp;</th>
															<th class="col-md-2">&nbsp;</th>
															<th class="col-md-1">BTW</th>
															<th class="col-md-1">&nbsp;</th>
														</tr>
													</thead>

													<!-- table items -->
													<tbody>
														<tr><!-- item -->
															<td class="col-md-2">Per Uur</td>
															<td class="col-md-1">$1</td>
															<td class="col-md-1"><input name="name" id="name" type="text" value="" class="form-control control-sm" /></td>
															<td class="col-md-1">$1</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-2">&nbsp;</td>
															<td class="col-md-2">&nbsp;</td>
															<td class="col-md-1">
																<select name="type" id="type" class="form-control pointer control-sm">
																	<option value="" selected="selected"></option>
																</select>
															</td>
															<td class="col-md-1"><button class="btn btn-danger btn-xs fa fa-times"></button></td>
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
															<td class="col-md-1"><button class="btn btn-danger btn-xs fa fa-times"></button></td>
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
															<td class="col-md-1"><button class="btn btn-danger btn-xs fa fa-times"></button></td>
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
						<p>Maecenas metus nulla, commodo a sodales sed, dignissim pretium nunc. Nam et lacus neque. Ut enim massa, sodales tempor convallis et, iaculis ac massa. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
					</div>

					<div id="endresult" class="tab-pane">
						<p>Kaas</p>
					</div>
				</div>

			</div>


		</div>

	</section>

</div>
<!-- /WRAPPER -->
@stop
