@extends('layout.master')

@section('content')
<?# -- WRAPPER -- ?>
<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			<h2><strong>Abonnementskeuze</strong></h2>
			<form method="POST" action="" accept-charset="UTF-8">
			{!! csrf_field() !!}

			<div class="white-row">
			<div class="row">
				<div class="col-md-1"></div>
				<div class="col-md-2"><strong>Periode</strong></div>
				<div class="col-md-2"><strong>Bedrag</strong></div>
				<div class="col-md-2"><strong>Korting</strong></div>
				<div class="col-md-2"><strong>Te betalen bedrag</strong></div>
				<div class="col-md-2"><strong>Kortingsbedrag</strong></div>
			</div>
			<div class="row">
				<div class="col-md-1"><input type="radio" name="payoption" value="1" checked /></div>
				<div class="col-md-2">Maand</div>
				<div class="col-md-2">&euro; 29,95</div>
				<div class="col-md-2">0%</div>
				<div class="col-md-2"><strong>&euro; 29,95</strong></div>
				<div class="col-md-2">&euro; 0,00</div>
			</div>
			<div class="row">
				<div class="col-md-1"><input type="radio" name="payoption" value="3" /></div>
				<div class="col-md-2">Kwartaal</div>
				<div class="col-md-2">&euro; 89,85</div>
				<div class="col-md-2">10%</div>
				<div class="col-md-2"><strong>&euro; 80,85</strong></div>
				<div class="col-md-2">&euro; 8,99</div>
			</div>
			<div class="row">
				<div class="col-md-1"><input type="radio" name="payoption" value="6" /></div>
				<div class="col-md-2">Half jaar</div>
				<div class="col-md-2">&euro; 179,70</div>
				<div class="col-md-2">15%</div>
				<div class="col-md-2"><strong>&euro; 152,75</strong></div>
				<div class="col-md-2">&euro; 26,95</div>
			</div>
			<div class="row">
				<div class="col-md-1"><input type="radio" name="payoption" value="12" /></div>
				<div class="col-md-2">Jaar</div>
				<div class="col-md-2">&euro; 359.40</div>
				<div class="col-md-2">20%</div>
				<div class="col-md-2"><strong>&euro; 287,52</strong></div>
				<div class="col-md-2">&euro; 71,89</div>
			</div>
			<br>
			<div class="row">
				<div class="col-md-12">
					<strong>Bedragen zijn exclusief BTW.</strong>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-md-12">
					<button class="btn btn-primary"><i class="fa fa-check"></i>Bevestigen</button>
				</div>
			</div>
			</div>
			</form>
		</div>

	</section>

</div>
<!-- /WRAPPER -->
@stop
