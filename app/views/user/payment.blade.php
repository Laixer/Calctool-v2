@extends('layout.master')

@section('content')
<?# -- WRAPPER -- ?>
<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			<h2><strong>Abonnementskeuze</strong></h2>
			{{ Form::open() }}

			<div class="row">
				<div class="col-md-1"></div>
				<div class="col-md-1"><strong>Periode</strong></div>
				<div class="col-md-1"><strong>Bedrag</strong></div>
				<div class="col-md-1"><strong>Korting</strong></div>
				<div class="col-md-2"><strong>Kortingsbedrag</strong></div>
				<div class="col-md-2"><strong>Te betalen</strong></div>
			</div>
			<div class="row">
				<div class="col-md-1"><input type="radio" name="payoption" value="1" /></div>
				<div class="col-md-1">Maand</div>
				<div class="col-md-1">&euro; 29,95</div>
				<div class="col-md-1">0%</div>
				<div class="col-md-2">&euro; 0,00</div>
				<div class="col-md-2"><strong>&euro; 29,95</strong></div>
			</div>
			<div class="row">
				<div class="col-md-1"><input type="radio" name="payoption" value="4" /></div>
				<div class="col-md-1">Kwartaal</div>
				<div class="col-md-1">&euro; 89,85</div>
				<div class="col-md-1">10%</div>
				<div class="col-md-2">&euro; 8,99</div>
				<div class="col-md-2"><strong>&euro; 80,85</strong></div>
			</div>
			<div class="row">
				<div class="col-md-1"><input type="radio" name="payoption" value="6" /></div>
				<div class="col-md-1">Half jaar</div>
				<div class="col-md-1">&euro; 179,70</div>
				<div class="col-md-1">15%</div>
				<div class="col-md-2">&euro; 26,95</div>
				<div class="col-md-2"><strong>&euro; 152,75</strong></div>
			</div>
			<div class="row">
				<div class="col-md-1"><input type="radio" name="payoption" value="12" /></div>
				<div class="col-md-1">Jaar</div>
				<div class="col-md-1">&euro; 359.40</div>
				<div class="col-md-1">20%</div>
				<div class="col-md-2">&euro; 71,89</div>
				<div class="col-md-2"><strong>&euro; 287,52</strong></div>
			</div>
			<br>
			<div class="row">
				<div class="col-md-12">
					<button class="btn btn-primary"><i class="fa fa-check"></i>Bevestigen</button>
				</div>
			</div>
			{{ Form::close() }}

		</div>

	</section>

</div>
<!-- /WRAPPER -->
@stop
