@extends('layout.master')

@section('content')
<div id="wrapper">

	<section class="container">

		<div class="row">

			<div class="col-md-9">
				<h2>
					<strong>Oops</strong>, Deze pagina is niet beschikbaar!
					<span class="subtitle">Ons excuus, de pagina {{ $url }} kon niet worden gevonden.</span>
				</h2>


				<div class="e404">404</div>
			</div>

			<aside class="col-md-3">

				<h3>ZOEKEN</h3>
				<div class="row">
					<div class="col-md-12">
						<form method="get" action="#" class="input-group">
							<input type="text" class="form-control" name="s" id="s" value="" placeholder="zoeken..." />
							<span class="input-group-btn">
								<button class="btn btn-primary"><i class="fa fa-search"></i></button>
							</span>
						</form>
					</div>
				</div>

				<h4>NAVIGATIE</h4>
				<ul class="nav nav-list">
					<li><a href="/"><i class="fa fa-circle-o"></i> Home</a></li>
					<li><a href="/about"><i class="fa fa-circle-o"></i> Over Ons</a></li>
					<li><a href="/about"><i class="fa fa-circle-o"></i> Contact</a></li>
				</ul>

			</aside>

		</div>

	</section>

</div>
@stop
