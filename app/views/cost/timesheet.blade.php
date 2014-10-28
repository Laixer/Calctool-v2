@extends('layout.master')

@section('content')
<?# -- WRAPPER -- ?>
<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			<h2><strong>Urenregistratie</strong></h2>

			<div class="tabs nomargin">

				<!-- tabs -->
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#hour" data-toggle="tab">
							<i class="fa fa-calendar"></i> Urenregistratie
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
					<div id="hour" class="tab-pane active">
						<div class="toogle">

							<div class="toggle">
								<label>Twee weken geleden</label>
								<div class="toggle-content">
									<table class="table table-striped">
										<?# -- table head -- ?>
										<thead>
											<tr>
												<th class="col-md-1">Datum</th>
												<th class="col-md-1">Uren</th>
												<th class="col-md-1">Soort</th>
												<th class="col-md-1">BTW</th>
												<th class="col-md-2">Hoofdstuk</th>
												<th class="col-md-4">Werkzaamheid</th>
												<th class="col-md-1">&nbsp;</th>
												<th class="col-md-1">&nbsp;</th>
											</tr>
										</thead>

										<!-- table items -->
										<tbody>
											<tr><!-- item -->
												<td class="col-md-1">5-7-2014</td>
												<td class="col-md-1">5</td>
												<td class="col-md-1">Meerwerk</td>
												<td class="col-md-1">21%</td>
												<td class="col-md-2">Badkamer</td>
												<td class="col-md-4">Vervangen van oude cementvloer</td>
												<td class="col-md-1"><button class="btn btn-primary btn-xs fa fa-comment-o"> Notitie</button></td>
												<td class="col-md-1"><button class="btn btn-danger btn-xs fa fa-times"></button></td>
											</tr>
											<tr><!-- item -->
												<td class="col-md-1">5-7-2014</td>
												<td class="col-md-1">5</td>
												<td class="col-md-1">Meerwerk</td>
												<td class="col-md-1">21%</td>
												<td class="col-md-2">Badkamer</td>
												<td class="col-md-4">Vervangen van oude cementvloer</td>
												<td class="col-md-1"><button class="btn btn-primary btn-xs fa fa-comment-o"> Notitie</button></td>
												<td class="col-md-1"><button class="btn btn-danger btn-xs fa fa-times"></button></td>
											</tr>
											<tr><!-- item -->
												<td class="col-md-1">5-7-2014</td>
												<td class="col-md-1">5</td>
												<td class="col-md-1">Meerwerk</td>
												<td class="col-md-1">21%</td>
												<td class="col-md-2">Badkamer</td>
												<td class="col-md-4">Vervangen van oude cementvloer</td>
												<td class="col-md-1"><button class="btn btn-primary btn-xs fa fa-comment-o"> Notitie</button></td>
												<td class="col-md-1"><button class="btn btn-danger btn-xs fa fa-times"></button></td>
											</tr>
										</tbody>
									</table>
								</div>

							</div>

							<div class="toggle">
								<label>Vorige week</label>
								<div class="toggle-content">
									<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas metus nulla, commodo a sodales sed, dignissim pretium nunc. Nam et lacus neque. Ut enim massa, sodales tempor convallis et, iaculis ac massa. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas metus nulla, commodo a sodales sed, dignissim pretium nunc. Nam et lacus neque. Ut enim massa, sodales tempor convallis et, iaculis ac massa.</p>
								</div>
							</div>

							<div class="toggle">
								<label>Deze week</label>
								<div class="toggle-content">
									<table class="table table-striped">
										<?# -- table head -- ?>
										<thead>
											<tr>
												<th class="col-md-1">Datum</th>
												<th class="col-md-1">Uren</th>
												<th class="col-md-1">Soort</th>
												<th class="col-md-1">BTW</th>
												<th class="col-md-2">Hoofdstuk</th>
												<th class="col-md-4">Werkzaamheid</th>
												<th class="col-md-1">&nbsp;</th>
												<th class="col-md-1">&nbsp;</th>
											</tr>
										</thead>

										<!-- table items -->
										<tbody>
											<tr><!-- item -->
												<td class="col-md-1">5-7-2014</td>
												<td class="col-md-1">5</td>
												<td class="col-md-1">Meerwerk</td>
												<td class="col-md-1">21%</td>
												<td class="col-md-2">Badkamer</td>
												<td class="col-md-4">Vervangen van oude cementvloer</td>
												<td class="col-md-1"><button class="btn btn-primary btn-xs fa fa-comment-o"> Notitie</button></td>
												<td class="col-md-1"><button class="btn btn-danger btn-xs fa fa-times"></button></td>
											</tr>
											<tr><!-- item -->
												<td class="col-md-1">5-7-2014</td>
												<td class="col-md-1">5</td>
												<td class="col-md-1">Meerwerk</td>
												<td class="col-md-1">21%</td>
												<td class="col-md-2">Badkamer</td>
												<td class="col-md-4">Vervangen van oude cementvloer</td>
												<td class="col-md-1"><button class="btn btn-primary btn-xs fa fa-comment-o"> Notitie</button></td>
												<td class="col-md-1"><button class="btn btn-danger btn-xs fa fa-times"></button></td>
											</tr>
											<tr><!-- item -->
												<td class="col-md-1">5-7-2014</td>
												<td class="col-md-1">5</td>
												<td class="col-md-1">Meerwerk</td>
												<td class="col-md-1">21%</td>
												<td class="col-md-2">Badkamer</td>
												<td class="col-md-4">Vervangen van oude cementvloer</td>
												<td class="col-md-1"><button class="btn btn-primary btn-xs fa fa-comment-o"> Notitie</button></td>
												<td class="col-md-1"><button class="btn btn-danger btn-xs fa fa-times"></button></td>
											</tr>
											<tr><!-- item -->
												<td class="col-md-1"><input type="date" class="form-control control-sm"/></td>
												<td class="col-md-1"><input type="number" min="0" class="form-control control-sm"/></td>
												<td class="col-md-1">
													<select name="type" id="type" class="form-control pointer control-sm">
														<option value="" selected="selected">Aanneming</option>
														<option value="" selected="selected">Meerwerk</option>
														<option value="" selected="selected">Stelpost</option>
													</select>
												</td>
												<td class="col-md-1">
													<select name="type" id="type" class="form-control pointer control-sm">
														<option value="" selected="selected">21</option>
													</select>
												</td>
												<td class="col-md-2">
													<select name="type" id="type" class="form-control pointer control-sm">
														<option value="" selected="selected">Badkamer</option>
														<option value="" selected="selected">Vloer</option>
													</select>
												</td>
												<td class="col-md-4">
													<select name="type" id="type" class="form-control pointer control-sm">
														<option value="" selected="selected">Vervangen van vloer met cement</option>
													</select>
												</td>
												<td class="col-md-1"><button class="btn btn-primary btn-xs fa fa-comment-o"> Notitie</button></td>
												<td class="col-md-1">&nbsp;</button></td>
											</tr>
										</tbody>
									</table>
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
												<th class="col-md-4">&nbsp;</th>
												<th class="col-md-2">Gecalculeerde uren</th>
												<th class="col-md-2">Geregistreerde uren</th>
												<th class="col-md-2">Verschil</th>
											</tr>
										</thead>

										<!-- table items -->
										<tbody>
											<tr><!-- item -->
												<td class="col-md-2"><strong>Hoofdstuk 1</strong></td>
												<td class="col-md-4">Werkzaamheid 1</td>
												<td class="col-md-2">6</td>
												<td class="col-md-2">42</td>
												<td class="col-md-2">83</td>
											</tr>
											<tr><!-- item -->
												<td class="col-md-2">&nbsp;</td>
												<td class="col-md-4">Werkzaamheid 2</td>
												<td class="col-md-2">6</td>
												<td class="col-md-2">42</td>
												<td class="col-md-2">42</td>
											</tr>
											<tr><!-- item -->
												<td class="col-md-2">&nbsp;</td>
												<td class="col-md-4">Werkzaamheid 3</td>
												<td class="col-md-2">6</td>
												<td class="col-md-2">42</td>
												<td class="col-md-2">83</td>
											</tr>
										</tbody>
									</table>

								</div>
							</div>

							<div class="toggle active">
								<label>Meerwerk</label>
								<div class="toggle-content">
									<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas metus nulla, commodo a sodales sed, dignissim pretium nunc. Nam et lacus neque. Ut enim massa, sodales tempor convallis et, iaculis ac massa. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas metus nulla, commodo a sodales sed, dignissim pretium nunc. Nam et lacus neque. Ut enim massa, sodales tempor convallis et, iaculis ac massa.</p>
								</div>
							</div>

							<div class="toggle active">
								<label>Stelposten	</label>
								<div class="toggle-content">
									<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas metus nulla, commodo a sodales sed, dignissim pretium nunc. Nam et lacus neque. Ut enim massa, sodales tempor convallis et, iaculis ac massa. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas metus nulla, commodo a sodales sed, dignissim pretium nunc. Nam et lacus neque. Ut enim massa, sodales tempor convallis et, iaculis ac massa.</p>
								</div>
							</div>

						</div>
					</div>

				</div>
			</div>
		</div>

	</section>

</div>
<!-- /WRAPPER -->
@stop
