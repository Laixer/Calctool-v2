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
									<a class="item-hover" href="/admin/environment">
										<span class="overlay color2"></span>
										<span class="inner">
											<span class="block fa fa-server fsize60"></span>
											<strong>Omgeving</strong>
										</span>
									</a>
									<a href="/admin/environment" class="btn btn-primary add_to_cart"><i class="fa fa-server"></i> Omgeving</a>
								</figure>
								<div class="item-box-desc">
									<a href="/admin/environment" ><h4>Omgeving</h4></a>
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

					</div>

				</section>

			</div>

		</div>

<?# -- /WRAPPER -- ?>

@stop
