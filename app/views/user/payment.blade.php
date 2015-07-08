@extends('layout.master')

@section('content')
<?# -- WRAPPER -- ?>
<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			<h2><strong>Kope kope kope</strong></h2>
			{{ Form::open() }}

			<div class="row">
				<div class="col-md-1"></div>
				<div class="col-md-2"><strong>Periode</strong></div>
				<div class="col-md-2"><strong>Bedrag</strong></div>
			</div>
			<div class="row">
				<div class="col-md-1"><input type="radio" name="payoption" value="1" /></div>
				<div class="col-md-2">Maand</div>
				<div class="col-md-2">&euro; 22,50</div>
			</div>
			<div class="row">
				<div class="col-md-1"><input type="radio" name="payoption" value="4" /></div>
				<div class="col-md-2">Kwartaal</div>
				<div class="col-md-2">&euro; 80</div>
			</div>
			<div class="row">
				<div class="col-md-1"><input type="radio" name="payoption" value="6" /></div>
				<div class="col-md-2">Half jaar</div>
				<div class="col-md-2">&euro; 110</div>
			</div>
			<div class="row">
				<div class="col-md-1"><input type="radio" name="payoption" value="12" /></div>
				<div class="col-md-2">Jaar</div>
				<div class="col-md-2">&euro; 200</div>
			</div>
			<div class="row">
				<div class="col-md-1"><input type="radio" name="payoption" value="13" checked /></div>
				<div class="col-md-2">Goudse Kaas</div>
				<div class="col-md-2">&euro; 6.000</div>
			</div><br />

			<div class="row">
				<div class="col-md-12">
					<button class="btn btn-primary"><i class="fa fa-check"></i> Koop dan</button>
				</div>
			</div>
			{{ Form::close() }}

		</div>

	</section>

</div>
<!-- /WRAPPER -->
@stop
