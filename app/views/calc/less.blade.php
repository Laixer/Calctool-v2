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
						<li data-target="#step4" class="complete">Stelpost<span class="chevron"></span></li>
						<li data-target="#step5" class="active">Minderwerk<span class="chevron"></span></li>
						<li data-target="#step6">Meerwerk<span class="chevron"></span></li>
						<li data-target="#step7">Factuur<span class="chevron"></span></li>
						<li data-target="#step8">Winst/Verlies<span class="chevron"></span></li>
					</ul>
				</div>
			</div>

			<hr />

			<h2><strong>Minderwerk</strong></h2>

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

												<h4>Arbeid</h4>
												<table class="table table-striped">
													<?# -- table head -- ?>
													<thead>
														<tr>
															<th class="col-md-2">Eenheid</th>
															<th class="col-md-1">Tarief</th>
															<th class="col-md-1">Arbeidsuren</th>
															<th class="col-md-1">Gecalculeerd</th>
															<th class="col-md-2">&nbsp;</th>
															<th class="col-md-1">&nbsp;</th>
															<th class="col-md-2">&nbsp;</th>
															<th class="col-md-1">Minderwerk</th>
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
															<td class="col-md-2">&nbsp;</td>
															<td class="col-md-1">&nbsp;</td>
															<td class="col-md-2">&nbsp;</td>
															<td class="col-md-1 text-danger">$-734,72</td>
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
															<th class="col-md-3">Gecalculeerd Incl. Winst</th>
															<th class="col-md-1">Minderwerk</th>
															<th class="col-md-1">&nbsp;</th>
														</tr>
													</thead>

													<!-- table items -->
													<tbody>
														<tr>
															<td class="col-md-3">Kaas</td>
															<td class="col-md-2">Stuk</td>
															<td class="col-md-1"><input name="name" id="name" type="text" value="7,60" class="form-control control-sm" /></td>
															<td class="col-md-1"><input name="name" id="name" type="number" min="0" value="3" class="form-control control-sm" /></td>
															<td class="col-md-3">$40</td>
															<td class="col-md-1 text-danger">$-72,82</td>
															<td class="col-md-1"><button class="btn btn-warning btn-xs fa fa-undo"></button></td>
														</tr>
														<tr>
															<td class="col-md-3">Kaas</td>
															<td class="col-md-2">Stuk</td>
															<td class="col-md-1"><input name="name" id="name" type="text" value="7,60" class="form-control control-sm" /></td>
															<td class="col-md-1"><input name="name" id="name" type="number" min="0" value="3" class="form-control control-sm" /></td>
															<td class="col-md-3">$40</td>
															<td class="col-md-1 text-danger">$-72,82</td>
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
															<th class="col-md-3">Gecalculeerd Incl. Winst</th>
															<th class="col-md-1">Minderwerk</th>
															<th class="col-md-1">&nbsp;</th>
														</tr>
													</thead>

													<!-- table items -->
													<tbody>
														<tr>
															<td class="col-md-3">Kaas</td>
															<td class="col-md-2">Stuk</td>
															<td class="col-md-1"><input name="name" id="name" type="text" value="7,60" class="form-control control-sm" /></td>
															<td class="col-md-1"><input name="name" id="name" type="number" min="0" value="3" class="form-control control-sm" /></td>
															<td class="col-md-3">$40</td>
															<td class="col-md-1 text-danger">$-72,82</td>
															<td class="col-md-1"><button class="btn btn-warning btn-xs fa fa-undo"></button></td>
														</tr>
														<tr>
															<td class="col-md-3">Kaas</td>
															<td class="col-md-2">Stuk</td>
															<td class="col-md-1"><input name="name" id="name" type="text" value="7,60" class="form-control control-sm" /></td>
															<td class="col-md-1"><input name="name" id="name" type="number" min="0" value="3" class="form-control control-sm" /></td>
															<td class="col-md-3">$40</td>
															<td class="col-md-1 text-danger">$-72,82</td>
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
						<div class="toogle">

							<div class="toggle active">
								<label>Aanneming</label>
								<div class="toggle-content">

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
												<th class="col-md-4">Totaal minderwerk (excl. BTW)</th>
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
												<td class="col-md-4">$742,28</td>
											</tr>
											<tr><!-- item -->
												<td class="col-md-2">&nbsp;</td>
												<td class="col-md-2">Werkzaamheid 2</td>
												<td class="col-md-1">6</td>
												<td class="col-md-1">$42</td>
												<td class="col-md-1">$83</td>
												<td class="col-md-1">$742</td>
												<td class="col-md-4">$742,28</td>
											</tr>
											<tr><!-- item -->
												<td class="col-md-2">&nbsp;</td>
												<td class="col-md-2">Werkzaamheid 3</td>
												<td class="col-md-1">6</td>
												<td class="col-md-1">$42</td>
												<td class="col-md-1">$83</td>
												<td class="col-md-1">$742</td>
												<td class="col-md-4">$742,28</td>
											</tr>
										</tbody>
									</table>

								</div>
							</div>

							<div class="toggle active">
								<label>Onderaanneming</label>
								<div class="toggle-content">
									<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas metus nulla, commodo a sodales sed, dignissim pretium nunc. Nam et lacus neque. Ut enim massa, sodales tempor convallis et, iaculis ac massa. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas metus nulla, commodo a sodales sed, dignissim pretium nunc. Nam et lacus neque. Ut enim massa, sodales tempor convallis et, iaculis ac massa.</p>
								</div>
							</div>

							<div class="toggle active">
								<label>Totalen project</label>
								<div class="toggle-content">
									<table class="table table-striped">
										<?# -- table head -- ?>
										<thead>
											<tr>
												<th class="col-md-4"><span class="pull-right">Arbeidsuren</span></th>
												<th class="col-md-2"><span class="pull-right">Arbeidskosten</span></th>
												<th class="col-md-2"><span class="pull-right">Materiaalkosten</span></th>
												<th class="col-md-2"><span class="pull-right">Materieelkosten</span></th>
												<th class="col-md-2"><span class="pull-right">Totaal (excl. BTW)</span></th>
											</tr>
										</thead>

										<!-- table items -->
										<tbody>
											<tr><!-- item -->
												<td class="col-md-4"><span class="pull-right">6</span></td>
												<td class="col-md-2"><span class="pull-right">$42</span></td>
												<td class="col-md-2"><span class="pull-right">$83</span></td>
												<td class="col-md-2"><span class="pull-right">$742</span></td>
												<td class="col-md-2"><span class="pull-right">$742,28</span></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>

						</div>
					</div>

					<div id="endresult" class="tab-pane">

						<h4>Aanneming</h4>
						<table class="table table-striped">
							<?# -- table head -- ?>
							<thead>
								<tr>
									<th class="col-md-4">&nbsp;</th>
									<th class="col-md-1">Manuren</th>
									<th class="col-md-2">Bedrag (excl. BTW)</th>
									<th class="col-md-1">&nbsp;</th>
									<th class="col-md-1">BTW</th>
									<th class="col-md-2">BTW bedrag</th>
									<th class="col-md-1">&nbsp;</th>
								</tr>
							</thead>

							<!-- table items -->
							<tbody>
								<tr><!-- item -->
									<td class="col-md-4">Arbeidskosten</td>
									<td class="col-md-1">6</td>
									<td class="col-md-2">$42</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-2">$742,28</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">6</td>
									<td class="col-md-2">$42</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-2">$742,28</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">6</td>
									<td class="col-md-2">$42</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>

								<tr><!-- item -->
									<td class="col-md-4">Materiaalkosten</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">$42</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-2">$742,28</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">$42</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-2">$742,28</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">$42</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>

								<tr><!-- item -->
									<td class="col-md-4">Materieelkosten</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">$42</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-2">$742,28</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">$42</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-2">$742,28</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">$42</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>

								<tr><!-- item -->
									<td class="col-md-4"><strong>Totaal Aanneming </strong></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1"><strong>$6.362,71</strong></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1"><strong>$6.362,71</strong></td>
								</tr>
							</tbody>
						</table>

						<h4>Onderaanneming</h4>
						<table class="table table-striped">
							<?# -- table head -- ?>
							<thead>
								<tr>
									<th class="col-md-4">&nbsp;</th>
									<th class="col-md-1">Manuren</th>
									<th class="col-md-2">Bedrag (excl. BTW)</th>
									<th class="col-md-1">&nbsp;</th>
									<th class="col-md-1">BTW</th>
									<th class="col-md-2">BTW bedrag</th>
									<th class="col-md-1">&nbsp;</th>
								</tr>
							</thead>

							<!-- table items -->
							<tbody>
								<tr><!-- item -->
									<td class="col-md-4">Arbeidskosten</td>
									<td class="col-md-1">6</td>
									<td class="col-md-2">$42</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-2">$742,28</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">6</td>
									<td class="col-md-2">$42</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-2">$742,28</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">6</td>
									<td class="col-md-2">$42</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>

								<tr><!-- item -->
									<td class="col-md-4">Materiaalkosten</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">$42</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-2">$742,28</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">$42</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-2">$742,28</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">$42</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>

								<tr><!-- item -->
									<td class="col-md-4">Materieelkosten</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">$42</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">21%</td>
									<td class="col-md-2">$742,28</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">$42</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">6%</td>
									<td class="col-md-2">$742,28</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-4">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">$42</td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-1">0%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1">&nbsp;</td>
								</tr>

								<tr><!-- item -->
									<td class="col-md-4"><strong>Totaal Aanneming </strong></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1"><strong>$6.362,71</strong></td>
									<td class="col-md-1">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-1"><strong>$6.362,71</strong></td>
								</tr>
							</tbody>
						</table>

						<h4>Cumulatieven Minderwerk</h4>
						<table class="table table-striped">
							<?# -- table head -- ?>
							<thead>
								<tr>
									<th class="col-md-6">&nbsp;</th>
									<th class="col-md-2">Bedrag (excl. BTW)</th>
									<th class="col-md-2">BTW bedrag</th>
									<th class="col-md-2">&nbsp;</th>
								</tr>
							</thead>

							<!-- table items -->
							<tbody>
								<tr><!-- item -->
									<td class="col-md-6">Calculatief te offereren (excl. BTW)</td>
									<td class="col-md-2">$42</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-6">BTW bedrag aanneming belast met 21%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-2">$42</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-6">BTW bedrag aanneming belast met 6%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-2">$42</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-6">BTW bedrag onderaanneming belast met 21%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-2">$42</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-6">BTW bedrag onderaanneming belast met 6%</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-2">$42</td>
									<td class="col-md-2">&nbsp;</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-6">Te offereren BTW bedrag</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-2">$3.826,38</td>
								</tr>
								<tr><!-- item -->
									<td class="col-md-6"><strong>Calculatief te factureren (Incl. BTW)</strong></td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-2">&nbsp;</td>
									<td class="col-md-2"><strong>$3.826,38</strong></td>
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
