@extends('layout.master')

@section('content')
<?# -- WRAPPER -- ?>
<link rel="stylesheet" href="/plugins/flipclock/flipclock.css">
<script src="/plugins/flipclock/flipclock.js"></script>
<script type="text/javascript">
	var clock;
	$(document).ready(function() {
		var currentDate = new Date();
		var futureDate  = new Date(2015, 6, 20);
		var diff = futureDate.getTime() / 1000 - currentDate.getTime() / 1000;
		clock = $('.clock').FlipClock(diff, {
			clockFace: 'DailyCounter',
			countdown: true,
			language: 'nl'
		});
	});
</script>
<div id="wrapper">

	<section class="container">

		<div class="row">

			<div class="col-md-9">
				<h2>
					<strong>Countdown</strong> tot de deadline
				</h2>

				<div class="clock" style="margin:2em;"></div>
				<div class="message"></div>
			</div>

		</div>

	</section>

</div>
<?# -- /WRAPPER -- ?>
@stop

