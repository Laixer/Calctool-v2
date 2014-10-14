@extends('layout.master')

@section('content')
<?# -- WRAPPER -- ?>
<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			<h2><strong>Calculeren</strong></h2>

			<div class="tabs nomargin">

				<!-- tabs -->
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#calculate" data-toggle="tab">
							<i class="fa fa-heart"></i> Calculeren
						</a>
					</li>
					<li>
						<a href="#summary" data-toggle="tab">
							<i class="fa fa-cogs"></i> Uittrekstaat
						</a>
					</li>
					<li>
						<a href="#endresult" data-toggle="tab">
							<i class="fa fa-cogs"></i> Eindresultaat
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
															<th class="col-md-2">&nbsp;</th>
															<th class="col-md-2">&nbsp;</th>
															<th class="col-md-2">Eenheid</th>
															<th class="col-md-1">Tarief</th>
															<th class="col-md-1">Arbeidsuren</th>
															<th class="col-md-1">Arbeidskosten</th>
															<th class="col-md-2">&nbsp;</th>
															<th class="col-md-1">BTW</th>
														</tr>
													</thead>

													<!-- table items -->
													<tbody>
														<tr><!-- item -->
															<td class="col-md-2">&nbsp;</td>
															<td class="col-md-2">&nbsp;</td>
															<td class="col-md-2">Per Uur</td>
															<td class="col-md-1">$1</td>
															<td class="col-md-1"><input name="name" id="name" type="text" value="" class="form-control" /></td>
															<td class="col-md-1">$1</td>
															<td class="col-md-2">&nbsp;</td>
															<td class="col-md-1">
																<select name="type" id="type" class="form-control pointer">
																	<option value="" selected="selected"></option>
																</select>
															</td>
														</tr>
													</tbody>
												</table>

												<h4>Materiaal</h4>
												<table class="table table-striped">
													<?# -- table head -- ?>
													<thead>
														<tr>
															<th class="col-md-4">Materiaalsoort</th>
															<th class="col-md-2">Eenheid</th>
															<th class="col-md-1">Prijs/eenheid</th>
															<th class="col-md-1">Hoeveelheid</th>
															<th class="col-md-1">Totaalprijs</th>
															<th class="col-md-2">Incl. Winst</th>
															<th class="col-md-1">BTW</th>
														</tr>
													</thead>

													<!-- table items -->
													<tbody>
														<tr><!-- item -->
															<td class="col-md-4"><input name="name" id="name" type="text" value="" class="form-control" /></td>
															<td class="col-md-2"><input name="name" id="name" type="text" value="" class="form-control" /></td>
															<td class="col-md-1"><input name="name" id="name" type="text" value="" class="form-control" /></td>
															<td class="col-md-1"><input name="name" id="name" type="number" min="0" value="" class="form-control" /></td>
															<td class="col-md-1">$20.000,00</td>
															<td class="col-md-2">$40</td>
															<td class="col-md-1">
																<select name="type" id="type" class="form-control pointer">
																	<option value="" selected="selected">0</option>
																	<option value="" selected="selected">9</option>
																	<option value="" selected="selected">21</option>
																</select>
															</td>
														</tr>
														<tr><!-- item -->
															<td class="col-md-4"><input name="name" id="name" type="text" value="" class="form-control" /></td>
															<td class="col-md-2"><input name="name" id="name" type="text" value="" class="form-control" /></td>
															<td class="col-md-1"><input name="name" id="name" type="text" value="" class="form-control" /></td>
															<td class="col-md-1"><input name="name" id="name" type="number" min="0" value="" class="form-control" /></td>
															<td class="col-md-1">$20.000,00</td>
															<td class="col-md-2">$40</td>
															<td class="col-md-1">
																<select name="type" id="type" class="form-control pointer">
																	<option value="" selected="selected">0</option>
																	<option value="" selected="selected">9</option>
																	<option value="" selected="selected">21</option>
																</select>
															</td>
														</tr>
													</tbody>
												</table>

												<h4>Materieel</h4>
												<table class="table table-striped">
													<?# -- table head -- ?>
													<thead>
														<tr>
															<th class="col-md-4">Materiaalsoort</th>
															<th class="col-md-2">Eenheid</th>
															<th class="col-md-1">Prijs/eenheid</th>
															<th class="col-md-1">Hoeveelheid</th>
															<th class="col-md-1">Totaalprijs</th>
															<th class="col-md-2">Incl. Winst</th>
															<th class="col-md-1">BTW</th>
														</tr>
													</thead>

													<!-- table items -->
													<tbody>
														<tr><!-- item -->
															<td class="col-md-4"><input name="name" id="name" type="text" value="" class="form-control" /></td>
															<td class="col-md-2"><input name="name" id="name" type="text" value="" class="form-control" /></td>
															<td class="col-md-1"><input name="name" id="name" type="text" value="" class="form-control" /></td>
															<td class="col-md-1"><input name="name" id="name" type="number" min="0" value="" class="form-control" /></td>
															<td class="col-md-1">$20.000,00</td>
															<td class="col-md-2">$40</td>
															<td class="col-md-1">
																<select name="type" id="type" class="form-control pointer">
																	<option value="" selected="selected">0</option>
																	<option value="" selected="selected">9</option>
																	<option value="" selected="selected">21</option>
																</select>
															</td>
														</tr>
														<tr><!-- item -->
															<td class="col-md-4"><input name="name" id="name" type="text" value="" class="form-control" /></td>
															<td class="col-md-2"><input name="name" id="name" type="text" value="" class="form-control" /></td>
															<td class="col-md-1"><input name="name" id="name" type="text" value="" class="form-control" /></td>
															<td class="col-md-1"><input name="name" id="name" type="number" min="0" value="" class="form-control" /></td>
															<td class="col-md-1">$20.000,00</td>
															<td class="col-md-2">$40</td>
															<td class="col-md-1">
																<select name="type" id="type" class="form-control pointer">
																	<option value="" selected="selected">0</option>
																	<option value="" selected="selected">9</option>
																	<option value="" selected="selected">21</option>
																</select>
															</td>
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
