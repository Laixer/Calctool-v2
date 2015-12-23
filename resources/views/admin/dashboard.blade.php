@extends('layout.master')

@section('content')
		<div id="wrapper">

			<section class="container text-center">
				<h1 class="text-center">
					<strong>Admin</strong> dashboard
					<span class="subtitle">POWER TO THE ADMIN!</span>
				</h1>
			</section>

			<div id="shop">

				<section class="container">

					<div class="row">

						<div class="col-sm-12 col-md-3"><!-- item -->
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
									<small class="styleColor">{{ \Calctool\Models\User::where('active','=','true')->count() }} actieve gebruiker(s)</small>
								</div>
							</div>
						</div>

						<div class="col-sm-12 col-md-3">
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
									<small class="styleColor">{{ \Calctool\Models\SysMessage::count() }} alert(s)</small>
								</div>
							</div>
						</div>

						<div class="col-sm-12 col-md-3"><!-- item -->
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
									<small class="styleColor">{{ \Calctool\Models\Payment::count() }} transactie(s)</small>
								</div>
							</div>
						</div>

						<div class="col-sm-12 col-md-3"><!-- item -->
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
									<small class="styleColor">{{ \Calctool\Models\Resource::count() }} bestand(en)</small>
								</div>
							</div>
						</div>

						<div class="col-sm-12 col-md-3">
							<div class="item-box item-box-show fixed-box">
								<figure>
									<a class="item-hover" href="/admin/project">
										<span class="overlay color2"></span>
										<span class="inner">
											<span class="block fa fa-pencil fsize60"></span>
											<strong>Projecten</strong>
										</span>
									</a>
									<a href="/admin/resource" class="btn btn-primary add_to_cart"><i class="fa fa-pencil"></i> Projectbeheer</a>
								</figure>
								<div class="item-box-desc">
									<a href="/admin/resource"><h4>Projecten</h4></a>
									<small class="styleColor">{{ \Calctool\Models\Project::count() }} Project(en)</small>
								</div>
							</div>
						</div>

						<div class="col-sm-12 col-md-3">
							<div class="item-box item-box-show fixed-box">
								<figure>
									<a class="item-hover" href="/admin/snailmail">
										<span class="overlay color2"></span>
										<span class="inner">
											<span class="block fa fa-print fsize60"></span>
											<strong>Opdrachten</strong>
										</span>
									</a>
									<a href="/admin/snailmail" class="btn btn-primary add_to_cart"><i class="fa fa-print"></i>Printservice</a>
								</figure>
								<div class="item-box-desc">
									<a href="/admin/snailmail"><h4>Printservice</h4></a>
									<small class="styleColor">{{ \Calctool\Models\OfferPost::whereNull('sent_date')->count() }} te versturen offertes</small>
									<small class="styleColor">{{ \Calctool\Models\InvoicePost::whereNull('sent_date')->count() }} te versturen facturen</small>
								</div>
							</div>
						</div>

						<div class="col-sm-12 col-md-3">
							<div class="item-box item-box-show fixed-box">
								<figure>
									<a class="item-hover" href="/admin/message">
										<span class="overlay color2"></span>
										<span class="inner">
											<span class="block fa fa-bell fsize60"></span>
											<strong>Notificaties</strong>
										</span>
									</a>
									<a href="/admin/message" class="btn btn-primary add_to_cart"><i class="fa fa-bell"></i> Notificaties</a>
								</figure>
								<div class="item-box-desc">
									<a href="/admin/message"><h4>Notificaties</h4></a>
								</div>
							</div>
						</div>

						<div class="col-sm-12 col-md-3">
							<div class="item-box item-box-show fixed-box">
								<figure>
									<a class="item-hover" href="/admin/promo">
										<span class="overlay color2"></span>
										<span class="inner">
											<span class="block fa fa-percent fsize60"></span>
											<strong>Acties</strong>
										</span>
									</a>
									<a href="/admin/promo" class="btn btn-primary add_to_cart"><i class="fa fa-percent"></i> Acties</a>
								</figure>
								<div class="item-box-desc">
									<a href="/admin/promo"><h4>Acties</h4></a>
								</div>
							</div>
						</div>

						<div class="col-sm-12 col-md-3">
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

						<div class="col-sm-12 col-md-3">
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

						<div class="col-sm-12 col-md-3">
							<div class="item-box item-box-show fixed-box">
								<figure>
									<a class="item-hover" href="/admin/log">
										<span class="overlay color2"></span>
										<span class="inner">
											<span class="block fa fa-list-alt fsize60"></span>
											<strong>Applicatie logs</strong>
										</span>
									</a>
									<a href="/admin/log" class="btn btn-primary add_to_cart"><i class="fa fa-list-alt"></i> Logviewer</a>
								</figure>
								<div class="item-box-desc">
									<a href="/admin/log" ><h4>Applicatie logs</h4></a>
								</div>
							</div>
						</div>

					</div>

				</section>

			</div>

		</div>

<?# -- /WRAPPER -- ?>

@stop
