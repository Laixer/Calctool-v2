<?# -- TOP NAV -- ?>
<header id="topNav" class="topHead"><?# -- remove class="topHead" if no topHead used! -- ?>
	<div class="container">

		<?# -- Mobile Menu Button -- ?>
		<button class="btn btn-mobile" data-toggle="collapse" data-target=".nav-main-collapse">
			<i class="fa fa-bars"></i>
		</button>

		<?# -- Logo text or image -- ?>
		<a class="logo" href="/">
			<img src="/images/logo2.png" width="200px" alt="Atropos" />
		</a>

		<?#-- Top Nav -- ?>
		<div class="navbar-collapse nav-main-collapse collapse pull-right">
			<nav class="nav-main mega-menu">
				<ul class="nav nav-pills nav-main scroll-menu" id="topMain">
					<li>
						{{ HTML::link('/', 'Home') }}
					</li>
					<li>
						<a href="#">Blog</a>
					</li>
					<li>
						{{ HTML::link('about-us', 'Over Ons') }}
					</li>
					<li>
						{{ HTML::link('contact', 'Contact') }}
					</li>
					<li>
						{{ HTML::link('faq', 'FAQ') }}
					</li>
					<li class="active">
					@if (Auth::check())
						{{ HTML::link('/logout', 'Uitloggen') }}
					@else
						{{ HTML::link('/login', 'Login') }}
					@endif
					</li>

					<?# -- GLOBAL SEARCH -- ?>
					<li class="search">
						<?# -- search form -- ?>
						<form method="get" action="#" class="input-group pull-right">
							<input type="text" class="form-control" name="k" id="k" value="" placeholder="Zoeken">
							<span class="input-group-btn">
								<button class="btn btn-primary notransition"><i class="fa fa-search"></i></button>
							</span>
						</form>
						<?# -- /search form -- ?>
					</li>
					<?# -- /GLOBAL SEARCH -- ?>

				</ul>
			</nav>
		</div>
		<?# -- /Top Nav -- ?>

	</div>
</header>

<span id="header_shadow"></span>
<?# -- /TOP NAV -- ?>
