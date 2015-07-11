@extends('layout.master')

@section('content')
<?# -- WRAPPER -- ?>
<div id="wrapper">

	<div id="shop">

		<section class="container">

			<div class="row">

				<!-- REGISTER -->
				<div class="col-md-6">

					<h2>Nieuwe <strong>Wachtwoord</strong></h2>

					{{ Form::open(array('class' => 'white-row')) }}

						@if(Session::get('success'))
						<div class="alert alert-success">
							<i class="fa fa-check-circle"></i>
							<strong>{{ Session::get('success') }}</strong>
						</div>
						@endif

						<?# -- alert failed -- ?>
						@if($errors->any())
						<div class="alert alert-danger">
							<i class="fa fa-frown-o"></i>
							@foreach ($errors->all() as $error)
								{{ $error }}
							@endforeach
						</div>
						@endif

						<div class="row">
							<div class="form-group">
								<div class="col-md-12">
									<label></label>
									{{ Form::label('secret', 'Wachtwoord') }}
									{{ Form::password('secret', array('class' => 'form-control')) }}
								</div>
							</div>
						</div>
						<div class="row">
							<div class="form-group">
								<div class="col-md-12">
									<label></label>
									{{ Form::label('secret_confirmation', 'Herhaal wachtwoord') }}
									{{ Form::password('secret_confirmation', array('class' => 'form-control')) }}
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<input type="submit" value="Opslaan" class="btn btn-primary pull-right push-bottom" data-loading-text="Loading...">
							</div>
						</div>

					{{ Form::close() }}

				</div>
				<!-- /REGISTER -->

				<!-- WHY? -->
				<div class="col-md-6">

					<h2>&nbsp;</h2>

					<div class="white-row">

						<h4>Registration is fast, easy, and free.</h4>

						<p>Once you're registered, you can:</p>
						<ul class="list-icon check">
							<li>Buy, sell, and interact with other members.</li>
							<li>Save your favorite searches and get notified.</li>
							<li>Watch the status of up to 200 items.</li>
							<li>View your Atropos information from any computer in the world.</li>
							<li>Connect with the Atropos community.</li>
						</ul>

						<hr class="half-margins">

						<p>
							Already have an account?
							<a href="/login">Click to Sign In</a>
						</p>
					</div>

					</div>
				<!-- /WHY? -->

			</div>

			<div class="white-row">
						<h4>Contact Customer Support</h4>
						<p>
							If you're looking for more help or have a question to ask, please <a href="contact-us.html">contact us</a>.
						</p>
					</div>

		</section>

	</div>
</div>
<?# -- /WRAPPER -- ?>
@stop
