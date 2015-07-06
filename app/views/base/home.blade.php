@extends('layout.master')

@section('content')
<?# -- WRAPPER -- ?>
<link href="http://vjs.zencdn.net/4.12/video-js.css" rel="stylesheet">
<script src="http://vjs.zencdn.net/4.12/video.js"></script>
<div id="wrapper">

	<section class="container">

		@if (SystemMessage::where('active','=',true)->count()>0)
		<div class="alert alert-warning">
			<i class="fa fa-warning"></i>
			<strong>{{ SystemMessage::where('active','=',true)->orderBy('created_at', 'desc')->first()->title }}</strong> {{ SystemMessage::where('active','=',true)->orderBy('created_at', 'desc')->first()->content }}
		</div>
		@endif

		<h2><strong>Navigatie</strong> koppelingen</h2>
		<article class="row">
			<div class="col-md-12">
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
			</div>
		</article>
		<article class="row">
			<div class="col-md-12">
				<!--<h4>Navigatie</h4>-->
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
							<a href="/relation/new">
								<i class="fa fa-user"></i>
								<h5>Nieuwe relatie</h5>
							</a>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="featured-box nobg">
						<div class="box-content">
							<i class="fa fa-calendar"></i>
							<h5>Uren</h5>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="featured-box nobg">
						<div class="box-content">
							<i class="fa fa-shopping-cart"></i>
							<h5>Inkoop</h5>
						</div>
					</div>
				</div>
			</div>
		</article>

<!--
http://ashobiz.asia/boot-extended14/ui/ui-117.html#
http://wrapbootstrap.com/preview/WB0DS0351
-->


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
														<img class="img-responsive" src="http://www.voedingswaardetabel.nl/_lib/img/prod/big/kaas40.jpg" width="260" height="260" alt="">
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
														<a class="item-hover lightbox" href="http://www.youtube.com/watch?v=W7Las-MJnJo" data-plugin-options="{&quot;type&quot;:&quot;iframe&quot;}">
															<span class="overlay color2"></span>
															<span class="inner">
																<span class="block fa fa-plus fsize20"></span>
																<strong>VIEW</strong> VIDEO
															</span>
														</a>
														<img class="img-responsive" src="http://www.voedingswaardetabel.nl/_lib/img/prod/big/kaas40.jpg" width="260" height="260" alt="">
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
														<img class="img-responsive" src="http://www.voedingswaardetabel.nl/_lib/img/prod/big/kaas40.jpg" width="260" height="260" alt="">
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
														<img class="img-responsive" src="http://www.voedingswaardetabel.nl/_lib/img/prod/big/kaas40.jpg" width="260" height="260" alt="">
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
														<a class="item-hover lightbox" href="http://www.youtube.com/watch?v=W7Las-MJnJo" data-plugin-options="{&quot;type&quot;:&quot;iframe&quot;}">
															<span class="overlay color2"></span>
															<span class="inner">
																<span class="block fa fa-plus fsize20"></span>
																<strong>VIEW</strong> VIDEO
															</span>
														</a>
														<img class="img-responsive" src="http://www.voedingswaardetabel.nl/_lib/img/prod/big/kaas40.jpg" width="260" height="260" alt="">
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
														<img class="img-responsive" src="http://www.voedingswaardetabel.nl/_lib/img/prod/big/kaas40.jpg" width="260" height="260" alt="">
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
														<img class="img-responsive" src="http://www.voedingswaardetabel.nl/_lib/img/prod/big/kaas40.jpg" width="260" height="260" alt="">
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
														<img class="img-responsive" src="http://www.voedingswaardetabel.nl/_lib/img/prod/big/kaas40.jpg" width="260" height="260" alt="">
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
														<a class="item-hover lightbox" href="http://www.youtube.com/watch?v=W7Las-MJnJo" data-plugin-options="{&quot;type&quot;:&quot;iframe&quot;}">
															<span class="overlay color2"></span>
															<span class="inner">
																<span class="block fa fa-plus fsize20"></span>
																<strong>VIEW</strong> VIDEO
															</span>
														</a>
														<img class="img-responsive" src="http://www.voedingswaardetabel.nl/_lib/img/prod/big/kaas40.jpg" width="260" height="260" alt="">
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
														<img class="img-responsive" src="http://www.voedingswaardetabel.nl/_lib/img/prod/big/kaas40.jpg" width="260" height="260" alt="">
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
														<img class="img-responsive" src="http://www.voedingswaardetabel.nl/_lib/img/prod/big/kaas40.jpg" width="260" height="260" alt="">
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
														<img class="img-responsive" src="http://www.voedingswaardetabel.nl/_lib/img/prod/big/kaas40.jpg" width="260" height="260" alt="">
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
										<h3>Do you like what you see? <a href="contact-us.html" target="_blank" class="btn btn-primary btn-lg">Yes, let's work together!</a></h3>
									</div>
									<!-- /CALLOUT -->
								</section>
							</div>
						</article>
					</div>

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
