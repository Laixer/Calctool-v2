@extends('layout.master')

@section('content')
<?# -- WRAPPER -- ?>
		<div id="wrapper">

			<section class="container text-center">
				<h1 class="text-center">
					<strong>Admin</strong> dashboard
					<span class="subtitle">BEST PRODUCTS YOU EVER SEEN!</span>
				</h1>
			</section>

			<div id="shop">

				<section class="container">

					<div class="row">

						<div class="col-sm-6 col-md-3"><!-- item -->
							<div class="item-box item-box-show fixed-box">
								<figure>
									<a class="item-hover" href="/admin/user">
										<span class="overlay color2"></span>
										<span class="inner">
											<span class="block fa fa-user fsize60"></span>
											<strong>Gebruikers</strong>
										</span>
									</a>
									<a href="/admin/user" class="btn btn-primary add_to_cart"><i class="fa fa-user"></i> Beheer gebruikers</a>
								</figure>
								<div class="item-box-desc">
									<a href="/admin/user" ><h4>Gebruikers</h4></a>
									<small class="styleColor">{{ User::count() }} actieve gebruikers</small>
								</div>
							</div>
						</div>

						<div class="col-sm-6 col-md-3"><!-- item -->
							<div class="item-box item-box-show fixed-box">
								<figure>
									<a class="item-hover" href="/admin/alert">
										<span class="overlay color2"></span>
										<span class="inner">
											<span class="block fa fa-warning fsize60"></span>
											<strong>Alerts</strong>
										</span>
									</a>
									<a href="/admin/alert" class="btn btn-primary add_to_cart"><i class="fa fa-warning"></i> Beheer alerts</a>

								</figure>
								<div class="item-box-desc">
									<a href="/admin/alert" ><h4>Alerts</h4></a>
									<small class="styleColor">{{ SystemMessage::count() }} alerts</small>
								</div>
							</div>
						</div>

						<div class="col-sm-6 col-md-3"><!-- item -->
							<div class="item-box item-box-show fixed-box">
								<figure>
									<a class="item-hover" href="/admin/payment">
										<span class="overlay color2"></span>
										<span class="inner">
											<span class="block fa fa-usd fsize60"></span>
											<strong>Betalingen</strong>
										</span>
									</a>
									<a href="/admin/payment" class="btn btn-primary add_to_cart"><i class="fa fa-usd"></i> Transacties & Betalingen</a>
								</figure>
								<div class="item-box-desc">
									<a href="/admin/payment"><h4>Betalingen</h4></a>
									<small class="styleColor">{{ Payment::count() }} transacties</small>
								</div>
							</div>
						</div>

						<div class="col-sm-6 col-md-3"><!-- item -->
							<div class="item-box item-box-show fixed-box">
								<figure>
									<a class="item-hover" href="/admin/resource">
										<span class="overlay color2"></span>
										<span class="inner">
											<span class="block fa fa-file fsize60"></span>
											<strong>Bestanden</strong>
										</span>
									</a>
									<a href="/admin/resource" class="btn btn-primary add_to_cart"><i class="fa fa-file"></i> Bestandsbeheer</a>
								</figure>
								<div class="item-box-desc">
									<a href="/admin/resource"><h4>Bestanden</h4></a>
									<small class="styleColor">{{ Resource::count() }} bestanden</small>
								</div>
							</div>
						</div>

						<div class="col-sm-6 col-md-3"><!-- item -->
							<div class="item-box item-box-show fixed-box">
								<figure>
									<a class="item-hover" href="/admin/environment">
										<span class="overlay color2"></span>
										<span class="inner">
											<span class="block fa fa-server fsize60"></span>
											<strong>Server & Config</strong>
										</span>
									</a>
									<a href="/admin/environment" class="btn btn-primary add_to_cart"><i class="fa fa-server"></i> Server & Config</a>
								</figure>
								<div class="item-box-desc">
									<a href="/admin/environment" ><h4>Server & Config</h4></a>
								</div>
							</div>
						</div>

						<div class="col-sm-6 col-md-3"><!-- item -->
							<div class="item-box item-box-show fixed-box">
								<figure>
									<a class="item-hover" href="/admin/phpinfo">
										<span class="overlay color2"></span>
										<span class="inner">
											<span class="block fa fa-wrench fsize60"></span>
											<strong>PHP Info</strong>
										</span>
									</a>
									<a href="/admin/phpinfo" class="btn btn-primary add_to_cart"><i class="fa fa-wrench"></i> PHP configuratie</a>
								</figure>
								<div class="item-box-desc">
									<a href="/admin/phpinfo" ><h4>PHP Info</h4></a>
								</div>
							</div>
						</div>

						<div class="col-sm-6 col-md-3"><!-- item -->
							<div class="item-box item-box-show fixed-box">
								<figure>
									<a class="item-hover" href="/admin/log">
										<span class="overlay color2"></span>
										<span class="inner">
											<span class="block fa fa-list-alt fsize60"></span>
											<strong>Logs</strong>
										</span>
									</a>
									<a href="/admin/log" class="btn btn-primary add_to_cart"><i class="fa fa-list-alt"></i> Logviewer</a>
								</figure>
								<div class="item-box-desc">
									<a href="/admin/log" ><h4>Log</h4></a>
								</div>
							</div>
						</div>

						<div class="col-sm-6 col-md-3"><!-- item -->
							<div class="item-box item-box-show fixed-box">
								<figure>
									<a class="item-hover" href="/admin/support">
										<span class="overlay color2"></span>
										<span class="inner">
											<span class="block fa fa-question-circle fsize60"></span>
											<strong>Support & Ondersteuning</strong>
										</span>
									</a>
									<a href="/admin/support" class="btn btn-primary add_to_cart"><i class="fa fa-question-circle"></i> Ticketsysteem</a>
								</figure>
								<div class="item-box-desc">
									<a href="/admin/support" ><h4>Support & Ondersteuning</h4></a>
									<small class="styleColor">{{ Resource::count() }} nieuwe tickets</small>
									<small class="styleColor">{{ Resource::count() }} open tickets</small>
								</div>
							</div>
						</div>

					</div>

				</section>

			</div>

		</div>

<?# -- /WRAPPER -- ?>

@stop
