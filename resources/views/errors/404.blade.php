@extends('layout.master')

@section('content')
<div id="wrapper">

	<section class="container">

		<div class="row">

			<div class="col-md-12 text-center">
				<div class="e404">404</div>
				<h2>
					<strong>Oeps</strong>, Deze pagina is niet beschikbaar!
					<span class="subtitle">Ons excuus, de pagina {{ Request::path() }} kon niet worden gevonden.</span>
				</h2>
			</div>

		</div>

	</section>

</div>
@stop
