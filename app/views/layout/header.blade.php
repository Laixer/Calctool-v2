<?# -- TOP NAV -- ?>
<header id="topNav" class="topHead">
	<div class="container">

		<?# -- Mobile Menu Button -- ?>
		<button class="btn btn-mobile" data-toggle="collapse" data-target=".nav-main-collapse">
			<i class="fa fa-bars"></i>
		</button>

		<?# -- Logo text or image -- ?>
		<a class="logo" href="/">
			<img src="/images/logo2.png" width="200px" alt="Calctool" />
		</a>

		<?php if (Auth::check()) Auth::user()->touch();	?>

		<?#-- Top Nav -- ?>
		<div class="navbar-collapse nav-main-collapse collapse pull-right">
			<nav class="nav-main mega-menu">
				<ul class="nav nav-pills nav-main scroll-menu" id="topMain">
					<li>
						{{ HTML::link('/', 'Home') }}
					</li>
					<li>
						{{ HTML::link('help', 'Help') }}
					</li>
					<li>
						{{ HTML::link('myaccount', 'Mijn account') }}
					</li>
					<li>
						{{ HTML::link('about', 'Over ons') }}
					</li>
					@if (Auth::check() && Auth::user()->isAdmin())
					<li>
						{{ HTML::link('admin', 'Admin CP') }}
					</li>
					@endif
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

<?# -- /TOP NAV -- ?>
