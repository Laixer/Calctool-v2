@extends('layout.master')

@section('content')

<?php
$envvars = array($_ENV);
$laravel = app();
$version = $laravel::VERSION;

$loadresult = @exec('uptime');
preg_match("/averages?: ([0-9\.]+),[\s]+([0-9\.]+),[\s]+([0-9\.]+)/", $loadresult, $avgs);

$uptime = explode(' up ', $loadresult);
$uptime = explode(',', $uptime[1]);
$uptime = $uptime[0].', '.$uptime[1];

function convert($size) {
	$unit=array('b','kb','mb','gb','tb','pb');
	return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
}
?>
<?# -- WRAPPER -- ?>
<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			<h2><strong>Omgeving {{ App::environment() }}</strong></h2>

			<div class="white-row">
				<h4>Serverstatus</h4>
				<div class="row">
					<div class="col-md-2"><strong>Servernaam</strong></div>
					<div class="col-md-4">{{ gethostname() }}</div>
				</div>
				<div class="row">
					<div class="col-md-2"><strong>Memory used</strong></div>
					<div class="col-md-4">{{ convert(memory_get_usage(true)) }}</div>
				</div>
				<div class="row">
					<div class="col-md-2"><strong>Load Averages</strong></div>
					<div class="col-md-4">{{ $avgs[1] . ' ' . $avgs[2] . ' ' . $avgs[2] }}</div>
				</div>
				<div class="row">
					<div class="col-md-2"><strong>Uptime</strong></div>
					<div class="col-md-4">{{ $uptime }}</div>
				</div>
				<br />
				<h4>Software</h4>
				<div class="row">
					<div class="col-md-2">Upstream</div>
					<div class="col-md-4">{{ date('Y-m-d\TH:i:s') }}</div>
				</div>
				<div class="row">
					<div class="col-md-2">Revisie</div>
					<div class="col-md-4">{{ substr(File::get('../.revision'), 0, 7) }}</div>
				</div>
				<div class="row">
					<div class="col-md-2">Framework</div>
					<div class="col-md-4">{{ $version }}</div>
				</div>
			</div>

			<div class="white-row">
				<h4>Omgevingsvariabelen</h4>
				@foreach ($envvars[0] as $envkey => $envval)
				<?php if (strpos($envkey,'PASSWORD') !== false) continue; ?>
				<div class="row">
					<div class="col-md-2">{{ $envkey }}</div>
					<div class="col-md-4">{{ $envval }}</div>
				</div>
				@endforeach
			</div>

		</div>

	</section>

</div>
<!-- /WRAPPER -->
@stop
