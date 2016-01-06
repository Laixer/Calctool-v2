@extends('layout.master')

@section('content')

<?php
$envvars = array($_ENV);
$laravel = app();
$version = $laravel::VERSION;
$db_version = DB::select(DB::raw("SELECT version()"));
if ($db_version)
	$db_version = $db_version[0]->version;
else
	$db_version = '-';

$loadresult = @exec('uptime');
preg_match("/averages?: ([0-9\.]+),[\s]+([0-9\.]+),[\s]+([0-9\.]+)/", $loadresult, $avgs);

$uptime = explode(' up ', $loadresult);
$uptime = explode(',', $uptime[1]);
$uptime = $uptime[0].', '.$uptime[1];

function convert($size) {
	$unit=array('b','kb','mb','gb','tb','pb');
	return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
}

$rev_timestamp = '-';
$rev = '-';
if (File::exists('../.revision')) {
	$rev_timestamp = File::lastModified('../.revision');
	$rev = substr(File::get('../.revision'), 0, 7);
}


?>
<?# -- WRAPPER -- ?>
<div id="wrapper">

	<section class="container">

		<div class="col-md-12">

			<div>
			<ol class="breadcrumb">
			  <li><a href="/">Home</a></li>
			  <li><a href="/admin">Admin CP</a></li>
			  <li class="active">Server & Config</li>
			</ol>
			<div>
			<br />

			<h2><strong>Omgeving {{ App::environment() }}</strong></h2>

			<div class="white-row">
				<h4>Serverstatus</h4>
				<div class="row">
					<div class="col-md-2"><strong>Servernaam</strong></div>
					<div class="col-md-10">{{ gethostname() }}</div>
				</div>
				<div class="row">
					<div class="col-md-2"><strong>Memory used</strong></div>
					<div class="col-md-10">{{ convert(memory_get_usage(true)) }}</div>
				</div>
				<div class="row">
					<div class="col-md-2"><strong>Load Averages</strong></div>
					<div class="col-md-10">{{ $avgs[1] . ' ' . $avgs[2] . ' ' . $avgs[2] }}</div>
				</div>
				<div class="row">
					<div class="col-md-2"><strong>Uptime</strong></div>
					<div class="col-md-10">{{ $uptime }}</div>
				</div>
				<div class="row">
					<div class="col-md-2"><strong>Date offset</strong></div>
					<div class="col-md-10">{{ date('T') }}</div>
				</div>
				<br />
				<h4>Software</h4>
				<div class="row">
					<div class="col-md-2">Upstream</div>
					<div class="col-md-10">{{ $rev_timestamp == '-' ? '-' : date('Y-m-d H:i:s', $rev_timestamp) }}</div>
				</div>
				<div class="row">
					<div class="col-md-2">Versie</div>
					<div class="col-md-10">{{ $_ENV['CT_VERSION'] }}</div>
				</div>
				<div class="row">
					<div class="col-md-2">Revisie</div>
					<div class="col-md-10">{{ $rev }}</div>
				</div>
				<div class="row">
					<div class="col-md-2">Framework</div>
					<div class="col-md-10">{{ $version }}</div>
				</div>
				<div class="row">
					<div class="col-md-2">Database</div>
					<div class="col-md-10">{{ $db_version }}</div>
				</div>
			</div>

			<div class="white-row">
				<h4>Omgevingsvariabelen</h4>
				@foreach ($envvars[0] as $envkey => $envval)
				<?php if (strpos($envkey,'PASSWORD') !== false) continue; ?>
				<div class="row">
					<div class="col-md-2">{{ $envkey }}</div>
					<div class="col-md-10">{{ $envval }}</div>
				</div>
				@endforeach
			</div>

		</div>

	</section>

</div>
s@stop
