@extends('layout.master')

@section('content')
<?# -- WRAPPER -- ?>
<div id="wrapper">

	<section class="container">

		<!-- WELCOME -->
		<article>
			<h2><strong>About</strong> Us</h2>
			<p>Sed ut perspiciatis unde omnis <strong>iste natus error</strong> sit voluptatem accusantium <em>doloremque laudantium</em>, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia <a href="#">voluptas sit aspernatur</a> aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut <strong>labore dolore magnm</strong> aliquam quaerat voluptatem.</p>
			<p class="lead">Lid est laborum dolo rumes fugats untras. Etharums ser quidem rerum facilis dolores nemis omnis fugats vitaes nemo minima rerums unsers sadips amets.</p>
		</article>
		<!-- WELCOME -->

		<div class="divider"><!-- divider -->
			<i class="fa fa-star"></i>
		</div>

		<!-- BORN TO BE A WINNER -->
		<article class="row">
			<div class="col-md-6">
				<div class="owl-carousel controlls-over" data-plugin-options='{"items": 1, "singleItem": true, "navigation": true, "pagination": true, "transitionStyle":"fadeUp"}'>
					<div>
						<img class="img-responsive" src="/images/demo/about_1.jpg" width="555" height="311" alt="">
					</div>
					<div>
						<iframe src="http://player.vimeo.com/video/23630702" width="800" height="450"></iframe>
					</div>
					<div>
						<img class="img-responsive" src="/images/demo/about_2.jpg" width="555" height="311" alt="">
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<h3>Atropos : Born to be a Winner</h3>
				<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
				<p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.</p>
				<p>Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore dolore magnm aliquam quaerat voluptatem.</p>
			</div>
		</article>
		<!-- /BORN TO BE A WINNER -->


		<div class="divider"><!-- divider -->
			<i class="fa fa-star"></i>
		</div>


		<!-- WHO WE ARE and SKILLS -->
		<article class="row">
			<div class="col-md-6">
				<h3>Who is Atropos</h3>

				<div class="toogle">

					<div class="toggle">
						<label>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</label>
						<div class="toggle-content">
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur <a data-toggle="tooltip" data-original-title="Default tooltip" href="#">pellentesque neque eget</a> diam posuere porta. Quisque ut nulla at nunc vehicula lacinia. Proin adipiscing porta tellus, ut feugiat nibh adipiscing sit amet. <a href="#" data-container="body" data-toggle="popover" data-placement="top" data-content="And here's some amazing content. It's very engaging. right?" data-original-title="A Title" title="A Title">Popover on top</a></p>
						</div>
					</div>

					<div class="toggle">
						<label>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</label>
						<div class="toggle-content">
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur <a data-toggle="tooltip" data-original-title="Default tooltip" href="#">pellentesque neque eget</a> diam posuere porta. Quisque ut nulla at nunc vehicula lacinia. Proin adipiscing porta tellus, ut feugiat nibh adipiscing sit amet. <a href="#" data-container="body" data-toggle="popover" data-placement="top" data-content="And here's some amazing content. It's very engaging. right?" data-original-title="A Title" title="A Title">Popover on top</a></p>
						</div>
					</div>

					<div class="toggle">
						<label>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</label>
						<div class="toggle-content">
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur <a data-toggle="tooltip" data-original-title="Default tooltip" href="#">pellentesque neque eget</a> diam posuere porta. Quisque ut nulla at nunc vehicula lacinia. Proin adipiscing porta tellus, ut feugiat nibh adipiscing sit amet. <a href="#" data-container="body" data-toggle="popover" data-placement="top" data-content="And here's some amazing content. It's very engaging. right?" data-original-title="A Title" title="A Title">Popover on top</a></p>
						</div>
					</div>

					<div class="toggle">
						<label>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</label>
						<div class="toggle-content">
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur <a data-toggle="tooltip" data-original-title="Default tooltip" href="#">pellentesque neque eget</a> diam posuere porta. Quisque ut nulla at nunc vehicula lacinia. Proin adipiscing porta tellus, ut feugiat nibh adipiscing sit amet. <a href="#" data-container="body" data-toggle="popover" data-placement="top" data-content="And here's some amazing content. It's very engaging. right?" data-original-title="A Title" title="A Title">Popover on top</a></p>
						</div>
					</div>

				</div>
			</div>
			<div class="col-md-6">
				<h3>Atropos Alien Skills</h3>

				<div class="progress-bars">

					<div class="progress-label">
						<span>HTML/CSS</span>
					</div>
					<div class="progress">
						<div class="progress-bar progress-bar-primary" data-appear-progress-animation="100%">
							<span class="progress-bar-tooltip">100%</span>
						</div>
					</div>
					<div class="progress-label">
						<span>Design</span>
					</div>
					<div class="progress">
						<div class="progress-bar progress-bar-primary" data-appear-progress-animation="86%" data-appear-animation-delay="300">
							<span class="progress-bar-tooltip">86%</span>
						</div>
					</div>
					<div class="progress-label">
						<span>Mysql</span>
					</div>
					<div class="progress">
						<div class="progress-bar progress-bar-primary" data-appear-progress-animation="69%" data-appear-animation-delay="600">
							<span class="progress-bar-tooltip">69%</span>
						</div>
					</div>
					<div class="progress-label">
						<span>PHP</span>
					</div>
					<div class="progress">
						<div class="progress-bar progress-bar-primary" data-appear-progress-animation="96%" data-appear-animation-delay="900">
							<span class="progress-bar-tooltip">96%</span>
						</div>
					</div>

				</div>

			</div>
		</article>
		<!-- /WHO WE ARE and SKILLS -->


		<div class="divider"><!-- divider -->
			<i class="fa fa-star"></i>
		</div>

		<hr />

			<div class="owl-carousel" data-plugin-options='{"items": 5, "singleItem": false, "autoPlay": true, "navigation": false, "pagination": false}'>
				<div>
					<img class="img-responsive" src="/images/demo/brands/1.jpg" alt="">
				</div>
				<div>
					<img class="img-responsive" src="/images/demo/brands/2.jpg" alt="">
				</div>
				<div>
					<img class="img-responsive" src="/images/demo/brands/3.jpg" alt="">
				</div>
				<div>
					<img class="img-responsive" src="/images/demo/brands/4.jpg" alt="">
				</div>
				<div>
					<img class="img-responsive" src="/images/demo/brands/5.jpg" alt="">
				</div>
				<div>
					<img class="img-responsive" src="/images/demo/brands/6.jpg" alt="">
				</div>
				<div>
					<img class="img-responsive" src="/images/demo/brands/7.jpg" alt="">
				</div>
				<div>
					<img class="img-responsive" src="/images/demo/brands/8.jpg" alt="">
				</div>
			</div>

	</section>

</div>
<?# -- /WRAPPER -- ?>
@stop
