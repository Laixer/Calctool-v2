@extends('layout.master')

@section('content')
<?# -- WRAPPER -- ?>
<div id="wrapper">

	<section id="contact" class="container">

		<div class="row">

			<?# -- FORM -- ?>
			<div class="col-md-8">

				<h2><strong>Contact</strong></h2>

				<form class="white-row" action="php/contact.php" method="post">
					<div class="row">
						<div class="form-group">
							<div class="col-md-6">
								<label>Naam *</label>
								<input type="text" value="" data-msg-required="Please enter your name." maxlength="100" class="form-control" name="name" id="name">
							</div>
							<div class="col-md-6">
								<label>E-mailadres *</label>
								<input type="email" value="" data-msg-required="Please enter your email address." data-msg-email="Please enter a valid email address." maxlength="100" class="form-control" name="email" id="email">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group">
							<div class="col-md-12">
								<label>Onderwerp</label>
								<input type="text" value="" data-msg-required="Please enter the subject." maxlength="100" class="form-control" name="subject" id="subject">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group">
							<div class="col-md-12">
								<label>Bericht *</label>
								<textarea maxlength="5000" data-msg-required="Please enter your message." rows="10" class="form-control" name="message" id="message"></textarea>
							</div>
						</div>
					</div>

					<br />

					<div class="row">
						<div class="col-md-12">
							<input type="submit" value="Verstuur" class="btn btn-primary btn-lg" data-loading-text="Laden...">
						</div>
					</div>
				</form>

			</div>
			<?# -- /FORM -- ?>


			<?# -- INFO -- ?>
			<div class="col-md-4">

				<h2>Visit Us</h2>

				<div class="white-row">
					<div id="gmap"><!-- google map --></div>
					<script type="text/javascript">
						var	$googlemap_latitude 	= -37.812344,
							$googlemap_longitude	= 144.968900,
							$googlemap_zoom			= 13;
					</script>

					<div class="divider white half-margins"><!-- divider -->
						<i class="fa fa-star"></i>
					</div>

					<p class="nomargin-bottom">
						<span class="block"><strong><i class="fa fa-map-marker"></i> Address:</strong> Street Name, City Name, Country</span>
						<span class="block"><strong><i class="fa fa-phone"></i> Phone:</strong> 1800-555-1234</span>
						<span class="block"><strong><i class="fa fa-envelope"></i> Email:</strong> <a href="mailto:mail@yourdomain.com">mail@yourdomain.com</a></span>
					</p>

				</div>

			</div>
			<?# -- /INFO -- ?>

		</div>

	</section>

</div>
<?# -- /WRAPPER -- ?>
@stop
