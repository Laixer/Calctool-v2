@extends('layout.master')

@section('content')
<?# -- WRAPPER -- ?>
<div id="wrapper">

	<section id="contact" class="container">

		<div class="row">

			<?# -- FORM -- ?>
			<div class="col-md-8">

				<h2>Drop us a line or just say <strong><em>Hello!</em></strong></h2>

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

				<h2>Details</h2>

				<p>
					<span class="block"><strong><i class="fa fa-envelope"></i> Email:</strong> <a href="mailto:tech@calctool.nl">tech@calctool.nl</a></span>
					<span class="block"><strong><i class="fa fa-wrench"></i> Versie:</strong> 0.6-{{ substr(File::get('../.revision'), 0, 7) }}</span>
					<span class="block"><strong><i class="fa fa-code"></i> Upstream:</strong> {{ date('Y-m-d\TH:i:s') }}</span>
					<span class="block"><strong><i class="fa fa-server"></i> Server:</strong> {{ gethostname() }}</span>
				</p>

			</div>
			<?# -- /INFO -- ?>

		</div>

	</section>

</div>
<?# -- /WRAPPER -- ?>
@stop
