@extends('layout.master')

<?php
$next_step = Cookie::get('nstep');
if (Input::get('nstep') == 'intro')
	$next_step = 'intro';

$relation = Relation::find(Auth::user()->self_id);
if ($relation)
	$iban = Iban::where('relation_id','=',$relation->id)->first();
else
	$iban = null;

//$contact = Contact::where('relation_id','=',$relation->id)->first();

?>

@section('content')

@if ($next_step && $next_step=='intro')
<script type="text/javascript">
	$(document).ready(function() {
		$('#tutModal').modal('toggle');
		$('button[data-action="hide"]').click(function(){
			$.get("/hidenextstep").fail(function(e) { console.log(e); });
		});
	});
</script>
<div class="modal fade" id="tutModal" tabindex="-1" role="dialog" aria-labelledby="tutModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header" style="background-color: #333">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel">Welkom bij de Calculatietool</h4>
			</div>

			<div class="modal-body">
				<p>Na het invullen van deze QuickStart kan je direct starten met de CalculatieTool.

				{{ Form::open(array('url' => '/mycompany/quickstart')) }}

				<h4 class="company">Jouw Bedrijfsgegevens</h4>
				<input type="hidden" name="id" id="id" value="{{ $relation ? $relation->id : '' }}"/>
				<div class="row">
					<div class="col-md-7">
						<div class="form-group">
							<label for="company_name">Bedrijfsnaam*</label>
							<input name="company_name" id="company_name" type="text" value="{{ Input::old('company_name') ? Input::old('company_name') : ($relation ? $relation->company_name : '') }}" class="form-control" />
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="company_type">Bedrijfstype*</label>
							<select name="company_type" id="company_type" class="form-control pointer">
							@foreach (RelationType::all() as $type)
								<option {{ $relation ? ($relation->type_id==$type->id ? 'selected' : '') : '' }} value="{{ $type->id }}">{{ ucwords($type->type_name) }}</option>
							@endforeach
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label for="kvk">K.v.K nummer</label>&nbsp;<a data-toggle="tooltip" data-placement="bottom" data-original-title="Je KVK-nummer dient te bestaan uit 8 cijfers" href="javascript:void(0);"><i class="fa fa-info-circle"></i></a>
							<input name="kvk" id="kvk" type="text" maxlength="8" minlength="8" value="{{ Input::old('kvk') ? Input::old('kvk') : ($relation ? $relation->kvk : '') }}" class="form-control"/>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="btw">BTW nummer</label>&nbsp;<a data-toggle="tooltip" data-placement="bottom" data-original-title="Je BTW-nummer bestaat uit een combinatie van 12 cijfers en/of letters. Veelal beginnen nederlandse BTW-nummers met 'NL' en eindigen op 'B01'." href="javascript:void(0);"><i class="fa fa-info-circle"></i></a>
							<input name="btw" id="btw" type="text" maxlength="14" minlength="14" value="{{ Input::old('btw') ? Input::old('btw') : ($relation ? $relation->btw : '') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="email_comp">Email*</label>
							<input name="email_comp" id="email_comp" type="email" value="{{ Input::old('email_comp') ? Input::old('email_comp') : ($relation ? $relation->email : '') }}" class="form-control"/>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-5">
						<div class="form-group">
							<label for="street">Straat*</label>
							<input name="street" id="street" type="text" value="{{ Input::old('street') ? Input::old('street') : ($relation ? $relation->address_street : '') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="address_number">Huis nr.*</label>
							<input name="address_number" id="address_number" type="text" value="{{ Input::old('address_number') ? Input::old('address_number') : ($relation ? $relation->address_number : '') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<label for="zipcode">Postcode*</label>
							<input name="zipcode" id="zipcode" maxlength="6" type="text" value="{{ Input::old('zipcode') ? Input::old('zipcode') : ($relation ? $relation->address_postal : '') }}" class="form-control"/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="city">Plaats*</label>
							<input name="city" id="city" type="text" value="{{ Input::old('city') ? Input::old('city') : ($relation ? $relation->address_city : '') }}" class="form-control"/>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
						<div class="form-group">
							<label for="province">Provincie*</label>
							<select name="province" id="province" class="form-control pointer">
								@foreach (Province::all() as $province)
									<option {{ $relation ? ($relation->province_id==$province->id ? 'selected' : '') : '' }} value="{{ $province->id }}">{{ ucwords($province->province_name) }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="country">Land*</label>
							<select name="country" id="country" class="form-control pointer">
								@foreach (Country::all() as $country)
									<option {{ $relation ? ($relation->country_id==$country->id ? 'selected' : '') : ($country->country_name=='nederland' ? 'selected' : '')}} value="{{ $country->id }}">{{ ucwords($country->country_name) }}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>

				<h4>Jouw Contactgegevens</h4>
				<div class="row">
					<div class="col-md-3">
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
					<div class="col-md-3">
						<div class="form-group">
							<label for="email">Email*</label>
							<input name="email" id="email" type="email" value="{{ Input::old('email') }}" class="form-control"/>
						</div>
					</div>
					<div class="col-md-3 company">
						<div class="form-group">
							<label for="contactfunction">Functie</label>
							<select name="contactfunction" id="contactfunction" class="form-control pointer">
							@foreach (ContactFunction::all() as $function)
								<option {{ $function->function_name=='directeur' ? 'selected' : '' }} value="{{ $function->id }}">{{ ucwords($function->function_name) }}</option>
							@endforeach
							</select>
						</div>
					</div>
				</div>
			</div>

			<div class="modal-footer">
					<div class="col-md-12">
						<button class="btn btn-primary"><i class="fa fa-check"></i> Opslaan</button>
					</div>
			</div>

		</div>
	</div>
</div>
@endif
<div id="wrapper">

	<section class="container">

		@if (SystemMessage::where('active','=',true)->count()>0)
		@if (SystemMessage::where('active','=',true)->orderBy('created_at', 'desc')->first()->level==1)
		<div class="alert alert-warning">
			<i class="fa fa-fa fa-info-circle"></i>
			{{ SystemMessage::where('active','=',true)->orderBy('created_at', 'desc')->first()->content }}
		</div>
		@else
		<div class="alert alert-danger">
			<i class="fa fa-warning"></i>
			<strong>{{ SystemMessage::where('active','=',true)->orderBy('created_at', 'desc')->first()->content }}</strong>
		</div>
		@endif
		@endif

		@if (!Auth::user()->hasPayed())
		<div class="alert alert-danger">
			<i class="fa fa-danger"></i>
			Account is gedeactiveerd, abonnement is verlopen.
		</div>
		@endif

		<h2><strong>Navigatie</strong> koppelingen</h2>
		<article class="row">
			<div class="col-md-6">
				<!--<h4>Navigatie</h4>-->
				<div class="col-md-3">
					<div class="featured-box nobg">
						<div class="box-content">
							<a href="/project">
								<i class="fa fa-folder-open"></i>
								<h5>Projecten</h5>
							</a>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="featured-box nobg">
						<div class="box-content">
							<a href="/project/new">
								<i class="fa fa-pencil"></i>
								<h5>Nieuw project</h5>
							</a>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="featured-box nobg">
						<div class="box-content">
							<a href="/relation">
								<i class="fa fa-users"></i>
								<h5>Relaties</h5>
							</a>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="featured-box nobg">
						<div class="box-content">
							<a href="/relation/new">
								<i class="fa fa-user"></i>
								<h5>Nieuwe relatie</h5>
							</a>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="col-md-3">
					<div class="featured-box nobg">
						<div class="box-content">
							<a href="/material">
								<i class="fa fa-sort-alpha-desc"></i>
								<h5>Materialen</h5>
							</a>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="featured-box nobg">
						<div class="box-content">
							<a href="/mycompany">
								<i class="fa fa-files-o"></i>
								<h5>Mijn Bedrijf</h5>
							</a>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="featured-box nobg">
						<div class="box-content">
							<a href="/timesheet">
								<i class="fa fa-calendar"></i>
								<h5>Uren</h5>
							</a>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="featured-box nobg">
						<div class="box-content">
							<a href="/purchase">
								<i class="fa fa-shopping-cart"></i>
								<h5>Inkoop</h5>
							</a>
						</div>
					</div>
				</div>
			</div>


<?php
// https://ashobiz.asia/boot-extended14/ui/ui-117.html#
// https://wrapbootstrap.com/preview/WB0DS0351
?>

						@if (0)
						<article class="row">
							<div class="col-md-12">
								<section id="portfolio">

									<h2><strong>Project</strong> overzicht</h2>
									<ul class="nav nav-pills isotope-filter isotope-filter" data-sort-id="isotope-list" data-option-key="filter">
										<li data-option-value="*" class="active"><a href="#">Openstaande calculaties</a></li>
										<li data-option-value=".development"><a href="#">Uitstaande offertes</a></li>
										<li data-option-value=".photography"><a href="#">Onderhanden projecten</a></li>
										<li data-option-value=".photography"><a href="#">Openstaande facturen</a></li>
										<li data-option-value=".design"><a href="#">Gesloten projecten</a></li>
									</ul>

									<div class="row">

										<ul class="sort-destination isotope fadeIn" data-sort-id="isotope-list" style="position: relative; overflow: hidden; height: 833px;">

											<li class="isotope-item col-sm-6 col-md-3 development" style="position: absolute; left: 0px; top: 0px; transform: translate3d(0px, 0px, 0px);"><!-- item -->
												<div class="item-box">
													<figure>
														<a class="item-hover" href="portfolio-single.html">
															<span class="overlay color2"></span>
															<span class="inner">
																<span class="block fa fa-plus fsize20"></span>
																<strong>PROJECT</strong> DETAIL
															</span>
														</a>
														<img class="img-responsive" src="<<plaatje>>" width="260" height="260" alt="">
													</figure>
													<div class="item-box-desc">
														<h4>Atropos Project</h4>
														<small class="styleColor">29 June, 2014</small>
													</div>
												</div>
											</li>

											<li class="isotope-item col-sm-6 col-md-3 photography" style="position: absolute; left: 0px; top: 0px; transform: translate3d(293px, 0px, 0px);"><!-- item 2 -->
												<div class="item-box">
													<figure>
														<a class="item-hover lightbox" href="https://www.youtube.com/watch?v=W7Las-MJnJo" data-plugin-options="{&quot;type&quot;:&quot;iframe&quot;}">
															<span class="overlay color2"></span>
															<span class="inner">
																<span class="block fa fa-plus fsize20"></span>
																<strong>VIEW</strong> VIDEO
															</span>
														</a>
														<img class="img-responsive" src="<<plaatje>>" width="260" height="260" alt="">
													</figure>
													<div class="item-box-desc">
														<h4>Atropos Project</h4>
														<small class="styleColor">29 June, 2014</small>
													</div>
												</div>
											</li>

											<li class="isotope-item col-sm-6 col-md-3 design" style="position: absolute; left: 0px; top: 0px; transform: translate3d(586px, 0px, 0px);"><!-- item 3 -->
												<div class="item-box">
													<figure>
														<a class="item-hover" href="portfolio-single.html">
															<span class="overlay color2"></span>
															<span class="inner">
																<span class="block fa fa-plus fsize20"></span>
																<strong>PROJECT</strong> DETAIL
															</span>
														</a>
														<img class="img-responsive" src="<<plaatje>>" width="260" height="260" alt="">
													</figure>
													<div class="item-box-desc">
														<h4>Atropos Project</h4>
														<small class="styleColor">29 June, 2014</small>
													</div>
												</div>
											</li>

											<li class="isotope-item col-sm-6 col-md-3 photography" style="position: absolute; left: 0px; top: 0px; transform: translate3d(879px, 0px, 0px);"><!-- item 4 -->
												<div class="item-box">
													<figure>
														<a class="item-hover" href="portfolio-single.html">
															<span class="overlay color2"></span>
															<span class="inner">
																<span class="block fa fa-plus fsize20"></span>
																<strong>PROJECT</strong> DETAIL
															</span>
														</a>
														<img class="img-responsive" src="<<plaatje>>" width="260" height="260" alt="">
													</figure>
													<div class="item-box-desc">
														<h4>Atropos Project</h4>
														<small class="styleColor">29 June, 2014</small>
													</div>
												</div>
											</li>

											<li class="isotope-item col-sm-6 col-md-3 development" style="position: absolute; left: 0px; top: 0px; transform: translate3d(0px, 277px, 0px);"><!-- item 5 -->
												<div class="item-box">
													<figure>
														<a class="item-hover lightbox" href="https://www.youtube.com/watch?v=W7Las-MJnJo" data-plugin-options="{&quot;type&quot;:&quot;iframe&quot;}">
															<span class="overlay color2"></span>
															<span class="inner">
																<span class="block fa fa-plus fsize20"></span>
																<strong>VIEW</strong> VIDEO
															</span>
														</a>
														<img class="img-responsive" src="<<plaatje>>" width="260" height="260" alt="">
													</figure>
													<div class="item-box-desc">
														<h4>Atropos Project</h4>
														<small class="styleColor">29 June, 2014</small>
													</div>
												</div>
											</li>

											<li class="isotope-item col-sm-6 col-md-3 design" style="position: absolute; left: 0px; top: 0px; transform: translate3d(293px, 277px, 0px);"><!-- item 6 -->
												<div class="item-box">
													<figure>
														<a class="item-hover" href="portfolio-single.html">
															<span class="overlay color2"></span>
															<span class="inner">
																<span class="block fa fa-plus fsize20"></span>
																<strong>PROJECT</strong> DETAIL
															</span>
														</a>
														<img class="img-responsive" src="<<plaatje>>" width="260" height="260" alt="">
													</figure>
													<div class="item-box-desc">
														<h4>Atropos Project</h4>
														<small class="styleColor">29 June, 2014</small>
													</div>
												</div>
											</li>

											<li class="isotope-item col-sm-6 col-md-3 photography design" style="position: absolute; left: 0px; top: 0px; transform: translate3d(586px, 278px, 0px);"><!-- item 7 -->
												<div class="item-box">
													<figure>
														<a class="item-hover" href="portfolio-single.html">
															<span class="overlay color2"></span>
															<span class="inner">
																<span class="block fa fa-plus fsize20"></span>
																<strong>PROJECT</strong> DETAIL
															</span>
														</a>
														<img class="img-responsive" src="<<plaatje>>" width="260" height="260" alt="">
													</figure>
													<div class="item-box-desc">
														<h4>Atropos Project</h4>
														<small class="styleColor">29 June, 2014</small>
													</div>
												</div>
											</li>

											<li class="isotope-item col-sm-6 col-md-3 development" style="position: absolute; left: 0px; top: 0px; transform: translate3d(879px, 278px, 0px);"><!-- item 8 -->
												<div class="item-box">
													<figure>
														<a class="item-hover" href="portfolio-single.html">
															<span class="overlay color2"></span>
															<span class="inner">
																<span class="block fa fa-plus fsize20"></span>
																<strong>PROJECT</strong> DETAIL
															</span>
														</a>
														<img class="img-responsive" src="<<plaatje>>" width="260" height="260" alt="">
													</figure>
													<div class="item-box-desc">
														<h4>Atropos Project</h4>
														<small class="styleColor">29 June, 2014</small>
													</div>
												</div>
											</li>

											<li class="isotope-item col-sm-6 col-md-3 development" style="position: absolute; left: 0px; top: 0px; transform: translate3d(0px, 555px, 0px);"><!-- item -->
												<div class="item-box">
													<figure>
														<a class="item-hover lightbox" href="https://www.youtube.com/watch?v=W7Las-MJnJo" data-plugin-options="{&quot;type&quot;:&quot;iframe&quot;}">
															<span class="overlay color2"></span>
															<span class="inner">
																<span class="block fa fa-plus fsize20"></span>
																<strong>VIEW</strong> VIDEO
															</span>
														</a>
														<img class="img-responsive" src="<<plaatje>>" width="260" height="260" alt="">
													</figure>
													<div class="item-box-desc">
														<h4>Atropos Project</h4>
														<small class="styleColor">29 June, 2014</small>
													</div>
												</div>
											</li>

											<li class="isotope-item col-sm-6 col-md-3 photography" style="position: absolute; left: 0px; top: 0px; transform: translate3d(293px, 555px, 0px);"><!-- item 2 -->
												<div class="item-box">
													<figure>
														<a class="item-hover" href="portfolio-single.html">
															<span class="overlay color2"></span>
															<span class="inner">
																<span class="block fa fa-plus fsize20"></span>
																<strong>PROJECT</strong> DETAIL
															</span>
														</a>
														<img class="img-responsive" src="<<plaatje>>" width="260" height="260" alt="">
													</figure>
													<div class="item-box-desc">
														<h4>Atropos Project</h4>
														<small class="styleColor">29 June, 2014</small>
													</div>
												</div>
											</li>

											<li class="isotope-item col-sm-6 col-md-3 design" style="position: absolute; left: 0px; top: 0px; transform: translate3d(586px, 555px, 0px);"><!-- item 3 -->
												<div class="item-box">
													<figure>
														<a class="item-hover" href="portfolio-single.html">
															<span class="overlay color2"></span>
															<span class="inner">
																<span class="block fa fa-plus fsize20"></span>
																<strong>PROJECT</strong> DETAIL
															</span>
														</a>
														<img class="img-responsive" src="<<plaatje>>" width="260" height="260" alt="">
													</figure>
													<div class="item-box-desc">
														<h4>Atropos Project</h4>
														<small class="styleColor">29 June, 2014</small>
													</div>
												</div>
											</li>

											<li class="isotope-item col-sm-6 col-md-3 photography" style="position: absolute; left: 0px; top: 0px; transform: translate3d(879px, 556px, 0px);"><!-- item 4 -->
												<div class="item-box">
													<figure>
														<a class="item-hover" href="portfolio-single.html">
															<span class="overlay color2"></span>
															<span class="inner">
																<span class="block fa fa-plus fsize20"></span>
																<strong>PROJECT</strong> DETAIL
															</span>
														</a>
														<img class="img-responsive" src="<<plaatje>>" width="260" height="260" alt="">
													</figure>
													<div class="item-box-desc">
														<h4>Atropos Project</h4>
														<small class="styleColor">29 June, 2014</small>
													</div>
												</div>
											</li>
										</ul>
									</div><!-- /.masonry-container -->
									<!-- CALLOUT -->
									<div class="bs-callout text-center nomargin-bottom">
										<h3>Ben je tevreden met de CalcTool?? <a href="about" target="_blank" class="btn btn-primary btn-lg">Ja? We horen het graag!</a></h3>
									</div>
									<!-- /CALLOUT -->
								</section>
							</div>
						</article>

					@endif

<!--
					<div id="tab2" class="tab-pane">
					<article class="row">
						<div class="col-md-12">
							<div class="col-md-3">
							<h5><strong>Openstaande</strong> calculaties</h5>
								@foreach (Project::where('user_id','=', Auth::user()->id)->get() as $project)
									<div class="row">{{ HTML::link('project-'.$project->id.'/edit', $project->project_name) }}</div>
								@endforeach
							</div>
							<div class="col-md-3">
							<h5><strong>Uitstaande</strong> offertes</h5>
								@foreach (Project::where('user_id','=', Auth::user()->id)->get() as $project)
									<div class="row">{{ HTML::link('project-'.$project->id.'/edit', $project->project_name) }}</div>
								@endforeach
							</div>
							<div class="col-md-3">
							<h5><strong>Onderhanden</strong> projecten</h5>
								@foreach (Project::where('user_id','=', Auth::user()->id)->get() as $project)
									<div class="row">{{ HTML::link('project-'.$project->id.'/edit', $project->project_name) }}</div>
								@endforeach
							</div>
						</div>
						<div class="col-md-12">
							<div class="col-md-3">
							<h5><strong>Openstaande</strong> facturen</h5>
								@foreach (Project::where('user_id','=', Auth::user()->id)->get() as $project)
									<div class="row">{{ HTML::link('project-'.$project->id.'/edit', $project->project_name) }}</div>
								@endforeach
							</div>
							<div class="col-md-3">
							<h5><strong>Afgesloten</strong> projecten</h5>
								@foreach (Project::where('user_id','=', Auth::user()->id)->get() as $project)
									<div class="row">{{ HTML::link('project-'.$project->id.'/edit', $project->project_name) }}</div>
								@endforeach
							</div>
						</div>

					</article>
-->

				</div>
			</div>

		</div>














	</section>
</div>

<?# -- /WRAPPER -- ?>

@stop
