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
							<div class="item-box fixed-box">
								<figure>
									<a class="item-hover" href="/admin/user">
										<span class="overlay color2"></span>
										<span class="inner">
											<span class="block fa fa-plus fsize20"></span>
											<strong>PRODUCT</strong> DETAIL
										</span>
									</a>
									<a href="/admin/user" class="btn btn-primary add_to_cart"><i class="fa fa-user"></i> Beheer gebruikers</a>
									<img class="img-responsive" src="assets/images/demo/shop/9.jpg" width="260" height="260" alt="">
								</figure>
								<div class="item-box-desc">
									<h4>Gebruikers</h4>
									<small class="styleColor">180 actieve gebruikers</small>
								</div>
							</div>
						</div>

						<div class="col-sm-6 col-md-3"><!-- item -->
							<div class="item-box fixed-box">
								<figure>
									<a class="item-hover" href="/admin/alert">
										<span class="overlay color2"></span>
										<span class="inner">
											<span class="block fa fa-plus fsize20"></span>
											<strong>PRODUCT</strong> DETAIL
										</span>
									</a>
									<a href="/admin/alert" class="btn btn-primary add_to_cart"><i class="fa fa-warning"></i> Beheer alerts</a>
									<img class="img-responsive" src="assets/images/demo/shop/2.jpg" width="260" height="260" alt="">
								</figure>
								<div class="item-box-desc">
									<h4>Alerts</h4>
								</div>
							</div>
						</div>

						<div class="col-sm-6 col-md-3"><!-- item -->
							<div class="item-box fixed-box">
								<figure>
									<a class="item-hover" href="shop-product-full-width.html">
										<span class="overlay color2"></span>
										<span class="inner">
											<span class="block fa fa-plus fsize20"></span>
											<strong>PRODUCT</strong> DETAIL
										</span>
									</a>
									<a href="shop-cart.html?action=cart_add&amp;product_id=1&amp;product_color=red&amp;product_size=l&amp;product_qty=1" class="btn btn-primary add_to_cart"><i class="fa fa-shopping-cart"></i> ADD TO CART</a>
									<img class="img-responsive" src="assets/images/demo/shop/3.jpg" width="260" height="260" alt="">
								</figure>
								<div class="item-box-desc">
									<h4>James Bond Watch - Titanium Case</h4>
									<small class="styleColor"><span>$180</span> $150</small>
								</div>
							</div>
						</div>

						<div class="col-sm-6 col-md-3"><!-- item -->
							<div class="item-box fixed-box">
								<figure>
									<a class="item-hover" href="shop-product-full-width.html">
										<span class="overlay color2"></span>
										<span class="inner">
											<span class="block fa fa-plus fsize20"></span>
											<strong>PRODUCT</strong> DETAIL
										</span>
									</a>
									<a href="shop-cart.html?action=cart_add&amp;product_id=1&amp;product_color=red&amp;product_size=l&amp;product_qty=1" class="btn btn-primary add_to_cart"><i class="fa fa-shopping-cart"></i> ADD TO CART</a>
									<img class="img-responsive" src="assets/images/demo/shop/4.jpg" width="260" height="260" alt="">
								</figure>
								<div class="item-box-desc">
									<h4>Pink Lady Shoes</h4>
									<small class="styleColor"><span>$180</span> $150</small>
								</div>
							</div>
						</div>

					</div>

				</section>

			</div>

		</div>

<?# -- /WRAPPER -- ?>

@stop
