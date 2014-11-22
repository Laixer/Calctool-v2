@extends('layout.master')

@section('content')
<?# -- WRAPPER -- ?>
<div id="wrapper">

	<section class="container">

		<h2><strong>Home</strong></h2>

		<?# -- BORN TO BE A WINNER -- ?>
		<article class="row">
			<div class="col-md-3">
				<h4>Navigatie</h4>
				<div class="col-md-4">
					<div class="featured-box nobg">
						<div class="box-content">
							<a href="/project/new">
								<i class="fa fa-pencil"></i>
								<h5>Nieuw project</h5>
							</a>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="featured-box nobg">
						<div class="box-content">
							<a href="/project">
								<i class="fa fa-folder-open"></i>
								<h5>Projecten</h5>
							</a>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="featured-box nobg">
						<div class="box-content">
							<a href="/relation/new">
								<i class="fa fa-user"></i>
								<h5>Nieuwe relatie</h5>
							</a>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="featured-box nobg">
						<div class="box-content">
							<a href="/relation">
								<i class="fa fa-users"></i>
								<h5>Relaties<br />&nbsp;</h5>
							</a>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="featured-box nobg">
						<div class="box-content">
							<i class="fa fa-calendar"></i>
							<h5>Uren registratie</h5>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="featured-box nobg">
						<div class="box-content">
							<i class="fa fa-shopping-cart"></i>
							<h5>Inkoop facturen</h5>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="featured-box nobg">
						<div class="box-content">
							<i class="fa fa-sort-alpha-desc"></i>
							<h5>Materialen database</h5>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="featured-box nobg">
						<div class="box-content">
							<i class="fa fa-cogs"></i>
							<h5>Instellingen</h5>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="featured-box nobg">
						<div class="box-content">
							<i class="fa fa-question"></i>
							<h5>Help</h5>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-1">
			</div>

			<div class="col-md-8">
			<h4>Nieuws</h4>
				<div class="owl-carousel controlls-over" data-plugin-options='{"items": 1, "singleItem": true, "navigation": true, "pagination": true, "transitionStyle":"fadeUp"}'>
					<div>
						<img class="img-responsive" src="http://www.hi-re.nl/wp-content/uploads/arbeidsmarkt-vakmanschap.jpg" width="850" height="250" alt="">
					</div>
					<div>
						<iframe width="555" height="311" src="http://www.youtube.com/embed/-bdKMT1znJ0"></iframe>
					</div>
					<div>
						<img class="img-responsive" src="http://www.hi-re.nl/wp-content/uploads/arbeidsmarkt-vakmanschap.jpg" width="850" height="250" alt="">
					</div>
					<div>
						<iframe width="555" height="311" src="http://www.youtube.com/embed/-bdKMT1znJ0"></iframe>
					</div>
				</div>
			</div>


		</article>
		<?# -- /BORN TO BE A WINNER -- ?>

		<hr />

		<?# -- FEATURED BOXES 3 -- ?>
		<div class="row">
			<div class="col-md-6">

				<h4>Openstaande projecten</h4>
				<div class="panel-group" id="project">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#project" href="#project1">
									<i class="fa fa-check"></i>
									Option 1
								</a>
							</h4>
						</div>
						<div id="project1" class="collapse in">
							<div class="panel-body">
								Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur <a data-toggle="tooltip" data-original-title="Default tooltip" href="#">pellentesque neque eget</a> diam posuere porta. Quisque ut nulla at nunc vehicula lacinia. Proin adipiscing porta tellus, ut feugiat nibh adipiscing sit amet. In eu justo a felis faucibus ornare vel id metus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; In eu libero ligula. Fusce eget metus lorem, ac viverra leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur pellentesque neque eget diam posuere porta. Quisque ut nulla at nunc vehicula lacinia.
							</div>
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#project" href="#project2">
									<i class="fa fa-check"></i>
									Option 2
								</a>
							</h4>
						</div>
						<div id="project2" class="collapse">
							<div class="panel-body">
							<h4>Openstaande facturen</h4>				Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur <a data-toggle="tooltip" data-original-title="Default tooltip" href="#">pellentesque neque eget</a> diam posuere porta. Quisque ut nulla at nunc vehicula lacinia. Proin adipiscing porta tellus, ut feugiat nibh adipiscing sit amet. In eu justo a felis faucibus ornare vel id metus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; In eu libero ligula. Fusce eget metus lorem, ac viverra leo.
							</div>
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#project" href="#project3">
									<i class="fa fa-check"></i>
									Option 3
								</a>
							</h4>
						</div>
						<div id="project3" class="collapse">
							<div class="panel-body">
								Curabitur <a data-toggle="tooltip" data-original-title="Default tooltip" href="#">pellentesque neque eget</a> diam posuere porta. Quisque ut nulla at nunc vehicula lacinia. Proin adipiscing porta tellus, ut feugiat nibh adipiscing sit amet. In eu justo a felis faucibus ornare vel id metus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; In eu libero ligula.
							</div>
						</div>
					</div>
				</div>

			</div>
			<div class="col-md-6">

				<h4>Openstaande facturen</h4>
				<div class="panel-group" id="invoice">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#invoice" href="#invoice1">
									<i class="fa fa-check"></i>
									Option 1
								</a>
							</h4>
						</div>
						<div id="invoice1" class="collapse in">
							<div class="panel-body">
								Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur <a data-toggle="tooltip" data-original-title="Default tooltip" href="#">pellentesque neque eget</a> diam posuere porta. Quisque ut nulla at nunc vehicula lacinia. Proin adipiscing porta tellus, ut feugiat nibh adipiscing sit amet. In eu justo a felis faucibus ornare vel id metus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; In eu libero ligula. Fusce eget metus lorem, ac viverra leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur pellentesque neque eget diam posuere porta. Quisque ut nulla at nunc vehicula lacinia.
							</div>
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#invoice" href="#invoice2">
									<i class="fa fa-check"></i>
									Option 2
								</a>
							</h4>
						</div>
						<div id="invoice2" class="collapse">
							<div class="panel-body">
								Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur <a data-toggle="tooltip" data-original-title="Default tooltip" href="#">pellentesque neque eget</a> diam posuere porta. Quisque ut nulla at nunc vehicula lacinia. Proin adipiscing porta tellus, ut feugiat nibh adipiscing sit amet. In eu justo a felis faucibus ornare vel id metus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; In eu libero ligula. Fusce eget metus lorem, ac viverra leo.
							</div>
						</div>
					</div>
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#invoice" href="#invoice3">
									<i class="fa fa-check"></i>
									Option 3
								</a>
							</h4>
						</div>
						<div id="invoice3" class="collapse">
							<div class="panel-body">
								Curabitur <a data-toggle="tooltip" data-original-title="Default tooltip" href="#">pellentesque neque eget</a> diam posuere porta. Quisque ut nulla at nunc vehicula lacinia. Proin adipiscing porta tellus, ut feugiat nibh adipiscing sit amet. In eu justo a felis faucibus ornare vel id metus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; In eu libero ligula.
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
		<?# -- /FEATURED BOXES 3 -- ?>

		<hr />

		<div class="row">
			<div class="col-md-6">
				<h4>Waarom de calculatietool?</h4>
				<ul class="list-icon star-o">
					<li>Reden 1</li>
					<li>Reden 2</li>
					<li>Reden 3</li>
					<li>Reden 4</li>
					<li>Reden 5</li>
				</ul>
			</div>

			<div class="col-md-6">
				<h4>Wat zeggen klanten ervan?</h4>
				<div class="owl-carousel text-center" data-plugin-options='{"items": 1, "singleItem": true, "navigation": false, "pagination": true, "autoPlay": true, "transitionStyle":"fadeUp"}'><!-- transitionStyle: fade, backSlide, goDown, fadeUp,  -->
					<div class="testimonial white">
						<p>Praesent est laborum dolo rumes fugats untras. Etha rums ser quidem rerum facilis dolores nemis onis fugats vitaes nemo minima rerums unsers sadips amets.</p>
						<cite><strong>Andre Opstal</strong>, Customer</cite>
					</div>

					<div class="testimonial white">
						<p>Donec tellus massa, tristique sit amet condim vel, facilisis quis sapien. Praesent id enim sit amet odio vulputate eleifend in in tortor. Donec tellus massa.</p>
						<cite><strong>Ton Benner</strong>, Customer</cite>
					</div>

					<div class="testimonial white">
						<p>Praesent est laborum dolo rumes fugats untras. Etha rums ser quidem rerum facilis dolores nemis onis fugats vitaes nemo minima rerums unsers sadips amets.</p>
						<cite><strong>Dorin Doe</strong>, Customer</cite>
					</div>

					<div class="testimonial white">
						<p>Donec tellus massa, tristique sit amet condim vel, facilisis quis sapien. Praesent id enim sit amet odio vulputate eleifend in in tortor. Donec tellus massa.</p>
						<cite><strong>Melissa Doe</strong>, Customer</cite>
					</div>

				</div>
			</div>
		</div>

	</section>

</div>
<?# -- /WRAPPER -- ?>
@stop
