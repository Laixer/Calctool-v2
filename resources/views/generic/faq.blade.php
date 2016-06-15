@extends('layout.master')

@section('title', 'FAQ')

@section('content')
<div id="wrapper">

	<section class="container">

		<h2><strong>F</strong>requently <strong>A</strong>sked <strong>Q</strong>uestions</h2>

		<p class="lead">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur pellentesque neque eget diam posuere porta. Quisque ut nulla at nunc vehicula lacinia. Proin adipiscing porta tellus, ut feugiat nibh adipiscing sit amet.</p>

		<div class="divider">
			<i class="fa fa-star"></i>
		</div>

		<div class="toogle toogle-accordion">

			<div class="toggle active">
				<label>Why Atropos is the best choice for you?</label>
				<div class="toggle-content">
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur pellentesque neque eget diam posuere porta. Quisque ut nulla at nunc vehicula lacinia. Proin adipiscing porta tellus, ut feugiat nibh adipiscing sit amet.</p>
					<a class="img-thumbnail lightbox pull-left inner" href="/images/demo/test_2.jpg" data-plugin-options='{"type":"image"}'>
						<img src="/images/demo/test_2_small.jpg" height="110" alt="" />
					</a>
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur pellentesque neque eget diam posuere porta. Quisque ut nulla at nunc vehicula lacinia. Proin adipiscing porta tellus, ut feugiat nibh adipiscing sit amet.</p>
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur <a data-toggle="tooltip" data-original-title="Default tooltip" href="#">pellentesque neque eget</a> diam posuere porta. Quisque ut nulla at nunc vehicula lacinia. Proin adipiscing porta tellus, ut feugiat nibh adipiscing sit amet. <a href="#" data-container="body" data-toggle="popover" data-placement="top" data-content="And here's some amazing content. It's very engaging. right?" data-original-title="A Title" title="A Title">Popover on top</a></p>
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur pellentesque neque eget diam posuere porta. Quisque ut nulla at nunc vehicula lacinia. Proin adipiscing porta tellus, ut feugiat nibh adipiscing sit amet.</p>

					<div class="divider styleColor white"><!-- divider -->
						<i class="fa fa-leaf"></i>
					</div>

					<!-- columns -->
					<div class="row">
						<div class="col-md-4"><!-- left -->
							<h3>Alien Features</h3>
							<p class="justify">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur pellentesque neque eget diam posuere porta. Quisque ut nulla at nunc vehicula lacinia. Proin adipiscing porta tellus, ut feugiat nibh adipiscing sit amet.</p>
						</div>
						<div class="col-md-4"><!-- center - video -->
							<iframe class="fitvids" src="http://player.vimeo.com/video/23630702" width="800" height="450"></iframe>
						</div>
						<div class="col-md-4"><!-- right -->
							<h3>Human Features</h3>
							<p class="justify">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur pellentesque neque eget diam posuere porta. Quisque ut nulla at nunc vehicula lacinia. Proin adipiscing porta tellus, ut feugiat nibh adipiscing sit amet.</p>
						</div>
					</div>

					<p class="lead padding30 text-center">Yes, Atropos can be customized very easy in many ways.</p>
				</div>
			</div>

			<div class="toggle">
				<label>Lorem ipsum dolor sit amet, consectetur adipiscing elit?</label>
				<div class="toggle-content">
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur <a data-toggle="tooltip" data-original-title="Default tooltip" href="#">pellentesque neque eget</a> diam posuere porta. Quisque ut nulla at nunc vehicula lacinia. Proin adipiscing porta tellus, ut feugiat nibh adipiscing sit amet. <a href="#" data-container="body" data-toggle="popover" data-placement="top" data-content="And here's some amazing content. It's very engaging. right?" data-original-title="A Title" title="A Title">Popover on top</a></p>
					<ul class="list-icon check StyleSecondColor">
						<li>Nullam id dolor id</li>
						<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit</li>
						<li>Maecenas sed diam eget</li>
						<li>Curabitur pellentesque neque eget diam posuere porta.</li>
					</ul>
				</div>
			</div>

			<div class="toggle">
				<label>Curabitur pellentesque neque eget diam?</label>
				<div class="toggle-content">
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur <a data-toggle="tooltip" data-original-title="Default tooltip" href="#">pellentesque neque eget</a> diam posuere porta. Quisque ut nulla at nunc vehicula lacinia. Proin adipiscing porta tellus, ut feugiat nibh adipiscing sit amet. <a href="#" data-container="body" data-toggle="popover" data-placement="top" data-content="And here's some amazing content. It's very engaging. right?" data-original-title="A Title" title="A Title">Popover on top</a></p>
					<ul class="list-icon check StyleSecondColor">
						<li>Nullam id dolor id</li>
						<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit</li>
						<li>Maecenas sed diam eget</li>
						<li>Curabitur pellentesque neque eget diam posuere porta.</li>
					</ul>
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur <a data-toggle="tooltip" data-original-title="Default tooltip" href="#">pellentesque neque eget</a> diam posuere porta. Quisque ut nulla at nunc vehicula lacinia. Proin adipiscing porta tellus, ut feugiat nibh adipiscing sit amet. <a href="#" data-container="body" data-toggle="popover" data-placement="top" data-content="And here's some amazing content. It's very engaging. right?" data-original-title="A Title" title="A Title">Popover on top</a></p>
				</div>
			</div>

			<div class="toggle">
				<label>Quisque ut nulla at nunc vehicula lacinia?</label>
				<div class="toggle-content">
					<ul class="list-icon check StyleSecondColor">
						<li>Nullam id dolor id</li>
						<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit</li>
						<li>Maecenas sed diam eget</li>
					</ul>
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur <a data-toggle="tooltip" data-original-title="Default tooltip" href="#">pellentesque neque eget</a> diam posuere porta. Quisque ut nulla at nunc vehicula lacinia. Proin adipiscing porta tellus, ut feugiat nibh adipiscing sit amet. <a href="#" data-container="body" data-toggle="popover" data-placement="top" data-content="And here's some amazing content. It's very engaging. right?" data-original-title="A Title" title="A Title">Popover on top</a></p>
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur <a data-toggle="tooltip" data-original-title="Default tooltip" href="#">pellentesque neque eget</a> diam posuere porta. Quisque ut nulla at nunc vehicula lacinia. Proin adipiscing porta tellus, ut feugiat nibh adipiscing sit amet. <a href="#" data-container="body" data-toggle="popover" data-placement="top" data-content="And here's some amazing content. It's very engaging. right?" data-original-title="A Title" title="A Title">Popover on top</a></p>
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur <a data-toggle="tooltip" data-original-title="Default tooltip" href="#">pellentesque neque eget</a> diam posuere porta. Quisque ut nulla at nunc vehicula lacinia. Proin adipiscing porta tellus, ut feugiat nibh adipiscing sit amet. <a href="#" data-container="body" data-toggle="popover" data-placement="top" data-content="And here's some amazing content. It's very engaging. right?" data-original-title="A Title" title="A Title">Popover on top</a></p>
				</div>
			</div>

			<div class="toggle">
				<label>Proin adipiscing porta tellus, ut feugiat nibh adipiscing sit amet?</label>
				<div class="toggle-content">
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur <a data-toggle="tooltip" data-original-title="Default tooltip" href="#">pellentesque neque eget</a> diam posuere porta. Quisque ut nulla at nunc vehicula lacinia. Proin adipiscing porta tellus, ut feugiat nibh adipiscing sit amet. <a href="#" data-container="body" data-toggle="popover" data-placement="top" data-content="And here's some amazing content. It's very engaging. right?" data-original-title="A Title" title="A Title">Popover on top</a></p>
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur <a data-toggle="tooltip" data-original-title="Default tooltip" href="#">pellentesque neque eget</a> diam posuere porta. Quisque ut nulla at nunc vehicula lacinia. Proin adipiscing porta tellus, ut feugiat nibh adipiscing sit amet. <a href="#" data-container="body" data-toggle="popover" data-placement="top" data-content="And here's some amazing content. It's very engaging. right?" data-original-title="A Title" title="A Title">Popover on top</a></p>
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur <a data-toggle="tooltip" data-original-title="Default tooltip" href="#">pellentesque neque eget</a> diam posuere porta. Quisque ut nulla at nunc vehicula lacinia. Proin adipiscing porta tellus, ut feugiat nibh adipiscing sit amet. <a href="#" data-container="body" data-toggle="popover" data-placement="top" data-content="And here's some amazing content. It's very engaging. right?" data-original-title="A Title" title="A Title">Popover on top</a></p>
					<ul class="list-icon check StyleSecondColor">
						<li>Nullam id dolor id</li>
						<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit</li>
						<li>Maecenas sed diam eget</li>
					</ul>
				</div>
			</div>

			<div class="toggle">
				<label>Proin adipiscing porta tellus?</label>
				<div class="toggle-content">
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur <a data-toggle="tooltip" data-original-title="Default tooltip" href="#">pellentesque neque eget</a> diam posuere porta. Quisque ut nulla at nunc vehicula lacinia. Proin adipiscing porta tellus, ut feugiat nibh adipiscing sit amet. <a href="#" data-container="body" data-toggle="popover" data-placement="top" data-content="And here's some amazing content. It's very engaging. right?" data-original-title="A Title" title="A Title">Popover on top</a></p>
				</div>
			</div>

			<div class="toggle">
				<label>Feugiat nibh adipiscing sit amet?</label>
				<div class="toggle-content">
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur <a data-toggle="tooltip" data-original-title="Default tooltip" href="#">pellentesque neque eget</a> diam posuere porta. Quisque ut nulla at nunc vehicula lacinia. Proin adipiscing porta tellus, ut feugiat nibh adipiscing sit amet. <a href="#" data-container="body" data-toggle="popover" data-placement="top" data-content="And here's some amazing content. It's very engaging. right?" data-original-title="A Title" title="A Title">Popover on top</a></p>
					<ul class="list-icon check StyleSecondColor">
						<li>Nullam id dolor id</li>
						<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit</li>
						<li>Maecenas sed diam eget</li>
					</ul>
				</div>
			</div>
		</div>

		<?# -- CALLOUT -- ?>
		<div class="bs-callout text-center nomargin-bottom">
			<h3>
				<strong>Not Here</strong> what you are looking for? <strong>1800-555-1234</strong>
			</h3>

			<div class="divider"><!-- divider -->
				<i class="fa fa-chevron-down"></i>
			</div>

			<h3>
				<a href="contact-us.html" target="_blank" class="btn btn-primary btn-lg">Contact Us!</a>
			</h3>
		</div>
		<?# -- /CALLOUT -- ?>

	</section>

</div>
<?# -- /WRAPPER -- ?>
@stop
