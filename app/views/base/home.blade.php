@extends('layout.master')

<?php
$next_step = Cookie::get('nstep');
if (Input::get('nstep') == 'intro')
	$next_step = 'intro';
?>

@section('content')

@if ($next_step && $next_step=='intro')
<script type="text/javascript">
	$(document).ready(function() {
		$('#tutModal').modal('toggle');
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
				<h2>Volg de stappen om je account op te zetten</h2>

				<div class="tabs">

					<!-- tabs -->
					<ul class="nav nav-tabs">
						<li class="active"><a href="#service-1" data-toggle="tab" aria-expanded="true"><i class="fa fa-heart-o"></i> Intro</a></li>
						<li class=""><a href="#service-2" data-toggle="tab" aria-expanded="false"><i class="fa fa-smile-o"></i> Account</a></li>
						<li class=""><a href="#service-3" data-toggle="tab" aria-expanded="false"><i class="fa fa-microphone"></i> Bedijf</a></li>
						<li class=""><a href="#service-4" data-toggle="tab" aria-expanded="false"><i class="fa fa-windows"></i> Service 4</a></li>
						<li class=""><a href="#service-5" data-toggle="tab" aria-expanded="false"><i class="fa fa-flask"></i> Service 5</a></li>
					</ul>

					<!-- tabs content -->
					<div class="tab-content">
						<div class="tab-pane active" id="service-1">
							<!--<i class="featured-icon pull-left fa fa-heart-o"></i>
							<p>Praesent est laborum dolo rumes fugats untras. Etha rums ser quidem rerum facilis dolores nemis onis fugats vitaes nemo minima rerums unsers sadips amets. Ut enim ad minim veniam, quis nostrud Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci amets uns. Etharums ser quidem rerum. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Asunt in anim uis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla dolores ipsums fugiats. Etha rums ser quidem rerum facilis dolores nemis onis fugats vitaes nemo minima rerums unsers sadips amets. Ut enim ad minim veniam, quis nostrud neque porro quisquam est. Asunt in anim uis aute irure dolor in reprehenderit in voluptate velit.</p>
							<p>Praesent id enim sit amet odio vulputate eleifend in in tortor. Donec tellus massa, tristique sit amet condim vel, facilisis quis sapien. Praesent id enim sit amet odio vulputate eleifend in in tortor.</p>-->
							<p class="lead">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur pellentesque neque eget diam posuere porta. Quisque ut nulla at nunc vehicula lacinia. Proin adipiscing porta tellus, ut feugiat nibh adipiscing sit amet.</p>
						</div>

						<div class="tab-pane" id="service-2">
							<i class="featured-icon pull-left fa fa-smile-o"><!-- service icon --></i>
							<p>Praesent est laborum dolo rumes fugats untras. Etha rums ser quidem rerum facilis dolores nemis onis fugats vitaes nemo minima rerums unsers sadips amets. Ut enim ad minim veniam, quis nostrud Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci amets uns. Etharums ser quidem rerum. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Asunt in anim uis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla dolores ipsums fugiats. Etha rums ser quidem rerum facilis dolores nemis onis fugats vitaes nemo minima rerums unsers sadips amets. Ut enim ad minim veniam, quis nostrud neque porro quisquam est. Asunt in anim uis aute irure dolor in reprehenderit in voluptate velit.</p>
							<p>Praesent id enim sit amet odio vulputate eleifend in in tortor. Donec tellus massa, tristique sit amet condim vel, facilisis quis sapien. Praesent id enim sit amet odio vulputate eleifend in in tortor.</p>
							<p>Donec tellus massa, tristique sit amet condim vel, facilisis quis sapien. Praesent id enim sit amet odio vulputate eleifend in in tortor. Donec tellus massa.</p>
						</div>

						<div class="tab-pane" id="service-3">
							<i class="featured-icon pull-left fa fa-microphone"><!-- service icon --></i>
							<p>Praesent est laborum dolo rumes fugats untras. Etha rums ser quidem rerum facilis dolores nemis onis fugats vitaes nemo minima rerums unsers sadips amets. Ut enim ad minim veniam, quis nostrud Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci amets uns. Etharums ser quidem rerum. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Asunt in anim uis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla dolores ipsums fugiats. Etha rums ser quidem rerum facilis dolores nemis onis fugats vitaes nemo minima rerums unsers sadips amets. Ut enim ad minim veniam, quis nostrud neque porro quisquam est. Asunt in anim uis aute irure dolor in reprehenderit in voluptate velit.</p>
							<p>Donec tellus massa, tristique sit amet condim vel, facilisis quis sapien. Praesent id enim sit amet odio vulputate eleifend in in tortor. Donec tellus massa.</p>
						</div>

						<div class="tab-pane" id="service-4">
							<i class="featured-icon pull-left fa fa-windows"><!-- service icon --></i>
							<p>Praesent est laborum dolo rumes fugats untras. Etha rums ser quidem rerum facilis dolores nemis onis fugats vitaes nemo minima rerums unsers sadips amets. Ut enim ad minim veniam, quis nostrud Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci amets uns. Etharums ser quidem rerum. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Asunt in anim uis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla dolores ipsums fugiats. Etha rums ser quidem rerum facilis dolores nemis onis fugats vitaes nemo minima rerums unsers sadips amets. Ut enim ad minim veniam, quis nostrud neque porro quisquam est. Asunt in anim uis aute irure dolor in reprehenderit in voluptate velit.</p>
							<p>Praesent id enim sit amet odio vulputate eleifend in in tortor. Donec tellus massa, tristique sit amet condim vel, facilisis quis sapien. Praesent id enim sit amet odio vulputate eleifend in in tortor.</p>
						</div>

						<div class="tab-pane" id="service-5">
							<i class="featured-icon pull-left fa fa-flask"><!-- service icon --></i>
							<p>Praesent id enim sit amet odio vulputate eleifend in in tortor. Donec tellus massa, tristique sit amet condim vel, facilisis quis sapien. Praesent id enim sit amet odio vulputate eleifend in in tortor.</p>
							<p>Donec tellus massa, tristique sit amet condim vel, facilisis quis sapien. Praesent id enim sit amet odio vulputate eleifend in in tortor. Donec tellus massa.</p>
						</div>

					</div>

				</div>

			</div>

			<div class="modal-footer">
				<button class="btn btn-default" data-dismiss="modal" style="border: #ddd 2px solid">Volgende</button>
				<button class="btn btn-default" data-dismiss="modal" style="border: #ddd 2px solid">Sluiten</button>
			</div>

		</div>
	</div>
</div>
@endif
<div id="wrapper">

	<section class="container">

		@if (SystemMessage::where('active','=',true)->count()>0)
		<div class="alert alert-warning">
			<i class="fa fa-warning"></i>
			<strong>{{ SystemMessage::where('active','=',true)->orderBy('created_at', 'desc')->first()->title }}</strong> {{ SystemMessage::where('active','=',true)->orderBy('created_at', 'desc')->first()->content }}
		</div>
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
								<h5>Bedrijf</h5>
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
